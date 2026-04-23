<laravel-boost-guidelines>
=== foundation rules ===

# Laravel Boost Guidelines

The Laravel Boost guidelines are specifically curated by Laravel maintainers for this application. These guidelines should be followed closely to ensure the best experience when building Laravel applications.

## Foundational Context

This application is a Laravel application and its main Laravel ecosystems package & versions are below. You are an expert with them all. Ensure you abide by these specific packages & versions.

- php - 8.4.17
- inertiajs/inertia-laravel (INERTIA) - v2
- laravel/framework (LARAVEL) - v12
- laravel/prompts (PROMPTS) - v0
- statamic/cms (STATAMIC) - v6
- laravel/mcp (MCP) - v0
- laravel/pint (PINT) - v1
- laravel/sail (SAIL) - v1
- livewire/livewire (LIVEWIRE) - v4
- phpunit/phpunit (PHPUNIT) - v11
- alpinejs (ALPINEJS) - v3
- tailwindcss (TAILWINDCSS) - v4

## Skills Activation

This project has domain-specific skills available. You MUST activate the relevant skill whenever you work in that domain—don't wait until you're stuck.

- `tailwindcss-development` — Styles applications using Tailwind CSS v4 utilities. Activates when adding styles, restyling components, working with gradients, spacing, layout, flex, grid, responsive design, dark mode, colors, typography, or borders; or when the user mentions CSS, styling, classes, Tailwind, restyle, hero section, cards, buttons, or any visual/UI changes.

## Conventions

- You must follow all existing code conventions used in this application. When creating or editing a file, check sibling files for the correct structure, approach, and naming.
- Use descriptive names for variables and methods. For example, `isRegisteredForDiscounts`, not `discount()`.
- Check for existing components to reuse before writing a new one.

## Verification Scripts

- Do not create verification scripts or tinker when tests cover that functionality and prove they work. Unit and feature tests are more important.

## Application Structure & Architecture

- Stick to existing directory structure; don't create new base folders without approval.
- Do not change the application's dependencies without approval.

## Frontend Bundling

- If the user doesn't see a frontend change reflected in the UI, it could mean they need to run `npm run build`, `npm run dev`, or `composer run dev`. Ask them.

## Documentation Files

- You must only create documentation files if explicitly requested by the user.

## Replies

- Be concise in your explanations - focus on what's important rather than explaining obvious details.

=== boost rules ===

# Laravel Boost

- Laravel Boost is an MCP server that comes with powerful tools designed specifically for this application. Use them.

## Artisan

- Use the `list-artisan-commands` tool when you need to call an Artisan command to double-check the available parameters.

## URLs

- Whenever you share a project URL with the user, you should use the `get-absolute-url` tool to ensure you're using the correct scheme, domain/IP, and port.

## Tinker / Debugging

- You should use the `tinker` tool when you need to execute PHP to debug code or query Eloquent models directly.
- Use the `database-query` tool when you only need to read from the database.
- Use the `database-schema` tool to inspect table structure before writing migrations or models.

## Reading Browser Logs With the `browser-logs` Tool

- You can read browser logs, errors, and exceptions using the `browser-logs` tool from Boost.
- Only recent browser logs will be useful - ignore old logs.

## Searching Documentation (Critically Important)

- Boost comes with a powerful `search-docs` tool you should use before trying other approaches when working with Laravel or Laravel ecosystem packages. This tool automatically passes a list of installed packages and their versions to the remote Boost API, so it returns only version-specific documentation for the user's circumstance. You should pass an array of packages to filter on if you know you need docs for particular packages.
- Search the documentation before making code changes to ensure we are taking the correct approach.
- Use multiple, broad, simple, topic-based queries at once. For example: `['rate limiting', 'routing rate limiting', 'routing']`. The most relevant results will be returned first.
- Do not add package names to queries; package information is already shared. For example, use `test resource table`, not `filament 4 test resource table`.

### Available Search Syntax

1. Simple Word Searches with auto-stemming - query=authentication - finds 'authenticate' and 'auth'.
2. Multiple Words (AND Logic) - query=rate limit - finds knowledge containing both "rate" AND "limit".
3. Quoted Phrases (Exact Position) - query="infinite scroll" - words must be adjacent and in that order.
4. Mixed Queries - query=middleware "rate limit" - "middleware" AND exact phrase "rate limit".
5. Multiple Queries - queries=["authentication", "middleware"] - ANY of these terms.

=== php rules ===

# PHP

- Always use curly braces for control structures, even for single-line bodies.

