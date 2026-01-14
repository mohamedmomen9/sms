{{-- 
    Custom table view for the proxied_subjects repeater
    Excel-style grouped banded table - each group has its own unique color
--}}

@php
    $containers = $getChildComponentContainers();
    
    // Color palette for groups - each group gets a unique color
    // Format: [row background, header background (slightly darker)]
    $groupColors = [
        ['row' => '#eff6ff', 'header' => '#dbeafe'],  // Blue
        ['row' => '#f0fdf4', 'header' => '#dcfce7'],  // Green
    ];
    
    // Dark mode colors
    $groupColorsDark = [
        ['row' => '#1e3a5f', 'header' => '#1e40af'],  // Blue
        ['row' => '#14532d', 'header' => '#166534'],  // Green
    ];
@endphp

<div class="border rounded-xl shadow-sm bg-white dark:bg-gray-900 dark:border-gray-700 relative">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 dark:bg-gray-800 text-xs uppercase font-medium text-gray-500 dark:text-gray-400 sticky top-0 z-1 shadow-sm">
            <tr>
                <th class="px-4 py-3 min-w-[200px]">{{ __('subject::app.Subject Name') }}</th>
                <th class="px-4 py-3 w-32">{{ __('subject::app.Code') }}</th>
                <th class="px-4 py-3 w-24 text-center">{{ __('curriculum::app.Credit Hours') }}</th>
                <th class="px-4 py-3 w-20 text-center">{{ __('curriculum::app.Mandatory') }}</th>
                <th class="px-4 py-3 w-20 text-center">{{ __('subject::app.Requires GPA?') }}</th>
                <th class="px-4 py-3 w-24">{{ __('subject::app.Min GPA') }}</th>
                <th class="px-4 py-3 min-w-[200px]">{{ __('subject::app.Prerequisites') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($containers as $groupContainer)
                @php
                    $groupState = $groupContainer->getState();
                    $groupLabel = $groupState['group_label'] ?? 'Group';
                    $colorIndex = $loop->index % count($groupColors);
                    $colors = $groupColors[$colorIndex];
                    $colorsDark = $groupColorsDark[$colorIndex];
                    
                    $subjectRepeater = null;
                    foreach ($groupContainer->getComponents() as $comp) {
                        if ($comp->getName() === 'subjects') {
                            $subjectRepeater = $comp;
                            break;
                        }
                    }
                    
                    $subjectCount = $subjectRepeater ? count($subjectRepeater->getChildComponentContainers()) : 0;
                @endphp
                
                {{-- Group Header Row --}}
                <tr class="border-t-2 border-b border-gray-200 dark:border-gray-600 group-header" 
                    style="background-color: {{ $colors['header'] }};"
                    data-dark-bg="{{ $colorsDark['header'] }}">
                    <td colspan="7" class="px-4 py-2.5">
                        <span class="font-bold text-gray-800 dark:text-gray-100">{{ $groupLabel }}</span>
                        <span class="ml-2 text-xs font-normal text-gray-500 dark:text-gray-400">{{ $subjectCount }} {{ __('subject::app.Subjects') }}</span>
                    </td>
                </tr>

                @if($subjectRepeater)
                    @foreach ($subjectRepeater->getChildComponentContainers() as $subjectContainer)
                        @php
                            $subjectState = $subjectContainer->getState();
                            $componentMap = [];
                            foreach ($subjectContainer->getComponents() as $comp) {
                                $componentMap[$comp->getName()] = $comp;
                            }
                        @endphp
                        
                        <tr class="border-b border-gray-100 dark:border-gray-700 hover:opacity-80 transition-opacity group-row"
                            style="background-color: {{ $colors['row'] }};"
                            data-dark-bg="{{ $colorsDark['row'] }}">
                            <td class="px-4 py-2 align-middle">
                                <span class="font-medium text-gray-900 dark:text-white text-sm">{{ $subjectState['name'] ?? '—' }}</span>
                                <div class="hidden">
                                    @if(isset($componentMap['id'])){!! $componentMap['id']->toHtml() !!}@endif
                                    @if(isset($componentMap['name'])){!! $componentMap['name']->toHtml() !!}@endif
                                </div>
                            </td>
                            <td class="px-4 py-2 align-middle text-gray-600 dark:text-gray-400 font-mono text-xs">
                                {{ $subjectState['code'] ?? '—' }}
                                <div class="hidden">@if(isset($componentMap['code'])){!! $componentMap['code']->toHtml() !!}@endif</div>
                            </td>
                            <td class="px-4 py-2 align-middle text-center">
                                <div class="w-20">@if(isset($componentMap['credit_hours'])){!! $componentMap['credit_hours']->toHtml() !!}@endif</div>
                            </td>
                            <td class="px-4 py-2 align-middle text-center">
                                <div class="flex justify-center">@if(isset($componentMap['is_mandatory'])){!! $componentMap['is_mandatory']->toHtml() !!}@endif</div>
                            </td>
                            <td class="px-4 py-2 align-middle text-center">
                                <div class="flex justify-center">@if(isset($componentMap['uses_gpa'])){!! $componentMap['uses_gpa']->toHtml() !!}@endif</div>
                            </td>
                            <td class="px-4 py-2 align-middle">
                                <div class="w-20">@if(isset($componentMap['gpa_requirement'])){!! $componentMap['gpa_requirement']->toHtml() !!}@endif</div>
                            </td>
                            <td class="px-4 py-2 align-middle">
                                <div class="min-w-[180px]">@if(isset($componentMap['prerequisites_ids'])){!! $componentMap['prerequisites_ids']->toHtml() !!}@endif</div>
                            </td>
                        </tr>
                    @endforeach
                    
                    @if(count($subjectRepeater->getChildComponentContainers()) === 0)
                        <tr style="background-color: {{ $colors['row'] }};">
                            <td colspan="7" class="px-4 py-3 text-center text-gray-400 italic text-sm">
                                {{ __('curriculum::app.No subjects in this group.') }}
                            </td>
                        </tr>
                    @endif
                @endif
            @endforeach
        </tbody>
    </table>
    
    @if(count($containers) === 0)
        <div class="p-8 text-center text-gray-500 dark:text-gray-400 italic">
            {{ __('curriculum::app.No subjects loaded. Select Faculty and Departments first.') }}
        </div>
    @endif
</div>

{{-- Dark mode color switcher --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        function updateColors() {
            const isDark = document.documentElement.classList.contains('dark');
            document.querySelectorAll('[data-dark-bg]').forEach(el => {
                if (isDark) {
                    el.style.backgroundColor = el.dataset.darkBg;
                } else {
                    // Reset to original inline style (stored in data attribute on first run)
                    if (!el.dataset.lightBg) {
                        el.dataset.lightBg = el.style.backgroundColor;
                    }
                    el.style.backgroundColor = el.dataset.lightBg;
                }
            });
        }
        
        // Run on load
        updateColors();
        
        // Observe for dark mode changes
        const observer = new MutationObserver(updateColors);
        observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
    });
</script>
