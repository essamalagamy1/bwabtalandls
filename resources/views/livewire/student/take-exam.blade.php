<div>
	<div class="flex justify-between items-center mb-6">
		<h1 class="text-2xl font-bold">{{ $exam->title }}</h1>
		
		@if($timeLeft > 0)
			<div x-data="{ time: {{ $timeLeft }} }" x-init="
				setInterval(() => {
					if (time > 0) {
						time--;
					} else {
						$wire.submitExam();
					}
				}, 1000);
			">
				<div class="bg-error text-error-content px-4 py-2 rounded-lg font-mono text-xl flex items-center gap-2 shadow-lg">
					<x-icon name="o-clock" class="w-6 h-6"/>
					<span x-text="Math.floor(time / 60).toString().padStart(2, '0') + ':' + (time % 60).toString().padStart(2, '0')"></span>
				</div>
			</div>
		@endif
	</div>

	<div class="space-y-6">
		@foreach($exam->questions as $index => $question)
			<x-card class="shadow-md" title="{{ __('lang.question') }} {{ $index + 1 }}">
				<div class="mb-4 text-lg">
					{{ $question->question_text }}
				</div>
				
				@if($question->getFirstMediaUrl('image'))
					<div class="mb-4">
						<img src="{{ $question->getFirstMediaUrl('image') }}" alt="Question Image" class="max-h-64 rounded-lg shadow-sm" />
					</div>
				@endif

				<div class="space-y-3">
					<label class="flex items-center gap-3 cursor-pointer p-3 rounded-lg hover:bg-base-200 transition-colors border border-base-300">
						<input type="radio" wire:model.defer="answers.{{ $question->id }}" value="a" class="radio radio-primary" />
						<span>{{ $question->option_a }}</span>
					</label>
					
					<label class="flex items-center gap-3 cursor-pointer p-3 rounded-lg hover:bg-base-200 transition-colors border border-base-300">
						<input type="radio" wire:model.defer="answers.{{ $question->id }}" value="b" class="radio radio-primary" />
						<span>{{ $question->option_b }}</span>
					</label>
					
					<label class="flex items-center gap-3 cursor-pointer p-3 rounded-lg hover:bg-base-200 transition-colors border border-base-300">
						<input type="radio" wire:model.defer="answers.{{ $question->id }}" value="c" class="radio radio-primary" />
						<span>{{ $question->option_c }}</span>
					</label>
					
					<label class="flex items-center gap-3 cursor-pointer p-3 rounded-lg hover:bg-base-200 transition-colors border border-base-300">
						<input type="radio" wire:model.defer="answers.{{ $question->id }}" value="d" class="radio radio-primary" />
						<span>{{ $question->option_d }}</span>
					</label>
				</div>
			</x-card>
		@endforeach
	</div>

	<div class="mt-8 flex justify-end">
		<x-button label="{{ __('lang.submit_exam') }}" wire:click="submitExam" wire:confirm="{{ __('lang.confirm_submit_exam') }}" icon="o-check-circle" class="btn-primary btn-lg w-full sm:w-auto" spinner="submitExam" />
	</div>
</div>
