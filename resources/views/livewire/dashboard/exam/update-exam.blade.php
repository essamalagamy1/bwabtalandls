<div>
	<x-button icon="o-pencil" class="btn-sm btn-ghost" @click="$wire.modalUpdate = true" tooltip="{{ __('lang.update') }}" wire:click="resetError"/>
	<x-modal wire:model="modalUpdate" title="{{ __('lang.update') }} {{ __('lang.exam') }}" box-class="modal-box-700">
		<x-form wire:submit="saveUpdate">
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

					@if($exam->hasAttachment())
						<div class="mt-3 p-2 bg-base-100 rounded-lg border border-base-300 flex items-center justify-between gap-2">
							<div class="flex items-center gap-2 overflow-hidden">
								@if($exam->attachment_type === 'image')
									<x-icon name="o-photo" class="w-5 h-5 text-primary shrink-0"/>
									<a href="{{ $exam->getFirstMediaUrl('attachment') }}" target="_blank" class="text-xs text-primary underline truncate">{{ $exam->getFirstMedia('attachment')?->file_name }}</a>
								@elseif($exam->attachment_type === 'audio')
									<x-icon name="o-speaker-wave" class="w-5 h-5 text-secondary shrink-0"/>
									<span class="text-xs truncate">{{ $exam->getFirstMedia('attachment')?->file_name }}</span>
								@else
									<x-icon name="o-document" class="w-5 h-5 text-info shrink-0"/>
									<span class="text-xs truncate">{{ $exam->getFirstMedia('attachment')?->file_name }}</span>
								@endif
							</div>
							<x-button icon="o-trash" class="btn-xs btn-error btn-ghost" wire:click="deleteAttachment" wire:confirm="{{ __('lang.confirm_delete') }}" tooltip="{{ __('lang.delete_attachment') }}"/>
						</div>
					@endif
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
				<x-button label="{{ __('lang.cancel') }}" @click="$wire.modalUpdate = false"/>
				<x-button label="{{ __('lang.update') }}" class="btn btn-primary" wire:loading.attr="disabled" type="submit" spinner="saveUpdate"/>
			</x-slot:actions>
		</x-form>
	</x-modal>
</div>