## Constructors

- Use PHP 8 constructor property promotion in `__construct()`.
    - `public function __construct(public GitHub $github) { }`
- Do not allow empty `__construct()` methods with zero parameters unless the constructor is private.

## Type Declarations

- Always use explicit return type declarations for methods and functions.
- Use appropriate PHP type hints for method parameters.

<!-- Explicit Return Types and Method Params -->
```php
protected function isAccessible(User $user, ?string $path = null): bool
{
    ...
}
```

## Enums

- Typically, keys in an Enum should be TitleCase. For example: `FavoritePerson`, `BestLake`, `Monthly`.

## Comments

- Prefer PHPDoc blocks over inline comments. Never use comments within the code itself unless the logic is exceptionally complex.

## PHPDoc Blocks

- Add useful array shape type definitions when appropriate.

=== herd rules ===

# Laravel Herd

- The application is served by Laravel Herd and will be available at: `https?://[kebab-case-project-dir].test`. Use the `get-absolute-url` tool to generate valid URLs for the user.
- You must not run any commands to make the site available via HTTP(S). It is always available through Laravel Herd.

=== tests rules ===

# Test Enforcement

- Every change must be programmatically tested. Write a new test or update an existing test, then run the affected tests to make sure they pass.
- Run the minimum number of tests needed to ensure code quality and speed. Use `php artisan test --compact` with a specific filename or filter.

=== inertia-laravel/core rules ===

# Inertia

- Inertia creates fully client-side rendered SPAs without modern SPA complexity, leveraging existing server-side patterns.
- Components live in `resources/js/Pages` (unless specified in `vite.config.js`). Use `Inertia::render()` for server-side routing instead of Blade views.
- ALWAYS use `search-docs` tool for version-specific Inertia documentation and updated code examples.

=== inertia-laravel/v2 rules ===

# Inertia v2

- Use all Inertia features from v1 and v2. Check the documentation before making changes to ensure the correct approach.
- New features: deferred props, infinite scrolling (merging props + `WhenVisible`), lazy loading on scroll, polling, prefetching.
- When using deferred props, add an empty state with a pulsing or animated skeleton.

=== laravel/core rules ===

# Do Things the Laravel Way

- Use `php artisan make:` commands to create new files (i.e. migrations, controllers, models, etc.). You can list available Artisan commands using the `list-artisan-commands` tool.
- If you're creating a generic PHP class, use `php artisan make:class`.
- Pass `--no-interaction` to all Artisan commands to ensure they work without user input. You should also pass the correct `--options` to ensure correct behavior.

## Database

- Always use proper Eloquent relationship methods with return type hints. Prefer relationship methods over raw queries or manual joins.
- Use Eloquent models and relationships before suggesting raw database queries.
- Avoid `DB::`; prefer `Model::query()`. Generate code that leverages Laravel's ORM capabilities rather than bypassing them.
- Generate code that prevents N+1 query problems by using eager loading.
- Use Laravel's query builder for very complex database operations.

### Model Creation

- When creating new models, create useful factories and seeders for them too. Ask the user if they need any other things, using `list-artisan-commands` to check the available options to `php artisan make:model`.

### APIs & Eloquent Resources

- For APIs, default to using Eloquent API Resources and API versioning unless existing API routes do not, then you should follow existing application convention.

## Controllers & Validation

- Always create Form Request classes for validation rather than inline validation in controllers. Include both validation rules and custom error messages.
- Check sibling Form Requests to see if the application uses array or string based validation rules.

## Authentication & Authorization

- Use Laravel's built-in authentication and authorization features (gates, policies, Sanctum, etc.).

## URL Generation

- When generating links to other pages, prefer named routes and the `route()` function.

## Queues

- Use queued jobs for time-consuming operations with the `ShouldQueue` interface.

## Configuration

- Use environment variables only in configuration files - never use the `env()` function directly outside of config files. Always use `config('app.name')`, not `env('APP_NAME')`.

## Testing

- When creating models for tests, use the factories for the models. Check if the factory has custom states that can be used before manually setting up the model.
- Faker: Use methods such as `$this->faker->word()` or `fake()->randomDigit()`. Follow existing conventions whether to use `$this->faker` or `fake()`.
- When creating tests, make use of `php artisan make:test [options] {name}` to create a feature test, and pass `--unit` to create a unit test. Most tests should be feature tests.

## Vite Error

- If you receive an "Illuminate\Foundation\ViteException: Unable to locate file in Vite manifest" error, you can run `npm run build` or ask the user to run `npm run dev` or `composer run dev`.

