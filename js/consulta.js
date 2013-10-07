jQuery(document).ready(function(){
    // TEMPLATE WIDGETS - AJAX
    jQuery('.template-widget-form').live('submit',function(){
        var div_id = jQuery(this).data("div_id");
        jQuery.post(consulta.ajaxurl, jQuery(this).serialize(), function (response) {
            if (response) {
                jQuery('.hl-lightbox-close').click();
                jQuery('#'+div_id).html(response);
            }
        });
        return false;
    });
});
