<div>
	<x-button icon="o-plus" class="btn-primary btn-sm mt-2 md:mt-0" label="{{ __('lang.add') }}" @click="$wire.modalAdd = true" wire:click="resetData"/>
	<x-modal wire:model="modalAdd" title="{{ __('lang.add') }} {{ __('lang.exam') }}" box-class="modal-box-700">
		<x-form wire:submit="saveAdd">
			<x-input label="{{ __('lang.title') }}" wire:model="title"/>
			<x-textarea label="{{ __('lang.description') }}" wire:model="description" rows="3"/>
			<x-choices-offline label="{{ __('lang.week') }}" wire:model="week_id" :options="$all_weeks" option-value="id" option-label="name" option-sub-label="full_path_name" single searchable/>
			<div class="grid grid-cols-2 gap-4">
				<x-input label="{{ __('lang.duration_minutes') }}" wire:model="duration_minutes" type="number" min="1"/>
				<x-input label="{{ __('lang.passing_score') }} (%)" wire:model="passing_score" type="number" min="0" max="100"/>
			</div>
			<x-toggle label="{{ __('lang.is_active') }}" wire:model="is_active" class="mt-4" />
			<x-slot:actions>
				<x-button label="{{ __('lang.cancel') }}" @click="$wire.modalAdd = false"/>
				<x-button label="{{ __('lang.save') }}" class="btn btn-primary" wire:loading.attr="disabled" type="submit" spinner="saveAdd"/>
			</x-slot:actions>
		</x-form>
	</x-modal>
</div>