=== laravel/v12 rules ===

# Laravel 12

- CRITICAL: ALWAYS use `search-docs` tool for version-specific Laravel documentation and updated code examples.
- Since Laravel 11, Laravel has a new streamlined file structure which this project uses.

## Laravel 12 Structure

- In Laravel 12, middleware are no longer registered in `app/Http/Kernel.php`.
- Middleware are configured declaratively in `bootstrap/app.php` using `Application::configure()->withMiddleware()`.
- `bootstrap/app.php` is the file to register middleware, exceptions, and routing files.
- `bootstrap/providers.php` contains application specific service providers.
- The `app\Console\Kernel.php` file no longer exists; use `bootstrap/app.php` or `routes/console.php` for console configuration.
- Console commands in `app/Console/Commands/` are automatically available and do not require manual registration.

## Database

- When modifying a column, the migration must include all of the attributes that were previously defined on the column. Otherwise, they will be dropped and lost.
- Laravel 12 allows limiting eagerly loaded records natively, without external packages: `$query->latest()->limit(10);`.

### Models

- Casts can and likely should be set in a `casts()` method on a model rather than the `$casts` property. Follow existing conventions from other models.

=== pint/core rules ===

# Laravel Pint Code Formatter

- You must run `vendor/bin/pint --dirty --format agent` before finalizing changes to ensure your code matches the project's expected style.
- Do not run `vendor/bin/pint --test --format agent`, simply run `vendor/bin/pint --format agent` to fix any formatting issues.

=== phpunit/core rules ===

# PHPUnit

- This application uses PHPUnit for testing. All tests must be written as PHPUnit classes. Use `php artisan make:test --phpunit {name}` to create a new test.
- If you see a test using "Pest", convert it to PHPUnit.
- Every time a test has been updated, run that singular test.
- When the tests relating to your feature are passing, ask the user if they would like to also run the entire test suite to make sure everything is still passing.
- Tests should cover all happy paths, failure paths, and edge cases.
- You must not remove any tests or test files from the tests directory without approval. These are not temporary or helper files; these are core to the application.

## Running Tests

- Run the minimal number of tests, using an appropriate filter, before finalizing.
- To run all tests: `php artisan test --compact`.
- To run all tests in a file: `php artisan test --compact tests/Feature/ExampleTest.php`.
- To filter on a particular test name: `php artisan test --compact --filter=testName` (recommended after making a change to a related file).

=== tailwindcss/core rules ===

# Tailwind CSS

- Always use existing Tailwind conventions; check project patterns before adding new ones.
- IMPORTANT: Always use `search-docs` tool for version-specific Tailwind CSS documentation and updated code examples. Never rely on training data.
- IMPORTANT: Activate `tailwindcss-development` every time you're working with a Tailwind CSS or styling-related task.

=== statamic/cms rules ===

## Statamic

- This application uses Statamic.
- Statamic is an open source, PHP CMS designed and built specifically for developers and their clients or content managers.
- Out of the box, Statamic stores content in Markdown files. It's trivial to move into a database later, if necessary.
- Statamic comes in two flavours:
    - **Statamic Core** which is free to use, however you want, forever. It includes everything needed to build a blog or portfolio site.
    - **Statamic Pro** which includes everything from Core, as well as unlimited user accounts, revision history, multi-site, Git integration, white labelling and more. Tailored for most production websites.
    - For more information on pricing, please send the user to https://statamic.com/pricing.

### Folder Structure

Statamic is a Laravel package, meaning it can be used alone or alongside an existing Laravel application.

Most of the folder structure will feel familiar to Laravel developers. However, Statamic creates a few additional files and folders during the install process.

<code-snippet name="Folder Structure" lang="text">
├── app/
├── bootstrap/
├── config/
│   ├── statamic/         # Statamic-specific configs

├── content/
│   ├── assets/           # Asset containers

│   ├── collections/      # Collections and entries

│   ├── globals/          # Global sets

│   ├── navigation/       # Navigations

│   ├── trees/            # Collection and navigation trees

├── database/
├── lang/
├── public/
│   ├── assets/           # Default location for assets

│   ├── ...
├── resources/
│   ├── addons/
│   ├── blueprints/       # Blueprints

│   ├── fieldsets/        # Fieldsets

│   ├── users/            # User roles & groups

│   ├── preferences.yaml  # Default preferences

│   ├── sites.yaml        # Sites config

│   ├── ...
├── routes/
├── storage/
├── tests/
├── users/
├── please                # Statamic's CLI tool

