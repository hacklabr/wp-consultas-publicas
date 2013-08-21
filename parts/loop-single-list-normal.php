<?php if (have_posts()) :
    while (have_posts()) :
        the_post(); ?>
        <li>
            <div class="interaction clearfix">
                <header>
                    <h1>
                        <?php if (get_post_meta($post->ID, '_user_created', true)) :?>
                            <div class="suggested-user-icon"><img src="<?php bloginfo('template_directory') ?>/img/user-suggest.png" title="Sugestão do usuário" alt="Sugestão do usuário" /></div>
                        <?php endif; ?>
    
                        <a href="<?php the_permalink();?>" title="<?php the_title_attribute();?>"><?php the_title();?></a>
                        
                        <?php if ( current_user_can('manage_options') && file_exists(WP_CONTENT_DIR . '/uploads/access_log/total/' . $post->ID)): ?>
                        <small><?php echo filesize(WP_CONTENT_DIR . '/uploads/access_log/total/' . $post->ID); ?> acessos</small>
                        <?php endif; ?>
                    </h1>
    
                    <div class="clear"></div>
                </header>
                
                <div class="comments-number" title="<?php comments_number('nenhum comentário','1 comentário','% comentários');?>"><?php comments_number('0','1','%');?></div>
            </div>
            <?php the_content(); ?>
        </li>
    <?php endwhile; ?> 
    
    <?php global $wp_query; if ( $wp_query->max_num_pages > 1 ) : ?>
        <nav id="posts-nav" class="clearfix">
            <span class="alignleft"><?php previous_posts_link(__('Anteriores','consulta')); ?></span>
            <span class="alignright"><?php echo next_posts_link(__('Próximos','consulta')); ?></span>
        </nav>
        <!-- #posts-nav -->
    <?php endif; ?>
<?php else : ?>
   <p><?php echo $post_type_object->labels->not_found; ?></p>
<?php endif; ?>