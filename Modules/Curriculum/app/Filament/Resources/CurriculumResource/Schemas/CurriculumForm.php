<?php

namespace Modules\Curriculum\Filament\Resources\CurriculumResource\Schemas;

use App\Filament\Forms\Components\TranslatableInput;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Group;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\HtmlString;
use Modules\Subject\Models\Subject;
use Modules\Faculty\Models\Faculty;

class CurriculumForm
{
    public static function schema(): array
    {
        return [
            Section::make(__('curriculum::app.Curriculum Details'))
                ->schema([
                    Select::make('department_id')
                        ->label(__('department::app.Department'))
                        ->relationship('department', 'name')
                        ->required()
                        ->searchable()
                        ->preload(),

                    Select::make('faculties')
                        ->label(__('curriculum::app.Associated Faculties'))
                        ->relationship('faculties', 'name')
                        ->multiple()
                        ->preload()
                        ->live()
                        ->afterStateUpdated(function ($state, Set $set, Get $get) {
                            $selectedFacultyIds = $state ?? [];
                            $currentProxyState = $get('proxied_subjects') ?? [];

                            foreach ($currentProxyState as $key => $group) {
                                if (!in_array($group['faculty_id'], $selectedFacultyIds)) {
                                    unset($currentProxyState[$key]);
                                }
                            }

                            foreach ($selectedFacultyIds as $facId) {
                                $exists = collect($currentProxyState)->contains('faculty_id', $facId);
                                if (!$exists) {
                                    $faculty = Faculty::find($facId);
                                    if ($faculty) {
                                        $subjects = Subject::where('faculty_id', $facId)->get();
                                        $subjectList = [];
                                        
                                        foreach ($subjects as $subj) {
                                            $subjectList[md5($subj->id)] = [
                                                'id' => $subj->id,
                                                'code' => $subj->code,
                                                'name' => $subj->name,
                                                'is_mandatory' => true,
                                                'credit_hours' => 3.0,
                                            ];
                                        }

                                        $currentProxyState[md5('fac_'.$facId)] = [
                                            'faculty_id' => $facId,
                                            'faculty_name' => $faculty->name,
                                            'subjects' => $subjectList,
                                        ];
                                    }
                                }
                            }
                            
                            $set('proxied_subjects', $currentProxyState);
                        }),

                    TextInput::make('code')
                        ->label(__('app.Code'))
                        ->maxLength(255),

                    Select::make('status')
                        ->label(__('app.Status'))
                        ->options([
                            'active' => __('app.Active'),
                            'archived' => __('app.Archived'),
                        ])
                        ->required()
                        ->default('active'),
                ])
                ->columns(2),

            Section::make(__('app.Name'))
                ->schema([
                    TranslatableInput::make('name', TextInput::class, function ($field, $locale) {
                        return $field->required()->maxLength(255);
                    }),
                ]),

            Repeater::make('proxied_subjects')
                ->label(__('curriculum::app.Subjects (Grouped by Faculty)'))
                ->columnSpanFull()
                ->dehydrated(true)
                ->schema([
                    Section::make(fn (Get $get) => $get('faculty_name') ?? 'Faculty Group')
                        ->schema([
                            TextInput::make('faculty_id')->hidden(),
                            TextInput::make('faculty_name')->hidden(),

                            Repeater::make('subjects')
                                ->hiddenLabel()
                                ->grid(4)
                                ->schema([
                                    TextInput::make('id')->hidden(),
                                    TextInput::make('code')->hidden(),
                                    TextInput::make('name')->hidden(),
                                    
                                    Group::make([
                                        Placeholder::make('details')
                                            ->hiddenLabel()
                                            ->content(fn (Get $get) => new HtmlString(
                                                '<div class="mb-2">' .
                                                '<div class="font-bold text-gray-900">' . ($get('name') ?? '-') . '</div>' .
                                                '<div class="text-xs font-mono text-gray-500">' . ($get('code') ?? '-') . '</div>' .
                                                '</div>'
                                            )),
                                        
                                        TextInput::make('credit_hours')
                                            ->label(__('curriculum::app.Credit Hours'))
                                            ->numeric()
                                            ->default(3.0)
                                            ->required()
                                            ->minValue(0),

                                        Toggle::make('is_mandatory')
                                            ->label(__('curriculum::app.Mandatory'))
                                            ->inline(false)
                                            ->onColor('success')
                                            ->offColor('danger'),
                                    ])
                                ])
                                ->addable(false)
                                ->deletable(false)
                                ->reorderable(true)
                                ->collapsible(false)
                        ])
                        ->collapsible()
                        ->compact()
                ])
                ->addable(false)
                ->deletable(false)
                ->reorderable(true)
                ->afterStateHydrated(function (Repeater $component, $record) {
                    if ($record && $record->faculties->count() > 0) {
                        $state = [];
                        
                        $facultyIds = $record->faculties->pluck('id')->toArray();
                        $linkedSubjects = $record->subjects;
                        
                        foreach ($facultyIds as $facId) {
                            $faculty = Faculty::find($facId);
                            if (!$faculty) continue;

                            $allSubjects = Subject::where('faculty_id', $facId)->get();
                            $subjectList = [];

                            foreach ($allSubjects as $subj) {
                                $linked = $linkedSubjects->firstWhere('id', $subj->id);
                                
                                $subjectList[md5($subj->id)] = [
                                    'id' => $subj->id,
                                    'code' => $subj->code,
                                    'name' => $subj->name,
                                    'is_mandatory' => $linked ? $linked->pivot->is_mandatory : true,
                                    'credit_hours' => $linked ? ($linked->pivot->credit_hours ?? 3.0) : 3.0,
                                ];
                            }
                            
                            $state[md5('fac_'.$facId)] = [
                                'faculty_id' => $facId,
                                'faculty_name' => $faculty->name,
                                'subjects' => $subjectList
                            ];
                        }
                        
                        $component->state($state);
                    }
                }),
        ];
    }
}
