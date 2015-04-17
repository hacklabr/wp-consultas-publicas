jQuery(document).ready(function() {
    // voto do usuario em uma das opcoes da avaliacao de um objeto
    jQuery('#object_evaluation input').live('click', function() {
        var radioButton = jQuery(this);
        jQuery('body').css('cursor', 'progress');
        
        jQuery.ajax({
            url: consulta.ajaxurl,
            type: 'post',
            data: {action: 'object_evaluation', in_list: jQuery(this).data('in_list'), userVote: jQuery(this).val(), postId: jQuery(this).data('post_id') },
            dataType: 'json',
            success: function(data) {
                var value = radioButton.val();
                var $container = radioButton.closest('.evaluation_container');
                jQuery('body').css('cursor', 'auto');
                radioButton.closest('li').find('.count_object_votes').html(data.count);
                $container.html(data.html);
                if(data.voted){
                    $container.find('input[type="radio"][value="'+value+'"]').parents('.list_object')
                        .find('.object_evaluation_feedback').show().delay(2000).fadeOut();

                    $container.find('.object_evaluation_response_feedback').show();
                    
                }else{
                    $mensagem = $container.find('em');
                    
                    for(i=0;i<2;i++) {
                        $mensagem.fadeTo(100, 0.2).fadeTo(100, 1.0);
                    }
                    $mensagem.delay(5000).fadeOut();
                    
                }
                jQuery('div.nao-avaliar:has(input:checked)').hide();
            }
        });
    });
    
    jQuery('div.nao-avaliar:has(input:checked)').hide();
    
    // controla a exibicao da caixa de avaliacao na listagem de objetos
    jQuery('.show_evaluation').click(function() {
        
        jQuery('.evaluation_container').find('em').hide();
        
        // na listagem de objetos mostra apenas uma caixa de avaliação por vez (ver http://code.hacklab.com.br/issues/1925)
        jQuery('.evaluation_container').not(jQuery(this).parent().next('.evaluation_container')).hide(1000);
        
        jQuery(this).parent().siblings('.evaluation_container').toggle('slow');
    });
});
