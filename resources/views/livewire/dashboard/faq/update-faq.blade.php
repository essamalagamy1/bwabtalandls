<div>
	<x-button icon="o-pencil" class="btn-sm btn-ghost" @click="$wire.modalUpdate = true" tooltip="{{__('lang.update')}}" wire:click="resetError"/>
	<x-modal wire:model="modalUpdate" title="{{__('lang.update')}}" box-class="modal-box-800">
		<x-form wire:submit="saveUpdate">
			<x-input label="{{__('lang.question_ar')}}" wire:model="question_ar"/>
			<x-input label="{{__('lang.question_en')}}" wire:model="question_en"/>
			<x-textarea label="{{__('lang.answer_ar')}}" wire:model="answer_ar" rows="4"/>
			<x-textarea label="{{__('lang.answer_en')}}" wire:model="answer_en" rows="4"/>
			<x-slot:actions>
				<x-button label="{{__('lang.cancel')}}" @click="$wire.modalUpdate = false"/>
				<x-button label="{{__('lang.update')}}" class="btn btn-primary" wire:loading.attr="disabled" type="submit" spinner="saveUpdate"/>
			</x-slot:actions>
		</x-form>
	</x-modal>
</div>
