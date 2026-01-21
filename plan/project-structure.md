# Project Structure Documentation

> **Purpose**: Single source of truth for the entire SMS (Student Management System) project.  
> **Last Updated**: 2026-01-21

---

## 1. Project Overview

### Purpose
A comprehensive Student Management System (SMS) for educational institutions that handles:
- Student enrollment and academic records
- Course offerings and scheduling
- Faculty and teacher management
- Appointment booking and service requests
- Course evaluations
- Field training management
- Payment processing
- Disciplinary grievances

### Goals
- Replace legacy PHP/MySQL system with modern Laravel modular architecture
- Provide REST APIs for mobile and web clients
- Offer Filament-based admin dashboard for staff
- Maintain academic data integrity with proper relationships

---

## 2. Tech Stack

### Backend
| Technology | Version | Purpose |
|------------|---------|---------|
| PHP | 8.2+ | Server-side language |
| Laravel | 11.x | PHP framework |
| Laravel Modules | nwidart/laravel-modules | Modular architecture |
| Filament | 3.x | Admin panel |
| Sanctum | Built-in | API authentication |
| Spatie Permission | 6.x | Role-based access control |

### Frontend
| Technology | Purpose |
|------------|---------|
| Blade | Server-side templating |
| Vite | Asset bundling |
| Tailwind CSS | Styling (via Filament) |
| Alpine.js | Interactivity (via Filament) |

### Database
| Technology | Purpose |
|------------|---------|
| MySQL | Primary database |
| Eloquent ORM | Database abstraction |

### Tools & Services
| Tool | Purpose |
|------|---------|
| Composer | PHP dependency management |
| NPM | JavaScript dependency management |
| Artisan | Laravel CLI |
| PHPUnit | Testing framework |

---

## 3. Folder & File Structure

```
/var/www/html/
├── app/                          # Core Laravel application
│   ├── Console/                  # Artisan commands
│   ├── Filament/                 # Filament admin panel
│   │   ├── Pages/                # Custom admin pages
│   │   ├── Resources/            # CRUD resources
│   │   └── Widgets/              # Dashboard widgets
│   ├── Helpers/                  # Helper functions
│   ├── Http/                     # Controllers, middleware
│   │   ├── Controllers/          # API controllers
│   │   └── Middleware/           # Request middleware
│   ├── Models/                   # Core Eloquent models
│   ├── Policies/                 # Authorization policies
│   ├── Providers/                # Service providers
│   ├── Services/                 # Business logic services
│   └── Traits/                   # Reusable traits
│
├── Modules/                      # Feature modules (main code)
│   ├── Academic/                 # Terms, academic years
│   ├── Auth/                     # Authentication
│   ├── Campus/                   # Campus, buildings, rooms
│   ├── Curriculum/               # Curriculum management
│   ├── Department/               # Departments
│   ├── Disciplinary/             # Grievances, violations
│   ├── Evaluation/               # Course evaluations
│   ├── Faculty/                  # Faculties/schools
│   ├── Payment/                  # Payment processing
│   ├── Services/                 # Appointments, service requests
│   ├── Students/                 # Student management
│   ├── Subject/                  # Courses, offerings
│   ├── Teachers/                 # Teacher management
│   ├── Training/                 # Field training
│   └── Users/                    # User management
│
├── config/                       # Configuration files
│   ├── app.php                   # Application settings
│   ├── auth.php                  # Authentication guards
│   ├── database.php              # Database connections
│   ├── filesystems.php           # File storage
│   ├── laravel-modular.php       # Module configuration
│   └── permission.php            # Spatie permissions
│
├── database/                     # Database files
│   ├── factories/                # Model factories
│   ├── migrations/               # Schema migrations
│   └── seeders/                  # Data seeders
│
├── resources/                    # Frontend assets
│   ├── css/                      # Stylesheets
│   ├── js/                       # JavaScript
│   └── views/                    # Blade templates
│
├── routes/                       # Route definitions
│   ├── api.php                   # API routes
│   └── web.php                   # Web routes
│
├── plan/                         # Project documentation
│   └── CicMysqlQueriesPlan/      # Migration plan
│       └── done/                 # Progress tracking
│
├── storage/                      # Generated files, logs
├── tests/                        # Test files
├── vendor/                       # Composer dependencies
└── public/                       # Web root
```

