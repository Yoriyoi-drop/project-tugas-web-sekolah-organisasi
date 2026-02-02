LIBRARY_SETUP.md
================

Goal
----
This file contains recommended composer/npm commands, configuration snippets, publish/migrate steps and short examples to modernize and extend `organisasi-sekolah-web-2.0` while keeping Laravel 12 compatibility.

IMPORTANT
---------
- Do NOT remove or modify existing AI files (e.g. ContinueClient.php, AIController.php). We will only extend capabilities around them (caching, queueing, rate-limits).
- Run these steps on a clean working tree and commit often.

NOTE ON CSS ASSETS
------------------
- All CSS assets (Google Fonts, Bootstrap Icons from npm, Font Awesome) are stored locally in public/css directory
- Only necessary Bootstrap files are kept (bootstrap.min.css), unused files (grid, reboot, utilities, RTL versions) have been removed
- CSS assets are referenced via Laravel's asset() helper for proper URL generation
- Bootstrap Icons are now loaded from npm package (public/css/bootstrap-icons-npm/)

1) Composer packages (Laravel 12 compatible)
------------------------------------------
Run these composer commands (single-line recommended):

```bash
composer require laravel/sanctum "spatie/laravel-permission:^6.0" "spatie/laravel-activitylog:^4.9" "owen-it/laravel-auditing:^14.0" fruitcake/laravel-cors \
  "spatie/laravel-responsecache:^7.0" "laravel/horizon:^6.0" predis/predis doctrine/dbal "barryvdh/laravel-ide-helper" "barryvdh/laravel-debugbar:^4.0" \
  "livewire/livewire:^3.5" "spatie/laravel-analytics:^5.0" "arapex/larapex-charts" "pusher/pusher-php-server:^7.2" "beyondcode/laravel-websockets" "laravel/telescope:^6.0" "beyondcode/laravel-dump-server"

# dev-only tools
composer require --dev "nunomaduro/larastan:^2.8" "barryvdh/laravel-ide-helper" "beyondcode/laravel-dump-server" "barryvdh/laravel-debugbar"
```

Notes:
- `predis/predis` is included to ensure Redis connectivity for Horizon and queues if ext-redis is not installed. If you have php redis extension, you can skip predis.
- For `laravel/octane` I recommend evaluating it separately - Octane has runtime implications (Swoole/RoadRunner). Do not enable it until the codebase is stateless-friendly.

2) NPM / Frontend packages
---------------------------
Install Tailwind, Alpine, CKEditor and build tools (Vite is already present in the repo):

```bash
npm install -D tailwindcss postcss autoprefixer
npm install alpinejs @alpinejs/persist
npm install --save @ckeditor/ckeditor5-build-classic

# Optional UI libraries for modals / components (Livewire integrates well)
npm install @headlessui/vue

npx tailwindcss init -p
```

3) Publish vendors & run migrations
----------------------------------
After composer install, run the typical vendor:publish and migration commands for each package.

Common sequence:

```bash
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-config"
php artisan vendor:publish --provider="OwenIt\Auditing\AuditingServiceProvider" --tag="config"
php artisan vendor:publish --provider="Spatie\ResponseCache\ResponseCacheServiceProvider" --tag="config"
php artisan vendor:publish --provider="Laravel\Horizon\HorizonServiceProvider" --tag="horizon-config"
php artisan vendor:publish --provider="BeyondCode\LaravelWebSockets\WebSocketsServiceProvider" --tag="websockets-config"
php artisan vendor:publish --provider="Laravel\Telescope\TelescopeServiceProvider"

# run migrations (spatie.permission creates roles/permissions tables)
php artisan migrate
```

4) Key configuration snippets & setup steps
-----------------------------------------

4.1 Spatie Permission (roles & permissions)
-----------------------------------------

# 1) Add trait to User model
In `app/Models/User.php` add:

```php
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles; // add HasRoles
    // ...
}
```

# 2) Publish & migrate (already above)
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
php artisan migrate

# 3) Example usage (seed or controller):

```php
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

// create permission
Permission::firstOrCreate(['name' => 'manage posts']);

// create role and assign
$role = Role::firstOrCreate(['name' => 'admin']);
$role->givePermissionTo('manage posts');

// assign role to user
$user->assignRole('admin');

// check
if ($user->can('manage posts')) { /* allowed */ }
```

