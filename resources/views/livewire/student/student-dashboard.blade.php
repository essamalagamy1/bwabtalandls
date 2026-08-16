<div>
	{{-- Welcome & Info Section --}}
	<div class="mb-10 grid grid-cols-1 lg:grid-cols-3 gap-6">
		<div class="lg:col-span-2 flex flex-col justify-center">
			<h1 class="text-4xl font-bold mb-2">
				{{ __('lang.welcome') ?? 'مرحباً' }}، <span class="text-primary">{{ $user->name }}</span>!
			</h1>
			<p class="text-base-content/70 text-lg mb-6">
				{{ now()->locale(app()->getLocale())->translatedFormat('l, j F Y h:i A') }}
			</p>

			<div class="flex flex-wrap gap-4">
				<x-badge value="{{ $user->grade?->stage?->name }}" class="badge-neutral badge-lg p-4 font-bold shadow-sm" icon="o-academic-cap" />
				<x-badge value="{{ $user->grade?->name }}" class="badge-neutral badge-lg p-4 font-bold shadow-sm" icon="o-book-open" />
				@if($activeSemester)
					<x-badge value="{{ $activeSemester->name }}" class="badge-primary badge-lg p-4 font-bold shadow-sm" icon="o-calendar" />
				@endif
			</div>
		</div>

		<div>
			<x-card class="shadow-md bg-base-200">
				<div class="text-center">
					<div class="text-5xl font-bold text-warning mb-2">{{ round($averageScore, 1) }}%</div>
					<div class="text-base-content/70 font-bold">{{ __('lang.average_score') ?? 'متوسط الدرجات' }}</div>
				</div>
				<hr class="my-4 border-base-300" />
				<div class="text-center">
					<div class="text-3xl font-bold">{{ $totalExamsTaken }}</div>
					<div class="text-base-content/70 font-bold">{{ __('lang.exams_taken') ?? 'اختبارات منجزة' }}</div>
				</div>
			</x-card>
		</div>
	</div>

	{{-- Progress Chart Section --}}
	@if($totalExamsTaken > 0)
		<div class="mb-10">
			<x-card title="{{ __('lang.your_progress') ?? 'تطور مستواك' }}" class="shadow-xl border-t-4 border-t-primary">
				<x-chart wire:model="progressChart" />
			</x-card>
		</div>
		<hr class="my-8 border-base-300" />
	@endif

	<div class="mb-8">
		<h2 class="text-2xl font-bold mb-4 flex items-center gap-2">
			<x-icon name="o-play-circle" class="w-8 h-8 text-primary" />
			{{ __('lang.trainings') }}
		</h2>
		
		<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
			@forelse($trainings as $training)
				<x-card title="{{ $training->title }}" class="shadow-xl">
					<x-slot:figure>
						@if($training->getFirstMediaUrl('image'))
							<img src="{{ $training->getFirstMediaUrl('image') }}" alt="Training Image" class="h-48 w-full object-cover"/>
						@else
							<div class="h-48 w-full bg-base-300 flex items-center justify-center">
								<x-icon name="o-play-circle" class="w-16 h-16 text-base-content/30" />
							</div>
						@endif
					</x-slot:figure>
					
					<div class="mt-4">
						<p class="text-base-content/70 text-sm mb-4">{{ Str::limit($training->description, 100) }}</p>
						<div class="flex justify-between items-center text-sm">
							<span class="badge badge-outline">{{ __('lang.'.$training->type) }}</span>
							<span class="text-base-content/50">{{ $training->week?->title }}</span>
						</div>
					</div>

					<x-slot:actions>
						<x-button label="{{ __('lang.view') }}" class="btn-primary w-full" icon="o-eye" />
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
						@if($exam->getFirstMediaUrl('image'))
							<img src="{{ $exam->getFirstMediaUrl('image') }}" alt="Exam Image" class="h-48 w-full object-cover"/>
						@else
							<div class="h-48 w-full bg-base-300 flex items-center justify-center">
								<x-icon name="o-document-text" class="w-16 h-16 text-base-content/30" />
							</div>
						@endif
					</x-slot:figure>
					
					<div class="space-y-2 mt-4">
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
