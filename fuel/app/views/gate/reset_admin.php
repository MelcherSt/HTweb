<div class="table-responsive">
	<table class="table table-striped table-hover table-condensed">
		<thead>
			<tr>
				<th class="col-md-2">Username</th>
				<th class="col-md-2">Token</th>
				<th class="col-md-2">Issued</th>	
				<th class="col-md-2"><?=__('actions.name')?></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($tokens as $token){ ?>
			<tr>						
				<td><?=$token->user->name?></td>
				<td><?=$token->token?>
				<td><?=strftime('%d/%m/%Y (%A)', strtotime($token->created_at))?></td>
				<td>
					<a href="#" class="clickable-row" data-toggle="modal" data-target="#delete-token-modal" data-macro-id="<?=$token->id?>"><span class="fa fa-close"></span> <?=__('actions.remove')?></a>
				</td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
	<em><?=sizeof($products) == 0 ? __('product.empty_list') : ''?></em>
</div>