<?php 
	$colors = $this->get_choice_colors();
	$len 	= count($colors);	
	$i 		= 0;
?>
<div class='kepler-choice-group-<?php _e($poll_id);?>'>
	<ul class='kepler-choice' data-url='<?php _e($ajax_url); ?>' data-id='<?php _e($poll_id); ?>' data-nonce='<?php _e($nonce); ?>' >
	<?php
		foreach ($choices as $choice) { ?>
			<li class='kepler-choice-item' style="background-color:<?php _e($colors[$i%$len]);?>" data-choice='<?php _e($choice['ID']);?>' ><?php _e(stripslashes($choice['choice'])) ?> </li>
			<?php
			$i++;	
		}
	?>
	</ul>
</div>