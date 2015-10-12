SWIFT.Library.BaseFreshbooksAdmin = SWIFT.Base.extend({

	/**
	 * Function called on click of veryfy connection button
	 */
	TestConnectionButtonClick: function () {


		SWIFT.Freshbooks.AdminObject.set('url',  jQuery("#freshbooks_apiUrl").val());
		SWIFT.Freshbooks.AdminObject.set('token',  jQuery("#freshbooks_authenticationToken").val());

		var _finalDispatchValue = 'apiurl=' + escape(SWIFT.Freshbooks.AdminObject.get("url")) + '&apitoken=' + escape(SWIFT.Freshbooks.AdminObject.get("token"));
		var _finalDispatchValueBASE64 = Base64.encode(_finalDispatchValue);
		UIStartLoading();
		UICreateWindow(_baseName + "/Freshbooks/AjaxRequest/VerifyConnection/" + _finalDispatchValueBASE64 , "verifycon", 'Verify Connection', "", 500, 300, true);
	},

	/**
	 * Function called on click of ok button of dialouge box
	 */
	ConnectionButtonOk: function () {
		var project = "<option value='0'>None</option>";

		SWIFT.Freshbooks.AdminObject.set('url',  jQuery("#freshbooks_apiUrl").val());
		SWIFT.Freshbooks.AdminObject.set('token',  jQuery("#freshbooks_authenticationToken").val());

		$.ajax({
			type:"POST",
			url: _baseName+'/Freshbooks/AjaxRequest/GetProjects',
			data: { apiurl: SWIFT.Freshbooks.AdminObject.get("url"), apitoken: SWIFT.Freshbooks.AdminObject.get("token") },
			dataType: 'json',
			beforeSend: function () { SWIFT.Freshbooks.AdminObject.addLoader('#freshbooks_testconnection'); },
			complete: function () { SWIFT.Freshbooks.AdminObject.removeLoader(jQuery('#freshbooks_testconnection')); },
			success: function(data) {
				$.each(data['projects'], function(index, projectData)
				{
					if (index == "project")
					{
						if (projectData.length > 1)
						{
							$.each(projectData, function(key, val)
							{
								if (val['project_id'] == SWIFT.Freshbooks.AdminObject.get("freshbooks_default_project")) {
									project += '<option value='+val['project_id']+' selected="selected">'+val['name']+'</option>';
								} else {
									project += '<option value='+val['project_id']+'>'+val['name']+'</option>';
								}
							});
						} else {
							project += '<option value='+projectData['project_id']+'>'+projectData['name']+'</option>';
						}
					}
				});

				$('#freshbooks_default_project').html(project);

				if ($('#freshbooks_default_project').val()) {
					jQuery("#freshbooks_default_project").trigger('change');
				}
			}
		});
	},

	/**
	 * Function called on change of default project
	 */
	DefaultProjectChange: function () {
		var task = "<option value='0'>None</option>";

		$.ajax({
			type:"POST",
			url: _baseName+'/Freshbooks/AjaxRequest/ProjectTask',
			data: { apiurl: SWIFT.Freshbooks.AdminObject.get("url"), apitoken: SWIFT.Freshbooks.AdminObject.get("token"), projectid: $("#freshbooks_default_project").val()},
			dataType: 'json',
			beforeSend: function (){ SWIFT.Freshbooks.AdminObject.addLoader('#freshbooks_default_task'); },
			complete: function (){ SWIFT.Freshbooks.AdminObject.removeLoader(jQuery('#freshbooks_default_task')); },
			success: function(data) {
				if (data == SWIFT.Freshbooks.AdminObject.get("request_fail_error") || data == SWIFT.Freshbooks.AdminObject.get("invalid_data_error"))
				{
					task = "<option value='0'>Please selct project first</option>";
				}
				else
				{
					$.each(data['tasks'], function(index, taskData)
					{
						if (index == "task")
						{
							if (taskData.length > 1)
							{
								$.each(taskData, function(key, val)
								{
									if (val['task_id'] == SWIFT.Freshbooks.AdminObject.get('freshbooks_default_task')) {
										task += '<option value='+val['task_id']+' selected="selected">'+val['name']+'</option>';
									} else {
										task += '<option value='+val['task_id']+'>'+val['name']+'</option>';
									}
								});
							} else {
								task += '<option value='+taskData['task_id']+'>'+taskData['name']+'</option>';
							}
						}
					});

					$('#freshbooks_default_task').html(task);
				}
			}
		});
	},

	/**
	 * Adds loader to the id passed in parameter
	 */
	addLoader: function (ide) {
		var freshbooks_wait = SWIFT.Freshbooks.AdminObject.get("freshbooks_wait");
		jQuery(ide).after('<div style="display:inline-block; margin-left:5px;" class="fbloader">&nbsp;</div>');
		jQuery(".fbloader").html('<img src="' + themepath + '/images/loadingcircle.gif"/>');
		jQuery(ide).html('<option>'+freshbooks_wait+'</option>');
		return SWIFT.Freshbooks.StaffObject;
	},

	/**
	 * Removes the loader from the object passed in parameter
	 */
	removeLoader: function (thisObj)
	{
		thisObj.next().remove('.fbloader');
	}
});

SWIFT.Freshbooks = {};
SWIFT.Freshbooks.AdminObject = SWIFT.Library.BaseFreshbooksAdmin.create();
