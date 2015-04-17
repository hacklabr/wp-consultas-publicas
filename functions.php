<?php 

include dirname(__FILE__).'/includes/congelado-functions.php';
include dirname(__FILE__).'/includes/html.class.php';
include dirname(__FILE__).'/includes/utils.class.php';

include dirname(__FILE__).'/includes/exportador-comentarios.php';
include dirname(__FILE__).'/includes/exportador-objetos-sugeridos.php';
include dirname(__FILE__).'/includes/exportador-avaliacoes.php';
include dirname(__FILE__).'/includes/relatorio.php';

add_action( 'after_setup_theme', 'consulta_setup' );
function consulta_setup() {
    load_theme_textdomain('consulta', TEMPLATEPATH . '/languages' );
    
    // POST THUMBNAILS
    add_theme_support('post-thumbnails');
    set_post_thumbnail_size( 230, 176, true );
    add_image_size('home-highlight', 230, 176);
    add_image_size('home-secondary-highlight', 270, 132);
    
//    REGISTRAR AQUI TODOS OS TAMANHOS UTILIZADOS NO LAYOUT
//    add_image_size('nome',X,Y);
//    add_image_size('nome2',X,Y);
    
    add_theme_support('custom-background');

    // CUSTOM IMAGE HEADER
    define('HEADER_TEXTCOLOR', '000000');
    define('HEADER_IMAGE_WIDTH', 950);
    define('HEADER_IMAGE_HEIGHT', 105);
    
    add_theme_support(
        'custom-header',
        array(
            'header-text' => true,
            'flex-width'    => true,
            'width'         => 950,
            'flex-height'    => true,
            'height'        => 105,
            'uploads'       => true,
            'wp-head-callback' => 'consulta_custom_header',
            'default-text-color' => '000000'
        )
    );
    
    function consulta_custom_header() {
        $custom_header = get_custom_header();
        ?>
        <style type="text/css">
                    
            #branding { background: url(<?php header_image(); ?>) no-repeat; height: <?php echo $custom_header->height; ?>px;}
            <?php if ( 'blank' == get_header_textcolor() ) : ?>
                #branding a { height: <?php echo $custom_header->height; ?>px; }
                #branding a:hover { background: none !important; }    
            <?php else: ?>       
                #branding, #branding a, #branding a:hover { color: #<?php header_textcolor(); ?> !important; }
                #branding a:hover { text-decoration: none; }
                #description { filter: alpha(opacity=60); opacity: 0.6; }
            <?php endif; ?>        
        </style>
        <?php
    }
    
    // AUTOMATIC FEED LINKS
    add_theme_support('automatic-feed-links');
}

// admin_bar removal
remove_action('wp_footer','wp_admin_bar_render',1000);
function remove_admin_bar(){
   return false;
}
add_filter( 'show_admin_bar' , 'remove_admin_bar');

add_action('admin_enqueue_scripts', function() {
    wp_enqueue_script('jquery-ui-datepicker');
    wp_enqueue_style('jquery-ui-custom', get_template_directory_uri() . '/css/ui-lightness/jquery-ui-1.9.1.custom.min.css');
    wp_enqueue_script('consulta-datepicker', get_template_directory_uri() . '/js/consulta-datepicker.js', array('consulta'));
    
    if (get_current_screen()->id == 'opcoes-da-consulta_page_export_comments'
        || get_current_screen()->id == 'opcoes-da-consulta_page_exportador_objetos_sugeridos')
    {
        wp_enqueue_script('consulta-exportador', get_template_directory_uri() . '/js/consulta-exportador.js', array('jquery'));
    }
});

