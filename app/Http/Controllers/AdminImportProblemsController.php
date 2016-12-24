<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Problem;
use App\Solution;

class AdminImportProblemsController extends Controller
{
	public function getImportProblems(){
		return view('admin.import_problems');
	}

	public function postImportProblems(Request $request){
		$this->validate($request, [
			'fps' => 'required|mimes:xml',
		]);
		$xml=simplexml_load_file($request->file('fps')->getRealPath(), 'SimpleXMLElement', LIBXML_PARSEHUGE)
			or die("Error: Cannot create object");
		$nodes = $xml->xpath ( "/fps/item" );

		foreach($nodes as $node){
			//create problem
			$problem = new Problem;

			$problem->name = (string)$node->title;
			$problem->type = 1; // problem type not supported in fps
			$problem->spj  = (bool)$node->spj;
			$problem->description = (string)$node->description;
			$problem->inputformat = (string)$node->input;
			$problem->outputformat = (string)$node->output;
			$problem->sampleinput = (string)$node->sample_input;
			$problem->sampleoutput = (string)$node->sample_output;
			$problem->hint = (string)$node->hint;
			$problem->source = (string)$node->source;
			$problem->timelimit = (int)$node->time_limit;
			if((string)$node->time_limit['unit'] == 's') $problem->timelimit *= 1000;
			$problem->memorylimit = (double)$node->memory_limit;
			if((string)$node->memory_limit['unit'] == 'kb') $problem->memorylimit /= 1024;

			$problem->save();

			//copy data
			$data_dir = '/'.$problem->id.'/';
			$test_no = 0;

			foreach($node->children()->test_input as $testinput){
				$data_path = $data_dir.'data'.$test_no++.'.in';
				Storage::disk('data')->put($data_path, $testinput);
			}
			$test_no = 0;
			foreach($node->children()->test_output as $testoutput){
				$data_path = $data_dir.'data'.$test_no++.'.ans';
				Storage::disk('data')->put($data_path, $testoutput);
			}


			//copy AC codes
			foreach($node->children()->solution as $code){
				$solution = new Solution;
				$language = (string)$code['language'];
				$lang = -1;
				switch($language){
					case 'C':
						$lang = 0;
						break;
					case 'C++':
						$lang = 1;
						break;
					case 'Pascal':
						$lang = 2;
						break;
					default:
						$lang = -1;
						break;
				}
				if($lang < 0){ //unsupported language
					continue;
				}

				$solution->user_id = $request->user()->id;
				$solution->problem_id = $problem->id;
				$solution->problemset_id = -1; //no problemset
				$solution->language = $lang;
				$solution->code = (string)$code;
				$solution->code_length = strlen((string)$code);

				$solution->save();
			}
		}
		//Storage::disk('data')->put('/233/temp.txt', "233333");
		echo "<br><br>";
	}

}
