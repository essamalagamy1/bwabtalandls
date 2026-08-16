<?php

namespace App\Traits;

use Illuminate\Support\Facades\Cache;

trait ClearsCacheOnChange
{
    /**
     * Boot the trait and register model event listeners.
     */
    protected static function bootClearsCacheOnChange(): void
    {
        static::created(function ($model) {
            $model->clearModelCache();
        });

        static::updated(function ($model) {
            $model->clearModelCache();
        });

        static::deleted(function ($model) {
            $model->clearModelCache();
        });
    }

    /**
     * Clear the cache for this model.
     * Override this method in your model to specify custom cache keys.
     */
    protected function clearModelCache(): void
    {
        $cacheKeys = $this->getCacheKeys();

        if (is_array($cacheKeys)) {
            foreach ($cacheKeys as $key) {
                Cache::forget($key);
            }
        } else {
            Cache::forget($cacheKeys);
        }
    }

    /**
     * Get the cache key(s) to clear.
     * Override this method in your model to specify custom cache keys.
     */
    protected function getCacheKeys(): string|array
    {
        // Default: use the table name as cache key
        return $this->getTable();
    }
}
