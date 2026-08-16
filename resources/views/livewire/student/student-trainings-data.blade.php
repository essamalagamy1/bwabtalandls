<div>
    <x-header title="{{ __('lang.my_trainings') ?? 'تدريباتي' }}" subtitle="{{ __('lang.all_trainings_for_your_grade') ?? 'جميع التدريبات المخصصة لصفك الدراسي' }}" separator>
        <x-slot:middle class="!justify-end">
            <x-input icon="o-magnifying-glass" placeholder="{{ __('lang.search') }}..." wire:model.live="search" class="w-full lg:w-auto" clearable />
        </x-slot:middle>
    </x-header>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @forelse($trainings as $training)
            <x-card title="{{ $training->title }}" class="shadow-xl">
                <x-slot:figure>
                    @if($training->getFirstMediaUrl('image'))
                        <img src="{{ $training->getFirstMediaUrl('image') }}" alt="Training Image" class="h-48 w-full object-cover"/>
                    @else
                        <div class="h-48 w-full bg-base-300 flex items-center justify-center">
                            <x-icon name="o-play-circle" class="w-16 h-16 text-base-content/30" />
                        </div>
                    @endif
                </x-slot:figure>
                
                <div class="mt-4">
                    <p class="text-base-content/70 text-sm mb-4">{{ Str::limit($training->description, 100) }}</p>
                    <div class="flex justify-between items-center text-sm">
                        <span class="badge badge-outline">{{ __('lang.'.$training->type) }}</span>
                        <span class="text-base-content/50 font-bold">{{ $training->week?->title }}</span>
                    </div>
                </div>

                <x-slot:actions>
                    <x-button label="{{ __('lang.view') }}" class="btn-primary w-full" icon="o-eye" />
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
