<?php

add_action('admin_menu', 'exportador_comentarios_menu');

function exportador_comentarios_menu() {
    add_submenu_page('theme_options', 'Exportar comentários', 'Exportar comentários', 'manage_options', 'export_comments', 'exportador_comentarios_page_callback_function');
}

function exportador_comentarios_page_callback_function() {
    ?>
    <div class="wrap span-20">
        <h2><?php echo __('Exportar Comentários', 'consulta'); ?></h2>

        <p><?php _e('Utilize está página para exportar uma tabela do Excel com informações de todos os comentários feitos pelos usuários nos objetos. Se desejar, é possível filtrar a lista para exportar apenas os comentários criados num determinado período de tempo.', 'consulta'); ?></p>
    
        <form method="post" action="<?php echo get_template_directory_uri(); ?>/includes/exportador-comentarios-xls.php" class="clear prepend-top">
            <div class="span-20 ">
                <div class="span-6 last">
                    <br/>
                    <input type="checkbox" name="periodo" id="period">
                    <label for="period">Exportar por período</label>
                    <div id="select_period" style="display: none;">
                        <br/>
                        <label for="data_inicial"><strong>Data inicial</strong></label><br/>
                        <input type="text" id="data_inicial" class="text select_date" name="data_inicial" />
                        <br/><br/>
                        <label for="data_final"><strong>Data final</strong></label><br/>
                        <input type="text" id="data_final" class="text select_date" name="data_final" />
                    </div>
                </div>
            </div>
            <p class="clear prepend-top">
                <input type="submit" class="button-primary" value="Exportar" />
            </p>
        </form>
    </div>
    <?php 
}
