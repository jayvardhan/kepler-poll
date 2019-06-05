<?php

	$value = get_post_meta( $post->ID, '_kepler_end_date', true );

	echo '<input type="text" id="kepler-end-date" name="_kepler_end_date" value="'. esc_attr( $value ) .'" />';
