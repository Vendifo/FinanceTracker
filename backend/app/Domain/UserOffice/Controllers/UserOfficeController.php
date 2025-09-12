<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Office;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserOfficeController extends BaseController
{
    /**
     * Показать список офисов пользователя
     */
    public function index(User $user)
    {
        $offices = $user->offices()->get();

        return response()->json([
            'user_id' => $user->id,
            'offices' => $offices
        ]);
    }

    /**
     * Обновить офисы пользователя
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'offices' => 'array',
            'offices.*' => 'exists:offices,id',
        ]);

        // Синхронизируем офисы
        $user->offices()->sync($request->input('offices', []));

        return response()->json([
            'message' => 'Офисы пользователя обновлены',
            'user_id' => $user->id,
            'offices' => $user->offices()->get()
        ]);
    }

    /**
     * Переключить активный офис текущего пользователя
     */
    public function switchOffice(Request $request)
    {
        $request->validate([
            'office_id' => 'required|exists:offices,id',
        ]);

        $officeId = $request->input('office_id');


        $user = Auth::user();

        // Проверяем, что пользователь имеет доступ к этому офису
        if (! $user->offices->pluck('id')->contains($officeId)) {
            return response()->json([
                'message' => 'Нет доступа к этому офису'
            ], 403);
        }

        // Сохраняем выбранный офис в сессии
        session(['current_office_id' => $officeId]);

        return response()->json([
            'message' => 'Активный офис изменён',
            'current_office_id' => $officeId
        ]);
    }
}
