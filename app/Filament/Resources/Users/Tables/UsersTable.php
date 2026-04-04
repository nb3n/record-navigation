<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Enums\ColumnManagerLayout;
use Filament\Tables\Table;
use App\Models\User;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->heading('Users')
            ->description('Individuals who registered through the application.')
            ->columns([
                TextColumn::make('name')
                    ->description(fn (User $record): string => $record->email)
                    ->copyable()
                    ->searchable(),

                ImageColumn::make('facehash_avatar_url')
                    ->label('Avatar')
                    ->circular(),
                    
                 TextColumn::make('created_at')
                    ->since()
                    ->dateTimeTooltip('M d, Y H:i A')
                    ->label('Joined on')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->since()
                    ->dateTimeTooltip('M d, Y H:i A')
                    ->label('Last Update')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->columnManagerLayout(ColumnManagerLayout::Modal)
            ->columnManagerColumns(2)
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
