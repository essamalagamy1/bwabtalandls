<div>
	<x-button icon="o-plus" class="btn-primary btn-sm mt-2 md:mt-0" label="{{__('lang.add')}}" @click="$wire.modalAdd = true" wire:click="resetData"/>
	{{--modalAdd--}}
	<x-modal wire:model="modalAdd" title="{{__('lang.add')}}" box-class="modal-box-600">
		<x-form wire:submit="saveAdd">
			<x-input label="{{__('lang.name_ar')}}" wire:model="name_ar"/>
			<x-input label="{{__('lang.name_en')}}" wire:model="name_en"/>
			<div>
				<x-file wire:model="image" label="{{__('lang.image')}}" accept="image/*"/>
				<div wire:loading wire:target="image" class="mt-2">
					<x-progress class="progress-primary h-1" indeterminate/>
					<p class="text-sm text-center text-primary">{{ __('lang.uploading_image') }}...</p>
				</div>			</div>
			<x-select label="{{__('lang.status')}}" wire:model="status" :options="[['id' => 'active', 'name' => __('lang.active')], ['id' => 'inactive', 'name' => __('lang.inactive')]]" option-value="id" option-label="name"/>
			<x-slot:actions>
				<x-button label="{{__('lang.cancel')}}" @click="$wire.modalAdd = false"/>
				<x-button label="{{__('lang.save')}}" class="btn btn-primary" wire:loading.attr="disabled" type="submit" spinner wire:target="saveAdd,image"/>
			</x-slot:actions>
		</x-form>
	</x-modal>
</div>