### Module Structure (Each Module)
```
Modules/{ModuleName}/
├── app/
│   ├── Filament/
│   │   └── Resources/            # Module's Filament resources
│   │       └── {Resource}/
│   │           ├── Pages/        # List, Create, Edit pages
│   │           ├── Schemas/      # Form schemas
│   │           ├── Tables/       # Table definitions
│   │           └── RelationManagers/
│   ├── Http/
│   │   └── Controllers/          # API controllers
│   ├── Models/                   # Eloquent models
│   ├── Policies/                 # Authorization
│   ├── Providers/                # Service providers
│   └── Services/                 # Business logic
├── config/                       # Module config
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
├── routes/
│   ├── api.php                   # Module API routes
│   └── web.php                   # Module web routes
└── resources/
    └── views/
```

---

## 4. Architecture & Data Flow

### Request Flow
```
Client Request
    ↓
routes/api.php or Modules/{Module}/routes/api.php
    ↓
Middleware (auth:sanctum, role checks)
    ↓
Controller (app/Http/Controllers or Modules/{Module}/app/Http/Controllers)
    ↓
Service Class (business logic)
    ↓
Model (Eloquent ORM)
    ↓
Database
    ↓
JSON Response
```

### Filament Admin Flow
```
Browser Request → /admin/*
    ↓
Filament Panel Provider
    ↓
Resource (app/Filament/Resources or Modules/{Module}/app/Filament/Resources)
    ↓
Form Schema / Table Definition
    ↓
Model Operations
    ↓
Rendered View
```

### Key Patterns
- **Repository Pattern**: Not used; Eloquent models are accessed directly
- **Service Layer**: Business logic in `Services/` folders
- **Modular Architecture**: Features separated into modules
- **Filament v4 Structure**: Separate Form/Table schema classes

---

## 5. Core Features & Modules

| Feature | Module | Key Files |
|---------|--------|-----------|
| **Student Management** | `Modules/Students/` | `Models/Student.php`, `Filament/Resources/StudentResource.php` |
| **Teacher Management** | `Modules/Teachers/` | `Models/Teacher.php` |
| **Course Offerings** | `Modules/Subject/` | `Models/CourseOffering.php`, `Models/Subject.php` |
| **Academic Terms** | `Modules/Academic/` | `Models/Term.php`, `Models/AcademicYear.php` |
| **Campus/Facilities** | `Modules/Campus/` | `Models/Campus.php`, `Models/Building.php`, `Models/Room.php` |
| **Curriculum** | `Modules/Curriculum/` | `Models/Curriculum.php` |
| **Appointments** | `Modules/Services/` | `Models/Appointment.php`, `Models/AppointmentDepartment.php` |
| **Service Requests** | `Modules/Services/` | `Models/ServiceRequest.php`, `Models/ServiceType.php` |
| **Evaluations** | `Modules/Evaluation/` | `Models/Evaluation.php`, `Models/Assessment.php` |
| **Field Training** | `Modules/Training/` | `Models/TrainingOpportunity.php`, `Models/FieldTraining.php` |
| **Payments** | `Modules/Payment/` | `Models/PaymentRegistration.php` |
| **Grievances** | `Modules/Disciplinary/` | `Models/Grievance.php` |

---

## 6. Configuration & Environment

### Environment Variables (`.env`)
| Variable | Purpose |
|----------|---------|
| `APP_NAME` | Application name |
| `APP_ENV` | Environment (local/production) |
| `APP_KEY` | Encryption key |
| `APP_DEBUG` | Debug mode |
| `APP_URL` | Base URL |
| `DB_CONNECTION` | Database driver |
| `DB_HOST` | Database host |
| `DB_DATABASE` | Database name |
| `DB_USERNAME` | Database user |
| `DB_PASSWORD` | Database password |

