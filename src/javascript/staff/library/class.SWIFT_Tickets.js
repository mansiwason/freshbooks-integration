SWIFT.Library.Tickets = SWIFT.Base.extend({
	/**
	* Get list of tasks corresponding to project id passed in parameter
	*/
	GetTaskList: function (_projectId) {
		var task = "";

		jQuery.ajax({
			type: "POST",
			url: _baseName+'/Freshbooks/AjaxRequest/Index',
			data: { action: 'ProjectTask', projectid: _projectId },
			dataType: 'json',
			beforeSend: function () { SWIFT.Freshbooks.StaffObject.addLoader('#selectbillingfbtask'); },
			complete: function () { SWIFT.Freshbooks.StaffObject.removeLoader(jQuery('#selectbillingfbtask')); },
			success: function(data) {
				if (data == "fail")
				{
					jQuery('#response').html("Connection Failed");
				}
				else
				{
					jQuery.each(data['tasks'], function(index, taskData) {
						if (index == "task")
						{
							if (taskData.length > 1)
							{
								jQuery.each(taskData, function(key, val)
								{
									// Check for default task set in admin settings
									if (parseInt(val['task_id']) ==  SWIFT.Freshbooks.StaffObject.get("freshbooks_default_task"))
									{
										task += '<option selected="selected" value='+val['task_id']+'>'+val['name']+'</option>';
									} else {
										task += '<option value='+val['task_id']+'>'+val['name']+'</option>';
									}
								});
							} else {
								task += '<option value='+taskData['task_id']+'>'+taskData['name']+'</option>';
							}
						}
					});

					jQuery('#selectbillingfbtask').html(task);
				}
			}
		});
	},

	/**
	* Get list of tasks corresponding to project id passed in parameter
	*/
	GetUserList: function () {
		if(parseInt(jQuery("#selectbillingfbinvoice").val()))
		{
			var users = "<option value='0'>None</option>";

			jQuery.ajax({
				type: "POST",
				url: _baseName+'/Freshbooks/AjaxRequest/Index',
				data: { action: "UserList" },
				dataType: 'json',
				beforeSend: function () { SWIFT.Freshbooks.StaffObject.addLoader('#selectbillingfbuser'); },
				complete: function () { SWIFT.Freshbooks.StaffObject.removeLoader(jQuery('#selectbillingfbuser')); },
				success: function(data) {
					if (data == "fail")
					{
						jQuery('#response').html("Connection Failed");
					} else {
						jQuery.each(data['staff_members'], function(key1, val1) {
							if (key1 == "member")
							{
								if (val1.length > 1)
								{
									jQuery.each(val1, function(key2, val2)
									{
										// Check for user name
										if (val2.last_name.toLowerCase() ==  SWIFT.Freshbooks.StaffObject.get("user_last_name") || val2.first_name.toLowerCase() == SWIFT.Freshbooks.StaffObject.get("user_first_name")) {
											users += '<option value='+val2.staff_id+' selected="selected">'+val2.first_name+' '+val2.last_name+'</option>';
										} else {
											users += '<option value='+val2.staff_id+'>'+val2.first_name+' '+val2.last_name+'</option>';
										}

									});
								} else {
									if (val1.last_name.toLowerCase() ==  SWIFT.Freshbooks.StaffObject.get("user_last_name") || val1.first_name.toLowerCase() == SWIFT.Freshbooks.StaffObject.get("user_first_name")) {
										users += '<option value='+val1.staff_id+' selected="selected">'+val1.first_name+' '+val1.last_name+'</option>';
									} else {
										users += '<option value='+val1.staff_id+'>'+val1.first_name+' '+val1.last_name+'</option>';
									}
								}
							}
						});

						jQuery('#selectbillingfbuser').html(users);

						if (parseInt(jQuery('#selectbillingfbuser').val())) {
							SWIFT.Freshbooks.StaffObject.GetProjectList();
						}

					}
				}
			});
		}
	},

	/**
	* Get project list corresponding to the user selected
	*/
	GetProjectList: function () {
		if(parseInt(jQuery("#selectbillingfbinvoice").val()))
		{
			var project = "";

			jQuery.ajax({
				type: "POST",
				url: _baseName+'/Freshbooks/AjaxRequest/Index',
				data: { action: 'ProjectsList', staffId:jQuery("#selectbillingfbuser").val() },
				dataType: 'json',
				beforeSend: function () { SWIFT.Freshbooks.StaffObject.addLoader('#selectbillingfbproject'); },
				complete: function () { SWIFT.Freshbooks.StaffObject.removeLoader(jQuery('#selectbillingfbproject')); },
				success: function(data) {
					if (data == "fail")
					{
						jQuery('#response').html("Connection Failed");
					}
					else
					{
						jQuery('#response').html("Connection ok, please select the default project");

						jQuery.each(data['projects'], function(index, projectData) {
							if (index == "project") {
								if (projectData.length > 1) {
									jQuery.each(projectData, function(key, val)
									{
										// Check for default project set in admin settings
										if (parseInt(val['project_id']) ==  SWIFT.Freshbooks.StaffObject.get("freshbooks_default_project"))
										{
											project += '<option selected="selected" value='+val['project_id']+'>'+val['name']+'</option>';
										} else {
											project += '<option value='+val['project_id']+'>'+val['name']+'</option>';
										}
									});
								} else {
									project += '<option value='+projectData['project_id']+'>'+projectData['name']+'</option>';
								}
							}
						});

						jQuery('#selectbillingfbproject').html(project);

						SWIFT.Freshbooks.StaffObject.GetTaskList(jQuery("#selectbillingfbproject").val());
					}
				}
			});
		}
	},

	/**
	* Disable/Enable freshbooks fields
	*/
	EnableDisableFbFields: function () {

		if(parseInt($("#selectbillingfbinvoice").val()))
		{
			jQuery("#selectbillingfbuser, #selectbillingfbproject, #selectbillingfbtask").removeAttr('disabled');
			//jQuery("#billingnotes").val(jQuery("#billingnotes").val()+" "+ SWIFT.Freshbooks.StaffObject.get("freshbooks_defaultNote"));
		}
		else
		{
			jQuery("#selectbillingfbuser, #selectbillingfbproject, #selectbillingfbtask").attr('disabled', '1');
			//jQuery("#billingnotes").val(jQuery("#billingnotes").val().substring(0, jQuery("#billingnotes").val().indexOf(SWIFT.Freshbooks.StaffObject.get("freshbooks_defaultNote"))));
		}
	},

	/**
	 * Adds loader to the id passed in parameter
	 */
	addLoader: function(ide){
		var freshbooks_wait = SWIFT.Freshbooks.StaffObject.get("freshbooks_wait");
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
SWIFT.Freshbooks.StaffObject = SWIFT.Library.Tickets.create();