// JS
function consulta_addJS() {
    global $wp_query;
    
    wp_enqueue_script('scrollto', get_template_directory_uri() . '/js/jquery.scrollTo-1.4.2-min.js',array('jquery'));
    wp_enqueue_script('consulta', get_template_directory_uri() . '/js/consulta.js',array('jquery', 'scrollto'));
    wp_localize_script('consulta', 'consulta', array( 'ajaxurl' => admin_url('admin-ajax.php') ));
    wp_enqueue_script('hl', get_template_directory_uri() . '/js/hl.js', array('consulta'));
    
    if (get_post_type() == 'object') {
        wp_enqueue_script('evaluation', get_template_directory_uri() . '/js/evaluation.js', array('jquery'));
    }
    
    if (is_singular()) {
        wp_enqueue_script( 'comment-reply' );
    }
    
    wp_enqueue_style('evaluation', get_template_directory_uri() . '/css/evaluation.css');
    
    if ($wp_query->get('tpl') == 'novo') {
        wp_enqueue_script('consulta-object-new', get_stylesheet_directory_uri() . '/js/consulta-object-new.js', array('jquery'));
    }
}
add_action('wp_print_scripts', 'consulta_addJS');

// paginas customizadas

add_filter('query_vars', 'consulta_custom_query_vars');
add_filter('rewrite_rules_array', 'consulta_custom_url_rewrites');
add_action('template_redirect', 'consulta_template_redirect_intercept');

function consulta_custom_query_vars($public_query_vars) {
    $public_query_vars[] = "tpl";

    return $public_query_vars;
}

function consulta_custom_url_rewrites($rules) {
    $new_rules = array(
        "novo/?$" => "index.php?tpl=novo",
    );

    return $new_rules + $rules;
}

function consulta_template_redirect_intercept() {
    global $wp_query;

    switch ($wp_query->get('tpl')) {
        case 'novo':
            $wp_query->is_home = false;
            
            require(TEMPLATEPATH . '/tpl-novo.php');
            die;
        default:
            break;
    }
}


// CUSTOM MENU
add_action( 'init', 'consulta_custom_menus' );
function consulta_custom_menus() {
	register_nav_menus( array(
		'principal' => __('Principal', 'consulta'),
	) );
}

// SIDEBARS
if(function_exists('register_sidebar')) {
    // sidebar 
    register_sidebar( array(
		'name' =>  'Sidebar',		
		'description' => __('Sidebar', 'consulta'),
		'before_widget' => '<div id="%1$s" class="widget %2$s clearfix">',
		'after_widget' => '</div>',
		'before_title' => '<h3 class="subtitulo">',
		'after_title' => '</h3>',
	) );
	
}

// EXCERPT MORE
function consulta_auto_excerpt_more( $more ) {
	global $post;
	return '...<br /><a class="more-link" href="'. get_permalink($post->ID) . '">Continue lendo &raquo;</a>';
}

add_filter( 'excerpt_more', 'consulta_auto_excerpt_more' );


// COMMENTS

if (!function_exists('consulta_comment')): 

function consulta_comment($comment, $args, $depth) {
    $GLOBALS['comment'] = $comment;
    
    $autor = get_userdata($comment->user_id);
    global $wpdb;
    $level_var = $wpdb->prefix . 'user_level';
    
    if ($autor) {
        $moderador = (int) $autor->$level_var >= 6;
    }
    
    $commentClass = 'clearfix';
    
    if (isset($moderador)) {
        $commentClass = 'clearfix conselheiro';
    }
    
    ?>
    <li <?php comment_class($commentClass); ?> id="comment-<?php comment_ID(); ?>"  > 
		<div class="content clearfix">
        <p class="comment-meta bottom">
          <?php echo get_comment_date() . ' às ' . get_comment_time() ; ?>                  
        </p>
        
        <?php //echo get_avatar($comment, 44); ?>
        
			<?php if($comment->comment_approved == '0') : ?><br/><em><?php _oi('Seu comentário está aguardando moderação', 'Comentários: Texto que aparece para o usuário quando o seu comentário fica em moderação'); ?></em><?php endif; ?>
          <?php comment_text(); ?>          
        
        <p class="comment-meta">
            <span class="comment-author"><?php echo is_object($autor) ? $autor->display_name : ''; ?></span>
        </p> 
        <p class="comment-meta">            
			<?php comment_reply_link(array('depth' => $depth, 'max_depth' => $args['max_depth'])) ?> <?php edit_comment_link( __('Edit', 'consulta'), '| ', ''); ?>
        </p>
        </div>
    </li>
    <?php
}

