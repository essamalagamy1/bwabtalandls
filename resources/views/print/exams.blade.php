<x-layouts.print 
    title="{{ __('lang.exam_analytical_report') }}"
    :selected-stage="$selectedStage"
    :selected-grade="$selectedGrade"
    :selected-section="$selectedSection"
    :selected-semester="$selectedSemester"
>
    <!-- Top Stats (Exact Same as Exam Reports Page) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 break-inside-avoid">
        <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-500 block mb-0.5">{{ __('lang.exams') }}</span>
                <span class="text-2xl font-black text-slate-900">{{ $totalExams }}</span>
            </div>
            <div class="p-2 bg-slate-100 text-slate-700 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
        </div>

        <div class="p-4 rounded-xl border border-sky-200 bg-white shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-sky-700 block mb-0.5">{{ __('lang.exam_attempts_mng') }}</span>
                <span class="text-2xl font-black text-sky-600">{{ $totalAttempts }}</span>
            </div>
            <div class="p-2 bg-sky-50 text-sky-600 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Chart: Average Score (Top 10 Recent Exams) -->
    <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm break-inside-avoid">
        <h4 class="text-xs font-bold text-slate-800 mb-3 border-r-4 border-indigo-600 pr-2">
            {{ __('lang.average_score') }} ({{ __('lang.recent_10_exams') }})
        </h4>
        <div class="h-60 relative">
            <canvas id="chartAverageScore"></canvas>
        </div>
    </div>

    <!-- Tables: Hardest and Easiest Questions -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 break-inside-avoid">
        
        <!-- Hardest Questions Table -->
        <div class="p-4 rounded-xl border-t-4 border-t-rose-500 border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center gap-1.5 mb-3">
                <svg class="w-4 h-4 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <h4 class="text-xs font-bold text-rose-800">{{ __('lang.hardest_questions') }}</h4>
            </div>
            <table class="w-full text-xs text-right border-collapse">
                <thead>
                    <tr class="bg-slate-100 text-slate-700 font-bold">
                        <th class="p-2 text-center w-8">#</th>
                        <th class="p-2">{{ __('lang.question') }}</th>
                        <th class="p-2 text-center w-28">{{ __('lang.pass_fail_ratio') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($hardestQuestions as $index => $item)
                        @php
                            $rate = $item->total_attempts > 0 ? round(($item->correct_count / $item->total_attempts) * 100) : 0;
                        @endphp
                        <tr class="hover:bg-slate-50">
                            <td class="p-2 text-center font-bold text-slate-500">{{ $index + 1 }}</td>
                            <td class="p-2 font-medium text-slate-900 leading-tight">
                                {{ Str::limit($item->question?->question_text, 60) }}
                            </td>
                            <td class="p-2 text-center font-black text-rose-600">{{ $rate }}%</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="p-3 text-center text-slate-500">{{ __('lang.no_data') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Easiest Questions Table -->
        <div class="p-4 rounded-xl border-t-4 border-t-emerald-500 border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center gap-1.5 mb-3">
                <svg class="w-4 h-4 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <h4 class="text-xs font-bold text-emerald-800">{{ __('lang.easiest_questions') }}</h4>
            </div>
            <table class="w-full text-xs text-right border-collapse">
                <thead>
                    <tr class="bg-slate-100 text-slate-700 font-bold">
                        <th class="p-2 text-center w-8">#</th>
                        <th class="p-2">{{ __('lang.question') }}</th>
                        <th class="p-2 text-center w-28">{{ __('lang.pass_fail_ratio') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($easiestQuestions as $index => $item)
                        @php
                            $rate = $item->total_attempts > 0 ? round(($item->correct_count / $item->total_attempts) * 100) : 0;
                        @endphp
                        <tr class="hover:bg-slate-50">
                            <td class="p-2 text-center font-bold text-slate-500">{{ $index + 1 }}</td>
                            <td class="p-2 font-medium text-slate-900 leading-tight">
                                {{ Str::limit($item->question?->question_text, 60) }}
                            </td>
                            <td class="p-2 text-center font-black text-emerald-600">{{ $rate }}%</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="p-3 text-center text-slate-500">{{ __('lang.no_data') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Exam Difficulty Ranking Table -->
    <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm break-inside-avoid">
        <h4 class="text-xs font-bold text-slate-800 mb-3 border-r-4 border-amber-500 pr-2">
            {{ __('lang.exam_difficulty') }} ({{ __('lang.ranked_by_failure') }})
        </h4>
        <table class="w-full text-xs text-right border-collapse">
            <thead>
                <tr class="bg-slate-100 text-slate-700 font-bold">
                    <th class="p-2 text-center w-10">#</th>
                    <th class="p-2">{{ __('lang.exam') }}</th>
                    <th class="p-2 text-center w-48">{{ __('lang.pass_fail_ratio') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($difficultExams as $index => $exam)
                    @php
                        $passRatio = $exam->attempts_count > 0 ? round(($exam->pass_count / $exam->attempts_count) * 100) : 0;
                    @endphp
                    <tr class="hover:bg-slate-50">
                        <td class="p-2 text-center font-bold text-slate-500">{{ $index + 1 }}</td>
                        <td class="p-2 font-bold text-slate-900">{{ $exam->title }}</td>
                        <td class="p-2 text-center font-bold text-amber-600">
                            {{ $passRatio }}% ({{ $exam->attempts_count }} {{ __('lang.attempts') }})
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="p-3 text-center text-slate-500">{{ __('lang.no_data') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Initialize Chart.js Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Chart: Average Score
            new Chart(document.getElementById('chartAverageScore'), {
                type: @json($averageScoreChart['type']),
                data: @json($averageScoreChart['data']),
                options: @json($averageScoreChart['options'])
            });
        });
    </script>
</x-layouts.print>
