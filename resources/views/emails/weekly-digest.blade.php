<x-mail::message>
# Next Frontiers Programme — Weekly Completion Digest

**Officer:** {{ $officerName }}  
**Date Generated:** {{ now()->format('l, d F Y') }}  

The following deliverables have been completed this week and are submitted for **SPSM Acceptance Standard Review**:

<x-mail::table>
| Activity Code | Cohort | Deliverable Title & Result | Proof of Evidence |
| :--- | :--- | :--- | :--- |
@foreach($activities as $activity)
| **{{ $activity->code }}** | {{ $activity->cohort }} | **{{ $activity->title }}**<br><small>{{ Str::limit($activity->deliverable_result, 70) }}</small> | [Open Proof]({{ $activity->evidence_url }}) |
@endforeach
</x-mail::table>

<x-mail::panel>
**Action Requested:** SPSM is kindly requested to review the acceptance standard for each deliverable above and record decisions in the programme portal (VERIFIED, RETURNED FOR CORRECTION, or OPEN—ESCALATED).
</x-mail::panel>

<x-mail::button :url="route('tracker.index')">
Open Review Desk
</x-mail::button>

Regards,  
Next Frontiers Incubator Operations Desk
</x-mail::message>