<?php

namespace App\Controller;

use App\Exception\UserNotFoundException;
use App\Services\UserAccount\UserAccountService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AccountConfirmationController
{
    /**
     * @var UserAccountService
     */
    private $accountService;

    public function __construct(UserAccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function confirmAccountWithTokenAction(Request $request): JsonResponse
    {
        $token = $request->query->get('token') ?? '';

        try {
            $this->accountService->activateUserWithConfirmToken($token);
        } catch (UserNotFoundException $exception) {
            return new JsonResponse(['user' => $exception->getMessage()], 404);
        }

        return new JsonResponse('User account confirmed.');
    }
}
