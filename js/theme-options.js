jQuery(document).ready(function() {
    //TODO: refactor to remove code duplication
    jQuery('#allow_suggested').click(function() {
        if (jQuery(this).is(':checked'))
            jQuery('#allow_suggested_labels_container').slideDown('slow');
        else
            jQuery('#allow_suggested_labels_container').slideUp('slow');
    });
    
    jQuery('#enable_taxonomy').click(function() {
        if (jQuery(this).is(':checked'))
            jQuery('#taxonomy_labels_container').slideDown('slow');
        else
            jQuery('#taxonomy_labels_container').slideUp('slow');
    });
    
    jQuery('#use_evaluation').click(function() {
        if (jQuery(this).is(':checked')) {
            jQuery('#use_evaluation_labels_container').slideDown('slow');
        } else {
            jQuery('#use_evaluation_labels_container').slideUp('slow');
        }
    });

    if (!jQuery('#allow_suggested').is(':checked')) {
        jQuery('#allow_suggested_labels_container').hide();
    }
        
    if (!jQuery('#enable_taxonomy').is(':checked')) {
        jQuery('#taxonomy_labels_container').hide();
    }
    
    if (!jQuery('#use_evaluation').is(':checked')) {
        jQuery('#use_evaluation_labels_container').hide();
    }
    
    //abas
    jQuery('#abas-secoes li a').click(function() {
        jQuery('#abas-secoes li').removeClass('active');
        jQuery(this).parent('li').addClass('active');
        jQuery('.aba-container').hide();
        jQuery('#' + jQuery(this).attr('id') + '-container').show();
        jQuery.cookie('consulta-active-tab', jQuery(this).attr('id'));
    });
    
    if (jQuery.cookie('consulta-active-tab')) {
        jQuery('#' + jQuery.cookie('consulta-active-tab')).click();
        jQuery.removeCookie('consulta-active-tab')
    } else {
        jQuery('#abas-secoes li.active a').click();
    }
    
    jQuery('.radio_evaluation_type').click(function() {
        var image = 'perce';
        if (jQuery(this).val() == 'average')
            image = 'media';
        
        jQuery('#exemplo_resultado img').hide();
        jQuery('#exemplo_resultado #'+image).show();
        
    });
    jQuery('.radio_evaluation_type:checked').click();
    
    
    var evaluation_limit_checkbox = function(animate){
        var $div = jQuery('#evaluation_max_num_container');
        if(jQuery('#evaluation_limit').is(':checked')){
            animate ? $div.slideDown() : $div.show();
            jQuery('#evaluation_allow_remove').attr('checked', true);
        }else{
            animate ? $div.slideUp() : $div.hide();
            jQuery('#evaluation_allow_remove').attr('checked', false);
        }
    };
    evaluation_limit_checkbox(false);
    jQuery('#evaluation_limit').click(evaluation_limit_checkbox);
});


