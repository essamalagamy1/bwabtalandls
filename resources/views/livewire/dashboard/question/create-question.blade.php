<div>
	<x-button icon="o-plus" class="btn-primary btn-sm mt-2 md:mt-0" label="{{ __('lang.add') }}" @click="$wire.modalAdd = true" wire:click="resetData"/>
	<x-modal wire:model="modalAdd" title="{{ __('lang.add') }} {{ __('lang.question') }}" box-class="modal-box-800">
		<x-form wire:submit="saveAdd">
			<x-choices-offline label="{{ __('lang.exam') }}" wire:model="exam_id" :options="$all_exams" option-value="id" option-label="name" single searchable/>
			<x-textarea label="{{ __('lang.question_text') }}" wire:model="question_text" rows="3"/>
			
			<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
				<x-input label="{{ __('lang.option_a') }}" wire:model="option_a"/>
				<x-input label="{{ __('lang.option_b') }}" wire:model="option_b"/>
				<x-input label="{{ __('lang.option_c') }}" wire:model="option_c"/>
				<x-input label="{{ __('lang.option_d') }}" wire:model="option_d"/>
			</div>

			<x-select label="{{ __('lang.correct_answer') }}" wire:model="correct_answer" :options="[
                ['id' => 'a', 'name' => __('lang.option_a')],
                ['id' => 'b', 'name' => __('lang.option_b')],
                ['id' => 'c', 'name' => __('lang.option_c')],
                ['id' => 'd', 'name' => __('lang.option_d')],
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
