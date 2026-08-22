<div>
	<x-card title="{{ __('lang.sections') }}" shadow class="mb-3">
		<x-slot:menu>
			@can('create_section')
				<livewire:dashboard.section.create-section wire:key="{{ \Illuminate\Support\Str::random(20) }}"/>
			@endcan
		</x-slot:menu>
		<div class="grid grid-cols-1 md:grid-cols-4 gap-3 mb-3">
			<x-input label="{{ __('lang.search') }}" wire:model.live="search_name" placeholder="{{ __('lang.search') }}..." clearable/>
			<x-choices-offline label="{{ __('lang.stage') }}" wire:model.live="search_stage_id" :options="$all_stages" option-value="id" option-label="name" single clearable searchable placeholder="{{ __('lang.search') }}"/>
			<x-choices-offline label="{{ __('lang.grade') }}" wire:model.live="search_grade_id" :options="$all_grades" option-value="id" option-label="name" option-sub-label="full_path_name" single clearable searchable placeholder="{{ __('lang.search') }}"/>
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
						<th class="text-center">{{ __('lang.status') }}</th>
						<th class="text-center">{{ __('lang.action') }}</th>
					</tr>
					</thead>
					<tbody>
					@forelse($sections as $section)
						<tr class="bg-base-200">
							<th class="text-center">{{ $sections->firstItem() + $loop->index }}</th>
							<td class="text-nowrap text-center font-medium">{{ $section->name }}</td>
							<td class="text-center">{{ $section->grade?->name ?? '-' }}</td>
							<td class="text-center">{{ $section->grade?->stage?->name ?? '-' }}</td>
							<td class="text-center">
								@if($section->is_active)
									<x-badge value="{{ __('lang.active') }}" class="badge-success"/>
								@else
									<x-badge value="{{ __('lang.inactive') }}" class="badge-error"/>
								@endif
							</td>
							<td>
								<div class="flex gap-2 justify-center">
									@can('edit_section')
										<livewire:dashboard.section.update-section :section="$section" :key="\Illuminate\Support\Str::random(10)"/>
										@if($section->is_active)
											<x-button
												icon="o-lock-closed"
												class="btn-sm btn-ghost text-warning"
												wire:click="toggleActive({{ $section->id }})"
												wire:confirm="أكيد تبي من إلغاء تفعيل الشعبة الدراسية ({{ $section->name }})؟"
												tooltip="{{ __('lang.deactivate') }}"
												wire:loading.attr="disabled"
											/>
										@else
											<x-button
												icon="o-lock-open"
												class="btn-sm btn-ghost text-success"
												wire:click="toggleActive({{ $section->id }})"
												wire:confirm="أكيد تبي من تفعيل الشعبة الدراسية ({{ $section->name }})؟"
												tooltip="{{ __('lang.activate') }}"
												wire:loading.attr="disabled"
											/>
										@endif
									@endcan
									@can('delete_section')
										<x-button icon="o-trash" class="btn-sm btn-ghost text-error"
											wire:click="delete({{ $section->id }})"
											wire:confirm="{{ __('lang.confirm_delete', ['attribute' => __('lang.section')]) }}"
											spinner="delete({{ $section->id }})"
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
					<div class="w-full flex-none">{{ $sections->links() }}</div>
				</div>
			</div>
		</div>
	</x-card>
</div>
