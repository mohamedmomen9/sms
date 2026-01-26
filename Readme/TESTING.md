# Test Suite Overview

This document describes the purpose of each test file and directory in the project,
based on the current passing test suite.

---

### To Run All Tests
Executes the full test suite, including both Application and Module tests.

- **Command**:
  ```bash
  php artisan test
  ```
  *Alternative*: `./vendor/bin/phpunit`

- **Purpose**: Validates the entire application's stability by running every available test case.
- **Scope**: Unit, Feature, Integration (All).
- **Location**:
  - `tests/` (Core application tests)
  - `Modules/*/tests/` (Module-specific tests)
---


## Global Tests

### tests/Unit/Policies/BasePolicyTest.php
Command: `php artisan test tests/Unit/Policies/BasePolicyTest.php`
- Verifies authorization logic for:
  - Viewing resources
  - Creating resources
  - Updating resources
  - Deleting resources
- Covers super admin, admin, and permission-based access control.

---

## Academic Module

### Modules/Academic/tests/Unit/Services/ScheduleServiceTest.php
Command: `php artisan test Modules/Academic/tests/Unit/Services/ScheduleServiceTest.php`
- Tests student schedule retrieval
- Ensures empty schedules when not enrolled
- Validates schedule grouping by day
- Confirms teacher schedule assignments

### Modules/Academic/tests/Feature/ScheduleApiTest.php
Command: `php artisan test Modules/Academic/tests/Feature/ScheduleApiTest.php`
- API-level tests for schedule endpoints
- Student schedule access
- Teacher schedule access
- “Today” filter
- Schedule grouping query parameters

---

## Admissions Module

### Modules/Admissions/tests/Unit/Models/ApplicantTest.php
Command: `php artisan test Modules/Admissions/tests/Unit/Models/ApplicantTest.php`
- Applicant model creation
- Status transitions handled via service logic

---

## Communications Module

### Models

#### Modules/Communications/tests/Unit/Models/AnnouncementTest.php
Command: `php artisan test Modules/Communications/tests/Unit/Models/AnnouncementTest.php`
- Announcement creation
- Campus relationships
- Global vs campus-specific announcements
- Query scopes:
  - Campus
  - Type(s)
  - Active
  - Search (title & details)
- Valid announcement types

#### Modules/Communications/tests/Unit/Models/NotificationTest.php
Command: `php artisan test Modules/Communications/tests/Unit/Models/NotificationTest.php`
- Notification creation
- Relationship with notification logs
- Extra data casting
- Search scopes (title, body)
- Read/unread count accessors

#### Modules/Communications/tests/Unit/Models/NotificationLogTest.php
Command: `php artisan test Modules/Communications/tests/Unit/Models/NotificationLogTest.php`
- Notification log creation
- Relationships to notifications
- Read/unread scopes
- Notifiable filtering
- Mark as read / unread behavior

### Services

#### Modules/Communications/tests/Unit/Services/AnnouncementServiceTest.php
Command: `php artisan test Modules/Communications/tests/Unit/Services/AnnouncementServiceTest.php`
- Listing announcements
- Filtering by campus, type, active status
- Searching announcements
- CRUD operations
- Event-type filtering

#### Modules/Communications/tests/Unit/Services/NotificationServiceTest.php
Command: `php artisan test Modules/Communications/tests/Unit/Services/NotificationServiceTest.php`
- CRUD operations for notifications
- Sending notifications to users
- Bulk notification sending
- Marking notifications read/unread
- Fetching unread notifications and counts

---

## Engagement Module

### Modules/Engagement/tests/Unit/Models/SurveyTest.php
Command: `php artisan test Modules/Engagement/tests/Unit/Models/SurveyTest.php`
- Active survey filtering
- User-specific survey visibility

### Modules/Engagement/tests/Unit/Services/SurveyServiceTest.php
Command: `php artisan test Modules/Engagement/tests/Unit/Services/SurveyServiceTest.php`
- Logging survey participation
- Preventing duplicate participation
- Participation checks
- Aggregated participation statistics

---

## Family Module

### Modules/Family/tests/Unit/Models/GuardianTest.php
Command: `php artisan test Modules/Family/tests/Unit/Models/GuardianTest.php`
- Guardian creation linked to students
- OTP generation logic

---

## Marketing Module

### Modules/Marketing/tests/Unit/Models/OfferTest.php
Command: `php artisan test Modules/Marketing/tests/Unit/Models/OfferTest.php`
- Active offer filtering
- Campus-specific offers
- Favorite status checks

