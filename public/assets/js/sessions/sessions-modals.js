/**
 * Scripts for modals on sessions pages
 * @author Melcher
 */

function showAddModal(canCook, canDish) {
	$("#add-enrollment-modal").modal('show');
	$("#add-cook").attr('disabled', canCook === 0);
	$("#add-dishwasher").attr('disabled', canDish === 0);
}

function showDeleteModal(userId, userName) {
	$("#delete-enrollment-modal").modal('show');
	$("#delete-user-name").html(userName);
	$("#delete-user-id").val(userId);
}

function showEditModal(userId, userName, guests, cook, dishwasher, canCook, canDish) {
	$("#edit-enrollment-modal").modal('show');
	$("#edit-user-name").html(userName);
	$("#edit-user-id").val(userId);
	$("#edit-guests").val(guests);
	$("#edit-cook").prop('checked', cook === 1).attr('disabled', canCook === 0);
	$("#edit-dishwasher").prop('checked', dishwasher === 1).attr('disabled', canDish === 0);	
}
