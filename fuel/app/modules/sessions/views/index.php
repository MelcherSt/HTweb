<div class="row">
	
	<!-- SIDENAV -->
	<div class="col-md-4">
		
		<!-- Basic session navigation -->
		<div class="panel panel-default">
			<div class="panel-heading"><?=__('actions.name')?></div>
			<div class="list-group">
				<a class="list-group-item" href="/sessions/yesterday"><i class="fa fa-chevron-left" aria-hidden="true"></i> <?=__('session.day.yesterday')?></a>
				<a class="list-group-item" href="/sessions/today"><i class="fa fa-cutlery" aria-hidden="true"></i> <?=__('session.day.today')?></a>
				<a class="list-group-item" href="/sessions/tomorrow"><i class="fa fa-chevron-right" aria-hidden="true"></i> <?=__('session.day.tomorrow')?></a>
			</div>
			<div class="list-group">
				<?php if(\Auth::has_access('sessions.administration')) { ?>
				<a href="/sessions/admin" class="list-group-item"><i class="fa fa-list-alt" aria-hidden="true"></i> <?=__('privileges.perm.manage')?></a>
				<?php } ?>
			</div>
		</div>
		
		<!-- Quick enrollment form -->
		<div class="panel panel-default">
			<div class="panel-heading">
				<?=__('session.index.quick_enroll')?>
			</div>
			
			<div class="panel-body">
				<form method="post" action="/sessions/enrollments/quick">
					<ul class="list-inline">
					<?php 
					$now = new DateTime();
					$date = new DateTime();
					$day_of_week = $date->format('w');
					$week_start = $date->modify('-' . ($day_of_week + 1) . 'days');

					// Print 7 days
					for ($i = 0; $i < (7 + $day_of_week); $i++): 
						$day = $week_start->modify('+1day');
						$date = $day->format('Y-m-d');
						if($day < $now) { continue; }

						$session = \Sessions\Model_Session::get_by_date($date);
						$enrollment = empty($session) ? null : $session->current_enrollment();
					?>
						<li>
							<div class="checkbox">
								<label><input name="dates[]" value="<?=$day->format('Y-m-d')?>" type="checkbox" <?=isset($enrollment) ? 'checked disabled' : '' ?>><?=strftime('%A (%d/%m)', $day->getTimestamp())?></label>
							</div>
						</li>
					<?php endfor; ?>
					</ul>
					<input type="submit" class="btn btn-sm btn-primary" value="<?=__('session.index.quick_btn')?>" />
				</form>
			</div>
		</div>	
	</div>
		
	<!-- BODY -->
	<div class="col-md-8">
		<p><?=__('session.index.msg')?> <a href="/receipts"><?=__('receipt.title')?></a>.</p>

		<h4><?=__('session.index.cooked_by_me')?></h4>
		<div class="table-responsive">
			<table class="table table-striped table-hover">
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
					foreach($sessions_cooked as $session): ?>
					<tr class="clickable-row" data-href="/sessions/view/<?=$session->date?>">
						<td><?=strftime('%A (%d/%m)', strtotime($session->date))?></td>
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
					</tr>
					<?php endforeach; ?>
				</tbody>	
			</table>
			<em><?=sizeof($sessions_cooked) == 0 ? __('session.empty_list') : ''?></em>
		</div>

		<h4><?=__('session.index.cooked_for_me')?></h4>
		<div class="table-responsive">
			<table class="table table-striped table-hover">
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
						<td><?=strftime('%A (%d/%m)', strtotime($session->date))?></td>
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
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
			<em><?=sizeof($sessions) == 0 ? __('session.empty_list') : ''?></em>
		</div>
	</div>
</div>




