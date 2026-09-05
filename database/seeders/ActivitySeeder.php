<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ActivitySeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Core Stakeholders
        $officer = User::firstOrCreate(
            ['email' => 'officer@programme.org'],
            [
                'name' => 'Sarah Chen (Field Officer)',
                'role' => 'officer',
                'password' => Hash::make('password'),
            ]
        );

        $spsm = User::firstOrCreate(
            ['email' => 'spsm@programme.org'],
            [
                'name' => 'David Kim (SPSM Manager)',
                'role' => 'spsm',
                'password' => Hash::make('password'),
            ]
        );

        User::firstOrCreate(
            ['email' => 'bsj@programme.org'],
            [
                'name' => 'Linda Taylor (BSJ Observer)',
                'role' => 'bsj',
                'password' => Hash::make('password'),
            ]
        );

        // 2. 2026 Programme Deliverables
        // Verified Deliverable (100% Complete)
        Activity::create([
            'year' => 2026,
            'cohort' => 'Cohort 1',
            'officer_id' => $officer->id,
            'code' => 'NF-2026-001',
            'title' => 'Incubator Intake Orientation & Baseline Diagnostics',
            'completed_on' => '2026-02-14',
            'deliverable_result' => '15 out of 15 venture teams attended baseline diagnostic survey.',
            'evidence_external_link' => 'https://drive.google.com/drive/folders/2026_Cohort1_001',
            'weekly_digest_sent_at' => '2026-02-16 16:00:00',
            'status' => Activity::STATUS_VERIFIED,
            'reviewer_id' => $spsm->id,
            'review_comments' => 'Diagnostics rubric fully satisfied. Verified.',
        ]);

        // Returned for Correction Deliverable (Needs Remediation - Active Deadline)
        Activity::create([
            'year' => 2026,
            'cohort' => 'Cohort 1',
            'officer_id' => $officer->id,
            'code' => 'NF-2026-002',
            'title' => 'Tranche 1 Small Grants Bank Details & Sign-Offs',
            'completed_on' => '2026-03-02',
            'deliverable_result' => 'All 15 bank accounts collected; notarization missing on 2 forms.',
            'evidence_external_link' => 'https://drive.google.com/drive/folders/2026_Cohort1_002',
            'weekly_digest_sent_at' => '2026-03-05 17:15:00',
            'status' => Activity::STATUS_RETURNED,
            'reviewer_id' => $spsm->id,
            'correction_deadline' => '2026-09-20',
            'review_comments' => 'Please upload notarized sign-off stamps for Teams #4 and #11.',
        ]);

        // Overdue Returned Deliverable (Triggers Red Alert Badge)
        Activity::create([
            'year' => 2026,
            'cohort' => 'Cohort 2',
            'officer_id' => $officer->id,
            'code' => 'NF-2026-003',
            'title' => 'Equipment Procurement Receipts Audit',
            'completed_on' => '2026-04-10',
            'deliverable_result' => 'Receipts uploaded from 10 hardware founders.',
            'evidence_external_link' => 'https://drive.google.com/drive/folders/2026_Cohort2_003',
            'weekly_digest_sent_at' => '2026-04-12 11:00:00',
            'status' => Activity::STATUS_RETURNED,
            'reviewer_id' => $spsm->id,
            'correction_deadline' => '2026-05-01', // Past deadline = Overdue
            'review_comments' => 'Receipts lack VAT breakdown. Resubmission mandatory.',
        ]);

        // Escalated Deliverable
        Activity::create([
            'year' => 2026,
            'cohort' => 'Cohort 2',
            'officer_id' => $officer->id,
            'code' => 'NF-2026-004',
            'title' => 'Independent Lab Safety Inspection Protocol',
            'completed_on' => '2026-07-22',
            'deliverable_result' => 'Inspector flagged ventilation failure in prototyping lab.',
            'evidence_external_link' => 'https://drive.google.com/drive/folders/2026_Cohort2_004',
            'weekly_digest_sent_at' => '2026-07-25 10:00:00',
            'status' => Activity::STATUS_ESCALATED,
            'reviewer_id' => $spsm->id,
            'review_comments' => 'Escalated directly to Steering Committee due to facility compliance hazard.',
        ]);

        // Fresh Deliverable Pending Weekly Dispatch
        Activity::create([
            'year' => 2026,
            'cohort' => 'Cohort 2',
            'officer_id' => $officer->id,
            'code' => 'NF-2026-005',
            'title' => 'Pitch Deck Rehearsal Video Recordings',
            'completed_on' => '2026-09-04',
            'deliverable_result' => '12 venture teams completed mock presentations.',
            'evidence_external_link' => 'https://drive.google.com/drive/folders/2026_Cohort2_005',
            'weekly_digest_sent_at' => null, // Pending Step 3 dispatch!
            'status' => Activity::STATUS_UNDER_REVIEW,
        ]);

        // 3. 2027 Programme Deliverable (Future tab)
        Activity::create([
            'year' => 2027,
            'cohort' => 'Cohort 1',
            'officer_id' => $officer->id,
            'code' => 'NF-2027-001',
            'title' => 'Call for Applications Press Release & Portal Opening',
            'completed_on' => '2027-01-10',
            'deliverable_result' => 'Portal open; received 150 inquiries within 72 hours.',
            'evidence_external_link' => 'https://drive.google.com/drive/folders/2027_Cohort1_001',
            'weekly_digest_sent_at' => '2027-01-15 12:00:00',
            'status' => Activity::STATUS_VERIFIED,
            'reviewer_id' => $spsm->id,
            'review_comments' => 'Approved press distribution verification.',
        ]);
    }
}