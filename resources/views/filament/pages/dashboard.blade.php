<x-filament-panels::page>
    {{-- Quick Stats Section - At Top --}}
    <h2 class="text-lg font-semibold text-gray-950 dark:text-white mb-4">{{ __('Overview') }}</h2>
    <div class="grid gap-4 grid-cols-2 md:grid-cols-3 xl:grid-cols-6 mb-8">
        @php
            $stats = [
                [
                    'label' => __('Campuses'),
                    'value' => \Modules\Campus\Models\Campus::count(),
                    'icon' => 'heroicon-o-map-pin',
                    'color' => 'rgb(239, 68, 68)',
                ], // Red
                [
                    'label' => __('Faculties'),
                    'value' => \Modules\Faculty\Models\Faculty::count(),
                    'icon' => 'heroicon-o-building-library',
                    'color' => 'rgb(6, 182, 212)',
                ], // Cyan
                [
                    'label' => __('Departments'),
                    'value' => \Modules\Department\Models\Department::count(),
                    'icon' => 'heroicon-o-building-office',
                    'color' => 'rgb(14, 165, 233)',
                ], // Sky
                [
                    'label' => __('Teachers'),
                    'value' => \Modules\Teachers\Models\Teacher::count(),
                    'icon' => 'heroicon-o-user-group',
                    'color' => 'rgb(244, 63, 94)',
                ], // Rose
                [
                    'label' => __('Students'),
                    'value' => \Modules\Students\Models\Student::count(),
                    'icon' => 'heroicon-o-academic-cap',
                    'color' => 'rgb(217, 70, 239)',
                ], // Fuchsia
                [
                    'label' => __('Active Courses'),
                    'value' => \Modules\Subject\Models\CourseOffering::whereHas(
                        'term',
                        fn($q) => $q->where('is_active', true),
                    )->count(),
                    'icon' => 'heroicon-o-book-open',
                    'color' => 'rgb(99, 102, 241)',
                ], // Indigo
            ];
        @endphp

        @foreach ($stats as $stat)
            <div
                class="fi-wi-stats-overview-stat relative rounded-xl bg-white p-6 shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="flex items-center gap-4">
                    <div class="rounded-lg p-3 custom-stat-icon"
                        style="background-color: {{ str_replace('rgb(', 'rgba(', str_replace(')', ', 0.1)', $stat['color'])) }};">
                        <x-filament::icon :icon="$stat['icon']" class="h-6 w-6" style="color: {{ $stat['color'] }}" />
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
        @foreach ($this->getGroupedNavigation() as $group)
            <div
                class="fi-section rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="fi-section-header flex items-center gap-3 px-6 py-4">
                    <div class="flex-1">
                        <h3
                            class="fi-section-header-heading text-base font-semibold leading-6 text-gray-950 dark:text-white">
                            {{ $group['label'] }}
                        </h3>
                    </div>
                    <span
                        class="inline-flex items-center justify-center rounded-full bg-primary-50 px-2 py-1 text-xs font-medium text-primary-700 ring-1 ring-inset ring-primary-600/20 dark:bg-primary-400/10 dark:text-primary-400 dark:ring-primary-400/30">
                        {{ count($group['items']) }}
                    </span>
                </div>
                <div class="fi-section-content border-t border-gray-200 dark:border-white/10">
                    <ul class="divide-y divide-gray-100 dark:divide-white/5">
                        @foreach ($group['items'] as $item)
                            <li>
                                <a href="{{ $item['url'] }}"
                                    class="flex items-center gap-3 px-6 py-3 text-sm transition hover:bg-gray-50 dark:hover:bg-white/5 {{ $item['isActive'] ? 'bg-primary-50 dark:bg-primary-400/10' : '' }}">
                                    @if ($item['icon'])
                                        @php
                                            $iconColor = match ($item['label']) {
                                                // Campus Management (Red Palette) - Alphabetical: Buildings, Campuses, Facilities, Rooms
                                                __('Building'), __('Buildings') => 'rgb(252, 165, 165)', // Red-300
                                                __('Campus'), __('Campuses') => 'rgb(248, 113, 113)', // Red-400
                                                __('Facility'), __('Facilities') => 'rgb(239, 68, 68)', // Red-500
                                                __('Room'), __('Rooms') => 'rgb(220, 38, 38)', // Red-600
                                                // Red-600

                                                // Academic Structure (Blue Palette) - Alphabetical: Academic Years, Curricula, Departments, Faculties, Terms
                                                __('Academic Year'),
                                                __('Academic Years')
                                                    => 'rgb(147, 197, 253)', // Blue-300
                                                __('Curriculum'), __('Curricula') => 'rgb(96, 165, 250)', // Blue-400
                                                __('Department'), __('Departments') => 'rgb(59, 130, 246)', // Blue-500
                                                __('Faculty'), __('Faculties') => 'rgb(37, 99, 235)', // Blue-600
                                                __('Term'), __('Terms') => 'rgb(29, 78, 216)', // Blue-700
                                                // Blue-700

                                                // Course Management (Violet Palette) - Alphabetical: Course Offerings, Session Types, Subjects
                                                __('Course Offering'),
                                                __('Course Offerings')
                                                    => 'rgb(167, 139, 250)', // Violet-400
                                                __('Session Type'),
                                                __('Session Types')
                                                    => 'rgb(139, 92, 246)', // Violet-500
                                                __('Subject'), __('Subjects') => 'rgb(124, 58, 237)', // Violet-600
                                                // Violet-600

                                                // Academic Management (Emerald Palette) - Alphabetical: Assessments, Parents, Students, Teachers, Tutorial Analytics
                                                __('Assessment'),
                                                __('Assessments')
                                                    => 'rgb(110, 231, 183)', // Emerald-300
                                                'Parent',
                                                'Parents',
                                                __('Parent'),
                                                __('Parents'),
                                                __('Guardian'),
                                                __('Guardians')
                                                    => 'rgb(52, 211, 153)', // Emerald-400
                                                __('Student'), __('Students') => 'rgb(16, 185, 129)', // Emerald-500
                                                __('Teacher'), __('Teachers') => 'rgb(5, 150, 105)', // Emerald-600
                                                // Emerald-600

                                                // Service Management (Amber Palette)
                                                __('Appointment'),
                                                __('Appointments')
                                                    => 'rgb(245, 158, 11)', // Amber-500
                                                __('Service Request'),
                                                __('Service Requests')
                                                    => 'rgb(217, 119, 6)', // Amber-600
                                                // Amber-600
                                                // Amber-600
                                                // Amber-600
                                                // Amber-600
                                                // Amber-600

                                                // Student Affairs (Rose Palette)
                                                __('Grievance'), __('Grievances') => 'rgb(244, 63, 94)', // Rose-500
                                                // Rose-500
                                                // Rose-500
                                                // Rose-500
                                                // Rose-500
                                                // Rose-500

                                                // Admissions (Orange Palette)
                                                __('Applicant'), __('Applicants') => 'rgb(249, 115, 22)', // Orange-500
                                                // Orange-500
                                                // Orange-500
                                                // Orange-500
                                                // Orange-500
                                                // Orange-500

                                                // Communications (Cyan Palette)
                                                __('Announcement'),
                                                __('Announcements')
                                                    => 'rgb(6, 182, 212)', // Cyan-500
                                                __('Notification'),
                                                __('Notifications')
                                                    => 'rgb(8, 145, 178)', // Cyan-600
                                                // Cyan-600
                                                // Cyan-600
                                                // Cyan-600
                                                // Cyan-600
                                                // Cyan-600

                                                // Engagement (Teal Palette)
                                                __('Survey'), __('Surveys') => 'rgb(20, 184, 166)', // Teal-500
                                                __('Survey Log'), __('Survey Logs') => 'rgb(13, 148, 136)', // Teal-600
                                                // Teal-600
                                                // Teal-600
                                                // Teal-600
                                                // Teal-600
                                                // Teal-600

                                                // Marketing (Lime Palette)
                                                __('Offer'), __('Offers') => 'rgb(132, 204, 22)', // Lime-500
                                                __('Offer Log'), __('Offer Logs') => 'rgb(101, 163 13)', // Lime-600
                                                // Lime-600
                                                // Lime-600
                                                // Lime-600
                                                // Lime-600
                                                // Lime-600

                                                // System (Slate Palette)
                                                __('App Version'),
                                                __('App Versions')
                                                    => 'rgb(100, 116, 139)', // Slate-500
                                                __('Lookup Item'),
                                                __('Lookup Items')
                                                    => 'rgb(71, 85, 105)', // Slate-600
                                                __('System Setting'),
                                                __('System Settings')
                                                    => 'rgb(51, 65, 85)', // Slate-700
                                                __('User Agreement'),
                                                __('User Agreements')
                                                    => 'rgb(30, 41, 59)', // Slate-800

                                                default => 'rgb(99, 102, 241)', // Primary/Indigo
                                            };
                                        @endphp
                                        <x-filament::icon :icon="$item['icon']" class="h-5 w-5"
                                            style="color: {{ $iconColor }}" />
                                    @endif
                                    <span class="flex-1 font-medium text-gray-700 dark:text-gray-200">
                                        {{ $item['label'] }}
                                    </span>
                                    @if (isset($item['count']) && $item['count'] !== null)
                                        <span
                                            class="mr-2 inline-flex items-center justify-center rounded-full bg-gray-50 px-2 py-1 text-xs font-medium text-gray-600 ring-1 ring-inset ring-gray-500/10 dark:bg-gray-400/10 dark:text-gray-400 dark:ring-gray-400/20">
                                            {{ $item['count'] }}
                                        </span>
                                    @endif
                                    <x-filament::icon icon="heroicon-m-chevron-right" class="h-4 w-4 text-gray-400" />
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endforeach
    </div>
</x-filament-panels::page>
