<div>
	@if($selected_exam)
		<div class="mb-4 p-4 bg-base-100 rounded-2xl shadow-md border border-primary/20 flex flex-wrap items-center justify-between gap-4">
			<div class="flex items-center gap-3">
				<div class="p-3 bg-primary text-primary-content rounded-xl shadow-sm">
					<x-icon name="o-document-text" class="w-6 h-6"/>
				</div>
				<div>
					<div class="text-xs text-base-content/70 font-medium">الأسئلة التابعة للاختبار:</div>
					<h2 class="text-xl font-bold text-primary">{{ $selected_exam->title }}</h2>
					<div class="text-xs text-base-content/60 mt-0.5">
						{{ $selected_exam->week?->semester?->grade?->stage?->name }} &bull; 
						{{ $selected_exam->week?->semester?->grade?->name }} &bull; 
						{{ $selected_exam->week?->semester?->name }} &bull; 
						{{ $selected_exam->week?->title }}
					</div>
				</div>
			</div>
			<div class="flex items-center gap-2">
				<x-button label="عرض كافة الأسئلة" icon="o-x-mark" class="btn-sm btn-ghost" link="{{ route('questions') }}"/>
				<x-button label="العودة للاختبارات" icon="o-arrow-left" class="btn-sm btn-outline btn-primary" link="{{ route('exams') }}"/>
			</div>
		</div>
	@endif

	<x-card title="{{ $selected_exam ? __('lang.questions') . ' (' . $selected_exam->title . ')' : __('lang.questions') }}" shadow class="mb-3">
		<x-slot:menu>
			@can('create_question')
				<livewire:dashboard.question.create-question :all_exams="$all_exams" :fixed_exam_id="$search_exam_id" wire:key="create-q-{{ $search_exam_id ?? 'all' }}"/>
			@endcan
		</x-slot:menu>

		<div class="grid grid-cols-1 {{ $search_exam_id ? '' : 'md:grid-cols-2' }} gap-3 mb-3">
			<x-input label="{{ __('lang.search') }}" wire:model.live="search_text" placeholder="{{ __('lang.search') }}..." clearable/>
			@if(!$search_exam_id)
				<x-choices-offline label="{{ __('lang.exam') }}" wire:model.live="search_exam_id" :options="$all_exams" option-value="id" option-label="name" single clearable searchable placeholder="{{ __('lang.search') }}" wire:key="filter-exam-select"/>
			@endif
		</div>

		<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
			<div class="overflow-x-auto">
				<table class="table">
					<thead class="min-w-full divide-y bg-base-300 text-base-content">
					<tr>
						<th class="text-center">#</th>
						<th class="text-center">{{ __('lang.question_text') }}</th>
						@if(!$search_exam_id)
							<th class="text-center">{{ __('lang.exam') }}</th>
						@endif
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
							@if(!$search_exam_id)
								<td class="text-center text-nowrap">{{ $question->exam?->title ?? '-' }}</td>
							@endif
							<td class="text-center"><x-badge value="{{ strtoupper($question->correct_answer) }}" class="badge-success"/></td>
							<td class="text-center text-nowrap">{{ formatDate($question->created_at, true) }}</td>
							<td>
								<div class="flex gap-2 justify-center">
									@can('edit_question')
										<livewire:dashboard.question.update-question :question="$question" :all_exams="$all_exams" :fixed_exam_id="$search_exam_id" wire:key="update-q-{{ $question->id }}-{{ $search_exam_id ?? 'all' }}"/>
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
							<th colspan="{{ $search_exam_id ? 5 : 6 }}" class="text-center">{{ __('lang.no_data') }}</th>
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
