<?php if (get_theme_option('use_evaluation') && consulta_get_number_alternatives() > 0) :
    $postId = get_the_ID();
    $evaluationOptions = get_theme_option('evaluation_labels');
    $userVote = str_replace('_label_', 'label_', get_user_vote($postId));
    $votes = get_votes($postId);
    $evaluation_type = get_theme_option('evaluation_type');
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
                    <?php
                    $ii = 0;
                    foreach ($evaluationOptions as $key => $value) :
                        if (empty($value)) {
                            break;
                        }
                        ?>
    
                        <div class="clear">
                            
                            <label><?php echo $value; ?>: <?php echo $votes[$ii]; ?> (<?php echo $perceVotes[$ii]; ?>%)</label>
                            <div id="evaluation_bar_bg" >
                                <div class="evaluation_bar" style="width: <?php echo $perceVotes[$ii]; ?>%;"></div>
                            </div>
                        </div>
                
                        <?php $ii ++;
                    endforeach; ?>
                </div>
            <?php elseif($evaluation_type == 'average'): ?>
                <?php $widthItem = consulta_get_width_item(); ?>
                <?php $numAlternatives = consulta_get_number_alternatives(); ?>
                <?php $average = consulta_get_votes_average($votes); ?>
                <?php $averageWidth =  ($average  * 100) / $numAlternatives; ?>
                
                <div id="evaluation_scale" class="clear">
                    <h5>Média de <?php echo array_sum($votes); ?> votos: <?php echo $average; ?></h5>
                    
                    <div id="evaluation_bar_bg" >
                        <div class="evaluation_bar" style="width: <?php echo $averageWidth; ?>%;"></div>
                    </div>
                    
                    <?php
                    $ii = 1;
                    foreach ($evaluationOptions as $key => $value) :
                        if (empty($value)) {
                            break;
                        }
                        ?>
                        
                        <div class="evaluation_average_label" style="width: <?php echo $widthItem; ?>%;">
                            <div class="evaluation_average_marker"></div>
                            <p><?php echo $ii, '. ', $value; ?></p>
                        </div>
                        <?php $ii++;
                    endforeach; ?>
                    
                    <div class="clear"></div>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    
        <?php if (is_user_logged_in()): ?>
            <?php $can_vote = current_user_can_vote() || $userVote; ?>
            
            <div class="user_evaluation">
                <h5><?php _e('Minha avaliação', 'consulta'); ?></h5>
                    
                <form id="object_evaluation">
                    
                    <input type="hidden" name="post_id" value="" />
                    <?php if($can_vote && evaluation_allow_remove_votes()) $evaluationOptions = array('0' => "Não avaliar") + $evaluationOptions ?>

                    <?php foreach ($evaluationOptions as $key => $value) : ?>
                        <?php if (empty($value)) break; ?>

                        <div class="list_object">
                            <label>
                                <input type="radio" value="<?php echo $key; ?>" data-post_id="<?php the_ID(); ?>" name="object_evaluation" <?php checked($userVote == $key); ?> <?php if(!$can_vote) echo 'disabled="disabled"' ?> />
                                <?php echo $value; ?>
                            </label>
                            <div class="object_evaluation_feedback" style="display: inline;"><img style="float: left; margin-left: 5px;" src="<?php bloginfo('stylesheet_directory'); ?>/img/accept.png" alt="" /></div>
                        </div>
                    <?php endforeach; ?>
                </form>
                
                <?php if(!$can_vote) :?>
                    <em><?php _e('Você não pode avaliar este objeto porque você já atingiu o limite de avaliações para esta consulta.','consulta') ?></em>
                <?php endif; ?>
            </div>
            
        <?php else: ?>
            <p><?php _e('Para avaliar é necessário estar cadastrado e ter efetuado login.', 'consulta'); ?></p>
        <?php endif; ?>
    </div>
<?php endif; ?>