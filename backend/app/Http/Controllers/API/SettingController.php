<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Str;

class SettingController extends Controller
{
    // Default settings that will be created if they don't exist
    protected $defaultSettings = [
        'app_name' => [
            'value' => 'ResumeRise',
            'type' => 'string',
            'group' => 'general',
        ],
        'timezone' => [
            'value' => 'UTC',
            'type' => 'string',
            'group' => 'general',
        ],
        'maintenance_mode' => [
            'value' => false,
            'type' => 'boolean',
            'group' => 'general',
        ],
        'allow_registrations' => [
            'value' => true,
            'type' => 'boolean',
            'group' => 'auth',
        ],
        'email_verification_required' => [
            'value' => true,
            'type' => 'boolean',
            'group' => 'auth',
        ],
        'enable_social_logins' => [
            'value' => false,
            'type' => 'boolean',
            'group' => 'auth',
        ],
        'mail_from_name' => [
            'value' => 'ResumeRise',
            'type' => 'string',
            'group' => 'mail',
        ],
        'mail_from_address' => [
            'value' => 'noreply@resumerise.app',
            'type' => 'string',
            'group' => 'mail',
        ],
        'mail_host' => [
            'value' => 'smtp.mailtrap.io',
            'type' => 'string',
            'group' => 'mail',
        ],
        'mail_port' => [
            'value' => 2525,
            'type' => 'integer',
            'group' => 'mail',
        ],
        'mail_encryption' => [
            'value' => 'tls',
            'type' => 'string',
            'group' => 'mail',
        ],
        'mail_username' => [
            'value' => '',
            'type' => 'string',
            'group' => 'mail',
        ],
        'mail_password' => [
            'value' => '',
            'type' => 'password',
            'group' => 'mail',
        ],
    ];

    // Get all settings grouped by their group
    public function index()
    {
        $settings = [];
        
        // Ensure all default settings exist
        $this->ensureDefaultSettingsExist();
        
        // Get all settings and group them
        Setting::all()->each(function ($setting) use (&$settings) {
            $value = $this->castValue($setting->value, $setting->type);
            // Don't expose sensitive data
            if ($setting->type === 'password') {
                $value = ''; // Don't return passwords
            }
            
            if (!isset($settings[$setting->group])) {
                $settings[$setting->group] = [];
            }
            
            $settings[$setting->group][$setting->key] = $value;
        });
        
        return response()->json($settings);
    }

    // Update settings
    public function update(Request $request)
    {
        $validated = $request->validate([
            'settings' => 'required|array',
            'settings.*.key' => 'required|string',
            'settings.*.value' => 'present',
        ]);
        
        $updated = [];
        
        foreach ($validated['settings'] as $settingData) {
            $key = $settingData['key'];
            $value = $settingData['value'];
            
            $setting = Setting::where('key', $key)->first();
            
            if ($setting) {
                // Don't update password if empty (to keep existing password)
                if ($setting->type === 'password' && empty($value)) {
                    continue;
                }
                
                $setting->value = $this->prepareValueForStorage($value, $setting->type);
                $setting->save();
                $updated[] = $key;
            }
        }
        
        // Clear settings cache
        Cache::forget('app_settings');
        
        // If maintenance mode was updated, update config
        if (in_array('maintenance_mode', $updated)) {
            $this->updateMaintenanceMode();
        }
        
        return response()->json([
            'message' => 'Settings updated successfully',
            'updated' => $updated,
        ]);
    }
    
    // Clear application cache
    public function clearCache()
    {
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        
        return response()->json(['message' => 'Application cache cleared successfully']);
    }
    
    // Reset all settings to defaults
    public function resetToDefaults()
    {
        Setting::truncate();
        $this->ensureDefaultSettingsExist();
        
        // Clear settings cache
        Cache::forget('app_settings');
        
        return response()->json(['message' => 'All settings have been reset to defaults']);
    }
    
    // Ensure all default settings exist in the database
    protected function ensureDefaultSettingsExist()
    {
        foreach ($this->defaultSettings as $key => $data) {
            Setting::firstOrCreate(
                ['key' => $key],
                [
                    'value' => $this->prepareValueForStorage($data['value'], $data['type']),
                    'type' => $data['type'],
                    'group' => $data['group'],
                ]
            );
        }
    }
    
    // Cast value to the appropriate type
    protected function castValue($value, $type)
    {
        if ($value === null) {
            return null;
        }
        
        switch ($type) {
            case 'integer':
                return (int) $value;
            case 'float':
                return (float) $value;
            case 'boolean':
                return (bool) $value;
            case 'array':
            case 'object':
                return json_decode($value, true);
            case 'json':
                return json_decode($value);
            default:
                return $value;
        }
    }
    
    // Prepare value for storage
    protected function prepareValueForStorage($value, $type)
    {
        if ($value === null) {
            return null;
        }
        
        switch ($type) {
            case 'array':
            case 'object':
            case 'json':
                return is_string($value) ? $value : json_encode($value);
            case 'boolean':
                return $value ? '1' : '0';
            default:
                return (string) $value;
        }
    }
    
    // Update the maintenance mode config
    protected function updateMaintenanceMode()
    {
        $maintenanceMode = Setting::where('key', 'maintenance_mode')->first();
        
        if ($maintenanceMode) {
            $value = $this->castValue($maintenanceMode->value, $maintenanceMode->type);
            Config::set('app.maintenance_mode', $value);
        }
    }
}
