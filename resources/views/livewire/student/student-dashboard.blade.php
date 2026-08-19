<div>
	{{-- Welcome & Info Section --}}
	<div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
		<div>
			<h1 class="text-3xl font-bold mb-2">
				{{ __('lang.welcome') ?? 'مرحباً' }}، <span class="text-primary">{{ $user->name }}</span>!
			</h1>
			<p class="text-base-content/70">
				{{ now()->locale(app()->getLocale())->translatedFormat('l, j F Y h:i A') }}
			</p>
		</div>

		<div class="flex flex-wrap gap-2">
			<x-badge value="{{ $user->grade?->stage?->name }}" class="badge-neutral font-bold shadow-sm" icon="o-academic-cap" />
			<x-badge value="{{ $user->grade?->name }}" class="badge-neutral font-bold shadow-sm" icon="o-book-open" />
			@if($activeSemester)
				<x-badge value="{{ $activeSemester->name }}" class="badge-primary font-bold shadow-sm" icon="o-calendar" />
			@endif
		</div>
	</div>

	{{-- Stats Section --}}
	<div class="grid grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
		<x-stat title="{{ __('lang.average_score') ?? 'متوسط الدرجات' }}" value="{{ round($averageScore, 1) }}%" icon="o-chart-bar" class="shadow-sm border-t-4 border-t-warning" color="text-warning" />
		<x-stat title="{{ __('lang.exams_taken') ?? 'اختبارات منجزة' }}" value="{{ $totalExamsTaken }}" icon="o-document-text" class="shadow-sm border-t-4 border-t-primary" color="text-primary" />
		<x-stat title="{{ __('lang.passed') ?? 'ناجح' }}" value="{{ $passedExams }}" icon="o-check-circle" class="shadow-sm border-t-4 border-t-success" color="text-success" />
		<x-stat title="{{ __('lang.failed') ?? 'راسب' }}" value="{{ $failedExams }}" icon="o-x-circle" class="shadow-sm border-t-4 border-t-error" color="text-error" />
	</div>

	{{-- Progress Charts Section --}}
	<div class="mb-10 grid grid-cols-1 lg:grid-cols-2 gap-6">
		<x-card title="{{ __('lang.your_progress') ?? 'تطور مستواك الزمني' }}" class="shadow-xl border-t-4 border-t-primary">
			@if($totalExamsTaken > 0)
				<x-chart wire:model="progressChart" />
			@else
				<div class="h-48 flex items-center justify-center flex-col text-base-content/50">
					<x-icon name="o-chart-bar" class="w-12 h-12 mb-2" />
					<p>{{ __('lang.no_data_yet') ?? 'لم تقم بإنجاز أي اختبارات بعد لظهور الإحصائيات' }}</p>
				</div>
			@endif
		</x-card>

		<x-card title="{{ __('lang.success_rate') ?? 'معدل النجاح' }}" class="shadow-xl border-t-4 border-t-secondary">
			@if($totalExamsTaken > 0)
				<div class="max-h-64 flex justify-center">
					<x-chart wire:model="statusChart" />
				</div>
			@else
				<div class="h-48 flex items-center justify-center flex-col text-base-content/50">
					<x-icon name="o-chart-pie" class="w-12 h-12 mb-2" />
					<p>{{ __('lang.no_data_yet') ?? 'أنجز اختبارات لترى مستوى نجاحك' }}</p>
				</div>
			@endif
		</x-card>
	</div>
	<hr class="my-8 border-base-300" />

	<div class="mb-8">
		<h2 class="text-2xl font-bold mb-4 flex items-center gap-2">
			<x-icon name="o-play-circle" class="w-8 h-8 text-primary" />
			{{ __('lang.trainings') }}
		</h2>
		
		<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
			@forelse($trainings as $training)
				<x-card title="{{ $training->title }}" class="shadow-xl">
					<x-slot:figure>
						<div class="h-48 w-full bg-base-300 flex items-center justify-center">
							<x-icon name="o-play-circle" class="w-16 h-16 text-base-content/30" />
						</div>
					</x-slot:figure>
					
					<div class="mt-4">
						<p class="text-base-content/70 text-sm mb-4">{{ Str::limit($training->description, 100) }}</p>
						<div class="flex justify-between items-center mb-3">
							<span class="badge badge-outline">{{ $training->week?->title }}</span>
							<span class="text-xs text-base-content/50">{{ $training->week?->semester?->name }}</span>
						</div>
						<div class="flex justify-between items-center text-sm">
							<span class="badge badge-outline">{{ __('lang.'.$training->type) }}</span>
						</div>
					</div>

					<x-slot:actions>
						@php
							$link = !empty($training->url) ? $training->url : ($training->getFirstMediaUrl('training_file') ?: '#');
						@endphp
						<x-button label="{{ __('lang.view') }}" class="btn-primary w-full" icon="o-eye" link="{{ $link }}" external target="_blank" />
					</x-slot:actions>
				</x-card>
			@empty
				<div class="col-span-full">
					<x-alert icon="o-information-circle" class="alert-info">
						{{ __('lang.no_data') }}
					</x-alert>
				</div>
			@endforelse
		</div>
	</div>

	<hr class="my-8 border-base-300" />

	<div>
		<h2 class="text-2xl font-bold mb-4 flex items-center gap-2">
			<x-icon name="o-document-text" class="w-8 h-8 text-warning" />
			{{ __('lang.exams') }}
		</h2>
		
		<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
			@forelse($exams as $exam)
				@php
					$attempt = $exam->attempts->first();
				@endphp
				<x-card title="{{ $exam->title }}" class="shadow-xl">
					<x-slot:figure>
						<div class="h-48 w-full bg-base-300 flex items-center justify-center">
							<x-icon name="o-document-text" class="w-16 h-16 text-base-content/30" />
						</div>
					</x-slot:figure>
					
					<div class="space-y-2 mt-4">
						<div class="flex justify-between items-center mb-2">
							<span class="badge badge-outline">{{ $exam->week?->title }}</span>
							<span class="text-xs text-base-content/50">{{ $exam->week?->semester?->name }}</span>
						</div>
						<div class="flex justify-between">
							<span class="text-sm text-base-content/70">{{ __('lang.questions') }}</span>
							<span class="font-bold">{{ $exam->questions_count }}</span>
						</div>
						<div class="flex justify-between">
							<span class="text-sm text-base-content/70">{{ __('lang.duration_minutes') }}</span>
							<span class="font-bold">{{ $exam->duration_minutes }}</span>
						</div>
						<div class="flex justify-between items-center">
							<span class="text-sm text-base-content/70">{{ __('lang.status') }}</span>
							@if($attempt)
								@if($attempt->status === 'passed')
									<x-badge value="{{ __('lang.passed') }}" class="badge-success"/>
								@elseif($attempt->status === 'failed')
									<x-badge value="{{ __('lang.failed') }}" class="badge-error"/>
								@elseif(is_null($attempt->status))
									<x-badge value="{{ __('lang.in_progress') ?? 'قيد الإجراء' }}" class="badge-warning"/>
								@else
									<x-badge value="{{ $attempt->status }}" class="badge-warning"/>
								@endif
							@else
								<x-badge value="{{ __('lang.not_attempted') }}" class="badge-neutral"/>
							@endif
						</div>
					</div>

					<x-slot:actions>
						@if($attempt)
							<x-button label="{{ __('lang.view_result') }}" class="btn-primary w-full" link="{{ route('student.exams.result', $exam->id) }}" icon="o-eye"/>
						@else
							<x-button label="{{ __('lang.enter_exam') }}" class="btn-primary w-full" link="{{ route('student.exams.take', $exam->id) }}" icon="o-play"/>
						@endif
					</x-slot:actions>
				</x-card>
			@empty
				<div class="col-span-full">
					<x-alert icon="o-exclamation-triangle" class="alert-info">
						{{ __('lang.no_data') }}
					</x-alert>
				</div>
			@endforelse
		</div>
	</div>
</div>
