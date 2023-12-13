<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Pipeline;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Symfony\Component\HttpFoundation\Response;

class SanctumMiddleware extends EnsureFrontendRequestsAreStateful
{
	/**
	 * Handle the incoming requests.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  callable  $next
	 * @return \Illuminate\Http\Response
	 */
	public function handle($request, $next)
	{
		// If we're making a request from a mobile app, set the default guard to 'sanctum'. This
		// will allow us to access `$request->user()` on routes not protected by `auth:sanctum`
		if (static::fromMobile($request)) {
			config(['auth.defaults.guard' => 'sanctum']);
		}

		return parent::handle($request, $next);
	}

	public static function fromMobile($request)
	{
		return !static::fromFrontend($request);
	}

	public static function fromFrontend($request)
	{
		$domain = $request->headers->get('referer') ?: $request->headers->get('origin') ?: $request->headers->get('host');

		if (is_null($domain)) {
			return false;
		}

		$domain = Str::replaceFirst('https://', '', $domain);
		$domain = Str::replaceFirst('http://', '', $domain);
		$domain = Str::endsWith($domain, '/') ? $domain : "{$domain}/";

		$stateful = array_filter(config('sanctum.stateful', []));

		return Str::is(Collection::make($stateful)->map(function ($uri) {
			return trim($uri).'/*';
		})->all(), $domain);
	}
}
