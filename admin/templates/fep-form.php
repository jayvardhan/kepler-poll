<div class='kepler-poll-form' data-url='<?php _e($url); ?>' data-token='<?php _e($token); ?>' data-redirect='<?php _e($redirect);?>'>
	<div class='add-poll-result'></div>
	<input type='text' class='form-control' id='add-poll-question' placeholder='Question'>
	
	<br/>
	
	<div class='poll-choice-wrapper'>
		<input type='text' class='form-control add-poll-choice'  placeholder='Choice'>
		<input type='text' class='form-control add-poll-choice'  placeholder='Choice'>
	</div>

	<button  class='btn pull-right' id='add-choice-btn'>+ Add Choice</button>

	<button type='submit' class='btn btn-primary' id='kepler-create-poll'>Create Poll &nbsp; <i class='fa fa-refresh fa-spin' style='display: none;'></i></button>	
</div>
