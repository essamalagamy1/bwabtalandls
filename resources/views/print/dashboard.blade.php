<x-layouts.print 
    title="{{ __('lang.dashboard_report') }}"
    :selected-stage="$selectedStage"
    :selected-grade="$selectedGrade"
    :selected-section="$selectedSection"
    :selected-semester="$selectedSemester"
>
    <!-- Stats Row 1: Students -->
    <div class="grid grid-cols-3 gap-3 break-inside-avoid">
        <div class="p-3.5 rounded-xl border border-indigo-200 bg-gradient-to-br from-indigo-500/10 to-indigo-500/5">
            <span class="text-xs font-bold text-indigo-700 block mb-0.5">{{ __('lang.total_students') }}</span>
            <span class="text-2xl font-black text-indigo-950">{{ $totalStudents }}</span>
        </div>
        <div class="p-3.5 rounded-xl border border-emerald-200 bg-gradient-to-br from-emerald-500/10 to-emerald-500/5">
            <span class="text-xs font-bold text-emerald-700 block mb-0.5">{{ __('lang.active_students') }}</span>
            <span class="text-2xl font-black text-emerald-950">{{ $activeStudents }}</span>
        </div>
        <div class="p-3.5 rounded-xl border border-rose-200 bg-gradient-to-br from-red-500/10 to-red-500/5">
            <span class="text-xs font-bold text-rose-700 block mb-0.5">{{ __('lang.inactive_students') }}</span>
            <span class="text-2xl font-black text-rose-950">{{ $inactiveStudents }}</span>
        </div>
    </div>

    <!-- Stats Row 2: Structure -->
    <div class="grid grid-cols-4 gap-2.5 break-inside-avoid">
        <div class="p-2.5 rounded-xl border border-violet-200 bg-gradient-to-br from-violet-500/10 to-violet-500/5">
            <span class="text-[11px] font-bold text-violet-700 block mb-0.5">{{ __('lang.total_stages') }}</span>
            <span class="text-xl font-black text-violet-950">{{ $totalStages }}</span>
        </div>
        <div class="p-2.5 rounded-xl border border-sky-200 bg-gradient-to-br from-sky-500/10 to-sky-500/5">
            <span class="text-[11px] font-bold text-sky-700 block mb-0.5">{{ __('lang.total_grades') }}</span>
            <span class="text-xl font-black text-sky-950">{{ $totalGrades }}</span>
        </div>
        <div class="p-2.5 rounded-xl border border-amber-200 bg-gradient-to-br from-amber-500/10 to-amber-500/5">
            <span class="text-[11px] font-bold text-amber-700 block mb-0.5">{{ __('lang.total_semesters') }}</span>
            <span class="text-xl font-black text-amber-950">{{ $totalSemesters }}</span>
        </div>
        <div class="p-2.5 rounded-xl border border-teal-200 bg-gradient-to-br from-teal-500/10 to-teal-500/5">
            <span class="text-[11px] font-bold text-teal-700 block mb-0.5">{{ __('lang.total_weeks') }}</span>
            <span class="text-xl font-black text-teal-950">{{ $totalWeeks }}</span>
        </div>
    </div>

    <!-- Stats Row 3: Exams -->
    <div class="grid grid-cols-4 gap-2.5 break-inside-avoid">
        <div class="p-2.5 rounded-xl border border-rose-200 bg-gradient-to-br from-rose-500/10 to-rose-500/5">
            <span class="text-[11px] font-bold text-rose-700 block mb-0.5">{{ __('lang.total_trainings') }}</span>
            <span class="text-xl font-black text-rose-950">{{ $totalTrainings }}</span>
        </div>
        <div class="p-2.5 rounded-xl border border-orange-200 bg-gradient-to-br from-orange-500/10 to-orange-500/5">
            <span class="text-[11px] font-bold text-orange-700 block mb-0.5">{{ __('lang.total_exams') }}</span>
            <span class="text-xl font-black text-orange-950">{{ $totalExams }}</span>
        </div>
        <div class="p-2.5 rounded-xl border border-pink-200 bg-gradient-to-br from-pink-500/10 to-pink-500/5">
            <span class="text-[11px] font-bold text-pink-700 block mb-0.5">{{ __('lang.total_questions') }}</span>
            <span class="text-xl font-black text-pink-950">{{ $totalQuestions }}</span>
        </div>
        <div class="p-2.5 rounded-xl border border-cyan-200 bg-gradient-to-br from-cyan-500/10 to-cyan-500/5">
            <span class="text-[11px] font-bold text-cyan-700 block mb-0.5">{{ __('lang.total_attempts') }}</span>
            <span class="text-xl font-black text-cyan-950">{{ $totalAttempts }}</span>
        </div>
    </div>

    <!-- Stats Row 4: Performance -->
    <div class="grid grid-cols-2 gap-3 break-inside-avoid">
        <div class="p-3 rounded-xl border border-yellow-200 bg-gradient-to-br from-yellow-500/10 to-yellow-500/5 flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-yellow-800 block mb-0.5">{{ __('lang.average_score') }}</span>
                <span class="text-2xl font-black text-yellow-950">{{ $avgScore }}%</span>
            </div>
        </div>
        <div class="p-3 rounded-xl border border-lime-200 bg-gradient-to-br from-lime-500/10 to-lime-500/5 flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-lime-800 block mb-0.5">{{ __('lang.pass_rate') }}</span>
                <span class="text-2xl font-black text-lime-950">{{ $passRate }}%</span>
            </div>
        </div>
    </div>

    <!-- Charts Grid: Exact Same 4 Charts as Dashboard -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        
        <!-- Chart 1: Students Per Grade -->
        <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm break-inside-avoid">
            <h4 class="text-xs font-bold text-slate-800 mb-3 border-r-4 border-indigo-600 pr-2">{{ __('lang.students_per_grade') }}</h4>
            <div class="h-56 relative">
                <canvas id="chartStudentsPerGrade"></canvas>
            </div>
        </div>

        <!-- Chart 2: Student Status Distribution -->
        <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm break-inside-avoid">
            <h4 class="text-xs font-bold text-slate-800 mb-3 border-r-4 border-indigo-600 pr-2">{{ __('lang.student_status_distribution') }}</h4>
            <div class="h-56 relative flex items-center justify-center">
                <canvas id="chartStudentStatus"></canvas>
            </div>
        </div>

        <!-- Chart 3: Exam Scores Average -->
        <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm break-inside-avoid">
            <h4 class="text-xs font-bold text-slate-800 mb-3 border-r-4 border-indigo-600 pr-2">{{ __('lang.exam_scores_avg') }}</h4>
            <div class="h-56 relative">
                <canvas id="chartExamScores"></canvas>
            </div>
        </div>

        <!-- Chart 4: New Students Monthly -->
        <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm break-inside-avoid">
            <h4 class="text-xs font-bold text-slate-800 mb-3 border-r-4 border-indigo-600 pr-2">{{ __('lang.new_students_monthly') }}</h4>
            <div class="h-56 relative">
                <canvas id="chartNewStudentsMonthly"></canvas>
            </div>
        </div>
    </div>

    <!-- Latest Students Table (Exact Same as Dashboard) -->
    <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm break-inside-avoid">
        <h4 class="text-xs font-bold text-slate-800 mb-3 border-r-4 border-indigo-600 pr-2">{{ __('lang.latest_students') }}</h4>
        <div class="overflow-x-auto">
            <table class="w-full text-xs text-right border-collapse">
                <thead>
                    <tr class="bg-slate-100 text-slate-700 font-bold">
                        <th class="p-2 text-center w-10">#</th>
                        <th class="p-2">{{ __('lang.name') }}</th>
                        <th class="p-2 text-center">{{ __('lang.email') }}</th>
                        <th class="p-2 text-center">{{ __('lang.grade') }}</th>
                        <th class="p-2 text-center">{{ __('lang.stage') }}</th>
                        <th class="p-2 text-center w-24">{{ __('lang.status') }}</th>
                        <th class="p-2 text-center w-28">{{ __('lang.joined_at') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($latestStudents as $index => $student)
                        <tr class="hover:bg-slate-50">
                            <td class="p-2 text-center font-bold text-slate-500">{{ $index + 1 }}</td>
                            <td class="p-2 font-bold text-slate-900">
                                <div class="flex items-center gap-2">
                                    @if($student->getFirstMediaUrl('image'))
                                        <img src="{{ $student->getFirstMediaUrl('image') }}" class="w-6 h-6 rounded-full object-cover border border-slate-300" alt="{{ $student->name }}" />
                                    @else
                                        <div class="w-6 h-6 rounded-full bg-slate-200 text-slate-700 font-bold flex items-center justify-center text-[10px]">
                                            {{ mb_substr($student->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <span>{{ $student->name }}</span>
                                </div>
                            </td>
                            <td class="p-2 text-center text-slate-600 font-mono text-[11px]">{{ $student->email }}</td>
                            <td class="p-2 text-center text-slate-700">{{ $student->grade?->name ?? '-' }}</td>
                            <td class="p-2 text-center text-slate-700">{{ $student->grade?->stage?->name ?? '-' }}</td>
                            <td class="p-2 text-center">
                                @if($student->status === 'active')
                                    <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 font-bold rounded text-[10px]">{{ __('lang.active') }}</span>
                                @elseif($student->status === 'inactive')
                                    <span class="px-2 py-0.5 bg-rose-100 text-rose-800 font-bold rounded text-[10px]">{{ __('lang.inactive') }}</span>
                                @else
                                    <span class="px-2 py-0.5 bg-amber-100 text-amber-800 font-bold rounded text-[10px]">{{ __('lang.pending') }}</span>
                                @endif
                            </td>
                            <td class="p-2 text-center text-slate-500 font-mono text-[10px]">
                                {{ $student->created_at->format('Y-m-d') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="p-3 text-center text-slate-500">{{ __('lang.no_data') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Initialize Chart.js Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Chart 1: Students Per Grade
            new Chart(document.getElementById('chartStudentsPerGrade'), {
                type: @json($studentsPerGradeChart['type']),
                data: @json($studentsPerGradeChart['data']),
                options: @json($studentsPerGradeChart['options'])
            });

            // Chart 2: Student Status Distribution
            new Chart(document.getElementById('chartStudentStatus'), {
                type: @json($studentStatusChart['type']),
                data: @json($studentStatusChart['data']),
                options: @json($studentStatusChart['options'])
            });

            // Chart 3: Exam Scores
            new Chart(document.getElementById('chartExamScores'), {
                type: @json($examScoresChart['type']),
                data: @json($examScoresChart['data']),
                options: @json($examScoresChart['options'])
            });

            // Chart 4: New Students Monthly
            new Chart(document.getElementById('chartNewStudentsMonthly'), {
                type: @json($newStudentsMonthlyChart['type']),
                data: @json($newStudentsMonthlyChart['data']),
                options: @json($newStudentsMonthlyChart['options'])
            });
        });
    </script>
</x-layouts.print>
