# Weixin

```bash
composer require cblink/socialite-feishu -vvv
```

## Installation & Basic Usage

Please see the [Base Installation Guide](https://socialiteproviders.com/usage/), then follow the provider specific instructions below.

### Add configuration to `config/services.php`

```php
'feishu' => [    
  'client_id' => env('FEISHU_CLIENT_ID'),  
  'client_secret' => env('FEISHU_CLIENT_SECRET'),  
  'redirect' => env('FEISHU_REDIRECT_URI') 
],
```

### Add provider event listener

Configure the package's listener to listen for `SocialiteWasCalled` events.

Add the event to your `listen[]` array in `app/Providers/EventServiceProvider`. See the [Base Installation Guide](https://socialiteproviders.com/usage/) for detailed instructions.

```php
protected $listen = [
    \SocialiteProviders\Manager\SocialiteWasCalled::class => [
        // ... other providers
        'Cblink\\Socialite\\Feishu\\FeishuExtendSocialite@handle',
    ],
];
```

### Usage

You should now be able to use the provider like you would regularly use Socialite (assuming you have the facade installed):

```php
return Socialite::driver('feishu')->redirect();
```

### Returned User fields

- ``id``
- ``unionid``
- ``nickname``
- ``avatar``