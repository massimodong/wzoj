function solutions_add_progress_bar(s, testcase){
	var width = s.data('width');
	label='';
	switch(testcase.verdict){
		case 'AC':
			label = '<div class="progress-bar progress-bar-success"></div>';
			break;
		case 'WA':
			label = '<div class="progress-bar progress-bar-danger"></div>';
			break;
		case 'TLE':
		case 'MLE':
		case 'RE':
			label = '<div class="progress-bar progress-bar-warning"></div>';
			break;
		default:
			label = '<div class="progress-bar progress-bar-info"></div>';
			break;
	}
	var e = $(label);
	e.attr('style', 'width: 0%');
	s.append(e);
	setTimeout(prg, 5);
	function prg(){
		e.attr('style', 'width: ' + width);
	}
}

function solutions_update_progress(s){
	if(typeof s.data('index') == 'undefined'){
		s.attr('class', 'progress');
		s.html('');
		s.data('index', 0);
		s.data('width', 100/s.data('cnttestcases') + '%');
	}

	testcases=s.data('testcases');
	for(i=s.data('index');i<testcases.length;++i){
		solutions_add_progress_bar(s, testcases[i]);
	}

	s.data('index', testcases.length);
}

function solutions_fill(s, solution){
	s.attr('class', '');
	var id='tr-' + solution.id;

	if(solution.ce){
		s.text(TRANS['compile_error']);
	}else{
		s.text(solution.score);
	}
	$('#' + id + ' .solution-timeused').text(solution.time_used + 'ms');
	console.log('#' + id + ' .solution-timeused');

	var m = new Number(solution.memory_used / 1024 / 1024);
	$('#' + id + ' .solution-memoryused').text(m.toFixed(2) + 'MB');

	if(solution.judger){
		$('#' + id + ' .solution-judger').text(solution.judger.name);
	}

	$('#' + id + ' .solution-submitted_at').text(solution.created_at);
}

function solutions_progress(){
	socket.on('solutions:App\\Events\\SolutionUpdated', function(data){
		var solution = data.solution;
		var s = $('#solution-' + solution.id);
		console.log(solution);
		if(solution.status >= 4){ //completed judging
			solutions_fill(s, solution);
		}else if(solution.status <=2){ //waiting or compiling
			s.html(TRANS['solution_status_' + solution.status]);
		}else{
			s.data('testcases', solution.testcases);
			solutions_update_progress(s);
		}
	})
}
