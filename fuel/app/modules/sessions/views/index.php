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

<div class="row text-center">
	<h4><?=__('session.index.quick_enroll')?></h4>
	<form method="post" action="/sessions/enrollments/quick">
		<ul class="list-inline">
		<?php 
		$now = new DateTime();
		$date = new DateTime();
		$day_of_week = $date->format('w') + 1;
		$week_start = $date->modify('-' . $day_of_week . 'days');


		for ($i = 0; $i < 7; $i++) { 
			$day = $week_start->modify('+1day');
			$date = $day->format('Y-m-d');
			if($day < $now) { continue; }

			$session = \Sessions\Model_Session::get_by_date($date);
			$enrollment = empty($session) ? null : $session->current_enrollment();
			$enrolled = isset($enrollment);
		?>
			<li>
				<div class="checkbox">
					<label><input name="dates[]" value="<?=$day->format('Y-m-d')?>" type="checkbox" <?=$enrolled ? 'checked disabled' : '' ?>><?=strftime('%A', $day->getTimestamp())?></label>
				</div>
			</li>


		<?php } ?>
		<ul>
		<input type="submit" class="btn btn-sm btn-primary" value="<?=__('session.index.quick_btn')?>" />
	</form>
</div>


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
				<?php 
				$sessions_cooked = \Sessions\Model_Session::get_by_cook($current_user->id);
				
				if(sizeof($sessions_cooked) == 0) {
					echo '<tr><td>' . __('session.empty_list') . '</td></tr>';
				}
				
				foreach($sessions_cooked as $session): ?>
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
				<?php 
				
				if(sizeof($sessions) == 0) {
					echo '<tr><td>' . __('session.empty_list') . '</td></tr>';
				}
				
				foreach($sessions as $session): ?>
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
