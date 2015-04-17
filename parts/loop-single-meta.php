<article id="post-<?php the_ID(); ?>" <?php post_class('post clearfix');?>>
    <header>
        <h1><?php the_title();?></h1>
        <?php if (get_theme_option('enable_taxonomy')): ?>
            <?php 
                $tax_obj = get_taxonomy('object_type');
            ?>
            <p class="bottom">
                <?php echo $tax_obj->labels->name; ?>: <?php the_terms( get_the_ID() , 'object_type' ); ?>
            </p>
        <?php endif; ?>
        <?php if (get_post_meta(get_the_ID(), '_user_created', false)) : ?>
            <p>Sugerido por <a href="<?php echo get_author_posts_url(get_the_author_meta('ID')); ?>"><?php echo get_the_author_meta('display_name'); ?></a></p>
        <?php endif; ?>
    </header>
    <div class="post-content clearfix">
        <div class="post-entry">
            <?php the_content(); ?>
        </div>
    </div>
    <!-- .post-content -->
    <div class="evaluation_container">
        <?php html::part('evaluation')?>
    </div>
    <footer class="clearfix"> 
        <?php html::part('interaction'); ?>
    </footer>
</article>
<!-- .post -->
