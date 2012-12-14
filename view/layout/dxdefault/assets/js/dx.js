function alertMessage(msg){
	if(console)
	{
		console.log(msg);
	}
}
function dump(msg){
	alertMessage(msg);
}
/***FORMS***/
function formDuplicateRow(row)
{
	var count = jQuery(row.replace('#','.')).length;
	var id = row + count;
	var rowCloned = jQuery(row).clone(true).attr('id', id.replace('#',''));
	rowCloned.find('label').remove();
	rowCloned.find('a.canBeDuplicated').attr('href', 'javascript:formDuplicateRowRemove(\''+ id +'\')').removeClass('btn-success').addClass('btn-danger').find('i').removeClass('icon-plus').addClass('icon-minus');
	if(count > 1)
	{
		rowCloned.insertAfter(row + (count - 1));
	}
	else
	{
		rowCloned.insertAfter(row);
	}
}

function formDuplicateRowRemove(row)
{
	jQuery(row).remove();
}
/**
 * Unresize all text area
 */
jQuery('textarea').css('resize', 'none');
/***FORMS***/