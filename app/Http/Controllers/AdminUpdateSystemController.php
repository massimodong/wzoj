<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Cache;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Option;

use PharData;

class AdminUpdateSystemController extends Controller
{
	public function disable_ob() {
		// Turn off output buffering
		ini_set('output_buffering', 'off');
		// Turn off PHP output compression
		ini_set('zlib.output_compression', false);
		// Implicitly flush the buffer(s)
		ini_set('implicit_flush', true);
		ob_implicit_flush(true);
		// Clear, and turn off output buffering
		while (ob_get_level() > 0) {
			// Get the curent level
			$level = ob_get_level();
			// End the buffering
			ob_end_clean();
			// If the current level has not changed, abort
			if (ob_get_level() == $level) break;
		}
		// Disable apache output buffering/compression
		if (function_exists('apache_setenv')) {
			apache_setenv('no-gzip', '1');
			apache_setenv('dont-vary', '1');
		}
	}

	public function getUpdate(){
		return view('admin.update_system');
	}

	public function postUpdate(){
		//header("Content-type: text/plain");
		ignore_user_abort(true);
		$this->disable_ob();

		echo "<pre>";

		echo "Start updating...\n";

		chdir("../");

		putenv('COMPOSER_HOME=' . getcwd());
		$opts = [
			'http' => [
				'method' => 'GET',
				'header' => [
					'User-Agent: PHP'
				]
			]
		];
		$context = stream_context_create($opts);

		echo "Getting latest release:";

		$latest_release = file_get_contents("https://api.github.com/repos/massimodong/wzoj/releases/latest?access_token=".env('GITHUB_ACCESS_TOKEN'), false, $context);

		$latest_release = json_decode($latest_release);

		echo $latest_release->tag_name."\n";
		echo "Downloading..\n";

		file_put_contents("storage/app/tmpfile.tar.gz", fopen($latest_release->tarball_url, 'r', false, $context));

		echo "Enabling Maintenance Mode\n";
		system("php artisan down");

		echo "Installing...\n";

		$p = new PharData("storage/app/tmpfile.tar.gz");
		$p->decompress();

		$phar = new PharData('storage/app/tmpfile.tar');
		$phar->extractTo('storage/app');
		system('cp -r -a storage/app/massimodong-wzoj-*/. ./');

		system('rm -Rf storage/app/massimodong-wzoj-* 2>&1');
		system('rm -Rf storage/app/tmpfile.tar 2>&1');
		system('rm -Rf storage/app/tmpfile.tar.gz 2>&1');

		system('composer install 2>&1');
		system('php artisan config:cache 2>&1');
		system('php artisan route:cache 2>&1');
		system('php artisan migrate 2>&1');

		echo "Disabling Maintenance Mode\n";
		system("php artisan up");
		echo "</pre>";

		Option::where('name', 'current_version_tag')->update(['value' => $latest_release->tag_name]);
		Option::where('name', 'current_version_id')->update(['value' => $latest_release->id]);
		Cache::tags(['options'])->flush();

		return back();
	}
}
