<?php

namespace App\Controller;

use App\Exception\InvalidUserException;
use App\Services\UserAccount\AuthenticationService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserController
{
    /**
     * @param Request $request
     * @param AuthenticationService $authService
     * @return JsonResponse
     */
    public function registerUserAction(Request $request, AuthenticationService $authService): JsonResponse
    {
        try {
            $authService->createUserAuthentication($request->request);
        } catch (\Exception $exception) {
            if ($exception instanceof InvalidUserException) {
                return new JsonResponse(json_decode($exception->getMessage()), $exception->getCode());
            } else {
                return new JsonResponse($exception->getMessage(), 500);
            }
        }

        return new JsonResponse('User created successfully', 201);
    }
}
