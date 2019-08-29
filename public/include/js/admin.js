const TRANS = {
	solution_status_0: '等待评测',
	solution_status_1: '等待重测',
	solution_status_2: '正在编译',
	solution_status_3: '正在运行',
	solution_status_4: '评测完成',
	solution_status_5: '取消',
	compile_error: '编译错误',
	all: '全部',
	days: '天',
	hours: '小时',
	minutes: '分钟',
	seconds: '秒',
	cnt_solutions: '提交数',
	estimate_time: '预计时间',
	confirm_rejudge: '确认重测',
	problem_type_1: '传统题',
	problem_type_2: '交互题',
	problem_type_3: '提交答案题',
	testdata: '测试数据',
}

const LANG = ['C', 'C++', 'Pascal'];

function updateDataTableSelectAllCtrl(table){
	var $table             = table.table().node();
	var $chkbox_all        = $('tbody input[type="checkbox"]', $table);
	var $chkbox_checked    = $('tbody input[type="checkbox"]:checked', $table);
	var chkbox_select_all  = $('thead input[name="select_all"]', $table).get(0);

	// If none of the checkboxes are checked
	if($chkbox_checked.length === 0){
		chkbox_select_all.checked = false;
		if('indeterminate' in chkbox_select_all){
			chkbox_select_all.indeterminate = false;
		}

		// If all of the checkboxes are checked
	} else if ($chkbox_checked.length === $chkbox_all.length){
		chkbox_select_all.checked = true;
		if('indeterminate' in chkbox_select_all){
			chkbox_select_all.indeterminate = false;
		}

		// If some of the checkboxes are checked
	} else {
		chkbox_select_all.checked = true;
		if('indeterminate' in chkbox_select_all){
			chkbox_select_all.indeterminate = true;
		}
	}
}

function createDatatableWithCheckboxs(table_id, rows_selected, form_id){
	var table = $('#'+table_id).DataTable({
		'columnDefs': [{
			'targets': 0,
			'searchable': false,
			'orderable': false,
			'width': '1%',
			'className': 'dt-body-center',
			'render': function (data, type, full, meta){
				return '<input type="checkbox">';
			}
			}],
		'order': [[1, 'asc']],
		'rowCallback': function(row, data, dataIndex){
			// Get row ID
			var rowId = data[1];

			// If row ID is in the list of selected row IDs
			if($.inArray(rowId, rows_selected) !== -1){
				$(row).find('input[type="checkbox"]').prop('checked', true);
				$(row).addClass('selected');
			}
		},
		'autoWidth': false,
		'lengthMenu':[[100, 500, -1],[100, 500, TRANS['all']]]
	});

	// Handle click on checkbox
	$('#'+table_id+' tbody').on('click', 'input[type="checkbox"]', function(e){
		var $row = $(this).closest('tr');

		// Get row data
		var data = table.row($row).data();

		// Get row ID
		var rowId = data[1];

		// Determine whether row ID is in the list of selected row IDs
		var index = $.inArray(rowId, rows_selected);

		// If checkbox is checked and row ID is not in list of selected row IDs
		if(this.checked && index === -1){
			rows_selected.push(rowId);

			// Otherwise, if checkbox is not checked and row ID is in list of selected row IDs
		} else if (!this.checked && index !== -1){
			rows_selected.splice(index, 1);
		}

		if(this.checked){
			$row.addClass('selected');
		} else {
			$row.removeClass('selected');
		}

		// Update state of "Select all" control
		updateDataTableSelectAllCtrl(table);

		// Prevent click event from propagating to parent
		e.stopPropagation();
	});

	// Handle click on table cells with checkboxes
	$('#'+table_id).on('click', 'tbody td, thead th:first-child', function(e){
		$(this).parent().find('input[type="checkbox"]').trigger('click');
	});

	// Handle click on "Select all" control
	$('thead input[name="select_all"]', table.table().container()).on('click', function(e){
		if(this.checked){
			$('#'+table_id+' tbody input[type="checkbox"]:not(:checked)').trigger('click');
		} else {
			$('#'+table_id+' tbody input[type="checkbox"]:checked').trigger('click');
		}

		// Prevent click event from propagating to parent
		e.stopPropagation();
	});

	// Handle table draw event
	table.on('draw', function(){
		// Update state of "Select all" control
		updateDataTableSelectAllCtrl(table);
	});

	// Handle form submission event
	$('#'+form_id).on('submit', function(e){
		var form = this;

		// Iterate over all selected checkboxes
		$.each(rows_selected, function(index, rowId){
			// Create a hidden element
			$(form).append(
					$('<input>')
					.attr('type', 'hidden')
					.attr('name', 'id[]')
					.val(rowId)
				      );
		});
	});
}

function sec2text( sec ){
	var ret = "";
	var flag = false;
	if(sec >= 86400){//days
		ret += ~~(sec / 86400) + TRANS['days'];
		sec %= 86400;
		flag = true;
	}

	if(flag || sec >= 3600){//hours
		if(flag) ret += ' ';
		ret += ~~(sec / 3600) + TRANS['hours'];
		sec %= 3600;
		flag = true;
	}

	if(flag || sec > 60){//minutes
		if(flag) ret += ' ';
		ret += ~~(sec / 60) + TRANS['minutes'];
		sec %= 60;
		flag = true;
	}

	if(flag) ret += ' ';
	ret += ~~sec + TRANS['seconds'];
	return ret;
}

function ms2text( ms ){
	return sec2text(ms / 1000);
}
