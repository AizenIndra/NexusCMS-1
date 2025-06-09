<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Realm extends Model
{
    protected $fillable = [
        'name',
        'hostname',
        'expansion',
        'emulator',
        'port',
        'auth_database',
        'world_database',
        'console_hostname',
        'console_username',
        'console_password',
        'console_urn'
    ];

    protected $casts = [
        'auth_database' => 'array',
        'world_database' => 'array',
        'port' => 'integer',
        'expansion' => 'integer'
    ];

    public function gameAccounts()
    {
        return $this->hasMany(GameAccount::class);
    }
} 