<?php

namespace App\Services;

use Google_Client;
use App\Models\OauthToken;
use App\Models\User;
use Carbon\Carbon;

class GoogleService
{
    protected $clientId;
    protected $clientSecret;
    protected $redirectUri;

    public function __construct()
    {
        $this->clientId = config('services.google.client_id') ?: env('GOOGLE_CLIENT_ID');
        $this->clientSecret = config('services.google.client_secret') ?: env('GOOGLE_CLIENT_SECRET');
        $this->redirectUri = config('services.google.redirect') ?: env('GOOGLE_REDIRECT_URI');
    }

    protected function createClient(): Google_Client
    {
        $client = new Google_Client();
        $client->setClientId($this->clientId);
        $client->setClientSecret($this->clientSecret);
        $client->setRedirectUri($this->redirectUri);
        $client->setAccessType('offline');
        $client->setPrompt('consent');

        // set default minimal scopes — adjust as needed
        $client->setScopes([
            \Google_Service_Drive::DRIVE_FILE,
        ]);

        return $client;
    }

    public function getAuthUrl(): string
    {
        $client = $this->createClient();
        return $client->createAuthUrl();
    }

    public function storeTokenFromAuthCode(User $user, string $code): OauthToken
    {
        $client = $this->createClient();
        $token = $client->fetchAccessTokenWithAuthCode($code);

        if (isset($token['error'])) {
            throw new \RuntimeException('Google OAuth error: ' . json_encode($token));
        }

        $row = OauthToken::updateOrCreate(
            ['user_id' => $user->id, 'provider' => 'google'],
            []
        );

        $this->saveTokenRow($row, $token);

        if (isset($token['scope'])) {
            $row->scope = $token['scope'];
            $row->save();
        }

        return $row;
    }

    public function getClientForUser(User $user): ?Google_Client
    {
        $tokenRow = OauthToken::where('user_id', $user->id)->where('provider', 'google')->first();
        if (! $tokenRow) {
            return null;
        }

        $client = $this->createClient();
        $token = $tokenRow->access_token;
        if (! $token) {
            return null;
        }

        $client->setAccessToken($token);

        if ($client->isAccessTokenExpired()) {
            $refreshToken = $tokenRow->refresh_token;
            if (! $refreshToken) {
                // no refresh token available
                return null;
            }

            $newToken = $client->fetchAccessTokenWithRefreshToken($refreshToken);

            if (isset($newToken['error'])) {
                // refresh failed, remove token row
                $tokenRow->delete();
                return null;
            }

            // ensure refresh_token persists
            if (empty($newToken['refresh_token'])) {
                $newToken['refresh_token'] = $refreshToken;
            }

            $client->setAccessToken($newToken);
            $this->saveTokenRow($tokenRow, $newToken);
        }

        return $client;
    }

    protected function saveTokenRow(OauthToken $row, array $token): void
    {
        $row->access_token = $token;
        if (! empty($token['refresh_token'])) {
            $row->refresh_token = $token['refresh_token'];
        }

        if (! empty($token['expires_in'])) {
            $row->expires_at = Carbon::now()->addSeconds($token['expires_in']);
        } elseif (! empty($token['expiry'])) {
            $row->expires_at = Carbon::createFromTimestamp($token['expiry']);
        }

        $row->save();
    }

    public function revoke(User $user): void
    {
        $tokenRow = OauthToken::where('user_id', $user->id)->where('provider', 'google')->first();
        if (! $tokenRow) {
            return;
        }

        $client = $this->createClient();
        $client->setAccessToken($tokenRow->access_token);
        try {
            $client->revokeToken();
        } catch (\Throwable $e) {
            // ignore revoke errors, we'll still remove stored tokens
        }

        $tokenRow->delete();
    }
}
