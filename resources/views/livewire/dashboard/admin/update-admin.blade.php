@php use App\Enums\Status;use App\Services\FileService; @endphp
<div>
	<x-button icon="o-pencil" class="btn-sm btn-ghost" @click="$wire.modalUpdate = true" tooltip="{{__('lang.update')}}" wire:click="resetError"/>
	<x-modal wire:model="modalUpdate" title="{{__('lang.update')}}" box-class="modal-box-600">
		<x-form wire:submit="saveUpdate">
			<div class="text-center mb-3 mx-auto">
				<x-avatar :image="$user->getFirstMediaUrl('image')" class="w-20 h-20"/>
			</div>
			<x-input label="{{__('lang.name')}}" wire:model="name"/>
			<x-input label="{{__('lang.email')}}" wire:model="email" type="email"/>
			<x-phone-input required label="{{__('lang.phone')}}" phoneProperty="phone" keyProperty="phone_key"/>
			<x-input label="{{__('lang.password')}}" wire:model="password" type="password"/>
			<x-input label="{{__('lang.password_confirmation')}}" wire:model="password_confirmation" type="password"/>
			<div>
				<x-choices-offline label="{{__('lang.roles')}}" wire:model="roles" :options="$all_roles" option-value="id" option-label="name" clearable searchable multiple/>
			</div>
			<div>
				<x-file wire:model="image" label="{{__('lang.image')}}" accept="image/*"/>
				<x-progress class="progress-primary h-0.5" indeterminate wire:loading wire:target="image"/>
			</div>
			<x-slot:actions>
				<x-button label="{{__('lang.cancel')}}" @click="$wire.modalUpdate = false"/>
				<x-button label="{{__('lang.update')}}" class="btn btn-primary" wire:loading.attr="disabled" type="submit" spinner="saveUpdate"/>
			</x-slot:actions>
		</x-form>
	</x-modal>
</div>

