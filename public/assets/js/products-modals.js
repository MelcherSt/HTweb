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
