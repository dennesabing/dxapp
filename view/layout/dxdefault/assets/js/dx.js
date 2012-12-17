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
function formDuplicateRow(selector, label)
{
	var count = jQuery(selector.replace('#','.')).length;
	var id = selector + count;
	var rowCloned = jQuery(selector).clone(true).attr('id', id.replace('#',''));
	if(!label)
	{
		rowCloned.find('label').remove();
	}
	rowCloned.find('a.canBeDuplicated').attr('href', 'javascript:formDuplicateRowRemove(\''+ id +'\')').attr('title','Remove').removeClass('btn-success').addClass('btn-danger').find('i').removeClass('icon-plus').addClass('icon-minus');
	if(count > 1)
	{
		rowCloned.insertAfter(selector + (count - 1));
	}
	else
	{
		rowCloned.insertAfter(selector);
	}
}

function formDuplicateRowRemove(selector)
{
	jQuery(selector).remove();
}
/**
 * Unresize all text area
 */
jQuery('textarea').css('resize', 'none');
/***FORMS***/