<div>
    <x-header title="{{ $user->name }}" subtitle="{{ __('lang.student_profile') ?? 'ملف الطالب' }}" separator>
        <x-slot:actions>
            <x-button label="{{ __('lang.back') ?? 'رجوع' }}" link="{{ route('students') }}" icon="o-arrow-left" class="btn-ghost" />
        </x-slot:actions>
    </x-header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        {{-- Profile Card --}}
        <div class="card bg-base-100 shadow-xl lg:col-span-1">
            <div class="card-body items-center text-center">
                <x-avatar :image="$user->getFirstMediaUrl('image')" class="!w-24 !h-24 mb-4" />
                <h2 class="card-title text-2xl">{{ $user->name }}</h2>
                <p class="text-gray-500">{{ $user->email }}</p>
                <div class="badge {{ $user->status === 'active' ? 'badge-success' : ($user->status === 'inactive' ? 'badge-error' : 'badge-warning') }} mt-2">
                    {{ $user->status === 'active' ? (__('lang.active') ?? 'مفعل') : ($user->status === 'inactive' ? (__('lang.inactive') ?? 'معطل') : (__('lang.pending') ?? 'قيد الانتظار')) }}
                </div>
                
                <div class="w-full mt-6 space-y-2 text-sm text-start">
                    <div class="flex justify-between border-b border-base-300 pb-2">
                        <span class="font-bold text-gray-500">{{ __('lang.phone') ?? 'رقم الجوال' }}</span>
                        <span dir="ltr">{{ $user->phone_key }}{{ $user->phone }}</span>
                    </div>
                    <div class="flex justify-between border-b border-base-300 pb-2">
                        <span class="font-bold text-gray-500">{{ __('lang.stage') ?? 'المرحلة' }}</span>
                        <span>{{ $user->grade?->stage?->name ?? '---' }}</span>
                    </div>
                    <div class="flex justify-between border-b border-base-300 pb-2">
                        <span class="font-bold text-gray-500">{{ __('lang.grade') ?? 'الصف' }}</span>
                        <span>{{ $user->grade?->name ?? '---' }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Stats and Charts --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-stat title="{{ __('lang.total_exams') ?? 'إجمالي الامتحانات' }}" value="{{ $totalExamsTaken }}" icon="o-document-text" class="shadow-md bg-base-100" />
                <x-stat title="{{ __('lang.average_score') ?? 'متوسط الدرجات' }}" value="{{ number_format($averageScore, 1) }}%" icon="o-star" class="shadow-md bg-base-100" />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="card bg-base-100 shadow-sm p-4">
                    <h3 class="font-bold mb-4">{{ __('lang.progress_chart') ?? 'مخطط التقدم' }}</h3>
                    @if($totalExamsTaken > 0)
                        <x-chart wire:model="progressChart" />
                    @else
                        <p class="text-center text-gray-500 py-10">{{ __('lang.no_data') ?? 'لا توجد بيانات' }}</p>
                    @endif
                </div>
                <div class="card bg-base-100 shadow-sm p-4">
                    <h3 class="font-bold mb-4">{{ __('lang.exam_status') ?? 'حالة الامتحانات' }}</h3>
                    @if($totalExamsTaken > 0)
                        <x-chart wire:model="statusChart" />
                    @else
                        <p class="text-center text-gray-500 py-10">{{ __('lang.no_data') ?? 'لا توجد بيانات' }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Exams --}}
    <x-card title="{{ __('lang.recent_exams') ?? 'أحدث الامتحانات' }}" class="shadow-xl">
        @if($allAttempts->isEmpty())
            <x-alert title="{{ __('lang.no_data') ?? 'لا يوجد امتحانات تم أداؤها حتى الآن.' }}" icon="o-information-circle" class="alert-info" />
        @else
            <div class="overflow-x-auto">
                <table class="table w-full">
                    <thead>
                        <tr>
                            <th>{{ __('lang.exam') ?? 'الامتحان' }}</th>
                            <th>{{ __('lang.date') ?? 'التاريخ' }}</th>
                            <th>{{ __('lang.score') ?? 'الدرجة' }}</th>
                            <th>{{ __('lang.status') ?? 'الحالة' }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allAttempts as $attempt)
                            <tr>
                                <td>{{ $attempt->exam->title }}</td>
                                <td>{{ $attempt->created_at->format('Y-m-d') }}</td>
                                <td>{{ $attempt->total_score }}</td>
                                <td>
                                    <div class="badge {{ $attempt->status === 'passed' ? 'badge-success' : 'badge-error' }}">
                                        {{ $attempt->status === 'passed' ? (__('lang.passed') ?? 'ناجح') : (__('lang.failed') ?? 'راسب') }}
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-card>
</div>
