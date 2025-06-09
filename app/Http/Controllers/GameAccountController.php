<?php

namespace App\Http\Controllers;

use App\Models\GameAccount;
use App\Models\Realm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class GameAccountController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $gameAccounts = GameAccount::with('realm')
            ->where('user_id', $user->id)
            ->get();
        $realms = Realm::all();
        
        return view('ucp.gameAccount', compact('gameAccounts', 'realms', 'user'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|min:3|max:32',
            'password' => 'required|string|min:6',
            'realm_id' => 'required|exists:realms,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $realm = Realm::findOrFail($request->realm_id);

            // Проверяем существование аккаунта в AzerothCore
            if (!GameAccount::verifyAzerothCoreAccount($request->username, $request->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Неверные учетные данные игрового аккаунта'
                ], 422);
            }

            // Получаем информацию об аккаунте
            $accountInfo = GameAccount::getAzerothCoreAccountInfo($request->username);
            
            if (!$accountInfo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Не удалось получить информацию об аккаунте'
                ], 500);
            }

            // Проверяем, не привязан ли уже этот аккаунт
            $existingAccount = GameAccount::where('realm_id', $realm->id)
                ->where('account_id', $accountInfo->id)
                ->first();

            if ($existingAccount) {
                return response()->json([
                    'success' => false,
                    'message' => 'Этот игровой аккаунт уже привязан к другому пользователю'
                ], 422);
            }

            // Создаем привязку
            $gameAccount = GameAccount::create([
                'user_id' => Auth::id(),
                'realm_id' => $realm->id,
                'account_id' => $accountInfo->id,
                'username' => $request->username,
                'status' => 'active'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Игровой аккаунт успешно привязан',
                'account' => $gameAccount
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Не удалось привязать игровой аккаунт',
                'error' => $e->getMessage()
            ], 500);
        }
    }
} 