endif; 

////////////////////

function print_msgs($msg, $extra_class='', $id=''){
    if (!is_array($msg)) {
        return false;
    }

    foreach($msg as $type=>$msgs) {
        if (!$msgs) {
            continue;
        }
        
        echo "<div class='$type $extra_class' id='$id'><ul>";

        if (!is_array($msgs)) {
            echo "<li>$msgs</li>";
        } else {
            foreach ($msgs as $m) {
                echo "<li>$m</li>";
            }
        }
        echo "</ul></div>";
    }
}


if (!function_exists('_oi')) {

    function _oi($str) {
        echo $str;
    }

    function __i($str) {
        return $str;
    }

}


#pega o numero de pessoas que comentaram em um objeto
function get_num_pessoas_comentarios($post_id) {
    
    if (!is_numeric($post_id))
        return false;
    
    global $wpdb;
    
    return $wpdb->get_var("SELECT COUNT( DISTINCT(user_id) ) FROM $wpdb->comments WHERE comment_post_ID = $post_id AND comment_approved = 1");
    

}



add_filter('the_title', 'quebra_linha_titulo_meta');

function quebra_linha_titulo_meta($title) {
    
    return str_replace('*', '<br />', $title);

}

function is_consulta_encerrada() {

    $datafinal = strtotime(get_theme_option('data_encerramento'));

    $encerrada = true;
    
    if (get_theme_option('data_encerramento') == date('Y-m-d'))
        $encerrada = false;
    
    if ($datafinal) {
        if ($datafinal > time())
            $encerrada = false;
    }
    
    return $encerrada;
    
}

add_action('customize_register', 'consulta_customize_register');
/**
 * Setup options that can be altered in the
 * theme customizer.
 * 
 * @param WP_Customize_Manager $wp_customize
 * @return null
 */
function consulta_customize_register($wp_customize) {
    $wp_customize->add_setting('consulta_theme_options[link_color]',
        array(
            'default' => '#00A0D0',
            'type' => 'option',
        )
    );

    $wp_customize->add_control(
        new WP_Customize_Color_Control($wp_customize, 'link_color',
            array(
                'label' => __('Link color', 'consulta'),
                'section' => 'colors',
                'settings' => 'consulta_theme_options[link_color]',
            )
        )
    );

    $wp_customize->add_setting('consulta_theme_options[title_color]',
        array(
            'default' => '#006633',
            'type' => 'option',
        )
    );
    
    $wp_customize->add_control(
        new WP_Customize_Color_Control($wp_customize, 'title_color',
            array(
                'label' => __('Title color', 'consulta'),
                'section' => 'colors',
                'settings' => 'consulta_theme_options[title_color]',
            )
        )
    );
}

/**
 * Retorna as cores para os títulos e links
 * do site.
 * 
 * @return array na primeira posição a cor dos links e na segunda a cor dos títulos 
 * 
 */
function consulta_get_theme_colors() {
    $colors = array();
    $options = get_option('consulta_theme_options');
    
    $colors[] = isset($options['link_color']) ? $options['link_color'] : '#00A0D0';
    $colors[] = isset($options['title_color']) ? $options['title_color'] : '#006633';
    
    return $colors;
}

/**
 * Add to the header the CSS elements that can
 * be altered dinamically with the theme customizer
 */
