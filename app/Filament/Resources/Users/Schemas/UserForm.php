<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Support\Icons\Heroicon;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rules\Unique;
use Illuminate\Support\Facades\Hash;
use App\Enums\UserRole;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Profile')
                    ->description('Basic user identity and contact information.')
                    ->schema([
                        TextInput::make('name')
                            ->label('Full Name')
                            ->placeholder('John Doe')
                            ->helperText('Enter the user’s full name.')
                            ->prefixIcon(Heroicon::User)
                            ->columnSpanFull()
                            ->required(),

                        TextInput::make('email')
                            ->label('Email Address')
                            ->email()
                            ->placeholder('john@example.com')
                            ->helperText('This will be used for login and notifications.')
                            ->prefixIcon(Heroicon::Envelope)
                            ->required() 
                            ->unique(
                                ignoreRecord: true
                            ),

                        Select::make('role')
                            ->label('Role')
                            ->options(UserRole::class)
                            ->default(UserRole::General)
                            ->searchable()
                            ->native(false)
                            ->prefixIcon(Heroicon::Shield)
                            ->helperText('Select the user role to define access level.')
                            ->required(),
                    ])
                    ->columnSpanFull()
                    ->columns(2),

                Section::make('Security')
                    ->description('Manage authentication credentials and verification status.')
                    ->schema([                        
                        TextInput::make('password')
                            ->label('Password')
                            ->password()
                            ->placeholder('Leave blank to keep current password')
                            ->helperText('Only required when creating a new user.')
                            ->prefixIcon(Heroicon::Key)
                            ->revealable()
                             ->autocomplete('new-password')
                            ->required(fn ($context) => $context === 'create')
                            ->dehydrated(fn ($state) => filled($state))
                            ->dehydrateStateUsing(fn ($state) => Hash::make($state)),

                        DateTimePicker::make('email_verified_at')
                            ->label('Email Verified At')
                            ->displayFormat('d M, Y')
                            ->native(false)
                            ->closeOnDateSelection()
                            ->helperText('Automatically set when email is verified.')
                            ->prefixIcon(Heroicon::CheckBadge)
                            ->default(now())
                            ->readOnly(),
                    ])
                    ->columnSpanFull()
                    ->columns(2),
            ]);
    }
}
