/**
 * Scripts for modals on privileges admin page
 * @author Melcher
 */

function showAddModal() {
	$("#add-permission-modal").modal('show');
}

function showDeleteModal(userId, userName, permissionId) {
	$("#delete-permission-modal").modal('show');
	$("#delete-user-name").html(userName);
	$("#delete-user-id").val(userId);
	$("#delete-permission-id").val(permissionId);
}

function showEditModal(userId, userName, guests, cook, dishwasher, canCook, canDish) {
	$("#edit-enrollment-modal").modal('show');
	$("#edit-user-name").html(userName);
	$("#edit-user-id").val(userId);
	$("#edit-guests").val(guests);
	$("#edit-cook").prop('checked', cook === 1).attr('disabled', canCook === 0);
	$("#edit-dishwasher").prop('checked', dishwasher === 1).attr('disabled', canDish === 0);	
}
