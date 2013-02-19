<?php
class ObjectPostType {

    static function init(){
        add_action( 'init', array(__CLASS__, 'register') ,0);
        add_action( 'init', array(__CLASS__, 'register_taxonomies') ,0);
    }
    
    static function get_default_labels() {
        return array(
                    'name' => __('Objetos', 'consulta'),
                    'singular_name' => __('Objeto', 'consulta'),
                    'add_new' => __('Adicionar novo', 'consulta'),
                    'add_new_item' => __('Adicionar novo objeto', 'consulta'),
                    'edit_item' => __('Editar objeto', 'consulta'),
                    'new_item' => __('Novo objeto', 'consulta'),
                    'view_item' => __('Ver objeto', 'consulta'),
                    'search_items' => __('Buscar objetos', 'consulta'),
                    'not_found' =>  __('Nenhuma Objeto Encontrado', 'consulta'),
                    'not_found_in_trash' => __('Nenhum Objeto na Lixeira', 'consulta'),
                 );
    }
    /*
    static function get_suggested_default_labels() {
        return array(
                    'name' => __('Objetos sugeridos', 'consulta'),
                    'singular_name' => __('Objeto sugerido', 'consulta'),
                    'add_new' => __('Adicionar novo', 'consulta'),
                    'add_new_item' => __('Adicionar novo objeto', 'consulta'),
                    'edit_item' => __('Editar objeto', 'consulta'),
                    'new_item' => __('Novo objeto', 'consulta'),
                    'view_item' => __('Ver objeto', 'consulta'),
                    'search_items' => __('Buscar objetos', 'consulta'),
                    'not_found' =>  __('Nenhuma Objeto Encontrado', 'consulta'),
                    'not_found_in_trash' => __('Nenhum Objeto na Lixeira', 'consulta'),
                 );
    }
    */
    static function register(){
        register_post_type('object', array(
                 'labels' => wp_parse_args(get_theme_option('object_labels'), self::get_default_labels()),
                 'public' => true,
                 'rewrite' => array('slug' => get_theme_option('object_url')),
                 'capability_type' => 'post',
                 'hierarchical' => false,
                 'map_meta_cap' => true,
                 'menu_position' => 6,
                 'has_archive' => true,
                 'supports' => array(
                     	'title',
                     	'editor',
                     	'excerpt',
                     	'comments',
                 ),
            )
        );
        /*
        if (get_theme_option('allow_suggested')) {
        
            register_post_type('suggested_object', array(
                    'labels' => wp_parse_args(get_theme_option('suggested_object_labels'), self::get_suggested_default_labels()),
                     'public' => true,
                     'rewrite' => array('slug' => get_theme_option('suggested_object_url')),
                     'capability_type' => 'post',
                     'hierarchical' => false,
                     'map_meta_cap' => true,
                     'menu_position' => 6,
                     'supports' => array(
                            'title',
                            'editor',
                            'excerpt',
                            'comments',
                     ),
                )
            );
        }
        */
    }
    
    static function get_taxonomy_default_labels() {
        return array(
            'name' => __('Tipos de objeto', 'consulta'),
            'singular_name' => __('Tipo de objeto', 'consulta'),
            'search_items' =>  __('Buscar tipos', 'consulta'),
            'all_items' => __('Todos os tipos', 'consulta'),
            'parent_item' => __('Tipo pai', 'consulta'),
            'parent_item_colon' => __('Tipo pai:', 'consulta'),
            'edit_item' => __('Editar tipo', 'consulta'),
            'update_item' => __('Atualizar tipo', 'consulta'),
            'add_new_item' => __('Adicionar novo tipo', 'consulta'),
            'new_item_name' => __('Nome do novo tipo', 'consulta'),
        ); 	
    }
    
    static function register_taxonomies(){
        
        //$post_types = get_theme_option('allow_suggested') ? array('object', 'suggested_object') : array('object');
        $post_types = array('object');
        
        if (get_theme_option('enable_taxonomy')) {
            
            register_taxonomy('object_type', $post_types, array(
                    'hierarchical' => true,
                    'labels' => wp_parse_args(get_theme_option('taxonomy_labels'), self::get_taxonomy_default_labels()),
                    'show_ui' => true,
                    'query_var' => true,
                    'rewrite' => array('slug' => get_theme_option('taxonomy_url')),
                )
            );
            
        }
        
    }
}

ObjectPostType::init();
