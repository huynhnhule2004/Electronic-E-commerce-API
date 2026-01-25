# User Module

## Purpose
User authentication and profile management (email + OAuth).

## Structure
- app/DTOs: `CreateUserDto`
- app/Models: `OauthAccount`
- database/migrations: profile fields + OAuth accounts
- app/Providers: `UserServiceProvider`

## Key Features
- Profile status: `pending` / `completed`
- OAuth accounts table: `user_oauth_accounts`

## Notes
Middleware `profile.complete` blocks incomplete profiles (username/phone missing).
