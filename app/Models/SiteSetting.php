<?php

namespace App\Models;

use App\Traits\ClearsCacheOnChange;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

final class SiteSetting extends Model implements HasMedia
{
    use ClearsCacheOnChange, HasFactory, HasTranslations, InteractsWithMedia;

    protected $table = 'site_settings';

    public $translatable = ['name', 'description', 'faq', 'about_us', 'shipping_returns', 'privacy_policy', 'terms_and_conditions', 'address', 'refund_policy', 'shipping_policy'];

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public static function getSetting()
    {
        return self::first() ?? new self;
    }

    protected function getCacheKeys(): string
    {
        return 'site_setting';
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('logo_white')
            ->useDisk('public')
            ->singleFile();

        $this
            ->addMediaCollection('logo_black')
            ->useDisk('public')
            ->singleFile();

        $this
            ->addMediaCollection('favicon')
            ->useDisk('public')
            ->singleFile();
    }
}
