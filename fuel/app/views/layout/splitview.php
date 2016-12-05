<div class="row">
	<div class="col-md-6">
		<?=$left_content?>
	</div>
	<div class="col-md-6">
		<?=$right_content?>
	</div>
</div>


<!-- Modal dialog for package removal -->
<div id="delete-enrollment-modal" class="modal fade">
	<div class="modal-dialog active">
		<div class="modal-content">
			<form id="remove-package" action="#" method="POST">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"
						aria-hidden="true">&times;</button>
					<h4 class="modal-title">Remove package</h4>
				</div>
				<div class="modal-body">
					<p>Are you sure you want to delete <span id="user-name"></span> from this session??</p>
					<!--  insert form elements here -->
					<div class="form-group">
						<input id="user-id" type="hidden" class="form-control" name="user-id">
					</div>
				</div>
				<div class="modal-footer">
					<input type="submit" class="btn btn-primary" value="Remove" />
					<button type="button" class="btn btn-default"
						data-dismiss="modal">Cancel</button>
				</div>
			</form>
		</div>
	</div>
</div>