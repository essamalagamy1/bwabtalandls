<?php

namespace App\Livewire\Dashboard\Coupon;

use App\Models\Coupon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Attributes\Lazy;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Mary\Traits\Toast;

#[Title('coupons')]
#[Lazy]
class CouponData extends Component
{
    use Toast, WithPagination;

    public function placeholder(): View
    {
        return view('livewire.placeholders.page-loading');
    }

    public $all_coupon;

    public $search_coupon_id;

    public $filter_type;

    public $filter_status;

    public function mount(): void
    {
        $this->all_coupon = Coupon::get(['id', 'code'])->toArray();
        view()->share('breadcrumbs', $this->breadcrumbs());
    }

    public function breadcrumbs(): array
    {
        return [
            [
                'label' => __('lang.coupons'),
                'icon' => 'o-ticket',
            ],
        ];
    }

    #[On('render')]
    public function render(): View
    {
        $data['coupons'] = Coupon::when($this->search_coupon_id, fn (Builder $query) => $query->where('id', $this->search_coupon_id))
            ->when($this->filter_type, fn (Builder $query) => $query->where('type', $this->filter_type))
            ->when($this->filter_status, fn (Builder $query) => $query->where('status', $this->filter_status))
            ->latest()
            ->paginate(20);

        return view('livewire.dashboard.coupon.coupon-data', $data);
    }

    public function delete($id): void
    {
        $this->authorize('delete_coupon');
        Coupon::findOrFail($id)->delete();
        $this->success(__('lang.deleted_successfully', ['attribute' => __('lang.coupon')]));
    }
}
