<?php
	switch($atts['grid_cols']){
		case 1: $col_class = 'col-1';break;
		case 2: $col_class = 'col-2';break;
		case 3: $col_class = 'col-3';break;
		case 4: $col_class = 'col-4';break;
		default:
			$col_class = 'col-3';
	}
?>


<div class="poll-grid yka-container">
	<div class="story-grid">
		<div class="story-grid-title"><?php _e($atts['title']);?></div>	
		<div id="<?php _e($atts['id']);?>" class="<?php _e($col_class);?>">
		<?php foreach($polls as $poll){ ?>
			<article class="<?php _e( $atts['grid'] );?>">
				<?php echo $this->poll_html( array( "id" => $poll->poll_id ) );?>
			</article>
		<?php } ?>			
		</div>
	</div>
</div>