<x-filament-panels::page>
    {{-- Quick Stats Section - At Top --}}
    <h2 class="text-lg font-semibold text-gray-950 dark:text-white mb-4">{{ __('Overview') }}</h2>
    <div class="grid gap-4 grid-cols-2 md:grid-cols-3 xl:grid-cols-6 mb-8">
        @php
            $stats = [
                ['label' => __('Campuses'), 'value' => \Modules\Campus\Models\Campus::count(), 'icon' => 'heroicon-o-map-pin', 'color' => 'rgb(239, 68, 68)'], // Red
                ['label' => __('Faculties'), 'value' => \Modules\Faculty\Models\Faculty::count(), 'icon' => 'heroicon-o-building-library', 'color' => 'rgb(6, 182, 212)'], // Cyan
                ['label' => __('Departments'), 'value' => \Modules\Department\Models\Department::count(), 'icon' => 'heroicon-o-building-office', 'color' => 'rgb(14, 165, 233)'], // Sky
                ['label' => __('Teachers'), 'value' => \Modules\Teachers\Models\Teacher::count(), 'icon' => 'heroicon-o-user-group', 'color' => 'rgb(244, 63, 94)'], // Rose
                ['label' => __('Students'), 'value' => \Modules\Students\Models\Student::count(), 'icon' => 'heroicon-o-academic-cap', 'color' => 'rgb(217, 70, 239)'], // Fuchsia
                ['label' => __('Active Courses'), 'value' => \Modules\Subject\Models\CourseOffering::whereHas('term', fn($q) => $q->where('is_active', true))->count(), 'icon' => 'heroicon-o-book-open', 'color' => 'rgb(99, 102, 241)'], // Indigo
            ];
        @endphp
        
        @foreach($stats as $stat)
            <div class="fi-wi-stats-overview-stat relative rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="flex items-center gap-4">
                    <div class="rounded-lg p-3 custom-stat-icon" style="background-color: {{ str_replace('rgb(', 'rgba(', str_replace(')', ', 0.1)', $stat['color'])) }};">
                        <x-filament::icon
                            :icon="$stat['icon']"
                            class="h-6 w-6"
                            style="color: {{ $stat['color'] }}"
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
                                                __('Campus'), __('Campuses') => 'rgb(239, 68, 68)', // Red
                                                __('Building'), __('Buildings') => 'rgb(249, 115, 22)', // Orange
                                                __('Room'), __('Rooms') => 'rgb(245, 158, 11)', // Amber
                                                __('Facility'), __('Facilities') => 'rgb(132, 204, 22)', // Lime

                                                // Academic Structure
                                                __('Academic Year'), __('Academic Years') => 'rgb(16, 185, 129)', // Emerald
                                                __('Term'), __('Terms') => 'rgb(20, 184, 166)', // Teal
                                                __('Faculty'), __('Faculties') => 'rgb(6, 182, 212)', // Cyan
                                                __('Department'), __('Departments') => 'rgb(14, 165, 233)', // Sky
                                                __('Curriculum'), __('Curricula') => 'rgb(59, 130, 246)', // Blue

                                                // Course Management
                                                __('Course Offering'), __('Course Offerings') => 'rgb(99, 102, 241)', // Indigo
                                                __('Subject'), __('Subjects') => 'rgb(139, 92, 246)', // Violet
                                                __('Session Type'), __('Session Types') => 'rgb(168, 85, 247)', // Purple

                                                // Users
                                                __('Student'), __('Students') => 'rgb(217, 70, 239)', // Fuchsia
                                                __('Teacher'), __('Teachers') => 'rgb(244, 63, 94)', // Rose
                                                __('User'), __('Users') => 'rgb(100, 116, 139)', // Slate
                                                __('Role'), __('Roles') => 'rgb(113, 113, 122)', // Zinc
                                                __('Permission'), __('Permissions') => 'rgb(115, 115, 115)', // Neutral

                                                default => 'rgb(99, 102, 241)', // Primary/Indigo
                                            };
                                        @endphp
                                        <x-filament::icon
                                            :icon="$item['icon']"
                                            class="h-5 w-5"
                                            style="color: {{ $iconColor }}"
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
