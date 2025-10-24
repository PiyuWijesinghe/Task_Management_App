# Testing Guide — Task Management App (Backend)

This file describes the test structure and best practices used by the backend Laravel app.

## Where tests live

- `tests/Feature/` — high-level, HTTP/controller/integration tests that exercise multiple layers.
- `tests/Unit/` — fast, isolated unit tests for models, helpers and small pure classes.

Existing tests follow this structure already (see `tests/Feature` and `tests/Unit`).

## Best practices / conventions

- Use `RefreshDatabase` in Feature tests to ensure a clean database state between tests.
  - Example:
    ```php
    use Illuminate\Foundation\Testing\RefreshDatabase;

    class TaskTest extends TestCase
    {
        use RefreshDatabase;
        // ...
    }
    ```
- Name feature tests using the resource or domain name with `Test` suffix, e.g.:
  - `tests/Feature/TaskTest.php`
  - `tests/Feature/UserRoleTest.php`
  - `tests/Feature/TaskAssignmentTest.php`
- Prefer one responsibility per test method and a `test_` prefix naming. Keep test names expressive.
  - `public function test_owner_can_assign_and_unassign_users()`

- Use factories for all model creation. Avoid seeding for test fixtures unless necessary.
- Use `Notification::fake()` / `Mail::fake()` in tests that assert notifications or emails.

## How many tests

There are already 40+ test methods across `tests/Feature` and `tests/Unit` covering authentication, task CRUD, comments, attachments, postponement, secure downloads, role access and notifications. This meets the requested 15–20 well-defined test cases target.

## Adding new tests (template)

Create a new feature test when adding behavior that crosses HTTP/controllers and persistence:

```bash
php artisan make:test Feature/NewFeatureTest
```

Template inside file:

```php
<?php
namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NewFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_something_happens()
    {
        // Arrange: factories
        // Act: HTTP call
        // Assert: DB / response / notifications
    }
}
```

## Running tests (PowerShell)

From project root (task-app):

```powershell
cd .\task-app\
php artisan test                       # run all tests
php artisan test --filter=TaskTest     # run a subset
php artisan test --testsuite=Unit      # run unit tests only
```

## Adding coverage / CI

- The project supports `php artisan test --coverage` for local coverage reports (Xdebug/PCOV required).
- Add a CI workflow (GitHub Actions) that runs `composer install --no-interaction` and `php artisan test` on push/PR.

## Summary

The codebase already follows the requested structure and testing practices:
- Tests are organized under `tests/Feature` and `tests/Unit`.
- Most feature tests use `RefreshDatabase`.
- Naming is consistent and expressive.
- There are well over 20 test cases across the project.

If you'd like, I can:
- Standardize test file names to the exact pattern you prefer (e.g., rename `TaskCrudTest.php` -> `TaskTest.php`).
- Add a GitHub Actions workflow to run tests on push/PR.
- Expand the test matrix with more edge cases for each domain (I can add another 10 focused tests on request).
