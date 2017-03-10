function addAlertSuccess(message){
	$('#alerts').append("<div class='alert alert-success alert-dismissable fade in'>"
		    	    + "<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>"
			    + message
			    + "</div>");
	window.setTimeout(function() { $(".alert-success").alert('close') }, 800);
}

function addAlertWarning(message){
	$("#alerts").append("<div class='alert alert-warning alert-dismissable'>"
			    + "<a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>"
			    + message
			    + "</div>");
}

function alertEach(key, message){
	addAlertWarning(message);
}

function changeCaptcha(){
	document.getElementById('captchaImage').src="/captcha/default?"+Date.now();
}


function sendForm(form){
	var action;
	if(form.attr('action')){
		action = form.attr('action');
	}else{
		action = window.location.pathname;
	}
	$.post(action, form.serialize())
		.done(function( data ){
			addAlertSuccess("操作成功!");
		})
		.fail(function( data ){
			$.each(data.responseJSON, alertEach);
		});
}

function showOrHideCode(){
	if(typeof showOrHideCode.show == 'undefined'){
		showOrHideCode.show = 1;
	}
	showOrHideCode.show = 1 - showOrHideCode.show; //reverse
	if(showOrHideCode.show == 1){
		$('#code_pre').css('display', 'block');
		$('#code_button').html('—');
	}else{
		$('#code_pre').css('display', 'none');
		$('#code_button').html('+');
	}
}

function selectHashTab(){
	var itemName = window.location.href+'-activeTab';
	$(document).ready(function() {
		$(document.body).on("click", "a[data-toggle]", function(event) {
			if(this.getAttribute("href") == '#') return;
			localStorage.setItem(itemName, this.getAttribute("href"));
		});

		var activeTab = localStorage.getItem(itemName);
		if (activeTab) {
			$("a[href='" + activeTab + "']").tab("show");
		}
	});
}

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
		'lengthMenu':[[-1, 10, 100, 500],[TRANS['all'], 10, 100, 500]]
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

function codeDetectLanguage( code ){
	const langs = {
		1: ['include', '{', '}', 'stdio', 'iostream', 'using', ' namespace ', 'int ', 'main(', //cpp
			'cin>>', 'cout<<', 'scanf(', 'printf(', 'return'],
		2: ['var', 'begin', 'end', 'read(', 'write(', 'then', ':='], //pascal
		4: ['from','import ', 'def ', ' in ', 'elif ', 'input(', 'print('] //python
	};
	var cur_lang = -1, cur_pb = 0.1;
	for(var key in langs){
		if(langs.hasOwnProperty(key)){
			//console.log(key + ':');
			var pb = 0, tot = 0;
			for(var i in langs[key]){
				if(code.indexOf(langs[key][i]) != -1){
					++pb;
				}
				++tot;
			}
			pb /= tot;
			//console.log( pb );

			if(pb > cur_pb){
				cur_lang = key;
				cur_pb = pb;
			}
		}
	}
	//console.log('result:' + cur_lang);
	return cur_lang;
}
