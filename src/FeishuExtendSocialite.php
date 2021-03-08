<?php

namespace Cblink\Socialite\Feishu;

use SocialiteProviders\Manager\SocialiteWasCalled;

/**
 * Class FeishuExtendSocialite
 * @package Cblink\Socialite\Feishu
 */
class FeishuExtendSocialite
{
    /**
     * Register the provider.
     *
     * @param \SocialiteProviders\Manager\SocialiteWasCalled $socialiteWasCalled
     */
    public function handle(SocialiteWasCalled $socialiteWasCalled)
    {
        $socialiteWasCalled->extendSocialite('feishu', Provider::class);
    }
}