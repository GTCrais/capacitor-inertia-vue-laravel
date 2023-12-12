<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

		<script>
			window.app_base_url = '<?php echo (config('app.url')) ?>';
		</script>

		@vite('resources/js/app.js')
		@inertiaHead
    </head>

    <body>
		@inertia
    </body>
</html>
