<div>
    <x-button icon="o-pencil" class="btn-sm btn-ghost" @click="$wire.modalUpdate = true" tooltip="{{ __('lang.update') }}"
        wire:click="resetError" />
    <x-modal wire:model="modalUpdate" title="{{ __('lang.update') }} {{ __('lang.semester') }}" box-class="modal-box-600">
        <x-form wire:submit="saveUpdate">
            <x-input label="{{ __('lang.name') }}" wire:model="name" />
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <x-choices-offline required label="{{ __('lang.stage') }}" wire:model.live="stage_id" :options="$all_stages"
                    option-value="id" option-label="name" single searchable placeholder="{{ __('lang.select') }}..." />
                <x-choices-offline required label="{{ __('lang.grade') }}" wire:model="grade_id" :options="$all_grades"
                    option-value="id" option-label="name" single searchable placeholder="{{ __('lang.select') }}..."
                    :disabled="!$stage_id" />
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <x-input type="number" label="{{ __('lang.academic_year_from') }}" wire:model="academic_year_from"
                    placeholder="2024" icon="o-calendar" />
                <x-input type="number" label="{{ __('lang.academic_year_to') }}" wire:model="academic_year_to"
                    placeholder="2025" icon="o-calendar" />
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <x-datepicker required label="{{ __('lang.start_date') }}" wire:model="start_date" icon="o-calendar" />
                <x-datepicker required label="{{ __('lang.end_date') }}" wire:model="end_date" icon="o-calendar" />
            </div>
            {{-- <x-toggle label="{{ __('lang.active') }}" wire:model="is_active"/> --}}
            <x-checkbox label="هل تريد إنشاء الأسابيع الخاصة بهذا الفصل بشكل تلقائي؟"
                wire:model.live="auto_generate_weeks"
                hint="سيتم إنشاء الأسابيع بناءً على تاريخ بدء ونهاية الفصل (فقط إذا لم تكن هناك أسابيع مسجلة)" />
            <x-slot:actions>
                <x-button label="{{ __('lang.cancel') }}" @click="$wire.modalUpdate = false" />
                <x-button label="{{ __('lang.update') }}" class="btn btn-primary" wire:loading.attr="disabled"
                    type="submit" spinner="saveUpdate" />
            </x-slot:actions>
        </x-form>
    </x-modal>
</div>
