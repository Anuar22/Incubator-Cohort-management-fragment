<!DOCTYPE html>
<html lang="en" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Next Frontiers — Programme Governance & Analytics Hub</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    
    <!-- Tailwind & Alpine -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    },
                    colors: {
                        accent: {
                            50: '#fffbeb',
                            100: '#fef3c7',
                            200: '#fde68a',
                            300: '#fcd34d',
                            400: '#fbbf24',
                            500: '#f59e0b',
                            600: '#d97706',
                            700: '#b45309',
                            800: '#92400e',
                            900: '#78350f',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 9999px; }
        ::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
    </style>
</head>
<body class="h-full antialiased font-sans text-slate-800" x-data="{
    sidebarOpen: false,
    logModalOpen: false,
    reviewModalOpen: false,
    remediateModalOpen: false,
    searchQuery: '',
    selectedItem: {},
    reviewDecision: 'verified',
    openReview(item) {
        this.selectedItem = item;
        this.reviewDecision = item.status === 'under_review' ? 'verified' : item.status;
        this.reviewModalOpen = true;
    },
    openRemediate(item) {
        this.selectedItem = item;
        this.remediateModalOpen = true;
    }
}">

    <div class="flex h-screen overflow-hidden">

        <!-- Left Sidebar: White Base with Golden Yellow Focus -->
        <aside 
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-40 w-72 bg-white text-slate-600 flex flex-col transition-transform duration-300 ease-in-out md:static md:translate-x-0 border-r border-slate-200 shrink-0 shadow-sm">
            
            <!-- Brand -->
            <div class="h-20 flex items-center px-6 border-b border-slate-100 justify-between bg-white">
                <div class="flex items-center space-x-3.5">
                    <div class="w-9 h-9 rounded-xl bg-accent-400 flex items-center justify-center font-extrabold text-slate-900 text-base shadow-sm ring-2 ring-accent-200/60">
                        NF
                    </div>
                    <div>
                        <span class="font-extrabold text-sm tracking-tight text-slate-900 block">Next Frontiers</span>
                        <span class="text-[10px] text-accent-700 font-semibold tracking-wider uppercase block">Grants &amp; Governance</span>
                    </div>
                </div>
                <button @click="sidebarOpen = false" class="md:hidden text-slate-400 hover:text-slate-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <!-- Nav Groups -->
            <div class="flex-1 overflow-y-auto px-4 py-6 space-y-7">
                
                <div class="space-y-1.5">
                    <span class="px-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Programme Workspaces</span>
                    <a href="{{ route('tracker.index', ['year' => $selectedYear]) }}" 
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-semibold bg-accent-50 text-accent-900 border border-accent-200/80 shadow-xs transition group">
                        <div class="flex items-center space-x-3">
                            <svg class="w-4 h-4 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                            </svg>
                            <span class="font-bold">Master Register</span>
                        </div>
                        <span class="text-[10px] font-mono font-bold bg-accent-200/80 text-accent-900 px-2 py-0.5 rounded-full">{{ $statistics['total'] }}</span>
                    </a>
                </div>

                <!-- Cohort List -->
                <div class="space-y-1.5">
                    <span class="px-3 text-[11px] font-bold text-slate-400 uppercase tracking-wider">Cohort Streams ({{ $selectedYear }})</span>
                    <a href="{{ route('tracker.index', ['year' => $selectedYear]) }}"
                       class="flex items-center justify-between px-3.5 py-2 rounded-xl text-xs font-medium transition {{ empty($selectedCohort) ? 'text-slate-900 bg-slate-100 font-bold' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' }}">
                        <span class="flex items-center space-x-2">
                            <span class="w-2 h-2 rounded-full {{ empty($selectedCohort) ? 'bg-accent-500' : 'bg-slate-300' }}"></span>
                            <span>All Cohorts</span>
                        </span>
                        <span class="text-[11px] font-mono text-slate-400">{{ $statistics['total'] }}</span>
                    </a>
                    @foreach($cohorts as $cohort)
                        <a href="{{ route('tracker.index', ['year' => $selectedYear, 'cohort' => $cohort]) }}"
                           class="flex items-center justify-between px-3.5 py-2 rounded-xl text-xs font-medium transition {{ $selectedCohort === $cohort ? 'text-slate-900 bg-slate-100 font-bold' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-50' }}">
                            <span class="flex items-center space-x-2 truncate">
                                <span class="w-2 h-2 rounded-full {{ $selectedCohort === $cohort ? 'bg-accent-500' : 'bg-slate-300' }}"></span>
                                <span class="truncate">{{ $cohort }}</span>
                            </span>
                        </a>
                    @endforeach
                </div>

                <!-- SOP Rules -->
                <div class="p-4 rounded-2xl bg-accent-50/70 border border-accent-200/70 text-[11px] leading-relaxed space-y-2.5">
                    <div class="font-bold uppercase tracking-wider text-accent-900 text-[10px] flex items-center space-x-1.5">
                        <svg class="w-4 h-4 text-accent-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>Standard Operating Rules</span>
                    </div>
                    <ul class="space-y-2 text-slate-600">
                        <li class="flex items-start space-x-2">
                            <span class="text-accent-600 font-bold">•</span>
                            <span><strong>Same-Day Entry:</strong> Log finish with evidence on completion.</span>
                        </li>
                        <li class="flex items-start space-x-2">
                            <span class="text-accent-600 font-bold">•</span>
                            <span><strong>Weekly Digest:</strong> Batch email sent to SPSM &amp; BSJ.</span>
                        </li>
                        <li class="flex items-start space-x-2">
                            <span class="text-accent-600 font-bold">•</span>
                            <span><strong>QA Standard:</strong> 100% progress strictly on VERIFIED status.</span>
                        </li>
                    </ul>
                </div>

            </div>

            <!-- Profile / System State -->
            <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-full bg-accent-100 border border-accent-300 flex items-center justify-center font-bold text-xs text-accent-800">
                        OP
                    </div>
                    <div class="text-xs">
                        <div class="font-bold text-slate-900">Operations Desk</div>
                        <div class="text-[10px] text-accent-700 flex items-center space-x-1 font-semibold">
                            <span class="w-1.5 h-1.5 rounded-full bg-accent-500 animate-pulse"></span>
                            <span>Session Active</span>
                        </div>
                    </div>
                </div>
            </div>

        </aside>

        <!-- Main Body -->
        <div class="flex-1 flex flex-col overflow-hidden bg-slate-50/50">

            <!-- Navbar -->
            <header class="h-20 bg-white border-b border-slate-200 px-6 sm:px-8 flex items-center justify-between sticky top-0 z-20 shadow-xs">
                <div class="flex items-center space-x-4">
                    <button @click="sidebarOpen = !sidebarOpen" class="md:hidden text-slate-500 hover:text-slate-800 p-1.5 rounded-lg border border-slate-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>
                    <div>
                        <div class="flex items-center space-x-2.5">
                            <h1 class="text-lg font-extrabold text-slate-900 tracking-tight">Programme Register &amp; Analytics</h1>
                            <span class="px-2 py-0.5 rounded-md bg-accent-100 text-accent-800 font-mono font-bold text-[11px] border border-accent-300">{{ $selectedYear }}</span>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5 font-medium">Deliverables, verification standards, and live QA health</p>
                    </div>
                </div>

                <div class="flex items-center space-x-3">
                    <!-- Year Segmented Control -->
                    <div class="bg-slate-100 p-1 rounded-xl border border-slate-200 flex items-center space-x-1">
                        @foreach($availableYears as $year)
                            <a href="{{ route('tracker.index', ['year' => $year]) }}"
                               class="px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all {{ $selectedYear == $year ? 'bg-white text-accent-800 shadow-xs font-bold' : 'text-slate-600 hover:text-slate-900' }}">
                                {{ $year }}
                            </a>
                        @endforeach
                    </div>

                    <!-- Dispatch Weekly Digest -->
                    <form action="{{ route('tracker.dispatch-weekly') }}" method="POST">
                        @csrf
                        <button type="submit" class="px-3.5 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 shadow-xs transition-all inline-flex items-center space-x-2">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span>Dispatch Weekly Digest</span>
                        </button>
                    </form>

                    <!-- Add Deliverable CTA -->
                    <button @click="logModalOpen = true" class="px-4 py-2 text-xs font-bold text-slate-900 bg-accent-400 hover:bg-accent-300 rounded-xl shadow-xs transition-all inline-flex items-center space-x-2">
                        <svg class="w-4 h-4 text-slate-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span>Log Deliverable</span>
                    </button>
                </div>
            </header>

            <!-- Scrollable Workspace -->
            <div class="flex-1 overflow-y-auto p-6 sm:p-8 space-y-6">

                <!-- Notifications -->
                @if (session('success'))
                    <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-900 rounded-xl text-xs font-semibold shadow-xs flex items-center space-x-2.5">
                        <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif
                @if (session('info'))
                    <div class="p-4 bg-accent-50 border border-accent-300 text-accent-950 rounded-xl text-xs font-semibold shadow-xs flex items-center space-x-2.5">
                        <svg class="w-4 h-4 text-accent-700 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span>{{ session('info') }}</span>
                    </div>
                @endif

                <!-- Executive Stats Bento Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                    
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs hover:border-accent-300 transition-all">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Total Deliverables</span>
                            <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            </div>
                        </div>
                        <div class="text-3xl font-black text-slate-900 tracking-tight mt-2">{{ $statistics['total'] }}</div>
                        <div class="text-[11px] text-slate-400 font-medium mt-1">Logged across all cohorts</div>
                    </div>

                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs hover:border-emerald-300 transition-all">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-bold text-emerald-700 uppercase tracking-wider">QA Verified</span>
                            <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                        </div>
                        <div class="text-3xl font-black text-emerald-700 tracking-tight mt-2">{{ $statistics['verified'] }}</div>
                        <div class="text-[11px] text-emerald-600 font-medium mt-1">Acceptance criteria satisfied</div>
                    </div>

                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs hover:border-accent-400 transition-all">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-bold text-accent-800 uppercase tracking-wider">In Correction</span>
                            <div class="w-7 h-7 rounded-lg bg-accent-100 text-accent-800 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </div>
                        </div>
                        <div class="text-3xl font-black text-accent-800 tracking-tight mt-2">{{ $statistics['returned'] }}</div>
                        <div class="text-[11px] text-accent-700 font-medium mt-1">Awaiting officer remediation</div>
                    </div>

                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-xs hover:border-rose-300 transition-all">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-bold text-rose-600 uppercase tracking-wider">Escalated</span>
                            <div class="w-7 h-7 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                            </div>
                        </div>
                        <div class="text-3xl font-black text-rose-700 tracking-tight mt-2">{{ $statistics['escalated'] }}</div>
                        <div class="text-[11px] text-rose-600 font-medium mt-1">Steering committee alert</div>
                    </div>

                    <div class="bg-accent-400 p-5 rounded-2xl shadow-sm text-slate-950 relative overflow-hidden flex flex-col justify-between">
                        <div>
                            <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-900/80">Programme Progress</span>
                            <div class="text-3xl font-black tracking-tight mt-2 text-slate-950">{{ $statistics['percent_complete'] }}%</div>
                        </div>
                        <div class="mt-4">
                            <div class="w-full bg-slate-900/15 rounded-full h-2 overflow-hidden ring-1 ring-black/5">
                                <div class="bg-slate-950 h-2 rounded-full transition-all duration-500" style="width: {{ $statistics['percent_complete'] }}%"></div>
                            </div>
                            <span class="text-[10px] text-slate-900/80 mt-1.5 block font-semibold">Derived strictly from verified work</span>
                        </div>
                    </div>

                </div>

                <!-- Analytical Performance Panel -->
                <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-xs space-y-4">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 border-b border-slate-100 pb-3">
                        <div>
                            <div class="flex items-center space-x-2">
                                <h3 class="font-extrabold text-sm text-slate-900 tracking-tight">Programme Health &amp; QA Analytics</h3>
                                <span class="bg-accent-100 text-accent-900 text-[10px] font-bold px-2 py-0.5 rounded-full border border-accent-300">Live SLA</span>
                            </div>
                            <p class="text-xs text-slate-500 mt-0.5">Cohort completion velocity and first-pass verification yield</p>
                        </div>
                        <div class="flex items-center space-x-3 bg-slate-50 px-3.5 py-1.5 rounded-xl border border-slate-200">
                            <span class="text-xs font-semibold text-slate-600">First-Pass QA Yield:</span>
                            <span class="text-xs font-black text-slate-900 bg-white px-2 py-0.5 rounded-md border border-slate-200 shadow-2xs">
                                {{ $firstPassRate }}%
                            </span>
                        </div>
                    </div>

                    <!-- Cohort Velocity Bars -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-1">
                        @forelse($cohortAnalytics as $cohortStat)
                            <div class="p-4 bg-slate-50/70 rounded-xl border border-slate-200 space-y-2.5">
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-bold text-slate-900">{{ $cohortStat->cohort }}</span>
                                    <span class="font-mono font-extrabold text-slate-900">{{ $cohortStat->percent }}% Complete</span>
                                </div>
                                <div class="w-full bg-slate-200/80 rounded-full h-2.5 overflow-hidden">
                                    <div class="bg-accent-400 h-2.5 rounded-full transition-all duration-500" style="width: {{ $cohortStat->percent }}%"></div>
                                </div>
                                <div class="flex items-center justify-between text-[11px] text-slate-500 font-medium">
                                    <span>{{ $cohortStat->verified }} of {{ $cohortStat->total }} Verified</span>
                                    <span class="{{ $cohortStat->returned > 0 ? 'text-amber-700 font-bold' : 'text-slate-400' }}">
                                        {{ $cohortStat->returned }} in correction
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-2 text-center py-4 text-xs text-slate-400">
                                No cohort metrics recorded for {{ $selectedYear }}.
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Alert Trays -->
                @if($actionRequiredCount > 0 || $pendingDispatchCount > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @if($actionRequiredCount > 0)
                            <div class="bg-white border-l-4 border-accent-500 p-4 rounded-xl shadow-xs flex items-center justify-between border-y border-r border-slate-200">
                                <div class="flex items-center space-x-3">
                                    <div class="w-9 h-9 rounded-lg bg-accent-100 text-accent-900 flex items-center justify-center font-bold">
                                        ⚠️
                                    </div>
                                    <div>
                                        <div class="font-bold text-xs text-slate-900">{{ $actionRequiredCount }} Deliverable(s) Returned by SPSM</div>
                                        <div class="text-[11px] text-accent-800 font-medium">Corrections required before deadline to satisfy standard.</div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($pendingDispatchCount > 0)
                            <div class="bg-white border-l-4 border-slate-400 p-4 rounded-xl shadow-xs flex items-center justify-between border-y border-r border-slate-200">
                                <div class="flex items-center space-x-3">
                                    <div class="w-9 h-9 rounded-lg bg-slate-100 text-slate-800 flex items-center justify-center font-bold">
                                        ✉️
                                    </div>
                                    <div>
                                        <div class="font-bold text-xs text-slate-900">{{ $pendingDispatchCount }} Items Pending Weekly Digest</div>
                                        <div class="text-[11px] text-slate-500 font-medium">Click "Dispatch Weekly Digest" to notify SPSM &amp; BSJ.</div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Register Workspace Card -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                    
                    <div class="p-5 border-b border-slate-100 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <h2 class="font-bold text-slate-900 text-sm tracking-tight">Active Deliverables Feed</h2>
                            <p class="text-xs text-slate-500 mt-0.5">Continuous verification queue across programme milestones</p>
                        </div>

                        <div class="flex items-center space-x-3">
                            <div class="relative">
                                <input 
                                    type="text" 
                                    placeholder="Filter deliverables..." 
                                    x-model="searchQuery" 
                                    class="text-xs pl-8 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-accent-400 focus:bg-white w-56 transition-all">
                                <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-100 text-xs text-left">
                            <thead class="bg-slate-50/80 text-slate-500 uppercase tracking-wider font-bold text-[10px]">
                                <tr>
                                    <th class="px-5 py-3.5">Code / Cohort</th>
                                    <th class="px-5 py-3.5">Deliverable &amp; Expected Result</th>
                                    <th class="px-4 py-3.5 text-center">Completed</th>
                                    <th class="px-4 py-3.5 text-center">Proof Link</th>
                                    <th class="px-4 py-3.5 text-center">Weekly Digest</th>
                                    <th class="px-4 py-3.5 text-center">QA Status</th>
                                    <th class="px-4 py-3.5 text-center">Deadline</th>
                                    <th class="px-4 py-3.5 text-center">Progress</th>
                                    <th class="px-5 py-3.5 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($activities as $item)
                                    <tr 
                                        x-show="searchQuery === '' || '{{ strtolower($item->code . ' ' . $item->title . ' ' . $item->cohort) }}'.includes(searchQuery.toLowerCase())"
                                        class="hover:bg-slate-50/80 transition-colors group">
                                        
                                        <td class="px-5 py-4 whitespace-nowrap">
                                            <div class="font-mono font-bold text-slate-900 text-xs tracking-tight">{{ $item->code }}</div>
                                            <div class="inline-block mt-1 text-[10px] font-semibold bg-accent-50 text-accent-900 border border-accent-200 px-2 py-0.5 rounded-md">
                                                {{ $item->cohort }}
                                            </div>
                                        </td>

                                        <td class="px-5 py-4 max-w-md">
                                            <div class="font-semibold text-slate-900 leading-snug">{{ $item->title }}</div>
                                            @if($item->deliverable_result)
                                                <div class="text-[11px] text-slate-500 mt-1 line-clamp-2 leading-relaxed">{{ $item->deliverable_result }}</div>
                                            @endif
                                            @if($item->review_comments)
                                                <div class="mt-2 bg-accent-50 border border-accent-200 rounded-lg p-2 text-[11px] text-accent-950 leading-relaxed flex items-start space-x-1.5">
                                                    <span class="font-bold text-accent-900 shrink-0">Reviewer Note:</span>
                                                    <span>{{ $item->review_comments }}</span>
                                                </div>
                                            @endif
                                        </td>

                                        <td class="px-4 py-4 text-center whitespace-nowrap font-medium text-slate-600">
                                            {{ $item->completed_on?->format('d M Y') }}
                                        </td>

                                        <td class="px-4 py-4 text-center whitespace-nowrap">
                                            @if($item->evidence_url)
                                                <a href="{{ $item->evidence_url }}" target="_blank" class="inline-flex items-center space-x-1 text-slate-900 hover:text-accent-700 font-bold underline underline-offset-2">
                                                    <span>Inspect</span>
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                </a>
                                            @else
                                                <span class="text-slate-400 italic">None</span>
                                            @endif
                                        </td>

                                        <td class="px-4 py-4 text-center whitespace-nowrap">
                                            @if($item->weekly_digest_sent_at)
                                                <span class="inline-flex items-center text-emerald-700 font-semibold space-x-1">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                    <span>{{ $item->weekly_digest_sent_at->format('d M') }}</span>
                                                </span>
                                            @else
                                                <span class="inline-flex items-center text-accent-700 font-medium text-[11px] space-x-1">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-accent-400 animate-pulse"></span>
                                                    <span>Pending</span>
                                                </span>
                                            @endif
                                        </td>

                                        <td class="px-4 py-4 text-center whitespace-nowrap">
                                            @php
                                                $badgeClasses = match($item->status) {
                                                    'verified' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                    'returned_for_correction' => 'bg-accent-50 text-accent-800 border-accent-300',
                                                    'open_escalated' => 'bg-rose-50 text-rose-700 border-rose-200',
                                                    default => 'bg-slate-100 text-slate-700 border-slate-200',
                                                };
                                            @endphp
                                            <span class="px-2.5 py-1 rounded-full border text-[10px] font-extrabold uppercase tracking-wide {{ $badgeClasses }}">
                                                {{ str_replace('_', ' ', $item->status) }}
                                            </span>
                                        </td>

                                        <td class="px-4 py-4 text-center whitespace-nowrap">
                                            @if($item->correction_deadline)
                                                <div class="{{ $item->isOverdue() ? 'text-rose-600 font-bold' : 'text-slate-700 font-medium' }}">
                                                    {{ $item->correction_deadline->format('d M Y') }}
                                                </div>
                                                @if($item->isOverdue())
                                                    <span class="inline-block mt-0.5 text-[9px] font-black tracking-widest text-rose-600 bg-rose-50 px-1.5 py-0.2 rounded border border-rose-200 uppercase">
                                                        OVERDUE
                                                    </span>
                                                @endif
                                            @else
                                                <span class="text-slate-400">—</span>
                                            @endif
                                        </td>

                                        <td class="px-4 py-4 text-center whitespace-nowrap font-mono font-bold">
                                            @if($item->progress === 100)
                                                <span class="text-emerald-700 text-xs font-black">100%</span>
                                            @else
                                                <span class="text-slate-400 text-xs font-medium">0%</span>
                                            @endif
                                        </td>

                                        <td class="px-5 py-4 text-right whitespace-nowrap space-x-1.5">
                                            @if($item->status === 'returned_for_correction')
                                                <button @click="openRemediate({{ $item->toJson() }})" class="px-2.5 py-1.5 text-xs font-bold bg-accent-400 hover:bg-accent-300 text-slate-900 rounded-lg shadow-xs transition-all">
                                                    Remediate
                                                </button>
                                            @endif
                                            <button @click="openReview({{ $item->toJson() }})" class="px-2.5 py-1.5 text-xs font-semibold bg-white hover:bg-slate-50 text-slate-800 rounded-lg border border-slate-200 shadow-xs transition-all">
                                                Review
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="px-6 py-16 text-center text-slate-400">
                                            <div class="max-w-xs mx-auto space-y-2">
                                                <svg class="w-10 h-10 mx-auto text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                <div class="font-bold text-slate-700 text-sm">No deliverables registered</div>
                                                <p class="text-xs text-slate-500">No activity logged for year {{ $selectedYear }}. Start by adding the first deliverable.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="px-6 py-4 border-t border-slate-100 bg-slate-50/50">
                        {{ $activities->links() }}
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Modal 1: Log Activity -->
    <div x-show="logModalOpen" x-cloak class="fixed inset-0 z-50 bg-slate-950/40 backdrop-blur-xs flex items-center justify-center p-4">
        <div @click.away="logModalOpen = false" class="bg-white rounded-2xl shadow-2xl w-full max-w-xl overflow-hidden border border-slate-200">
            <div class="px-6 py-5 bg-white border-b border-slate-100 flex justify-between items-center">
                <div>
                    <h3 class="font-extrabold text-sm text-slate-900 tracking-tight">Record Programme Deliverable</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Enforces approved [Year] &gt; [Cohort] evidence structure</p>
                </div>
                <button @click="logModalOpen = false" class="text-slate-400 hover:text-slate-700 text-xl leading-none">&times;</button>
            </div>
            
            <form action="{{ route('tracker.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Programme Year</label>
                        <input type="number" name="year" value="{{ $selectedYear }}" class="w-full text-xs border border-slate-200 rounded-xl p-2.5 focus:ring-2 focus:ring-accent-400 focus:outline-none" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Cohort Identifier</label>
                        <input type="text" name="cohort" placeholder="e.g. Cohort 1" class="w-full text-xs border border-slate-200 rounded-xl p-2.5 focus:ring-2 focus:ring-accent-400 focus:outline-none" required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Activity Code (Unique ID)</label>
                        <input type="text" name="code" placeholder="NF-2026-001" class="w-full text-xs font-mono font-medium border border-slate-200 rounded-xl p-2.5 focus:ring-2 focus:ring-accent-400 focus:outline-none" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1.5">Completion Date</label>
                        <input type="date" name="completed_on" value="{{ date('Y-m-d') }}" class="w-full text-xs border border-slate-200 rounded-xl p-2.5 focus:ring-2 focus:ring-accent-400 focus:outline-none" required>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Deliverable Title</label>
                    <input type="text" name="title" placeholder="e.g. Intake Diagnostic Survey &amp; Baseline Rubric" class="w-full text-xs border border-slate-200 rounded-xl p-2.5 focus:ring-2 focus:ring-accent-400 focus:outline-none" required>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Deliverable Result &amp; Outcomes</label>
                    <textarea name="deliverable_result" rows="2" class="w-full text-xs border border-slate-200 rounded-xl p-2.5 focus:ring-2 focus:ring-accent-400 focus:outline-none" placeholder="Summary of outcomes achieved against milestones..."></textarea>
                </div>

                <div class="border-t border-slate-100 pt-4 space-y-3">
                    <label class="block text-xs font-extrabold text-slate-900">Proof of Evidence</label>
                    <div>
                        <span class="text-[11px] text-slate-500 block mb-1">Direct File Upload:</span>
                        <input type="file" name="evidence_file" class="w-full text-xs border border-slate-200 rounded-xl p-2 bg-slate-50">
                    </div>
                    <div>
                        <span class="text-[11px] text-slate-500 block mb-1">Or External Storage Link:</span>
                        <input type="url" name="evidence_link" placeholder="https://drive.google.com/..." class="w-full text-xs border border-slate-200 rounded-xl p-2.5 focus:ring-2 focus:ring-accent-400 focus:outline-none">
                    </div>
                </div>

                <div class="flex justify-end space-x-2 pt-4 border-t border-slate-100">
                    <button type="button" @click="logModalOpen = false" class="px-4 py-2 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-all">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-xs font-bold text-slate-900 bg-accent-400 hover:bg-accent-300 rounded-xl shadow-xs transition-all">Save Deliverable</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal 2: SPSM Review -->
    <div x-show="reviewModalOpen" x-cloak class="fixed inset-0 z-50 bg-slate-950/40 backdrop-blur-xs flex items-center justify-center p-4">
        <div @click.away="reviewModalOpen = false" class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden border border-slate-200">
            <div class="px-6 py-5 bg-white border-b border-slate-100 flex justify-between items-center">
                <div>
                    <h3 class="font-extrabold text-sm text-slate-900 tracking-tight">SPSM Quality Standard Review</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Verification dictates programme progress</p>
                </div>
                <button @click="reviewModalOpen = false" class="text-slate-400 hover:text-slate-700 text-xl leading-none">&times;</button>
            </div>

            <form action="{{ route('tracker.review') }}" method="POST" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="activity_id" :value="selectedItem.id">

                <div class="bg-accent-50/60 p-3.5 rounded-xl border border-accent-200">
                    <span class="text-[10px] font-mono font-bold text-accent-800 uppercase tracking-wider block" x-text="selectedItem.code"></span>
                    <div class="font-bold text-xs text-slate-900 mt-0.5" x-text="selectedItem.title"></div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Acceptance Decision</label>
                    <select name="status" x-model="reviewDecision" class="w-full text-xs font-semibold border border-slate-200 rounded-xl p-2.5 focus:ring-2 focus:ring-accent-400 focus:outline-none">
                        <option value="verified">VERIFIED (100% Progress Unlocked)</option>
                        <option value="returned_for_correction">RETURNED FOR CORRECTION</option>
                        <option value="open_escalated">OPEN — ESCALATED</option>
                    </select>
                </div>

                <div x-show="reviewDecision === 'returned_for_correction'" x-transition class="space-y-3 bg-accent-50/70 p-4 rounded-xl border border-accent-200">
                    <div>
                        <label class="block text-xs font-bold text-accent-950 mb-1">Remediation Deadline</label>
                        <input type="date" name="correction_deadline" :value="selectedItem.correction_deadline" class="w-full text-xs border border-accent-200 rounded-lg p-2 bg-white">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-accent-950 mb-1">Instructions for Officer</label>
                        <textarea name="review_comments" rows="2" class="w-full text-xs border border-accent-200 rounded-lg p-2 bg-white" placeholder="Specific proof or corrections required..."></textarea>
                    </div>
                </div>

                <div class="flex justify-end space-x-2 pt-4 border-t border-slate-100">
                    <button type="button" @click="reviewModalOpen = false" class="px-4 py-2 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-all">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-xs font-bold text-slate-900 bg-accent-400 hover:bg-accent-300 rounded-xl shadow-xs transition-all">Record Decision</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal 3: Remediate / Resubmit -->
    <div x-show="remediateModalOpen" x-cloak class="fixed inset-0 z-50 bg-slate-950/40 backdrop-blur-xs flex items-center justify-center p-4">
        <div @click.away="remediateModalOpen = false" class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden border border-slate-200">
            <div class="px-6 py-5 bg-white border-b border-slate-100 flex justify-between items-center">
                <div>
                    <h3 class="font-extrabold text-sm text-slate-900 tracking-tight">Resubmit Corrected Deliverable</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Logs correction timestamp automatically</p>
                </div>
                <button @click="remediateModalOpen = false" class="text-slate-400 hover:text-slate-700 text-xl leading-none">&times;</button>
            </div>

            <form action="{{ route('tracker.correction') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                <input type="hidden" name="activity_id" :value="selectedItem.id">

                <div class="bg-accent-50/70 p-3.5 rounded-xl border border-accent-200 text-xs">
                    <span class="font-bold text-accent-950 block">SPSM Feedback:</span>
                    <div class="text-accent-900 mt-1" x-text="selectedItem.review_comments || 'Please supply requested documentation.'"></div>
                </div>

                <div class="space-y-3">
                    <label class="block text-xs font-bold text-slate-800">Attach Corrected Evidence</label>
                    <input type="file" name="evidence_file" class="w-full text-xs border border-slate-200 rounded-xl p-2 bg-slate-50">
                    <input type="url" name="evidence_link" placeholder="Or paste updated external link" class="w-full text-xs border border-slate-200 rounded-xl p-2.5 focus:ring-2 focus:ring-accent-400 focus:outline-none">
                </div>

                <div class="flex justify-end space-x-2 pt-4 border-t border-slate-100">
                    <button type="button" @click="remediateModalOpen = false" class="px-4 py-2 text-xs font-semibold text-slate-600 bg-slate-100 hover:bg-slate-200 rounded-xl transition-all">Cancel</button>
                    <button type="submit" class="px-4 py-2 text-xs font-bold text-slate-900 bg-accent-400 hover:bg-accent-300 rounded-xl shadow-xs transition-all">Submit for Re-evaluation</button>
                </div>
            </form>
        </div>
    </div>

</body>
</html>