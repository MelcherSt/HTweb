/**
 * Scripts for modals on products pages
 * @author Melcher
 */

function checkAll() {
	$(".user-select").prop('checked', true);
}

function uncheckAll() {
	$(".user-select").prop('checked', false);
}

$('document').ready(function () {
	$('#delete-macro-modal').on('show.bs.modal', function(e) {
	var productId = $(e.relatedTarget).data('macro-id');
	var productName = $(e.relatedTarget).data('macro-name');
	$('#delete-macro-id').val(productId);
	$('#delete-macro-name').html(productName);
});	

$('#delete-product-modal').on('show.bs.modal', function(e) {
	var productId = $(e.relatedTarget).data('product-id');
	var productName = $(e.relatedTarget).data('product-name');
	$('#delete-product-id').val(productId);
	$('#delete-product-name').html(productName);
});	

});

