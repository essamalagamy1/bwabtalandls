<?php

namespace App\Livewire\Dashboard\SiteSetting;

use App\Models\SiteSetting;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Mary\Traits\Toast;

#[Title('site_settings')]
class UpdateSiteSetting extends Component
{
    use Toast, WithFileUploads;

    public $setting;

    public $name_ar;

    public $name_en;

    public $theme;

    public $description_ar;

    public $description_en;

    public $logo_white;

    public $logo_black;

    public $favicon;

    public $about_us_ar;

    public $about_us_en;

    public $shipping_returns_ar;

    public $shipping_returns_en;

    public $privacy_policy_ar;

    public $privacy_policy_en;

    public $terms_and_conditions_ar;

    public $terms_and_conditions_en;

    public $refund_policy_ar;

    public $refund_policy_en;

    public $shipping_policy_ar;

    public $shipping_policy_en;

    public $address_ar;

    public $address_en;

    public $phone;

    public $email;

    public $google_client_id;

    public $google_client_secret;

    public $google_redirect_uri;

    public $color_primary;

    public $color_secondary;

    public $color_accent;

    public function mount(): void
    {
        $this->authorize('show_site_setting');
        $this->setting = SiteSetting::getSetting();

        $this->name_ar = $this->setting->getTranslation('name', 'ar');
        $this->name_en = $this->setting->getTranslation('name', 'en');
        $this->theme = $this->setting->theme ?? 'template1';
        $this->description_ar = $this->setting->getTranslation('description', 'ar');
        $this->description_en = $this->setting->getTranslation('description', 'en');
        $this->about_us_ar = $this->setting->getTranslation('about_us', 'ar');
        $this->about_us_en = $this->setting->getTranslation('about_us', 'en');
        $this->shipping_returns_ar = $this->setting->getTranslation('shipping_returns', 'ar');
        $this->shipping_returns_en = $this->setting->getTranslation('shipping_returns', 'en');
        $this->privacy_policy_ar = $this->setting->getTranslation('privacy_policy', 'ar');
        $this->privacy_policy_en = $this->setting->getTranslation('privacy_policy', 'en');
        $this->terms_and_conditions_ar = $this->setting->getTranslation('terms_and_conditions', 'ar');
        $this->terms_and_conditions_en = $this->setting->getTranslation('terms_and_conditions', 'en');
        $this->address_ar = $this->setting->getTranslation('address', 'ar');
        $this->address_en = $this->setting->getTranslation('address', 'en');
        $this->refund_policy_ar = $this->setting->getTranslation('refund_policy', 'ar');
        $this->refund_policy_en = $this->setting->getTranslation('refund_policy', 'en');
        $this->shipping_policy_ar = $this->setting->getTranslation('shipping_policy', 'ar');
        $this->shipping_policy_en = $this->setting->getTranslation('shipping_policy', 'en');
        $this->phone = $this->setting->phone;
        $this->email = $this->setting->email;
        $this->google_client_id = $this->setting->google_client_id;
        $this->google_client_secret = $this->setting->google_client_secret;
        $this->google_redirect_uri = $this->setting->google_redirect_uri;
        $this->color_primary = $this->setting->color_primary ?? '#25376F';
        $this->color_secondary = $this->setting->color_secondary ?? '#FFFEFC';
        $this->color_accent = $this->setting->color_accent ?? '#25376F';

        view()->share('breadcrumbs', $this->breadcrumbs());
    }

    public function breadcrumbs(): array
    {
        return [
            [
                'label' => __('lang.site_settings'),
                'icon' => 'o-cog-6-tooth',
            ],
        ];
    }

    public function rules(): array
    {
        return [
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'theme' => 'required|string|max:255',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'logo_white' => 'nullable|file|max:5000|mimes:jpg,jpeg,png,gif,webp,svg',
            'logo_black' => 'nullable|file|max:5000|mimes:jpg,jpeg,png,gif,webp,svg',
            'favicon' => 'nullable|file|max:2000|mimes:jpg,jpeg,png,gif,webp,svg,ico',
            'about_us_ar' => 'nullable|string',
            'about_us_en' => 'nullable|string',
            'shipping_returns_ar' => 'nullable|string',
            'shipping_returns_en' => 'nullable|string',
            'privacy_policy_ar' => 'nullable|string',
            'privacy_policy_en' => 'nullable|string',
            'terms_and_conditions_ar' => 'nullable|string',
            'terms_and_conditions_en' => 'nullable|string',
            'refund_policy_ar' => 'nullable|string',
            'refund_policy_en' => 'nullable|string',
            'shipping_policy_ar' => 'nullable|string',
            'shipping_policy_en' => 'nullable|string',
            'address_ar' => 'nullable|string',
            'address_en' => 'nullable|string',
            'phone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'google_client_id' => 'nullable|string|max:255',
            'google_client_secret' => 'nullable|string|max:255',
            'google_redirect_uri' => 'nullable|string|max:255',
            'color_primary' => 'nullable|string|max:20',
            'color_secondary' => 'nullable|string|max:20',
            'color_accent' => 'nullable|string|max:20',
        ];
    }

