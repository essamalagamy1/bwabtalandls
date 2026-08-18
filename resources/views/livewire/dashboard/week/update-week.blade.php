<div>
	<x-button icon="o-pencil" class="btn-sm btn-ghost" @click="$wire.modalUpdate = true" tooltip="{{ __('lang.update') }}" wire:click="resetError"/>
	<x-modal wire:model="modalUpdate" title="{{ __('lang.update') }} {{ __('lang.week') }}" box-class="modal-box-600">
		<x-form wire:submit="saveUpdate">
			<x-input label="{{ __('lang.title') }}" wire:model="title"/>
			<x-input label="{{ __('lang.order') }}" wire:model="order" type="number" min="1"/>
			<x-choices-offline label="{{ __('lang.semester') }}" wire:model="semester_id" :options="$all_semesters" option-value="id" option-label="name" option-sub-label="full_path_name" single searchable/>
			<div class="grid grid-cols-1 md:grid-cols-2 gap-3">
				<x-datepicker label="{{ __('lang.start_date') }}" wire:model="start_date" icon="o-calendar"/>
				<x-datepicker label="{{ __('lang.end_date') }}" wire:model="end_date" icon="o-calendar"/>
			</div>
			<x-toggle label="{{ __('lang.active') }}" wire:model="is_active"/>
			<x-slot:actions>
				<x-button label="{{ __('lang.cancel') }}" @click="$wire.modalUpdate = false"/>
				<x-button label="{{ __('lang.update') }}" class="btn btn-primary" wire:loading.attr="disabled" type="submit" spinner="saveUpdate"/>
			</x-slot:actions>
		</x-form>
	</x-modal>
</div>
