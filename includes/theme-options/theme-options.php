<?php

function get_theme_default_options() {

    return array(
        'pagina_help' => site_url('sobre'),
        'pagina_sugerir' => site_url('sugerir-uma-meta'),
        'data_encerramento' => '2011-10-01',
        
        'object_labels' => ObjectPostType::get_default_labels(),
        //'suggested_object_labels' => ObjectPostType::get_suggested_default_labels(),
        'taxonomy_labels' => ObjectPostType::get_taxonomy_default_labels(),
        
        'taxonomy_url' => 'tipo',
        'object_url' => 'objeto',
        //'suggested_object_url' => 'objeto_sugerido',
        
        //'allow_suggested' => false,
        'enable_taxonomy' => false,
        
        'list_type' => 'normal',
        'object_list_intro' => '',

        'use_evaluation' => false,
        'evaluation_labels' => array('label_1' => __('Concordo', 'consulta'), 'label_2' => __('Não concordo', 'consulta'), 'label_3' => '', 'label_4' => '', 'label_5' => ''),
        'evaluation_text' => __('Você concorda com esta proposta?', 'consulta'),
        'evaluation_type' => 'percentage',
        
        'pagina_participe' => ''
    );

}

function get_theme_option($option_name) {
    $option = wp_parse_args( 
                    get_option('theme_options'), 
                    get_theme_default_options()
                );
    return isset($option[$option_name]) ? $option[$option_name] : false;
}

add_action('admin_init', 'theme_options_init');
add_action('admin_menu', 'theme_options_menu');

add_action('admin_print_scripts-toplevel_page_theme_options', 'theme_options_js');
add_action('admin_print_styles-toplevel_page_theme_options', 'theme_options_css');

function theme_options_init() {
    register_setting('theme_options_options', 'theme_options', 'theme_options_validate_callback_function');
    register_setting('theme_options_destaques', 'destaques');
}

function theme_options_menu() {
    $topLevelMenuLabel = __('Opções da Consulta', 'consulta');
    $page_title = 'Opções';
    $menu_title = 'Opções';
    
    /* Top level menu */
    add_submenu_page('theme_options', $page_title, $menu_title, 'manage_options', 'theme_options', 'theme_options_page_callback_function');
    
    
    add_menu_page($topLevelMenuLabel, $topLevelMenuLabel, 'manage_options', 'theme_options', 'theme_options_page_callback_function');
    add_submenu_page('theme_options', 'Destaques', 'Destaques', 'manage_options', 'theme_options_destaques', 'theme_options_page_destaques_callback');
}

function theme_options_js() {
    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_script('theme_options_js', get_bloginfo('stylesheet_directory') . '/js/theme-options.js', array('jquery'));
}

function theme_options_css() {
    wp_enqueue_style('theme-options', get_template_directory_uri() . '/css/ui-lightness/jquery-ui-1.9.1.custom.min.css');
}

function theme_options_validate_callback_function($input) {
    foreach ($input as $key => $value) {
        if (is_array($value)) {
            $input[$key] = theme_options_validate_callback_function($value);
        } else if (in_array($key, array('pagina_help', 'pagina_sugerir', 'taxonomy_url', 'object_url', 'suggested_object_url'))) {
            $input[$key] = sanitize_title($value);
        } else if (is_string($value)) {
            $input[$key] = strip_tags($value);
        }
    }
    
    return $input;
}


