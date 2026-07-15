<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SiteSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
    ];

    /**
     * Allowed setting keys — prevents arbitrary key creation.
     */
    private static array $allowedKeys = [
        'hero_headline', 'hero_subheadline', 'hero_video_url', 'profil_video_url',
        'tentang_text', 'tentang_image', 'stat_sampah', 'stat_produk', 'stat_warga',
        'contact_address', 'contact_email', 'contact_instagram', 'contact_phone',
    ];

    /**
     * Get a setting value by key with optional default.
     */
    public static function get(string $key, string $default = ''): string
    {
        $setting = static::where('key', $key)->first();
        return $setting ? ($setting->value ?? $default) : $default;
    }

    /**
     * Set a setting value by key. Only allowed keys can be created/updated.
     */
    public static function set(string $key, ?string $value, string $type = 'text'): void
    {
        if (!in_array($key, static::$allowedKeys, true)) {
            throw new \InvalidArgumentException("Setting key '{$key}' is not allowed.");
        }

        static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'type' => $type]
        );
    }
}
