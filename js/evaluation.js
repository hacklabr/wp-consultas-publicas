jQuery(document).ready(function() {
    // voto do usuario em uma das opcoes da avaliacao de um objeto
    jQuery('#object_evaluation input').live('click', function() {
        var radioButton = jQuery(this);
        jQuery('body').css('cursor', 'progress');
        
        jQuery.ajax({
            url: consulta.ajaxurl,
            type: 'post',
            data: {action: 'object_evaluation', userVote: jQuery(this).val(), postId: jQuery(this).data('post_id') },
            dataType: 'json',
            success: function(data) {
                jQuery('body').css('cursor', 'auto');
                radioButton.closest('li').find('.count_object_votes').html(data.count);
                radioButton.closest('.evaluation_container').html(data.html);
                jQuery('input[name=object_evaluation]:checked').siblings('.object_evaluation_feedback').show();
                jQuery('input[name=object_evaluation]:checked').siblings('.object_evaluation_feedback').delay(1500).fadeOut('slow');
            }
        });
    });
    
    // controla a exibicao da caixa de avaliacao na listagem de objetos
    jQuery('.show_evaluation').click(function() {
        // na listagem de objetos mostra apenas uma caixa de avaliação por vez (ver http://code.hacklab.com.br/issues/1925)
        jQuery('.evaluation_container').not(jQuery(this).parent().next('.evaluation_container')).hide(1000);
        
        jQuery(this).parent().siblings('.evaluation_container').toggle('slow');
    });
});
