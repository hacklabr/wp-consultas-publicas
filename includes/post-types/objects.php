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
    
    /**
     * Retorna o valor de um label. Se não uma chave é passada
     * retorna o valor de todos os labels.
     * 
     * @param null|string $key
     * @return array|string
     */    
    static function get_taxonomy_label($key = null) {
        $labels = wp_parse_args(get_theme_option('taxonomy_labels'), self::get_taxonomy_default_labels());
        
        if (is_null($key)) {
            return $labels;
        } else if (isset($labels[$key])) {
            return $labels[$key];
        } else {
            throw Exception("Chave $key não existe.");
        }
    }
    
    static function register_taxonomies(){
        $post_types = array('object');
        
        if (get_theme_option('enable_taxonomy')) {
            
            register_taxonomy('object_type', $post_types, array(
                    'hierarchical' => true,
                    'labels' => self::get_taxonomy_label(),
                    'show_ui' => true,
                    'query_var' => true,
                    'rewrite' => array('slug' => get_theme_option('taxonomy_url')),
                )
            );
            
        }
    }
}

ObjectPostType::init();
