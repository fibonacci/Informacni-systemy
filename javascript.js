$(document).ready(function(){
	$('.authors .delete a').click(function(e){
		deleteItem($(this).attr('class'), 'deleteauthor');
		return false;
	});
	
	$('.articles .delete a').click(function(e){
		deleteItem($(this).attr('class'), 'deletearticle');
		return false;
	});
	
	$('input[type=submit]').click(function(){
		var inputs = $('input[type=text], textarea');
		var ok = true;
		$.each(inputs, function(index, input){
			jinput = $(input);
			if(jinput.attr('value')){
				jinput.removeClass('empty');
			} else {
				ok = false;
				jinput.addClass('empty');
			}
		});

		if(!ok){
			alert('Nevyplnili jste všechna pole.');
		}
		
		return ok;
	});
});

function deleteItem(id, page){
	$.get("index.php", { 'page': 'admin', 'section': page, 'id': id} );
	$('.fadeOut_' + id).fadeOut(1000);
}
