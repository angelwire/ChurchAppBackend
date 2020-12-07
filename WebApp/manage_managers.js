var selector;
function finishedLoading()
{
    selector = document.getElementById('filterSelect');
    
    var getValue = document.getElementById('get').getAttribute("value");
    if (getValue != "")
    {
        filterSheetTableValue(getValue);
    }
}

function filterSheetTable()
{
    var eventName = selector.options[selector.selectedIndex].innerHTML;
    var rows = document.getElementsByName("nameColumn");
    
    for(var ii=0; ii<rows.length; ii++)
    {
        var parentRow = rows[ii].parentElement;
        console.log(rows[ii].innerHTML);
        if (rows[ii].innerHTML == eventName || eventName=="--All--" || (eventName=="--No Event--" && rows[ii].innerHTML == ""))
        {
            parentRow.setAttribute("style", "display:table-row");
        } else
        {
            parentRow.setAttribute("style", "display:none");
        }
    }
}

function filterSheetTableName(name)
{
    for (var ii=0; ii<selector.options.length; ii++)
    {
        if (name == selector.options[ii].innerHTML)
        {
            selector.selectedIndex=ii;
        }
    }
    filterSheetTable();
}

function filterSheetTableValue(value)
{
    for (var ii=0; ii<selector.options.length; ii++)
    {
        console.log(value + ":" + selector.options[ii].value);
        if (value == selector.options[ii].value)
        {
            selector.selectedIndex=ii;
        }
    }
    filterSheetTable();
}

function editSheet(sheetId)
{
    location.href = "edit_sign_up_sheet_page.php?sid=" + sheetId;
}

function viewReplies(sheetId)
{
    location.href = "view_replies.php?sid=" + sheetId;
}

function showPopup(element)
{
  var popup = element.children[1];
  popup.classList.toggle("show");
}

function submitForm()
{
	var submissionForm = document.getElementById("submissionForm");
    
	var checkboxArray = document.getElementsByName("accessCheckbox");
    
    addPostToForm(submissionForm, "unl", checkboxArray[0].checked ? "1": "0");
	addPostToForm(submissionForm, "eve", checkboxArray[1].checked ? "1": "0");
	addPostToForm(submissionForm, "she", checkboxArray[2].checked ? "1": "0");
	addPostToForm(submissionForm, "ale", checkboxArray[3].checked ? "1": "0");
	addPostToForm(submissionForm, "inf", checkboxArray[4].checked ? "1": "0");
    
    submissionForm.submit();
}

function addPostToForm(element, name, value)
{
    var node = document.createElement("input");
    node.setAttribute("type","text");
    node.setAttribute("name",name);
    node.setAttribute("value",value);
    node.setAttribute("style","display:none");

    element.appendChild(node);
}

function deleteManager(managerId)
{
	if (confirm("Are you sure you want to delete the manager?"))
	{
		var submissionForm = document.createElement("form");
		submissionForm.setAttribute("action","delete_manager.php");
		submissionForm.setAttribute("method","post");
		submissionForm.setAttribute("enctype","multipart/form-data");

		addPostToForm(submissionForm,"mid",managerId);
		document.body.appendChild(submissionForm);
		submissionForm.submit();
	}
}
