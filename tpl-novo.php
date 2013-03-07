<?php 

if (!get_theme_option('allow_suggested') || !is_user_logged_in()) {
    wp_redirect(home_url('404'), 302 );
    exit();
}

$errors = array();

if (!empty($_POST) && wp_verify_nonce($_POST['create_new_object'], 'consulta_create_new_object')) {
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    $object_types = filter_var($_POST['tax_input']['object_type'], FILTER_SANITIZE_NUMBER_INT, FILTER_REQUIRE_ARRAY);
    var_dump($object_types); die;
    $args = array(
        'post_type' => 'object',
        'post_title' => $title,
        'post_content' => $description, 
    );
    
    $postId = wp_insert_post($args);
    
    if ($postId) {
        update_post_meta($postId, '_user_created', true);
        wp_set_post_terms($postId, $object_types, 'object_type');
    } else {
        $errors[] = __('Não foi possível criar o objeto.', 'consulta');
    } 
}

get_header();

?>

<h2>Adicionar novo objeto</h2>

<form id="new_object" method="post">
    <?php wp_nonce_field('consulta_create_new_object', 'create_new_object'); ?>
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
            <ul id="object_type">
                <?php wp_terms_checklist(null, array('taxonomy' => 'object_type')); ?>
            </ul>
        </div>
        <?php
    }
    ?>
    
    <p>
        <input id="new_object_submit" type="submit" value="<?php _e('Enviar', 'consulta'); ?>">
    </p>
</form>

<?php get_footer(); ?>
