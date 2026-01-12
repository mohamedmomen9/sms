<?php

namespace App\Filament\TeacherPanel\Pages;

use App\Filament\TeacherPanel\Widgets\TeacherScheduleWidget;
use Filament\Pages\Dashboard;

class TeacherDashboard extends Dashboard
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    
    protected static string $routePath = '/';
    
    protected static ?int $navigationSort = -2;

    public function getTitle(): string
    {
        return __('Teacher Dashboard');
    }

    public static function getNavigationLabel(): string
    {
        return __('Dashboard');
    }

    public function getHeading(): string
    {
        $teacher = auth('teacher')->user();
        $name = $teacher?->name ?? __('Teacher');
        
        return __('Welcome, :name', ['name' => $name]);
    }

    public function getSubheading(): ?string
    {
        return __('Manage your subjects and classes from here');
    }

    public function getWidgets(): array
    {
        return [
            TeacherScheduleWidget::class,
        ];
    }
}
