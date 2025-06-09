<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class GameAccount extends Model
{
    protected $fillable = [
        'user_id',
        'realm_id',
        'account_id',
        'username',
        'status',
        'last_login'
    ];

    protected $casts = [
        'last_login' => 'datetime',
        'account_id' => 'integer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function realm()
    {
        return $this->belongsTo(Realm::class);
    }

    /**
     * Проверяет существование аккаунта в базе данных AzerothCore
     */
    public static function verifyAzerothCoreAccount($username, $password)
    {
        try {
            // Подключаемся к базе данных auth
            $account = DB::connection('auth')
                ->table('account')
                ->where('username', $username)
                ->first();

            if (!$account) {
                return false;
            }

            // Проверяем пароль используя SRP6
            $salt = hex2bin($account->salt);
            $verifier = hex2bin($account->verifier);
            
            return Srp6::verifySRP6($username, $password, $salt, $verifier);
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Получает информацию об аккаунте из базы данных AzerothCore
     */
    public static function getAzerothCoreAccountInfo($username)
    {
        try {
            return DB::connection('auth')
                ->table('account')
                ->where('username', $username)
                ->first();
        } catch (\Exception $e) {
            return null;
        }
    }
} 