add_action('wp_print_styles', function() {
    list($linkColor, $titleColor) = consulta_get_theme_colors();
    ?>
    <style>
    /* Colors */

    a { color: <?php echo $linkColor; ?>; }
    #main-menu > li > a:hover, #temas-item:hover, #temas-item.active, #main-menu > li.current-menu-item > a, #main-menu > li.current-menu-ancestor > a, #main-menu > li.current-menu-parent > a, #main-menu > li.current_page_item > a, #main-menu li.active > a { background: <?php echo $linkColor; ?>; }
    .post .interaction .commenters-number { color: <?php echo $linkColor; ?>; }
    .page-link a:hover, .page-link span.current { background: <?php echo $linkColor; ?>; }
    .page-link a.prev:hover, .page-link a.next:hover { color: <?php echo $linkColor; ?>; }
    .tabs li.current { color: <?php echo $linkColor; ?>; }
    .tabs li:hover { color: <?php echo $linkColor; ?>; }
    .meta-sugerida-info { color: <?php echo $linkColor; ?>; }
    .post-content tr th, .post-content thead th { background: <?php echo $linkColor; ?>; }
    .commentlist li.delegado .content, .commentlist li.conselheiro .content { border-left: 4px solid <?php echo $linkColor; ?>; }
    #comentar, #cancel-comment-reply-link, .button-submit { background: <?php echo $linkColor; ?>; }
    .alteracao, .comment-author { color: <?php echo $linkColor; ?>; }
    .tema .interaction .commenters-number { color: <?php echo $linkColor; ?>; }
    #wp-calendar caption { background: <?php echo $linkColor; ?>; }
    #wp-calendar thead th { color: <?php echo $linkColor; ?>; }
    .blue-button { background: <?php echo $linkColor; ?>; }
    .gray-button { color: <?php echo $linkColor; ?>; }
    .post .interaction .comments-number { background-color: <?php echo $linkColor; ?>; }
    .post .interaction span.commenters-number-icon { background-color: <?php echo $linkColor; ?>; }
    .tema .interaction .comments-number { background-color: <?php echo $linkColor; ?>; }
    .tema .interaction .commenters-number span.commenters-number-icon { background-color: <?php echo $linkColor; ?>; }
    .interaction .show_evaluation span.count_object_votes_icon { background-color: <?php echo $linkColor; ?>; }
    .interaction .show_evaluation { color: <?php echo $linkColor; ?> }
    h1,h2,h3,h4,h5,h6 { color: <?php echo $titleColor; ?>; }
    #cronometro span { color: <?php echo $titleColor; ?>; }
    .post-content label { color: <?php echo $titleColor; ?>; }
    #respond label { color: <?php echo $titleColor; ?>; }
    .acao-numero { color: <?php echo $titleColor; ?>; }
    #login, #cronometro { background-color: <?php echo $titleColor; ?>; }
    .participation-button { background-color: <?php echo $titleColor; ?>; }
    #feed-link { background-color: <?php echo $titleColor; ?>;}
    .hl-lightbox-close { background-color: <?php echo $titleColor; ?>; }
    
    .evaluation_bar { background-color: <?php echo $linkColor; ?> }
    
    #new_object .clearfix > label { color: <?php echo $titleColor; ?>; }
    #new_object_submit { background: <?php echo $titleColor; ?>; }
    #new_object_submit:hover { background: <?php echo $linkColor; ?>; }
    .suggested-user-icon{ background: <?php echo $linkColor; ?>; }

    </style>
    <?php
    
}, 20);

/**
 * Return the value of the current user vote
 * for the object evaluation.
 * 
 * @param int $postId
 * @return string
 */
function get_user_vote($postId) {
    global $wpdb;

    $userVote = $wpdb->get_var(
        $wpdb->prepare("SELECT `meta_key` FROM {$wpdb->postmeta} WHERE post_id = %d AND meta_value = %d", $postId, get_current_user_id())
    );

    return $userVote;
}

/**
 * Get all the users votes for the
 * object evaluation.
 * 
 * @param int $postId
 * @return array
 */
function get_votes($postId) {
    $votes = array();
    $evaluationOptions = get_theme_option('evaluation_labels');

    foreach (range(1, 5) as $i) {
        $label = 'label_' . $i;
        
        // somente considera os votos das opções de avaliação que estejam ativas
        if (!empty($evaluationOptions[$label])) {
            $votes[] = count(get_post_meta($postId, '_' . $label));
        } else {
            $votes[] = 0;
        }
    }
    
    return $votes;
}

/**
 * Retorna o id do usuário com o seu voto em um post.
 * Diferente da get_votes() que retorna apenas quantos votos
 * cada opção teve para um post.
 *
 * @param int $postId
 * @return array array cuja a chave é o id do usuário e o valor é o seu voto
 */
