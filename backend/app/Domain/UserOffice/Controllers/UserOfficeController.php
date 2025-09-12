<?php

namespace App\Domain\UserOffice\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Core\BaseController;
use App\Models\Office;

/**
 * @package App\Domain\UserOffice\Controllers
 */
class UserOfficeController extends BaseController
{
    /**
     * Список офисов пользователя
     *
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(User $user)
    {
        /** @var User $user */
        return $this->apiResponse(
            $user->offices()->get(),
            'Список офисов пользователя'
        );
    }

    /**
     * Обновить офисы пользователя
     *
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, User $user)
    {
        /** @var User $user */
        $request->validate([
            'offices' => 'array',
            'offices.*' => 'exists:offices,id',
        ]);

        $user->offices()->sync($request->input('offices', []));

        return $this->apiResponse(
            $user->offices()->get(),
            'Офисы пользователя обновлены'
        );
    }

    /**
     * Переключить активный офис текущего пользователя
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function switchOffice(Request $request)
    {
        $request->validate([
            'office_id' => 'required|exists:offices,id',
        ]);

        $user = Auth::user();
        /** @var User $user */
        $officeId = $request->input('office_id');

        if (! $user->offices->pluck('id')->contains($officeId)) {
            return $this->apiResponse(null, 'Нет доступа к этому офису', false, 403);
        }

        session(['current_office_id' => $officeId]);

        return $this->apiResponse(
            ['current_office_id' => $officeId],
            'Активный офис изменён'
        );
    }

    /**
     * Получить активный офис текущего пользователя
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function currentOffice()
    {
        $user = Auth::user();
        /** @var User $user */
        $currentOfficeId = session('current_office_id');
        $office = $user->offices()->find($currentOfficeId);

        return $this->apiResponse([
            'current_office_id' => $currentOfficeId,
            'current_office' => $office,
        ], 'Активный офис пользователя');
    }
}
