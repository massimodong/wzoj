function getTestcases( s ){
	return $.get('/ajax/testcases', {solution_id: s.data('id'), last_tid: s.data('t_id')});
}

function getSolution( s ){
	return $.get('/ajax/solution-status', {solution_id: s.data('id')});
}


function setLabel(s, pos, label){
	var s_id = s.data('id');
	var e = $(label);
	var width = s.data('width');
	e.attr('id', s_id+'-'+pos);

	//todo
	/*
	if(cur_page > s.data('page')){
		s.html('');
		s.data('page', s.data('page') + 1);
	}
	*/

	if($('#'+e.attr('id')).length){
		$('#'+e.attr('id')).attr('class', e.attr('class'));
	}else{
		e.attr('style', 'width: 0%');
		s.append(e);
		setTimeout("$('#" + e.attr('id') + "').attr('style', 'width: " + width + "');", 50);
	}
}

function animateTestcase(s, testcase){
	//++index;
	s.data('index', s.data('index') + 1);
	s.data('t_id', testcase.id);

	var width = s.data('width');

	label='';
	switch(testcase.verdict){
		case 'AC':
			label = '<div class="progress-bar progress-bar-success" style="width: ' + width + '"></div>';
			break;
		case 'WA':
			label = '<div class="progress-bar progress-bar-danger" style="width: ' + width + '"></div>';
			break;
		case 'TLE':
		case 'MLE':
		case 'RE':
			label = '<div class="progress-bar progress-bar-warning" style="width: ' + width + '"></div>';
			break;
		default:
			label = '<div class="progress-bar progress-bar-info" style="width: ' + width +'"></div>';
			break;
	}
	setLabel(s, s.data('index'), label);
	s.data('wait_time', testcase.time_used);
}

function work( s, done){
	$.when(getTestcases(s), getSolution(s)).done(function(testcases, solution){
		ts = testcases[0].testcases;
		st = solution[0].status;

		if(st < 3){
			s.text(TRANS['solution_status_'+st]);
			setTimeout(work.bind(this, s, done), s.data('wait_time'));
			return;
		}

		//is now running
		if(typeof s.data('running') == 'undefined' || s.data('running') == 0){
			s.data('running', 1);
			s.data('width', 100/testcases[0].cnt_testcases + '%');
			s.html('');
			s.attr('class', 'progress');
		}

		if(typeof ts !== 'undefined' && ts.length > 0){
			for(i=0;i<ts.length;++i){
				animateTestcase(s, ts[i]);
			}
		}

		if(s.length == 0){
			return;
		}else if(st == 3){
			setTimeout(work.bind(this, s, done), s.data('wait_time'));
		}else{
			//finish running
			if(s.data('running') == 1){
				s.data('running', 0);
				setTimeout(done.bind(this, s), 300);
			}

			if(solution[0].ce){
				s.data('ce', true);
			}
			s.data('score', solution[0].score);

			if(typeof s.data('waiting') == 'undefined' || s.data('waiting') == 0){
				s.data('waiting', 1);
			}
		}
	});
}

function animateJudging( s ,done){
	//s.date('id')
	s.data('t_id', 0); //last testcase_id
	s.data('index', -1); //index of current testcase
	s.data('wait_time', 200); //time used by last testcase
	s.data('score', 0);//score
	s.data('ce', false);//compile error
	work(s, done);
}

function updatePendings( finish ){
	$.get('/ajax/solutions-judging').done(function(data){
		$.each(data.solutions, function(key, value){
			var id = value.id, td = $('#solution-'+id);
			if(td.length && td.data('waiting') == 1){
				td.data('waiting', 0);
				animateJudging(td, finish);
			}
		});
		setTimeout(updatePendings.bind(this, finish), 500);
	});
}

function fillTable( s ){
	var tr = $('#tr-'+s.data('id')), id=tr.attr('id');
	s.attr('class', '');
	s.text(TRANS['solution_status_4']);
	$.get('/ajax/solution-result', {solution_id: s.data('id')}).done(function(data){
		if(s.data('ce')){
			$('#' + id + ' .solution-score').text(TRANS['compile_error']);
		}else{
			$('#' + id + ' .solution-score').text(data.score);
		}
		$('#' + id + ' .solution-timeused').text(data.time_used + 'ms');

		var m = new Number(data.memory_used / 1024 / 1024);
		$('#' + id + ' .solution-memoryused').text(m.toFixed(2) + 'MB');

		if(data.judger){
			$('#' + id + ' .solution-judger').text(data.judger.fullname);
		}

		$('#' + id + ' .solution-judgedat').text(data.judged_at);
	})
}

function solutions_update(last_solution_id){
	if(last_solution_id < 0) return;
	$.get('/ajax/solutions', {
		top: last_solution_id
	})
	.done(function(data){
		$.each(data.solutions, function(key, solution){
			var row = $("<tr class='clickable-row'></tr>");
			row.attr('id', 'tr-' + solution.id);
			row.data('href', '/solutions/' + solution.id);
			row.append("<td>" + solution.id + "</td>");
			row.append("<td>" + solution.user.name + "</td>");
			row.append("<td>" + solution.problem.name + "</td>");

			var soldiv = $("<div class='judging-solution'></div>");
			soldiv.attr('id', 'solution-' + solution.id);
			soldiv.data('id', solution.id);
			soldiv.data('waiting', 1);
			if(solution.status != 3){//not running
				soldiv.append(TRANS["solution_status_" + solution.status]);
			}

			row.append($("<td></td>").append(soldiv));
			if(solution.ce){
				row.append("<td class='solution-score'>" + TRANS['compile_error'] + "</td>");
			}else{
				row.append("<td class='solution-score'>" + solution.score + "</td>");
			}
			row.append("<td class='solution-timeused'>" + solution.time_used + "ms</td>");

			var m = new Number(solution.memory_used / 1024 / 1024);
			row.append("<td class='solution-memoryused'>" + m.toFixed(2) + "MB</td>");

			row.append("<td>" + LANG[solution.language] + "</td>");
			row.append("<td>" + solution.code_length +"B</td>");

			row.append("<td class='solution-judger'>" + (solution.judger?solution.judger.name:"") + "</td>");
			row.append("<td class='solution-judgedat'>" + solution.judged_at + "</td>");

			$('#solutions-tbody').prepend(row);

			last_solution_id = solution.id;
		});
		$(".clickable-row").click(function() {
			window.document.location = $(this).data("href");
		});
		setTimeout(solutions_update.bind(this, last_solution_id), 500);
	});
}