function get_votes_data($postId) {
    $votes = array();
    $evaluationOptions = get_theme_option('evaluation_labels');

    foreach (range(1, 5) as $i) {
        $label = 'label_' . $i;
        $optionVotes = get_post_meta($postId, '_' . $label);
        
        if (!empty($optionVotes)) {
            foreach($optionVotes as $userId) {
                if (!empty($evaluationOptions[$label])) {
                    // somente considera os votos das opções de avaliação que estejam ativas
                    $votes[$userId] = '_' . $label;
                }
            }
        }
    }

    return $votes;
}

/**
 * Retorna todos os votos para
 * todos os objetos
 * 
 * @return array
 */
function get_all_votes() {
    $votes = array();
    $objects = get_posts(array('post_type' => 'object', 'posts_per_page' => -1));
    
    foreach ($objects as $object) {
        $objectVotes = get_votes_data($object->ID);
        
        if (!empty($objectVotes)) {
            foreach ($objectVotes as $user_id => $vote) {
                $votes[] = array('post_id' => $object->ID, 'user_id' => $user_id, 'vote' => $vote);
            }
        }
    }
    
    return $votes;
}

/**
 * Retorna o label do voto de um usuário.
 * 
 * @param string $vote_id
 * @return string
 */
function get_vote_label($vote_id) {
    $evaluationLabels = get_theme_option('evaluation_labels');
    // remove o underscore que é usado para guardar o postmeta mas não é usado na opção evaluation_labels
    $vote_id = substr($vote_id, 1);
    
    if (isset($evaluationLabels[$vote_id])) {
        return $evaluationLabels[$vote_id];
    } else {
        throw new Exception("Label de voto '{$vote_id}' inválido.");
    }
}

/**
 * Return the number of votes in an
 * object.
 * 
 * @param int $postId
 * @return int
 */
function count_votes($postId) {
    $votes = 0;
    $evaluationOptions = get_theme_option('evaluation_labels');

    foreach (range(1, 5) as $i) {
        $label = 'label_' . $i;
        
        // somente considera os votos das opções de avaliação que estejam ativas
        if (!empty($evaluationOptions[$label])) {
            $votes += count(get_post_meta($postId, '_' . $label));
        }
    }
    
    return $votes;
}

/**
 * Verifica se usuário atual ainda pode votar: havendo limite, o número de votos deste usuário deve ser menor do que este limite
 * @global type $wpdb
 * @return boolean
 */
function current_user_can_vote(){
    $options = wp_parse_args( 
        get_option('theme_options'), 
        get_theme_default_options()
    );
    
    if(!$options['evaluation_limit'])
        return true;
    
    global $wpdb;
    
    $user_id = get_current_user_id();
    
    $num = intval($wpdb->get_var("SELECT COUNT(meta_id) FROM $wpdb->postmeta WHERE meta_key IN ( '_label_1' , '_label_2' , '_label_3' , '_label_4' , '_label_5' ) AND meta_value = '$user_id'"));
    
    return intval($options['evaluation_max_num']) > $num;
}

function evaluation_allow_remove_votes(){
    $options = wp_parse_args( 
        get_option('theme_options'), 
        get_theme_default_options()
    );
    
    return $options['evaluation_allow_remove'];
}

/**
 * Joga na tela o gráfico de barras das avaliações feitas pelos
 * usuários em um objeto da consulta.
 * 
 * @param int $postId o id do objeto avaliado
 */
function evaluation_build_bars_graph($postId) {
    $evaluationOptions = get_theme_option('evaluation_labels');
    $votes = get_votes($postId);
    $perceVotes = consulta_get_votes_percentage($votes);
    $i = 0;
    
    foreach ($evaluationOptions as $key => $value) {
        // se a opção for Não Avaliar (valor da chave é zero)
        if (!$key) {
            continue;
        }
        
        if (empty($value)) {
            break;
        }
        ?>

        <div class="clear">
            <label><?php echo $value; ?>: <?php echo $votes[$i]; ?> (<?php echo $perceVotes[$i]; ?>%)</label>
            <div id="evaluation_bar_bg" >
                <div class="evaluation_bar" style="width: <?php echo $perceVotes[$i]; ?>%;"></div>
            </div>
        </div>

        <?php $i++;
    }
}

