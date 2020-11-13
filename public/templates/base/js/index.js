$(document).ready(function() {

	load_add_task_form();
	auth_form();

	$(window).resize(function() {});

});





load_add_task_form = function()
{
	$('#add_task button').on({
		click: function() {
			
			$.post(PREF+'/index/get_form_task_add', {}, function(data) {
				history.pushState(null, null, '/');
				$('#body').html(data);
				$('input[name=name]').focus();
				task_save();
			});

		}
	});
};


task_save = function()
{
	$('#task_save button').on({
		click: function() {
			var data = $('#task-add-block').serializeArray();

			$.post(PREF+'/index/save_task_add', data, function(data) {
				$('#body').html(data);
				load_add_task_form();
			});

		}
	});
};


auth_form = function()
{
	$('#login').on({
		click: function() {
			
			$.post(PREF+'/index/get_form_login', {}, function(data) {
				history.pushState(null, null, '/');
				$('#body').html(data);
				$('input[name=login]').focus();
				login();
			});

		}
	});
};


login = function()
{
	$('#task_save button').on({
		click: function() {
			var data = $('#auth-block').serializeArray();

			$.post(PREF+'/index/login', data, function(data) {
				$('#body').html(data);
				auth_form();
			});

		}
	});
};