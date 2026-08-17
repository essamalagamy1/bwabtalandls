@php use App\Services\FileService; @endphp
@assets()
<link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
<script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
@endassets()
<div>
    <x-card title="{{ __('lang.site_settings') }}" shadow class="mb-3">
        <x-form wire:submit="saveUpdate">
            {{-- Basic Info --}}
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-3">{{ __('lang.basic_info') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <x-input label="{{ __('lang.name_ar') }}" wire:model="name_ar"/>
                    <x-input dir="ltr" label="{{ __('lang.name_en') }}" wire:model="name_en"/>
                    {{--                    <x-select label="{{ __('lang.theme') }}" wire:model="theme" :options="[--}}
                    {{--	                    ['id' => 'template1', 'name' => 'template1'],--}}
                    {{--	                    ['id' => 'template2', 'name' => 'template2']--}}
                    {{--	                    ]"/>--}}
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-3">
                    <x-textarea label="{{ __('lang.description_ar') }}" wire:model="description_ar" rows="3"/>
                    <x-textarea dir="ltr" label="{{ __('lang.description_en') }}" wire:model="description_en" rows="3"/>
                </div>
            </div>

            {{-- Colors --}}
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-3">{{ __('lang.color_settings') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <x-colorpicker wire:model="color_primary" label="{{ __('lang.color_primary') }}" suffix="Hex code"/>
                    <x-colorpicker wire:model="color_secondary" label="{{ __('lang.color_secondary') }}" suffix="Hex code"/>
                    <x-colorpicker wire:model="color_accent" label="{{ __('lang.color_accent') }}" suffix="Hex code"/>
                </div>
            </div>

            {{-- Logos --}}
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-3">{{ __('lang.logos') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                        <div class="flex justify-center mb-4">
                            <div>
                                <x-file label="{{ __('lang.logo_white') }}" wire:model="logo_white" accept="image/*"
                                        hint="{{ __('lang.click_on_image_to_change') }}" class="cursor-pointer">
                                    @if ($logo_white && method_exists($logo_white, 'temporaryUrl'))
                                        <img alt="logo_white" src="{{ $logo_white->temporaryUrl() }}"
                                             class="!w-24 !h-24 rounded-lg object-contain bg-gray-800 p-1"/>
                                    @else
                                        <img alt="logo_white" src="{{ $setting->getFirstMediaUrl('logo_white') }}"
                                             class="!w-24 !h-24 rounded-lg object-contain bg-gray-800 p-1"/>
                                    @endif
                                </x-file>
                                <div wire:loading wire:target="logo_white" class="mt-2">
                                    <x-progress class="progress-primary h-1" indeterminate/>
                                    <p class="text-sm text-center text-primary">{{ __('lang.uploading_image') }}...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-center mb-4">
                            <div>
                                <x-file label="{{ __('lang.logo_black') }}" wire:model="logo_black" accept="image/*"
                                        hint="{{ __('lang.click_on_image_to_change') }}" class="cursor-pointer">
                                    @if ($logo_black && method_exists($logo_black, 'temporaryUrl'))
                                        <img alt="logo_black" src="{{ $logo_black->temporaryUrl() }}"
                                             class="!w-24 !h-24 rounded-lg object-contain bg-gray-100 p-1"/>
                                    @else
                                        <img alt="logo_black" src="{{ $setting->getFirstMediaUrl('logo_black') }}"
                                             class="!w-24 !h-24 rounded-lg object-contain bg-gray-100 p-1"/>
                                    @endif
                                </x-file>
                                <div wire:loading wire:target="logo_black" class="mt-2">
                                    <x-progress class="progress-primary h-1" indeterminate/>
                                    <p class="text-sm text-center text-primary">{{ __('lang.uploading_image') }}...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-center mb-4">
                            <div>
                                <x-file label="{{ __('lang.favicon') }}" wire:model="favicon" accept="image/*"
                                        hint="{{ __('lang.click_on_image_to_change') }}" class="cursor-pointer">
                                    @if ($favicon && method_exists($favicon, 'temporaryUrl'))
                                        <img alt="favicon" src="{{ $favicon->temporaryUrl() }}"
                                             class="!w-24 !h-24 rounded-lg object-contain bg-gray-100 p-1"/>
                                    @else
                                        <img alt="favicon" src="{{ $setting->getFirstMediaUrl('favicon') }}"
                                             class="!w-24 !h-24 rounded-lg object-contain bg-gray-100 p-1"/>
                                    @endif
                                </x-file>
                                <div wire:loading wire:target="favicon" class="mt-2">
                                    <x-progress class="progress-primary h-1" indeterminate/>
                                    <p class="text-sm text-center text-primary">{{ __('lang.uploading_image') }}...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Contact Info --}}
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-3">{{ __('lang.contact_info') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <x-textarea label="{{ __('lang.address_ar') }}" wire:model="address_ar" rows="3"/>
                    <x-textarea dir="ltr" label="{{ __('lang.address_en') }}" wire:model="address_en"
                                rows="3"/>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mt-3">
                    <x-input dir="ltr" label="{{ __('lang.phone') }}" wire:model="phone"/>
                    <x-input label="{{ __('lang.email') }}" wire:model="email" type="email"/>
                </div>
            </div>

            {{-- Google OAuth Settings --}}
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-3">{{ __('lang.google_oauth_settings') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <x-input dir="ltr" label="{{ __('lang.google_client_id') }}" wire:model="google_client_id"
                             hint="{{ __('lang.google_client_id_hint') }}"/>
                    <x-input dir="ltr" label="{{ __('lang.google_client_secret') }}"
                             wire:model="google_client_secret" hint="{{ __('lang.google_client_secret_hint') }}"/>
                    <x-input dir="ltr" label="{{ __('lang.google_redirect_uri') }}"
                             wire:model="google_redirect_uri" hint="{{ __('lang.google_redirect_uri_hint') }}"/>
                </div>
            </div>

            {{-- About Us --}}
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-3">{{ __('lang.about_us') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <x-trix required wire:model="about_us_ar" label="{{ __('lang.about_us_ar') }}"
                            key="{{ \Illuminate\Support\Str::random(20) }}"></x-trix>
                    <x-trix dir="ltr" required wire:model="about_us_en" label="{{ __('lang.about_us_en') }}"
                            key="{{ \Illuminate\Support\Str::random(20) }}"></x-trix>
                </div>
            </div>

            {{-- Shipping & Returns --}}
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-3">{{ __('lang.shipping_returns') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <x-trix required wire:model="shipping_returns_ar" label="{{ __('lang.shipping_returns_ar') }}"
                            key="{{ \Illuminate\Support\Str::random(20) }}"></x-trix>
                    <x-trix dir="ltr" required wire:model="shipping_returns_en"
                            label="{{ __('lang.shipping_returns_en') }}"
                            key="{{ \Illuminate\Support\Str::random(20) }}"></x-trix>
                </div>
            </div>

            {{-- Privacy Policy --}}
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-3">{{ __('lang.privacy_policy') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <x-trix required wire:model="privacy_policy_ar" label="{{ __('lang.privacy_policy_ar') }}"
                            key="{{ \Illuminate\Support\Str::random(20) }}"></x-trix>
                    <x-trix dir="ltr" required wire:model="privacy_policy_en"
                            label="{{ __('lang.privacy_policy_en') }}"
                            key="{{ \Illuminate\Support\Str::random(20) }}"></x-trix>
                </div>
            </div>

            {{-- Terms and Conditions --}}
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-3">{{ __('lang.terms_and_conditions') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <x-trix required wire:model="terms_and_conditions_ar"
                            label="{{ __('lang.terms_and_conditions_ar') }}"
                            key="{{ \Illuminate\Support\Str::random(20) }}"></x-trix>
                    <x-trix dir="ltr" required wire:model="terms_and_conditions_en"
                            label="{{ __('lang.terms_and_conditions_en') }}"
                            key="{{ \Illuminate\Support\Str::random(20) }}"></x-trix>
                </div>
            </div>

            {{-- refund policy --}}

            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-3">{{ __('lang.refund_policy') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <x-trix required wire:model="refund_policy_ar" label="{{ __('lang.refund_policy_ar') }}"
                            key="{{ \Illuminate\Support\Str::random(20) }}"></x-trix>
                    <x-trix dir="ltr" required wire:model="refund_policy_en"
                            label="{{ __('lang.refund_policy_en') }}"
                            key="{{ \Illuminate\Support\Str::random(20) }}"></x-trix>
                </div>
            </div>

            {{-- shipping policy --}}
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-3">{{ __('lang.shipping_policy') }}</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <x-trix required wire:model="shipping_policy_ar" label="{{ __('lang.shipping_policy_ar') }}"
                            key="{{ \Illuminate\Support\Str::random(20) }}"></x-trix>
                    <x-trix dir="ltr" required wire:model="shipping_policy_en"
                            label="{{ __('lang.shipping_policy_en') }}"
                            key="{{ \Illuminate\Support\Str::random(20) }}"></x-trix>
                </div>
            </div>

            <div class="flex justify-end">
                @can('edit_site_setting')
                    <x-button label="{{ __('lang.update') }}" class="btn btn-primary" wire:loading.attr="disabled"
                        type="submit" spinner="saveUpdate" />
                @endcan
            </div>
        </x-form>
    </x-card>
</div>