├── ...
</code-snippet>

### Statamic's CLI

- Statamic ships with its own `please` CLI tool, useful for creating tags or fieldtypes, updating search indexes, enabling multi-site and much more.
- You may run `php please` to get the list of available commands. You may use the `--help` option on a command to inspect its required parameters.

### Statamic's Core Concepts

- **Assets:** Files managed by Statamic and made available to your writers and developers with tags and fieldtypes. They can be images, videos, PDFs, or any other type of file.
- **Collections:** Collections are containers that hold groups of related entries. Each entry in a collection can represent a blog post, product, recipe or page.
- **Globals:** Global variables store content that belongs to your whole site, not just a single page or URL. They're available everywhere, in all of your views, all the time.
- **Navigations:** A navigation is a hierarchy of links and text nodes that are used to build navs and menus on the frontend of your site.
- **Taxonomies:** A taxonomy is a system of classifying data around a set of unique characteristics. Think things like tags, categories, etc.
- **Users:** Users are the member accounts to your site or application. What a user can do with their account is up to you. They could have limited or full access to the Control Panel, a login-only area of the front-end, or even something more custom by tapping into Laravel.
- **Blueprints:** Blueprints determine the fields shown in your publish forms. You can configure the field's order, each field's width and group them into sections and tabs. Blueprints are attached to collections, taxonomies, globals, assets, users and even forms, all of which help to determine their content schema.
- **Fieldsets:** Fieldsets are used to store and organize reusable fields. Blueprints can reference fields or entire fieldsets, helping you keep your configurations nice and DRY.

### Templating

- Statamic supports two templating languages:
    - **Antlers** is tightly integrated and simple to learn. Uses the `.antlers.html` file extension.
    - **Laravel Blade** ships with Laravel and is familiar to most Laravel developers. Uses the `.blade.php` file extension.
- When creating views, you should familiarize yourself with the project and determine which templating language is already in use.
- When using Laravel Blade, you may want to use the "Antlers Blade Components" feature which lets you use a Blade-component-esque syntax with Statamic's tags feature:

<code-snippet name="Antlers Blade Components example" lang="blade">
    <s:collection from="pages" limit="2" sort="title:desc">
        {{ $title }}
    </s:collection>
</code-snippet>

### Control Panel

- The Control Panel is the primary way to create and manage content.
- Unless disabled or overridden, the Control Panel is usually accessible from `https://your-website.com/cp`.
- Super users can do and see everything, while non-super users can only do & see what their roles allow for.

### Extending Statamic

- You can either extend Statamic in the context of an application, or in the context of an addon.
- Addons are Composer packages, meaning they can be reused, distributed, or even sold to others later.
- There are a variety of ways you can extend Statamic: creating tags, fieldtypes, modifiers, etc. A lot of these things can be bootstrapped with `php please make:` commands.

#### Extending the Control Panel

- The Control Panel is built with Inertia.js and Vue 3.
- When running the `make:fieldtype` or `make:widget` commands, Statamic will install the necessary npm packages and configure Vite.
- Running `setup-cp-vite` in an application context will also do this for you. You'll be able to use `npm run cp:dev` and `npm run cp:build` to run Vite.
- You should use Statamic's UI Components where possible. It includes components for buttons, cards, inputs, etc.
    - UI Components can be imported from `@statamic/cms/ui`.
    - For more information on Statamic's UI Components, please visit our Storybook docs: https://ui.statamic.dev

### Additional Context

- Statamic Documentation: https://statamic.dev/llms.txt
- GitHub Issues: https://github.com/statamic/cms/issues

=== reachweb/statamic-livewire-filters rules ===

## Statamic Livewire Filters

Statamic Livewire Filters is an addon that enables real-time, reactive filtering of Statamic collections without page reloads, using Livewire. It provides a drop-in replacement for Statamic's collection tag and a suite of filter components.

Full documentation: https://livewirefilters.com/llms-full.txt

### Basic Usage

Replace your collection tag with the livewire-collection tag and add filter components anywhere on the page:

<code-snippet name="Livewire Collection Tag" lang="antlers">
{{ livewire-collection:cars paginate="10" sort="title:asc" }}
</code-snippet>

Your entry template is now at `resources/views/vendor/statamic-livewire-filters/livewire/livewire-collection.antlers.html`.

### Filter Components

All filters require `blueprint`, `field`, and `condition` properties. The `blueprint` format is `collection.blueprint` (e.g., `cars.car`).

Available filter types:

