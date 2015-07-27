
/* 
* Function to add and display Loader....
*/

(function ($, window, document, undefined) {
	$.fn.somefunction = function(parameters){
			return $('.dimmer').prepend('<div id="preloader"><div class="loader"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div></div>');
}
})(jQuery, window , document);

$('body').append('<div class="dimmer" />');
$('body .dimmer').somefunction('Please Wait...');


window.setTimeout(function(){
	$('body .dimmer').remove();
},1000);


$(document).on('click','.startscrape',function(e){
	e.preventDefault();
	if($(this).hasClass('btn-primary')){

		$('.btn-info').attr('disabled',true);
		$('.remove').attr('disabled',true);
		$('.removeall').attr('disabled',true);
		$(this).removeClass('btn-primary').addClass('stopper').html('Stop');
		// var $type = $('.vtype').val();
		$.ajax({
			url:'api/start.php',
			// data:{type:$type},
			success:function(a){
				console.log('requested scraping');
			}
		});
	}
});

$(document).on('click','.stopper',function(e){
	e.preventDefault();
	$(this).removeClass('stopper').addClass('btn-primary').html('Start');;
	$.ajax({
		url:'api/stop.php',
		success:function(a){
			console.log('requested scraping');
		}
	});
	$('.btn-info').attr('disabled',false);
	$('.remove').attr('disabled',false);
	$('.removeall').attr('disabled',false);
	window.location.reload();
});


$(document).on('click','.export',function(e){
	e.preventDefault();

	var link = document.createElement('a');
    var downloadType = $('input[name="exporttype"]').prop('checked');
    console.log(downloadType);
	if(downloadType)
	    link.href = "api/export.php?current";
	else
		link.href = "api/print_excel.php?current";
    link.target = '_blank';
    document.body.appendChild(link);
    link.click();
    $(link).remove();
});

$(document).on('click','.exportall',function(e){
	e.preventDefault();
	var link = document.createElement('a');
	var downloadType = $('input[name="exporttype"]').prop('checked');
    if(downloadType)
	    link.href = "api/export.php?current";
	else
		link.href = "api/print_excel.php";
    link.target = '_blank';
    document.body.appendChild(link);
    link.click();
    $(link).remove();
});

$(document).on('click','.exportfrom',function(e){
	e.preventDefault();
	var link = document.createElement('a');
	var downloadType = $('input[name="exporttype"]').prop('checked');
	if(downloadType)
	    link.href = "api/export.php"+"?fromDate="+$('.fromdateval').val();
	else
		link.href = "api/print_excel.php"+"?fromDate="+$('.fromdateval').val();
    link.target = '_blank';
    document.body.appendChild(link);
    link.click();
    $(link).remove();
});

$(document).on('click','.remove',function(e){
	e.preventDefault();
	if(!$(this).prop('disabled'))
	{
		$.ajax({
			url:'api/remove.php',
			success:function(a){
				alert("Today's database was deleted successfully");
			}
		});
	}
});


$(document).on('click','.removeall',function(e){
	e.preventDefault();
	if(!$(this).prop('disabled'))
	{
		$.ajax({
			url:'api/removeall.php',
			success:function(a){
				alert("All the databases was deleted successfully");
			}
		});
	}
});

$(document).on('click','.login',function(e){
	e.preventDefault();
	if(!$(this).prop('disabled'))
	{
		$.ajax({
			url:'api/login.php',
			type:'POST',
			data:{pass:$('.password').val(),user:$('.username').val()},
			success:function(a){
				alert("Login was successfull");
			}
		});
	}
});


var checkScriptSatatus = '';
$(document).ready(function(){

	setInterval(function(){
		$.ajax({
			url:'api/logs.php',
			success:function(a){
				if(checkScriptSatatus.length != a.length && checkScriptSatatus.length != ''){
					checkScriptSatatus = a;
					$('.btn-info').attr('disabled',true);
					$('.remove').attr('disabled',true);
					$('.removeall').attr('disabled',true);
					$('.btn-primary').removeClass('btn-primary').addClass('stopper').html('Stop');
				}
				else{
					$('.btn-info').attr('disabled',false);
				}
				
				$('.logs').html(a);
			}
		});
	}, 1400);
});