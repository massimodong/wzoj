function ranklist_addRow(user){
	if(typeof ranklist_addRow.cnt == 'undefined'){
			ranklist_addRow.cnt = 0;
	}
	ranklist_addRow.cnt++;

	var row = $(user_template);
	row.attr('id', 'user-' + user.id);
	row.attr('data-id', user.id);

	$('#rank-table').append(row)
		.isotope('appended', row);

	$('#user-' + user.id + ' .rank-user').html("<a href='/users/" + user.id + "'>" + escapeHtml(user.name) + "</a>");
	$('#user-' + user.id + ' .rank-fullname').html(escapeHtml(user.fullname));
	$('#user-' + user.id + ' .rank-class').html(escapeHtml(user.class));
	$('#user-' + user.id + ' .rank-score').html(0);

	var indicator = $(indicator_template);
	indicator.children('.rank_num').html('<small>' + ranklist_addRow.cnt + '</small>');
	$('#rank-indicator').append(indicator);
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
		msg = TRANS['compile_error'];
	}else if(solution.status == 4){ //judged
		msg = solution.score;
	}else if(solution.status !=3){ //not running
		msg = TRANS["solution_status_" + solution.status];
	}

	ps.html("<div id='solution-" + solution.id + "'>" + msg + "</div>");
	$('#solution-' + solution.id).data('testcases', solution.testcases);
	$('#solution-' + solution.id).data('cnttestcases', solution.cnt_testcases);

	if(solution.status == 4){
		ranklist_updateScore(row, ps, solution.score);
	}

	if(solution.status == 4) ranklist_setColor(ps);
}

function ranklist_updateSolutions(problemset_id, contest_start_at, contest_end_at){
	socket.on('solutions:App\\Events\\NewSolution', function(solution){
			var submitted_at = solution.created_at.date.split('.')[0];

			if(solution.problemset_id != problemset_id) return;
			if(submitted_at < contest_start_at) return;
			if(submitted_at > contest_end_at) return;

			ranklist_addSolution(solution);
	});
}

function ranklist_setColor(ps){//too hard coloring
	return;
	/*
	var newscore = ps.data('score');
	if(newscore == 100){
		ps.css('background-color', '#88ff88');
	}else if(newscore >= 80){
		ps.css('background-color', '#ddff88');
	}else if(newscore >= 20){
		ps.css('background-color', '#ffff88');
	}else{
		ps.css('background-color', '#ffcccc');
	}
	*/
}

function ranklist_fillCell(s, solution){
	s.attr('class', '');
	if(s.data('ce')){
		s.text(TRANS['compile_error']);
	}else{
		s.text(solution.score);
	}

	var ps = s.parent(), newscore = solution.score;
	ranklist_updateScore(ps.parent(), ps, newscore);
	ranklist_setColor(ps);
}
