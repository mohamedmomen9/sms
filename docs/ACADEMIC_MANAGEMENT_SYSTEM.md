# Academic Management System - Implementation Summary

## Overview
This Laravel + Filament v4 system implements role-based academic management with hierarchical data visibility and filtering.

## Core Entities & Relationships

### Entity Hierarchy
```
University
└── Faculty (belongsTo University)
    ├── Department (belongsTo Faculty)
    │   └── Subject (belongsTo Department, optionally belongsTo Faculty)
    └── Subject (can belongsTo Faculty directly)
```

### User Scope Levels
Users can be assigned to one of three scope levels:
1. **University Level** - Access to all faculties, departments, and subjects within a university
2. **Faculty Level** - Access to all departments and subjects within a faculty
3. **Subject Level** - Access only to a specific subject

## Files Created/Modified

### Migrations
- `database/migrations/2025_12_28_101700_add_scope_fields_to_users_table.php` - Adds `university_id`, `subject_id`, and `is_admin` to users table
- `database/migrations/2025_12_28_101800_add_faculty_id_to_subjects_table.php` - Adds `faculty_id` to subjects table for direct faculty relationship

### Traits
- `app/Traits/HasAcademicScope.php` - Core trait providing:
  - `isAdmin()`, `isScopedToUniversity()`, `isScopedToFaculty()`, `isScopedToSubject()` - Role checking methods
  - `getScopedUniversityId()`, `getScopedFacultyId()`, `getAccessibleFacultyIds()`, `getAccessibleDepartmentIds()` - Scope resolution
  - `scopeUniversityQuery()`, `scopeFacultyQuery()`, `scopeDepartmentQuery()`, `scopeSubjectQuery()` - Query builders
  - `canAccessUniversity()`, `canAccessFaculty()`, `canAccessDepartment()`, `canAccessSubject()` - Access checkers

### Models Updated
- `app/Models/User.php` - Added HasAcademicScope trait, university/subject relationships, scope attributes
- `app/Models/University.php` - Added users, departments relationships
- `app/Models/Faculty.php` - Added subjects, users relationships
- `app/Models/Department.php` - Added university accessor
- `app/Models/Subject.php` - Added faculty relationship, effective faculty/university accessors

### Policies Updated
- `app/Policies/UniversityPolicy.php` - Admin-only create/update/delete, scoped view
- `app/Policies/FacultyPolicy.php` - Scope-based CRUD permissions
- `app/Policies/DepartmentPolicy.php` - Scope-based CRUD permissions
- `app/Policies/SubjectPolicy.php` - Scope-based CRUD permissions (subject-scoped users cannot create/delete)
- `app/Policies/UserPolicy.php` - Hierarchical user management permissions

### Filament Resources Updated
- `app/Filament/Resources/UniversityResource/`
  - Read-only for non-admins
  - Query scoping via `getEloquentQuery()` and `modifyQueryUsing()`

- `app/Filament/Resources/FacultyResource/`
  - University filter based on user scope
  - Query scoping enforced

- `app/Filament/Resources/DepartmentResource/`
  - **Dependent selects**: University → Faculty (reactive)
  - Auto-assignment for scoped users
  - Hidden/disabled fields based on scope

- `app/Filament/Resources/SubjectResource/`
  - **3-level cascading selects**: University → Faculty → Department
  - Department is optional (subjects can belong directly to faculty)
  - Auto-assignment for scoped users

- `app/Filament/Resources/UserResource/`
  - Admin toggle with scope clearing
  - Scope level selection with cascading academic assignment
  - Query scoping based on viewer's scope

### Table Schemas Updated
- All table schemas now include:
  - Role-based filters (university, faculty filters only for users with access)
  - Count columns for related entities
  - Proper sorting and searching

### Seeder
- `database/seeders/AcademicStructureSeeder.php` - Creates:
  - All necessary permissions
  - Admin and User roles
  - 2 universities, 3 faculties, 3 departments, 4 subjects
  - 4 test users at different scope levels

## Test Users

| Email | Role | Scope |
|-------|------|-------|
| admin@example.com | Admin | Global Access |
| university.admin@example.com | User | Cairo University |
| faculty.admin@example.com | User | Faculty of Engineering |
| subject.user@example.com | User | Electric Circuits Subject |

Password for all users: `password`

## Key Features

### Admin Users
- Full access to all universities, faculties, departments, subjects
- Dynamic dependent selects in forms:
  - Select University → Faculty options update
  - Select Faculty → Department options update
- Can create/edit/delete all entities
- Can assign any scope level to users

### Standard Users

#### University-Scoped
- University field auto-assigned and hidden
- Faculty list filtered to their university
- Can create/edit departments and subjects within their university

#### Faculty-Scoped
- University and Faculty fields auto-assigned and hidden
- Department and Subject lists filtered to their faculty
- Can create/edit departments and subjects within their faculty

#### Subject-Scoped
- All hierarchy fields auto-assigned and hidden
- Can only view and edit their assigned subject
- Cannot create new subjects or departments

## Security Enforcement

1. **Policy-based access control** - Laravel Policies check user scope for every CRUD operation
2. **Query-level scoping** - `getEloquentQuery()` and `modifyQueryUsing()` ensure database queries are filtered
3. **Form-level filtering** - Select options are filtered server-side based on user scope
4. **Relationship constraints** - All relationship queries use proper scoping

## Running the System

```bash
# Run migrations
php artisan migrate

# Seed test data
php artisan db:seed --class=AcademicStructureSeeder

# Clear caches
php artisan optimize:clear
php artisan filament:optimize-clear

# Start the development server
php artisan serve
```

Access the admin panel at `/admin` and login with one of the test users to experience different scope levels.
