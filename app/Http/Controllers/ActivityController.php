<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\User;
use App\Mail\WeeklyDigestNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ActivityController extends Controller
{
    /**
     * Resolve current user ID or fallback to the first database user.
     */
    private function resolveActorId(): int
    {
        return auth()->id() ?? User::first()?->id ?? 1;
    }

    public function index(Request $request)
    {
        // 1. Available Years & Active Year Selection
        $availableYears = Activity::distinct()->pluck('year')->sortDesc();
        if ($availableYears->isEmpty()) {
            $availableYears = collect([2026, 2027]);
        }

        $selectedYear = (int) $request->get('year', $availableYears->first());
        $selectedCohort = $request->get('cohort');

        // 2. Executive Rollup Statistics
        $total = Activity::where('year', $selectedYear)->count();
        $verified = Activity::where('year', $selectedYear)->where('status', Activity::STATUS_VERIFIED)->count();
        $returned = Activity::where('year', $selectedYear)->where('status', Activity::STATUS_RETURNED)->count();
        $escalated = Activity::where('year', $selectedYear)->where('status', Activity::STATUS_ESCALATED)->count();

        $statistics = [
            'total' => $total,
            'verified' => $verified,
            'returned' => $returned,
            'escalated' => $escalated,
            'percent_complete' => $total > 0 ? round(($verified / $total) * 100, 1) : 0,
        ];

        // 3. Cohorts List for Current Year
        $cohorts = Activity::where('year', $selectedYear)->distinct()->pluck('cohort');

        // 4. Analytics Engine: Cohort Completion Velocity
        $cohortAnalytics = Activity::where('year', $selectedYear)
            ->select('cohort')
            ->selectRaw('COUNT(*) as total')
            ->selectRaw("COUNT(CASE WHEN status = 'verified' THEN 1 END) as verified")
            ->selectRaw("COUNT(CASE WHEN status = 'returned_for_correction' THEN 1 END) as returned")
            ->selectRaw("COUNT(CASE WHEN status = 'open_escalated' THEN 1 END) as escalated")
            ->groupBy('cohort')
            ->get()
            ->map(function ($c) {
                $c->percent = $c->total > 0 ? round(($c->verified / $c->total) * 100) : 0;
                return $c;
            });

        // 5. Analytics Engine: First-Pass QA Yield (Passed with zero returns/remediations)
        $totalReviewed = Activity::where('year', $selectedYear)
            ->whereIn('status', [Activity::STATUS_VERIFIED, Activity::STATUS_RETURNED, Activity::STATUS_ESCALATED])
            ->count();

        $firstPassVerified = Activity::where('year', $selectedYear)
            ->where('status', Activity::STATUS_VERIFIED)
            ->whereNull('corrected_on')
            ->count();

        $firstPassRate = $totalReviewed > 0 ? round(($firstPassVerified / $totalReviewed) * 100, 1) : 100;

        // 6. Primary Deliverables Feed
        $activities = Activity::with(['officer', 'reviewer'])
            ->where('year', $selectedYear)
            ->when($selectedCohort, fn($q) => $q->where('cohort', $selectedCohort))
            ->orderBy('completed_on', 'desc')
            ->paginate(15);

        // 7. Operational Action Counts
        $pendingDispatchCount = Activity::whereNull('weekly_digest_sent_at')->count();
        $actionRequiredCount = Activity::where('status', Activity::STATUS_RETURNED)
            ->whereNull('corrected_on')
            ->count();

        return view('tracker.index', compact(
            'statistics',
            'activities',
            'cohorts',
            'selectedYear',
            'selectedCohort',
            'availableYears',
            'cohortAnalytics',
            'firstPassRate',
            'pendingDispatchCount',
            'actionRequiredCount'
        ));
    }

    // Officer: Log new activity & store evidence
    public function store(Request $request)
    {
        $validated = $request->validate([
            'year' => 'required|digits:4',
            'cohort' => 'required|string|max:50',
            'code' => 'required|string|unique:activities,code',
            'title' => 'required|string|max:255',
            'completed_on' => 'required|date',
            'deliverable_result' => 'nullable|string',
            'evidence_file' => 'nullable|file|max:25600',
            'evidence_link' => 'nullable|url',
        ]);

        $filePath = null;
        if ($request->hasFile('evidence_file')) {
            $folder = "evidence/{$validated['year']}/" . preg_replace('/[^A-Za-z0-9_-]/', '_', $validated['cohort']);
            $filePath = $request->file('evidence_file')->store($folder, 'public');
        }

        Activity::create([
            'year' => $validated['year'],
            'cohort' => $validated['cohort'],
            'officer_id' => $this->resolveActorId(),
            'code' => $validated['code'],
            'title' => $validated['title'],
            'completed_on' => $validated['completed_on'],
            'deliverable_result' => $validated['deliverable_result'],
            'evidence_file_path' => $filePath,
            'evidence_external_link' => $validated['evidence_link'],
            'status' => Activity::STATUS_UNDER_REVIEW,
        ]);

        return back()->with('success', 'Deliverable recorded and queued for review.');
    }

    // Officer: Dispatch weekly completion note to SPSM & BSJ
    public function dispatchWeeklyDigest()
    {
        $pendingActivities = Activity::whereNull('weekly_digest_sent_at')->get();

        if ($pendingActivities->isEmpty()) {
            return back()->with('info', 'No pending deliverables waiting for weekly dispatch.');
        }

        $recipients = User::whereIn('role', ['spsm', 'bsj'])->pluck('email')->toArray();
        if (empty($recipients)) {
            $recipients = ['spsm@programme.org', 'bsj@programme.org'];
        }

        $officerName = auth()->user()->name ?? 'Programme Officer';

        try {
            Mail::to($recipients)->send(new WeeklyDigestNotification($pendingActivities, $officerName));
        } catch (\Throwable $e) {
            Log::warning('Weekly digest notification could not be delivered via SMTP: ' . $e->getMessage());
        }

        Activity::whereIn('id', $pendingActivities->pluck('id'))
            ->update(['weekly_digest_sent_at' => now()]);

        return back()->with('success', "Dispatched weekly note for {$pendingActivities->count()} deliverables to SPSM & BSJ.");
    }

    // SPSM: Verification & Decision
    public function review(Request $request)
    {
        $validated = $request->validate([
            'activity_id' => 'required|exists:activities,id',
            'status' => 'required|in:verified,returned_for_correction,open_escalated',
            'review_comments' => 'nullable|string',
            'correction_deadline' => 'required_if:status,returned_for_correction|nullable|date',
        ]);

        $activity = Activity::findOrFail($validated['activity_id']);

        $activity->update([
            'status' => $validated['status'],
            'reviewer_id' => $this->resolveActorId(),
            'review_comments' => $validated['review_comments'] ?? null,
            'correction_deadline' => $validated['status'] === Activity::STATUS_RETURNED
                ? $validated['correction_deadline']
                : null,
            'corrected_on' => $validated['status'] === Activity::STATUS_VERIFIED
                ? ($activity->corrected_on ?? now()->toDateString())
                : null,
        ]);

        return back()->with('success', "Decision recorded: {$activity->code} status set to " . strtoupper(str_replace('_', ' ', $activity->status)));
    }

    // Officer: Resubmit corrected work
    public function submitCorrection(Request $request)
    {
        $request->validate([
            'activity_id' => 'required|exists:activities,id',
            'evidence_file' => 'nullable|file|max:25600',
            'evidence_link' => 'nullable|url',
        ]);

        $activity = Activity::findOrFail($request->activity_id);

        if ($request->hasFile('evidence_file')) {
            $folder = "evidence/{$activity->year}/" . preg_replace('/[^A-Za-z0-9_-]/', '_', $activity->cohort);
            $activity->evidence_file_path = $request->file('evidence_file')->store($folder, 'public');
        } elseif ($request->filled('evidence_link')) {
            $activity->evidence_external_link = $request->evidence_link;
        }

        $activity->corrected_on = now()->toDateString();
        $activity->status = Activity::STATUS_UNDER_REVIEW;
        $activity->save();

        return back()->with('success', "Remediation for {$activity->code} submitted for re-evaluation.");
    }
}