/**
 * Joga na tela o gráfico de com a média das avaliações feitas
 * pelos usuários em um objeto da consulta.
 * 
 * @param int $postId o id do objeto avaliado
 */
function evaluation_build_scale_graph($postId) {
    $evaluationOptions = get_theme_option('evaluation_labels');
    $votes = get_votes($postId);
    $widthItem = consulta_get_width_item();
    $numAlternatives = consulta_get_number_alternatives();
    $average = consulta_get_votes_average($votes);
    $averageWidth =  ($average  * 100) / $numAlternatives;
    
    ?>
    <div id="evaluation_bar_bg" >
        <div class="evaluation_bar" style="width: <?php echo $averageWidth; ?>%;"></div>
    </div>
    
    <?php
    $i = 1;
    
    foreach ($evaluationOptions as $key => $value) :
        // se a opção for Não Avaliar (valor da chave é zero)
        if (!$key) {
            continue;
        }
        
        if (empty($value)) {
            break;
        }
        ?>
        
        <div class="evaluation_average_label" style="width: <?php echo $widthItem; ?>%;">
            <div class="evaluation_average_marker"></div>
            <p><?php echo $i, '. ', $value; ?></p>
        </div>
        <?php $i++;
    endforeach; ?>
    
    <div class="clear"></div>
    
    <?php
}

/**
 * Retorna o número de opções disponíveis para a avaliação
 * dos objetos da consulta.
 * 
 * @return int
 */
function evaluation_count_options() {
    $evaluationOptions = get_theme_option('evaluation_labels');
    $count = 0;
    
    foreach($evaluationOptions as $key => $option) {
        if (!empty($option) && $key !== 0) {
            $count++;
        }
    }
    
    return $count;
}

/**
 * Compute user vote for object evaluation
 */
add_action('wp_ajax_object_evaluation', function() {
    global $post;
    
    $data = array('voted'=>true);
    $userVote = filter_input(INPUT_POST, 'userVote', FILTER_SANITIZE_STRING);
    $postId = filter_input(INPUT_POST, 'postId', FILTER_SANITIZE_NUMBER_INT);

    if ($userOldVote = get_user_vote($postId)) {
        // se já votou, altera o voto deletando o antigo e inserindo novo se o voto for diferente de zero
        delete_post_meta($postId, $userOldVote, get_current_user_id());
        
        if ($userVote) {
            add_post_meta($postId, '_' . $userVote, get_current_user_id());
        }
    } elseif ($userVote && current_user_can_vote()) {
        // caso não tenha votado, só deixa votar se não atingiu o limite
        add_post_meta($postId, '_' . $userVote, get_current_user_id());
    } else {
        $data['voted'] = false;
    }

    $post = get_post($postId);
    
    ob_start();
    isset($_POST['in_list']) ? html::part('evaluation', array('in_list' => $_POST['in_list'])) : html::part('evaluation');
    
    $data['html'] = ob_get_clean();
    $data['count'] = count_votes($postId);
    
    die(json_encode($data));
});

function consulta_default_menu() {
    $objects_link = site_url( get_theme_option('object_url') );
    $object_ob = get_post_type_object('object');
    $objects_label = $object_ob->labels->name;
    ?>
        <ul id="main-menu" class="clearfix">
            <?php wp_list_pages('title_li='); ?>
            <li>
                <a href="<?php echo $objects_link; ?>"><?php echo $objects_label; ?></a>
            </li>
        </ul>
    <?php
    
}

function consulta_get_votes_percentage($votes) {
    if (!is_array($votes) || sizeof($votes) < 5) {
        return -1;
    }
        
    $sum = array_sum($votes); 
    
    if ($sum < 1) {
        return array(0,0,0,0,0);
    }
           
    $return = array();
    
    foreach ($votes as $k => $vote) {
        $return[$k] = number_format(($vote / $sum) * 100, 1);
    }
    
    return $return;
}

