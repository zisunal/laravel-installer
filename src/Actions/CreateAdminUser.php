<?php

namespace Zisunal\LaravelInstaller\Actions;

use Illuminate\Support\Facades\Hash;

class CreateAdminUser
{
    public function handle(array $admin): void
    {
        $model = config('installer.admin_model', \App\Models\User::class);

        if (! class_exists($model)) {
            return;
        }

        $email = $admin['email'] ?? null;
        $password = $admin['password'] ?? null;

        if (! $email || ! $password) {
            return;
        }

        $user = $model::query()->updateOrCreate(
            ['email' => $email],
            [
                'name' => $admin['name'] ?? 'Admin',
                'password' => Hash::make($password),
            ]
        );

        $roleAttribute = config('installer.admin_role_attribute', 'role');
        $roleValue = config('installer.admin_role_value', 'owner');

        if (method_exists($user, 'setAttribute')) {
            $user->setAttribute($roleAttribute, $roleValue);
            $user->save();
        }
    }
}