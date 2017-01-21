/**
 * script.js
 * @author Melcher
 */

$(document).ready(function($) {
	// Make rows clickable while maintaing anchors
    $(".clickable-row").click(function(e) {
		// event target will always retrieve the anchor when clicked.
		if(e.target.tagName !== 'A') {
			window.location = $(this).data("href");
		}
    });
	
	// Hide IBANs by default
	$('.iban').hide();
	
	// Fade in all alerts
	$(".alert").addClass("in");
	// Fade out success alerts
	$("#alert-success").delay(2000).fadeOut("slow", function () { $(this).remove(); });	
});


// Show iban with given id number			
function showIban($id) {				
	$('.iban-' + $id).show();
	$('.iban-show-' + $id).hide();
}
