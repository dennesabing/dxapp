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
function formDuplicateFieldset(selector, label, insertMode)
{
	var count = jQuery(selector).length;
	var id = selector + count;
	var oldId = selector + (count - 1);
	var insertMode = 'first';
	if(insertMode != undefined)
	{
		insertMode = insertMode;
	}
	var rowCloned = jQuery(selector + ':first').clone(true).removeClass(oldId.replace('.','')).addClass(id.replace('.',''));
	if(!label)
	{
		rowCloned.find('label').remove();
	}
	rowCloned.find('input,select,textarea').each(function(i, x){
		var v = jQuery(x);
		v.val('');
		var name = v.attr('name').replace(0,count);
		v.attr('name', name).attr('id', name);
	});
	rowCloned.find('a.anchorCanBeDuplicated').attr('onclick', 'javascript:formDuplicateFieldsetRemove(this)').attr('title','Remove').removeClass('btn-success').addClass('btn-danger').find('i').removeClass('icon-plus').addClass('icon-minus');
	rowCloned.insertAfter(selector + ':first');
}
function formDuplicateFieldsetRemove(selector)
{
	jQuery(selector).closest('fieldset').remove();
}
/**
 * Unresize all text area
 */
jQuery('textarea').css('resize', 'none');
/***FORMS***/