var counter = 0;
function $(objectID)
{
    //~ alert(document.getElementById(objectID));
    return document.getElementById(objectID);
}

function d$(objectID) {
    return dijit.byId(objectID);
}
function EditItem(task, itemID, userID) {
    var form = getForm(task, itemID, userID);
        
    // Create counter
    var dialog = new dijit.Dialog({
            id: 'dialog',
            // Dialog title
            title: form.title,
            // Create Dialog content
            content: form.content,
            onCancel: function(evt){
                closeDialog('dialog');
            },
    });
    dialog.show();
}

function closeDialog(dialogID) {
    d$(dialogID).hide();
    dojo.hitch(d$(dialogID), setTimeout(function(){
                d$(dialogID).destroyRecursive();
            }, 300));
}

function getForm(task, itemID, userID) {        
    var return_form = {
        title: "",        
        content: ""
    }

    switch(task) {
        case 'editpwa':
            return_form.title = "Edit PWA";
            break;
        case 'edittask':
            return_form.title = "Edit Task";
            break;
        case 'editproject':
            return_form.title = "Edit Project";
            break;
        default:
            return_form.title = "Edit Something";
            break;
    }
    
    dojo.xhrGet({
            url: "./utils/"+task+".php",
            handleAs: "text",
            preventCache: true,
            sync:true,
            content: {
                id: itemID,
                userid : userID
            },
            load: function(data) {
                //Replace newlines with nice HTML tags.
                return_form.content = data;
            },
            error: function(error) {
                alert("An unexpected error occurred: " + error);
                return;
            }
    });
    
    return return_form;    
}

function showReportSettings(reportID) {
    var settings_content = "";
    dojo.xhrGet({
            url: "./utils/report_settings.php",
            handleAs: "text",
            preventCache: true,
            sync:true,
            content: {
                reportName: reportID
            },
            load: function(data) {
                //Replace newlines with nice HTML tags.
                settings_content = data;
            },
            error: function(error) {
                alert("An unexpected error occurred: " + error);
                return;
            }
    });

     // Create counter
    var dialog = new dijit.Dialog({
            id: 'dialog',
            // Dialog title
            title: "Report Settings",
            // Create Dialog content
            content: settings_content,
            onCancel: function(evt){
                closeDialog('dialog');
            },
    });
    dialog.show();
}

function showWaitDialog(show) {
    if(show) {
        var dialog = new dijit.Dialog({
                id: 'wait_dialog',
                // Dialog title
                title: "Waiting for update ...",
                // Create Dialog content
                content: '<b>Waiting for data update</b>',
                onCancel: function(evt){
                    closeDialog('wait_dialog');
                }
        });
        dialog.show();        
    } else {
        setTimeout(function() {closeDialog('wait_dialog');}, 500);
    }
}


function getReport(params) {
    var report_contents = "";
    var timeframe = d$('week_week');
    dojo.xhrGet({
            url: "./utils/report_data.php",
            handleAs: "text",
            preventCache: true,
            sync:true,
            content: params,
            load: function(data) {
                //Replace newlines with nice HTML tags.
                report_contents = data;
            },
            error: function(error) {
                alert("An unexpected error occurred: " + error);
                return;
            }
    });
    
    $('report_contents').innerHTML = report_contents;
    closeDialog('dialog');
}

function switchCharged(charged, taskID) {
    showWaitDialog(true);
    dojo.xhrGet({
            url: "index.php",
            handleAs: "text",
            preventCache: true,
            sync:true,
            content: {
                a: 'pwa',
                t: 'charge',
                value: charged,
                id: taskID
            },
            load: function(data) {
                //Replace newlines with nice HTML tags.
                location.reload();
            },
            error: function(error) {
                alert("An unexpected error occurred: " + error);
                showWaitDialog(false);
                return;
            }
    });
}

function DeletePWA(pwaID) {
    showWaitDialog(true);
    dojo.xhrGet({
            url: "index.php",
            handleAs: "text",
            preventCache: true,
            sync:true,
            content: {
                a: 'pwa',
                t: 'delete',
                id: pwaID
            },
            load: function(data) {
                //Replace newlines with nice HTML tags.
                location.reload();
            },
            error: function(error) {
                alert("An unexpected error occurred: " + error);
                showWaitDialog(false);
                return;
            }
    });
}

function SetProjectActive(projectID, active){
	showWaitDialog(true);
    dojo.xhrGet({
            url: "index.php",
            handleAs: "text",
            preventCache: true,
            sync:true,
            content: {
                a: 'projects',
                t: 'active',
                value: active,
                id: projectID
            },
            load: function(data) {
                //Replace newlines with nice HTML tags.
                location.reload();
            },
            error: function(error) {
                alert("An unexpected error occurred: " + error);
                showWaitDialog(false);
                return;
            }
    });
}

