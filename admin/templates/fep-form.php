<div class='kepler-poll-form' data-url='<?php _e($url); ?>' data-token='<?php _e($token); ?>' data-redirect='<?php _e($redirect);?>'>
	<div class='kepler-poll-result'></div>
	<input type='text' class='form-control kepler-add-question' placeholder='Question'>
	
	<br/>
	
	<div class='kepler-choice-wrapper'>
		<input type='text' class='form-control kepler-add-choice'  placeholder='Choice'>
		<input type='text' class='form-control kepler-add-choice'  placeholder='Choice'>
	</div>

	<button  class='btn pull-right kepler-choice-btn'>+ Add Choice</button>

	<button type='submit' class='btn btn-primary kepler-create-poll'>Create Poll &nbsp; <i class='fa fa-refresh fa-spin' style='display: none;'></i></button>	
</div>
