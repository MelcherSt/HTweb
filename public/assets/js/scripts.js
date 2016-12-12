/**
 * script.js
 * @author Melcher
 */

$(document).ready(function($) {
	// Make rows clickable
    $(".clickable-row").click(function() {
        window.location = $(this).data("href");
    });
	
	// Fade in all alerts
	$(".alert").addClass("in");
	// Fade out success alerts
	$("#alert-success").delay(2000).fadeOut("slow", function () { $(this).remove(); });	
});
