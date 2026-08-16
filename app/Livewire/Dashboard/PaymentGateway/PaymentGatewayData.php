<?php

namespace App\Livewire\Dashboard\PaymentGateway;

use App\Models\PaymentGateway;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

#[Title('payment_gateways')]
#[Lazy]
class PaymentGatewayData extends Component
{
    use Toast, WithPagination;

    public function placeholder(): View
    {
        return view('livewire.placeholders.page-loading');
    }

    public $search;

    public $filter_status;

    public function mount(): void
    {
        view()->share('breadcrumbs', $this->breadcrumbs());
    }

    public function breadcrumbs(): array
    {
        return [
            [
                'label' => __('lang.payment_gateways'),
                'icon' => 'o-credit-card',
            ],
        ];
    }

    #[On('render')]
    public function render(): View
    {
        $data['gateways'] = PaymentGateway::when($this->search, fn (Builder $query) => $query->where('name', 'like', "%{$this->search}%"))
            ->when($this->filter_status !== null && $this->filter_status !== '', fn (Builder $query) => $query->where('is_active', $this->filter_status))
            ->orderBy('sort_order')
            ->paginate(20);

        return view('livewire.dashboard.payment-gateway.payment-gateway-data', $data);
    }

    public function toggleStatus($id): void
    {
        $this->authorize('edit_payment_gateway');
        $gateway = PaymentGateway::findOrFail($id);
        $gateway->update(['is_active' => ! $gateway->is_active]);
        $this->success(__('lang.updated_successfully', ['attribute' => __('lang.payment_gateway')]));
    }

    public function setDefault($id): void
    {
        $this->authorize('edit_payment_gateway');
        PaymentGateway::query()->update(['is_default' => false]);
        PaymentGateway::findOrFail($id)->update(['is_default' => true]);
        $this->success(__('lang.updated_successfully', ['attribute' => __('lang.payment_gateway')]));
    }

    public function delete($id): void
    {
        $this->authorize('delete_payment_gateway');
        PaymentGateway::findOrFail($id)->delete();
        $this->success(__('lang.deleted_successfully', ['attribute' => __('lang.payment_gateway')]));
    }
}
