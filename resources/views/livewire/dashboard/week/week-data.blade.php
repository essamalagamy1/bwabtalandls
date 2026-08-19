<div>
	<x-card title="{{ __('lang.weeks') }}" shadow class="mb-3">
		<x-slot:menu>
			@can('create_week')
				<livewire:dashboard.week.create-week :all_semesters="$all_semesters" wire:key="{{ \Illuminate\Support\Str::random(20) }}"/>
			@endcan
		</x-slot:menu>
		<div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-3">
			<x-input label="{{ __('lang.search') }}" wire:model.live="search_title" placeholder="{{ __('lang.search') }}..." clearable/>
			<x-choices-offline label="{{ __('lang.semester') }}" wire:model.live="search_semester_id" :options="$all_semesters" option-value="id" option-label="name" option-sub-label="full_path_name" single clearable searchable placeholder="{{ __('lang.search') }}"/>
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
						<th class="text-center">{{ __('lang.order') }}</th>
						<th class="text-center">{{ __('lang.academic_path') ?? 'المسار الأكاديمي' }}</th>
						<th class="text-center">{{ __('lang.start_date') }}</th>
						<th class="text-center">{{ __('lang.end_date') }}</th>
						<th class="text-center">{{ __('lang.trainings') }}</th>
						<th class="text-center">{{ __('lang.exams') }}</th>
						<th class="text-center">{{ __('lang.status') }}</th>
						<th class="text-center">{{ __('lang.action') }}</th>
					</tr>
					</thead>
					<tbody>
					@forelse($weeks as $week)
						<tr class="bg-base-200">
							<th class="text-center">{{ $weeks->firstItem() + $loop->index }}</th>
							<td class="text-nowrap">{{ $week->title }}</td>
							<td class="text-center"><x-badge value="{{ $week->order }}" class="badge-neutral"/></td>
							<td class="text-center text-nowrap">
								<div class="font-bold">{{ $week->semester?->name ?? '-' }}</div>
								@if($week->semester)
									<div class="text-xs text-base-content/70 mt-1">
										{{ $week->semester->grade?->stage?->name }} - 
										{{ $week->semester->grade?->name }}
									</div>
								@endif
							</td>
							<td class="text-center text-nowrap">{{ $week->start_date?->format('Y-m-d') ?? '-' }}</td>
							<td class="text-center text-nowrap">{{ $week->end_date?->format('Y-m-d') ?? '-' }}</td>
							<td class="text-center"><x-badge value="{{ $week->trainings_count }}" class="badge-info"/></td>
							<td class="text-center"><x-badge value="{{ $week->exams_count }}" class="badge-warning"/></td>
							<td class="text-center">
								@if($week->is_active)
									<x-badge value="{{ __('lang.active') }}" class="badge-success"/>
								@else
									<x-badge value="{{ __('lang.inactive') }}" class="badge-error"/>
								@endif
							</td>
							<td>
								<div class="flex gap-2 justify-center">
									@can('show_training')
										<x-button icon="o-play-circle" class="btn-sm btn-ghost text-info" link="{{ route('trainings', ['search_week_id' => $week->id]) }}" tooltip="{{ __('lang.trainings') }}"/>
									@endcan
									@can('show_exam')
										<x-button icon="o-document-text" class="btn-sm btn-ghost text-warning" link="{{ route('exams', ['search_week_id' => $week->id]) }}" tooltip="{{ __('lang.exams') }}"/>
									@endcan
									@can('edit_week')
										<livewire:dashboard.week.update-week :week="$week" :all_semesters="$all_semesters" :key="\Illuminate\Support\Str::random(10)"/>
										@if($week->is_active)
											<x-button
												icon="o-lock-closed"
												class="btn-sm btn-ghost text-warning"
												wire:click="toggleActive({{ $week->id }})"
												wire:confirm="عند الغاء تفعيل الاسبوع ({{ $week->title }}) سوف يتم الغاء تفعيل كافة التدريبات والامتحانات المرتبطة به. هل أنت متأكد من الإلغاء؟"
												tooltip="{{ __('lang.deactivate') }}"
											/>
										@else
											<x-button
												icon="o-lock-open"
												class="btn-sm btn-ghost text-success"
												wire:click="toggleActive({{ $week->id }})"
												tooltip="{{ __('lang.activate') }}"
											/>
										@endif
									@endcan
									@can('delete_week')
										<x-button icon="o-trash" class="btn-sm btn-ghost text-error"
											wire:click="delete({{ $week->id }})"
											wire:confirm="{{ __('lang.confirm_delete', ['attribute' => __('lang.week')]) }}"
											spinner="delete({{ $week->id }})"
											tooltip="{{ __('lang.delete') }}"/>
									@endcan
								</div>
							</td>
						</tr>
					@empty
						<tr class="bg-base-200">
							<th colspan="10" class="text-center">{{ __('lang.no_data') }}</th>
						</tr>
					@endforelse
					</tbody>
				</table>
				<div class="flex items-center justify-between px-4 py-3 bg-base-300 text-base-content sm:px-6">
					<div class="w-full flex-none">{{ $weeks->links() }}</div>
				</div>
			</div>
		</div>
	</x-card>
</div>