### Key Config Files
| File | Purpose |
|------|---------|
| `config/app.php` | Application settings, timezone, locale |
| `config/auth.php` | Authentication guards and providers |
| `config/database.php` | Database connections |
| `config/filesystems.php` | File storage disks |
| `config/laravel-modular.php` | Module autoloading settings |
| `config/permission.php` | Spatie permission settings |

---

## 7. Common Change Scenarios

### Updating UI/Design

| Change | Files to Edit |
|--------|---------------|
| Filament admin theme | `app/Providers/Filament/AdminPanelProvider.php` |
| Table columns | `Modules/{Module}/app/Filament/Resources/{Resource}/Tables/{Resource}Table.php` |
| Form fields | `Modules/{Module}/app/Filament/Resources/{Resource}/Schemas/{Resource}Form.php` |
| Navigation icons | `Modules/{Module}/app/Filament/Resources/{Resource}Resource.php` → `$navigationIcon` |
| Navigation groups | `Modules/{Module}/app/Filament/Resources/{Resource}Resource.php` → `$navigationGroup` |

### Changing Business Logic

| Change | Files to Edit |
|--------|---------------|
| Add validation rules | `Modules/{Module}/app/Http/Controllers/*.php` or Form schema |
| Add business method | `Modules/{Module}/app/Services/{Service}.php` |
| Modify scopes | `Modules/{Module}/app/Models/{Model}.php` |
| Add computed property | `Modules/{Module}/app/Models/{Model}.php` → accessors |

### Adding a New Feature

| Step | Files to Create/Edit |
|------|---------------------|
| 1. Create module | `php artisan module:make {ModuleName}` |
| 2. Add migration | `Modules/{Module}/database/migrations/` |
| 3. Create model | `Modules/{Module}/app/Models/{Model}.php` |
| 4. Add service | `Modules/{Module}/app/Services/{Service}.php` |
| 5. Add controller | `Modules/{Module}/app/Http/Controllers/{Controller}.php` |
| 6. Add routes | `Modules/{Module}/routes/api.php` |
| 7. Add Filament resource | `Modules/{Module}/app/Filament/Resources/{Resource}Resource.php` |

### Modifying API Endpoints

| Change | Files to Edit |
|--------|---------------|
| Add route | `Modules/{Module}/routes/api.php` |
| Change controller | `Modules/{Module}/app/Http/Controllers/{Controller}.php` |
| Add middleware | `Modules/{Module}/routes/api.php` → Route group `middleware()` |
| Change response format | Controller method return statement |

### Updating Database Schema

| Change | Files to Create/Edit |
|--------|---------------------|
| Add column | New migration in `database/migrations/` |
| Add table | New migration in `database/migrations/` |
| Modify model | `Modules/{Module}/app/Models/{Model}.php` → `$fillable`, `$casts` |
| Add relationship | Model → add relationship method |

### Changing Authentication/Authorization

| Change | Files to Edit |
|--------|---------------|
| Auth guards | `config/auth.php` |
| API tokens | `Modules/Auth/app/Http/Controllers/` |
| Permissions | Database seeders + Spatie permission |
| Policies | `app/Policies/` or `Modules/{Module}/app/Policies/` |
| Filament access | Resource → `canViewAny()`, `canCreate()`, etc. |

### Adjusting Deployment/Environment

| Change | Files to Edit |
|--------|---------------|
| Environment vars | `.env` |
| App settings | `config/app.php` |
| Database connection | `config/database.php` + `.env` |
| File storage | `config/filesystems.php` |
| Cache driver | `config/cache.php` + `.env` |

---

## 7.5 Testing Strategy (New)

### Testing Infrastructure
- **Framework**: PHPUnit 10
- **Environment**: SQLite (Memory) for speed, MySQL for local dev
- **Traits**: `CreatesTestUser`, `InteractsWithJwt`

