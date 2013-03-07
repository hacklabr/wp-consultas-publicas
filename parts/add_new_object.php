<?php 

$labels = get_theme_option('object_labels');

?>

<?php if (is_user_logged_in()) : ?>
    <p><a href="<?php echo home_url('/novo'); ?>"><?php echo $labels['add_new_item']?></a></p>
<?php endif; ?>