function theme_options_page_callback_function() {
    // hack alert: limpa o cache das regras de redirecionamento para atualizar os links
    // dos customs post types quando muda o slug de um deles. não dá para fazer isso logo
    // depois de salvar a opção pois nesse momento o valor do slug do objeto em si ainda não
    // foi atualizado. 
    global $wp_rewrite;
    $wp_rewrite->flush_rules();
    
    ?>

    <style>
    #abas-secoes { padding-left: 4px; border-bottom: 1px solid #DDDDDD; list-style: none; margin: 0px 0px 22px }
    #abas-secoes:after {
      content: "\0020";
      display: block;
      height: 0;
      clear: both;
      visibility: hidden;
      overflow:hidden;
    }
    #abas-secoes li { float: left; margin-right: 4px; margin-bottom: -1px; padding: 5px 6px; border: 1px solid #DDDDDD; border-radius: 6px 6px 0 0; -moz-border-radius: 6px 6px 0 0; -webkit-border-radius: 6px 6px 0 0; font-weight: bold; }
    #abas-secoes li:hover { background-color: #EEEEEE; } 
    #abas-secoes li.active:hover { background-color: #FFF; } 
    #abas-secoes li.active { border-bottom: 1px solid #fff; }
    #abas-secoes a { display: block; color: #999; cursor: pointer; }
    #abas-secoes a:hover { text-decoration: none;  }
    
    #exemplo_resultado { padding: 15px; border: 1px solid grey; }
    
    </style>
    
    <div class="wrap span-20">
        <h2><?php echo __('Opções da Consulta', 'consulta'); ?></h2>
        
        
        
        <form action="options.php" method="post" class="clear prepend-top">
            <?php 
            settings_fields('theme_options_options');
            $options = wp_parse_args( 
                get_option('theme_options'), 
                get_theme_default_options()
            );
            ?>            
            
            <ul id="abas-secoes" >
                <li class="active"><a id="aba-outras">Opções Gerais</a></li>
                <li><a id="aba-objeto">Objeto da consulta</a></li>
                <li><a id="aba-listagem">Tipo de listagem</a></li>
                <li><a id="aba-quantitativa">Avaliação quantitativa</a></li>
            </ul>
            
            <div id="aba-objeto-container" class="aba-container">
            <div class="span-20 ">
                <div class="span-6 last">
                    <h3><?php _e('Objeto da Consulta', 'consulta'); ?></h3>
                    <p>
                    <?php _e('Quais são os objetos da sua consulta? Itens de um projeto de lei? Metas de um Plano? Utilize esta página para dar o nome adequado aquilo que você está colocando sob consulta. Preencha as opções abaixo substituindo o termo "objeto" pelo nome do objeto da sua consulta.', 'consulta'); ?>
                    </p>
                    <table class="wp-list-table widefat fixed">
                        
                        
                        
                        <tr>
                        <td><label for="name">Nome do objeto da consulta (plural)</label></td>
                        <td><input type="text" id="name" class="text" name="theme_options[object_labels][name]" value="<?php echo htmlspecialchars($options['object_labels']['name']); ?>"/></td>
                        </tr>
                        
                        
                        <tr>
                        <td><label for="singular_name">Nome do objeto da consulta (singular)</label></td>
                        <td><input type="text" id="singular_name" class="text" name="theme_options[object_labels][singular_name]" value="<?php echo htmlspecialchars($options['object_labels']['singular_name']); ?>"/></td>
                        </tr>
                        
                        <tr>
                        <td><label for="add_new">Adicionar novo</label></td>
                        <td><input type="text" id="add_new" class="text" name="theme_options[object_labels][add_new]" value="<?php echo htmlspecialchars($options['object_labels']['add_new']); ?>"/></td>
                        </tr>
                        
                        <tr>
                        <td><label for="add_new_item">Adicionar novo objeto</label></td>
                        <td><input type="text" id="add_new_item" class="text" name="theme_options[object_labels][add_new_item]" value="<?php echo htmlspecialchars($options['object_labels']['add_new_item']); ?>"/></td>
                        </tr>
                        <tr>
                        <td><label for="edit_item">Editar objeto</label></td>
                        <td><input type="text" id="edit_item" class="text" name="theme_options[object_labels][edit_item]" value="<?php echo htmlspecialchars($options['object_labels']['edit_item']); ?>"/></td>
                        </tr>
                        <tr>
                        <td><label for="new_item">Novo objeto</label></td>
                        <td><input type="text" id="new_item" class="text" name="theme_options[object_labels][new_item]" value="<?php echo htmlspecialchars($options['object_labels']['new_item']); ?>"/></td>
                        </tr>
                        <tr>
                        <td><label for="view_item">Ver objeto</label></td>
                        <td><input type="text" id="view_item" class="text" name="theme_options[object_labels][view_item]" value="<?php echo htmlspecialchars($options['object_labels']['view_item']); ?>"/></td>
                        </tr>
                        <tr>
                        <td><label for="search_items">Buscar objetos</label></td>
                        <td><input type="text" id="search_items" class="text" name="theme_options[object_labels][search_items]" value="<?php echo htmlspecialchars($options['object_labels']['search_items']); ?>"/></td>
                        </tr>
                        <tr>
                        <td><label for="not_found">Nenhum Objeto Encontrado</label></td>
                        <td><input type="text" id="not_found" class="text" name="theme_options[object_labels][not_found]" value="<?php echo htmlspecialchars($options['object_labels']['not_found']); ?>"/></td>
                        </tr>
                        <tr>
                        <td><label for="not_found_in_trash">Nenhum Objeto na Lixeira</label></td>
                        <td><input type="text" id="not_found_in_trash" class="text" name="theme_options[object_labels][not_found_in_trash]" value="<?php echo htmlspecialchars($options['object_labels']['not_found_in_trash']); ?>"/></td>
                        </tr>
                        
                        <tr>
                        <td><label for="object_url">Endereço base para os objetos da consulta</label></td>
                        <td><?php echo site_url(); ?>/<input type="text" id="object_url" class="text" name="theme_options[object_url]" value="<?php echo htmlspecialchars($options['object_url']); ?>"/></td>
                        </tr>
                        
                    </table>
                    
                </div>
            </div>
            
            <div class="span-20 ">
                <div class="span-6 last">
                    <h3><?php echo __('Categorização dos Objetos da Consulta', 'consulta'); ?></h3>
                    
                    <p>
                    <?php _e('Os objetos da sua consulta podem ser agrupados dentro de uma classificação. Por exemplo, as metas de um plano podem estar agrupadas em diferentes temas. Neste caso, sua taxonomia seria "temas". Use os campos abaixo para dar um nome para a sua classificação.', 'consulta'); ?>
                    </p>
                    
                    <input type="checkbox" id="enable_taxonomy" name="theme_options[enable_taxonomy]" <?php checked('on', $options['enable_taxonomy']); ?> />
                    <label for="enable_taxonomy"><?php echo __('Habilitar categorização dos objetos', 'consulta'); ?></label>
                    
                    <div id="taxonomy_labels_container">
                    <table class="wp-list-table widefat fixed">
                        
                        <tr>
                        <td><label for="name">Nome da taxonomia (plural)</label></td>
                        <td><input type="text" id="name" class="text" name="theme_options[taxonomy_labels][name]" value="<?php echo htmlspecialchars($options['taxonomy_labels']['name']); ?>"/></td>
                        </tr>
                        <tr>
                        <td><label for="singular_name">Nome da taxonomia (singular)</label></td>
                        <td><input type="text" id="singular_name" class="text" name="theme_options[taxonomy_labels][singular_name]" value="<?php echo htmlspecialchars($options['taxonomy_labels']['singular_name']); ?>"/></td>
                        </tr>
                        <tr>
                        <td><label for="add_new_item">Adicionar novo tipo</label></td>
                        <td><input type="text" id="add_new_item" class="text" name="theme_options[taxonomy_labels][add_new_item]" value="<?php echo htmlspecialchars($options['taxonomy_labels']['add_new_item']); ?>"/></td>
                        </tr>
                        <tr>
                        <td><label for="edit_item">Editar tipo</label></td>
                        <td><input type="text" id="edit_item" class="text" name="theme_options[taxonomy_labels][edit_item]" value="<?php echo htmlspecialchars($options['taxonomy_labels']['edit_item']); ?>"/></td>
                        </tr>
                        <tr>
                        <td><label for="new_item_name">Nome do novo tipo</label></td>
                        <td><input type="text" id="new_item_name" class="text" name="theme_options[taxonomy_labels][new_item_name]" value="<?php echo htmlspecialchars($options['taxonomy_labels']['new_item_name']); ?>"/></td>
                        </tr>
                        <tr>
                        <td><label for="search_items">Buscar tipos</label></td>
                        <td><input type="text" id="search_items" class="text" name="theme_options[taxonomy_labels][search_items]" value="<?php echo htmlspecialchars($options['taxonomy_labels']['search_items']); ?>"/></td>
                        </tr>
                        <tr>
                        <td><label for="all_items">Todos os tipos</label></td>
                        <td><input type="text" id="all_items" class="text" name="theme_options[taxonomy_labels][all_items]" value="<?php echo htmlspecialchars($options['taxonomy_labels']['all_items']); ?>"/></td>
                        </tr>
                        <tr>
                        <td><label for="parent_item">Tipo pai</label></td>
                        <td><input type="text" id="parent_item" class="text" name="theme_options[taxonomy_labels][parent_item]" value="<?php echo htmlspecialchars($options['taxonomy_labels']['parent_item']); ?>"/></td>
                        </tr>
                        <tr>
                        <td><label for="parent_item_colon">Tipo pai:</label></td>
                        <td><input type="text" id="parent_item_colon" class="text" name="theme_options[taxonomy_labels][parent_item_colon]" value="<?php echo htmlspecialchars($options['taxonomy_labels']['parent_item_colon']); ?>"/></td>
                        </tr>
                        <tr>
                        <td><label for="update_item">Atualizar tipo</label></td>
                        <td><input type="text" id="update_item" class="text" name="theme_options[taxonomy_labels][update_item]" value="<?php echo htmlspecialchars($options['taxonomy_labels']['update_item']); ?>"/></td>
                        </tr>
                        
                        <tr>
                        <td><label for="taxonomy_url">Endereço base para a taxonomia do objeto</label></td>
                        <td><?php echo site_url(); ?>/<input type="text" id="taxonomy_url" class="text" name="theme_options[taxonomy_url]" value="<?php echo htmlspecialchars($options['taxonomy_url']); ?>"/></td>
                        </tr>
                    </table>
                    </div>
                    
                </div>
            </div>
            </div>
            
            <div id="aba-listagem-container" class="aba-container">
            <div class="span-20 ">
                <div class="span-6 last">
                    <h3><?php _e('Tipo de listagem', 'consulta'); ?></h3>
                    
                    <p><?php _e('Como você gostaria de listar seus objetos', 'consulta'); ?></p>
                    
                    <input type="radio" name="theme_options[list_type]" id="list_type_normal" value="normal" <?php checked('normal', $options['list_type']); ?>/>
                    <label for="list_type_normal"><b>Normal - </b></label> Listagem corrida, estilo blog.
                    <br/><br/>
                    <input type="radio" name="theme_options[list_type]" id="list_type_title" value="title" <?php checked('title', $options['list_type']); ?>/>
                    <label for="list_type_title"><b>Apenas títulos - </b></label> Lista apenas com os títulos dos objetos
                    <br/><br/>
                    <input type="radio" name="theme_options[list_type]" id="list_type_title_taxonomy" value="title_taxonomy" <?php checked('title_taxonomy', $options['list_type']); ?>/>
                    <label for="list_type_title_taxonomy"><b>Apenas títulos agrupados por categoria - </b></label> Lista apenas com os títulos dos objetos agrupados por tipo de objeto.
                    <br/><br/>
                    <?php _e('Texto introdutório para a página de listagem de objetos', 'consulta'); ?><br/>
                    <textarea name="theme_options[object_list_intro]" id="object_list_intro" ><?php echo $options['object_list_intro']; ?></textarea>
                    
                    
                </div>
            </div>
            </div>
            
            <div id="aba-quantitativa-container" class="aba-container">
            <div class="span-20 ">
                <div class="span-6 last">
                    <h3><?php echo __('Avaliação quantitativa dos objetos da consulta', 'consulta'); ?></h3>
                    
                    <p><?php _e('Os objetos da sua consulta podem avaliados pelos usuários. O sistema permite até cinco valores diferentes para a avaliação. Por exemplo, a avaliação pode usar dois valores ("concordo" e "não concordo").', 'consulta'); ?></p>

                    <input type="checkbox" id="use_evaluation" name="theme_options[use_evaluation]" value="on" <?php checked('on', $options['use_evaluation']); ?> />
                    <label for="use_evaluation"><?php _e('Permitir que os usuários avaliem os objetos', 'consulta'); ?></label>

                    <div id="use_evaluation_labels_container">
                        <br/><br/>
                        <?php _e('Texto introdutório para a avaliação quantitativa', 'consulta'); ?><br/>
                        <textarea name="theme_options[evaluation_text]" id="object_list_intro" ><?php echo $options['evaluation_text']; ?></textarea>
                        <br/><br/>
                        <table class="wp-list-table widefat fixed">
                            <tr>
                                <td><label for="label_1"><?php _e('Nome do primeiro valor (1)', 'consulta'); ?></label></td>
                                <td><input type="text" id="label_1" class="text" name="theme_options[evaluation_labels][label_1]" value="<?php echo htmlspecialchars($options['evaluation_labels']['label_1']); ?>"/></td>
                            </tr>
                            <tr>
                                <td><label for="label_2"><?php _e('Nome do segundo valor (2)', 'consulta'); ?></label></td>
                                <td><input type="text" id="label_2" class="text" name="theme_options[evaluation_labels][label_2]" value="<?php echo htmlspecialchars($options['evaluation_labels']['label_2']); ?>"/></td>
                            </tr>
                            <tr>
                                <td><label for="label_3"><?php _e('Nome do terceiro valor (3)', 'consulta'); ?></label></td>
                                <td><input type="text" id="label_3" class="text" name="theme_options[evaluation_labels][label_3]" value="<?php echo htmlspecialchars($options['evaluation_labels']['label_3']); ?>"/></td>
                            </tr>
                            <tr>
                                <td><label for="label_4"><?php _e('Nome do quarto valor (4)', 'consulta'); ?></label></td>
                                <td><input type="text" id="label_4" class="text" name="theme_options[evaluation_labels][label_4]" value="<?php echo htmlspecialchars($options['evaluation_labels']['label_4']); ?>"/></td>
                            </tr>
                            <tr>
                                <td><label for="label_5"><?php _e('Nome do quinto valor (5)', 'consulta'); ?></label></td>
                                <td><input type="text" id="label_5" class="text" name="theme_options[evaluation_labels][label_5]" value="<?php echo htmlspecialchars($options['evaluation_labels']['label_5']); ?>"/></td>
                            </tr>
                        </table>
                        
                        <h3><?php _e('Tipo de resultado', 'consulta'); ?></h3>
                    
                        <input type="radio" name="theme_options[evaluation_type]" id="evaluation_type_percentage" value="percentage" class="radio_evaluation_type" <?php checked('percentage', $options['evaluation_type']); ?>/>
                        <label for="evaluation_type_percentage"><b>Porcentagem de cada resposta</b></label><br/>
                        <br/>
                        
                        <input type="radio" name="theme_options[evaluation_type]" id="evaluation_type_average" value="average" class="radio_evaluation_type" <?php checked('average', $options['evaluation_type']); ?>/>
                        <label for="evaluation_type_average"><b>Média das respostas</b></label><br/>
                        <br/>
                        
                        <p>Exemplo de resultado:</p>
                        <div id="exemplo_resultado">
                            <?php html::image('ex_avaliacao_perce.png', __('Exemplo de resultado', 'consulta'), array('id' => 'perce') ); ?>
                            <?php html::image('ex_avaliacao_media.png', __('Exemplo de resultado', 'consulta'), array('id' => 'media') ); ?>
                        </div>
                        
                    </div>
                    
                </div>
            </div>
            </div>
            
            <div id="aba-outras-container" class="aba-container">
            <div class="span-20 ">
                <div class="span-6 last">
                    <h3><?php echo __('Opções Gerais', 'consulta'); ?></h3>
                    
                    <table class="wp-list-table widefat fixed">
                        
                        <tr>
                        <td><label for="pagina_participe"><?php _e('Página com instruções para participação', 'consulta'); ?></label></td>
                        <td>
                            <p><?php _e('Selecione uma página para ativar o botão "Participe" na sua barra lateral.', 'consulta'); ?></p>
                            <?php wp_dropdown_pages(array(
                                'name' => 'theme_options[pagina_participe]',
                                'selected' => $options['pagina_participe'],
                                'show_option_none' => 'Não mostrar botão "Participe"'
                            )); ?>
                        </td>
                        </tr>
                        <tr>
                        <td><label for="data_encerramento"><?php _e('Data de encerramento da consulta', 'consulta'); ?></label></td>
                        <td><input type="text" id="data_encerramento" class="text" name="theme_options[data_encerramento]" value="<?php echo htmlspecialchars($options['data_encerramento']); ?>"/></td>
                        </tr>
                    </table>
                </div>
            </div>
            </div>
            
            <p class="textright clear prepend-top">
                <input type="submit" class="button-primary" value="<?php _e('Save Changes'); ?>" />
            </p>
        </form>
    </div>

