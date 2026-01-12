<?php

namespace App\Filament\TeacherPanel\Widgets;

use Filament\Widgets\Widget;
use Modules\Academic\Services\ScheduleService;
use Modules\Teachers\Models\Teacher;

class TeacherScheduleWidget extends Widget
{
    protected static string $view = 'filament.teacher-panel.widgets.teacher-schedule-widget';

    protected static ?int $sort = 1;

    protected int | string | array $columnSpan = 'full';

    public array $schedule = [];
    public array $groupedSchedule = [];
    public ?string $currentDay = null;

    public function mount(): void
    {
        $this->loadSchedule();
        $this->currentDay = now()->format('l'); // e.g., "Monday"
    }

    protected function loadSchedule(): void
    {
        /** @var Teacher|null $teacher */
        $teacher = auth('teacher')->user();

        if (!$teacher) {
            return;
        }

        $scheduleService = app(ScheduleService::class);
        $schedule = $scheduleService->getTeacherSchedule($teacher);
        
        $this->schedule = $schedule->toArray();
        $this->groupedSchedule = $scheduleService->groupScheduleByDay($schedule)->toArray();
    }

    public function getTodayClasses(): array
    {
        return collect($this->schedule)
            ->filter(fn ($item) => ($item['day'] ?? '') === $this->currentDay)
            ->sortBy('start_time')
            ->values()
            ->toArray();
    }

    public function getUpcomingClass(): ?array
    {
        $now = now()->format('H:i:s');
        
        return collect($this->getTodayClasses())
            ->filter(fn ($item) => ($item['start_time'] ?? '00:00:00') > $now)
            ->first();
    }

    protected function getViewData(): array
    {
        return [
            'schedule' => $this->schedule,
            'groupedSchedule' => $this->groupedSchedule,
            'todayClasses' => $this->getTodayClasses(),
            'upcomingClass' => $this->getUpcomingClass(),
            'currentDay' => $this->currentDay,
            'hasSchedule' => !empty($this->schedule),
        ];
    }
}
