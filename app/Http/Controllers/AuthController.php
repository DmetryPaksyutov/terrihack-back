<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthLoginRequest;
use App\Http\Resources\UserWithTokenResource;
use App\Services\AuthService\AuthService;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function __construct(
        readonly private AuthService $authService,
    ) {
    }

    /**
     * @throws \Exception
     */
    public function handleTelegramCallback(): Application|Redirector|RedirectResponse
    {
        $telegramUser = Socialite::driver('telegram')->user();

        $userIdAndTokenDTO = $this->authService->authorizeOrRegister($telegramUser, 'telegram');

        return redirect("/login?userId=$userIdAndTokenDTO->id&token=$userIdAndTokenDTO->token");
    }

    public function authByOnceToken(AuthLoginRequest $request): UserWithTokenResource
    {
        return new UserWithTokenResource($this->authService->auth($request->getUser()->id));
    }
}