function projectMenu(params) {
    pMenu = new dijit.Menu({
        targetNodeIds: ["project_" + params["projectID"] + "_" + params["itemID"]]
    });

    pMenu.addChild(new dijit.MenuItem({
        label: "Edit Project",
        onClick: function() {
        	document.location = "?a=projects&t=edit&id="+params["projectID"];
        }
    }));
    
    pActive = new dijit.Menu();
    
    pActive.addChild(new dijit.MenuItem({
    	label: 'YES',
    	onClick: function() {
    		SetProjectActive(params["projectID"], true);
        }
    }));
    
    pActive.addChild(new dijit.MenuItem({
    	label: 'NO',
    	onClick: function() {
    		SetProjectActive(params["projectID"], false);
        }
    }));
    
    pMenu.addChild(new dijit.PopupMenuItem({
        label: "Active",
        popup: pActive
    }));

    pMenu.addChild(new dijit.MenuSeparator());
    
    pMenu.addChild(new dijit.MenuItem({
        label: "Projects Page",
        onClick: function() {
            document.location = "?a=projects";
        }
    }));
    pMenu.startup();
}

function pwaMenu(params) {
	pMenu = new dijit.Menu({
        targetNodeIds: ["pwa_" + params["pwaID"]]
    });
    
   
    pMenu.addChild(new dijit.MenuItem({
        label: "Edit PWA",
        onClick: function() {
            //EditItem('edittask', params["taskID"]);
        	document.location = "?a=pwa&t=edit&id="+params["pwaID"];
        }
    }));

    pMenu.addChild(new dijit.MenuItem({
        label: "Delete PWA",
        onClick: function() {
            if(confirm("Do you really want to delete this PWA?")) {
            	DeletePWA(params["pwaID"]);
            }
        }
    }));
    pMenu.addChild(new dijit.MenuSeparator());
    
    pMenu.addChild(new dijit.MenuItem({
        label: "Switch Charge",
        onClick: function() {
        	switchCharged(params["charged"], params["pwaID"]);
        }
    }));
    pMenu.startup();
}

function SetTaskCutover(taskID, active){
	showWaitDialog(true);
    dojo.xhrGet({
            url: "index.php",
            handleAs: "text",
            preventCache: true,
            sync:true,
            content: {
                a: 'tasks',
                t: 'cutover',
                value: active,
                id: taskID
            },
            load: function(data) {
                //Replace newlines with nice HTML tags.
                location.reload();
            },
            error: function(error) {
                alert("An unexpected error occurred: " + error);
                showWaitDialog(false);
                return;
            }
    });
}

function SetTaskStatus(taskID, status){
	showWaitDialog(true);
    dojo.xhrGet({
            url: "index.php",
            handleAs: "text",
            preventCache: true,
            sync:true,
            content: {
                a: 'tasks',
                t: 'status',
                value: status,
                id: taskID
            },
            load: function(data) {
                //Replace newlines with nice HTML tags.
                location.reload();
            },
            error: function(error) {
                alert("An unexpected error occurred: " + error);
                showWaitDialog(false);
                return;
            }
    });
}
function taskMenu(params) {
    pMenu = new dijit.Menu({
        targetNodeIds: ["task_" + params["taskID"] + "_" + params["itemID"]]
    });
    
   
    pMenu.addChild(new dijit.MenuItem({
        label: "Edit Task",
        onClick: function() {
        	document.location = "?a=tasks&t=edit&id="+params["taskID"];
        }
    }));

    pStatus = new dijit.Menu();
    
    pStatus.addChild(new dijit.MenuItem({
    	label: 'Analysis',
    	onClick: function() {
            SetTaskStatus(params["taskID"], 'Analysis');
        }
    }));
    
    pStatus.addChild(new dijit.MenuItem({
    	label: 'In Progress',
    	onClick: function() {
    		SetTaskStatus(params["taskID"], 'In Progress');
        }
    }));
    
    pStatus.addChild(new dijit.MenuItem({
    	label: 'TDS Pending',
    	onClick: function() {
    		SetTaskStatus(params["taskID"], 'TDS Pending');
        }
    }));
    
    pStatus.addChild(new dijit.MenuItem({
    	label: 'TIMS Pending',
    	onClick: function() {
    		SetTaskStatus(params["taskID"], 'TIMS Pending');
        }
    }));
    
    pStatus.addChild(new dijit.MenuItem({
    	label: 'Pending',
    	onClick: function() {
    		SetTaskStatus(params["taskID"], 'Pending');
        }
    }));
    
    pStatus.addChild(new dijit.MenuItem({
    	label: 'Waiting for...',
    	onClick: function() {
    		SetTaskStatus(params["taskID"], 'Waiting for...');
        }
    }));
    
    pStatus.addChild(new dijit.MenuItem({
    	label: 'Closed',
    	onClick: function() {
    		SetTaskStatus(params["taskID"], 'Closed');
        }
    }));

    pMenu.addChild(new dijit.PopupMenuItem({
        label: "Change Status",
        popup: pStatus
    }));
	
    pCutover = new dijit.Menu();
    
    pCutover.addChild(new dijit.MenuItem({
    	label: 'YES',
    	onClick: function() {
            SetTaskCutover(params["taskID"], "Y");
        }
    }));
    
    pCutover.addChild(new dijit.MenuItem({
    	label: 'NO',
    	onClick: function() {
    		SetTaskCutover(params["taskID"], "N");
        }
    }));
    
    pMenu.addChild(new dijit.PopupMenuItem({
        label: "Cut Over",
        popup: pCutover
    }));
    pMenu.addChild(new dijit.MenuSeparator());
    
    
    pMenu.addChild(new dijit.MenuItem({
        label: "Tasks Page",
        onClick: function() {
            document.location = "?a=tasks";
        }
    }));
    pMenu.startup();
}