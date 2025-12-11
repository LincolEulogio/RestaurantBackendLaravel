<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            // General
            ['key' => 'restaurant_name', 'value' => 'Mi Restaurante', 'group' => 'general'],
            ['key' => 'restaurant_cuisine_type', 'value' => 'Italiana', 'group' => 'general'],
            ['key' => 'restaurant_phone', 'value' => '+52 123 456 7890', 'group' => 'general'],
            ['key' => 'restaurant_email', 'value' => 'contacto@mirestaurante.com', 'group' => 'general'],
            ['key' => 'restaurant_address', 'value' => 'Calle, número, colonia, ciudad, estado', 'group' => 'general'],
            ['key' => 'restaurant_timezone', 'value' => 'America/Lima', 'group' => 'general'],
            ['key' => 'restaurant_currency', 'value' => 'PEN - Sol Peruano', 'group' => 'general'],

            // Payment Methods
            ['key' => 'payment_cash', 'value' => '1', 'group' => 'payment', 'type' => 'boolean'],
            ['key' => 'payment_card', 'value' => '1', 'group' => 'payment', 'type' => 'boolean'],
            ['key' => 'payment_transfer', 'value' => '0', 'group' => 'payment', 'type' => 'boolean'],
            ['key' => 'payment_digital', 'value' => '1', 'group' => 'payment', 'type' => 'boolean'],

            // System Preferences
            ['key' => 'system_auto_print', 'value' => '1', 'group' => 'system', 'type' => 'boolean'],
            ['key' => 'system_sound_notifications', 'value' => '0', 'group' => 'system', 'type' => 'boolean'],
        ];

        foreach ($settings as $setting) {
            \App\Models\Setting::set(
                $setting['key'], 
                $setting['value'], 
                $setting['group'], 
                $setting['type'] ?? 'string'
            );
        }
    }
}
