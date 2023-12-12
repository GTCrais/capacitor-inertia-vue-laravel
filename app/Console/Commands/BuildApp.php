<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Str;

class BuildApp extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build-app';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Builds index.html';

    /**
     * Execute the console command.
     */
    public function handle()
    {
		$this->info('Building...');
		$this->info("Running 'npm run build'...");

		$result = Process::run('npm run build');
		$this->info($result->output());

		$this->info("Building 'index.html'...");

		$content = file_get_contents(config('app.url'));

		// If content has "hot" files, throw an error
		if ($content) {

		}

		$content = Str::replace(config('app.url') . '/build/assets/', '', $content);
		$content = Str::replace("window.app_base_url = 'https://capacitor.test'", "window.app_base_url = '" . config('app.exposed_url') . "'", $content);

		file_put_contents(
			public_path('build/assets/index.html'), $content
		);

		$this->info('Synchronizing Capacitor assets...');
		$result = Process::run('npx cap sync');
		$this->info($result->output());

		$this->info('Application built.');
    }
}
