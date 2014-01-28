$(document).ready(function() {
	
	$(".close").click( function() { $('.notice').slideUp('slow') } )
	setTimeout(function () {
	   $('.notice').slideUp('slow');
	}, 4000);
	
	//Autoselect
	$(".cycles ul li:first").trigger('click');
	$(".matieres ul li:first").trigger('click');
});

function tabnav( tab ) {
			tab.bind('keydown', function(event) {
			  if(event.keyCode == 9){ //for tab key
				   $(this).blur();
				   $(this).parents('tr').next().find('span.com > input').focus();
			    return false; 
			  }
			});
}

function tabnavi( tab ) {
			tab.bind('keydown', function(event) {
			  if(event.keyCode == 9){ //for tab key
				   $(this).blur();
				   var e = $(this).parents('tr').next().find('span.inf > button')
				   e.focus()
				   e.trigger("click");
			    return false; 
			  }
			});
}


function flashmsg(message) {
    $('body').prepend('<div id="flash" style="display:none"></div>');
    $('#flash').html(message);
    $('#flash').toggleClass('flashmsg');
    $('#flash').fadeIn('fast');
    $('#flash').fadeOut(3000,function(){$('#flash').remove()} );
}


