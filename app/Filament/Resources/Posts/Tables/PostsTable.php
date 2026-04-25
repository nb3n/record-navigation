<?php

namespace App\Filament\Resources\Posts\Tables;

use App\Enums\PostStatus;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Notifications\Notification;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class PostsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->heading('Posts')
            ->description('Browse and manage posts across different statuses and featured content.')
            ->columns([
                ImageColumn::make('cover_image')
                    ->label('Image')
                    ->square()
                    ->checkFileExistence(false)
                    ->disk('r2')
                    ->extraImgAttributes([
                        'loading' => 'lazy',
                        'class' => 'rounded-md',
                    ]),

                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight(FontWeight::Medium),

                TextColumn::make('slug')
                    ->badge()
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                ImageColumn::make('authors.facehash_avatar_url')
                    ->label('Authors')
                    ->circular()
                    ->stacked()
                    ->limit(3)
                    ->limitedRemainingText()
                    ->checkFileExistence(false)
                    ->extraImgAttributes([
                        'loading' => 'lazy',
                    ])
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('categories.name')
                    ->searchable()
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('status')
                    ->badge()
                    ->searchable(),

                IconColumn::make('is_featured')
                    ->boolean(),

                TextColumn::make('views_count')
                    ->label('Views')
                    ->numeric()
                    ->badge()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('published_at')
                    ->label('Published')
                    ->since()
                    ->dateTimeTooltip('M d, Y H:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('scheduled_at')
                    ->label('Scheduled')
                    ->since()
                    ->dateTimeTooltip('M d, Y H:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->since()
                    ->dateTimeTooltip('M d, Y H:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->since()
                    ->dateTimeTooltip('M d, Y H:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('deleted_at')
                    ->label('Deleted')
                    ->since()
                    ->dateTimeTooltip('M d, Y H:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters(
                [
                    SelectFilter::make('status')
                        ->label('Status')
                        ->searchable()
                        ->options(PostStatus::class)
                        ->native(false),

                    SelectFilter::make('categories')
                        ->label('Categories')
                        ->relationship('categories', 'name')
                        ->searchable(['name'])
                        ->preload()
                        ->native(false),

                    SelectFilter::make('authors')
                        ->label('Authors')
                        ->relationship('authors', 'name')
                        ->searchable(['name', 'email'])
                        ->preload()
                        ->native(false),

                    SelectFilter::make('is_featured')
                        ->label('Featured')
                        ->searchable()
                        ->preload()
                        ->options([
                            1 => 'Featured',
                            0 => 'Not Featured',
                        ])
                        ->native(false),

                    Filter::make('published_at')
                        ->schema([
                            DatePicker::make('published_from')
                                ->placeholder(fn ($state): string => 'Dec 18, '.now()->subYear()->format('Y'))
                                ->displayFormat('d M, Y')
                                ->closeOnDateSelection()
                                ->native(false),

                            DatePicker::make('published_until')
                                ->placeholder(fn ($state): string => now()->format('M d, Y'))
                                ->displayFormat('d M, Y')
                                ->closeOnDateSelection()
                                ->native(false),

                        ])
                        ->columns(2)
                        ->query(function (Builder $query, array $data): Builder {
                            return $query
                                ->when(
                                    $data['published_from'] ?? null,
                                    fn (Builder $query, $date): Builder => $query->whereDate('published_at', '>=', $date),
                                )
                                ->when(
                                    $data['published_until'] ?? null,
                                    fn (Builder $query, $date): Builder => $query->whereDate('published_at', '<=', $date),
                                );
                        })
                        ->indicateUsing(function (array $data): array {
                            $indicators = [];
                            if ($data['published_from'] ?? null) {
                                $indicators['published_from'] = 'Published from '.Carbon::parse($data['published_from'])->toFormattedDateString();
                            }
                            if ($data['published_until'] ?? null) {
                                $indicators['published_until'] = 'Published until '.Carbon::parse($data['published_until'])->toFormattedDateString();
                            }

                            return $indicators;
                        })
                        ->columnSpan(2),

                    TrashedFilter::make()
                        ->searchable()
                        ->native(false),
                ],
                layout: FiltersLayout::AboveContentCollapsible
            )
            ->filtersFormColumns(3)
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

                    ForceDeleteBulkAction::make()
                        ->before(function ($action): void {
                            Notification::make()
                                ->title('Now, now, don\'t be cheeky, leave some records for others to play with!')
                                ->warning()
                                ->send();

                            $action->cancel();
                        }),

                    RestoreBulkAction::make()
                        ->before(function ($action): void {
                            Notification::make()
                                ->title('Now, now, these records aren\'t yours to restore!')
                                ->warning()
                                ->send();

                            $action->cancel();
                        }),
                ]),
            ]);
    }
}
