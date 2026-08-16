@php use App\Services\FileService; @endphp
<div>
	<x-button icon="o-pencil" class="btn-sm btn-ghost" @click="$wire.modalUpdate = true" tooltip="{{__('lang.update')}}"/>
	<x-modal wire:model="modalUpdate" title="{{__('lang.update')}}" box-class="modal-box-600">
		<x-form wire:submit="saveEdit">
			<div class="flex justify-center mb-4">
				<div>
					<x-file wire:model="image" accept="image/*" hint="{{__('lang.click_on_image_to_change')}}" class="cursor-pointer">
						<img src="{{ $university->getFirstMediaUrl('image') }}" class="!w-24 !h-24 rounded-lg object-cover"/>
					</x-file>
					<div wire:loading wire:target="image" class="mt-2">
						<x-progress class="progress-primary h-1" indeterminate/>
						<p class="text-sm text-center text-primary">{{ __('lang.uploading_image') }}...</p>
					</div>
				</div>
			</div>
			<x-input label="{{__('lang.name_ar')}}" wire:model="name_ar"/>
			<x-input label="{{__('lang.name_en')}}" wire:model="name_en"/>
			<x-select label="{{__('lang.status')}}" wire:model="status" :options="[['id' => 'active', 'name' => __('lang.active')], ['id' => 'inactive', 'name' => __('lang.inactive')]]" option-value="id" option-label="name"/>
			<x-slot:actions>
				<x-button label="{{__('lang.cancel')}}" @click="$wire.modalUpdate = false"/>
				<x-button label="{{__('lang.update')}}" class="btn btn-primary" wire:loading.attr="disabled" wire:target="image,saveEdit" type="submit" spinner/>
			</x-slot:actions>
		</x-form>
	</x-modal>
</div>

