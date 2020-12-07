var formCount = 0;
var lineElementID = 'line';
var lineContentsName = 'lineContents';

var currentYear = (new Date()).getFullYear();
var currentMonth = (new Date()).getMonth();

var nameElement;
var descriptionElement;
var eventElement;

function finishedLoading()
{
    addForm();
    
    var eventSelector = document.createElement("select");
    eventSelector.setAttribute("id","eventSelector");
    var selectorCell = document.getElementById("eventSelectorCell");
    
    selectorCell.appendChild(eventSelector);
    
    eventElement = document.getElementById("eventSelector");
    nameElement = document.getElementById('nameBox');
    descriptionElement = document.getElementById('descriptionBox');
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

function addForm()
{
    var container = document.createElement('eventValue' +formCount);
    container.setAttribute('lineNumber',formCount);
	
    var selectorBoxText = "<div id='eventSelectorCell'> Event the Sign-up Sheet is for: </div><br>";
    var showInMainPageText = "Show in main social page: <input type='checkbox' name='showInMainSocialPage' id='homepageBox'>"+helpText("If this box is checked then the form/sign-up-sheet will show up in the main social page instead of just in the event page.")+"<br><br>";
    var submissionMessageText = "Submission response message:<br><textarea type='textarea' onkeyDown='if(event.keyCode==13) {event.preventDefault();}' name='name' id='messageBox' required></textarea>"+helpText("The message to show the user when he or she completes the sign-up-sheet. Use this to thank users and to remind them of important information")+"<br>";	
	
    var lineName = 'formLine' +formCount;
    var lineContentsID = 'lineContents' +formCount;
    
    var deleteName = 'name' +formCount;
    var labelName = 'table' +formCount;
    var chooserName = 'chooser' +formCount;
	
    var nameBoxText = "Sign-Up Sheet Name:<br><input type='text' onkeyDown='if(event.keyCode==13) {event.preventDefault();}' name='name' id='nameBox' required></input>";
    var descriptionBoxText = "Sign-Up Sheet description:<br><textarea type='textarea' rows='4' cols='20' name='description' id='descriptionBox' required></textarea>";
    var addElementText = "<button type='button' onClick=addFormElement("+formCount+");>Add<br>Field</button>";
    
    container.innerHTML = selectorBoxText + showInMainPageText + submissionMessageText + "<table_div><table border='1' padding='5px' name='"+lineName+"'><tr><th colspan='1'>" +nameBoxText+ "</th><th colspan='1'  style='width:auto'>"+descriptionBoxText+"</th><th>"+addElementText+"</th></tr></table></table_div>";
    document.getElementById('formsBox').appendChild(container);
    
    formCount+=1;
}

function helpText(inString)
{
    return "<div class='popup' onclick='showPopup(this)'><img src='Images/question_icon.png' style='height:1em;'></img><span class='popuptext' id='myPopup'>"+inString+"</span></div>";
}

function addFormElement(lineNumber)
{
    var element = document.getElementsByName("formLine" + lineNumber)[0];
    var row = element.insertRow(-1);
    row.setAttribute("name","questionRow");
    
    var labelCell = row.insertCell(0);
    
    var typeCell = row.insertCell(1);
    //typeCell.setAttribute("colspan","2");
    typeCell.setAttribute("min-width","500px");
    
    var removeCell = row.insertCell(2);
    var removeButton  = document.createElement("button");
    removeButton.innerHTML = "Remove<br>Field";
    removeButton.type="button";
    removeButton.setAttribute("onclick","getParentLevel(this,2).remove();");
    removeCell.appendChild(removeButton);    
    
    var textCommand="if(this.parentElement) {this.parentElement.parentElement.getElementsByTagName('table').remove();}";
    
    labelCell.innerHTML = "Label: <input type='text' onkeyDown='if(event.keyCode==13) {event.preventDefault();}' name='label'></input>";
    
    typeCell.appendChild(document.createTextNode("Type: "));
    var selector = document.createElement("select");
    selector.name="type";
    
    selector.setAttribute("onchange", "changeThings(this,this.selectedIndex);");
    
    var option0 = document.createElement("option");
    option0.text="Text";
    option0.value="t";
    selector.add(option0);
    
    var option1 = document.createElement("option");
    option1.text="Long Text";
    option1.value="tt";
    selector.add(option1);
    
    var option2 = document.createElement("option");
    option2.text="Select One";
    option2.value="s";
    selector.add(option2);
    
    var option3 = document.createElement("option");
    option3.text="Select Multiple";
    option3.value="ss";
    selector.add(option3);
	
	var option4 = document.createElement("option");
    option4.text="Yes/No";
    option4.value="yn";
    selector.add(option4);
	
	var option5 = document.createElement("option");
    option5.text="List";
    option5.value="l";
    selector.add(option5);
    
    typeCell.appendChild(selector);
    
    var optionTable = document.createElement("table");
    
    var addRow = optionTable.insertRow(-1);
    var addCell = addRow.insertCell(-1);
    
    var addOptionButton = document.createElement("button");
    addOptionButton.innerHTML = "Add Option";
    addOptionButton.type="button";
    addOptionButton.setAttribute("onclick","addOption(getParentLevel(this,3))");
    
    addCell.appendChild(addOptionButton);
        
    typeCell.appendChild(optionTable);
    optionTable.width="100%";
    optionTable.style.margin = "3px";
    optionTable.style.display= "none";
    optionTable.setAttribute("name","optionsList");
    
    return row;
}

function changeThings(element, number)
{
    console.log("changing... " + number);
    switch (number)
        {
            case 0: optionSetText(getParentLevel(element,1)); break;//text
            case 1: optionSetText(getParentLevel(element,1)); break;//long text
            case 2: optionSetSelect(getParentLevel(element,1)); break;//select one
            case 3: optionSetSelect(getParentLevel(element,1)); break;//select many
			case 4: optionSetYesNo(getParentLevel(element,1)); break;//yes or no
			case 5: optionSetList(getParentLevel(element,1)); break;//list
        }
}

function optionSetText(element)
{
    var tt = getParentLevel(element,1).getElementsByTagName("table")[0];
    tt.style.display = 'none';
    console.log("text");
}

function optionSetYesNo(element)
{
    var tt = getParentLevel(element,1).getElementsByTagName("table")[0];
    tt.style.display = 'none';
    console.log("yesno");
}

function optionSetList(element)
{
    var tt = getParentLevel(element,1).getElementsByTagName("table")[0];
    tt.style.display = 'none';
    console.log("list");
}

function optionSetSelect(element)
{
    var tt = getParentLevel(element,1).getElementsByTagName("table")[0];
    tt.style.display= "";
    console.log("select");
}

function addOption(element)
{
    console.log("element:")
    console.log(element);
    
    var optionRow = element.insertRow(-1);
    var removeCell = optionRow.insertCell(0);
    var removeButton  = document.createElement("button");
    removeButton.innerHTML = "Remove Option";
    removeButton.type="button";
    removeButton.setAttribute("onclick","getParentLevel(this,2).remove();");
    removeCell.appendChild(removeButton);
    
    var questionRow = getParentLevel(element,3).rowIndex - 1; //Subtract 1 to get the question index
    
    console.log("question row:" + questionRow)
    
    var optionCell = optionRow.insertCell(1);
    optionCell.innerHTML = "Option: <input type='text' onkeyDown='if(event.keyCode==13) {event.preventDefault();}' name='optionLabel"+questionRow+"'></input>";

    //TODO someday: add a limit parameter to limit how many times an option can be selected
    //var limitCell = optionRow.insertCell(2);
    //limitCell.innerHTML = "Limit: <input type='number' onkeyDown='if(event.keyCode==13) {event.preventDefault();}' name='optionLimit"+questionRow+"' style='width:4em;' min='-1'></input>";	
    console.log("THIS IS THE RIGHT FILE!!!");
    return optionRow;
}

function editLineContents(selected, line)
{

}

function removeParent(element)
{
    element.parentElement.remove();
}

function removeParentLevel(element,levels)
{
    getParentLevel(element, levels).remove();
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
    var box = document.getElementById("formsBox");
    var nameBox = document.getElementById("nameBox");
    var descriptionBox = document.getElementById("descriptionBox");
    var eventSelectorObject = document.getElementById("eventSelector");
	var outsideEventBox = document.getElementById("homepageBox");
    var eventText = eventSelectorObject.options[eventSelectorObject.selectedIndex].value;
	var messageBox = document.getElementById("messageBox");
	var homepageBox = document.getElementById("homepageBox");	
	
	var homepageBoxValue = homepageBox.selected ? "1" : "0";
	
	var messageBoxValue = messageBox.value;
	if (messageBoxValue == "")
    {
		alert("No Response Message");
        return "No Response Message";
    }
    
    var nameBoxValue = nameBox.value;
    if (nameBoxValue == "")
    {
		alert("No name");
        return "No Name";
    }
	
	if (nameBoxValue.indexOf('"') != -1)
	{
		alert("Names cannot include quotation marks");
        return "Bad Name";
	}
	
    var descriptionBoxValue = descriptionBox.value;
    if (descriptionBoxValue == "")
    {
		alert("No description");
        return "No Description";
    }
	
	var outsideEventBoxValue = outsideEventBox.checked ? "1" : "0";
    
    var labelText = [];
    var typeText = [];
    var optionsQuestion = [];//contains arrays
    //var optionsLimits = [];
    var questionArray = [];
    questionArray = document.getElementsByName("questionRow");
    
    for (var ii = 0; ii<questionArray.length ;ii++)
    {
        //Where ii is the row
        labelText[ii] = document.getElementsByName("label")[ii].value;
        if (labelText[ii] == "")
        {
            return "Empty Label #" + ii;
        }
        
        var selectorObject = document.getElementsByName("type")[ii]
        typeText[ii] = selectorObject.options[selectorObject.selectedIndex].value;

        
        if (typeText[ii].charAt(0) == "s")
        {
            var optionsArray = document.getElementsByName("optionLabel" + ii);
            var optionsText = [];
            //var limitsArray = document.getElementsByName("optionLimit" + ii);
            //var limitsText = [];
            for (var jj = 0; jj<optionsArray.length; jj++)
            {
                optionsText[jj] = optionsArray[jj].value;
                //limitsText[jj] = limitsArray[jj].value;
                if (optionsText[jj] =="")
                {
                    return "Empty Option Text #" + jj + " from field #" + ii;
                }
            }
            optionsQuestion[ii] = optionsText.toString();
            //optionsLimits[ii] = limitsText.toString();
        }
    }
    
    var submissionForm = document.createElement("form");
    submissionForm.setAttribute("action","create_sign_up_sheet.php");
    submissionForm.setAttribute("method","post");
    submissionForm.setAttribute("enctype","multipart/form-data");
    
    addPostToForm(submissionForm,"pas","noodlesoupofchicken");
    addPostToForm(submissionForm,"nam", nameBoxValue);
    addPostToForm(submissionForm,"des", descriptionBoxValue);
    addPostToForm(submissionForm,"eve", eventText);
	addPostToForm(submissionForm,"oeb", outsideEventBoxValue);	
	addPostToForm(submissionForm,"res", messageBoxValue);		
	addPostToForm(submissionForm,"hom", homepageBoxValue);
    
    var question = [];
    
    for (var ii = 0; ii<questionArray.length ;ii++)
    {
        //Where ii is the row
        //This one has limits that we ain't using
        //question[ii] = {label:labelText[ii], type:typeText[ii], options:optionsQuestion[ii], limits:optionsLimits[ii]};
        question[ii] = {label:labelText[ii], type:typeText[ii], options:optionsQuestion[ii]};
    }
    
	if (question.length > 0)
	{
    addPostToForm(submissionForm, "que", JSON.stringify(question));
	alert(JSON.stringify(question));
    document.body.appendChild(submissionForm);
    submissionForm.submit();
	}
	else
	{
		alert("All sign up sheets must have at least 1 field");
	}
}

function addFilledQuestion(label, type, autofill)
{
    var newRow = addFormElement(0);
    var rowNumber = newRow.rowIndex - 1;
    
    var fillLabel = document.getElementsByName('label')[rowNumber];
    var fillType = document.getElementsByName('type')[rowNumber];
    var fillAuto = document.getElementsByName('autofill')[rowNumber];
    
    fillLabel.setAttribute('value', label);
    var questionTypeIndex = selectSetValue(fillType, type);
    fillAuto.checked = (autofill == 1);
    
    if (questionTypeIndex > 1)
    {
        document.getElementsByName("optionsList")[rowNumber].setAttribute("style", "margin: 3px;");
    }
    
    return newRow;
}

function addFilledOption(fieldNumber, option)
{
    console.log(" ----- New option ----- ");
    console.log("field number: " + fieldNumber);
    
    var optionTable = document.getElementsByName("optionsList")[fieldNumber];
    var optionRow = addOption(optionTable.childNodes[0]); //Passes the inner body of the table
    var optionNumber = optionRow.rowIndex - 1; //Subtract 1 for index

    console.log("option number: " + optionNumber);
    
    var optionLabelBox = document.getElementsByName("optionLabel" + fieldNumber)[optionNumber];
    optionLabelBox.setAttribute("value",option);
    return optionRow;
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

function createEventSelectorOption(id, name)
{
    var newOption = document.createElement("option");
    newOption.text = name;
    newOption.value = id;
    
    var eventSelector = document.getElementById("eventSelector");
    eventSelector.add(newOption);
}

function setFormDetails(name, description, event)
{
    nameElement.setAttribute("value", name);
    descriptionElement.innerHTML = description;
    selectSetValue(eventElement, event);
}

function selectSetValue(element, value)
{
    for(var ii=0; ii< element.options.length; ii++)
    {
        if(element.options[ii].value == value)
        {
            element.selectedIndex = ii;
            return ii;
        }
    }
    return 0;
}

function cancelEditing()
{
    if (confirm("Are you sure you would like to cancel?"))
    {
        window.location = "manage_sign_up_sheets.php";
    }
}

function showPopup(element)
{
  var popup = element.children[1];
  popup.classList.toggle("show");
}