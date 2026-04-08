<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Models\Category;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Schema;

class CategoryInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Flex::make([
                    Section::make('Category Details')
                        ->description('Core information about this category.')
                        ->schema([
                            TextEntry::make('name')
                                ->columnSpanFull(),

                            TextEntry::make('description')
                                ->columnSpanFull(),

                            TextEntry::make('slug')
                                ->badge(),

                            TextEntry::make('status')
                                ->badge(),

                            TextEntry::make('parent.name')
                                ->label('Parent Category')
                                ->badge()
                                ->color('gray')
                                ->visible(fn (Category $record): bool => !is_null($record->parent_id))
                                ->placeholder('None'),
                        ])
                        ->columns(3),

                    Section::make('Timestamps')
                        ->description('Creation and update history.')
                        ->schema([
                            TextEntry::make('created_at')
                                ->label('Created')
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

                            TextEntry::make('deleted_at')
                                ->label('Deleted')
                                ->since()
                                ->badge()
                                ->color('red')
                                ->visible(fn (Category $record): bool => $record->trashed())
                                ->dateTimeTooltip('M d, Y H:i A')
                                ->columnSpanFull(),
                        ])
                        ->columns(2)
                        ->grow(false)
                        ->extraAttributes([
                            'class' => 'py-8 px-6',
                        ]),
                ])
                ->columnSpanFull()
                ->from('md'),
            ]);
    }
}
