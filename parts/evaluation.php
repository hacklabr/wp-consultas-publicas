<?php if (get_theme_option('use_evaluation') && consulta_get_number_alternatives() > 0) :
    $postId = get_the_ID();
    $evaluationOptions = get_theme_option('evaluation_labels');
    $userVote = str_replace('_label_', 'label_', get_user_vote($postId));
    $votes = get_votes($postId);
    $evaluation_type = get_theme_option('evaluation_type');
    $in_list = isset($in_list) ? $in_list : false;
    
    ?>

    <div class="evaluation clearfix">
        <?php if (is_single()) : ?>
            <h3 class="subtitulo"><?php _e('Avaliação', 'consulta'); ?></h3>
        <?php endif; ?>

        <p><?php echo get_theme_option('evaluation_text'); ?></p>

        <?php if (get_theme_option('evaluation_public_results') || current_user_can('manage_options')) : ?>
            <?php if ($evaluation_type == 'percentage'): ?>
                <?php $perceVotes = consulta_get_votes_percentage($votes); ?>
                
                <div id="evaluation_bars" class="clear">
                    <h5><?php _e('Resultado até o momento', 'consulta'); ?></h5>
                    <?php evaluation_build_bars_graph($postId); ?>
                </div>
            <?php elseif($evaluation_type == 'average'): ?>
                <?php $average = consulta_get_votes_average($votes); ?>
                
                <div id="evaluation_scale" class="clear">
                    <h5>Média de <?php echo array_sum($votes); ?> votos: <?php echo $average; ?></h5>
                    <?php evaluation_build_scale_graph($postId); ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    
        <?php if (is_user_logged_in()): ?>
            <?php $can_vote = current_user_can_vote() || $userVote; ?>
            
            <div class="user_evaluation">
                <h5><?php _e('Minha avaliação', 'consulta'); ?></h5>
                    
                <form id="object_evaluation">

                    <input type="hidden" name="post_id" value="" />

                    <?php foreach ($evaluationOptions as $key => $value) : ?>
                        <?php if (empty($value)) break; ?>

                        <div class="list_object <?php if( ! $key ) echo 'nao-avaliar'; ?>">
                            <label> 
                                <input type="radio" value="<?php echo $key; ?>" data-post_id="<?php the_ID(); ?>" data-in_list="<?php echo $in_list ? "1" : "" ?>" name="object_evaluation" <?php checked($userVote == $key); ?> <?php if(!$can_vote && !$in_list) echo 'disabled="disabled"' ?> />
                                <?php echo $value; ?>
                            </label>
                            <div class="object_evaluation_feedback" style="display: none;"><img style="float: left; margin-left: 5px;" src="<?php bloginfo('stylesheet_directory'); ?>/img/accept.png" alt="" /></div>
                        </div>
                    <?php endforeach; ?>

                    <div class="object_evaluation_response_feedback" style="display: none;"><p style="padding: 10px; border: 1px solid #006633;"><?php echo get_theme_option('evaluation_response'); ?></p></div>
                </form>
                
                <?php if(!$can_vote) :?>
                    <em><?php echo get_theme_option('evaluation_limit_msg'); ?></em>
                <?php endif; ?>
            </div>
            
        <?php else: ?>
            <p><?php _e('Para avaliar é necessário estar cadastrado e ter efetuado login.', 'consulta'); ?></p>
        <?php endif; ?>
    </div>
<?php endif; ?>
