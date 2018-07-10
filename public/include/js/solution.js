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

	var m = new Number(solution.memory_used / 1024 / 1024);
	$('#' + id + ' .solution-memoryused').text(m.toFixed(2) + 'MB');

	if(solution.judger){
		$('#' + id + ' .solution-judger').text(solution.judger.name);
	}

	$('#' + id + ' .solution-submitted_at').text(solution.created_at.date.split('.')[0]);
}

function solutions_progress(){
	socket.on('solutions:App\\Events\\SolutionUpdated', function(solution){
		var s = $('#solution-' + solution.id);
		if(solution.status >= 4){ //completed judging
			solutions_fill(s, solution);
		}else if(solution.status <=2){ //waiting or compiling
			s.html(TRANS['solution_status_' + solution.status]);
		}else{
			s.data('testcases', solution.testcases);
			s.data('cnttestcases', solution.cnt_testcases);
			solutions_update_progress(s);
		}
	})
}

function solutions_new_update(){
	socket.on('solutions:App\\Events\\NewSolution', function(solution){
		var row = $("<tr class='clickable-row'></tr>");
		row.attr('id', 'tr-' + solution.id);
		row.data('href', '/solutions/' + solution.id);
		row.append("<td>" + solution.id + "</td>");
		row.append("<td>" + solution.user.name + "</td>");
		row.append("<td>" + solution.problem.name + "</td>");

		var soldiv = $("<div></div>");
		soldiv.attr('id', 'solution-' + solution.id);
		soldiv.data('testcases', solution.testcases);
		soldiv.data('cnttestcases', solution.cnt_testcases);
		soldiv.html(TRANS['solution_status_' + solution.status]);
		row.append($("<td></td>").append(soldiv));

		row.append("<td class='solution-timeused'>0ms</td>");
		row.append("<td class='solution-memoryused'>0.00MB</td>");
		row.append("<td>" + LANG[solution.language] + "</td>");
		row.append("<td>" + solution.code_length +"B</td>");
		row.append("<td class='solution-judger'>" + (solution.judger?solution.judger.name:"") + "</td>");
		row.append("<td class='solution-submitted_at'>" + solution.created_at.date.split('.')[0] + "</td>");
		$('#solutions-tbody').prepend(row);
		$(".clickable-row").click(function() {
			window.document.location = $(this).data("href");
		});
	});
}
