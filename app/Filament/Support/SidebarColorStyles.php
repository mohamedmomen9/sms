<?php

namespace App\Filament\Support;

class SidebarColorStyles
{
    public static function styles(): string
    {
        return <<<'CSS'
            <style>
                /* Campus Management */
                a[href*="/campuses"] .fi-sidebar-item-icon { color: rgb(239 68 68) !important; } /* Red-500 */
                a[href*="/buildings"] .fi-sidebar-item-icon { color: rgb(249 115 22) !important; } /* Orange-500 */
                a[href*="/rooms"] .fi-sidebar-item-icon { color: rgb(245 158 11) !important; } /* Amber-500 */
                a[href*="/facilities"] .fi-sidebar-item-icon { color: rgb(132 204 22) !important; } /* Lime-500 */

                /* Academic Structure */
                a[href*="/academic-years"] .fi-sidebar-item-icon { color: rgb(16 185 129) !important; } /* Emerald-500 */
                a[href*="/terms"] .fi-sidebar-item-icon { color: rgb(20 184 166) !important; } /* Teal-500 */
                a[href*="/faculties"] .fi-sidebar-item-icon { color: rgb(6 182 212) !important; } /* Cyan-500 */
                a[href*="/departments"] .fi-sidebar-item-icon { color: rgb(14 165 233) !important; } /* Sky-500 */
                a[href*="/curricula"] .fi-sidebar-item-icon, a[href*="/curriculums"] .fi-sidebar-item-icon { color: rgb(59 130 246) !important; } /* Blue-500 */

                /* Course Management */
                a[href*="/course-offerings"] .fi-sidebar-item-icon { color: rgb(99 102 241) !important; } /* Indigo-500 */
                a[href*="/subjects"] .fi-sidebar-item-icon { color: rgb(139 92 246) !important; } /* Violet-500 */
                a[href*="/session-types"] .fi-sidebar-item-icon { color: rgb(168 85 247) !important; } /* Purple-500 */

                /* User Management */
                a[href*="/students"] .fi-sidebar-item-icon { color: rgb(217 70 239) !important; } /* Fuchsia-500 */
                a[href*="/teachers"] .fi-sidebar-item-icon { color: rgb(244 63 94) !important; } /* Rose-500 */
                a[href*="/users"] .fi-sidebar-item-icon { color: rgb(100 116 139) !important; } /* Slate-500 */
                a[href*="/roles"] .fi-sidebar-item-icon { color: rgb(113 113 122) !important; } /* Zinc-500 */
                a[href*="/permissions"] .fi-sidebar-item-icon { color: rgb(115 115 115) !important; } /* Neutral-500 */
            </style>
        CSS;
    }
}
