<li>
    <div class="interaction clearfix">
        <header>
            <h1>
                <a href="<?php the_permalink();?>" title="<?php the_title_attribute();?>"><?php the_title();?></a>
            </h1>
    
            <div class="clear"></div>
        </header>

        <div class="comments-number" title="<?php comments_number('nenhum comentário','1 comentário','% comentários');?>"><?php comments_number('0','1','%');?></div>
        <?php if (get_theme_option('use_evaluation')) : ?>
            <?php html::part('show_evaluation'); ?>
        <?php endif; ?>
    </div>
    <?php if (get_theme_option('evaluation_show_on_list')) : ?>
        <div class="evaluation_container" style="display: none;">
            <?php html::part('evaluation')?>
        </div>
    <?php endif; ?>
</li>