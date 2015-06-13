<?php namespace RainLab\Forum\Models;

use Model;

/**
 * Settings Model
 */
class Settings extends Model
{
    public $implement = ['System.Behaviors.SettingsModel'];

    // A unique code
    public $settingsCode = 'forum_rating_settings';

    // Reference to field configuration
    public $settingsFields = 'fields.yaml';

    /**
     * Here we initialize the default data
     */
    public function initSettingsData ()
    {        
        // $providers = \RomaldyMinaya\Socialite\Plugin::$providers;
    }
}