<div>
	<x-button icon="o-pencil" class="btn-sm btn-ghost" @click="$wire.modalUpdate = true" tooltip="{{ __('lang.update') }}" wire:click="resetError"/>
	<x-modal wire:model="modalUpdate" title="{{ __('lang.update') }} {{ __('lang.section') }}" box-class="modal-box-600">
		<x-form wire:submit="saveUpdate">
			<x-select label="{{ __('lang.stage') }}" wire:model.live="stage_id" :options="$all_stages" option-value="id" option-label="name" placeholder="{{ __('lang.select') }}..." />
			<x-select label="{{ __('lang.grade') }}" wire:model.live="grade_id" :options="$all_grades" option-value="id" option-label="name" placeholder="{{ __('lang.select') }}..." />
			<x-input label="{{ __('lang.name') }}" wire:model="name"/>
			<x-toggle label="{{ __('lang.active') }}" wire:model="is_active"/>
			<x-slot:actions>
				<x-button label="{{ __('lang.cancel') }}" @click="$wire.modalUpdate = false"/>
				<x-button label="{{ __('lang.update') }}" class="btn btn-primary" wire:loading.attr="disabled" type="submit" spinner="saveUpdate"/>
			</x-slot:actions>
		</x-form>
	</x-modal>
</div>
