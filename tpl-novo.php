<?php 

if (!get_theme_option('allow_suggested') || !is_user_logged_in()) {
    wp_redirect(home_url('404'), 302 );
    exit();
}

get_header();

?>

<h2>Adicionar novo objeto</h2>

<form id="new_object" method="post">
    <div class="clearfix">
        <label for="title"><?php _e('Título', 'consulta'); ?></label>
        <input type="text" id="title" value="" name="title">
    </div>
    <div class="clearfix">
        <label for="description"><?php _e('Descrição', 'consulta'); ?></label>
        <textarea name="description" id="description"></textarea>
    </div>
    
    <?php
    if (get_theme_option('enable_taxonomy')) {
        require('wp-admin/includes/template.php'); ?>
        <div class="clearfix">
            <label><?php echo ObjectPostType::get_taxonomy_label('name'); ?></label>
            <?php wp_terms_checklist(null, array('taxonomy' => 'object_type')); ?>
        </div>
        <?php
    }
    ?>
    
    <p>
        <input type="submit" value="<?php _e('Enviar', 'consulta'); ?>">
    </p>
</form>

<?php get_footer(); ?>
