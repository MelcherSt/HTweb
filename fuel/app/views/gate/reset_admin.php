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
				<td><?=Date::forge($token->created_at)->format('%d/%m/%Y (%A)')?></td>
				<td>
					<a href="delete/<?=$token->id?>" class="clickable-row"><span class="fa fa-close"></span> <?=__('actions.remove')?></a>
				</td>
			</tr>
			<?php } ?>
		</tbody>
	</table>
	<em><?=sizeof($tokens) == 0 ? __('product.empty_list') : ''?></em>
</div>