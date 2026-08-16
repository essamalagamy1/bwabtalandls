<?php

namespace App\Livewire\Dashboard\Coupon;

use App\Models\Coupon;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Mary\Traits\Toast;

class CreateCoupon extends Component
{
    use Toast;

    public bool $modalAdd = false;

    public $code;

    public $type = 'fixed';

    public $value;

    public $min_order_value;

    public $max_discount;

    public $usage_limit;

    public $expiry_date;

    public $status = 'inactive';

    public function render(): View
    {
        return view('livewire.dashboard.coupon.create-coupon');
    }

    public function rules(): array
    {
        return [
            'code' => 'required|string|max:255|unique:coupons,code',
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0',
            'min_order_value' => 'required|numeric|min:0',
            'max_discount' => 'required|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'expiry_date' => 'required|date|after:today',
            'status' => 'required|in:active,inactive',
        ];
    }

    public function saveAdd(): void
    {
        $this->authorize('create_coupon');
        $this->validate();
        Coupon::create([
            'code' => $this->code,
            'country_id' => 1,
            'type' => $this->type,
            'value' => $this->value,
            'min_order_value' => $this->min_order_value,
            'max_discount' => $this->max_discount,
            'usage_limit' => $this->usage_limit,
            'expiry_date' => $this->expiry_date,
            'status' => $this->status,
        ]);
        $this->modalAdd = false;
        $this->dispatch('render')->component(CouponData::class);
        $this->success(__('lang.added_successfully', ['attribute' => __('lang.coupon')]));
    }

    public function resetData(): void
    {
        $this->reset(['code', 'type', 'value', 'min_order_value', 'max_discount', 'usage_limit', 'expiry_date', 'status']);
        $this->type = 'fixed';
        $this->status = 'inactive';
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
