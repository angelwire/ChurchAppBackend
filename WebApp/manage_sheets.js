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