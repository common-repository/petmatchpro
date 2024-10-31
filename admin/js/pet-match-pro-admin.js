(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

function resetForm() {
    document.getElementById("myForm").reset();
}
$('body').on('click', '.et-core-modal-header', function () {
	$('body.admin-bar').removeClass('et_pb_stop_scroll');
})

})( jQuery );


var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
    /* Toggle between adding and removing the "active" class,
    to highlight the button that controls the panel */
    this.classList.toggle("active");

    /* Toggle between hiding and showing the active panel */
    var panel = this.nextElementSibling;
    if (panel.style.display === "block") {
      panel.style.display = "none";
    } else {
      panel.style.display = "block";
    }
  });
}


jQuery( document ).ready(function() { 	
	jQuery('#wpfooter').css({"position": "unset", "padding-left" : "20px"});
    console.log( "ready!" );
    var gkForm = jQuery('#gk-pets-options');
    var gmLicense = jQuery('.el-license-container');
    if(gkForm){
    	jQuery('#gk-pets-options').append(jQuery('#wpfooter'));
        jQuery('#gk-pets-options #wpfooter').css({"position": "unset", "margin-left": "0", "padding-left" : "0" });
    }
    if(gmLicense){
    	jQuery('form.gm_license').after(jQuery('#wpfooter'));
        jQuery('.gm_license + #wpfooter').css({"margin-left": "0", "padding-left" : "0" });
        jQuery('.sfsi_new_premium_banner_body').css({"max-width": "80%", "margin": "50px", "padding" : "0" });
        jQuery('.dismiss-btn').css({"display": "none" });
    }
    
})
