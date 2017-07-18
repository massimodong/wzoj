<?php

namespace App;

use Storage;

class FileManager
{
	static function resolvePath($path){
		$dir_names = [];
		foreach(explode('/', $path) as $part){
			if(empty($part) || $part === '.') continue;
			if($part !== '..'){
				array_push($dir_names, $part);
			}else if(count($dir_names) > 0){
				array_pop($dir_names);
			}
		}
		if(empty($dir_names)) return '/';
		else return '/'.join('/', $dir_names).'/';
	}
	static function getRequests($request, $config){
		if(isset($request->file)){
			$userFile = FileManager::resolvePath($request->file);
			if(Storage::disk($config['disk'])->has($config['basepath'].$userFile)){
				return view('fileManager.readText', [
						'text' => Storage::disk($config['disk'])->get($config['basepath'].$userFile)
				]);
			}else{
				abort(404);
			}
		}else{
			$userPath = FileManager::resolvePath($request->path);
			$directories = Storage::disk($config['disk'])->directories($config['basepath'].$userPath);
			$files = Storage::disk($config['disk'])->files($config['basepath'].$userPath);
			return view('fileManager.index',[
					'config' => $config,
					'userPath' => $userPath,
					'directories' => $directories,
					'files' => $files,
			]);
		}
	}
}
