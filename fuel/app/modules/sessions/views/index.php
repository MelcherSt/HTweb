<div class="row">
	<div class="col-md-4">
		<a href="/sessions/yesterday">
		<div class="panel-default">
			<div class="panel-heading ">
				<div class="row">
					<div class="col-xs-3">
						<i class="fa fa-chevron-left fa-5x"></i>
					</div>
					<div class="col-xs-9 text-right">
						<div class="huge"><?=__('session.day.yesterday')?><small></small></div>
						<div></div>
					</div>
				</div>
			</div>	
		</div>
		</a>
	</div>
	
	<div class="col-md-4">
		<a href="/sessions/today">
		<div class="panel-default">
			<div class="panel-heading">
				<div class="row">
					<div class="col-xs-3">
						<i class="fa fa-cutlery fa-5x"></i>
					</div>
					<div class="col-xs-9 text-right">
						<div class="huge"><?=__('session.day.today')?><small></small></div>
						<div></div>
					</div>
				</div>
			</div>	
		</div>
		</a>
	</div>
	
	<div class="col-md-4">
		<a href="/sessions/tomorrow">
		<div class="panel-default">
			<div class="panel-heading">
				<div class="row">
					<div class="col-xs-3">
						<i class="fa fa-chevron-right fa-5x"></i>
					</div>
					<div class="col-xs-9 text-right">
						<div class="huge"><?=__('session.day.tomorrow')?><small></small></div>
						<div></div>
					</div>
				</div>
			</div>	
		</div>
		</a>
	</div>
</div>
<br>

<!-- TODO: Insert quick picker -->

<br>

<p><?=__('session.index.msg')?> <a href="/receipts"><?=__('receipt.title')?></a>.</p>

<div class="row">
	<h2><?=__('session.index.cooked_by_me')?></h2>
	<div class="table-responsive">
		<table class="table table-hover">
			<thead>
				<tr>
					<th class="col-md-2"><?=__('session.field.date')?></th>
					<th class="col-md-1"><?=__('session.role.participant_plural')?></th>
					<th class="col-md-2"><?=__('session.role.cook_plural')?></th>
					<th class="col-md-2"><?=__('session.role.dishwasher_plural')?></th>
					<th class="col-md-1"><?=__('product.field.cost')?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach(\Sessions\Model_Session::get_by_cook($current_user->id) as $session): ?>
				<tr class="clickable-row" data-href="/sessions/view/<?=$session->date?>">
					<td><?=strftime('%F - %A', strtotime($session->date))?></td>
					<td><?=$session->count_total_participants()?></td>
					<td>
						<?php foreach($session->get_cook_enrollments() as $enrollment):?>
							<?=$enrollment->user->get_fullname();?>			
						<?php endforeach; ?>	
					</td>
					<td>
						<?php foreach($session->get_dishwasher_enrollments() as $enrollment):?>
							<?=$enrollment->user->get_fullname();?>			
						<?php endforeach; ?>
					</td>
					<td><?='€ ' . $session->cost?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>

<div class="row">
	<h2><?=__('session.index.cooked_for_me')?></h2>
	<div class="table-responsive">
		<table class="table table-hover">
			<thead>
				<tr>
					<th class="col-md-2"><?=__('session.field.date')?></th>
					<th class="col-md-1"><?=__('session.role.participant_plural')?></th>
					<th class="col-md-2"><?=__('session.role.cook_plural')?></th>
					<th class="col-md-2"><?=__('session.role.dishwasher_plural')?></th>
					<th class="col-md-1"><?=__('product.field.cost')?></th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($sessions as $session): ?>
				<tr class="clickable-row" data-href="/sessions/view/<?=$session->date?>">
					<td><?=strftime('%F - %A', strtotime($session->date))?></td>
					<td><?=$session->count_total_participants()?></td>
					<td>
						<?php foreach($session->get_cook_enrollments() as $enrollment):?>
							<?=$enrollment->user->get_fullname();?>			
						<?php endforeach; ?>	
					</td>
					<td>
						<?php foreach($session->get_dishwasher_enrollments() as $enrollment):?>
							<?=$enrollment->user->get_fullname();?>			
						<?php endforeach; ?>
					</td>
					<td><?='€ ' . $session->cost?></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div>
