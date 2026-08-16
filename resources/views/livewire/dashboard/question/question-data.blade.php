<div>
	<x-card title="{{ __('lang.questions') }}" shadow class="mb-3">
		<x-slot:menu>
			@can('create_question')
				<livewire:dashboard.question.create-question :all_exams="$all_exams" wire:key="{{ \Illuminate\Support\Str::random(20) }}"/>
			@endcan
		</x-slot:menu>
		<div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
			<x-input label="{{ __('lang.search') }}" wire:model.live="search_text" placeholder="{{ __('lang.search') }}..." clearable/>
			<x-choices-offline label="{{ __('lang.exam') }}" wire:model.live="search_exam_id" :options="$all_exams" option-value="id" option-label="name" single clearable searchable placeholder="{{ __('lang.search') }}"/>
		</div>
		<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
			<div class="overflow-x-auto">
				<table class="table">
					<thead class="min-w-full divide-y bg-base-300 text-base-content">
					<tr>
						<th class="text-center">#</th>
						<th class="text-center">{{ __('lang.question_text') }}</th>
						<th class="text-center">{{ __('lang.exam') }}</th>
						<th class="text-center">{{ __('lang.correct_answer') }}</th>
						<th class="text-center">{{ __('lang.created_at') }}</th>
						<th class="text-center">{{ __('lang.action') }}</th>
					</tr>
					</thead>
					<tbody>
					@forelse($questions as $question)
						<tr class="bg-base-200">
							<th class="text-center">{{ $questions->firstItem() + $loop->index }}</th>
							<td class="text-nowrap max-w-xs truncate" title="{{ $question->question_text }}">
								<div class="flex items-center gap-2">
									@if($question->getFirstMediaUrl('image'))
										<div class="avatar">
											<div class="w-8 rounded">
												<img src="{{ $question->getFirstMediaUrl('image') }}" alt="Question Image"/>
											</div>
										</div>
									@endif
									{{ \Illuminate\Support\Str::limit($question->question_text, 50) }}
								</div>
							</td>
							<td class="text-center text-nowrap">{{ $question->exam?->title ?? '-' }}</td>
							<td class="text-center"><x-badge value="{{ strtoupper($question->correct_answer) }}" class="badge-success"/></td>
							<td class="text-center text-nowrap">{{ formatDate($question->created_at, true) }}</td>
							<td>
								<div class="flex gap-2 justify-center">
									@can('edit_question')
										<livewire:dashboard.question.update-question :question="$question" :all_exams="$all_exams" :key="\Illuminate\Support\Str::random(10)"/>
									@endcan
									@can('delete_question')
										<x-button icon="o-trash" class="btn-sm btn-ghost text-error"
											wire:click="delete({{ $question->id }})"
											wire:confirm="{{ __('lang.confirm_delete', ['attribute' => __('lang.question')]) }}"
											spinner="delete({{ $question->id }})"
											tooltip="{{ __('lang.delete') }}"/>
									@endcan
								</div>
							</td>
						</tr>
					@empty
						<tr class="bg-base-200">
							<th colspan="6" class="text-center">{{ __('lang.no_data') }}</th>
						</tr>
					@endforelse
					</tbody>
				</table>
				<div class="flex items-center justify-between px-4 py-3 bg-base-300 text-base-content sm:px-6">
					<div class="w-full flex-none">{{ $questions->links() }}</div>
				</div>
			</div>
		</div>
	</x-card>
</div>
