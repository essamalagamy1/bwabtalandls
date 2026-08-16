<div>
	<x-card title="{{ __('lang.trainings') }}" shadow class="mb-3">
		<x-slot:menu>
			@can('create_training')
				<livewire:dashboard.training.create-training :all_weeks="$all_weeks" :all_semesters="[]" wire:key="{{ \Illuminate\Support\Str::random(20) }}"/>
			@endcan
		</x-slot:menu>
		<div class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-3">
			<x-input label="{{ __('lang.search') }}" wire:model.live="search_title" placeholder="{{ __('lang.search') }}..." clearable/>
			<x-choices-offline label="{{ __('lang.week') }}" wire:model.live="search_week_id" :options="$all_weeks" option-value="id" option-label="name" single clearable searchable placeholder="{{ __('lang.search') }}"/>
			<x-select label="{{ __('lang.type') }}" wire:model.live="search_type" :options="[
                ['id' => '', 'name' => __('lang.all')],
                ['id' => 'video', 'name' => __('lang.video')],
                ['id' => 'pdf', 'name' => 'PDF'],
                ['id' => 'file', 'name' => __('lang.file')],
                ['id' => 'link', 'name' => __('lang.link')],
            ]" option-value="id" option-label="name"/>
			<x-select label="{{ __('lang.published') }}" wire:model.live="search_is_published" :options="[
                ['id' => '', 'name' => __('lang.all')],
                ['id' => '1', 'name' => __('lang.yes')],
                ['id' => '0', 'name' => __('lang.no')],
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
						<th class="text-center">{{ __('lang.week') }}</th>
						<th class="text-center">{{ __('lang.published') }}</th>
						<th class="text-center">{{ __('lang.publish_date') }}</th>
						<th class="text-center">{{ __('lang.action') }}</th>
					</tr>
					</thead>
					<tbody>
					@forelse($trainings as $training)
						<tr class="bg-base-200">
							<th class="text-center">{{ $trainings->firstItem() + $loop->index }}</th>
							<td class="text-nowrap">{{ $training->title }}</td>
							<td class="text-center"><x-badge value="{{ $training->type }}" class="badge-neutral"/></td>
							<td class="text-center text-nowrap">{{ $training->week?->title ?? '-' }}</td>
							<td class="text-center">
								@if($training->is_published)
									<x-badge value="{{ __('lang.yes') }}" class="badge-success"/>
								@else
									<x-badge value="{{ __('lang.no') }}" class="badge-error"/>
								@endif
							</td>
							<td class="text-center text-nowrap">{{ formatDate($training->publish_date) ?? '-' }}</td>
							<td>
								<div class="flex gap-2 justify-center">
									@can('edit_training')
										<livewire:dashboard.training.update-training :training="$training" :all_weeks="$all_weeks" :all_semesters="[]" :key="\Illuminate\Support\Str::random(10)"/>
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
							<th colspan="7" class="text-center">{{ __('lang.no_data') }}</th>
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
