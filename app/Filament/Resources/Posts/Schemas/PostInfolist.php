<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Models\Post;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;

class PostInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)
                    ->schema([
                        Grid::make()
                            ->schema([
                                Section::make('Basic Information')
                                    ->description('Essential details of the post including title, URL slug, summary, and cover image.')
                                    ->compact()
                                    ->schema([
                                        TextEntry::make('title'),
                                        TextEntry::make('slug')
                                            ->copyable()
                                            ->badge()
                                            ->color('gray'),

                                        TextEntry::make('description')
                                            ->columnSpanFull(),

                                        ImageEntry::make('cover_image')
                                            ->disk('r2')
                                            ->extraImgAttributes([
                                                'loading' => 'lazy',
                                                'class' => 'rounded-md'
                                            ])
                                            ->checkFileExistence(false)
                                            ->columnSpanFull()
                                            ->hiddenLabel()
                                            ->grow(false),
                                    ])
                                    ->columns(2)
                                    ->columnSpanFull(),

                                Section::make('Content')
                                    ->description('The main body of the post. Supports rich text and markdown formatting.')
                                    ->compact()
                                    ->schema([
                                        TextEntry::make('content')
                                            ->prose()
                                            ->markdown()
                                            ->hiddenLabel()
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2)
                                    ->columnSpanFull(),
                            ])
                            ->columnSpan(2),
                            
                        Grid::make()
                            ->schema([
                                Section::make('Publishing')
                                    ->description('Controls the visibility of the post, including status, publish timing, and featured flag.')
                                    ->compact()
                                    ->schema([
                                        TextEntry::make('status')
                                            ->badge(),

                                        IconEntry::make('is_featured')
                                            ->boolean(),

                                        TextEntry::make('views_count')
                                            ->numeric()
                                            ->badge()
                                            ->color('gray')
                                            ->visible(fn (Post $record) => $record->views_count > 0),

                                        TextEntry::make('published_at')
                                            ->label('Published')
                                            ->since()
                                            ->badge()
                                            ->color('gray')
                                            ->dateTimeTooltip('M d, Y H:i A')
                                            ->visible(fn (Post $record) => filled($record->published_at)),

                                        TextEntry::make('scheduled_at')
                                            ->label('Scheduled')
                                            ->since()
                                            ->badge()
                                            ->color('gray')
                                            ->dateTimeTooltip('M d, Y H:i A')
                                            ->visible(fn (Post $record) => filled($record->scheduled_at)),
                                    ])
                                    ->columns(2)
                                    ->columnSpanFull(),

                                Section::make('Metadata')
                                    ->description('Categorization, authorship, and system-generated timestamps for this post.')
                                    ->compact()
                                    ->schema([
                                        TextEntry::make('categories.name')
                                            ->label('Categories')
                                            ->badge()
                                            ->visible(fn (Post $record) => $record->categories->isNotEmpty()),

                                        TextEntry::make('authors.name')
                                            ->label('Authors')
                                            ->badge()
                                            ->visible(fn (Post $record) => $record->authors->isNotEmpty()),

                                        TextEntry::make('created_at')
                                            ->label('Created')
                                            ->since()
                                            ->badge()
                                            ->color('gray')
                                            ->dateTimeTooltip('M d, Y H:i A'),

                                        TextEntry::make('updated_at')
                                            ->label('Last Update')
                                            ->since()
                                            ->badge()
                                            ->color('gray')
                                            ->dateTimeTooltip('M d, Y H:i A'),

                                        TextEntry::make('deleted_at')
                                            ->label('Deleted')
                                            ->since()
                                            ->badge()
                                            ->color('red')
                                            ->dateTimeTooltip('M d, Y H:i A')
                                            ->visible(fn (Post $record): bool => $record->trashed()),
                                    ])
                                    ->columns(2)
                                    ->columnSpanFull(),
                            ])
                            ->columnSpan(1)
                            ->grow(false),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
