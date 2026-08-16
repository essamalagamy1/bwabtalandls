<div>
	<x-button icon="o-plus" class="btn-primary btn-sm mt-2 md:mt-0" label="{{__('lang.add')}}" @click="$wire.modalAdd = true" wire:click="resetData"/>
	{{--modalAdd--}}
	<x-modal wire:model="modalAdd" title="{{__('lang.add')}}" box-class="modal-box-600">
		<x-form wire:submit="saveAdd">
			<x-input label="{{__('lang.code')}}" wire:model="code" hint="{{__('lang.example')}} SAVE20"/>
			<x-select label="{{__('lang.type')}}" wire:model="type" :options="[['id' => 'fixed', 'name' => __('lang.fixed')], ['id' => 'percent', 'name' => __('lang.percent')]]" option-value="id" option-label="name"/>
			<x-input label="{{__('lang.value')}}" type="number" step="0.01" wire:model="value"/>
			<x-input label="{{__('lang.min_order_value')}}" type="number" step="0.01" wire:model="min_order_value"/>
			<x-input label="{{__('lang.max_discount')}}" type="number" step="0.01" wire:model="max_discount"/>
			<x-input label="{{__('lang.usage_limit')}}" type="number" wire:model="usage_limit" hint="{{__('lang.leave_empty_unlimited')}}"/>
			<x-input label="{{__('lang.expiry_date')}}" type="date" wire:model="expiry_date"/>
			<x-select label="{{__('lang.status')}}" wire:model="status" :options="[['id' => 'active', 'name' => __('lang.active')], ['id' => 'inactive', 'name' => __('lang.inactive')]]" option-value="id" option-label="name"/>
			<x-slot:actions>
				<x-button label="{{__('lang.cancel')}}" @click="$wire.modalAdd = false"/>
				<x-button label="{{__('lang.save')}}" class="btn btn-primary" wire:loading.attr="disabled" type="submit" spinner="saveAdd"/>
			</x-slot:actions>
		</x-form>
	</x-modal>
</div>
