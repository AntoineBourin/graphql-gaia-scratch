<?php

namespace App\Controller;

use App\Exception\InvalidUserException;
use App\Services\AuthenticationService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserController
{
    /**
     * @param Request $request
     * @param AuthenticationService $authService
     * @return JsonResponse
     */
    public function registerUserAction(Request $request, AuthenticationService $authService)
    {
        try {
            $authService->createUserAuthentication($request->request);
        } catch (InvalidUserException $exception) {
            return new JsonResponse(json_decode($exception->getMessage()), 422);
        }

        return new JsonResponse('User created successfully', 201);
    }
}
