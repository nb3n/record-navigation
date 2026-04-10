<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ActionGroup;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Enums\ColumnManagerLayout;
use Filament\Tables\Table;
use App\Models\User;
use App\Enums\UserRole;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Enums\FiltersLayout;

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
            ->filters(
                [
                    SelectFilter::make('role')
                        ->label('Type')
                        ->options(UserRole::class)
                        ->native(false),

                    TernaryFilter::make('email_verified_at')
                        ->label('Verification')
                        ->nullable()
                        ->trueLabel('Verified')
                        ->falseLabel('Unverified')
                        ->native(false),
                ], 
                layout: FiltersLayout::AboveContentCollapsible
            )
            ->recordActions([
                ActionGroup::make([
                    ActionGroup::make([
                        ViewAction::make(),
                        EditAction::make(),
                    ])
                    ->dropdown(false),

                    DeleteAction::make()
                        ->before(function ($action): void {
                            Notification::make()
                                ->title('Now, now, don\'t be cheeky, leave some records for others to play with!')
                                ->warning()
                                ->send();

                            $action->cancel();
                        }),
                ])
                ->tooltip('Actions'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()
                        ->before(function ($action): void {
                            Notification::make()
                                ->title('Now, now, don\'t be cheeky, leave some records for others to play with!')
                                ->warning()
                                ->send();

                            $action->cancel();
                        }),
                ]),
            ]);
    }
}
