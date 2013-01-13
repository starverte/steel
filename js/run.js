    jQuery(document).ready(function($) {
        $(".royalSlider").royalSlider({
            imageScaleMode: 'fill',
		autoPlay: {
    			enabled: true,
    			pauseOnHover: true,
			delay: 5000
    		},
	    transitionType: 'fade',
	    controlNavigation: 'none',
	    loopRewind: 'true'
        });  
    });