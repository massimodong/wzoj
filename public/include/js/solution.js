function getTestcases( s ){
	return $.get('/ajax/testcases', {solution_id: s.data('id'), last_tid: s.data('t_id')});
}

function getSolution( s ){
	return $.get('/ajax/solution-status', {solution_id: s.data('id')});
}

const WIDTH = '20%';
const TESTCASE_PER_PAGE = 5;

function setLabel(s, pos, label){
	var s_id = s.data('id');
	var e = $(label);
	e.attr('id', s_id+'-'+pos);

	var cur_page = Math.floor(pos / TESTCASE_PER_PAGE);
	if(cur_page > s.data('page')){
		s.html('');
		s.data('page', s.data('page') + 1);
	}

	if($('#'+e.attr('id')).length){
		$('#'+e.attr('id')).attr('class', e.attr('class'));
	}else{
		e.attr('style', 'width: 0%');
		s.append(e);
		setTimeout("$('#" + e.attr('id') + "').attr('style', 'width: " + WIDTH + "');", 50);
	}
}

function animateTestcase(s, testcase){
	//++index;
	s.data('index', s.data('index') + 1);
	s.data('t_id', testcase.id);

	label='';
	switch(testcase.verdict){
		case 'AC':
			label = '<div class="progress-bar progress-bar-success" style="width: ' + WIDTH + '"></div>';
			break;
		case 'WA':
			label = '<div class="progress-bar progress-bar-danger" style="width: ' + WIDTH + '"></div>';
			break;
		case 'TLE':
		case 'MLE':
		case 'RE':
			label = '<div class="progress-bar progress-bar-warning" style="width: ' + WIDTH + '"></div>';
			break;
		default:
			label = '<div class="progress-bar progress-bar-info" style="width: ' + WIDTH +'"></div>';
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

		if(s.data('waiting') == 1){
			s.data('waiting', 0);
			s.attr('class', 'progress');
			s.html('');
		}

		if(typeof ts !== 'undefined' && ts.length > 0){
			for(i=0;i<ts.length;++i){
				animateTestcase(s, ts[i]);
			}
		}else{
			if(st == 3){//still running
				label = '<div class="progress-bar progress-bar-striped" style="width: ' + WIDTH + '"></div>';
				setLabel(s, s.data('index')+1, label);
			}
		}

		if(st == 3){
			setTimeout(work.bind(this, s, done), s.data('wait_time'));
		}else{
			s.attr('class', '');
			s.text(TRANS['solution_status_4']);
			done(s);
		}
	});
}

function animateJudging( s ,done){
	//s.date('id')
	s.data('t_id', 0); //last testcase_id
	s.data('index', -1); //index of current testcase
	s.data('wait_time', 500); //time used by last testcase
	s.data('page', 0); //page
	work(s, done);
}

function fillTable( s ){
	var tr = $('#tr-'+s.data('id')), id=tr.attr('id');
	$.get('/ajax/solution-result', {solution_id: s.data('id')}).done(function(data){
		$('#' + id + ' .solution-score').text(data.score);
		$('#' + id + ' .solution-timeused').text(data.time_used + 'ms');

		var m = new Number(data.memory_used / 1024 / 1024);
		$('#' + id + ' .solution-memoryused').text(m.toFixed(2) + 'MB');

		$('#' + id + ' .solution-judgedat').text(data.judged_at);
	})
}
