# VILT Settings Module

VILT framework Settings module GUI to save key and value on database and cache it

## Install

```bash
composer require queents/settings-module
```
Add Module to `modules_statuses.json` if not exists

```json
{
    "Settings": true
}
```

Make a migration

```bash
php artisan migrate
```

Publish Assets

```bash
npm i & npm run build
```

OR

```bash
yarn & yarn build
```

to create a new settings page you can use this command

```bash
php artisan vilt:setting
```

and put the setting name like `SiteMap` and your Module name

go to `Modules/YourModuleName/Database/Migrations` and you will get the main setting migration set your values

go to `Modules/YourModuleName/Settings` and add your settings as a public vars and set the group name

go to `Modules/YourModuleName/Pages` and you will get the settings Page edit the rows to be your selected rows type


## Support

you can join our discord server to get support [VILT Admin](https://discord.gg/HUNYbgKDdx)

## Docs

look to the new docs of v4.00 on my website [Docs](https://vilt.3x1.io/docs/)

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Credits

- [3x1](https://github.com/3x1io)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

