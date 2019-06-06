jQuery(document).ready(function($){

	//enable datepicker
	$("#poll-end-date").datepicker();


	// Poll Choice Repeater
	keplerChoiceRepeater = {

		//two choices are default so initaite counter with 2
		counter: 2,
		
		init: function (){
			$('.add-choice-btn').on('click', this.repeatHandler.bind(this));
		},

		repeatHandler: function(e) {
			e.preventDefault();

			var el = '<div class="kepler-choice-item"><button class="choice-remove-btn">x</button><input type="text" class="regular-text" name="add-poll-choice['+ 
						this.counter +
					 ']" placeholder="Choice"></div>';

			//append choice-item
			$('.poll-choice-wrapper').append(el);

			//increase counter
			this.counter++;

			//safely attach event on newly created choice-remove-btn
			this.choiceRemoveBtnEventRegister();
		},

		choiceRemoveBtnEventRegister: function(){
			//first unset any listener if already exits
			$('.choice-remove-btn').off();

			//attach event 
			$('.choice-remove-btn').on( 'click', this.choiceRemoveHandler);
		},

		choiceRemoveHandler: function(e){
			e.preventDefault();

			$(e.target).closest('div').remove();

			console.log('add implementation for choice removal');
		}

	};

	keplerChoiceRepeater.init();

});