<?php 

}

function theme_options_page_destaques_callback() {
        
?>
    <div class="wrap span-20">
        <h2><?php echo __('Theme Options', 'consulta'); ?></h2>

        <form action="options.php" method="post" class="clear prepend-top">
            <?php settings_fields('theme_options_destaques'); ?>
            <?php $options = get_option('destaques'); ?>
            
            <div class="span-20 ">
                <div class="span-6 last">
                    
                    <h2>Destaque principal</h2>
                    <table>
                        <tr>
                        <td><label for="1_titulo">Título</label></td>
                        <td><input type="text" id="1_titulo" class="text" name="destaques[1_titulo]" value="<?php echo htmlspecialchars($options['1_titulo']); ?>"/></td>
                        </tr>
                        
                        <tr>
                        <td><label for="1_data">Data</label></td>
                        <td><input type="text" id="1_data" class="text" name="destaques[1_data]" value="<?php echo htmlspecialchars($options['1_data']); ?>"/></td>
                        </tr>
                        
                        <tr>
                        <td><label for="1_link">Link</label></td>
                        <td><input type="text" id="1_link" class="text" name="destaques[1_link]" value="<?php echo htmlspecialchars($options['1_link']); ?>"/></td>
                        </tr>
                        
                        <tr>
                        <td><label for="1_imagem">Imagem 230x176(opcional)</label></td>
                        <td><input type="text" id="1_imagem" class="text" name="destaques[1_imagem]" value="<?php echo htmlspecialchars($options['1_imagem']); ?>"/></td>
                        </tr>
                        
                        <tr>
                        <td><label for="1_txt">Texto</label>
                        <textarea id="1_txt" class="text" name="destaques[1_txt]" ><?php echo htmlspecialchars($options['1_txt']); ?></textarea>
                        </tr>
                    </table>
                    
                    <h2>Destaque 2</h2>
                    <td><label for="2_titulo">Título</label></td>
                    <td><input type="text" id="2_titulo" class="text" name="destaques[2_titulo]" value="<?php echo htmlspecialchars($options['2_titulo']); ?>"/></td>
                    
                    <td><label for="2_data">Data</label></td>
                    <td><input type="text" id="2_data" class="text" name="destaques[2_data]" value="<?php echo htmlspecialchars($options['2_data']); ?>"/></td>
                    
                    <td><label for="2_link">Link</label></td>
                    <td><input type="text" id="2_link" class="text" name="destaques[2_link]" value="<?php echo htmlspecialchars($options['2_link']); ?>"/></td>
                    
                    <td><label for="2_imagem">Imagem 270x132 (opcional)</label></td>
                    <td><input type="text" id="2_imagem" class="text" name="destaques[2_imagem]" value="<?php echo htmlspecialchars($options['2_imagem']); ?>"/></td>
                    
                    <td><label for="2_txt">Texto</label>
                    <textarea id="2_txt" class="text" name="destaques[2_txt]" ><?php echo htmlspecialchars($options['2_txt']); ?></textarea>
                    
                    
                    <h2>Destaque 3</h2>
                    <td><label for="3_titulo">Título</label></td>
                    <td><input type="text" id="3_titulo" class="text" name="destaques[3_titulo]" value="<?php echo htmlspecialchars($options['3_titulo']); ?>"/></td>
                    
                    <td><label for="3_data">Data</label></td>
                    <td><input type="text" id="3_data" class="text" name="destaques[3_data]" value="<?php echo htmlspecialchars($options['3_data']); ?>"/></td>
                    
                    <td><label for="3_link">Link</label></td>
                    <td><input type="text" id="3_link" class="text" name="destaques[3_link]" value="<?php echo htmlspecialchars($options['3_link']); ?>"/></td>
                    
                    <td><label for="3_imagem">Imagem 270x132 (opcional) </label></td>
                    <td><input type="text" id="3_imagem" class="text" name="destaques[3_imagem]" value="<?php echo htmlspecialchars($options['3_imagem']); ?>"/></td>
                    
                    <td><label for="3_txt">Texto</label>
                    <textarea id="3_txt" class="text" name="destaques[3_txt]" ><?php echo htmlspecialchars($options['3_txt']); ?></textarea>
                    
                </div>
            </div>
            
            <p class="textright clear prepend-top">
                <input type="submit" class="button-primary" value="<?php _e('Save Changes'); ?>" />
            </p>
        </form>
    </div>

<?php } ?>
