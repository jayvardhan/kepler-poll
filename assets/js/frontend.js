$(document).ready(function() {
	$('.kepler-choice-item').each(function(choice) {
		$(this).on('click', function(e){
			e.preventDefault();
			var choiceId = $(this).data('choice');
			
			var choiceGroup = $(this).parent();
			
			var contextClass = choiceGroup.parent().attr('class');
			var pollId = choiceGroup.data('id');
			var url = choiceGroup.data('url');
			var nonce = choiceGroup.data('nonce');
			var param = '&id=' + pollId + '&choice=' + choiceId + '&nonce=' + nonce;
			url += param;
			
			$.ajax({
	        	type:'get',
	        	url: url,
	        	context:contextClass,
	        	success: function(response){ 
	        		$('.'+this)[0].innerHTML = response;
	        	}
			});
		});
	});
});

