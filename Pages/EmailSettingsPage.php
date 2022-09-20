<?php

namespace Modules\Settings\Pages;

use App\Models\User;
use Modules\Base\Services\Components\Base\Action;
use Modules\Base\Services\Components\Base\AddRoute;
use Modules\Base\Services\Components\Base\Alert;
use Modules\Base\Services\Rows\Text;
use Modules\Notifications\Services\SendNotification;
use Modules\Settings\Services\Setting;
use Modules\Settings\Settings\EmailSettings;

class EmailSettingsPage extends Setting {

    public ?string $setting = EmailSettings::class;
    public ?bool $api = true;
    public ?string $path = "email_settings";
    public ?string $group = "Settings";
    public ?string $icon = "bx bx-envelope";

    public  function rows(): array
    {
        return [
            Text::make('mail_mailer')->label(__('Email Mailer')),
            Text::make('mail_host')->label(__('Email Host')),
            Text::make('mail_port')->label(__('Email Port')),
            Text::make('mail_username')->label(__('Email Username')),
            Text::make('mail_password')->label(__('Email Password')),
            Text::make('mail_encryption')->label(__('Email Encryption')),
            Text::make('mail_from_address')->label(__('Email From Address')),
            Text::make('mail_from_name')->label(__('Email From Name')),
        ];
    }

    public function actions(): array
    {
        return [
            Action::make('test_email')->label(__('Test Email'))->icon('bx bx-send')->type('success')->action('admin.settings.email_settings.test_email'),
        ];
    }

    public function route(): array
    {
        return [
            AddRoute::make('test_email')->path('test_email')->method('sendEmail')->type('post')->controller(static::class)
        ];
    }

    public function sendEmail(){
        $user = User::first();
        SendNotification::make("Test Email Send Form Server " . url('/'))
            ->message("test email to test current email settings")
            ->provider(['email'])
            ->privacy('private')
            ->model(User::class)
            ->model_id($user->id)
            ->send();

        return Alert::make(__('Test Email Sent'))->type('success')->fire();
    }
}
