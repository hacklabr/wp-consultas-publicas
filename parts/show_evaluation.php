<?php if (get_theme_option('evaluation_show_on_list') && (get_theme_option('evaluation_public_results') || is_user_logged_in())) : ?>
    <div class="show_evaluation"><span class="count_object_votes"><?php echo count_votes($post->ID); ?></span> V</div>
<?php endif; ?>