<x-layouts.print 
    title="{{ __('lang.student_analytical_report') }}"
    :selected-stage="$selectedStage"
    :selected-grade="$selectedGrade"
    :selected-section="$selectedSection"
    :selected-semester="$selectedSemester"
    :selected-student="$selectedStudent"
>
    <!-- Top Stats (Exact Same as Student Reports Page) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 break-inside-avoid">
        <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-slate-500 block mb-0.5">{{ __('lang.total') }}</span>
                <span class="text-2xl font-black text-slate-900">{{ $totalAttempts }}</span>
            </div>
            <div class="p-2 bg-slate-100 text-slate-700 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            </div>
        </div>

        <div class="p-4 rounded-xl border border-amber-200 bg-white shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-amber-700 block mb-0.5">{{ __('lang.average_score') }}</span>
                <span class="text-2xl font-black text-amber-600">{{ round($avgScore, 2) }}%</span>
            </div>
            <div class="p-2 bg-amber-50 text-amber-600 rounded-lg">
                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
            </div>
        </div>

        <div class="p-4 rounded-xl border border-emerald-200 bg-white shadow-sm flex items-center justify-between">
            <div>
                <span class="text-xs font-bold text-emerald-700 block mb-0.5">{{ __('lang.pass_fail_ratio') }}</span>
                <span class="text-2xl font-black text-emerald-600">{{ $passRate }}%</span>
            </div>
            <div class="p-2 bg-emerald-50 text-emerald-600 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Charts (Exact Same as Student Reports Page) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
        <!-- Pass/Fail Chart -->
        <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm break-inside-avoid">
            <h4 class="text-xs font-bold text-slate-800 mb-3 border-r-4 border-indigo-600 pr-2">{{ __('lang.pass_fail_ratio') }}</h4>
            <div class="h-56 relative flex items-center justify-center">
                <canvas id="chartPassFail"></canvas>
            </div>
        </div>

        <!-- Performance Over Time Chart -->
        <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm lg:col-span-2 break-inside-avoid">
            <h4 class="text-xs font-bold text-slate-800 mb-3 border-r-4 border-indigo-600 pr-2">{{ __('lang.performance_over_time') }}</h4>
            <div class="h-56 relative">
                <canvas id="chartPerformance"></canvas>
            </div>
        </div>
    </div>

    <!-- Tables (Exact Same as Student Reports Page) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- Top Students Table -->
        <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm break-inside-avoid">
            <div class="flex items-center justify-between mb-3 border-r-4 border-emerald-600 pr-2">
                <h4 class="text-xs font-bold text-emerald-800">{{ __('lang.top_students') }}</h4>
                <span class="text-[10px] text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded font-bold border border-emerald-200">الأعلى تحصيلاً</span>
            </div>
            <table class="w-full text-xs text-right border-collapse">
                <thead>
                    <tr class="bg-slate-100 text-slate-700 font-bold">
                        <th class="p-2 text-center w-10">#</th>
                        <th class="p-2">{{ __('lang.student') }}</th>
                        <th class="p-2 text-center w-28">{{ __('lang.average_score') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($topStudents as $index => $student)
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
                            <td class="p-2 text-center font-black text-emerald-600 text-sm">
                                {{ round($student->exam_attempts_avg_total_score, 2) }}%
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

        <!-- Weak Students Table -->
        <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-sm break-inside-avoid">
            <div class="flex items-center justify-between mb-3 border-r-4 border-rose-600 pr-2">
                <h4 class="text-xs font-bold text-rose-800">{{ __('lang.students_needing_improvement') }}</h4>
                <span class="text-[10px] text-rose-700 bg-rose-50 px-2 py-0.5 rounded font-bold border border-rose-200">أقل من 60%</span>
            </div>
            <table class="w-full text-xs text-right border-collapse">
                <thead>
                    <tr class="bg-slate-100 text-slate-700 font-bold">
                        <th class="p-2 text-center w-10">#</th>
                        <th class="p-2">{{ __('lang.student') }}</th>
                        <th class="p-2 text-center w-28">{{ __('lang.average_score') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($weakStudents as $index => $student)
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
                            <td class="p-2 text-center font-black text-rose-600 text-sm">
                                {{ round($student->exam_attempts_avg_total_score, 2) }}%
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
    </div>

    <!-- Initialize Chart.js Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Chart 1: Pass/Fail Ratio
            new Chart(document.getElementById('chartPassFail'), {
                type: @json($passFailChart['type']),
                data: @json($passFailChart['data']),
                options: @json($passFailChart['options'])
            });

            // Chart 2: Performance Over Time
            new Chart(document.getElementById('chartPerformance'), {
                type: @json($performanceChart['type']),
                data: @json($performanceChart['data']),
                options: @json($performanceChart['options'])
            });
        });
    </script>
</x-layouts.print>
