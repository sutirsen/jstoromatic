$(document).ready(function(){

	//Data Table initialization
    $('.userListDataTable').DataTable({
    	"columnDefs": [
    	    { "orderable": false, "targets": [0,-1], "searchable": false }
    	  ],
    	"order": []
    });

    $('.genericDataTable').DataTable({
    	"columnDefs": [
    	    { "orderable": false, "targets": [0,-1], "searchable": false }
    	  ],
    	"order": []
    });

    $('#page-wrapper div.row:first').after(msgSect);

    $('.chosen-select').chosen();

});

function getCheckBoxs(className)
{
	var allIds = "";
	$('input:checkbox.'+className).each(function () {
		if(this.checked)
		{
			allIds += $(this).val()+",";
		}
	});
	if(allIds == "")
	{
		return false;
	}
	else
	{
		allIds = allIds.substring(0, allIds.length - 1);
		return allIds;
	}
}

function deleteChecked(formName, idHolder)
{
	var ids = getCheckBoxs("selectable");
	if(!ids)
	{	
		bootbox.alert("<b>Please select some items to delete.</b>");
	}
	else
	{
		bootbox.confirm("Are you sure? This is irreversible", function(result) {
			if(result == true)
			{
				//bootbox.alert(ids);	
				$('#ids').val(ids);
				$("#"+formName).submit();
			}  
		});
		
	}
}