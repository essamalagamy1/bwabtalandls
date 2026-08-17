<div>
	<x-card title="{{ __('lang.semesters') }}" shadow class="mb-3">
		<x-slot:menu>
			@can('create_semester')
				<livewire:dashboard.semester.create-semester :all_grades="$all_grades" wire:key="{{ \Illuminate\Support\Str::random(20) }}"/>
			@endcan
		</x-slot:menu>
		<div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-3">
			<x-input label="{{ __('lang.search') }}" wire:model.live="search_name" placeholder="{{ __('lang.search') }}..." clearable/>
			<x-choices-offline label="{{ __('lang.grade') }}" wire:model.live="search_grade_id" :options="$all_grades" option-value="id" option-label="name" optionSubLabel="stage.name" single clearable searchable placeholder="{{ __('lang.search') }}"/>
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
						<th class="text-center">{{ __('lang.name') }}</th>
						<th class="text-center">{{ __('lang.grade') }}</th>
						<th class="text-center">{{ __('lang.stage') }}</th>
						<th class="text-center">{{ __('lang.start_date') }}</th>
						<th class="text-center">{{ __('lang.end_date') }}</th>
						<th class="text-center">{{ __('lang.weeks') }}</th>
						<th class="text-center">{{ __('lang.status') }}</th>
						<th class="text-center">{{ __('lang.action') }}</th>
					</tr>
					</thead>
					<tbody>
					@forelse($semesters as $semester)
						<tr class="bg-base-200">
							<th class="text-center">{{ $semesters->firstItem() + $loop->index }}</th>
							<td class="text-nowrap">{{ $semester->name }}</td>
							<td class="text-center">{{ $semester->grade?->name ?? '-' }}</td>
							<td class="text-center">{{ $semester->grade?->stage?->name ?? '-' }}</td>
							<td class="text-center text-nowrap">{{ $semester->start_date?->format('Y-m-d') ?? '-' }}</td>
							<td class="text-center text-nowrap">{{ $semester->end_date?->format('Y-m-d') ?? '-' }}</td>
							<td class="text-center"><x-badge value="{{ $semester->weeks_count }}" class="badge-info"/></td>
							<td class="text-center">
								@if($semester->is_active)
									<x-badge value="{{ __('lang.active') }}" class="badge-success"/>
								@else
									<x-badge value="{{ __('lang.inactive') }}" class="badge-error"/>
								@endif
							</td>
							<td>
								<div class="flex gap-2 justify-center">
									@can('edit_semester')
										<livewire:dashboard.semester.update-semester :semester="$semester" :all_grades="$all_grades" :key="\Illuminate\Support\Str::random(10)"/>
										<x-button
											icon="{{ $semester->is_active ? 'o-lock-closed' : 'o-lock-open' }}"
											class="btn-sm btn-ghost {{ $semester->is_active ? 'text-warning' : 'text-success' }}"
											wire:click="toggleActive({{ $semester->id }})"
											tooltip="{{ $semester->is_active ? __('lang.deactivate') : __('lang.activate') }}"
										/>
									@endcan
									@can('delete_semester')
										<x-button icon="o-trash" class="btn-sm btn-ghost text-error"
											wire:click="delete({{ $semester->id }})"
											wire:confirm="{{ __('lang.confirm_delete', ['attribute' => __('lang.semester')]) }}"
											spinner="delete({{ $semester->id }})"
											tooltip="{{ __('lang.delete') }}"/>
									@endcan
								</div>
							</td>
						</tr>
					@empty
						<tr class="bg-base-200">
							<th colspan="9" class="text-center">{{ __('lang.no_data') }}</th>
						</tr>
					@endforelse
					</tbody>
				</table>
				<div class="flex items-center justify-between px-4 py-3 bg-base-300 text-base-content sm:px-6">
					<div class="w-full flex-none">{{ $semesters->links() }}</div>
				</div>
			</div>
		</div>
	</x-card>
</div>
