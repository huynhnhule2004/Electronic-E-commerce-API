<?php

namespace Modules\Auth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Modules\Auth\Actions\ChangePasswordAction;
use Modules\Auth\Actions\LoginUserAction;
use Modules\Auth\Actions\LogoutUserAction;
use Modules\Auth\Actions\RefreshTokenAction;
use Modules\Auth\Actions\RegisterUserAction;
use Modules\Auth\Actions\ResetPasswordAction;
use Modules\Auth\Actions\SendPasswordResetAction;
use Modules\Auth\Actions\UpdateProfileAction;
use Modules\Auth\DTOs\ChangePasswordDto;
use Modules\Auth\DTOs\ForgotPasswordDto;
use Modules\Auth\DTOs\LoginDto;
use Modules\Auth\DTOs\RegisterDto;
use Modules\Auth\DTOs\ResetPasswordDto;
use Modules\Auth\DTOs\UpdateProfileDto;
use Modules\Auth\Http\Requests\ChangePasswordRequest;
use Modules\Auth\Http\Requests\ForgotPasswordRequest;
use Modules\Auth\Http\Requests\LoginRequest;
use Modules\Auth\Http\Requests\RegisterRequest;
use Modules\Auth\Http\Requests\ResetPasswordRequest;
use Modules\Auth\Http\Requests\UpdateProfileRequest;
use Modules\Auth\Http\Resources\AuthUserResource;

class AuthController extends Controller
{
    public function __construct(
        private readonly RegisterUserAction $registerAction,
        private readonly LoginUserAction $loginAction,
        private readonly LogoutUserAction $logoutAction,
        private readonly RefreshTokenAction $refreshAction,
        private readonly UpdateProfileAction $updateProfileAction,
        private readonly SendPasswordResetAction $sendPasswordResetAction,
        private readonly ResetPasswordAction $resetPasswordAction,
        private readonly ChangePasswordAction $changePasswordAction,
        private readonly \Modules\Auth\Actions\GenerateRefreshTokenAction $generateRefreshTokenAction,
    ) {
    }

    /**
     * Register a new user
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            $dto = RegisterDto::fromRequest($request->validated());
            $user = $this->registerAction->execute($dto);

            // Auto-login after registration
            $token = Auth::guard('api')->login($user);
            $refreshToken = $this->generateRefreshTokenAction->execute($user->id);

            return response()->json([
                'success' => true,
                'data' => [
                    'access_token' => $token,
                    'refresh_token' => $refreshToken,
                    'token_type' => 'bearer',
                    'expires_in' => Auth::guard('api')->factory()->getTTL() * 60,
                    'user' => new AuthUserResource($user),
                ],
                'message' => 'Registration successful',
                'code' => 201,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => $e->getMessage(),
                'code' => $e->getCode() ?: 500,
            ], $e->getCode() ?: 500);
        }
    }

    /**
     * Login user and return JWT token
     */
    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $dto = LoginDto::fromRequest($request->validated());
            $result = $this->loginAction->execute($dto);

            return response()->json([
                'success' => true,
                'data' => [
                    'access_token' => $result['access_token'],
                    'refresh_token' => $result['refresh_token'],
                    'token_type' => $result['token_type'],
                    'expires_in' => $result['expires_in'],
                    'user' => new AuthUserResource($result['user']),
                ],
                'message' => 'Login successful',
                'code' => 200,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => $e->getMessage(),
                'code' => $e->getCode() ?: 500,
            ], $e->getCode() ?: 500);
        }
    }

    /**
     * Logout user (invalidate token)
     */
    public function logout(): JsonResponse
    {
        try {
            $this->logoutAction->execute();

            return response()->json([
                'success' => true,
                'data' => null,
                'message' => 'Logged out successfully',
                'code' => 200,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => $e->getMessage(),
                'code' => $e->getCode() ?: 500,
            ], $e->getCode() ?: 500);
        }
    }

    /**
     * Refresh JWT token
     */
    public function refresh(\Illuminate\Http\Request $request): JsonResponse
    {
        try {
            $token = $request->input('refresh_token');
            
            if (!$token) {
                return response()->json([
                    'success' => false,
                    'data' => null,
                    'message' => 'Refresh token is required',
                    'code' => 400,
                ], 400);
            }

            $result = $this->refreshAction->execute($token);

            return response()->json([
                'success' => true,
                'data' => $result,
                'message' => 'Token refreshed successfully',
                'code' => 200,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => $e->getMessage(),
                'code' => $e->getCode() ?: 500,
            ], $e->getCode() ?: 500);
        }
    }

    /**
     * Get current authenticated user
     */
    public function me(): JsonResponse
    {
        try {
            $user = Auth::guard('api')->user();

            return response()->json([
                'success' => true,
                'data' => new AuthUserResource($user),
                'message' => 'OK',
                'code' => 200,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => $e->getMessage(),
                'code' => $e->getCode() ?: 500,
            ], $e->getCode() ?: 500);
        }
    }

    /**
     * Update user profile
     */
    public function updateProfile(UpdateProfileRequest $request): JsonResponse
    {
        try {
            $user = Auth::guard('api')->user();
            $dto = UpdateProfileDto::fromRequest($request->validated());
            $updatedUser = $this->updateProfileAction->execute($user, $dto);

            return response()->json([
                'success' => true,
                'data' => new AuthUserResource($updatedUser),
                'message' => 'Profile updated successfully',
                'code' => 200,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => $e->getMessage(),
                'code' => $e->getCode() ?: 500,
            ], $e->getCode() ?: 500);
        }
    }

    /**
     * Send password reset link
     */
    public function forgotPassword(ForgotPasswordRequest $request): JsonResponse
    {
        try {
            $dto = ForgotPasswordDto::fromRequest($request->validated());
            $message = $this->sendPasswordResetAction->execute($dto);

            return response()->json([
                'success' => true,
                'data' => null,
                'message' => $message,
                'code' => 200,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => $e->getMessage(),
                'code' => $e->getCode() ?: 500,
            ], $e->getCode() ?: 500);
        }
    }

    /**
     * Reset password with token
     */
    public function resetPassword(ResetPasswordRequest $request): JsonResponse
    {
        try {
            $dto = ResetPasswordDto::fromRequest($request->validated());
            $message = $this->resetPasswordAction->execute($dto);

            return response()->json([
                'success' => true,
                'data' => null,
                'message' => $message,
                'code' => 200,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => $e->getMessage(),
                'code' => $e->getCode() ?: 500,
            ], $e->getCode() ?: 500);
        }
    }

    /**
     * Change password (requires current password)
     */
    public function changePassword(ChangePasswordRequest $request): JsonResponse
    {
        try {
            $user = Auth::guard('api')->user();
            $dto = ChangePasswordDto::fromRequest($request->validated());
            $this->changePasswordAction->execute($user, $dto);

            return response()->json([
                'success' => true,
                'data' => null,
                'message' => 'Password changed successfully',
                'code' => 200,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => $e->getMessage(),
                'code' => $e->getCode() ?: 500,
            ], $e->getCode() ?: 500);
        }
    }
}