### Test Types
| Type | Location | Purpose |
|------|----------|---------|
| **Unit** | `Modules/{Module}/tests/Unit/` | Test individual service methods and model logic |
| **Feature** | `Modules/{Module}/tests/Feature/` | Test API endpoints (HTTP status, JSON structure) |
| **Filament** | `Modules/{Module}/tests/Feature/Filament/` | Test Admin Panel resources (Livewire) |

### Key Test Commands
```bash
# Run all tests
php artisan test

# Run specific module tests
php artisan test Modules/Services/

# Run specific test suite
php artisan test --testsuite=Feature
```

---

## 8. Conventions & Guidelines

### Naming Conventions

| Item | Convention | Example |
|------|------------|---------|
| Model | PascalCase, singular | `Student`, `CourseOffering` |
| Controller | PascalCase + Controller | `StudentController` |
| Migration | snake_case with date prefix | `2026_01_20_000001_create_students_table.php` |
| Table | snake_case, plural | `students`, `course_offerings` |
| Column | snake_case | `student_id`, `created_at` |
| Route | kebab-case | `/api/v1/course-offerings` |
| Filament Resource | PascalCase + Resource | `StudentResource` |

### Code Organization Rules

1. **Never use fully qualified class names inline**
   ```php
   // ❌ Bad
   \Modules\Students\Models\Student::all();
   
   // ✅ Good
   use Modules\Students\Models\Student;
   Student::all();
   ```

2. **Always add `use` statements at file top**

3. **Model relationships use imported classes**
   ```php
   public function student(): BelongsTo
   {
       return $this->belongsTo(Student::class, 'student_id', 'student_id');
   }
   ```

4. **Filament v4 structure**
   - Form schemas in `Schemas/` folder
   - Table definitions in `Tables/` folder
   - Relation managers in `RelationManagers/` folder

5. **Student identifier**
   - Use `student_id` (not `cicid`)
   - Type: VARCHAR(50)
   - FK references: `->references('student_id')->on('students')`

### Comments
- No AI-generated comments
- Keep comments short, practical, human-readable
- Only comment non-obvious logic

---

## 9. Future Notes

### Known Limitations
- Legacy `cicid` field still exists in some documentation references
- Some tables created but services/controllers not yet implemented
- No automated testing for new modules yet

### Completed Milestones (Jan 2026)
- [x] Stage 4: Service classes for business logic (Services, Appointments, Payments)
- [x] Stage 5: API controllers and routes (v1 prefix, standard responses)
- [x] Stage 6: Filament dashboard resources (All modules w/ Sidebar Colors)
- [x] Stage 7: Data migration & Seeding (DemoDataSeeder with comprehensive output)
- [x] Stage 8: Testing and verification (Unit, Feature, Filament tests)

### Upcoming Goals
- [ ] Production Deployment
- [ ] PWA / Mobile App Integration
- [ ] Advanced Reporting / Analytics

### Extension Points
- Add new modules via `php artisan module:make {Name}`
- Extend Filament resources with custom actions
- Add API versioning via route prefixes (`/api/v1/`, `/api/v2/`)

---

## Quick Reference

### Artisan Commands
```bash
# Run migrations
php artisan migrate

# Create module
php artisan module:make {ModuleName}

# Clear cache
php artisan optimize:clear

# List routes
php artisan route:list --path=api/v1

# Create Filament resource
php artisan make:filament-resource {Name} --module={Module}
```

### Common File Paths
| Purpose | Path |
|---------|------|
| Student model | `Modules/Students/app/Models/Student.php` |
| Appointment model | `Modules/Services/app/Models/Appointment.php` |
| Main migrations | `database/migrations/` |
| API routes (global) | `routes/api.php` |
| Module API routes | `Modules/{Module}/routes/api.php` |
| Filament resources | `Modules/{Module}/app/Filament/Resources/` |
