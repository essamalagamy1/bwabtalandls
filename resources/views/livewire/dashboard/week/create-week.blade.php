<div>
	<x-button icon="o-plus" class="btn-primary btn-sm mt-2 md:mt-0" label="{{ __('lang.add') }}" @click="$wire.modalAdd = true" wire:click="resetData"/>
	<x-modal wire:model="modalAdd" title="{{ __('lang.add') }} {{ __('lang.week') }}" box-class="modal-box-600">
		<x-form wire:submit="saveAdd">
			<x-input label="{{ __('lang.title') }}" wire:model="title"/>
			<x-input label="{{ __('lang.order') }}" wire:model="order" type="number" min="1"/>
			<x-choices-offline label="{{ __('lang.semester') }}" wire:model="semester_id" :options="$all_semesters" option-value="id" option-label="name" single searchable/>
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
