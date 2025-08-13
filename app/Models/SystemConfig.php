<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SystemConfig extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'description',
    ];

    /**
     * Get config value with caching
     */
    public static function getValue(string $key, $default = null)
    {
        return Cache::remember("system_config_{$key}", 3600, function () use ($key, $default) {
            $config = static::where('key', $key)->first();
            if (!$config) {
                return $default;
            }

            return match($config->type) {
                'integer' => (int) $config->value,
                'boolean' => (bool) $config->value,
                'json' => json_decode($config->value, true),
                default => $config->value,
            };
        });
    }

    /**
     * Set config value
     */
    public static function setValue(string $key, $value, string $type = 'string', string $description = null): void
    {
        $config = static::firstOrNew(['key' => $key]);
        $config->value = is_array($value) ? json_encode($value) : (string) $value;
        $config->type = $type;
        $config->description = $description;
        $config->save();

        Cache::forget("system_config_{$key}");
    }

    /**
     * Get all configs as array
     */
    public static function getAllAsArray(): array
    {
        return static::all()->mapWithKeys(function ($config) {
            return [$config->key => static::getValue($config->key)];
        })->toArray();
    }

    /**
     * Initialize default system configurations
     */
    public static function initializeDefaults(): void
    {
        $defaults = [
            'auto_confirm_hours' => ['value' => 24, 'type' => 'integer', 'description' => 'Hours to wait before auto-confirming payments'],
            'payment_gateway_enabled' => ['value' => true, 'type' => 'boolean', 'description' => 'Enable payment gateway integration'],
            'upi_enabled' => ['value' => true, 'type' => 'boolean', 'description' => 'Enable UPI payments'],
            'qr_enabled' => ['value' => true, 'type' => 'boolean', 'description' => 'Enable QR code payments'],
            'email_notifications_enabled' => ['value' => true, 'type' => 'boolean', 'description' => 'Enable email notifications'],
            'sms_notifications_enabled' => ['value' => true, 'type' => 'boolean', 'description' => 'Enable SMS notifications'],
            'admin_email' => ['value' => 'admin@kitti.com', 'type' => 'string', 'description' => 'Admin email for notifications'],
            'company_name' => ['value' => 'KITTI Investment Platform', 'type' => 'string', 'description' => 'Company name'],
            'company_address' => ['value' => 'Mumbai, Maharashtra, India', 'type' => 'string', 'description' => 'Company address'],
            'support_phone' => ['value' => '+91-9876543210', 'type' => 'string', 'description' => 'Support phone number'],
            'support_email' => ['value' => 'support@kitti.com', 'type' => 'string', 'description' => 'Support email'],
        ];

        foreach ($defaults as $key => $config) {
            if (!static::where('key', $key)->exists()) {
                static::setValue($key, $config['value'], $config['type'], $config['description']);
            }
        }
    }
}
