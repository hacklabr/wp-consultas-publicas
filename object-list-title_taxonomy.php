<?php 

$types = get_terms('object_type', 'orderby=id&order=ASC');

?>

<?php get_header(); ?>	
	
    <section id="main-section" class="span-15 prepend-1 append-1">
        
        <h2><?php echo $post_type_object->labels->name; ?></h2>
        
        <?php echo get_theme_option('object_list_intro'); ?>
        
		<?php foreach ($types as $type): ?>
		<section class="tema">
			<?php
			
			$termDiscription = term_description( $type->term_id, 'object_type' );

			if($termDiscription != '') : ?>
				<header>
					<h1><a href="<?php echo get_term_link($type->slug, 'object_type'); ?>"><?php echo $type->name; ?></a></h1>
				</header>
			<?php endif; ?>
			<ul>
			<?php $metas = new WP_Query('posts_per_page=-1&post_type=object&object_type=' . $type->slug); ?>
			<?php if ( $metas->have_posts()) : while ( $metas->have_posts()) : $metas->the_post(); ?>
						<li>
							
							<div class="interaction clearfix">
								<div class="comments-number" title="<?php comments_number('nenhum comentário','1 comentário','% comentários');?>"><?php comments_number('0','1','%');?></div>
								<div class="commenters-number" title="número de pessoas que comentaram"><span class="commenters-number-icon"></span><?php echo get_num_pessoas_comentarios($post->ID); ?></div>
								<h1>
                                    <a href="<?php the_permalink();?>" title="<?php the_title_attribute();?>"><?php the_title();?></a>
                                    
                                    <?php if ( current_user_can('manage_options') && file_exists(WP_CONTENT_DIR . '/uploads/access_log/total/' . $post->ID)): ?>
                                    <small><?php echo filesize(WP_CONTENT_DIR . '/uploads/access_log/total/' . $post->ID); ?> acessos</small>
                                    <?php endif; ?>
                                </h1>
							</div>
						</li>


			<?php endwhile; ?>                			
			<?php else : ?>
			   <?php
               
               $post_type_object = get_post_type_object( 'object' );
               echo '<p>';
               echo $post_type_object->labels->not_found;
               echo '</p>';
               
               ?>
			<?php endif; ?>
			</ul>
			
		</section>    
		<?php endforeach; ?>
    
    </section>
    <!-- #main-section -->
    
<?php get_sidebar(); ?>
<?php get_footer(); ?>
