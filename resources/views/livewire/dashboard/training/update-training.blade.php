<div>
	<x-button icon="o-pencil" class="btn-sm btn-ghost" @click="$wire.modalUpdate = true" tooltip="{{ __('lang.update') }}" wire:click="resetError"/>
	<x-modal wire:model="modalUpdate" title="{{ __('lang.update') }} {{ __('lang.training') }}" box-class="modal-box-700">
		<x-form wire:submit="saveUpdate">
			<x-input label="{{ __('lang.title') }}" wire:model="title"/>
			<x-textarea label="{{ __('lang.description') }}" wire:model="description" rows="3"/>
			<x-select label="{{ __('lang.type') }}" wire:model.live="type" :options="[
                ['id' => 'video', 'name' => __('lang.video')],
                ['id' => 'pdf', 'name' => 'PDF'],
                ['id' => 'file', 'name' => __('lang.file')],
                ['id' => 'link', 'name' => __('lang.link')],
            ]" option-value="id" option-label="name"/>
			@if(in_array($type, ['link', 'video']))
				<x-input label="{{ __('lang.url') }}" wire:model="url" placeholder="https://..."/>
			@endif
			<x-choices-offline label="{{ __('lang.week') }}" wire:model="week_id" :options="$all_weeks" option-value="id" option-label="name" option-sub-label="full_path_name" single searchable/>
			<x-toggle label="{{ __('lang.is_active') }}" wire:model="is_active" class="mt-4" />
			@if(in_array($type, ['pdf', 'file']))
				<div>
					<x-file wire:model="training_file" label="{{ __('lang.file') }}"/>
					<x-progress class="progress-primary h-0.5" indeterminate wire:loading wire:target="training_file"/>
				</div>
			@endif
			<x-slot:actions>
				<x-button label="{{ __('lang.cancel') }}" @click="$wire.modalUpdate = false"/>
				<x-button label="{{ __('lang.update') }}" class="btn btn-primary" wire:loading.attr="disabled" type="submit" spinner="saveUpdate"/>
			</x-slot:actions>
		</x-form>
	</x-modal>
</div>