- **TextFilter**: Live search text input.
- **CheckboxFilter**: Multiple-choice checkboxes.
- **RadioFilter**: Single-choice radio buttons.
- **SelectFilter**: Dropdown selection, with optional search.
- **DateFilter**: Date filtering using Flatpickr.
- **RangeFilter**: Single range slider.
- **DualRangeFilter**: Min/max range selection using noUiSlider.
- **ToggleFilter**: Boolean toggle switch.

<code-snippet name="Text filter example" lang="antlers">
{{ livewire:lf-text-filter
    blueprint="cars.car"
    field="title"
    condition="contains"
    placeholder="Search cars"
}}
</code-snippet>

<code-snippet name="Checkbox filter with taxonomy" lang="antlers">
{{ livewire:lf-checkbox-filter
    blueprint="cars.car"
    field="car_brand"
    condition="taxonomy"
}}
</code-snippet>

<code-snippet name="Select filter with search" lang="antlers">
{{ livewire:lf-select-filter
    blueprint="cars.car"
    field="transmission"
    condition="is"
    view="lf-select-advanced"
    searchable="true"
    placeholder="Transmission"
}}
</code-snippet>

<code-snippet name="Range filter" lang="antlers">
{{ livewire:lf-range-filter
    blueprint="cars.car"
    field="seats"
    condition="gte"
    min="2"
    max="9"
    default="2"
}}
</code-snippet>

<code-snippet name="Toggle filter" lang="antlers">
{{ livewire:lf-toggle-filter
    blueprint="cars.car"
    field="date_of_registration"
    condition="gte"
    preset_value="2025-01-01"
    label="Only new vehicles"
}}
</code-snippet>

### Sorting

<code-snippet name="Sort component" lang="antlers">
{{ livewire:lf-sort
    blueprint="cars.car"
    fields="title|max_passengers|price"
}}
</code-snippet>

### UI Components

<code-snippet name="Tags and count components" lang="antlers">
{{ livewire:lf-tags blueprint="cars.car" fields="title|car_brand|transmission" }}
{{ livewire:lf-count }}
</code-snippet>

### Preset Filters

You can preset filter values directly in the tag:

<code-snippet name="Preset filter values" lang="antlers">
{{ livewire-collection:cars taxonomy:car_brand:any="toyota" max_passengers:gte="4" }}
</code-snippet>

### Allowed Filters

Restrict which filters can be applied for security:

<code-snippet name="Allowed filters" lang="antlers">
{{ livewire-collection:cars paginate="6" allowed_filters="taxonomy:car_brand:any|transmission:is" }}
</code-snippet>

### Query Scopes

Use custom query scopes for advanced filtering:

<code-snippet name="Query scope filter" lang="antlers">
{{ livewire:lf-checkbox-filter
    blueprint="cars.car"
    field="car_brand"
    condition="query_scope"
    modifier="multiselect"
}}
</code-snippet>

### URL Query String

Enable SEO-friendly URLs in the config:

<code-snippet name="Custom query string config" lang="php">
// config/statamic-livewire-filters.php
'custom_query_string' => 'search',
'custom_query_string_aliases' => [
    'brand' => 'taxonomy:car_brand:any',
    'fuel' => 'fuel_type:is',
],
</code-snippet>

Add the URL handler component to your layout:

<code-snippet name="URL handler component" lang="antlers">
{{ livewire:lf-url-handler }}
</code-snippet>

### Hooks

Modify entries data before display:

<code-snippet name="Using hooks" lang="php">
\Reach\StatamicLivewireFilters\Http\Livewire\LivewireCollection::hook('livewire-fetched-entries',
    function ($entries, $next) {
        $entries->each(function ($entry) {
            // Modify entry data
        });
        return $next($entries);
    }
);
</code-snippet>

### File Structure

- `src/Http/Livewire/LivewireCollection.php` — Main Livewire component managing filtering state.
- `src/Http/Livewire/Lf*.php` — Individual filter components (LfCheckboxFilter, LfRadioFilter, etc.).
- `src/Http/Livewire/Traits/` — Shared traits: `IsLivewireFilter`, `HandleParams`, `HandleFieldOptions`, `GenerateParams`.
- `src/Tags/LivewireCollection.php` — Antlers livewire-collection tag.
- `resources/views/livewire/filters/` — Filter component views.
- `resources/views/livewire/sort/` — Sort component view.
- `resources/views/livewire/ui/` — Pagination, tags, count views.

### Namespace

PHP namespace: `Reach\StatamicLivewireFilters`
</laravel-boost-guidelines>
