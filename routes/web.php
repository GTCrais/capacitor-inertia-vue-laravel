<?php

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

$usingSanctum = true;
$authMiddleware = $usingSanctum ? 'auth:sanctum' : 'auth';
$guestMiddleware = $usingSanctum ? 'guest:sanctum' : 'guest';

Route::get('/', function() {
	return \Inertia\Inertia::render('Home');
});

Route::middleware($guestMiddleware)->get('/login', function() {
	return \Inertia\Inertia::render('Login');
});

Route::middleware($guestMiddleware)->post('/login', function(\Illuminate\Http\Request $request) {
	if (auth()->attempt($request->only('email', 'password'))) {
		return redirect('/account');
	}

	return redirect()->to('/login')->withErrors([
		'general' => 'Incorrect login data',
	]);
});

Route::middleware($guestMiddleware)->post('/mobile/login', function(\Illuminate\Http\Request $request) {
	$user = User::where('email', $request->input('email'))->first();

	if (!$user || !Hash::check($request->input('password'), $user->password)) {
		abort(401, 'Incorrect login data');
	}

	return response()->json(
		$user->createToken('AUTH_TOKEN')->plainTextToken
	);

	// return ['token' => $user->createToken('AUTH_TOKEN')->plainTextToken];
});

Route::middleware($authMiddleware)->get('/account', function() {
	return \Inertia\Inertia::render('Account');
});

Route::middleware($authMiddleware)->get('/logout', function(\Illuminate\Http\Request $request) {
	auth()->guard('web')->logout();

	if ($request->hasSession()) {
		$request->session()->invalidate();
		$request->session()->regenerateToken();
	}

	return redirect()->to('/');
});
