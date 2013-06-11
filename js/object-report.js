jQuery(document).ready(function() {
    jQuery('.toggle_evaluation').click(function() {
        jQuery(this).siblings('#evaluation_bars').toggle('slow');
    });
});