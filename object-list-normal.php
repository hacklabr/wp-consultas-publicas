<?php

$post_type_object = get_post_type_object( 'object' );

get_header();

?>
<section id="main-section" class="span-15 prepend-1 append-1">
    <h2><?php echo $post_type_object->labels->name; ?></h2>

    <?php html::part('add_new_object'); ?>
    
    <?php if (is_tax('object_type')) : ?>
        <?php
        
        $termDiscription = term_description( '', get_query_var( 'taxonomy' ) );
        
        if ($termDiscription != '') : ?>
            <div class="ementa-do-tema">
                <h1><?php wp_title("",true); ?></h1>
                <div class="append-bottom"></div>
                <?php echo $termDiscription; ?>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <?php echo get_theme_option('object_list_intro'); ?>
    <?php endif; ?>    

    <section class="tema">
        <ul>
            <?php html::part('loop-single-list-normal'); ?>
        </ul>
    </section>    
</section>
<!-- #main-section -->
    
<?php get_sidebar(); ?>
<?php get_footer(); ?>
