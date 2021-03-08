<?php

namespace Cblink\Socialite\Feishu;

use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\User;

class Provider extends AbstractProvider
{
    /**
     * Unique Provider Identifier.
     */
    public const IDENTIFIER = 'FEISHU';

    /**
     * {@inheritdoc}.
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('https://open.feishu.cn/open-apis/authen/v1/index', $state);
    }

    /**
     * {@inheritdoc}.
     */
    protected function buildAuthUrlFromBase($url, $state)
    {
        $query = http_build_query($this->getCodeFields($state), '', '&', $this->encodingType);

        return $url.'?'.$query;
    }

    /**
     * {@inheritdoc}.
     */
    protected function getCodeFields($state = null)
    {
        return [
            'appid'         => $this->clientId,
            'redirect_uri' => $this->redirectUrl,
        ];
    }

    /**
     * {@inheritdoc}.
     */
    protected function getTokenUrl()
    {
        return 'https://open.feishu.cn/open-apis/auth/v3/app_access_token/internal';
    }

    /**
     * {@inheritdoc}.
     */
    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->post('https://open.feishu.cn/open-apis/authen/v1/access_token', [
            'query' => [
                'grant_type' => 'authorization_code',
                'code'       => $this->getCode(),
            ],
            'headers' => [
                'Authorization' => sprintf('Bearer %s', $token)
            ]
        ]);

        $user = json_decode($response->getBody(), true);

        return $user['data'];
    }

    /**
     * {@inheritdoc}.
     */
    protected function mapUserToObject(array $user)
    {
        return (new User())->setRaw($user)->map([
            'id'       => $user['openid'],
            'unionid'  => $user['unionid'] ?? null,
            'nickname' => $user['name'] ?? null,
            'avatar'   => $user['avatar_url'] ?? null,
            'name'     => $user['name'] ?? null,
            'email'    => $user['email'] ?? null,
        ]);
    }

    /**
     * {@inheritdoc}.
     */
    protected function getTokenFields($code)
    {
        return [
            'appid' => $this->clientId,
            'secret' => $this->clientSecret,
        ];
    }

    /**
     * {@inheritdoc}.
     */
    public function getAccessTokenResponse($code)
    {
        $response = $this->getHttpClient()->post($this->getTokenUrl(), [
            'query' => $this->getTokenFields($code),
        ]);

        $body = json_decode($response->getBody(), true);

        return ['access_token' => $body['app_access_token']];
    }
}