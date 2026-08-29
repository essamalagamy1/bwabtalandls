<div>
	<x-button icon="o-plus" class="btn-primary btn-sm mt-2 md:mt-0" label="{{ __('lang.add') }}" @click="$wire.modalAdd = true" wire:click="resetData"/>
	<x-modal wire:model="modalAdd" title="{{ __('lang.add') }} {{ __('lang.exam') }}" box-class="modal-box-700">
		<x-form wire:submit="saveAdd">
			<x-input label="{{ __('lang.title') }}" wire:model="title"/>
			<x-textarea label="{{ __('lang.description') }}" wire:model="description" rows="3"/>
			<div class="grid grid-cols-2 gap-4">
				<x-select label="{{ __('lang.stage') }}" wire:model.live="stage_id" :options="$all_stages" option-value="id" option-label="name" placeholder="{{ __('lang.select') }}..." />
				<x-select label="{{ __('lang.grade') }}" wire:model.live="grade_id" :options="$all_grades" option-value="id" option-label="name" placeholder="{{ __('lang.select') }}..." />
				<x-select label="{{ __('lang.semester') }}" wire:model.live="semester_id" :options="$all_semesters" option-value="id" option-label="name_with_academic_year" placeholder="{{ __('lang.select') }}..." />
				<x-select label="{{ __('lang.week') }}" wire:model.live="week_id" :options="$all_weeks" option-value="id" option-label="title" placeholder="{{ __('lang.select') }}..." />
			</div>
			<div class="grid grid-cols-2 gap-4 mt-4">
				<x-input label="{{ __('lang.duration_minutes') }}" wire:model="duration_minutes" type="number" min="1"/>
				<x-input label="{{ __('lang.passing_score') }} (%)" wire:model="passing_score" type="number" min="0" max="100"/>
			</div>
			<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 p-3 bg-base-200/60 rounded-xl border border-base-300">
				<div>
					<x-file label="{{ __('lang.attachment_file') }}" wire:model="attachment" accept="image/*,audio/*" icon="o-paper-clip"/>
					<div class="text-xs text-base-content/60 mt-1">{{ __('lang.image_or_audio') }} (JPG, PNG, WebP, MP3, WAV, M4A)</div>
					<x-progress class="progress-primary h-0.5 mt-1" indeterminate wire:loading wire:target="attachment"/>
				</div>
				<div>
					<x-input label="{{ __('lang.media_views_limit') }}" wire:model="media_views_limit" type="number" min="0" icon="o-eye"/>
					<div class="text-xs text-info mt-1 flex items-center gap-1">
						<x-icon name="o-information-circle" class="w-4 h-4 shrink-0"/>
						<span>{{ __('lang.media_views_limit_hint') }}</span>
					</div>
				</div>
			</div>
			<x-toggle label="{{ __('lang.is_active') }}" wire:model="is_active" class="mt-4" />
			<x-slot:actions>
				<x-button label="{{ __('lang.cancel') }}" @click="$wire.modalAdd = false"/>
				<x-button label="{{ __('lang.save') }}" class="btn btn-primary" wire:loading.attr="disabled" type="submit" spinner="saveAdd"/>
			</x-slot:actions>
		</x-form>
	</x-modal>
</div>
