var formCount = 0;
var lineElementID = 'line';
var lineContentsName = 'lineContents';

var currentYear = (new Date()).getFullYear();
var currentMonth = (new Date()).getMonth();

function finishedLoading()
{
    document.getElementsByName('year1')[0].setAttribute("min", currentYear);
    document.getElementsByName('year1')[0].setAttribute("value", currentYear);
    document.getElementsByName('year2')[0].setAttribute("min", currentYear);
    document.getElementsByName('year2')[0].setAttribute("value", currentYear);
    
    document.getElementsByName('month1')[0].options[currentMonth].selected = true;
    document.getElementsByName('month2')[0].options[currentMonth].selected = true;
}


function copyEvent()
{
    document.getElementsByName('year2')[0].value=document.getElementsByName('year1')[0].value;
    document.getElementsByName('month2')[0].value=document.getElementsByName('month1')[0].value;
    document.getElementsByName('day2')[0].value=document.getElementsByName('day1')[0].value;
    document.getElementsByName('hour2')[0].value=document.getElementsByName('hour1')[0].value;
    document.getElementsByName('minute2')[0].value=document.getElementsByName('minute1')[0].value;
    document.getElementsByName('m2')[0].value=document.getElementsByName('m1')[0].value;
}

function updateBeginDate()
{
    var yearObject = document.getElementsByName('year1')[0];
    var monthObject = document.getElementsByName('month1')[0];
    var dayObject = document.getElementsByName('day1')[0];
    var maxDays = getDaysInMonth(yearObject.value, monthObject.selectedIndex + 1);
    
    dayObject.setAttribute('max', maxDays);
    
    var dateValue = '';
    dateValue = document.getElementsByName('year1')[0].value + ":"
    + document.getElementsByName('month1')[0].value + ":"
    + formatTime(document.getElementsByName('day1')[0].value,
                document.getElementsByName('hour1')[0].value,
                document.getElementsByName('minute1')[0].value,
                document.getElementsByName('m1')[0].value);
                
    document.getElementsByName('dab')[0].value = dateValue;
}

function updateEndDate()
{
    var yearObject = document.getElementsByName('year2')[0];
    var monthObject = document.getElementsByName('month2')[0];
    var dayObject = document.getElementsByName('day2')[0];
    var maxDays = getDaysInMonth(yearObject.value, monthObject.selectedIndex + 1);
    
    dayObject.setAttribute('max', maxDays);
    
    var dateValue = '';
    dateValue = document.getElementsByName('year2')[0].value + ":"
    + document.getElementsByName('month2')[0].value + ":"
    + formatTime(document.getElementsByName('day2')[0].value,
                document.getElementsByName('hour2')[0].value,
                document.getElementsByName('minute2')[0].value,
                document.getElementsByName('m2')[0].value);
                
    document.getElementsByName('dae')[0].value = dateValue;
	console.log("New end date value: " + dateValue);
}

function getDaysInMonth (year, month) {
    year = parseInt(year);
    month = parseInt(month)
    if (isNaN(year) || isNaN(month) )
    {
        return 31;
    }
    else
    {
        return new Date(year, month, 0).getDate();
    }
}


function formatTime(inDay, inHour, inMinute, inM)
{
    var returnTime = addPreceedingZero(inDay) + ":";
    
    if (inM == "pm")
    {
		inHour = (parseInt(inHour) % 12) + 12;
    }
    else
    {
        inHour = parseInt(inHour) % 12;
    }
    returnTime = returnTime + addPreceedingZero(inHour) + ":" + addPreceedingZero(inMinute);    
    return returnTime;
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
    document.getElementById('addElement').innerHTML='Add Another Sign-Up Sheet';
    var container = document.createElement('eventValue' +formCount);
    container.setAttribute('lineNumber',formCount);
    
    var lineName = 'formLine' +formCount;
    var lineContentsID = 'lineContents' +formCount;
    
    var deleteName = 'name' +formCount;
    var labelName = 'table' +formCount;
    var chooserName = 'chooser' +formCount;
    
    var deleteBoxText = "<button type='button' onclick='removeParentLevel(this,6);'>Remove<br>Sign-Up Sheet</button>";
    var nameBoxText = "Sign-Up Sheet Name:<br><input type='text' onkeyDown='if(event.keyCode==13) {event.preventDefault();}' name='name'"+lineName+"></input>";    
    var typeBoxText = "Sign-Up Sheet:<br><select name='type'"+lineName+">";
    typeBoxText = typeBoxText + "<option value='registration'>Registration</option>";
    typeBoxText = typeBoxText +  "<option value='rsvp'>RSVP</option>";
    typeBoxText = typeBoxText +  "<option value='volunteer'>Volunteer</option>";
    typeBoxText = typeBoxText +  "<option value='application'>Application</option></select>";
    var descriptionBoxText = "Sign-Up Sheet description:<br><textarea type='textarea' rows='4' cols='40' name='description'"+lineName+"></textarea>";
    var addElementText = "<button type='button' onClick=addFormElement("+formCount+");>Add<br>Element</button>";
    
    container.innerHTML = "<table_div><table border='1' padding='5px' name='"+lineName+"'><tr><th>"+deleteBoxText+"</th><th>" +nameBoxText+ "</th><th>"+typeBoxText+"</th><th>"+descriptionBoxText+"</th><th>"+addElementText+"</th></tr></table></table_div>";
    document.getElementById('formsBox').appendChild(container);
    formCount+=1;
}

