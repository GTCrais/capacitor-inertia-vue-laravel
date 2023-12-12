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
