<?php

namespace App\Livewire\Dashboard\PaymentGateway;

use App\Models\PaymentGateway;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

#[Title('update_payment_gateway')]
class UpdatePaymentGateway extends Component
{
    use Toast, WithFileUploads;

    public PaymentGateway $gateway;

    public $name;

    public $slug;

    public $is_active = false;

    public $is_default = false;

    public $currency = 'EGP';

    public $mode = 'test';

    public $sort_order = 0;

    public $logo;

    // Dynamic credentials as key-value pairs
    public $credentials = [];

    public function mount(PaymentGateway $gateway): void
    {
        $this->authorize('edit_payment_gateway');
        $this->gateway = $gateway;
        $this->name = $gateway->name;
        $this->slug = $gateway->slug;
        $this->is_active = $gateway->is_active;
        $this->is_default = $gateway->is_default;
        $this->currency = $gateway->currency ?? 'EGP';
        $this->mode = $gateway->mode ?? 'test';
        $this->sort_order = $gateway->sort_order ?? 0;
        // Convert credentials array to key-value format for editing
        $this->credentials = [];
        if (is_array($gateway->credentials)) {
            foreach ($gateway->credentials as $key => $value) {
                $this->credentials[] = ['key' => $key, 'value' => $value];
            }
        }

        view()->share('breadcrumbs', $this->breadcrumbs());
    }

    public function breadcrumbs(): array
    {
        return [
            [
                'label' => __('lang.payment_gateways'),
                'url' => route('dashboard.payment-gateways'),
                'icon' => 'o-credit-card',
            ],
            [
                'label' => __('lang.edit'),
                'icon' => 'o-pencil',
            ],
        ];
    }

    public function render(): View
    {
        return view('livewire.dashboard.payment-gateway.update-payment-gateway');
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:payment_gateways,slug,'.$this->gateway->id,
            'currency' => 'required|string|max:10',
            'mode' => 'required|in:test,live',
            'sort_order' => 'nullable|integer',
            'logo' => 'nullable|image|max:5000|mimes:jpg,jpeg,png,gif,webp,svg',
        ]);

        // Convert key-value array back to associative array
        $credentialsArray = [];
        foreach ($this->credentials as $cred) {
            if (! empty($cred['key'])) {
                $credentialsArray[$cred['key']] = $cred['value'] ?? '';
            }
        }

        if ($this->is_default && ! $this->gateway->is_default) {
            PaymentGateway::query()->update(['is_default' => false]);
        }

        $this->gateway->update([
            'name' => $this->name,
            'slug' => $this->slug,
            'credentials' => $credentialsArray,
            'is_active' => $this->is_active,
            'is_default' => $this->is_default,
            'currency' => $this->currency,
            'mode' => $this->mode,
            'sort_order' => $this->sort_order ?? 0,
        ]);

        if ($this->logo && is_object($this->logo)) {
            $this->gateway->addMedia($this->logo->getRealPath())->toMediaCollection('logo');
        }

        $this->success(__('lang.updated_successfully', ['attribute' => __('lang.payment_gateway')]));

        return $this->redirect(route('dashboard.payment-gateways'));
    }

    public function addCredential(): void
    {
        $this->credentials[] = ['key' => '', 'value' => ''];
    }

    public function removeCredential($index): void
    {
        unset($this->credentials[$index]);
        $this->credentials = array_values($this->credentials);
    }
}
