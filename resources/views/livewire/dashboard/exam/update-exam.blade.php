<div>
	<x-button icon="o-pencil" class="btn-sm btn-ghost" @click="$wire.modalUpdate = true" tooltip="{{ __('lang.update') }}" wire:click="resetError"/>
	<x-modal wire:model="modalUpdate" title="{{ __('lang.update') }} {{ __('lang.exam') }}" box-class="modal-box-700">
		<x-form wire:submit="saveUpdate">
			<x-input label="{{ __('lang.title') }}" wire:model="title"/>
			<x-textarea label="{{ __('lang.description') }}" wire:model="description" rows="3"/>
			<div class="grid grid-cols-2 gap-4">
				<x-select label="{{ __('lang.stage') }}" wire:model.live="stage_id" :options="$all_stages" option-value="id" option-label="name" placeholder="{{ __('lang.select') }}..." />
				<x-select label="{{ __('lang.grade') }}" wire:model.live="grade_id" :options="$all_grades" option-value="id" option-label="name" placeholder="{{ __('lang.select') }}..." />
				<x-select label="{{ __('lang.semester') }}" wire:model.live="semester_id" :options="$all_semesters" option-value="id" option-label="name" placeholder="{{ __('lang.select') }}..." />
				<x-select label="{{ __('lang.week') }}" wire:model.live="week_id" :options="$all_weeks" option-value="id" option-label="title" placeholder="{{ __('lang.select') }}..." />
			</div>
			<div class="grid grid-cols-2 gap-4 mt-4">
				<x-input label="{{ __('lang.duration_minutes') }}" wire:model="duration_minutes" type="number" min="1"/>
				<x-input label="{{ __('lang.passing_score') }} (%)" wire:model="passing_score" type="number" min="0" max="100"/>
			</div>
			<x-toggle label="{{ __('lang.is_active') }}" wire:model="is_active" class="mt-4" />
			<x-slot:actions>
				<x-button label="{{ __('lang.cancel') }}" @click="$wire.modalUpdate = false"/>
				<x-button label="{{ __('lang.update') }}" class="btn btn-primary" wire:loading.attr="disabled" type="submit" spinner="saveUpdate"/>
			</x-slot:actions>
		</x-form>
	</x-modal>
</div>
