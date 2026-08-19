<div>
	<x-button icon="o-pencil" class="btn-sm btn-ghost" @click="$wire.modalUpdate = true" tooltip="{{ __('lang.update') }}" wire:click="resetError"/>
	<x-modal wire:model="modalUpdate" title="{{ __('lang.update') }} {{ __('lang.semester') }}" box-class="modal-box-600">
		<x-form wire:submit="saveUpdate">
			<x-input label="{{ __('lang.name') }}" wire:model="name"/>
			<x-choices-offline label="{{ __('lang.grade') }}" wire:model="grade_id" :options="$all_grades" option-value="id" option-label="name" option-sub-label="full_path_name" single searchable/>
			<div class="grid grid-cols-1 md:grid-cols-2 gap-3">
				<x-datepicker label="{{ __('lang.start_date') }}" wire:model="start_date" icon="o-calendar"/>
				<x-datepicker label="{{ __('lang.end_date') }}" wire:model="end_date" icon="o-calendar"/>
			</div>
			{{-- <x-toggle label="{{ __('lang.active') }}" wire:model="is_active"/> --}}
			<x-slot:actions>
				<x-button label="{{ __('lang.cancel') }}" @click="$wire.modalUpdate = false"/>
				<x-button label="{{ __('lang.update') }}" class="btn btn-primary" wire:loading.attr="disabled" type="submit" spinner="saveUpdate"/>
			</x-slot:actions>
		</x-form>
	</x-modal>
</div>
