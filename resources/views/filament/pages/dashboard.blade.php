<x-filament-panels::page>
    {{-- Quick Stats Section - At Top --}}
    <h2 class="text-lg font-semibold text-gray-950 dark:text-white mb-4">{{ __('Overview') }}</h2>
    <div class="grid gap-4 grid-cols-2 md:grid-cols-3 xl:grid-cols-6 mb-8">
        @php
            $stats = [
                ['label' => __('Campuses'), 'value' => \Modules\Campus\Models\Campus::count(), 'icon' => 'heroicon-o-map-pin', 'color' => 'red'],
                ['label' => __('Faculties'), 'value' => \Modules\Faculty\Models\Faculty::count(), 'icon' => 'heroicon-o-building-library', 'color' => 'cyan'],
                ['label' => __('Departments'), 'value' => \Modules\Department\Models\Department::count(), 'icon' => 'heroicon-o-building-office', 'color' => 'sky'],
                ['label' => __('Teachers'), 'value' => \Modules\Teachers\Models\Teacher::count(), 'icon' => 'heroicon-o-user-group', 'color' => 'rose'],
                ['label' => __('Students'), 'value' => \Modules\Students\Models\Student::count(), 'icon' => 'heroicon-o-academic-cap', 'color' => 'fuchsia'],
                ['label' => __('Active Courses'), 'value' => \Modules\Subject\Models\CourseOffering::whereHas('term', fn($q) => $q->where('is_active', true))->count(), 'icon' => 'heroicon-o-book-open', 'color' => 'indigo'],
            ];
        @endphp
        
        @foreach($stats as $stat)
            <div class="fi-wi-stats-overview-stat relative rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="flex items-center gap-4">
                    <div class="rounded-lg bg-{{ $stat['color'] }}-50 p-3 dark:bg-{{ $stat['color'] }}-400/10">
                        <x-filament::icon
                            :icon="$stat['icon']"
                            class="h-6 w-6 text-{{ $stat['color'] }}-600 dark:text-{{ $stat['color'] }}-400"
                        />
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                            {{ $stat['label'] }}
                        </p>
                        <p class="text-2xl font-semibold text-gray-950 dark:text-white">
                            {{ number_format($stat['value']) }}
                        </p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Navigation Groups --}}
    <h2 class="text-lg font-semibold text-gray-950 dark:text-white mb-4">{{ __('Quick Access') }}</h2>
    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
        @foreach($this->getGroupedNavigation() as $group)
            <div class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="fi-section-header flex items-center gap-3 px-6 py-4">
                    <div class="flex-1">
                        <h3 class="fi-section-header-heading text-base font-semibold leading-6 text-gray-950 dark:text-white">
                            {{ $group['label'] }}
                        </h3>
                    </div>
                    <span class="inline-flex items-center justify-center rounded-full bg-primary-50 px-2 py-1 text-xs font-medium text-primary-700 ring-1 ring-inset ring-primary-600/20 dark:bg-primary-400/10 dark:text-primary-400 dark:ring-primary-400/30">
                        {{ count($group['items']) }}
                    </span>
                </div>
                <div class="fi-section-content border-t border-gray-200 dark:border-white/10">
                    <ul class="divide-y divide-gray-100 dark:divide-white/5">
                        @foreach($group['items'] as $item)
                            <li>
                                <a 
                                    href="{{ $item['url'] }}"
                                    class="flex items-center gap-3 px-6 py-3 text-sm transition hover:bg-gray-50 dark:hover:bg-white/5 {{ $item['isActive'] ? 'bg-primary-50 dark:bg-primary-400/10' : '' }}"
                                >
                                    @if($item['icon'])
                                        @php
                                            $iconColor = match($item['label']) {
                                                // Campus Management
                                                __('Campus'), __('Campuses') => 'text-red-500', 
                                                __('Building'), __('Buildings') => 'text-orange-500',
                                                __('Room'), __('Rooms') => 'text-amber-500',
                                                __('Facility'), __('Facilities') => 'text-lime-500',

                                                // Academic Structure
                                                __('Academic Year'), __('Academic Years') => 'text-emerald-500',
                                                __('Term'), __('Terms') => 'text-teal-500',
                                                __('Faculty'), __('Faculties') => 'text-cyan-500',
                                                __('Department'), __('Departments') => 'text-sky-500',
                                                __('Curriculum'), __('Curricula') => 'text-blue-500',

                                                // Course Management
                                                __('Course Offering'), __('Course Offerings') => 'text-indigo-500',
                                                __('Subject'), __('Subjects') => 'text-violet-500',
                                                __('Session Type'), __('Session Types') => 'text-purple-500',

                                                // Users
                                                __('Student'), __('Students') => 'text-fuchsia-500',
                                                __('Teacher'), __('Teachers') => 'text-rose-500',
                                                __('User'), __('Users') => 'text-slate-500',
                                                __('Role'), __('Roles') => 'text-zinc-500',
                                                __('Permission'), __('Permissions') => 'text-neutral-500',

                                                default => 'text-primary-500',
                                            };
                                        @endphp
                                        <x-filament::icon
                                            :icon="$item['icon']"
                                            class="h-5 w-5 {{ $iconColor }} dark:{{ str_replace('500', '400', $iconColor) }}"
                                        />
                                    @endif
                                    <span class="font-medium text-gray-700 dark:text-gray-200">
                                        {{ $item['label'] }}
                                    </span>
                                    <x-filament::icon
                                        icon="heroicon-m-chevron-right"
                                        class="ml-auto h-4 w-4 text-gray-400"
                                    />
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endforeach
    </div>
</x-filament-panels::page>
