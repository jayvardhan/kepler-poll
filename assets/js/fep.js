jQuery(document).ready(function($) {

	// choice field repeater
	var addChoice = $('#add-choice-btn');
	var choiceWrapper = $('.poll-choice-wrapper');

	addChoice.on('click', function(e){
		var el = document.createElement('input');
		el.type = 'text';
		el.className = 'form-control add-poll-choice';
		el.setAttribute('placeholder', 'Choice');

		choiceWrapper.append(el);	
	});	


	//event handler to create new poll 
	var poll = $('.kepler-poll-form');
	var url = poll.attr("data-url");
	var token = poll.attr("data-token"); 
	
	var result = $('.add-poll-result');

	var createPoll = $('#kepler-create-poll');
	createPoll.on('click', function(e){
		e.preventDefault();

		var question = $('#add-poll-question');

		if(question.val() === ""){
			result.html("<h5>Give question for the Poll!</h5>");
			result.css('display', 'block');
			return;
		}


		var choiceElements = $('.add-poll-choice');
		
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

		var loader = $('#kepler-create-poll .fa');

		loader.css("display", "inline-block");

		var data = {
			'token'		: token,		
			'question'	: question.val(),
			'choices' 	: choices
		};
		

		$.post(
			url,
			data,
			function(response){
				result.html(response);
				result.css('display', 'block');

				if(typeof tinymce !== 'undefined') {
					tinymce.activeEditor.execCommand('mceInsertContent', false, response);
				}

				loader.css('display','none');
				//$('#kepler-poll-modal').modal('hide');

			}
	  	);
	});
});