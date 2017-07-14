<?php 
if(isset($is_admin)) {
	$url = '/sessions/admin/';
} else {
	$url = '/sessions/';
}
?>

<div class="table-responsive">
	<table class="table table-striped table-hover table-condensed">
		<thead>
			<tr>
				<th class="col-md-2"><?=__('session.field.date')?></th>
				<th class="col-md-1"><?=__('session.role.participant_plural')?></th>
				<th class="col-md-2"><?=__('session.role.cook_plural')?></th>
				<th class="col-md-2"><?=__('session.role.dishwasher_plural')?></th>
				<th class="col-md-1"><?=__('product.field.cost')?></th>
				<?php if(isset($is_admin)) { ?>
				<th class="col-md-1"><?=__('actions.name')?></th>
				<?php } ?>
			</tr>
		</thead>
		<tbody>
			<?php foreach($sessions as $session) { ?>
			<tr class="clickable-row <?=$session->is_cook() && !isset($hide_colors) ? 'info' : ''?>" data-href="<?=$url.$session->date?>">
				<td><?=strftime('%d/%m (%A)', strtotime($session->date))?></td>
				<td><?=$session->count_total_participants()?></td>
				<td><?=$session->get_nicified_cooks()?></td>
				<td><?=$session->get_nicified_dishwashers()?></td>
				<td><?='€ ' . $session->cost?></td>
				<?php if(isset($is_admin)) { ?>
				<td><a href="#" data-href="#" class="clickable-row" data-toggle="modal" data-target="#delete-session-modal" data-session-id="<?=$session->id?>" data-session-date="<?=$session->date?>"><span class="fa fa-close"></span> <?=__('actions.remove')?></a></td>
				<?php } ?>
			</tr>
			<?php } ?>
		</tbody>	
	</table>
	<em><?=sizeof($sessions) == 0 ? __('session.empty_list') : ''?></em>
</div>