function addFormElement(lineNumber)
{
    console.log(lineNumber);
    
    var element = document.getElementsByName("formLine" + lineNumber)[0];
    var row = element.insertRow(-1);
    
    var removeCell = row.insertCell(0);
    var removeButton  = document.createElement("button");
    removeButton.innerHTML = "Remove<br>Element";
    removeButton.type="button";
    removeButton.setAttribute("onclick","getParentLevel(this,2).remove();");
    removeCell.appendChild(removeButton);
    
    var autofillCell = row.insertCell(1);
    var labelCell = row.insertCell(2);
    var typeCell = row.insertCell(3);
    typeCell.setAttribute("colspan","2");
    
    var textCommand="if(this.parentElement) {this.parentElement.parentElement.getElementsByTagName('table').remove();}";
    
    autofillCell.innerHTML = "Allow Autofill: <input type='checkbox' value='autofill' name='autofill"+lineNumber+"'>";
    labelCell.innerHTML = "Label: <input type='text' onkeyDown='if(event.keyCode==13) {event.preventDefault();}' name='label"+lineNumber+"'></input>";
    
    typeCell.appendChild(document.createTextNode("Type: "));
    var selector = document.createElement("select");
    selector.name="type"+lineNumber;
    
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
    //typeCell.innerHTML = "Type: <select name='type"+lineNumber+"' onChange='typeChange(this)'><option value='t'>Text</option><option value='tt'>Long Text</option><option value='s'>Select One</option><option value='ss'>Select Multiple</option></select><table name='optionsTable"+lineNumber+"'><tr><th>"+addOptionText+"</th></tr></table>";
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
        }
}

function optionSetText(element)
{
    var tt = getParentLevel(element,1).getElementsByTagName("table")[0];
    tt.style.display = 'none';
    console.log("text");
}

function optionSetSelect(element)
{
    var tt = getParentLevel(element,1).getElementsByTagName("table")[0];
    tt.style.display= "";
    console.log("select");
}

function addOption(element)
{
    console.log(element);
    var optionRow = element.insertRow(element.rows.length-1);
    var removeCell = optionRow.insertCell(0);
    var removeButton  = document.createElement("button");
    removeButton.innerHTML = "Remove Option";
    removeButton.type="button";
    removeButton.setAttribute("onclick","getParentLevel(this,2).remove();");
    removeCell.appendChild(removeButton);
    
    var optionCell = optionRow.insertCell(1);
    optionCell.innerHTML = "Option: <input type='text' onkeyDown='if(event.keyCode==13) {event.preventDefault();}' name='option'></input>";
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

function goToSignUpPage(number)
{
    location.href = "manage_sign_up_sheets.php?sig=" + number;
}

function showPopup(element)
{
  var popup = element.children[1];
  popup.classList.toggle("show");
}

function updateCharacterCount(element)
{
	var count = element.value.length;
	var counter = document.getElementById('characterCount');
	counter.innerHTML = "(" + count + ")";
}

function testAddress()
{
	var address = document.getElementById('addressField').value.split(" ").join("+");
	var url='https://www.google.com/maps/search/' + address;
	window.open(url,"_blank");
}

function checkChurch(element)
{
	var addressElement = document.getElementById("addressField");
	if (element.value == "Church")
	{
		addressElement.value = "7770 North Whirlpool Drive Sperry Oklahoma, Cornerstone Church";
	}
}