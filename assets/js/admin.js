jQuery(document).ready(function($){

	//enable datepicker
	$("#poll-end-date").datepicker();


	// Poll Choice Repeater
	keplerChoiceRepeater = {

		//two choices are default so initaite counter with 2
		counter: 2,
		deleteList: [],
		hiddenDelete: '',
		
		init: function (){
			this.hiddenDelete = $('input[name=_kepler_choice_delete]');
			
			$('.add-choice-btn').on('click', this.repeatHandler.bind(this));
			$('.choice-remove-btn').on( 'click', this.choiceRemoveHandler.bind(this));
		},

		repeatHandler: function(e) {
			e.preventDefault();

			var el = '<li class="kepler-choice-item"><button class="choice-remove-btn">x</button>'+
						'<input type="text" class="regular-text" name="_kepler_poll_choice['+ 
						this.counter +
					 '][title]" placeholder="Enter Choice"><input type="hidden" name="_kepler_poll_choice['+ this.counter +'][id]"></li>';

			//append choice-item
			$('.kepler-choice-wrapper').append(el);

			//increase counter
			this.counter++;

			//safely attach event on newly created choice-remove-btn
			this.choiceRemoveBtnEventRegister();
		},

		choiceRemoveBtnEventRegister: function(){
			//first unset any listener if already exits
			$('.choice-remove-btn').off();

			//attach event 
			$('.choice-remove-btn').on( 'click', this.choiceRemoveHandler.bind(this));
		},

		choiceRemoveHandler: function(e){
			e.preventDefault();

			//grab choice id from subsequent element
			var choiceID = $(e.target).next().next().val();
			
			//remove choice list item 
			$(e.target).closest('li').remove();
			
			if( choiceID.length ) {
				//catch all choice to be deleted
				this.deleteList.push( choiceID );
				//update choice delete value
				this.hiddenDelete.val( this.deleteList.join() );
			}
		}

	};

	keplerChoiceRepeater.init();

});