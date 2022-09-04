<?php

namespace Modules\Settings\Pages;

use Modules\Base\Helpers\Resources\Row;
use Modules\Base\Services\Rows\Email;
use Modules\Base\Services\Rows\Media;
use Modules\Base\Services\Rows\Repeater;
use Modules\Base\Services\Rows\Tel;
use Modules\Base\Services\Rows\Text;
use Modules\Base\Services\Rows\Textarea;
use Modules\Settings\Services\Setting;
use Modules\Settings\Settings\SitesSettings;

class SiteSettingsPage extends Setting {

    public ?string $setting = SitesSettings::class;
    public ?bool $api = true;
    public ?string $path = "site_settings";
    public ?string $group = "Settings";
    public ?string $icon = "bx bxs-cog";

    public  function rows(): array
    {
        return [
            Text::make('site_name')->label(__('Site Name')),
            Text::make('site_description')->label(__('Site Description')),
            Text::make('site_keywords')->label(__('Site Keywords')),
            Media::make('site_profile')->label(_('Site Profile')),
            Media::make('site_logo')->label(__('Site Logo')),
            Text::make('site_author')->label(__('Site Author')),
            Textarea::make('site_address')->label(__('Site Address'))->type('textarea'),
            Email::make('site_email')->label(__('Site Email'))->type('email'),
            Tel::make('site_phone')->label(__('Site Phone'))->type('tel'),
            Text::make('site_phone_code')->label(_('Site Phone Code')),
            Text::make('site_location')->label(__('Site Location')),
            Text::make('site_currency')->label(__('Site Currency')),
            Text::make('site_language')->label(__('Site Language')),
            Repeater::make('site_social')->label(__('Site Social'))->type('repeater')->options([
                Text::make('vendor')->label(__('Vendor')),
                Text::make('url')->label(__('URL')),
            ]),
            Repeater::make('site_menu')->label(__('Site Menu'))->type('repeater')->options([
                Text::make('title')->label(__('Title')),
                Text::make('icon')->label(__('Icon')),
                Text::make('target')->label(__('Target'))->type('switch'),
                Text::make('url')->label(__('URL')),
                Text::make('route')->label(__('Route')),
            ]),
        ];
    }

}
