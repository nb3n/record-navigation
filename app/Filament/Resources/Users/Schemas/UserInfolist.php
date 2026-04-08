<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Schema;
use App\Models\User;

class UserInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Flex::make([
                    Section::make('Profile')
                        ->description('Core user identity and contact information.')
                        ->schema([
                            TextEntry::make('name')
                                ->columnSpanFull(),

                            TextEntry::make('email')
                                ->copyable()
                                ->badge()
                                ->color('gray'),
                            
                            IconEntry::make('email_verified_at')
                                ->label('Email Verified')
                                ->boolean()
                                ->getStateUsing(fn (User $record) => filled($record->email_verified_at)),

                            ImageEntry::make('facehash_avatar_url')
                                ->label('Avatar')
                                ->circular()
                                ->imageSize(64),
                        ])
                        ->columns(2),

                    Section::make('Activity')
                        ->description('Account creation and recent update history.')
                        ->schema([
                            TextEntry::make('created_at')
                                ->label('Joined')
                                ->since()
                                ->badge()
                                ->color('gray')
                                ->dateTimeTooltip('M d, Y H:i A'),

                            TextEntry::make('updated_at')
                                ->label('Last Updated')
                                ->since()
                                ->badge()
                                ->color('gray')
                                ->dateTimeTooltip('M d, Y H:i A'),
                        ])
                        ->grow(false)
                        ->columns(2)
                        ->extraAttributes([
                            'class' => 'py-8 px-6',
                        ]),
                ])
                ->columnSpanFull()
                ->from('md'),
            ]);
    }
}
