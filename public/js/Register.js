var aError = 
[
	["vp_error", "Vul dit verplichte veld met je ", " in. "],
	["min2_error", "Vul in dit veld je ", " met minimaal 2 karakters in. "],
	["email_error", "Vul een geldige email in"],
	["postcode_error", "Vul een geldige postcode in"],
	["phone_error", "Vul een geldige telefoonnummer in"]
],
aMulitpleSentence = ["zijn", "foutmeldingen"];	
var bSubmit = false;

$(document).ready( function() 
{
	$("input").blur( function() 
	{
		checkInput(this);
		
	});

	$("input").keyup( function() 
	{
		checkInput(this);
		
	});

	$("#form").submit( function( event )
	{
		$(".vp").each(function() 
	 	{
	 		checkInput($(this)); 
	 	});

	 	validateForm();
	});
});

function validateForm()
{
	var ms1, ms2;

	ms1 = $("#message p:first-child");
	ms2 = $("#message p:nth-child(2)");

	ms1.html("");
	ms2.html("");

	if ( $(".error_span").length > 0)
	{
		event.preventDefault();

		if ($(".error_span").length == 1) 
		{
			aMulitpleSentence[0] = "is";
			aMulitpleSentence[1] = "foutmelding";
		}

		if ($(".error_span").length > 0) 
		{
			ms1.html("Er "+ aMulitpleSentence[0] +" nog "+ $(".error_span").length +" "+ aMulitpleSentence[1]+".");		
		}
	}
}	

function checkInput(elm)
{
	if ( !$(elm).val() && $(elm).hasClass('vp')) 
		$(elm).addClass('vp_error')

	else
		$(elm).removeClass('vp_error')

	if($(elm).hasClass('min2') && $(elm).val().length < 2)
		$(elm).addClass('min2_error')

	else
		$(elm).removeClass('min2_error')

	if ($(elm).hasClass('email') && !validateEmail($(elm).val()) )
		$(elm).addClass('email_error')

	else
		$(elm).removeClass('email_error')

	if ($(elm).hasClass('postcode') && !validatePostcode($(elm).val()) )
		$(elm).addClass('postcode_error')

	else
		$(elm).removeClass('postcode_error')

	if ($(elm).hasClass('phone') && !validatePhoneNumber($(elm).val()) )
		$(elm).addClass('phone_error')

	else
		$(elm).removeClass('phone_error')

	set_errors( $(elm) );
}	

function set_errors( el )
{
	var id = "";
	var text;
	
	for (var i = 0; i < aError.length; i++) 
	{
		id = "_"+aError[i][0];	
		if (el.hasClass(aError[i][0])) 
		{				
			text = aError[i][1] +""+ el.attr('placeholder') +""+ aError[i][2];

			if (!( aError[i][0] == "vp_error" || aError[i][0] == "min2_error") )
			{
				text = aError[i][1];
			}

			createSpan(el, id, text);
		}
		else
		{
			removeSpan(el , id);
		}
	}
	console.log("aantal fouten = " + $(".error_span").length)
}

function createSpan(el, id, text)
{
	var span = "";
	if( $("#"+el.attr("id")+""+id).length == 0 )
	{
		span = $("<span>");
		span.attr("id", el.attr("id")+""+id);
		span.addClass('error_span');
		span.css("color", "#DC343B");
		span.html(text);
		span.addClass('error_span');
		el.parent().append( span );
	}

	return span;
}

function removeSpan( el, id )
{
	$("#"+el.attr("id")+""+id).remove();
}


function validateEmail( email ) 
{
	var emailReg = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i;

	if (emailReg.test(email)) 
	{
		return true;
	}

	return false;
}

function validatePostcode( pc )
{
	var regPC = /^[1-9][0-9]{3} ?(?!sa|sd|ss)[a-z]{2}$/i;

	if (regPC.test( pc )) 
	{
		return true;
	}

	return false;
}

function validatePhoneNumber( pn )
{
	var regPhone = /\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/;

	if (regPhone.test( pn )) 
	{
		return true;
	}

	return false;
}