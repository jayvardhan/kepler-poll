
<div class="kepler-poll">
	<div class="kepler-header">
		<span class='kepler-brand'>ACME INC. POLL</span>
		<p class="kepler-poll-author">Created by <a href="<?php _e(get_author_posts_url( $poll['post_author'] )); ?>">
			<?php _e(get_the_author_meta('display_name', $poll['post_author'] ));?></a></p>
	</div>
	<div class="kelper-poll-content">
		<div class="kepler-poll-question"><?php _e($poll['post_title']);?></div>
		<?php _e($poll_choice_markup)?>
	</div>
</div>	



