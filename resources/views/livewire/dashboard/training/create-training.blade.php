<div>
	<x-button icon="o-plus" class="btn-primary btn-sm mt-2 md:mt-0" label="{{ __('lang.add') }}" @click="$wire.modalAdd = true" wire:click="resetData"/>
	<x-modal wire:model="modalAdd" title="{{ __('lang.add') }} {{ __('lang.training') }}" box-class="modal-box-700">
		<x-form wire:submit="saveAdd">
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
				<x-button label="{{ __('lang.cancel') }}" @click="$wire.modalAdd = false"/>
				<x-button label="{{ __('lang.save') }}" class="btn btn-primary" wire:loading.attr="disabled" type="submit" spinner="saveAdd"/>
			</x-slot:actions>
		</x-form>
	</x-modal>
</div>
