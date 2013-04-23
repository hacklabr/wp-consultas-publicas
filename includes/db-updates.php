<?php

// adiciona meta _user_created como false para os posts que não foram criados pelos usuários para facilitar distinção 
if (congelado_db_update('db-update-1')) {
    $posts = get_posts(array('post_type' => 'object', 'posts_per_page' => -1));
    
    foreach ($posts as $post) {
        if (!get_post_meta($post->ID, '_user_created', true)) {
            update_post_meta($post->ID, '_user_created', false);
        }
    }
}

// adiciona capability edit_posts aos usuários registrados para permitir que eles associem
// itens de uma taxonomia quando estão criando um objeto sugerido
if (congelado_db_update('db-update-2')) {
    $role = get_role('subscriber');
    $role->add_cap('edit_posts');
}