function consulta_get_votes_average($votes) {
    if (!is_array($votes) || sizeof($votes) < 5) {
        return -1;
    }
        
    $sum = array_sum($votes); 
    $value = 0;
    
    if ($sum < 1)
        return 0;
    
    $value += $votes[0] * 1;
    $value += $votes[1] * 2;
    $value += $votes[2] * 3;
    $value += $votes[3] * 4;
    $value += $votes[4] * 5;
    
    return number_format($value / $sum, 1);
}

function consulta_get_width_item() {
    return 100 / consulta_get_number_alternatives();
}

function consulta_get_number_alternatives() {
    $evaluationOptions = get_theme_option('evaluation_labels');
    $i = 0;
    
    foreach ($evaluationOptions as $key => $value) {
        // se a opção for Não Avaliar (valor da chave é zero)
        if( ! $key )
            continue;
        
        if (empty($value)) break;
        $i++;
    }
    
    return $i;
}

/*
 * Configurações customizadas antes de pegar os
 * posts que serão exibidos.
 * 
 * @param WP_Query $query
 * @return null
 */
function consulta_pre_get_posts($query) {
    if (is_admin()) {
        return;
    }

    if ($query->get('post_type') == 'object' && $query->is_archive) {
        // não página a listagem de objetos quando exibe apenas o título.
        if (get_theme_option('list_type') == 'title') {
            $query->set('posts_per_page', -1);
        }
        
        if ($query->is_main_query() && (get_theme_option('list_type') == 'title' || get_theme_option('list_type') == 'title_taxonomy')) {
            // não exibe objetos criados pelo usuário na listagem padrão quando exibe apenas o título ou título organizado por taxonomia
            $query->set('meta_key', '_user_created');
            $query->set('meta_value', false);
        }
        
        // permite que o admin defina a ordenação dos objetos
        $query->set('order', get_theme_option('list_order'));
        $query->set('orderby', get_theme_option('list_order_by'));
    }
    
    // exibe na pagina do autor os objetos que ele sugeriu
    if ($query->is_author && get_theme_option('allow_suggested')) {
        $query->set('post_type', 'object');
    }
}
add_action('pre_get_posts', 'consulta_pre_get_posts', 1);

add_action('consulta_show_user_link', 'consulta_show_user_link');
/**
 * Exibe no header um link para o perfil do usuário.
 * 
 * @return null
 */
function consulta_show_user_link() {
    global $current_user;

    ?>
    <div id="logged-user-name">
        <a href="<?php echo get_edit_profile_url($current_user->ID); ?>">
            <?php echo substr($current_user->display_name, 0, 38); ?>
        </a>
    </div>
    <?php
}

// exibe aviso para o admin caso os permalinks estejam desabilitados
add_action('admin_notices', function() {
    if (!get_option('permalink_structure') && current_user_can('manage_options')) {
        echo '<div id="message" class="error"><p><strong>O módulo de Consultas Públicas depende da estrutura de permalinks habilitada para funcionar corretamente. Por favor altere esta configuração na página <a href="' . admin_url('options-permalink.php') . '">Links permanentes</a>. Você pode escolher qualquer uma das opções, exceto a primeira chamada "Padrão".</strong><p></div>';
    }
});

/**
 * hook para apagar registros de votos caso o item seja movido para o lixo
 *
 * @return null
 */
add_action( 'delete_post', 'consulta_trash_votes');
function consulta_trash_votes($pid) {
    global $wpdb;
    $table = ($wpdb->postmeta) ? $wpdb->postmeta : '';
    if ($wpdb->get_var($wpdb->prepare( "SELECT COUNT(meta_id) FROM $table WHERE meta_key IN ( '_label_1' , '_label_2' , '_label_3' , '_label_4' , '_label_5' ) AND post_id = %d", $pid))) {
      return $wpdb->query( $wpdb->prepare( "DELETE FROM $table WHERE meta_key IN ( '_label_1' , '_label_2' , '_label_3' , '_label_4' , '_label_5' ) AND post_id = %d", $pid));
    }
    return true;
}