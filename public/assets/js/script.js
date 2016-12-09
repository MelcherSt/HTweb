/**
 * script.js
 * @author Melcher
 */

$(document).ready(function($) {
	// Make rows clickable
    $(".clickable-row").click(function() {
        window.location = $(this).data("href");
    });
});