### Modules/Marketing/tests/Unit/Services/OfferServiceTest.php
Command: `php artisan test Modules/Marketing/tests/Unit/Services/OfferServiceTest.php`
- Offer creation
- Favorite toggling
- Analytics and engagement metrics

---

## Payment Module

### Modules/Payment/tests/Unit/Services/PaymentServiceTest.php
Command: `php artisan test Modules/Payment/tests/Unit/Services/PaymentServiceTest.php`
- Registration payment creation
- Callback handling (success & failure)
- Payment status updates
- Transaction ID lookups

---

## Services Module

### Unit Tests

#### Modules/Services/tests/Unit/Services/AppointmentServiceTest.php
Command: `php artisan test Modules/Services/tests/Unit/Services/AppointmentServiceTest.php`
- Available slot calculation
- Capacity enforcement
- Booking appointments
- Canceling appointments

#### Modules/Services/tests/Unit/Services/ServiceRequestServiceTest.php
Command: `php artisan test Modules/Services/tests/Unit/Services/ServiceRequestServiceTest.php`
- Submitting service requests
- Pricing validation
- Listing active services
- Status transitions (e.g. delivered)

### Feature Tests

#### Modules/Services/tests/Feature/AppointmentApiTest.php
Command: `php artisan test Modules/Services/tests/Feature/AppointmentApiTest.php`
- Department listing
- Student appointment booking via API

#### Modules/Services/tests/Feature/Filament/AppointmentResourceTest.php
Command: `php artisan test Modules/Services/tests/Feature/Filament/AppointmentResourceTest.php`
- Admin panel rendering
- Listing appointment records
- Rendering create appointment page

#### Modules/Services/tests/Feature/ServiceRequestApiTest.php
Command: `php artisan test Modules/Services/tests/Feature/ServiceRequestApiTest.php`
- Listing available services
- Submitting service requests via API

---

## Students Module

### Modules/Students/tests/Unit/StudentTutorialTest.php
Command: `php artisan test Modules/Students/tests/Unit/StudentTutorialTest.php`
- Tracking tutorial completion status for students

---

## System Module

### Modules/System/tests/Unit/LookupItemTest.php
Command: `php artisan test Modules/System/tests/Unit/LookupItemTest.php`
- Retrieving lookup items by type
- Ensuring correct ordering

### Modules/System/tests/Unit/SystemSettingTest.php
Command: `php artisan test Modules/System/tests/Unit/SystemSettingTest.php`
- Setting and retrieving system settings
- Typed setting support

### Modules/System/tests/Unit/UserAgreementTest.php
Command: `php artisan test Modules/System/tests/Unit/UserAgreementTest.php`
- Accepting user agreements

---

## Authentication Module

### Modules/Auth/tests/Feature/LoginTest.php
Command: `php artisan test Modules/Auth/tests/Feature/LoginTest.php`
- Login validation rules
- Role-based login endpoints
- Student, teacher, and staff authentication
- Invalid role handling

### Modules/Auth/tests/Feature/LogoutTest.php
Command: `php artisan test Modules/Auth/tests/Feature/LogoutTest.php`
- Logout token validation
- Token format handling

### Modules/Auth/tests/Feature/TokenRefreshTest.php
Command: `php artisan test Modules/Auth/tests/Feature/TokenRefreshTest.php`
- Token refresh validation
- Invalid token rejection

---

## Core Feature Tests

### tests/Feature/ExampleTest.php
Command: `php artisan test tests/Feature/ExampleTest.php`
- Application bootstrapping sanity check

### tests/Feature/CampusResourceTest.php
Command: `php artisan test tests/Feature/CampusResourceTest.php`
- Super admin campus listing

### tests/Feature/CurriculumResourceTest.php
Command: `php artisan test tests/Feature/CurriculumResourceTest.php`
- Super admin curriculum listing

### tests/Feature/FacultyResourceTest.php
Command: `php artisan test tests/Feature/FacultyResourceTest.php`
- Super admin faculty listing

### tests/Feature/SubjectResourceTest.php
Command: `php artisan test tests/Feature/SubjectResourceTest.php`
- Super admin subject listing

### tests/Feature/UserResourceTest.php
Command: `php artisan test tests/Feature/UserResourceTest.php`
- Super admin user listing

---

## Summary

- **Unit Tests** validate models, services, and business rules.
- **Feature Tests** validate HTTP APIs, authentication, and admin UI behavior.
- Coverage includes authorization, workflows, edge cases, and role-based access.

Total:
- **121 tests**
- **223 assertions**
