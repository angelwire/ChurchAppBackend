var userID = "-1";
var sheetID = "-1";

function finishedLoading()
{
    console.log("PHP complete");
    
    sheetID = document.getElementsByName("sid")[0].value;
    userID = document.getElementsByName("uid")[0].value;
}

function addPreceedingZero(inNumber)
{
    inNumber = inNumber + "";
    if (inNumber.length == 1)
    {
        inNumber = "0" + inNumber;
    }
    return inNumber;
}

function getParentLevel(element,levels)
{
    while(levels > 0)
    {
        levels-=1;
        element = element.parentElement;
    }
    return element;
}

function submitForm()
{
    //<form action='sign_up.php' method='post' enctype='multipart/form-data'>
    
    var submissionForm = document.createElement("form");
    submissionForm.setAttribute("action","submit_response.php");
    submissionForm.setAttribute("method","post");
    submissionForm.setAttribute("enctype","multipart/form-data");
    
    //Add the sheet ID and user ID to the form
    addPostToForm(submissionForm, "sid", sheetID);
    addPostToForm(submissionForm, "uid", userID);
    
    var responseArray = [];
    
    var answerObjects = document.getElementsByName("answer");
    for (var ii = 0, element; element = answerObjects[ii]; ii++)
    {
        var qValue = 0;
        var aValue = 0;
        
        if (element.type == "text")
        {
            aValue = element.value;
            qValue = element.getAttribute("questionid");
        } else
        if (element.type == "select-one")
        {
            aValue = element.options[element.selectedIndex].text;
            qValue = element.getAttribute("questionid");
        } else
        if (element.getAttribute("type") == "selectmany")
        {
            aValue = [];
            qValue = element.getAttribute("questionid");
            var selectManyArray = document.getElementsByName("selectboxes");
            for (var jj = 0, box; box = selectManyArray[jj]; jj++)
            {
                if (getParentLevel(box,2) === element && box.checked == true)
                {
                    aValue.push(box.value);
                }
            }
        }
        
        responseArray[ii] = {[qValue]:aValue};
    }
	
	
    
    addPostToForm(submissionForm, "qna", JSON.stringify(responseArray));
    
    document.body.appendChild(submissionForm);
    submissionForm.submit();
}

function submitToSupport()
{
    //<form action='sign_up.php' method='post' enctype='multipart/form-data'>
    
    var submissionForm = document.createElement("form");
    submissionForm.setAttribute("action","contact_support.php");
    submissionForm.setAttribute("method","post");
    submissionForm.setAttribute("enctype","multipart/form-data");
    
    var name = document.getElementById("name").value;
    var element = document.getElementById("type");
    var type = element.options[element.selectedIndex].text;;
    var message = document.getElementById("message").value;
    
    addPostToForm(submissionForm, "did", userID);
    addPostToForm(submissionForm, "mes", "Name: " + name + " --- Type: " + type + " --- Message: " + message);
    
    document.body.appendChild(submissionForm);
    submissionForm.submit();
}

function addListResponse(element, questionId)
{
	if (document.getElementsByName("listResponseBox").length < 64)
	{
		var parentElement = getParentLevel(element,1);
		
		var newDiv = document.createElement("div");
		newDiv.style="height:auto;";
		
		var newTextBox = document.createElement("input");
		newTextBox.setAttribute("type","text");
		newTextBox.setAttribute("name","answer");
		newTextBox.setAttribute("questionId",questionId);
		newDiv.appendChild(newTextBox);
		
		var removeButton = document.createElement("button");
		removeButton.innerHTML = "X";
		removeButton.onclick = function(){removeListResponse(this)};
		removeButton.style = "background:rgba(200,0,0,255)";
		newDiv.appendChild(removeButton);
		
		var newLine = document.createElement("br");
		newDiv.appendChild(newLine);
		
		parentElement.insertBefore(newDiv,element);
	}
	else
	{
		alert("No more list responses are allowed, if you need extra space please combine multiple answers into a single box");
	}
}

function removeListResponse(element)
{
	var elementParent = getParentLevel(element,1);
	var beforeNode = element.previousSibling;
	var afterNode = element.nextSibling;
	elementParent.removeChild(element);
	elementParent.removeChild(beforeNode);
	elementParent.removeChild(afterNode);
	getParentLevel(elementParent,1).removeChild(elementParent);
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