<?php

namespace Zisunal\LaravelInstaller\Livewire\Steps;

class AdminUserStep extends AbstractInstallerStep
{
    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public function mount(): void
    {
        $adminUser = session( 'admin-user' );
        if ( $adminUser ) {
            $this->name = $adminUser[ 'name' ] ?? '';
            $this->email = $adminUser[ 'email' ] ?? '';
            $this->password = $adminUser[ 'password' ] ?? '';
            $this->password_confirmation = $adminUser[ 'password' ] ?? '';
        }
    }

    public static function title(): string
    {
        return 'Admin User';
    }

    public static function description(): ?string
    {
        return 'Create the first administrator.';
    }

    public static function order(): int
    {
        return 5;
    }

    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['required', 'string', 'min:8'],
        ];
    }

    public function finalizeSetup(): void
    {
        $this->validate();

        session()->put( 'admin-user', [
            ...session( 'admin-user' ) ?? [],
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
        ] );

        parent::next();
    }

    public function render()
    {
        return view('installer::livewire.steps.admin-user');
    }
}