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

function showAddModal() {
	$("#add-product-modal").modal('show');
}

function showDeleteModal(productId, productName) {
	$("#delete-product-modal").modal('show');
	$("#delete-product-name").html(productName);
	$("#delete-product-id").val(productId);
}

$('#delete-macro-modal').on('show.bs.modal', function(e) {
	//get data-id attribute of the clicked element
	var productId = $(e.relatedTarget).data('macro-id');
	var productName = $(e.relatedTarget).data('macro-name');

	//populate the textbox
	$('#delete-macro-id').val(productId);
	$('#delete-macro-name').html(productName);
});	
