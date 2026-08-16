<div>
    <x-card title="{{ __('lang.add') }} {{ __('lang.payment_gateway') }}" shadow class="mb-3">
        <x-form wire:submit="save">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-input label="{{ __('lang.name') }}" wire:model="name" placeholder="Paymob" required />
                <x-input label="{{ __('lang.slug') }}" wire:model="slug" placeholder="paymob" required />
                <x-input label="{{ __('lang.currency') }}" wire:model="currency" placeholder="EGP" />
                <x-select label="{{ __('lang.mode') }}" wire:model="mode" :options="[['id' => 'test', 'name' => 'Test'], ['id' => 'live', 'name' => 'Live']]" option-value="id"
                    option-label="name" />
                <x-input label="{{ __('lang.sort_order') }}" wire:model="sort_order" type="number" />
                <div class="flex gap-4 items-center">
                    <x-toggle label="{{ __('lang.active') }}" wire:model="is_active" />
                    <x-toggle label="{{ __('lang.default') }}" wire:model="is_default" />
                </div>
            </div>

            <x-hr />
            <h3 class="font-bold text-lg mb-3">{{ __('lang.credentials') }}</h3>

            <div class="space-y-3">
                @foreach ($credentials as $index => $cred)
                    <div class="flex gap-2 items-end">
                        <x-input label="{{ __('lang.key') }}" wire:model="credentials.{{ $index }}.key" placeholder="api_key" class="flex-1" />
                        <x-input label="{{ __('lang.value') }}" wire:model="credentials.{{ $index }}.value" placeholder="sk_live_xxx" class="flex-1" />
                        <x-button icon="o-trash" wire:click="removeCredential({{ $index }})" class="btn-ghost btn-sm" />
                    </div>
                @endforeach
            </div>

            <x-button label="{{ __('lang.add') }} {{ __('lang.credential') }}" wire:click="addCredential" icon="o-plus" class="btn-ghost btn-sm mt-2" />

            <x-slot:actions>
                <x-button label="{{ __('lang.cancel') }}" link="{{ route('dashboard.payment-gateways') }}"
                    wire:navigate />
                <x-button label="{{ __('lang.save') }}" type="submit" class="btn-primary" spinner="save" />
            </x-slot:actions>
        </x-form>
    </x-card>
</div>