4.2 Spatie Activity Log
-----------------------

# Publish config + migrate if needed
php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-config"

# Example config changes (config/activitylog.php):
'default_log_name' => env('ACTIVITY_LOG_NAME', 'default'),

# Create log entries in code
activity()
    ->causedBy(auth()->user())
    ->performedOn($model)
    ->withProperties(['ip' => request()->ip()])
    ->log('updated profile');

4.3 Owen-it Auditing (DB level auditing)
----------------------------------------

# Publish config
php artisan vendor:publish --provider="OwenIt\Auditing\AuditingServiceProvider" --tag="config"

# In model(s) enable auditing

```php
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;

class Post extends Model implements Auditable
{
    use AuditableTrait;
}
```

4.4 Response Cache (cache AI responses / heavy API results)
---------------------------------------------------------

# Publish config
php artisan vendor:publish --provider="Spatie\ResponseCache\ResponseCacheServiceProvider" --tag="config"

# In `app/Http/Kernel.php` register middleware:

protected $routeMiddleware = [
    // ...
    'response.cache' => \Spatie\ResponseCache\Middlewares\CacheResponse::class,
];

# Use on routes that call AI endpoints, e.g. in routes/api.php or web routes:
Route::middleware(['auth:sanctum','response.cache'])->group(function(){
    Route::get('/ai/generate', [App\Http\Controllers\AIController::class, 'generate']);
});

# You can also cache programmatically:
\Spatie\ResponseCache\ResponseCache::cacheResponse($request, $response);

4.5 Horizon (queue monitor for AI jobs)
-------------------------------------

# 1) Use Redis queue driver. Set in .env:
QUEUE_CONNECTION=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# 2) Publish Horizon config
php artisan vendor:publish --provider="Laravel\Horizon\HorizonServiceProvider"

# 3) Start Horizon
php artisan horizon

# Dispatching AI jobs to dedicated queue and monitor with horizon:

// app/Jobs/ProcessAiRequest.php
class ProcessAiRequest implements ShouldQueue
{
    public $timeout = 120;
    public function handle(){
        // call AI client, cache response, log
    }
}

// Dispatch:
ProcessAiRequest::dispatch($payload)->onQueue('ai');

4.6 WebSockets / Pusher / Echo (realtime notifications)
-----------------------------------------------------

# Option A: Pusher (hosted)
1) Set in .env:
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your-id
PUSHER_APP_KEY=your-key
PUSHER_APP_SECRET=your-secret
PUSHER_APP_CLUSTER=mt1

2) On frontend use Laravel Echo + Pusher JS to listen for events.

# Option B: Self-hosted websockets (BeyondCode)
1) Install and publish configuration (already included above).
php artisan vendor:publish --provider="BeyondCode\LaravelWebSockets\WebSocketsServiceProvider" --tag="config"

2) Configure `config/websockets.php` and `config/broadcasting.php` to use `pusher` driver but point to your websockets server.

3) Run websockets server:
php artisan websockets:serve

# Example broadcast event
class NewNotification extends ShouldBroadcast
{
    public function broadcastOn(){
        return new PrivateChannel('user.' . $this->userId);
    }
}

4.7 Spatie Analytics + Larapex charts (dashboard analytics)
---------------------------------------------------------

# Spatie Analytics requires Google Analytics setup with a service account.
# Set environment and config:
ANALYTICS_VIEW_ID=xxxxxx
GOOGLE_APPLICATION_CREDENTIALS=/path/to/ga-service-account.json

# Example usage in a controller:
use Spatie\Analytics\Analytics;
use Spatie\Analytics\Period;

public function dashboard(\LarapexCharts\LarapexChart $chartService){
    $analyticsData = Analytics::fetchVisitorsAndPageViews(Period::days(7));

    $chart = $chartService->lineChart();
    $chart->setTitle('Visitors & PageViews');
    $chart->setXAxis($analyticsData->pluck('date')->map(fn($d)=>$d->format('Y-m-d'))->toArray());
    $chart->addData('Visitors', $analyticsData->pluck('visitors')->toArray());

    return view('admin.dashboard', compact('chart'));
}

