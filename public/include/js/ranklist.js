function ranklist_addRow(user){
	var row = $(user_template);
	row.attr('id', 'user-' + user.id);

	$('#rank-table').append(row)
		.isotope('appended', row);

	$('#user-' + user.id + ' .rank-user').html(user.name);
	$('#user-' + user.id + ' .rank-class').html(user.class);
	$('#user-' + user.id + ' .rank-score').html(0);
}

function ranklist_updateScore(row, ps, newscore){
	if(typeof ps.data('score') == 'undefined'){
		ps.data('score', 0);
	}
	if(typeof row.data('score') == 'undefined'){
		row.data('score', 0);
	}

	row.data('score', row.data('score') - ps.data('score'));
	ps.data('score', newscore);
	row.data('score', row.data('score') + newscore);

	row.children('.rank-score').html(row.data('score'));

	$('#rank-table').isotope('updateSortData').isotope();
}

function ranklist_addSolution(solution){
	var row = $('#user-' + solution.user_id);
	if(row.length == 0){ //user does not exists
		ranklist_addRow(solution.user);
		row = $('#user-' + solution.user_id);
	}

	var ps = row.children('.problem-' + solution.problem_id);
	var msg = '';
	if(solution.ce){
		msg = 'CE';
	}else if(solution.status == 4){ //judged
		msg = solution.score;
	}else if(solution.status !=3){ //not running
		msg = TRANS["solution_status_" + solution.status];
	}

	ps.html("<div id='solution-" + solution.id + "' class='judging-solution' data-id='" + solution.id + "' data-waiting='1'>" +msg + "</div>");

	if(solution.status == 4){
		ranklist_updateScore(row, ps, solution.score);
	}else{
		ranklist_updateScore(row, ps, 0);
	}
}

function ranklist_updateSolutions(problemset_id, last_solution_id){
	$.get('/ajax/problemset-solutions',{
		problemset_id: problemset_id,
		top: last_solution_id
	})
	.done(function(data){
		$.each(data.solutions, function(key, solution){
			ranklist_addSolution(solution);
			last_solution_id = solution.id;
		})
		setTimeout(ranklist_updateSolutions.bind(this, problemset_id, last_solution_id), 500);
	});
}

function ranklist_fillCell(s){
	s.attr('class', '');
	if(s.data('ce')){
		s.text('CE');
	}else{
		s.text(s.data('score'));
	}

	ranklist_updateScore(s.parent().parent(), s.parent(), s.data('score'));
}
