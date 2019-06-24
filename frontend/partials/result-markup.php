<div class='kepler-choice-group-<?php _e($poll_id);?>'>
	<ul class='kepler-choice' >
	<?php
		foreach ($results as $row=>$obj) { ?>
			<li class='kepler-choice-result' style="background-color:<?php _e($colors[$i%$len]);?>" >
				<?php _e(stripslashes($obj->choice));?>
				<span class='kepler-right'><?php _e(number_format($obj->percentage));?>%</span>
			</li> <?php
			$total_responses = $obj->total;
			$i++;	
		}
	?>
	</ul>

	<?php

	/* SHOW RESPONSES ONLY IF CURRENT USER IS THE POLL AUTHOR OR ADMIN */
	$current_user_id = get_current_user_id();
	if( ( $current_user_id && ( $current_user_id == $poll_user_id ) )  || current_user_can('administrator') ){ ?>
			<p class='small text-muted'>Total Responses: <?php _e($total_responses);?></p> <?php
		}
	?>
	
</div>

