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
		else return '/'.join('/', $dir_names);
	}
	static function downloadFiles($disk, $basePath, $userPath, $files,$name){
		$zip = new ZipArchive;
		$zip_name = time().'.zip';
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
        $path = Storage::disk($config['disk'])->getAdapter()->getPathPrefix().'/'.$config['basepath'].$userFile;
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $path);
        finfo_close($finfo);

        return response(Storage::disk($config['disk'])->get($config['basepath'].$userFile), 200)->header('Content-Type', $mime);

			}else{
				abort(404);
			}
		}else{
			$userPath = FileManager::resolvePath($request->path);
			$directories = Storage::disk($config['disk'])->directories($config['basepath'].$userPath);
			$files = Storage::disk($config['disk'])->files($config['basepath'].$userPath);
			$can_modify = isset($config['modify']) && $config['modify'];
			return view('fileManager.index',[
					'config' => $config,
					'userPath' => $userPath,
					'directories' => $directories,
					'files' => $files,
					'can_modify' => $can_modify,
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
	
	static function storeFiles($disk, $basePath, $userPath, $files){
		foreach($files as $file){
			$name=basename($file->getClientOriginalName());
			Storage::disk($disk)->put($basePath.$userPath.$name, file_get_contents($file->getRealPath()));
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
			case 'upload':
				FileManager::storeFiles($config['disk'],
							$config['basepath'],
							$path,
							$request->file('files'));
				return back();
			default:
				abort(403);
				return;
		}
	}
}
