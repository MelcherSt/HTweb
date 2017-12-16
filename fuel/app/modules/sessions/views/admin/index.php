<div class="col-md-4">
	<div class="card">
		<div class="card-header"><?= __('actions.name') ?></div>
		<div class="card-body">
			<em><?= __('actions.no_actions') ?></em>
		</div>
	</div>
</div>

<div class="col-md-8">
	<?= Presenter::forge('overview', 'admin') ?>
</div>


<!-- Modal dialog for session deletion -->
<div id="delete-session-modal" class="modal fade">
	<div class="modal-dialog active">
		<div class="modal-content">
			<?= Form::open('/sessions/delete') ?>
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"
						aria-hidden="true">&times;</button>
				<h4 class="modal-title"><?= __('session.modal.remove.title') ?></h4>
			</div>
			<div class="modal-body">
				<p><?= __('session.modal.remove.msg') ?> <strong><span id="delete-session-date"></span></strong>?</p>
				<?= Form::hidden('session-id', null, ['id' => 'delete-session-id']) ?>
			</div>
			<div class="modal-footer">					
				<?= Form::submit(['value' => __('session.modal.remove.btn'), 'name' => 'submit', 'class' => 'btn btn-danger']) ?>	
				<button type="button" class="btn btn-default" data-dismiss="modal"><?= __('actions.cancel') ?></button>
			</div>
			<?= Form::close() ?>
		</div>
	</div>
</div>