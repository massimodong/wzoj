<?php

namespace App;

use Storage;
use ZipArchive;

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
	static function downloadFiles($disk, $basePath, $userPath, $files,$name){
		$zip = new ZipArchive;
		$zip_name = $name.'.zip';
		$zip_file = tempnam("","");
		$zip->open($zip_file, ZipArchive::CREATE);

		foreach($files as $file){
			$file = basename($file);
			if(in_array($basePath.$userPath.$file,Storage::disk($disk)->directories($basePath.$userPath), true)){
				//zip all files in directory
				$pre_length = strlen(FileManager::resolvePath($basePath.$userPath))-1;
				foreach(Storage::disk($disk)->allFiles($basePath.$userPath.$file) as $sub_file){
					$zip->addFromString(substr($sub_file, $pre_length), Storage::disk($disk)->get($sub_file));
				}
			}else if(Storage::disk($disk)->has($basePath.$userPath.$file)){
				//zip file
				$zip->addFromString($file, Storage::disk($disk)->get($basePath.$userPath.$file));
			}
		}
		$zip->close();
		return response()->download($zip_file, $zip_name)->deleteFileAfterSend(true);
	}
	static function getRequests($request, $config){
		if(isset($request->action)){
			switch($request->action){
				case 'download':
					return FileManager::downloadFiles($config['disk'],
									  $config['basepath'],
									  FileManager::resolvePath($request->path),
									  $request->id,
									  $config['title']);
				default:
					abort(403);
			}
		}else if(isset($request->file)){
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

	static function deleteFiles($disk, $basePath, $userPath, $files){
		foreach($files as $file){
			$file = basename($file);
			if(in_array($basePath.$userPath.$file,Storage::disk($disk)->directories($basePath.$userPath), true)){
				Storage::disk($disk)->deleteDirectory($basePath.$userPath.$file);
			}else if(Storage::disk($disk)->has($basePath.$userPath.$file)){
				Storage::disk($disk)->delete($basePath.$userPath.$file);
			}
		}
	}

	static function postRequests($request, $config){
		$path = FileManager::resolvePath($request->path);
		switch($request->action){
			case 'delete':
				FileManager::deleteFiles($config['disk'],
							 $config['basepath'],
							 $path,
							 $request->id);
				return back();
			default:
				abort(403);
				return;
		}
	}
}
