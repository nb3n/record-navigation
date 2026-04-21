<?php

namespace App\Filament\Resources\Posts\Schemas;

use App\Enums\PostStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;
use Filament\Support\Icons\Heroicon;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Grid;

class PostForm
{
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->handlePublishingState($data);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $this->handlePublishingState($data);
    }

    protected function handlePublishingState(array $data): array
    {
        $status = $data['status'] instanceof \BackedEnum
            ? $data['status']->value
            : (string) $data['status'];

        switch ($status) {
            case 'published':
                $data['published_at'] = $data['published_at'] ?? now();
                $data['scheduled_at'] = null;
                break;

            case 'scheduled':
                $data['published_at'] = null;
                break;

            case 'draft':
            case 'under_review':
            case 'archived':
                $data['published_at'] = null;
                $data['scheduled_at'] = null;
                break;
        }

        return $data;
    }

    private static function resolveStatus(mixed $status): string
    {
        if ($status instanceof \BackedEnum) {
            return $status->value;
        }

        return (string) ($status ?? '');
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)
                    ->schema([
                        Grid::make()
                            ->schema([
                                Section::make('Post Details')
                                    ->description('Core information about your post, including its title, URL, and summary.')
                                    ->compact()
                                    ->schema([
                                        TextInput::make('title')
                                            ->label('Post Title')
                                            ->placeholder('e.g., 10 Tips for Better Productivity')
                                            ->helperText('Enter a clear and engaging title for your post. This will be displayed to readers.')
                                            ->required()
                                            ->prefixIcon(Heroicon::Signal)
                                            ->live(onBlur: true)
                                            ->maxLength(100)
                                            ->afterStateUpdated(function (Set $set, ?string $state) {
                                                $cleanedState = preg_replace('/\s+/', ' ', trim($state ?? ''));
                                                
                                                $slug = Str::slug(str_replace('&', 'and', $cleanedState));

                                                $set('slug', $slug);
                                                $set('title', $cleanedState);
                                            }),

                                        TextInput::make('slug')
                                            ->label('Post URL (Slug)')
                                            ->placeholder('e.g., productivity-tips')
                                            ->helperText('A unique, URL-friendly version of the title. It is generated automatically.')
                                            ->required()
                                            ->prefixIcon(Heroicon::GlobeAlt)
                                            ->unique(ignoreRecord: true)
                                            ->dehydrated()
                                            ->readonly()
                                            ->maxLength(120),

                                        Textarea::make('description')
                                            ->label('Post Summary')
                                            ->placeholder('Write a brief summary of your post...')
                                            ->helperText('A short description that may appear in previews, search results, or social shares.')
                                            ->columnSpanFull()
                                            ->required()
                                            ->maxLength(150),
                                    ])
                                    ->columns(2)
                                    ->columnSpanFull(),

                                Section::make('Post Content')
                                    ->description('Write your post content and upload a cover image.')
                                    ->compact()
                                    ->schema([
                                        RichEditor::make('content')
                                            ->label('Post Content')
                                            ->placeholder('Write your post here...')
                                            ->helperText('This is the main body of your post.')
                                            ->required()
                                            ->floatingToolbars([
                                                'paragraph' => [
                                                    'bold', 'strike', 'subscript', 'superscript', 'h2', 'link', 'blockquote', 'codeBlock', 'bulletList', 'orderedList',
                                                ],
                                                'heading' => [
                                                    'h1', 'h2', 'h3',
                                                ],
                                                'table' => [
                                                    'tableAddColumnBefore', 'tableAddColumnAfter', 'tableDeleteColumn',
                                                    'tableAddRowBefore', 'tableAddRowAfter', 'tableDeleteRow',
                                                    'tableMergeCells', 'tableSplitCell',
                                                    'tableToggleHeaderRow',
                                                    'tableDelete',
                                                ],
                                            ])
                                            ->fileAttachmentsDisk('r2')
                                            ->fileAttachmentsDirectory('media/posts/attachments')
                                            ->fileAttachmentsMaxSize(256)
                                            ->columnSpanFull(),

                                        FileUpload::make('cover_image')
                                            ->label('Cover Image')
                                            ->image()
                                            ->acceptedFileTypes(['image/webp'])
                                            ->imageEditor()
                                            ->openable()
                                            ->downloadable()
                                            ->previewable()
                                            ->deletable()
                                            ->disk('r2')
                                            ->required()
                                            ->directory('media/posts')
                                            ->maxSize(256)
                                            ->disabled()
                                            ->helperText('Upload a featured image for this post.')
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2)
                                    ->columnSpanFull(),
                            ])
                            ->columnSpan(2),
                        
                        Grid::make()
                            ->schema([
                                Section::make('Publishing')
                                    ->description('Status, visibility, and scheduling.')
                                    ->compact()
                                    ->schema([
                                        Select::make('status')
                                            ->label('Post Status')
                                            ->options(PostStatus::class)
                                            ->helperText('Choose whether the post is saved as a draft, published, or archived.')
                                            ->default('draft')
                                            ->searchable()
                                            ->prefixIcon(Heroicon::CheckCircle)
                                            ->required()
                                            ->live()
                                            ->native(false),

                                        ToggleButtons::make('is_featured')
                                            ->label('Featured Post')
                                            ->boolean()
                                            ->inline()
                                            ->default(false)
                                            ->helperText('Enable this to highlight the post in featured sections or on the homepage.')
                                            ->required(),

                                        DateTimePicker::make('published_at')
                                            ->label('Publish Date')
                                            ->placeholder('Auto-set on publish')
                                            ->helperText('Leave empty to use current time when publishing.')
                                            ->nullable()
                                            ->closeOnDateSelection()
                                            ->prefixIcon(Heroicon::CheckBadge)
                                            ->live()
                                            ->afterStateUpdated(fn (Set $set, Get $get) =>
                                                static::resolveStatus($get('status')) !== 'published'
                                                    ? $set('status', 'published')
                                                    : null
                                            )
                                            ->visible(fn (Get $get): bool =>
                                                static::resolveStatus($get('status')) === 'published'
                                            )
                                            ->native(false),

                                        DateTimePicker::make('scheduled_at')
                                            ->label('Schedule Date')
                                            ->placeholder('Pick a future date & time')
                                            ->helperText('Must be at least 1 minute in the future.')
                                            ->nullable()
                                            ->minDate(now()->addMinute())
                                            ->closeOnDateSelection()
                                            ->prefixIcon(Heroicon::Calendar)
                                            ->live()
                                            ->afterStateUpdated(fn (Set $set, Get $get) =>
                                                static::resolveStatus($get('status')) !== 'scheduled'
                                                    ? $set('status', 'scheduled')
                                                    : null
                                            )
                                            ->visible(fn (Get $get): bool =>
                                                static::resolveStatus($get('status')) === 'scheduled'
                                            )
                                            ->native(false),
                                    ])
                                    ->columns(1)
                                    ->columnSpanFull(),   
                                    
                                Section::make('Content Organization')
                                    ->description('Assign categories and authors to organize and attribute your post.')
                                    ->schema([
                                        Select::make('categories')
                                            ->label('Categories')
                                            ->relationship(name: 'categories', titleAttribute: 'name')
                                            ->searchable(['name'])
                                            ->searchPrompt('Search categories...')
                                            ->preload()
                                            ->loadingMessage('Loading categories...')
                                            ->searchingMessage('Searching categories...')
                                            ->noSearchResultsMessage('No categories found.')
                                            ->noOptionsMessage('No categories available.')
                                            ->placeholder('Select one or more categories')
                                            ->helperText('Choose categories to group and organize this post.')
                                            ->prefixIcon(Heroicon::Tag)
                                            ->maxItems(3)
                                            ->multiple()
                                            ->required()
                                            ->native(false),

                                        Select::make('authors')
                                            ->label('Authors')
                                            ->relationship(name: 'authors', titleAttribute: 'name')
                                            ->searchable(['name', 'email'])
                                            ->searchPrompt('Search authors by name or email')
                                            ->preload()
                                            ->loadingMessage('Loading authors...')
                                            ->searchingMessage('Searching authors...')
                                            ->noSearchResultsMessage('No authors found.')
                                            ->noOptionsMessage('No authors available.')
                                            ->placeholder('Select one or more authors')
                                            ->helperText('Assign one or more authors responsible for this post.')
                                            ->prefixIcon(Heroicon::User)
                                            ->maxItems(5)
                                            ->multiple()
                                            ->required()
                                            ->native(false),
                                    ])
                                    ->columns(1)
                                    ->columnSpanFull(),
                            ])
                            ->columnSpan(1)
                            ->grow(false),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
