<?php

namespace App\Livewire\Dashboard\PaymentGateway;

use App\Models\PaymentGateway;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

#[Title('create_payment_gateway')]
class CreatePaymentGateway extends Component
{
    use Toast, WithFileUploads;

    public $name;

    public $slug;

    public $is_active = false;

    public $is_default = false;

    public $currency = 'EGP';

    public $mode = 'test';

    public $sort_order = 0;

    public $logo;

    // Dynamic credentials
    public $credentials = [];

    public function mount(): void
    {
        $this->authorize('create_payment_gateway');
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
                'label' => __('lang.add'),
                'icon' => 'o-plus',
            ],
        ];
    }

    public function render(): View
    {
        return view('livewire.dashboard.payment-gateway.create-payment-gateway');
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:payment_gateways,slug',
            'currency' => 'required|string|max:10',
            'mode' => 'required|in:test,live',
            'sort_order' => 'nullable|integer',
            'logo' => 'nullable|image|max:5000|mimes:jpg,jpeg,png,gif,webp,svg',
        ]);

        if ($this->is_default) {
            PaymentGateway::query()->update(['is_default' => false]);
        }

        $gateway = PaymentGateway::create([
            'name' => $this->name,
            'slug' => $this->slug,
            'credentials' => $this->credentials,
            'is_active' => $this->is_active,
            'is_default' => $this->is_default,
            'currency' => $this->currency,
            'mode' => $this->mode,
            'sort_order' => $this->sort_order ?? 0,
        ]);

        if ($this->logo) {
            $gateway->addMedia($this->logo->getRealPath())->toMediaCollection('logo');
        }
        $this->success(__('lang.added_successfully', ['attribute' => __('lang.payment_gateway')]));

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
