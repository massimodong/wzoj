<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Storage;

class AdminDatabaseBackupController extends Controller
{
	public function getBackups(){
		$files = Storage::disk('backup')->files('database');
		$backups = [];
		$tot_size = 0;
		foreach($files as $file){
			$item = [
				'time' => pathinfo($file, PATHINFO_FILENAME),
				'size' => Storage::disk('backup')->size($file),
			];
			array_push($backups, $item);
			$tot_size += $item['size'];
		}

		usort($backups, function($a, $b){
			return $a['time'] > $b['time'];
		});

		return [
			'backups' => $backups,
			'tot_size' => $tot_size,
		];
	}
	public function getIndex(){
		$meta = $this->getBackups();
		return view('admin/database_backup', [
			'backups' => $meta['backups'],
			'tot_size' => $meta['tot_size'],
		]);
	}

	public function postRestrictSize(){
		$meta = $this->getBackups();

		$tot_size = $meta['tot_size'];
		$limit = ojoption('database_size_limit') * 1024 * 1024 * 1024;

		foreach($meta['backups'] as $backup){
			if($tot_size <= $limit) break;
			$tot_size -= $backup['size'];
			Storage::disk('backup')->delete('database/'.$backup['time'].'.sql');
		}

		return back();
	}

	public function deleteBackup(Request $request){
		Storage::disk('backup')->delete('database/'.$request->delete_backup_id.'.sql');
		return back();
	}
}
