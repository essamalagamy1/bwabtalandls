<div>
	<x-card title="{{ __('lang.weeks') }}" shadow class="mb-3">
		<x-slot:menu>
			@can('create_week')
				<livewire:dashboard.week.create-week :all_semesters="$all_semesters" wire:key="{{ \Illuminate\Support\Str::random(20) }}"/>
			@endcan
		</x-slot:menu>
		<div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
			<x-input label="{{ __('lang.search') }}" wire:model.live="search_title" placeholder="{{ __('lang.search') }}..." clearable/>
			<x-choices-offline label="{{ __('lang.semester') }}" wire:model.live="search_semester_id" :options="$all_semesters" option-value="id" option-label="name" single clearable searchable placeholder="{{ __('lang.search') }}"/>
		</div>
		<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
			<div class="overflow-x-auto">
				<table class="table">
					<thead class="min-w-full divide-y bg-base-300 text-base-content">
					<tr>
						<th class="text-center">#</th>
						<th class="text-center">{{ __('lang.title') }}</th>
						<th class="text-center">{{ __('lang.order') }}</th>
						<th class="text-center">{{ __('lang.semester') }}</th>
						<th class="text-center">{{ __('lang.trainings') }}</th>
						<th class="text-center">{{ __('lang.exams') }}</th>
						<th class="text-center">{{ __('lang.created_at') }}</th>
						<th class="text-center">{{ __('lang.action') }}</th>
					</tr>
					</thead>
					<tbody>
					@forelse($weeks as $week)
						<tr class="bg-base-200">
							<th class="text-center">{{ $weeks->firstItem() + $loop->index }}</th>
							<td class="text-nowrap">{{ $week->title }}</td>
							<td class="text-center"><x-badge value="{{ $week->order }}" class="badge-neutral"/></td>
							<td class="text-center text-nowrap">{{ $week->semester?->name ?? '-' }}</td>
							<td class="text-center"><x-badge value="{{ $week->trainings_count }}" class="badge-info"/></td>
							<td class="text-center"><x-badge value="{{ $week->exams_count }}" class="badge-warning"/></td>
							<td class="text-center text-nowrap">{{ formatDate($week->created_at, true) }}</td>
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
							<th colspan="8" class="text-center">{{ __('lang.no_data') }}</th>
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
