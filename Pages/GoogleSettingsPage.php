<?php

namespace Modules\Settings\Pages;

use Modules\Base\Helpers\Resources\Row;
use Modules\Base\Services\Rows\Media;
use Modules\Base\Services\Rows\Text;
use Modules\Base\Services\Rows\Textarea;
use Modules\Settings\Services\Setting;
use Modules\Settings\Settings\GoogleSettings;

class GoogleSettingsPage extends Setting {

    public ?string $setting = GoogleSettings::class;
    public ?bool $api = true;
    public ?string $path = "google_settings";
    public ?string $group = "Settings";
    public ?string $icon = "bx bxl-google";

    public  function rows(): array
    {
        return [
            Text::make('google_api_key')->label(__('Google API key')),
            Media::make('google_firebase_cr')->type('file')->label(__('Firebase CREDENTIALS')),
            Text::make('google_firebase_database_url')->label(__('Firebase Database URL')),
            Textarea::make('google_firebase_vapid')->label(__('Firebase VAPID KEY')),
            Text::make('google_recaptcha_key')->label(__('Recaptcha Key')),
            Text::make('google_recaptcha_secret')->label(__('Recaptcha Secret')),
        ];
    }


}
