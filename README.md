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

#### Note
I'm using Windows, so Mac users will probably need to adapt a thing here and there

#### Steps
1. Before anything, serve your app on an 'exposed URL', i.e. the URL on which Capacitor app will be able to reach your app's API. Easiest way to do this locally is to check your computer's IP on the local network (192.168.x.x), then run `php artisan serve --host=192.168.x.x --port=8000)`. In this case your external URL will be `http://192.168.x.x:8000`.   
If you're for example running your app on Laragon, and serving it on `https://capacitor.test`, this URL will not be reachable by Capacitor. This is why we need to host it on an external URL for local development
2. Set this URL in your `.env` file as the `EXPOSED_APP_URL`
3. Run `npm run build` to build assets initially
4. Capacitor requires `index.html` to exist in the app's root directory, along with CSS and JS assets. Write a command for compiling your blade layout into index.html, and then copying assets where they need to go - [Console\Commands\BuildApp](https://github.com/GTCrais/capacitor-inertia-vue-laravel/blob/master/app/Console/Commands/BuildApp.php)
    - First, run the npm's `build` command, to build the assets
    - In our [default.blade.php](https://test) set `window.app_base_url` to your app's URL (NOT the external one). This will allow the Inertia Vue frontend to make requests to the correct URL (you'll see in a moment why this is required)
    - Next, we need to ensure that in our `index.html` all absolute paths (`http://{app.url}`) are replaced with relative paths, i.e. all `http://{app.url}` strings need to be deleted
    - Before compiling the `index.html` from our Blade template, we'll replace the `window.app_base_url`'s value with our exposed URL. This will allow the Capacitor app to make requests to the correct URL. This allows us to serve the app for web browsers on a "standard" URL like `https://capacitor.test`, and then also expose it to the Capacitor app on `http://192.168.x.x:8000` at the same time
    - Compile Blade template into `index.html`
    - Run `npx cap sync` to sync our compiled files the Capacitor app's folder. By default this is `./android`, but I've moved it to `/mobile/android` in the `capacitor.config.json` config file