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
			<table class="table table-hover">
				<thead>
					<tr>
						<th class="col-md-2"><?=__('session.field.date')?></th>
						<th class="col-md-1"><?=__('session.role.participant_plural')?></th>
						<th class="col-md-2"><?=__('session.role.cook_plural')?></th>
						<th class="col-md-2"><?=__('session.role.dishwasher_plural')?></th>
						<th class="col-md-1"><?=__('product.field.cost')?></th>
						<th class="col-md-2"><?=__('actions.name')?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($sessions as $session): ?>
					<tr id="session-<?=$session->id?>" class="clickable-row" data-href="/sessions/admin/view/<?=$session->date?>">
						<td><?=strftime('%A (%e/%m)', strtotime($session->date))?></td>
						<td><?=$session->count_total_participants()?></td>
						<td>
							<?php foreach($session->get_cook_enrollments() as $enrollment):?>
								<?=$enrollment->user->name;?>			
							<?php endforeach; ?>	
						</td>
						<td>
							<?php foreach($session->get_dishwasher_enrollments() as $enrollment):?>
								<?=$enrollment->user->name;?> 	
							<?php endforeach; ?>
						</td>
						<td><?='€ ' . $session->cost?></td>
						<td><a href="#" data-href="#" class="clickable-row" onclick="showDeleteModal(<?=$session->id?>, '<?=$session->date?>')"><span class="fa fa-close"></span> <?=__('actions.remove')?></a></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<?=sizeof($sessions) == 0 ? __('session.empty_list') : ''?>
		</div>
	</div>
</div>

<!-- Modal dialog for session deletion -->
<div id="delete-session-modal" class="modal fade">
	<div class="modal-dialog active">
		<div class="modal-content">
			<form id="remove-session">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"
						aria-hidden="true">&times;</button>
					<h4 class="modal-title"><?=__('product.modal.remove.title')?></h4>
				</div>
				<div class="modal-body">
					<p><?=__('product.modal.remove.msg')?> <strong><span id="delete-session-date"></span></strong>?</p>
					<div class="form-group">
						<input id="delete-session-id" type="hidden" class="form-control" name="product_id">
					</div>
				</div>
				<div class="modal-footer">
					<input type="submit" class="btn btn-danger" value="<?=__('product.modal.remove.btn')?>" />
					<button type="button" class="btn btn-default"
						data-dismiss="modal"><?=__('actions.cancel')?></button>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
	
$('document').ready(function() {
	$('#remove-session').submit(function(event) {
		event.preventDefault();
		var sessionId = $('#delete-session-id').val();
		$.ajax({
			type: 'DELETE',
			data: 'session_id=' + sessionId,
			success: function() { 
				alertSuccess(LANG.session.alert.success.remove);
				$('#session-' + sessionId).fadeOut();
			},
			error: function(){ 
				alertError(LANG.session.alert.error.remove);
			},
			url: '/sessions/admin/',
			cache:false
		  });
		  $("#delete-session-modal").modal('hide');
	});
});
		

function showDeleteModal(sessionId, sessionDate) {
	$("#delete-session-date").html(sessionDate);
	$("#delete-session-id").val(sessionId);
	$("#delete-session-modal").modal();	
}

LANG = {
	session: {
		alert: {
			success: {
				remove: 'Session has been removed.'
			}, 
			error: {
				remove: 'Cannot remove session.'
			}
		}
	}
	
};

</script>