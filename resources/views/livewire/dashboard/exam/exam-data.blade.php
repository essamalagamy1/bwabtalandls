<div>
	<x-card title="{{ __('lang.exams') }}" shadow class="mb-3">
		<x-slot:menu>
			@can('create_exam')
				<livewire:dashboard.exam.create-exam wire:key="create-exam-modal"/>
			@endcan
		</x-slot:menu>
		<div class="grid grid-cols-1 md:grid-cols-5 gap-3 mb-3">
			<div wire:key="filter-search-title-wrap">
				<x-input label="{{ __('lang.search') }}" wire:model.live="search_title" placeholder="{{ __('lang.search') }}..." clearable/>
			</div>
			<div wire:key="filter-stage-wrap">
				<x-choices-offline label="{{ __('lang.stage') }}" wire:model.live="search_stage_id" :options="$all_stages" option-value="id" option-label="name" single clearable searchable placeholder="{{ __('lang.search') }}" wire:key="filter-stage-select"/>
			</div>
			<div wire:key="filter-grade-wrap-{{ $search_stage_id ?? 'all' }}">
				<x-choices-offline label="{{ __('lang.grade') }}" wire:model.live="search_grade_id" :options="$all_grades" option-value="id" option-label="name" option-sub-label="full_path_name" single clearable searchable placeholder="{{ __('lang.search') }}" wire:key="filter-grade-select-{{ $search_stage_id ?? 'all' }}"/>
			</div>
			<div wire:key="filter-section-wrap-{{ $search_stage_id ?? 'all' }}-{{ $search_grade_id ?? 'all' }}">
				<x-choices-offline label="{{ __('lang.section') }}" wire:model.live="search_section_id" :options="$all_sections" option-value="id" option-label="name" option-sub-label="full_path_name" single clearable searchable placeholder="{{ __('lang.search') }}" wire:key="filter-section-select-{{ $search_stage_id ?? 'all' }}-{{ $search_grade_id ?? 'all' }}"/>
			</div>
			<div wire:key="filter-semester-wrap-{{ $search_stage_id ?? 'all' }}-{{ $search_grade_id ?? 'all' }}">
				<x-choices-offline label="{{ __('lang.semester') }}" wire:model.live="search_semester_id" :options="$all_semesters" option-value="id" option-label="name" option-sub-label="full_path_name" single clearable searchable placeholder="{{ __('lang.search') }}" wire:key="filter-semester-select-{{ $search_stage_id ?? 'all' }}-{{ $search_grade_id ?? 'all' }}"/>
			</div>
			<div wire:key="filter-week-wrap-{{ $search_stage_id ?? 'all' }}-{{ $search_grade_id ?? 'all' }}-{{ $search_semester_id ?? 'all' }}">
				<x-choices-offline label="{{ __('lang.week') }}" wire:model.live="search_week_id" :options="$all_weeks" option-value="id" option-label="name" option-sub-label="full_path_name" single clearable searchable placeholder="{{ __('lang.search') }}" wire:key="filter-week-select-{{ $search_stage_id ?? 'all' }}-{{ $search_grade_id ?? 'all' }}-{{ $search_semester_id ?? 'all' }}"/>
			</div>
			<div wire:key="filter-status-wrap">
				<x-select label="{{ __('lang.status') }}" wire:model.live="search_is_active" :options="[['id' => '1', 'name' => __('lang.active')], ['id' => '0', 'name' => __('lang.inactive')]]" option-value="id" option-label="name" placeholder="{{ __('lang.all') }}" wire:key="filter-status-select"/>
			</div>
		</div>
		<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
			<div class="overflow-x-auto">
				<table class="table">
					<thead class="min-w-full divide-y bg-base-300 text-base-content">
					<tr>
						<th class="text-center">#</th>
						<th class="text-center">{{ __('lang.title') }}</th>
						<th class="text-center">{{ __('lang.academic_path') ?? 'المسار الأكاديمي' }}</th>
						<th class="text-center">{{ __('lang.duration_minutes') }}</th>
						<th class="text-center">{{ __('lang.passing_score') }}</th>
						<th class="text-center">{{ __('lang.questions') }}</th>
						<th class="text-center">{{ __('lang.status') }}</th>
						<th class="text-center">{{ __('lang.action') }}</th>
					</tr>
					</thead>
					<tbody>
					@forelse($exams as $exam)
						<tr class="bg-base-200">
							<th class="text-center">{{ $exams->firstItem() + $loop->index }}</th>
							<td class="text-nowrap">
								<div class="flex items-center gap-1.5">
									<span>{{ $exam->title }}</span>
									@if($exam->hasAttachment())
										@if($exam->attachment_type === 'audio')
											<x-icon name="o-speaker-wave" class="w-4 h-4 text-secondary shrink-0" tooltip="{{ __('lang.attached_audio') }} ({{ $exam->media_views_limit == 0 ? __('lang.unlimited_views') : $exam->media_views_limit . ' ' . __('lang.times') }})"/>
										@elseif($exam->attachment_type === 'image')
											<x-icon name="o-photo" class="w-4 h-4 text-primary shrink-0" tooltip="{{ __('lang.attached_image') }} ({{ $exam->media_views_limit == 0 ? __('lang.unlimited_views') : $exam->media_views_limit . ' ' . __('lang.times') }})"/>
										@else
											<x-icon name="o-paper-clip" class="w-4 h-4 text-info shrink-0" tooltip="{{ __('lang.attached_media') }} ({{ $exam->media_views_limit == 0 ? __('lang.unlimited_views') : $exam->media_views_limit . ' ' . __('lang.times') }})"/>
										@endif
									@endif
								</div>
							</td>
							<td class="text-center text-nowrap">
								<div class="font-bold">{{ $exam->week?->title ?? '-' }}</div>
								@if($exam->week)
									<div class="text-xs text-base-content/70 mt-1">
										{{ $exam->week->semester?->grade?->stage?->name }} - 
										{{ $exam->week->semester?->grade?->name }} - 
										{{ $exam->week->semester?->name }}
									</div>
								@endif
							</td>
							<td class="text-center"><x-badge value="{{ $exam->duration_minutes }}" class="badge-neutral"/></td>
							<td class="text-center"><x-badge value="{{ $exam->passing_score }}%" class="badge-info"/></td>
							<td class="text-center"><x-badge value="{{ $exam->questions_count }}" class="badge-warning"/></td>
							<td class="text-center">
								@if($exam->is_active)
									<x-badge value="{{ __('lang.active') }}" class="badge-success"/>
								@else
									<x-badge value="{{ __('lang.inactive') }}" class="badge-error"/>
								@endif
							</td>
							<td>
								<div class="flex gap-2 justify-center">
									@can('show_question')
										<x-button icon="o-question-mark-circle" class="btn-sm btn-ghost text-info" link="{{ route('questions', ['search_exam_id' => $exam->id]) }}" tooltip="{{ __('lang.questions') }}"/>
									@endcan
									@can('show_exam_attempt')
										<x-button icon="o-users" class="btn-sm btn-ghost text-primary" link="{{ route('exam_attempts', ['search_exam_id' => $exam->id]) }}" tooltip="{{ __('lang.exam_attempts_mng') }}"/>
									@endcan
									@can('edit_exam')
										<livewire:dashboard.exam.update-exam :exam="$exam" wire:key="update-exam-{{ $exam->id }}"/>
										@if($exam->is_active)
											<x-button
												icon="o-lock-closed"
												class="btn-sm btn-ghost text-warning"
												wire:click="toggleActive({{ $exam->id }})"
												wire:confirm="أكيد تبي من إلغاء تفعيل الامتحان ({{ $exam->title }})؟"
												tooltip="{{ __('lang.deactivate') }}"
												wire:loading.attr="disabled"
											/>
										@else
											<x-button
												icon="o-lock-open"
												class="btn-sm btn-ghost text-success"
												wire:click="toggleActive({{ $exam->id }})"
												wire:confirm="أكيد تبي من تفعيل الامتحان ({{ $exam->title }})؟"
												tooltip="{{ __('lang.activate') }}"
												wire:loading.attr="disabled"
											/>
										@endif
									@endcan
									@can('delete_exam')
										<x-button icon="o-trash" class="btn-sm btn-ghost text-error"
											wire:click="delete({{ $exam->id }})"
											wire:confirm="{{ __('lang.confirm_delete', ['attribute' => __('lang.exam')]) }}"
											spinner="delete({{ $exam->id }})"
											tooltip="{{ __('lang.delete') }}"/>
									@endcan
								</div>
							</td>
						</tr>
					@empty
						<tr class="bg-base-200">
							<th colspan="8" class="text-center">{{ __('lang.no_data') }}</th>
						</tr>
					@endforelse
					</tbody>
				</table>
				<div class="flex items-center justify-between px-4 py-3 bg-base-300 text-base-content sm:px-6">
					<div class="w-full flex-none">{{ $exams->links() }}</div>
				</div>
			</div>
		</div>
	</x-card>
</div>