# In blade
{!! $chart->container() !!}
{!! $chart->script() !!}

4.8 Livewire + Tailwind integration
-----------------------------------

# Install Livewire assets
php artisan livewire:publish --assets

# Example Livewire component placement
app/Http/Livewire/NotificationsPanel.php

# Blade usage
<livewire:notifications-panel />

4.9 Telescope (developer debugging & inspection)
-----------------------------------------------

# Install Telescope (dev only)
composer require laravel/telescope --dev
php artisan telescope:install
php artisan migrate
php artisan serve & visit /telescope

4.10 Larastan (static analysis)
--------------------------------

composer require --dev nunomaduro/larastan:^2.8
# then create phpstan.neon and run php artisan code:analyse or vendor/bin/phpstan analyse

5) Example: Cache AI responses and queue processing flow
-------------------------------------------------------

# AIController (pseudo)
public function generate(Request $request){
    $cacheKey = 'ai:'.md5(json_encode($request->all()));
    if (Cache::has($cacheKey)) {
        return Cache::get($cacheKey);
    }

    // dispatch job to process heavy model (async) or call directly
    $response = \App\Services\AiService::call($request->input('prompt'));
    Cache::put($cacheKey, $response, now()->addMinutes(60));
    return response()->json($response);
}

# For longer jobs, queue with Horizon and return job status or push notification when ready.

6) config/app.php changes (if any)
---------------------------------
Most packages are auto-discovered. Minimal changes you may want to make:

- Add aliases if desired (optional):

'aliases' => [
    // ...
    'Form' => Illuminate\Support\Facades\Form::class,
],

No required core edits; instead use published configs for each package.

7) Post-install & maintenance
------------------------------

Recommended commands after installing packages:

```bash
php artisan optimize:clear
php artisan migrate
php artisan db:seed # if you have role/permission seeder
php artisan horizon:install
php artisan responsecache:clear
```

8) Additional guidance & best practices
--------------------------------------
- Keep AI response caching TTL conservative and invalidate cache on model changes.
- Use named queues for heavy AI jobs (queue name: 'ai') and monitor via Horizon.
- Protect broadcast channels with broadcasting auth (routes/channels.php) and private channels.
- For production, use Redis with persistence and set queue retry/backoff policies.
- For Octane: only adopt after testing statelessness, and ensure session, cache, and queue drivers are compatible.

9) Example `routes/channels.php` snippet for private notifications
-----------------------------------------------------------------
Broadcast::channel('user.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

10) Example: quick Role & Permission seeder
-------------------------------------------
Create `database/seeders/PermissionSeeder.php`:

```php
public function run(){
    $perms = ['manage_users','manage_posts','view_reports','manage_security'];
    foreach($perms as $p) Permission::firstOrCreate(['name'=>$p]);
    $admin = Role::firstOrCreate(['name'=>'admin']);
    $admin->syncPermissions($perms);
}
```

11) Where to place new code
---------------------------
- app/Services: AI client wrapper (AiService), caching helpers
- app/Jobs: queued jobs for AI processing (ProcessAiRequest)
- app/Http/Livewire: Livewire components for admin UI
- resources/js: Echo + Pusher + Livewire client code
- routes: `routes/web.php` for dashboard UI, `routes/api.php` for AI endpoints

12) Quick troubleshooting checklist
----------------------------------
- If Horizon queues don't start: check Redis connectivity and QUEUE_CONNECTION env.
- If broadcast events not received: check broadcasting config, pusher credentials or websocket server, and CORS.
- If activity logs empty: ensure config/activitylog.php has correct `enabled` and model logging is invoked.

13) Want me to apply some of these changes automatically?
-------------------------------------------------------
I can (pick one):
- A) Add `HasRoles` trait to `app/Models/User.php` and create a PermissionSeeder + run migrations and seed (I will make edits and run tests). 
- B) Add a sample Livewire component + Larapex chart and a small dashboard view.
- C) Create scaffolding for queued AI job + Horizon config and an example job.

Choose A / B / C or tell me what to commit next and I'll implement it.

---
End of LIBRARY_SETUP.md
