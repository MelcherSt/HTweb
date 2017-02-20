<div class="row">
	
	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-heading"><?=__('actions.name')?></div>
			<div class="panel-body">
				<em><?=__('actions.no_actions')?></em>
			</div>
		</div>
		
		<div class="panel panel-default">
			<div class="panel-heading">
				<?=__('privileges.title')?>
			</div>
			<div class="list-group">
				<?php if(\Auth::has_access('receipts.administration')) { ?>
				<a href="/receipts/admin" class="list-group-item"><i class="fa fa-list-alt" aria-hidden="true"></i> <?=__('privileges.perm.manage')?></a>
				<?php } ?>
			</div>
		</div>	
	</div>
	
	<div class="col-md-8">
		<p><?=__('receipt.index.msg')?></p>
		<div class="table-responsive">
			<table class="table table-striped table-hover">
				<thead>
					<tr>
						<th class='col-md-2'><?=__('receipt.field.date')?></th>
						<th class='col-md-11'><?=__('receipt.field.notes')?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($receipts as $receipt): ?>
					<tr class="clickable-row" data-href="/receipts/view/<?=$receipt->id?>">
						<td><?=$receipt->date?></td>
						<td><?=$receipt->notes?></td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<em><?=sizeof($receipts) == 0 ? __('receipt.empty_list') : ''?></em>
		</div>
	</div>
</div>

