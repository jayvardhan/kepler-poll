jQuery(document).ready(function($) {

	// choice field repeater
	var addChoice = $('.kepler-choice-btn');
	var choiceWrapper = $('.kepler-choice-wrapper');

	addChoice.on('click', function(e){
		var el = document.createElement('input');
		el.type = 'text';
		el.className = 'form-control kepler-add-choice';
		el.setAttribute('placeholder', 'Choice');
		//console.log('choice added from plugin js');
		choiceWrapper.append(el);	
	});	


	//event handler to create new poll 
	var poll 	 = $('.kepler-poll-form');
	var url 	 = poll.attr("data-url");
	var token 	 = poll.attr("data-token"); 
	var redirect = poll.attr("data-redirect");

	var result 	 = $('.kepler-poll-result');

	var createPoll = $('.kepler-create-poll');
	createPoll.on('click', function(e){
		e.preventDefault();

		var question = $('.kepler-add-question');

		if(question.val() === ""){
			result.html("<h5>Give question for the Poll!</h5>");
			result.css('display', 'block');
			return;
		}


		var choiceElements = $('.kepler-add-choice');
		
		var choices = new Array(); 		
		choiceElements.each(function(index, choice){
			if(choice.value !== ""){
				choices.push({id:index, title:choice.value}); 
			}
		});

		if(choices.length < 2){
			result.html("<h5>Give atleast two Choices to create the Poll!</h5>");
			result.css('display', 'block');
			return;
		}

		var loader = $('.kepler-create-poll .fa');

		loader.css("display", "inline-block");

		var data = {
			'token'	   : token,		
			'question' : question.val(),
			'choices'  : choices,
			'redirect' : redirect,
		};

		//console.log(data);

		$.post(
			url,
			data,
			function(response){
				response = JSON.parse(response);
				loader.css('display','none');

				
				if(response.redirectUrl !== undefined && response.redirectUrl){
					
					window.location.replace(response.redirectUrl+"?success=1");
					return;
				}

				result.css('display', 'block');
				result.html(response.shortcode);

				if(typeof tinymce !== 'undefined') {
					tinymce.activeEditor.execCommand('mceInsertContent', false, response.shortcode);
				}

				//$('#kepler-poll-modal').modal('hide');

			}
	  	);
	});
});