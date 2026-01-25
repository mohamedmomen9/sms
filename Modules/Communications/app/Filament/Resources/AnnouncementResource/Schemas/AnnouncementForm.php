<?php

namespace Modules\Communications\Filament\Resources\AnnouncementResource\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Modules\Communications\Models\Announcement;

class AnnouncementForm
{
    public static function schema(): array
    {
        return [
            Section::make(__('communications::app.Announcement Details'))
                ->schema([
                    TextInput::make('title')
                        ->label(__('communications::app.Title'))
                        ->required()
                        ->maxLength(255)
                        ->columnSpanFull(),

                    Select::make('type')
                        ->label(__('communications::app.Type'))
                        ->options(
                            collect(Announcement::getTypes())->mapWithKeys(fn($type) => [
                                $type => __("communications::app.{$type}")
                            ])->toArray()
                        )
                        ->required()
                        ->default('news'),

                    Select::make('campus_id')
                        ->label(__('communications::app.Campus'))
                        ->relationship('campus', 'name')
                        ->placeholder(__('communications::app.All Campuses'))
                        ->helperText(__('communications::app.Leave empty for all campuses'))
                        ->searchable()
                        ->preload(),

                    DatePicker::make('date')
                        ->label(__('communications::app.Date'))
                        ->required()
                        ->default(now()),

                    Toggle::make('is_active')
                        ->label(__('communications::app.Active'))
                        ->default(true)
                        ->inline(false),
                ])
                ->columns(2),

            Section::make(__('communications::app.Content'))
                ->schema([
                    RichEditor::make('details')
                        ->label(__('communications::app.Details'))
                        ->toolbarButtons([
                            'bold',
                            'italic',
                            'underline',
                            'strike',
                            'link',
                            'orderedList',
                            'bulletList',
                            'blockquote',
                        ])
                        ->columnSpanFull(),

                    TextInput::make('link')
                        ->label(__('communications::app.Link'))
                        ->url()
                        ->placeholder(__('communications::app.Optional external URL'))
                        ->maxLength(500),
                ])
                ->columns(1),

            Section::make(__('communications::app.Media'))
                ->schema([
                    // Single upload field with editor - saves to cropped_image
                    // On first upload, also saves original to image
                    FileUpload::make('cropped_image')
                        ->label(__('communications::app.Upload & Edit Image'))
                        ->image()
                        ->imageEditor()
                        ->imageEditorMode(2)
                        ->imageEditorViewportWidth('1920')
                        ->imageEditorViewportHeight('1080')
                        ->imageEditorAspectRatios([
                            null,
                            '16:9',
                            '4:3',
                            '1:1',
                        ])
                        ->imageEditorEmptyFillColor('#000000')
                        ->directory('announcements/cropped')
                        ->maxSize(5120)
                        ->imagePreviewHeight('250')
                        ->helperText(__('communications::app.Upload image then use editor to crop. Original is preserved.'))
                        ->live()
                        ->afterStateUpdated(function ($state, Set $set, Get $get) {
                            // Only save original when it's a NEW upload (TemporaryUploadedFile)
                            // and there's no existing original image
                            if ($state instanceof TemporaryUploadedFile) {
                                $existingOriginal = $get('image');

                                // Only save original if no original exists yet
                                if (empty($existingOriginal)) {
                                    // Store original copy BEFORE any edits
                                    $originalPath = $state->store('announcements/original', 'public');
                                    $set('image', $originalPath);
                                }
                            }
                        })
                        ->columnSpanFull(),

                    // Hidden field to store the original image path
                    Hidden::make('image'),

                    // Preview: Original Image (read-only)
                    Placeholder::make('original_preview')
                        ->label(__('communications::app.Original Image'))
                        ->content(function (Get $get, $record) {
                            $imagePath = $get('image');

                            // Fallback to record if form state is empty
                            if (empty($imagePath) && $record) {
                                $imagePath = $record->image;
                            }

                            if (empty($imagePath)) {
                                return new \Illuminate\Support\HtmlString(
                                    '<div class="text-gray-400 italic">' . __('communications::app.No image uploaded yet') . '</div>'
                                );
                            }

                            $imageUrl = Storage::disk('public')->url($imagePath);
                            return new \Illuminate\Support\HtmlString(
                                '<div class="p-2 bg-gray-100 dark:bg-gray-800 rounded-lg">
                                    <img src="' . $imageUrl . '" alt="Original" class="max-h-[200px] rounded shadow" />
                                    <p class="text-xs text-gray-500 mt-1">' . __('communications::app.Original - never modified') . '</p>
                                </div>'
                            );
                        })
                        ->columnSpan(1),

                    // Preview: Cropped Image (read-only)
                    Placeholder::make('cropped_preview')
                        ->label(__('communications::app.Cropped Image'))
                        ->content(function (Get $get, $record) {
                            $croppedPath = $get('cropped_image');

                            // Handle array format from FileUpload
                            if (is_array($croppedPath)) {
                                $croppedPath = reset($croppedPath);
                            }

                            // Fallback to record if form state is empty
                            if (empty($croppedPath) && $record) {
                                $croppedPath = $record->cropped_image;
                            }

                            if (empty($croppedPath)) {
                                return new \Illuminate\Support\HtmlString(
                                    '<div class="text-gray-400 italic">' . __('communications::app.No cropped image yet') . '</div>'
                                );
                            }

                            // Handle TemporaryUploadedFile or string path
                            if ($croppedPath instanceof TemporaryUploadedFile) {
                                $imageUrl = $croppedPath->temporaryUrl();
                            } else {
                                $imageUrl = Storage::disk('public')->url($croppedPath);
                            }

                            return new \Illuminate\Support\HtmlString(
                                '<div class="p-2 bg-gray-100 dark:bg-gray-800 rounded-lg">
                                    <img src="' . $imageUrl . '" alt="Cropped" class="max-h-[200px] rounded shadow" />
                                    <p class="text-xs text-gray-500 mt-1">' . __('communications::app.Cropped version for display') . '</p>
                                </div>'
                            );
                        })
                        ->columnSpan(1),
                ])
                ->columns(2)
                ->collapsible(),
        ];
    }
}
