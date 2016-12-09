<div class="row">
	<p>List of all receipts in the system.</p>
	<a href="/receipts/admin/create" class="btn btn-primary pull-right" role="button"><span class="fa fa-plus"></span> Create receipt</a>
	
</div>


<div class="row">
	<div class="table-responsive">
		<table class="table table-hover">
			<thead>
				<tr>
					<th>Id</th>
					<th>Creation date</th>
					<th>Notes</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($receipts as $receipt): ?>
				<tr>
					<td><?=$receipt->id?></td>
					<td><?=$receipt->date?></td>
					<td><?=$receipt->notes?></td>
					<td>
						<a href="/receipts/view/<?=$receipt->id?>"><span class="fa fa-eye"></span> View</a> |
						<a href="#" onclick="showDeleteModal(<?=$receipt->id?>)"><span class="fa fa-trash"></span> Remove</a>
					</td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>

<!-- Modal dialog for receipt deletion -->
<div id="delete-receipt-modal" class="modal fade">
	<div class="modal-dialog active">
		<div class="modal-content">
			<form id="remove-package" action="/receipts/admin/delete/" method="POST">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"
						aria-hidden="true">&times;</button>
					<h4 class="modal-title">Delete enrollment</h4>
				</div>
				<div class="modal-body">
					<p><strong>Are you sure you want to delete this receipt?</strong>
						<br>Delelting a receipt will redistribute all points
					among the participants. Costs calculated for the receipt will be lost and all session and/or products will be marked unsettled.
					</p>
					<!--  insert form elements here -->
					<div class="form-group">
						<input id="delete-receipt-id" type="hidden" class="form-control" name="receipt_id">
					</div>
				</div>
				<div class="modal-footer">
					<input type="submit" class="btn btn-danger" value="Delete Receipt" />
					<button type="button" class="btn btn-default"
						data-dismiss="modal">Cancel</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
function showDeleteModal(receiptId) {
	$("#delete-receipt-modal").modal('show');
	$("#delete-receipt-id").val(receiptId);
}
</script>

