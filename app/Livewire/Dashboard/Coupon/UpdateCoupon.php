<?php

namespace App\Livewire\Dashboard\Coupon;

use App\Models\Coupon;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Mary\Traits\Toast;

class UpdateCoupon extends Component
{
    use Toast;

    public bool $modalUpdate = false;

    public Coupon $coupon;

    public $code;

    public $type;

    public $value;

    public $min_order_value;

    public $max_discount;

    public $usage_limit;

    public $expiry_date;

    public $status;

    public function mount(): void
    {
        $this->code = $this->coupon->code;
        $this->type = $this->coupon->type;
        $this->value = $this->coupon->value;
        $this->min_order_value = $this->coupon->min_order_value;
        $this->max_discount = $this->coupon->max_discount;
        $this->usage_limit = $this->coupon->usage_limit;
        $this->expiry_date = $this->coupon->expiry_date->format('Y-m-d');
        $this->status = $this->coupon->status->value;
    }

    public function rules(): array
    {
        return [
            'code' => 'required|string|max:255|unique:coupons,code,'.$this->coupon->id,
            'type' => 'required|in:fixed,percent',
            'value' => 'required|numeric|min:0',
            'min_order_value' => 'required|numeric|min:0',
            'max_discount' => 'required|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'expiry_date' => 'required|date',
            'status' => 'required|in:active,inactive',
        ];
    }

    public function saveUpdate(): void
    {
        $this->authorize('edit_coupon');
        $this->validate();
        $this->coupon->update([
            'code' => $this->code,
            'type' => $this->type,
            'value' => $this->value,
            'min_order_value' => $this->min_order_value,
            'max_discount' => $this->max_discount,
            'usage_limit' => $this->usage_limit,
            'expiry_date' => $this->expiry_date,
            'status' => $this->status,
        ]);
        $this->modalUpdate = false;
        $this->dispatch('render')->component(CouponData::class);
        $this->success(__('lang.updated_successfully', ['attribute' => __('lang.coupon')]));
    }

    public function render(): View
    {
        return view('livewire.dashboard.coupon.update-coupon');
    }

    public function resetError(): void
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
}
