<div class="row">
	
	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-heading"><?=__('actions.name')?></div>
			<div class="panel-body">
				<em><?=__('actions.no_actions')?></em>
			</div>
		</div>
	</div>
	
	<div class="col-md-8">
		<div class="table-responsive">
			<table
				id="sessions-table"
				data-toggle="table"
				data-url="/api/v1/sessions/"
				data-sort-name="date"
				data-pagination="true"
				data-side-pagination="server"
				data-page-list="[5, 10, 20, 50, 100, 200]"
				data-sort-order="desc"
			>				
				<thead>
					<tr>
						<th data-field="date"  data-sortable="true" class="col-md-2"><?=__('session.field.date')?></th>
						<th data-field="participants"  data-sortable="true" class="col-md-1"><?=__('session.role.participant_plural')?></th>
						<th data-field="cooks"  class="col-md-2"><?=__('session.role.cook_plural')?></th>
						<th data-field="dishwashers"  class="col-md-2"><?=__('session.role.dishwasher_plural')?></th>
						<th data-formatter="costFormatter" data-field="cost"  data-sortable="true" class="col-md-1"><?=__('product.field.cost')?></th>
						<th data-field="actions" data-formatter="actionFormatter" data-events="actionEvents" class="col-md-2"><?=__('actions.name')?></th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>

<!-- Modal dialog for session deletion -->
<div id="delete-session-modal" class="modal fade">
	<div class="modal-dialog active">
		<div class="modal-content">
			<form id="delete-session-form" 
				action="/sessions/admin/" 
				method="delete"
				data-alert-success="<?=__('session.alert.success.remove_session')?>"
				data-alert-error="<?=__('session.alert.error.remove_session')?>"
			>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"
						aria-hidden="true">&times;</button>
					<h4 class="modal-title"><?=__('session.modal.remove.title')?></h4>
				</div>
				<div class="modal-body">
					<p><?=__('session.modal.remove.msg')?> <strong><span id="delete-session-date"></span></strong>?</p>
					<div class="form-group">
						<input id="delete-session-id" type="hidden" class="form-control" name="session_id">
					</div>
				</div>
				<div class="modal-footer">
					<input type="submit" class="btn btn-danger" value="<?=__('session.modal.remove.btn')?>" />
					<button type="button" class="btn btn-default"
						data-dismiss="modal"><?=__('actions.cancel')?></button>
				</div>
			</form>
		</div>
	</div>
</div>