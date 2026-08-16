<div class="max-w-3xl mx-auto mt-10">
	<x-card class="shadow-2xl text-center p-8">
		@if($attempt->status === 'passed')
			<div class="text-success mb-6 flex justify-center">
				<x-icon name="o-check-badge" class="w-24 h-24" />
			</div>
			<h1 class="text-3xl font-bold mb-2">{{ __('lang.congratulations') }}</h1>
			<p class="text-xl text-base-content/70 mb-8">{{ __('lang.you_passed_exam') }}</p>
		@elseif($attempt->status === 'failed')
			<div class="text-error mb-6 flex justify-center">
				<x-icon name="o-x-circle" class="w-24 h-24" />
			</div>
			<h1 class="text-3xl font-bold mb-2">{{ __('lang.hard_luck') }}</h1>
			<p class="text-xl text-base-content/70 mb-8">{{ __('lang.you_failed_exam') }}</p>
		@endif

		<div class="grid grid-cols-2 gap-4 max-w-md mx-auto mb-8">
			<div class="bg-base-200 p-4 rounded-xl">
				<div class="text-sm text-base-content/70 mb-1">{{ __('lang.your_score') }}</div>
				<div class="text-3xl font-bold {{ $attempt->status === 'passed' ? 'text-success' : 'text-error' }}">
					{{ round($attempt->total_score, 2) }}%
				</div>
			</div>
			
			<div class="bg-base-200 p-4 rounded-xl">
				<div class="text-sm text-base-content/70 mb-1">{{ __('lang.passing_score') }}</div>
				<div class="text-3xl font-bold text-info">
					{{ $exam->passing_score }}%
				</div>
			</div>
		</div>

		<div class="mt-8 text-start">
			<h2 class="text-2xl font-bold mb-6 text-center">{{ __('lang.exam_answers') ?? 'إجابات الامتحان' }}</h2>
			<div class="space-y-6">
				@foreach($attempt->answers as $index => $answer)
					<div class="bg-base-100 p-6 rounded-xl border border-base-300 shadow-sm relative overflow-hidden text-start">
						<!-- Correct/Wrong Indicator Strip -->
						<div class="absolute inset-y-0 right-0 w-2 {{ $answer->is_correct ? 'bg-success' : 'bg-error' }}"></div>
						
						<div class="pr-6">
							<div class="flex items-start justify-between mb-4">
								<h3 class="font-bold text-lg leading-relaxed">
									<span class="text-primary ml-1">{{ $index + 1 }}.</span>
									{{ $answer->question->question_text }}
								</h3>
								<div class="flex-shrink-0 mr-4">
									@if($answer->is_correct)
										<x-badge value="{{ __('lang.correct') ?? 'صحيح' }}" class="badge-success badge-sm" />
									@else
										<x-badge value="{{ __('lang.wrong') ?? 'خطأ' }}" class="badge-error badge-sm" />
									@endif
								</div>
							</div>

							@if($answer->question->getFirstMediaUrl('image'))
								<img src="{{ $answer->question->getFirstMediaUrl('image') }}" alt="Question Image" class="max-w-md rounded-lg mb-4" />
							@endif

							<div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-4">
								@foreach(['a' => 'option_a', 'b' => 'option_b', 'c' => 'option_c', 'd' => 'option_d'] as $key => $option)
									@if($answer->question->$option)
										@php
											$isUserChoice = $answer->selected_option === $key;
											$isCorrectChoice = $answer->question->correct_answer === $key;
											
											$bgClass = 'bg-base-200';
											$borderClass = 'border-transparent';
											$icon = '';
											
											if ($isCorrectChoice) {
												$bgClass = 'bg-success/20';
												$borderClass = 'border-success';
												$icon = 'o-check-circle';
											} elseif ($isUserChoice && !$isCorrectChoice) {
												$bgClass = 'bg-error/20';
												$borderClass = 'border-error';
												$icon = 'o-x-circle';
											}
										@endphp
										<div class="p-3 rounded-lg border-2 {{ $bgClass }} {{ $borderClass }} flex justify-between items-center">
											<span>{{ $answer->question->$option }}</span>
											@if($icon)
												<x-icon name="{{ $icon }}" class="w-5 h-5 {{ $isCorrectChoice ? 'text-success' : 'text-error' }}" />
											@endif
										</div>
									@endif
								@endforeach
							</div>
						</div>
					</div>
				@endforeach
			</div>
		</div>

		<div class="flex justify-center gap-4 mt-8">
			<x-button label="{{ __('lang.back_to_exams') }}" link="{{ route('student.exams') }}" icon="o-arrow-left" class="btn-outline"/>
		</div>
	</x-card>
</div>
