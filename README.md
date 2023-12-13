## Capacitor::wrap(Laravel + Vue + Inertia)

### Stack
- Laravel
- Inertia
- Vue 3
- Vite
- Tailwind
- Sanctum

### Introduction
This is a proof of concept for wrapping Laravel + Vue + Inertia app in Capacitor JS, with Sanctum as the auth mechanism for
stateful and stateless requests.

### Notes
- I'm using Windows, so Mac users will probably need to adapt a thing or two here and there
- I assume you've read the Capacitor's docs (or at least skimmed through them, so you understand how it works), and that you have Android Studio installed
- This repository, as stated, uses Laravel, Vue, and Inertia. If you want to apply the steps listed below to your own app, it's expected that that app is already set up using this stack

### Deviations from defaults
- `HandleInertiaRequest` - root view set to `default`. `auth()->user()` added to `share()` method
- `EnsureFrontendRequestsAreStateful` - wrapped in `SanctumMiddleware` custom middleware due to a bug in Sanctum documented [here](https://github.com/laravel/sanctum/issues/482).  
When using `SanctumMiddleware` which addresses this bug, you need to make sure to set Sanctum's `stateful` domains correctly, so it wouldn't recognize your Capacitor's requests as stateful, since they're, by default, coming from `http://localhost` for Android apps.  
Additionally, `SanctumMiddleware` will dynamically set the auth guard to `sanctum` when the request is coming from a mobile app. This will allow us to have access to `$request->user()` on routes not protected by `auth:sanctum` middleware
- `RouteServiceProvider` - set `HOME` to `/account`. Wrapped routes in `api` middleware group, instead of `web`
- `App\Http\Kernel` - added `SanctumMiddleware` and `HandleInertialRequest` middleware to the `api` middleware group
- `capacitor.config.json` - android path set to `mobile/android`
- `app` config - added `exposed_url`
- `sanctum` config - made sure that only local dev URL and production URL for web browsers are set as `stateful` domains 
- `cors` config:
    - set `paths` to `['*']`
    - set `allowed_origins` to `['http://localhost']` - this is the default host from which Android Capacitor apps send requests. Check out Capacitor docs to see the origin you need to add for iOS apps
    - set `exposed_header` to `['x-inertia']` - otherwise the frontend won't recognize your API's responses as Inertia responses
- `AndroidManifest.xml` (`mobile/android/app/src/main/AndroidManifest.xml`) - under `android:supportsRtl="true"` added `android:usesCleartextTraffic="true"` because our exposed URL is `http` and not `https`. This should be set to false in production

### Steps
1. Create `.env` from `.env.example` and fill out required fields. `EXPOSED_APP_URL` will be explained in the next step
2. Run `composer install` and `npm install`
3. Serve your app on an 'exposed URL', i.e. a URL on which Capacitor app will be able to reach your app's API. Easiest way to do this locally is to check your computer's IP on the local network (192.168.x.x), then run `php artisan serve --host=192.168.x.x --port=8000`. In this case your exposed URL will be `http://192.168.x.x:8000`.   
If you're, for example, running your app on Laragon and serving it on `https://capacitor.test`, this URL will not be reachable by the Capacitor app. This is why we need to host it on an exposed URL for local development
4. Set this URL in your `.env` file as the `EXPOSED_APP_URL`
5. Run `npm run build` to build assets initially
6. Take a look at the [default.blade.php](https://github.com/GTCrais/capacitor-inertia-vue-laravel/blob/master/resources/views/default.blade.php) view - you'll see that `window.app_base_url` is set to your app's URL (NOT the exposed one). This will allow your SPA's frontend to make requests to the correct URL (you'll see in a moment why this is required)
7. Capacitor requires `index.html` to exist in the app's root directory, along with CSS and JS assets. Write a console command for compiling your blade layout into `index.html`, and copying assets where they need to go - [Console\Commands\BuildApp](https://github.com/GTCrais/capacitor-inertia-vue-laravel/blob/master/app/Console/Commands/BuildApp.php). You can run the command with `php artisan build-app`. If you take a look at it, the command does the following:
    - First, it runs the `npm`'s `build` command to build the assets
    - Next, it ensures that in the compiled `index.html` all absolute paths (`https://{app.url}`) are replaced with relative paths, i.e. all `https://{app.url}` strings are deleted since the assets are in the same folder as the `index.html`
    - Before compiling the `index.html` from our Blade template, it replaces the `window.app_base_url`'s value with our exposed URL. This will allow the Capacitor app to make requests to the correct URL. This means you can serve the app for web browsers on a "standard" URL like `https://capacitor.test`, and then also expose it to the Capacitor app on `http://192.168.x.x:8000` at the same time. In production these URLs will be the same, but for local development this is not possible, as far as I'm aware
    - It compiles Blade template into `index.html` and puts it in `public/build/assets` (this is where Vite puts compiled JS and CSS assets)
    - It runs `npx cap sync` which syncs your compiled files to the Capacitor app's folder
8. Authentication from Capacitor - the only thing we need to do is set the default guard in `auth` config to `sanctum` so we can have access to `$request->user()` even on routes not protected by `auth:sanctum`, and set up separate `login` and `logout` routes for mobile devices (check [web.php]() routes for more info)

### Files worth looking at
- ...
- ...