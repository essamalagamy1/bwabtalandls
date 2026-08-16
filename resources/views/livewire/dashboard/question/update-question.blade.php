<div>
	<x-button icon="o-pencil" class="btn-sm btn-ghost" @click="$wire.modalUpdate = true" tooltip="{{ __('lang.update') }}" wire:click="resetError"/>
	<x-modal wire:model="modalUpdate" title="{{ __('lang.update') }} {{ __('lang.question') }}" box-class="modal-box-800">
		<x-form wire:submit="saveUpdate">
			@if($question->getFirstMediaUrl('image'))
				<div class="text-center mb-3 mx-auto">
					<x-avatar :image="$question->getFirstMediaUrl('image')" class="w-32 h-auto rounded"/>
				</div>
			@endif
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
				<x-button label="{{ __('lang.cancel') }}" @click="$wire.modalUpdate = false"/>
				<x-button label="{{ __('lang.update') }}" class="btn btn-primary" wire:loading.attr="disabled" type="submit" spinner="saveUpdate"/>
			</x-slot:actions>
		</x-form>
	</x-modal>
</div>
