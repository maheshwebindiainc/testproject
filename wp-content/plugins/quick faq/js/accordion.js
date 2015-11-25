jQuery( document ).ready(function() {
        jQuery(".faq-plugin-accordion").each(function() {
            var dataActive      = jQuery(this).attr( 'data-active' )  == 'true' ? true : false;
            var dataDisabled    = jQuery(this).attr( 'data-disabled')   == 'true' ? true : false;
            var dataCollapsible = jQuery(this).attr( 'data-collapsible')   == 'true' ? true : false;
             var dataAnimate = jQuery(this).attr( 'data-animate')   == 'true' ? true : false;
            jQuery(".accordion" ).accordion({
                heightStyle:    'content',
                active:         dataActive,
                collapsible:    dataCollapsible,
                animate :       dataAnimate
            });
        });
    });