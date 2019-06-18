<?php
	require_once __DIR__.'/../class-kepler-choice.php';
	
	$choice_db = KEPLER_CHOICE::get_instance();
	$choice_list = $choice_db->get_choices($post->ID); ?>
	
	<ul class='kepler-choice-wrapper'> <?php

	if( count($choice_list) ) { 
		
		$i = 0;
		foreach ($choice_list as $choice) { 
				$input_field_name = "_kepler_poll_choice[$i][title]";
				$hidden_field_name = "_kepler_poll_choice[$i][id]";
				
			?>
			<li class="kepler-choice-item"> <?php

				if(count($choice_list) > 2 && $i >= 2 ) {
					_e('<button class="choice-remove-btn">x</button>');
				} ?>
				
				<input type='text' class='regular-text' name='<?php _e($input_field_name);?>'   placeholder='Enter Choice' value='<?php _e($choice->choice);?>' > 
				<input type='hidden' name='<?php _e($hidden_field_name);?>' value='<?php _e($choice->ID)?>'>	

			</li> <?php 		
			$i++;
		}
	} else { ?>
		<li class="kepler-choice-item">
			<input type='text' class='regular-text' name='_kepler_poll_choice[0][title]'   placeholder='Enter Choice'> 
			<input type='hidden' name='_kepler_poll_choice[0][id]' value=''>	
		</li>
		
		<li class="kepler-choice-item">
			<input type='text' class='regular-text' name='_kepler_poll_choice[1][title]'   placeholder='Enter Choice'>
			<input type='hidden' name='_kepler_poll_choice[1][id]' value=''>	
		</li> <?php
 	} ?>

	</ul>

	<button  class='button add-choice-btn'>+ Add Choice</button>

	

