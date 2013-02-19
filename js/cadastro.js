jQuery(document).ready(function() {


    jQuery('#estado').change(function() {
        
        if (jQuery(this).val() != '') {
            jQuery('#municipio').html('<option value="">Carregando...</option>');
            
            jQuery.ajax({
                url: consulta.ajaxurl, 
                type: 'post',
                data: {action: 'consulta_get_cities_options', uf: jQuery('#estado').val(), selected: jQuery('#municipio').val()},
                success: function(data) {
                    jQuery('#municipio').html(data);
                } 
            });
        }
        
        
    }).change();
    
    jQuery('select#atuacao').change(function() {
    
        if (jQuery(this).val() == 'outra_area_cultura' || jQuery(this).val() == 'nao_cultura') {
            jQuery('#atuacao_outra_container').show();
        } else {
            jQuery('#atuacao_outra_container').hide();
        }
    
    }).change();
    
    jQuery('select#ocupacao').change(function() {
    
        if (jQuery(this).val() == 'outra') {
            jQuery('#ocupacao_outra_container').show();
        } else {
            jQuery('#ocupacao_outra_container').hide();
        }
    
    }).change();


});
