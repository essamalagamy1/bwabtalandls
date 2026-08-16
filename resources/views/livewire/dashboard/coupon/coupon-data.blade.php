<div>
	<x-card title="{{ __('lang.coupons') }}" shadow class="mb-3">
		<x-slot:menu>
			@can('create_coupon')
				<livewire:dashboard.coupon.create-coupon wire:key="{{\Illuminate\Support\Str::random(20)}}"></livewire:dashboard.coupon.create-coupon>
			@endcan
		</x-slot:menu>
		<div class="flex gap-3 mb-3 flex-wrap">
			<div class="w-64">
				<x-ui.choices-advanced-search label="{{ __('lang.coupons') }}" wire:model.live="search_coupon_id" :options="$all_coupon" single clearable searchable
				                   option-value="id" option-label="code" placeholder="{{ __('lang.search') }}"/>
			</div>
			<div class="w-48">
				<x-select label="{{__('lang.type')}}" wire:model.live="filter_type" :options="[['id' => 'fixed', 'name' => __('lang.fixed')], ['id' => 'percent', 'name' => __('lang.percent')]]" placeholder="{{__('lang.all')}}" option-value="id" option-label="name"/>
			</div>
			<div class="w-48">
				<x-select label="{{__('lang.status')}}" wire:model.live="filter_status" :options="[['id' => 'active', 'name' => __('lang.active')], ['id' => 'inactive', 'name' => __('lang.inactive')]]" placeholder="{{__('lang.all')}}" option-value="id" option-label="name"/>
			</div>
		</div>
		<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
			<div class="overflow-x-auto">
				<table class="table">
					<thead class="min-w-full divide-y bg-base-300 text-base-content">
					<tr>
						<th class="text-center">#</th>
						<th class="text-center">{{__('lang.code')}}</th>
						<th class="text-center">{{__('lang.type')}}</th>
						<th class="text-center">{{__('lang.value')}}</th>
						<th class="text-center">{{__('lang.min_order_value')}}</th>
						<th class="text-center">{{__('lang.expiry_date')}}</th>
						<th class="text-center">{{__('lang.status')}}</th>
						<th class="text-center">{{__('lang.action')}}</th>
					</tr>
					</thead>
					<tbody>
					@forelse($coupons as $coupon)
						<tr class="bg-base-200">
							<th class="text-center">{{$coupons->firstItem() + $loop->index}}</th>
							<th class="text-nowrap">{{$coupon->code}}</th>
							<th class="text-center">
								<x-badge :value="__('lang.'.$coupon->type)" class="bg-{{$coupon->type == 'fixed' ? 'green-500' : 'blue-500'}}"/>
							</th>
							<th class="text-center">{{$coupon->value}}</th>
							<th class="text-center">{{$coupon->min_order_value}}</th>
							<th class="text-center text-nowrap">{{$coupon->expiry_date->format('Y-m-d')}}</th>
							<th class="text-center">
								<x-badge :value="$coupon->status->title()" class="bg-{{$coupon->status->color()}}"/>
							</th>
							<td>
								<div class="flex gap-2 justify-center">
									@can('edit_coupon')
										<livewire:dashboard.coupon.update-coupon :coupon="$coupon" :key="\Illuminate\Support\Str::random(10)"/>
									@endcan
									@can('delete_coupon')
										<x-button wire:confirm="{{__('lang.confirm_delete', ['attribute' => __('lang.coupon')])}}" icon="o-trash" class="btn-sm btn-ghost" wire:click="delete({{$coupon->id}})" wire:loading.attr="disabled"
										          wire:target="delete({{$coupon->id}})" spinner="delete({{$coupon->id}})" tooltip="{{__('lang.delete')}}"/>
									@endcan
								</div>
							</td>
						</tr>
					@empty
						<tr class="bg-base-200">
							<th colspan="9" class="text-center">{{__('lang.no_data')}}</th>
						</tr>
					@endforelse
					</tbody>
				</table>
				<div class="flex items-center justify-between px-4 py-3 bg-base-300 text-base-content sm:px-6 min-w-">
					<div class="flex w-full items-center justify-between">
						<div class="w-full flex-none">
							{{ $coupons->links() }}
						</div>
					</div>
				</div>
			</div>
		</div>
	</x-card>
</div>
