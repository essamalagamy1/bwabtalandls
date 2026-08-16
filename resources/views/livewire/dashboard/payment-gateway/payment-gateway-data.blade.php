<div>
    <x-card title="{{ __('lang.payment_gateways') }}" shadow class="mb-3">
        <x-slot:menu>
            @can('create_payment_gateway')
                <x-button label="{{ __('lang.add') }}" icon="o-plus" link="{{ route('dashboard.payment-gateways.create') }}"
                    class="btn-primary btn-sm" wire:navigate />
            @endcan
        </x-slot:menu>
        <div class="flex gap-3 mb-3 flex-wrap">
            <div class="w-64">
                <x-input label="{{ __('lang.search') }}" wire:model.live.debounce.300ms="search"
                    placeholder="{{ __('lang.search') }}..." icon="o-magnifying-glass" />
            </div>
            <div class="w-48">
                <x-select label="{{ __('lang.status') }}" wire:model.live="filter_status" :options="[['id' => '1', 'name' => __('lang.active')], ['id' => '0', 'name' => __('lang.inactive')]]"
                    placeholder="{{ __('lang.all') }}" option-value="id" option-label="name" />
            </div>
        </div>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead class="min-w-full divide-y bg-base-300 text-base-content">
                        <tr>
                            <th class="text-center">#</th>
                            <th class="text-center">{{ __('lang.name') }}</th>
                            <th class="text-center">{{ __('lang.slug') }}</th>
                            <th class="text-center">{{ __('lang.currency') }}</th>
                            <th class="text-center">{{ __('lang.mode') }}</th>
                            <th class="text-center">{{ __('lang.status') }}</th>
                            <th class="text-center">{{ __('lang.default') }}</th>
                            <th class="text-center">{{ __('lang.action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($gateways as $gateway)
                            <tr class="bg-base-200">
                                <th class="text-center">{{ $gateways->firstItem() + $loop->index }}</th>
                                <th class="text-nowrap">{{ $gateway->name }}</th>
                                <th class="text-center">{{ $gateway->slug }}</th>
                                <th class="text-center">{{ $gateway->currency }}</th>
                                <th class="text-center">
                                    <x-badge :value="$gateway->mode"
                                        class="bg-{{ $gateway->mode == 'live' ? 'green-500' : 'yellow-500' }}" />
                                </th>
                                <th class="text-center">
                                    <x-toggle wire:click="toggleStatus({{ $gateway->id }})" :checked="$gateway->is_active" />
                                </th>
                                <th class="text-center">
                                    @if ($gateway->is_default)
                                        <x-badge value="{{ __('lang.default') }}" class="bg-blue-500" />
                                    @else
                                        <x-button label="{{ __('lang.set_default') }}"
                                            wire:click="setDefault({{ $gateway->id }})" class="btn-xs btn-ghost" />
                                    @endif
                                </th>
                                <td>
                                    <div class="flex gap-2 justify-center">
                                        @can('edit_payment_gateway')
                                            <x-button noWireNavigate icon="o-pencil" class="btn-sm btn-ghost" link="{{ route('dashboard.payment-gateways.edit', $gateway) }}" tooltip="{{ __('lang.edit') }}" />
                                        @endcan
                                        @can('delete_payment_gateway')
                                            @if($gateway->slug !== 'cod')
                                                <x-button icon="o-trash" class="btn-sm btn-ghost" wire:click="delete({{ $gateway->id }})" tooltip="{{ __('lang.delete') }}"
                                                          wire:confirm="{{__('lang.confirm_delete', ['attribute' => __('lang.payment_gateway')])}}"
                                                          wire:loading.attr="disabled" wire:target="delete({{ $gateway->id }})" spinner="delete({{ $gateway->id }})"/>
                                            @endif
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr class="bg-base-200">
                                <th colspan="8" class="text-center">{{ __('lang.no_data') }}</th>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="flex items-center justify-between px-4 py-3 bg-base-300 text-base-content sm:px-6 min-w-">
                    <div class="flex w-full items-center justify-between">
                        <div class="w-full flex-none">
                            {{ $gateways->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-card>
</div>
