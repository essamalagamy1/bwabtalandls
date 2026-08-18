<div>
	<x-card title="{{ __('lang.grades') }}" shadow class="mb-3">
		<x-slot:menu>
			@can('create_grade')
				<livewire:dashboard.grade.create-grade :all_stages="$all_stages" wire:key="{{ \Illuminate\Support\Str::random(20) }}"/>
			@endcan
		</x-slot:menu>
		<div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-3">
			<x-input label="{{ __('lang.search') }}" wire:model.live="search_name" placeholder="{{ __('lang.search') }}..." clearable/>
			<x-choices-offline label="{{ __('lang.stage') }}" wire:model.live="search_stage_id" :options="$all_stages" option-value="id" option-label="name" single clearable searchable placeholder="{{ __('lang.search') }}"/>
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
						<th class="text-center">{{ __('lang.stage') }}</th>
						<th class="text-center">{{ __('lang.semesters') }}</th>
						<th class="text-center">{{ __('lang.status') }}</th>
						<th class="text-center">{{ __('lang.created_at') }}</th>
						<th class="text-center">{{ __('lang.action') }}</th>
					</tr>
					</thead>
					<tbody>
					@forelse($grades as $grade)
						<tr class="bg-base-200">
							<th class="text-center">{{ $grades->firstItem() + $loop->index }}</th>
							<td class="text-nowrap">{{ $grade->name }}</td>
							<td class="text-center text-nowrap">{{ $grade->stage?->name ?? '-' }}</td>
							<td class="text-center"><x-badge value="{{ $grade->semesters_count }}" class="badge-info"/></td>
							<td class="text-center">
								@if($grade->is_active)
									<x-badge value="{{ __('lang.active') }}" class="badge-success"/>
								@else
									<x-badge value="{{ __('lang.inactive') }}" class="badge-error"/>
								@endif
							</td>
							<td class="text-center text-nowrap">{{ formatDate($grade->created_at, true) }}</td>
							<td>
								<div class="flex gap-2 justify-center">
									@can('edit_grade')
										<livewire:dashboard.grade.update-grade :grade="$grade" :all_stages="$all_stages" :key="\Illuminate\Support\Str::random(10)"/>
										<x-button
											icon="{{ $grade->is_active ? 'o-lock-closed' : 'o-lock-open' }}"
											class="btn-sm btn-ghost {{ $grade->is_active ? 'text-warning' : 'text-success' }}"
											wire:click="toggleActive({{ $grade->id }})"
											@if($grade->is_active) wire:confirm="هل أنت متأكد من الإلغاء؟ سيتم إلغاء تفعيل كافة الفصول والأسابيع والتدريبات المرتبطة بهذا الصف تلقائياً" @endif
											tooltip="{{ $grade->is_active ? __('lang.deactivate') : __('lang.activate') }}"
										/>
									@endcan
									@can('delete_grade')
										<x-button icon="o-trash" class="btn-sm btn-ghost text-error"
											wire:click="delete({{ $grade->id }})"
											wire:confirm="{{ __('lang.confirm_delete', ['attribute' => __('lang.grade')]) }}"
											spinner="delete({{ $grade->id }})"
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
					<div class="w-full flex-none">{{ $grades->links() }}</div>
				</div>
			</div>
		</div>
	</x-card>
</div>
