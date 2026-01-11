<?php

namespace Modules\Curriculum\Filament\Resources\CurriculumResource\Schemas;

use App\Filament\Forms\Components\TranslatableInput;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\HtmlString;
use Modules\Department\Models\Department;
use Modules\Faculty\Models\Faculty;
use Modules\Subject\Models\Subject;

class CurriculumForm
{
    public static function schema(): array
    {
        return [
            Section::make(__('curriculum::app.Curriculum Details'))
                ->schema([
                    // Name with tabs - English required, Arabic optional
                    self::makeNameInput(),

                    Select::make('faculties')
                        ->label(__('app.Faculties'))
                        ->multiple()
                        ->options(fn () => Faculty::all()->mapWithKeys(fn ($f) => [
                            $f->id => self::getTranslatedName($f->name)
                        ]))
                        ->preload()
                        ->searchable()
                        ->live()
                        ->afterStateHydrated(function ($component, $record) {
                            if ($record) {
                                $component->state($record->faculties->pluck('id')->toArray());
                            }
                        })
                        ->afterStateUpdated(function ($state, Set $set, Get $get) {
                            $selectedFacultyIds = $state ?? [];
                            
                            // Filter departments to only those in selected faculties
                            $currentDepts = $get('departments') ?? [];
                            if (!empty($currentDepts) && !empty($selectedFacultyIds)) {
                                $validDepts = Department::whereIn('id', $currentDepts)
                                    ->whereIn('faculty_id', $selectedFacultyIds)
                                    ->pluck('id')
                                    ->toArray();
                                $set('departments', $validDepts);
                            } elseif (empty($selectedFacultyIds)) {
                                // Keep departments as is when no faculties selected
                            }
                            
                            // Rebuild subjects
                            self::rebuildSubjectsState($selectedFacultyIds, $get('departments') ?? [], $set, $get('proxied_subjects') ?? []);
                        }),

                    Select::make('departments')
                        ->label(__('app.Departments'))
                        ->multiple()
                        ->options(function (Get $get) {
                            $facultyIds = $get('faculties') ?? [];
                            if (empty($facultyIds)) {
                                return Department::with('faculty')->get()->mapWithKeys(fn ($d) => [
                                    $d->id => self::getTranslatedName($d->name) . ' (' . self::getTranslatedName($d->faculty?->name) . ')'
                                ]);
                            }
                            return Department::whereIn('faculty_id', $facultyIds)
                                ->with('faculty')
                                ->get()
                                ->mapWithKeys(fn ($d) => [
                                    $d->id => self::getTranslatedName($d->name) . ' (' . self::getTranslatedName($d->faculty?->name) . ')'
                                ]);
                        })
                        ->searchable()
                        ->preload()
                        ->live()
                        ->afterStateHydrated(function ($component, $record) {
                            if ($record) {
                                $component->state($record->departments->pluck('id')->toArray());
                            }
                        })
                        ->afterStateUpdated(function ($state, Set $set, Get $get) {
                            $facultyIds = $get('faculties') ?? [];
                            $departmentIds = $state ?? [];
                            self::rebuildSubjectsState($facultyIds, $departmentIds, $set, $get('proxied_subjects') ?? []);
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

            // Subjects grouped by Faculty -> Department
            Repeater::make('proxied_subjects')
                ->label(__('curriculum::app.Subjects (Grouped by Faculty)'))
                ->columnSpanFull()
                ->dehydrated(true)
                ->schema([
                    Section::make(fn (Get $get) => $get('group_label') ?? 'Group')
                        ->schema([
                            Hidden::make('faculty_id'),
                            Hidden::make('department_id'),
                            Hidden::make('group_label'),

                            Repeater::make('subjects')
                                ->hiddenLabel()
                                ->grid(3)
                                ->schema([
                                    Hidden::make('id'),
                                    Hidden::make('code'),
                                    Hidden::make('name'),
                                    
                                    Placeholder::make('details')
                                        ->hiddenLabel()
                                        ->content(fn (Get $get) => new HtmlString(
                                            '<div class="mb-2">' .
                                            '<div class="font-bold text-gray-900 dark:text-gray-100">' . ($get('name') ?? '-') . '</div>' .
                                            '<div class="text-xs font-mono text-gray-500">' . ($get('code') ?? '-') . '</div>' .
                                            '</div>'
                                        )),
                                    
                                    TextInput::make('credit_hours')
                                        ->label(__('curriculum::app.Credit Hours'))
                                        ->numeric()
                                        ->default(3.0)
                                        ->required()
                                        ->minValue(0.1)
                                        ->rule('gt:0')
                                        ->extraInputAttributes(['min' => 0.1, 'onkeydown' => "if(event.key === '-') event.preventDefault();"]),

                                    Toggle::make('is_mandatory')
                                        ->label(__('curriculum::app.Mandatory'))
                                        ->inline(false)
                                        ->onColor('success')
                                        ->offColor('danger')
                                        ->default(true),
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
                    if (!$record) {
                        // Initialize empty state if needed or leave empty
                        return;
                    }

                    $state = [];
                    $linkedSubjects = $record->subjects->keyBy('id');
                    
                    // Get departments from the curriculum (many-to-many)
                    $departments = $record->departments()->with('faculty', 'subjects')->get();

                    foreach ($departments as $dept) {
                        $facultyName = self::getTranslatedName($dept->faculty?->name);
                        $deptName = self::getTranslatedName($dept->name);
                        $groupLabel = "{$facultyName} → {$deptName}";

                        $subjectList = [];
                        foreach ($dept->subjects as $subj) {
                            $linked = $linkedSubjects->get($subj->id);
                            
                            $subjectList[md5($subj->id)] = [
                                'id' => $subj->id,
                                'code' => $subj->code,
                                'name' => self::getTranslatedName($subj->name),
                                'is_mandatory' => $linked ? (bool) $linked->pivot->is_mandatory : true,
                                'credit_hours' => $linked ? ($linked->pivot->credit_hours ?? 3.0) : 3.0,
                            ];
                        }

                        if (count($subjectList) > 0) {
                            $state[md5('dept_' . $dept->id)] = [
                                'faculty_id' => $dept->faculty_id,
                                'department_id' => $dept->id,
                                'group_label' => $groupLabel,
                                'subjects' => $subjectList,
                            ];
                        }
                    }
                    
                    $component->state($state);
                }),
        ];
    }

    protected static function makeNameInput(): \Filament\Forms\Components\Tabs
    {
        return \Filament\Forms\Components\Tabs::make(__('app.Name'))
            ->tabs([
                \Filament\Forms\Components\Tabs\Tab::make(__('app.English'))
                    ->schema([
                        TextInput::make('name.en')
                            ->label(__('app.Name') . ' (' . __('app.English') . ')')
                            ->required()
                            ->maxLength(255),
                    ]),
                \Filament\Forms\Components\Tabs\Tab::make(__('app.Arabic'))
                    ->schema([
                        TextInput::make('name.ar')
                            ->label(__('app.Name') . ' (' . __('app.Arabic') . ')')
                            ->maxLength(255), // Not required
                    ]),
            ])
            ->columnSpanFull()
            ->contained(true);
    }

    protected static function rebuildSubjectsState(array $facultyIds, array $departmentIds, Set $set, array $currentState = []): void
    {
        // Build a lookup map of existing subject values [subject_id => data]
        $existingData = [];
        foreach ($currentState as $group) {
            if (isset($group['subjects']) && is_array($group['subjects'])) {
                foreach ($group['subjects'] as $sData) {
                    if (isset($sData['id'])) {
                        $existingData[$sData['id']] = $sData;
                    }
                }
            }
        }

        // If departments are selected, use those; otherwise use all from faculties
        if (!empty($departmentIds)) {
            $departments = Department::whereIn('id', $departmentIds)
                ->with('faculty', 'subjects')
                ->get();
        } elseif (!empty($facultyIds)) {
            $departments = Department::whereIn('faculty_id', $facultyIds)
                ->with('faculty', 'subjects')
                ->get();
        } else {
            $set('proxied_subjects', []);
            return;
        }

        $state = [];
        foreach ($departments as $dept) {
            $facultyName = self::getTranslatedName($dept->faculty?->name);
            $deptName = self::getTranslatedName($dept->name);
            $groupLabel = "{$facultyName} → {$deptName}";

            $subjectList = [];
            foreach ($dept->subjects as $subj) {
                // Check if we have existing values for this subject
                $prev = $existingData[$subj->id] ?? [];

                $subjectList[md5($subj->id)] = [
                    'id' => $subj->id,
                    'code' => $subj->code,
                    'name' => self::getTranslatedName($subj->name),
                    // Preserve existing values if available, otherwise defaults
                    'is_mandatory' => isset($prev['is_mandatory']) ? (bool) $prev['is_mandatory'] : true,
                    'credit_hours' => isset($prev['credit_hours']) ? (float) $prev['credit_hours'] : 3.0,
                ];
            }

            if (count($subjectList) > 0) {
                $state[md5('dept_' . $dept->id)] = [
                    'faculty_id' => $dept->faculty_id,
                    'department_id' => $dept->id,
                    'group_label' => $groupLabel,
                    'subjects' => $subjectList,
                ];
            }
        }

        $set('proxied_subjects', $state);
    }

    protected static function getTranslatedName($name): string
    {
        if (is_array($name)) {
            return $name[app()->getLocale()] ?? $name['en'] ?? '';
        }
        return $name ?? '';
    }
}
