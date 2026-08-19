<div>
	<x-header title="{{ __('lang.my_exams') ?? 'امتحاناتي' }}" subtitle="{{ __('lang.all_exams_for_your_grade') ?? 'جميع الامتحانات المخصصة لصفك الدراسي' }}" separator>
		<x-slot:middle class="!justify-end">
			<div class="flex gap-2 w-full lg:w-auto">
				@if(isset($semesters) && count($semesters) > 0)
					<x-select 
						icon="o-calendar" 
						:options="$semesters" 
						option-value="id" 
						option-label="name" 
						placeholder="{{ __('lang.all_semesters') ?? 'الفصول المتاحة' }}" 
						wire:model.live="selectedSemester" 
						class="w-full lg:w-auto" 
					/>
				@endif

				@if(!empty($weeks) && count($weeks) > 0)
					<x-select 
						icon="o-calendar-days" 
						:options="$weeks" 
						option-value="id" 
						option-label="title" 
						placeholder="{{ __('lang.all_weeks') ?? 'جميع الأسابيع' }}" 
						wire:model.live="selectedWeek" 
						class="w-full lg:w-auto" 
					/>
				@endif
			</div>
		</x-slot:middle>
	</x-header>

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
					<div class="flex justify-between">
						<span class="text-sm text-base-content/70">{{ __('lang.passing_score') }}</span>
						<span class="font-bold text-success">{{ $exam->passing_score }}%</span>
					</div>
					<div class="flex justify-between items-center">
						<span class="text-sm text-base-content/70">{{ __('lang.status') }}</span>
						@if($attempt)
							@if($attempt->status === 'passed')
								<x-badge value="{{ __('lang.passed') }}" class="badge-success"/>
							@elseif($attempt->status === 'failed')
								<x-badge value="{{ __('lang.failed') }}" class="badge-error"/>
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
	
	<div class="mt-6">
		{{ $exams->links() }}
	</div>
</div>
