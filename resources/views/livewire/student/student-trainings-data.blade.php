<div>
    <x-header title="{{ __('lang.my_trainings') ?? 'تدريباتي' }}" subtitle="{{ __('lang.all_trainings_for_your_grade') ?? 'جميع التدريبات المخصصة لصفك الدراسي' }}" separator>
        <x-slot:middle class="!justify-end gap-2">
            @if(!empty($weeks) && count($weeks) > 0)
                <x-select 
                    icon="o-calendar-days" 
                    :options="$weeks" 
                    option-value="id" 
                    option-label="title" 
                    placeholder="{{ __('lang.all_weeks') ?? 'جميع الأسابيع' }}" 
                    wire:model.live="selectedWeek" 
                    class="w-full lg:w-48" 
                />
            @endif
            <x-input icon="o-magnifying-glass" placeholder="{{ __('lang.search') }}..." wire:model.live="search" class="w-full lg:w-auto" clearable />
        </x-slot:middle>
    </x-header>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($trainings as $training)
            <x-card title="{{ $training->title }}" class="shadow-xl">
                <x-slot:figure>
                    <div class="h-48 w-full bg-base-300 flex items-center justify-center">
                        <x-icon name="o-play-circle" class="w-16 h-16 text-base-content/30" />
                    </div>
                </x-slot:figure>
                
                <div class="mt-4">
                    <p class="text-base-content/70 text-sm mb-4">{{ Str::limit($training->description, 100) }}</p>
                    <div class="flex justify-between items-center mb-3">
                        <span class="badge badge-outline">{{ $training->week?->title }}</span>
                        <span class="text-xs text-base-content/50">{{ $training->week?->semester?->name }}</span>
                    </div>
                    <div class="flex justify-between items-center text-sm">
                        <span class="badge badge-outline">{{ __('lang.'.$training->type) }}</span>
                    </div>
                </div>

                <x-slot:actions>
                    @php
                        $link = !empty($training->url) ? $training->url : ($training->getFirstMediaUrl('training_file') ?: '#');
                    @endphp
                    <x-button label="{{ __('lang.view') }}" class="btn-primary w-full" icon="o-eye" link="{{ $link }}" external target="_blank" />
                </x-slot:actions>
            </x-card>
        @empty
            <div class="col-span-full">
                <x-alert icon="o-exclamation-triangle" class="alert-info">
                    {{ __('lang.no_data') }}
                </x-alert>
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $trainings->links() }}
    </div>
</div>
