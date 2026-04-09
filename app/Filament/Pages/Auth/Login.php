<?php

namespace App\Filament\Pages\Auth;

class Login extends \Filament\Auth\Pages\Login
{
    public function mount(): void
    {
        parent::mount();

        $this->form->fill([
            'email' => 'demo@rnd.com',
            'password' => 'demo.Plugin@RN!',
            'remember' => true,
        ]);
    }
}