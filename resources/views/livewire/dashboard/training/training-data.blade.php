<div>
	<x-card title="{{ __('lang.trainings') }}" shadow class="mb-3">
		<x-slot:menu>
			@can('create_training')
				<livewire:dashboard.training.create-training wire:key="create-training-modal"/>
			@endcan
		</x-slot:menu>
		<div class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-3">
			<x-input label="{{ __('lang.search') }}" wire:model.live="search_title" placeholder="{{ __('lang.search') }}..." clearable/>
			<x-choices-offline label="{{ __('lang.stage') }}" wire:model.live="search_stage_id" :options="$all_stages" option-value="id" option-label="name" single clearable searchable placeholder="{{ __('lang.search') }}"/>
			<x-choices-offline label="{{ __('lang.grade') }}" wire:model.live="search_grade_id" :options="$all_grades" option-value="id" option-label="name" option-sub-label="full_path_name" single clearable searchable placeholder="{{ __('lang.search') }}"/>
			<x-choices-offline label="{{ __('lang.semester') }}" wire:model.live="search_semester_id" :options="$all_semesters" option-value="id" option-label="name" option-sub-label="full_path_name" single clearable searchable placeholder="{{ __('lang.search') }}"/>
			<x-choices-offline label="{{ __('lang.week') }}" wire:model.live="search_week_id" :options="$all_weeks" option-value="id" option-label="name" option-sub-label="full_path_name" single clearable searchable placeholder="{{ __('lang.search') }}"/>
			<x-select label="{{ __('lang.type') }}" wire:model.live="search_type" :options="[
                ['id' => '', 'name' => __('lang.all')],
                ['id' => 'video', 'name' => __('lang.video')],
                ['id' => 'pdf', 'name' => 'PDF'],
                ['id' => 'file', 'name' => __('lang.file')],
                ['id' => 'link', 'name' => __('lang.link')],
            ]" option-value="id" option-label="name"/>
			<x-select label="{{ __('lang.status') }}" wire:model.live="search_is_active" :options="[
                ['id' => '', 'name' => __('lang.all')],
                ['id' => '1', 'name' => __('lang.active')],
                ['id' => '0', 'name' => __('lang.inactive')],
            ]" option-value="id" option-label="name"/>
		</div>
		<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
			<div class="overflow-x-auto">
				<table class="table">
					<thead class="min-w-full divide-y bg-base-300 text-base-content">
					<tr>
						<th class="text-center">#</th>
						<th class="text-center">{{ __('lang.title') }}</th>
						<th class="text-center">{{ __('lang.type') }}</th>
						<th class="text-center">{{ __('lang.academic_path') ?? 'المسار الأكاديمي' }}</th>
						<th class="text-center">{{ __('lang.status') }}</th>
						<th class="text-center">{{ __('lang.action') }}</th>
					</tr>
					</thead>
					<tbody>
					@forelse($trainings as $training)
						<tr class="bg-base-200">
							<th class="text-center">{{ $trainings->firstItem() + $loop->index }}</th>
							<td class="text-nowrap">{{ $training->title }}</td>
							<td class="text-center"><x-badge value="{{ __('lang.'.$training->type) }}" class="badge-neutral"/></td>
							<td class="text-center text-nowrap">
                                                                <div class="font-bold">{{ $training->week?->title ?? '-' }}</div>
                                                                @if($training->week)
                                                                        <div class="text-xs text-base-content/70 mt-1">
                                                                                {{ $training->week->semester?->grade?->stage?->name }} - 
                                                                                {{ $training->week->semester?->grade?->name }} - 
                                                                                {{ $training->week->semester?->name }}
                                                                        </div>
                                                                @endif
                                                        </td>
							<td class="text-center">
								@if($training->is_active)
									<x-badge value="{{ __('lang.active') }}" class="badge-success"/>
								@else
									<x-badge value="{{ __('lang.inactive') }}" class="badge-error"/>
								@endif
							</td>
							<td>
								<div class="flex gap-2 justify-center">
									@can('edit_training')
										<livewire:dashboard.training.update-training :training="$training"  :key="\Illuminate\Support\Str::random(10)"/>
										@if($training->is_active)
											<x-button
												icon="o-lock-closed"
												class="btn-sm btn-ghost text-warning"
												wire:click="toggleActive({{ $training->id }})"
												wire:confirm="هل أنت متأكد من إلغاء تفعيل التدريب ({{ $training->title }})؟"
												tooltip="{{ __('lang.deactivate') }}"
												wire:loading.attr="disabled"
											/>
										@else
											<x-button
												icon="o-lock-open"
												class="btn-sm btn-ghost text-success"
												wire:click="toggleActive({{ $training->id }})"
												wire:confirm="هل أنت متأكد من تفعيل التدريب ({{ $training->title }})؟"
												tooltip="{{ __('lang.activate') }}"
												wire:loading.attr="disabled"
											/>
										@endif
									@endcan
									@can('delete_training')
										<x-button icon="o-trash" class="btn-sm btn-ghost text-error"
											wire:click="delete({{ $training->id }})"
											wire:confirm="{{ __('lang.confirm_delete', ['attribute' => __('lang.training')]) }}"
											spinner="delete({{ $training->id }})"
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
					<div class="w-full flex-none">{{ $trainings->links() }}</div>
				</div>
			</div>
		</div>
	</x-card>
</div>
