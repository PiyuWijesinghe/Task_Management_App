<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class OauthToken extends Model
{
    protected $table = 'oauth_tokens';

    protected $guarded = [];

    protected $dates = [
        'expires_at',
    ];

    // Access token is stored encrypted as JSON string
    public function setAccessTokenAttribute($value)
    {
        $json = is_array($value) ? json_encode($value) : $value;
        $this->attributes['access_token'] = Crypt::encryptString($json);
    }

    public function getAccessTokenAttribute($value)
    {
        if (!$value) {
            return null;
        }
        $json = Crypt::decryptString($value);
        return json_decode($json, true);
    }

    public function setRefreshTokenAttribute($value)
    {
        $this->attributes['refresh_token'] = $value ? Crypt::encryptString($value) : null;
    }

    public function getRefreshTokenAttribute($value)
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
