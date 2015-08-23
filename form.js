function sendmail() {

	var fields_ok = true;

	// get values
	var email = document.querySelector("#email").value.trim();
	var subject = document.querySelector("#subject").value.trim();
	var message = document.querySelector("#message").value.trim();

	
	// get handles for error messages
	var hint_email = document.querySelector("#hint_email");
	var hint_subject = document.querySelector("#hint_subject");
	var hint_message = document.querySelector("#hint_message");

	
	// make sure all fields are filled in 
	if(email.length == 0) {
		hint_email.style.display = "inline";
		fields_ok = false;
	} else {
		hint_email.style.display = "none";
	}

	if(subject.length == 0) {
		hint_subject.style.display = "inline";
		fields_ok = false;
	} else {
		hint_subject.style.display = "none";
	}

	if(message.length == 0) {
		hint_message.style.display = "inline";
		fields_ok = false;
	} else {
		hint_message.style.display = "none";
	}
	
	
	// if everything is ok, post it to the server
	if(fields_ok) {
	
		// hide form and show loading icon
		document.querySelector("#contact_form").style.display = "none";
		document.querySelector("#loading").style.display = "block";
	
		// prepare data for POST
		var data = "email=" + encodeURIComponent(email)
				 + "&subject=" + encodeURIComponent(subject)
				 + "&message=" + encodeURIComponent(message);
		
		// create and execute AJAX request
		var request = new XMLHttpRequest();
		request.onreadystatechange = function()	{
			
			if (request.readyState == 4 && request.status == 200) {
			
				// log returned data so that we can inspect it 
				console.log(request.responseText);

				// hide loading icon
				document.querySelector("#loading").style.display = "none";
				
				// show sucess or error message
				var response = JSON.parse(request.responseText);
				if(response.status == "ok")
				{
					document.querySelector("#confirmation").style.display = "block";
				}
				else
				{
					document.querySelector("#errmessage").style.display = "block";
				}
			}
			
		}
		request.open("POST", "sendmail.php", true);
		request.setRequestHeader("Content-type","application/x-www-form-urlencoded");
		request.send(data);	
	}
}

function reset() {
	document.querySelector("#confirmation").style.display = "none";
	document.querySelector("#errmessage").style.display = "none";
	document.querySelector("#loading").style.display = "none";
	document.querySelector("#contact_form").style.display = "block";
	document.querySelector("#email").value = "";
	document.querySelector("#subject").value = "";
	document.querySelector("#message").value = "";
}
