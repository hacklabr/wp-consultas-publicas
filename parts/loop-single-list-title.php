<li>
    <div class="interaction clearfix">
        <header>
            <h1>
                <?php if ( current_user_can('manage_options') && file_exists(WP_CONTENT_DIR . '/uploads/access_log/total/' . $post->ID)): ?>
                <small><?php echo filesize(WP_CONTENT_DIR . '/uploads/access_log/total/' . $post->ID); ?> acessos</small><br />
                <?php endif; ?>
                <a href="<?php the_permalink();?>" title="<?php the_title_attribute();?>"><?php the_title();?></a>
            </h1>
    
            <div class="clear"></div>
        </header>
        
        <div class="comments-number" title="<?php comments_number('nenhum comentário','1 comentário','% comentários');?>"><?php comments_number('0','1','%');?></div>
        <?php html::part('show_evaluation'); ?>


    </div>
    <?php if (get_theme_option('evaluation_show_on_list')) : ?>                     
        <div class="evaluation_container" style="display: none;">
            <?php html::part('evaluation', array('in_list' => true))?>
        </div>
    <?php endif; ?>
</li>