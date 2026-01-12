<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <x-heroicon-o-calendar-days class="h-5 w-5 text-primary-500" />
                {{ __('My Schedule') }}
            </div>
        </x-slot>

        <x-slot name="description">
            {{ __('Your classes for the current term') }}
        </x-slot>

        @if(!$hasSchedule)
            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                <x-heroicon-o-calendar class="h-12 w-12 mx-auto mb-3 opacity-50" />
                <p class="text-lg font-medium">{{ __('No Schedule Available') }}</p>
                <p class="text-sm">{{ __('You have no classes scheduled for this term.') }}</p>
            </div>
        @else
            {{-- Today's Classes Section --}}
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-3 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-success-500 animate-pulse"></span>
                    {{ __('Today') }} - {{ ucfirst($currentDay) }}
                </h3>

                @if(count($todayClasses) > 0)
                    <div class="space-y-3">
                        @foreach($todayClasses as $class)
                            <div class="relative flex items-start gap-4 p-4 rounded-xl 
                                {{ $loop->first && $upcomingClass && ($upcomingClass['course_offering_id'] ?? null) === ($class['course_offering_id'] ?? null) 
                                    ? 'bg-primary-50 dark:bg-primary-950/50 border-2 border-primary-500' 
                                    : 'bg-gray-50 dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700' 
                                }}">
                                
                                {{-- Time Badge --}}
                                <div class="flex flex-col items-center text-center min-w-[80px]">
                                    <span class="text-lg font-bold text-primary-600 dark:text-primary-400">
                                        {{ \Carbon\Carbon::parse($class['start_time'] ?? '00:00')->format('g:i A') }}
                                    </span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ \Carbon\Carbon::parse($class['end_time'] ?? '00:00')->format('g:i A') }}
                                    </span>
                                </div>

                                {{-- Class Details --}}
                                <div class="flex-1">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <h4 class="font-semibold text-gray-900 dark:text-white">
                                                {{ $class['subject']['name'] ?? __('Unknown Subject') }}
                                            </h4>
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                {{ $class['subject']['code'] ?? '' }} 
                                                @if($class['section_number'])
                                                    - {{ __('Section') }} {{ $class['section_number'] }}
                                                @endif
                                            </p>
                                        </div>
                                        
                                        {{-- Enrollment Badge --}}
                                        @if(isset($class['enrollment_count']) && isset($class['capacity']))
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                {{ ($class['enrollment_count'] / max($class['capacity'], 1)) > 0.8 
                                                    ? 'bg-warning-100 text-warning-800 dark:bg-warning-900 dark:text-warning-300' 
                                                    : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' 
                                                }}">
                                                <x-heroicon-m-user-group class="h-3 w-3 mr-1" />
                                                {{ $class['enrollment_count'] }}/{{ $class['capacity'] }}
                                            </span>
                                        @endif
                                    </div>

                                    {{-- Location --}}
                                    @if($class['location']['room']['label'] ?? null)
                                        <div class="mt-2 flex items-center gap-1 text-sm text-gray-500 dark:text-gray-400">
                                            <x-heroicon-m-map-pin class="h-4 w-4" />
                                            {{ $class['location']['room']['label'] }}
                                        </div>
                                    @endif
                                </div>

                                {{-- Next Up Indicator --}}
                                @if($upcomingClass && ($upcomingClass['course_offering_id'] ?? null) === ($class['course_offering_id'] ?? null))
                                    <div class="absolute top-0 right-0 -mt-2 -mr-2">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-primary-500 text-white shadow-lg">
                                            {{ __('Next Up') }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-6 bg-gray-50 dark:bg-gray-800/50 rounded-xl">
                        <x-heroicon-o-check-circle class="h-10 w-10 mx-auto mb-2 text-success-500" />
                        <p class="text-gray-600 dark:text-gray-400">{{ __('No classes scheduled for today!') }}</p>
                    </div>
                @endif
            </div>

            {{-- Weekly Schedule Overview --}}
            <div>
                <h3 class="text-lg font-semibold mb-3 flex items-center gap-2">
                    <x-heroicon-o-calendar class="h-5 w-5" />
                    {{ __('Weekly Overview') }}
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3">
                    @foreach($groupedSchedule as $dayGroup)
                        <div class="p-3 rounded-lg border 
                            {{ ($dayGroup['day'] ?? '') === $currentDay 
                                ? 'bg-primary-50 dark:bg-primary-950/30 border-primary-300 dark:border-primary-700' 
                                : 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700' 
                            }}">
                            <h4 class="font-semibold text-sm mb-2 
                                {{ ($dayGroup['day'] ?? '') === $currentDay 
                                    ? 'text-primary-700 dark:text-primary-300' 
                                    : 'text-gray-700 dark:text-gray-300' 
                                }}">
                                {{ ucfirst($dayGroup['day'] ?? __('Unknown')) }}
                                <span class="text-xs font-normal text-gray-500 dark:text-gray-400 ml-1">
                                    ({{ count($dayGroup['classes'] ?? []) }} {{ trans_choice('class|classes', count($dayGroup['classes'] ?? [])) }})
                                </span>
                            </h4>

                            <div class="space-y-1.5">
                                @foreach($dayGroup['classes'] ?? [] as $class)
                                    <div class="flex items-center gap-2 text-xs">
                                        <span class="font-mono text-gray-600 dark:text-gray-400">
                                            {{ \Carbon\Carbon::parse($class['start_time'] ?? '00:00')->format('H:i') }}
                                        </span>
                                        <span class="truncate text-gray-700 dark:text-gray-300" title="{{ $class['subject']['name'] ?? '' }}">
                                            {{ $class['subject']['code'] ?? '' }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
