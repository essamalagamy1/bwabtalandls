<div>
	<x-card title="{{ __('lang.stages') }}" shadow class="mb-3">
		<x-slot:menu>
			@can('create_stage')
				<livewire:dashboard.stage.create-stage wire:key="{{ \Illuminate\Support\Str::random(20) }}"/>
			@endcan
		</x-slot:menu>
		<div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-3">
			<x-input label="{{ __('lang.search') }}" wire:model.live="search_name" placeholder="{{ __('lang.search') }}..." clearable/>
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
						<th class="text-center">{{ __('lang.grades') }}</th>
						<th class="text-center">{{ __('lang.status') }}</th>
						<th class="text-center">{{ __('lang.created_at') }}</th>
						<th class="text-center">{{ __('lang.action') }}</th>
					</tr>
					</thead>
					<tbody>
					@forelse($stages as $stage)
						<tr class="bg-base-200">
							<th class="text-center">{{ $stages->firstItem() + $loop->index }}</th>
							<td class="text-nowrap">{{ $stage->name }}</td>
							<td class="text-center">
								<x-badge value="{{ $stage->grades_count }}" class="badge-info"/>
							</td>
							<td class="text-center">
								@if($stage->is_active)
									<x-badge value="{{ __('lang.active') }}" class="badge-success"/>
								@else
									<x-badge value="{{ __('lang.inactive') }}" class="badge-error"/>
								@endif
							</td>
							<td class="text-center text-nowrap">{{ formatDate($stage->created_at, true) }}</td>
							<td>
								<div class="flex gap-2 justify-center">
									@can('edit_stage')
										<livewire:dashboard.stage.update-stage :stage="$stage" :key="\Illuminate\Support\Str::random(10)"/>
										@if($stage->is_active)
											<x-button
												icon="o-lock-closed"
												class="btn-sm btn-ghost text-warning"
												wire:click="toggleActive({{ $stage->id }})"
												wire:confirm="هل أنت متأكد من إلغاء تفعيل المرحلة ({{ $stage->name }})؟ سيتم إلغاء تفعيل كافة الصفوف والفصول والأسابيع والتدريبات المرتبطة بها."
												tooltip="{{ __('lang.deactivate') }}"
												wire:loading.attr="disabled"
											/>
										@else
											<x-button
												icon="o-lock-open"
												class="btn-sm btn-ghost text-success"
												wire:click="toggleActive({{ $stage->id }})"
												wire:confirm="هل أنت متأكد من تفعيل المرحلة ({{ $stage->name }})؟"
												tooltip="{{ __('lang.activate') }}"
												wire:loading.attr="disabled"
											/>
										@endif
									@endcan
									@can('delete_stage')
										<x-button icon="o-trash" class="btn-sm btn-ghost text-error"
											wire:click="delete({{ $stage->id }})"
											wire:confirm="{{ __('lang.confirm_delete', ['attribute' => __('lang.stage')]) }}"
											wire:loading.attr="disabled"
											spinner="delete({{ $stage->id }})"
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
					<div class="flex w-full items-center justify-between">
						<div class="w-full flex-none">
							{{ $stages->links() }}
						</div>
					</div>
				</div>
			</div>
		</div>
	</x-card>
</div>
