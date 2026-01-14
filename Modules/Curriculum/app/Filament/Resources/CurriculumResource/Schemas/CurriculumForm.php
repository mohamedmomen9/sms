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
                            
                            $currentDepts = $get('departments') ?? [];
                            if (!empty($selectedFacultyIds)) {
                                if (!empty($currentDepts)) {
                                    $validDepts = Department::whereIn('id', $currentDepts)
                                        ->whereIn('faculty_id', $selectedFacultyIds)
                                        ->pluck('id')
                                        ->toArray();
                                    $set('departments', $validDepts);
                                }
                            } else {
                                $set('departments', []);
                            }
                            
                            self::rebuildSubjectsState($selectedFacultyIds, $get('departments') ?? [], $set, $get('proxied_subjects') ?? []);
                        }),

                    Select::make('departments')
                        ->label(__('app.Departments'))
                        ->multiple()
                        ->options(function (Get $get) {
                            $facultyIds = $get('faculties') ?? [];
                            if (empty($facultyIds)) {
                                return []; // No departments if no faculty selected
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
                        ->disabled(fn (Get $get) => empty($get('faculties')))
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

            Repeater::make('proxied_subjects')
                ->label(__('curriculum::app.Subjects (Grouped by Faculty)'))
                ->columnSpanFull()
                ->view('curriculum::filament.forms.components.subjects-table')
                ->dehydrated(true)
                ->schema([
                    Hidden::make('faculty_id'),
                    Hidden::make('department_id'),
                    Hidden::make('group_label'),

                    Repeater::make('subjects')
                        ->hiddenLabel()
                        ->schema([
                            Hidden::make('id'),
                            Hidden::make('code'),
                            Hidden::make('name'),
                            
                            TextInput::make('credit_hours')
                                        ->hiddenLabel()
                                        ->numeric()
                                        ->default(3.0)
                                        ->required()
                                        ->minValue(0.1)
                                        ->rule('gt:0')
                                        ->extraInputAttributes(['min' => 0.1, 'class' => 'h-8 text-sm']),

                                    Toggle::make('is_mandatory')
                                        ->hiddenLabel()
                                        ->inline(false)
                                        ->onColor('success')
                                        ->offColor('danger')
                                        ->default(true),
                                    
                                    Toggle::make('uses_gpa')
                                        ->hiddenLabel()
                                        ->inline(false)
                                        ->live(onBlur: true)
                                        ->afterStateUpdated(fn (Set $set, $state) => !$state ? $set('gpa_requirement', null) : null),

                                    TextInput::make('gpa_requirement')
                                        ->hiddenLabel()
                                        ->numeric()
                                        ->minValue(0)
                                        ->maxValue(5.0)
                                        ->step(0.01)
                                        ->placeholder('—')
                                        ->disabled(fn (Get $get) => !($get('uses_gpa') && $get('is_mandatory')))
                                        ->nullable()
                                        ->extraInputAttributes(['class' => 'h-8 text-sm']),

                                    Select::make('prerequisites_ids')
                                        ->hiddenLabel()
                                        ->multiple()
                                        ->placeholder('None')
                                        ->options(fn () => Subject::all()->mapWithKeys(fn ($s) => [$s->id => self::getTranslatedName($s->name) . " ({$s->code})"]))
                                        ->searchable()
                                        ->preload()
                                        ->extraAttributes(['class' => 'min-w-[200px]']),
                                ])
                                ->addable(false)
                                ->deletable(false)
                                ->reorderable(false)
                ])
                ->addable(false)
                ->deletable(false)
                ->reorderable(false)
                ->afterStateHydrated(function (Repeater $component, $record) {
                    if (!$record) {
                        return;
                    }
                    $facultyIds = $record->faculties->pluck('id')->toArray();
                    $departmentIds = $record->departments->pluck('id')->toArray();
                    
                    $state = [];
                    $linkedSubjects = $record->subjects->keyBy('id');
                    
                     $faculties = Faculty::whereIn('id', $facultyIds)->with(['departments', 'subjects.prerequisites'])->get();
                     $selectedDeptIds = collect($departmentIds);

                     foreach ($faculties as $faculty) {
                         $facultySubjects = collect();
                         $groupLabel = "";
                         $deptId = null;

                         if ($faculty->departments->count() > 0) {
                             $validDepts = $faculty->departments->whereIn('id', $selectedDeptIds);
                             
                             foreach ($validDepts as $dept) {
                                  $dept->load('subjects.prerequisites');
                                  $facultyName = self::getTranslatedName($faculty->name);
                                  $deptName = self::getTranslatedName($dept->name);
                                  $groupLabel = "{$facultyName} → {$deptName}";
                                  
                                  self::addSubjectsToState($state, $dept->subjects, $linkedSubjects, $faculty->id, $dept->id, $groupLabel);
                             }
                         } else {
                             $facultyName = self::getTranslatedName($faculty->name);
                             $groupLabel = "{$facultyName} (" . __('subject::app.Direct to Faculty') . ")";
                             
                             self::addSubjectsToState($state, $faculty->subjects, $linkedSubjects, $faculty->id, null, $groupLabel);
                         }
                     }

                    $component->state($state);
                }),
        ];
    }
    
    protected static function addSubjectsToState(array &$state, $subjects, $linkedSubjects, $facultyId, $deptId, $groupLabel) 
    {
        $subjectList = [];
        foreach ($subjects as $subj) {
            $linked = $linkedSubjects->get($subj->id);
            
            $subjectList[md5($subj->id)] = [
                'id' => $subj->id,
                'code' => $subj->code,
                'name' => self::getTranslatedName($subj->name),
                'is_mandatory' => $linked ? (bool) $linked->pivot->is_mandatory : true,
                'credit_hours' => $linked ? ($linked->pivot->credit_hours ?? 3.0) : 3.0,
                'uses_gpa' => $linked ? (bool) $linked->pivot->uses_gpa : false,
                'gpa_requirement' => $linked ? $linked->pivot->gpa_requirement : null,
                'prerequisites_ids' => $subj->prerequisites->pluck('id')->toArray(),
            ];
        }

        if (count($subjectList) > 0) {
            $key = md5('group_' . $facultyId . '_' . ($deptId ?? 'null'));
            $state[$key] = [
                'faculty_id' => $facultyId,
                'department_id' => $deptId,
                'group_label' => $groupLabel,
                'subjects' => $subjectList,
            ];
        }
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
                            ->maxLength(255),
                    ]),
            ])
            ->columnSpanFull()
            ->contained(true);
    }

    protected static function rebuildSubjectsState(array $facultyIds, array $departmentIds, Set $set, array $currentState = []): void
    {
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
        
        $state = [];
        
        if (empty($facultyIds)) {
            $set('proxied_subjects', []);
            return;
        }

        $faculties = Faculty::whereIn('id', $facultyIds)
            ->with(['departments.subjects.prerequisites', 'subjects.prerequisites'])
            ->get();
        
        $selectedDeptIds = collect($departmentIds);

        foreach ($faculties as $faculty) {
             $hasDepartmentsInSystem = $faculty->departments->isNotEmpty();

             if ($hasDepartmentsInSystem) {
                 $validDepts = $faculty->departments->whereIn('id', $selectedDeptIds);
                 foreach ($validDepts as $dept) {
                      $facultyName = self::getTranslatedName($faculty->name);
                      $deptName = self::getTranslatedName($dept->name);
                      $groupLabel = "{$facultyName} → {$deptName}";
                      
                      self::addSubjectsToStateFromRebuild($state, $dept->subjects, $existingData, $faculty->id, $dept->id, $groupLabel);
                 }
                 
             } else {
                 $facultyName = self::getTranslatedName($faculty->name);
                 $groupLabel = "{$facultyName} (" . __('subject::app.Direct to Faculty') . ")";
                 
                 self::addSubjectsToStateFromRebuild($state, $faculty->subjects, $existingData, $faculty->id, null, $groupLabel);
             }
        }

        $set('proxied_subjects', $state);
    }
    
    protected static function addSubjectsToStateFromRebuild(array &$state, $subjects, $existingData, $facultyId, $deptId, $groupLabel)
    {
        $subjectList = [];
        foreach ($subjects as $subj) {
            $prev = $existingData[$subj->id] ?? [];
            
            $prereqIds = [];
            if (isset($prev['prerequisites_ids'])) {
                $prereqIds = $prev['prerequisites_ids'];
            } else {
                $prereqIds = $subj->prerequisites->pluck('id')->toArray();
            }

            $subjectList[md5($subj->id)] = [
                'id' => $subj->id,
                'code' => $subj->code,
                'name' => self::getTranslatedName($subj->name),
                'is_mandatory' => isset($prev['is_mandatory']) ? (bool) $prev['is_mandatory'] : true,
                'credit_hours' => isset($prev['credit_hours']) ? (float) $prev['credit_hours'] : 3.0,
                'uses_gpa' => isset($prev['uses_gpa']) ? (bool) $prev['uses_gpa'] : false,
                'gpa_requirement' => isset($prev['gpa_requirement']) ? $prev['gpa_requirement'] : null,
                'prerequisites_ids' => $prereqIds,
            ];
        }

        if (count($subjectList) > 0) {
            $key = md5('group_' . $facultyId . '_' . ($deptId ?? 'null'));
            $state[$key] = [
                'faculty_id' => $facultyId,
                'department_id' => $deptId,
                'group_label' => $groupLabel,
                'subjects' => $subjectList,
            ];
        }
    }

    protected static function getTranslatedName($name): string
    {
        if (is_array($name)) {
            return $name[app()->getLocale()] ?? $name['en'] ?? '';
        }
        return $name ?? '';
    }
}
