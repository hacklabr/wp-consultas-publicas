<?php

// exibe o gráfico da votação na página de relatórios no admin

if (current_user_can('manage_options')) :
    $evaluation_type = get_theme_option('evaluation_type');
    $evaluationOptions = get_theme_option('evaluation_labels');
    $votes = get_votes($item->ID);
    
    if ($evaluation_type == 'percentage'):
        $perceVotes = consulta_get_votes_percentage($votes); ?>
        
        <div id="evaluation_bars" class="clear evaluation_container" style="display: none;">
            <?php
            $i = 0;
            foreach ($evaluationOptions as $key => $value) :
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
        
                <?php $i ++;
            endforeach; ?>
        </div>
    <?php elseif($evaluation_type == 'average'): ?>
        <?php $widthItem = consulta_get_width_item(); ?>
        <?php $numAlternatives = consulta_get_number_alternatives(); ?>
        <?php $average = consulta_get_votes_average($votes); ?>
        <?php $averageWidth =  ($average  * 100) / $numAlternatives; ?>
        
        <div id="evaluation_scale" class="clear evaluation_container" style="display: none;">
            <div id="evaluation_bar_bg" >
                <div class="evaluation_bar" style="width: <?php echo $averageWidth; ?>%;"></div>
            </div>
            
            <?php
            $i = 1;
            foreach ($evaluationOptions as $key => $value) :
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
        </div>
    <?php endif; ?>
<?php endif; ?>