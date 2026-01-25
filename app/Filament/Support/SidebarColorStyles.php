<?php

namespace App\Filament\Support;

class SidebarColorStyles
{
    public static function styles(): string
    {
        return <<<'CSS'
            <style>
                /* Campus Management (Red Palette) - Alphabetical: Buildings, Campuses, Facilities, Rooms */
                a[href*="/buildings"] .fi-sidebar-item-icon { color: rgb(252 165 165) !important; } /* Red-300 */
                a[href*="/campuses"] .fi-sidebar-item-icon { color: rgb(248 113 113) !important; } /* Red-400 */
                a[href*="/facilities"] .fi-sidebar-item-icon { color: rgb(239 68 68) !important; } /* Red-500 */
                a[href*="/rooms"] .fi-sidebar-item-icon { color: rgb(220 38 38) !important; } /* Red-600 */

                /* Academic Structure (Blue Palette) - Alphabetical: Academic Years, Curricula, Departments, Faculties, Terms */
                a[href*="/academic-years"] .fi-sidebar-item-icon { color: rgb(147 197 253) !important; } /* Blue-300 */
                a[href*="/curricula"] .fi-sidebar-item-icon, a[href*="/curriculums"] .fi-sidebar-item-icon { color: rgb(96 165 250) !important; } /* Blue-400 */
                a[href*="/departments"] .fi-sidebar-item-icon { color: rgb(59 130 246) !important; } /* Blue-500 */
                a[href*="/faculties"] .fi-sidebar-item-icon { color: rgb(37 99 235) !important; } /* Blue-600 */
                a[href*="/terms"] .fi-sidebar-item-icon { color: rgb(29 78 216) !important; } /* Blue-700 */

                /* Course Management (Violet Palette) - Alphabetical: Course Offerings, Session Types, Subjects */
                a[href*="/course-offerings"] .fi-sidebar-item-icon { color: rgb(167 139 250) !important; } /* Violet-400 */
                a[href*="/session-types"] .fi-sidebar-item-icon { color: rgb(139 92 246) !important; } /* Violet-500 */
                a[href*="/subjects"] .fi-sidebar-item-icon { color: rgb(124 58 237) !important; } /* Violet-600 */

                /* Academic Management (Emerald Palette) - Alphabetical: Assessments, Parents, Students, Teachers, Tutorial Analytics */
                a[href*="/assessments"] .fi-sidebar-item-icon { color: rgb(110 231 183) !important; } /* Emerald-300 */
                a[href*="/guardians"] .fi-sidebar-item-icon { color: rgb(52 211 153) !important; } /* Emerald-400 */
                a[href*="/students"] .fi-sidebar-item-icon { color: rgb(16 185 129) !important; } /* Emerald-500 */
                a[href*="/teachers"] .fi-sidebar-item-icon { color: rgb(5 150 105) !important; } /* Emerald-600 */
                a[href*="/student-tutorials"] .fi-sidebar-item-icon { color: rgb(4 120 87) !important; } /* Emerald-700 */

                /* User Management (Indigo Palette) - Alphabetical: Permissions, Roles, Users */
                a[href*="/permissions"] .fi-sidebar-item-icon { color: rgb(129 140 248) !important; } /* Indigo-400 */
                a[href*="/roles"] .fi-sidebar-item-icon { color: rgb(99 102 241) !important; } /* Indigo-500 */
                a[href*="/users"] .fi-sidebar-item-icon { color: rgb(79 70 229) !important; } /* Indigo-600 */

                /* Service Management (Amber Palette) - Alphabetical: Appointments, Service Requests */
                a[href*="/appointments"] .fi-sidebar-item-icon { color: rgb(251 191 36) !important; } /* Amber-400 */
                a[href*="/service-requests"] .fi-sidebar-item-icon { color: rgb(245 158 11) !important; } /* Amber-500 */

                /* Disciplinary & Evaluation (Rose Palette) */
                a[href*="/grievances"] .fi-sidebar-item-icon { color: rgb(244 63 94) !important; } /* Rose-500 */

                /* Admissions (Orange Palette) */
                a[href*="/applicants"] .fi-sidebar-item-icon { color: rgb(249 115 22) !important; } /* Orange-500 */

                /* Communications (Cyan Palette) - Alphabetical: Announcements, Notifications */
                a[href*="/announcements"] .fi-sidebar-item-icon { color: rgb(34 211 238) !important; } /* Cyan-400 */
                a[href*="/notifications"] .fi-sidebar-item-icon { color: rgb(6 182 212) !important; } /* Cyan-500 */

                /* Engagement (Teal Palette) - Alphabetical: Survey Logs, Surveys */
                a[href*="/survey-logs"] .fi-sidebar-item-icon { color: rgb(45 212 191) !important; } /* Teal-400 */
                a[href*="/surveys"] .fi-sidebar-item-icon { color: rgb(20 184 166) !important; } /* Teal-500 */

                /* Marketing (Lime Palette) - Alphabetical: Offer Logs, Offers */
                a[href*="/offer-logs"] .fi-sidebar-item-icon { color: rgb(163 230 53) !important; } /* Lime-400 */
                a[href*="/offers"] .fi-sidebar-item-icon { color: rgb(132 204 22) !important; } /* Lime-500 */

                /* System (Slate Palette) - Alphabetical: App Versions, Lookup Items, System Settings, User Agreements */
                a[href*="/app-versions"] .fi-sidebar-item-icon { color: rgb(203 213 225) !important; } /* Slate-300 */
                a[href*="/lookup-items"] .fi-sidebar-item-icon { color: rgb(148 163 184) !important; } /* Slate-400 */
                a[href*="/system-settings"] .fi-sidebar-item-icon { color: rgb(100 116 139) !important; } /* Slate-500 */
                a[href*="/user-agreements"] .fi-sidebar-item-icon { color: rgb(71 85 105) !important; } /* Slate-600 */
            </style>
        CSS;
    }
}
