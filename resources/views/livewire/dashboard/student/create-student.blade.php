<div>
	<x-button icon="o-plus" class="btn-primary btn-sm mt-2 md:mt-0" label="{{ __('lang.add') }}" @click="$wire.modalAdd = true" wire:click="resetData"/>
	<x-modal wire:model="modalAdd" title="{{ __('lang.add') }} {{ __('lang.student') }}" box-class="modal-box-700">
		<x-form wire:submit="saveAdd">
			<x-input label="{{ __('lang.name') }}" wire:model="name"/>
			<x-input label="{{ __('lang.email') }}" type="email" wire:model="email"/>
			<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
				<x-input label="{{ __('lang.password') }}" type="password" wire:model="password"/>
				<x-input label="{{ __('lang.password_confirmation') }}" type="password" wire:model="password_confirmation"/>
			</div>
			
			<x-phone-input required label="{{ __('lang.phone') }}" phoneProperty="phone" keyProperty="phone_key"/>
			
			<x-choices-offline label="{{ __('lang.grade') }}" wire:model="grade_id" :options="$all_grades" option-value="id" option-label="name" option-sub-label="full_path_name" single searchable/>
			
			<x-select label="{{ __('lang.status') }}" wire:model="status" :options="[
                ['id' => 'active', 'name' => __('lang.active')],
                ['id' => 'inactive', 'name' => __('lang.inactive')],
                ['id' => 'pending', 'name' => __('lang.pending')],
            ]" option-value="id" option-label="name"/>

			<div>
				<x-file wire:model="image" label="{{ __('lang.image') }}" accept="image/*"/>
				<x-progress class="progress-primary h-0.5" indeterminate wire:loading wire:target="image"/>
			</div>
			
			<x-slot:actions>
				<x-button label="{{ __('lang.cancel') }}" @click="$wire.modalAdd = false"/>
				<x-button label="{{ __('lang.save') }}" class="btn btn-primary" wire:loading.attr="disabled" type="submit" spinner="saveAdd"/>
			</x-slot:actions>
		</x-form>
	</x-modal>
</div>
