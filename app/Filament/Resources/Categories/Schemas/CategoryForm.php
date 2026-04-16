<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Enums\CategoryStatus;
use App\Filament\Resources\Categories\Tables\SelectParentCategoryTable;
use Filament\Forms\Components\ModalTableSelect;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Category Details')
                    ->description('Basic information about the category that helps in organizing your content.')
                    ->schema([
                        TextInput::make('name')
                            ->label('Category Name')
                            ->placeholder('e.g., Technology, Health, Lifestyle')
                            ->helperText('Enter a descriptive name for the category. This will be visible in menus and filters.')
                            ->required()
                            ->prefixIcon(Heroicon::Signal)
                            ->live(onBlur: true)
                            ->maxLength(60)
                            ->afterStateUpdated(function (Set $set, ?string $state) {
                                $cleanedState = preg_replace('/\s+/', ' ', trim($state ?? ''));

                                $slug = Str::slug(str_replace('&', 'and', $cleanedState));

                                $set('slug', $slug);
                                $set('name', $cleanedState);
                            }),

                        TextInput::make('slug')
                            ->label('Slug / URL Identifier')
                            ->placeholder('e.g., technology, health-tips')
                            ->helperText('Unique URL-friendly identifier for this category. Auto-generated if left blank.')
                            ->required()
                            ->prefixIcon(Heroicon::GlobeAlt)
                            ->unique(ignoreRecord: true)
                            ->dehydrated()
                            ->readonly()
                            ->maxLength(80),

                        Textarea::make('description')
                            ->label('Description')
                            ->placeholder('Write a short description for this category to help users understand its content.')
                            ->helperText('This may appear in category listings or SEO snippets.')
                            ->columnSpanFull()
                            ->maxLength(150),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),

                Section::make('Parent & Status')
                    ->description('Organize categories and control their visibility.')
                    ->schema([
                        ModalTableSelect::make('parent_id')
                            ->label('Parent Category')
                            ->relationship(
                                name: 'parent',
                                titleAttribute: 'name'
                            )
                            ->tableConfiguration(SelectParentCategoryTable::class)
                            ->helperText('Choose a parent category to create a hierarchy, or leave blank for a top-level category.')
                            ->nullable()
                            ->badge()
                            ->rules([
                                'different:id',
                            ])
                            ->validationMessages([
                                'different' => 'A category cannot be its own parent.',
                            ]),

                        Select::make('status')
                            ->label('Category Status')
                            ->helperText('Select whether this category is active, inactive, or archived.')
                            ->options(CategoryStatus::class)
                            ->prefixIcon(Heroicon::CheckCircle)
                            ->default('active')
                            ->required()
                            ->native(false),
                    ])
                    ->columns(2)
                    ->columnSpanFull(),
            ]);
    }
}
