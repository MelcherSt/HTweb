<?php
$participants = $permission->users;

foreach(Model_User::get_by_state() as $user) {
	$user_options[$user->id] = $user->get_fullname();
}
?>

<div class="row">	
	<!-- SIDENAV -->
	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-heading"><?=__('actions.name')?></div>
			<div class="list-group">
				<a class="list-group-item" onClick="showAddModal()" href="#"><i class="fa fa-plus" aria-hidden="true"></i> <?=__('product.index.btn.add_product')?></a>	
			</div>
		</div>
		
		<div class="panel panel-default">
			<div class="panel-heading"><?=__('privileges.field.permission')?></div>			
			<div class="panel-body">
				<div class="well">
					<?=$permission->description?>
				</div>
				<dl>
					<dt><?=__('privileges.field.area')?></dt>
					<dd><?=$permission->area?></dd>
					<dt><?=__('privileges.field.permission')?></dt>
					<dd><?=$permission->permission?></dd>
					<dt><?=__('actions.name')?></dt>
					<dd><?=implode('|', $permission->actions)?></dd>
				</dl>
			</div>
		</div>
	</div>
	
	<!-- BODY -->
	<div class="col-md-8">
		<h3><?=__('product.field.participant_plural')?></h3>
		<div class="table-responsive">
			<table class="table table-striped table-hover">
				<thead>
					<tr>
						<th><?=__('user.field.name')?></th>
						<th><?=__('actions.name')?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($participants as $participant) { 
						$user = Model_User::find($participant->id); ?>
					<tr>
						<td><?=$user->get_fullname()?></td>
						<td>			
							<a href="#" onclick="showEditModal(<?=$user->id?>, '<?=$user->name?>')"><span class="fa fa-pencil"></span> <?=__('actions.edit')?></a>  |
							<a href="#" onclick="showDeleteModal(<?=$user->id?>, '<?=$user->name?>')"><span class="fa fa-close"></span> <?=__('actions.remove')?></a>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>	

<!-- Modal dialog for permission revokation -->
<div id="delete-permission-modal" class="modal fade">
	<div class="modal-dialog active">
		<div class="modal-content">
			<?=Form::open('/privileges/admin/delete/' . $permission->id)?>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"
						aria-hidden="true">&times;</button>
					<h4 class="modal-title"><?=__('privileges.modal.remove_enroll.title')?></h4>
				</div>
				<div class="modal-body">
					<p><?=__('privileges.modal.remove_enroll.msg')?> <strong><span id="delete-user-name"></span></strong>?</p>
					<?=Form::hidden('user-id', null, ['id' => 'delete-user-id'])?>
				</div>
				<div class="modal-footer">	
					<?=Form::submit(['value'=> __('privileges.modal.remove_enroll.btn'), 'name'=>'submit', 'class' => 'btn btn-primary'])?>
					<button type="button" class="btn btn-default" data-dismiss="modal"><?=__('actions.cancel')?></button>
				</div>
			<?=Form::close()?>
		</div>
	</div>
</div>

<!-- Modal dialog for permission assignment -->
<div id="add-permission-modal" class="modal fade">
	<div class="modal-dialog active">
		<div class="modal-content">
			<?=Form::open('/privileges/admin/create/'. $permission->id)?>
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal"
						aria-hidden="true">&times;</button>
					<h4 class="modal-title"><?=__('privileges.modal.create_enroll.title')?></h4>
				</div>
				<div class="modal-body">
					<p><?=__('privileges.modal.create_enroll.msg')?></p>					
					<div class="form-group">
						<?=Form::label(__('user.name'), 'user-id')?>
						<?=Form::select('user-id', null, $user_options, ['class' => 'form-control'])?>
					</div>	
					<br>
				</div>
				<div class="modal-footer">
					<?=Form::submit(['value'=> __('privileges.modal.create_enroll.btn'), 'name'=>'submit', 'class' => 'btn btn-primary'])?>
					<button type="button" class="btn btn-default"
						data-dismiss="modal"><?=__('actions.cancel')?></button>
				</div>
			<?=Form::close()?>
		</div>
	</div>
</div>