    public function saveUpdate(): void
    {
        $this->authorize('edit_site_setting');

        if ($this->logo_white && (! $this->logo_white instanceof \Illuminate\Http\UploadedFile || ! file_exists($this->logo_white->getRealPath()))) {
            $this->logo_white = null;
        }
        if ($this->logo_black && (! $this->logo_black instanceof \Illuminate\Http\UploadedFile || ! file_exists($this->logo_black->getRealPath()))) {
            $this->logo_black = null;
        }
        if ($this->favicon && (! $this->favicon instanceof \Illuminate\Http\UploadedFile || ! file_exists($this->favicon->getRealPath()))) {
            $this->favicon = null;
        }

        $this->validate();

        $data = [
            'name' => [
                'ar' => $this->name_ar,
                'en' => $this->name_en,
            ],
            'theme' => $this->theme,
            'description' => [
                'ar' => $this->description_ar,
                'en' => $this->description_en,
            ],
            'about_us' => [
                'ar' => $this->about_us_ar,
                'en' => $this->about_us_en,
            ],
            'shipping_returns' => [
                'ar' => $this->shipping_returns_ar,
                'en' => $this->shipping_returns_en,
            ],
            'privacy_policy' => [
                'ar' => $this->privacy_policy_ar,
                'en' => $this->privacy_policy_en,
            ],
            'terms_and_conditions' => [
                'ar' => $this->terms_and_conditions_ar,
                'en' => $this->terms_and_conditions_en,
            ],
            'address' => [
                'ar' => $this->address_ar,
                'en' => $this->address_en,
            ],
            'refund_policy' => [
                'ar' => $this->refund_policy_ar,
                'en' => $this->refund_policy_en,
            ],
            'shipping_policy' => [
                'ar' => $this->shipping_policy_ar,
                'en' => $this->shipping_policy_en,
            ],
            'phone' => $this->phone,
            'email' => $this->email,
            'google_client_id' => $this->google_client_id,
            'google_client_secret' => $this->google_client_secret,
            'google_redirect_uri' => $this->google_redirect_uri,
            'color_primary' => $this->color_primary,
            'color_secondary' => $this->color_secondary,
            'color_accent' => $this->color_accent,
        ];

        if ($this->setting->exists) {
            $this->setting->update($data);
        } else {
            $this->setting = SiteSetting::create($data);
        }

        $mediaChanged = false;

        // Handle logo_white
        if ($this->logo_white && $this->logo_white instanceof \Illuminate\Http\UploadedFile && file_exists($this->logo_white->getRealPath())) {
            $this->setting->addMedia($this->logo_white->getRealPath())->toMediaCollection('logo_white');
            $this->logo_white = null;
            $mediaChanged = true;
        }

        // Handle logo_black
        if ($this->logo_black && $this->logo_black instanceof \Illuminate\Http\UploadedFile && file_exists($this->logo_black->getRealPath())) {
            $this->setting->addMedia($this->logo_black->getRealPath())->toMediaCollection('logo_black');
            $this->logo_black = null;
            $mediaChanged = true;
        }

        // Handle favicon
        if ($this->favicon && $this->favicon instanceof \Illuminate\Http\UploadedFile && file_exists($this->favicon->getRealPath())) {
            $this->setting->addMedia($this->favicon->getRealPath())->toMediaCollection('favicon');
            $this->favicon = null;
            $mediaChanged = true;
        }

        \Illuminate\Support\Facades\Cache::forget('site_setting');
        $this->setting = SiteSetting::getSetting();

        $this->success(__('lang.updated_successfully', ['attribute' => __('lang.site_settings')]));
    }

    public function render(): View
    {
        return view('livewire.dashboard.site-setting.update-site-setting');
    }
}
