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

	public function postUpdate(Request $request){
    if(!($request->hasFile('pkg') && $request->file('pkg')->isValid())){
      if(!$request->hasFile('pkg')) print("file not found<br>");
      if(!$request->file('pkg')->isValid()) print("file not valid<br>");
      return "Error!!"; //TODO
    }

    logAction('admin_update_system', [], LOG_SEVERE);

		//header("Content-type: text/plain");
		ignore_user_abort(true);
		$this->disable_ob();

		echo "<pre>";

		echo "Start updating...\n";

		chdir("../");

		putenv('COMPOSER_HOME=' . getcwd());

    $file = $request->file('pkg');
		file_put_contents("storage/app/tmpfile.tar.gz", fopen($file->getRealPath(), "r"));

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

		system('composer update 2>&1');
		system('php artisan config:cache 2>&1');
		system('php artisan route:cache 2>&1');
		system('php artisan migrate 2>&1');
		\Redis::command('flushall');

		echo "Disabling Maintenance Mode\n";
		system("php artisan up");
		echo "</pre>";

		Option::where('name', 'current_version_tag')->update(['value' => $request->version_tag]);
		Option::where('name', 'current_version_id')->update(['value' => $request->version_id]);
		Cache::tags(['options'])->flush();

		return back();
	}
}
