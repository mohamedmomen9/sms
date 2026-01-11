<?php

namespace App\Helpers;

use App\Models\Setting;
use Modules\Academic\Models\AcademicYear;
use Modules\Academic\Models\Term;

class DbHelper
{
    public static function getCurrentYear()
    {
        // Try to get from Settings first
        $year = Setting::get('current_year');
        
        if ($year) {
            return $year;
        }

        // Fallback to active year in database
        $activeYear = AcademicYear::where('is_active', true)->value('name');
        return $activeYear ?? date('Y');
    }

    public static function getCurrentTerm()
    {
        // Try to get from Settings first
        $term = Setting::get('current_term');

        if ($term) {
            return $term;
        }

        // Fallback to active term in database
        $activeTerm = Term::where('is_active', true)->value('code');
        return $activeTerm ?? 'FALL';
    }
}
