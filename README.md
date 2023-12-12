## !!! WORK IN PROGRESS !!!

### Capacitor::wrap(Laravel + Vue + Inertia)

#### Stack
- Laravel
- Inertia
- Vue 3
- Vite
- Tailwind
- Sanctum

#### Introduction
This is a proof of concept for wrapping Laravel + Vue + Inertia app in Capacitor JS, with Sanctum as the auth mechanism for
stateful and stateless requests.

#### Notes
- I'm using Windows, so Mac users will probably need to adapt a thing or two here and there
- I assume you've read the Capacitor's docs (or at least skimmed through them, so you understand how it works), and that you have Android Studio installed

#### Deviations from defaults
- `HandleInertiaRequest` - root view set to `default` (optional)
- `EnsureFrontendRequestsAreStateful` - wrapped in `SanctumMiddleware` custom middleware due to a bug (more on that later)
- `capacitor.config.json` - android path set to `mobile/android` (optional)
- `app` config - added `exposed_url`
- `sanctum` config - made sure that only local dev URL and production URL for web browsers are set as `stateful` domains 
- `cors` config:
    - set `paths` to `['*']`
    - set `allowed_origins` to `['http://localhost']` - this is the default host from which Android Capacitor apps send requests. Check out Capacitor docs to see the origin you need to add for iOS apps
    - set `exposed_header` to `['x-inertia']` - otherwise the frontend won't recognize your API's responses as Inertia responses
- `AndroidManifest.xml` (`mobile/android/app/src/main/AndroidManifest.xml`) - under `android:supportsRtl="true"` added `android:usesCleartextTraffic="true"` because our exposed URL is `http` and not `https`. This should be set to false in production

#### Steps
1. Create `.env` from `.env.example` and fill out required fields. `EXPOSED_APP_URL` will be explained in the next step
2. Serve your app on an 'exposed URL', i.e. a URL on which Capacitor app will be able to reach your app's API. Easiest way to do this locally is to check your computer's IP on the local network (192.168.x.x), then run `php artisan serve --host=192.168.x.x --port=8000`. In this case your exposed URL will be `http://192.168.x.x:8000`.   
If you're, for example, running your app on Laragon and serving it on `https://capacitor.test`, this URL will not be reachable by the Capacitor app. This is why we need to host it on an exposed URL for local development
3. Set this URL in your `.env` file as the `EXPOSED_APP_URL`
4. Run `npm run build` to build assets initially
5. Take a look at the [default.blade.php](https://github.com/GTCrais/capacitor-inertia-vue-laravel/blob/master/resources/views/default.blade.php) view - you'll see that `window.app_base_url` is set to your app's URL (NOT the exposed one). This will allow your SPA's frontend to make requests to the correct URL (you'll see in a moment why this is required)
6. Capacitor requires `index.html` to exist in the app's root directory, along with CSS and JS assets. Write a console command for compiling your blade layout into `index.html`, and copying assets where they need to go - [Console\Commands\BuildApp](https://github.com/GTCrais/capacitor-inertia-vue-laravel/blob/master/app/Console/Commands/BuildApp.php). If you take a look at it, the command does the following:
    - First, it runs the `npm`'s `build` command to build the assets
    - Next, it ensures that in the compiled `index.html` all absolute paths (`https://{app.url}`) are replaced with relative paths, i.e. all `https://{app.url}` strings are deleted since the assets are in the same folder as the `index.html`
    - Before compiling the `index.html` from our Blade template, it replaces the `window.app_base_url`'s value with our exposed URL. This will allow the Capacitor app to make requests to the correct URL. This means you can serve the app for web browsers on a "standard" URL like `https://capacitor.test`, and then also expose it to the Capacitor app on `http://192.168.x.x:8000` at the same time. In production these URLs will be the same, but for local development this is not possible, as far as I'm aware
    - It compiles Blade template into `index.html` and puts it in `public/build/assets` (this is where Vite puts compiled JS and CSS assets)
    - It runs `npx cap sync` which syncs your compiled files to the Capacitor app's folder
7. Authentication from Capacitor - WIP. Actually, work hasn't even been started on this yet, but it should be relatively simple, using Sanctum's docs for API Token Authentication