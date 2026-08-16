<div>
	<x-button icon="o-plus" class="btn-primary btn-sm mt-2 md:mt-0" label="{{__('lang.add')}}" @click="$wire.modalAdd = true" wire:click="resetData"/>
	{{--modalAdd--}}
	<x-modal wire:model="modalAdd" title="{{__('lang.add')}}" box-class="modal-box-800">
		<x-form wire:submit="saveAdd">
			<x-input label="{{__('lang.question_ar')}}" wire:model="question_ar"/>
			<x-input label="{{__('lang.question_en')}}" wire:model="question_en"/>
			<x-textarea label="{{__('lang.answer_ar')}}" wire:model="answer_ar" rows="4"/>
			<x-textarea label="{{__('lang.answer_en')}}" wire:model="answer_en" rows="4"/>
			<x-slot:actions>
				<x-button label="{{__('lang.cancel')}}" @click="$wire.modalAdd = false"/>
				<x-button label="{{__('lang.save')}}" class="btn btn-primary" wire:loading.attr="disabled" type="submit" spinner="saveAdd"/>
			</x-slot:actions>
		</x-form>
	</x-modal>
</div>
