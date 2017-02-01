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
		
		<?=Request::forge('/privileges/sessions/nav')->execute();?>
		
	</div>
		
	<!-- BODY -->
	<div class="col-md-8">
		<p><?=__('session.index.msg')?> <a href="/receipts"><?=__('receipt.title')?></a>.</p>

		<h4><?=__('session.index.cooked_by_me')?></h4>
		<div class="table-responsive">
			<table
				id="sessions-cooked-table"
				data-toggle="table"
				data-url="/api/v1/sessions/bycook/<?=$current_user->id?>"
				data-sort-name="date"
				data-pagination="true"
				data-side-pagination="server"
				data-page-list="[5, 10, 20, 50, 100, 200]"
				data-sort-order="desc"
			>				
				<thead>
					<tr>
						<th data-field="date"  data-sortable="true" class="col-md-2"><?=__('session.field.date')?></th>
						<th data-field="participants"  data-sortable="true" class="col-md-1"><?=__('session.role.participant_plural')?></th>
						<th data-field="cooks"  class="col-md-2"><?=__('session.role.cook_plural')?></th>
						<th data-field="dishwashers"  class="col-md-2"><?=__('session.role.dishwasher_plural')?></th>
						<th data-formatter="costFormatter" data-field="cost"  data-sortable="true" class="col-md-1"><?=__('product.field.cost')?></th>
					</tr>
				</thead>
			</table>
		</div>

		<h4><?=__('session.index.cooked_for_me')?></h4>
		<div class="table-responsive">
			<table
				id="sessions-table"
				data-toggle="table"
				data-url="/api/v1/sessions/byothers/<?=$current_user->id?>"
				data-sort-name="date"
				data-pagination="true"
				data-side-pagination="server"
				data-page-list="[5, 10, 20, 50, 100, 200]"
				data-sort-order="desc"
			>				
				<thead>
					<tr>
						<th data-field="date"  data-sortable="true" class="col-md-2"><?=__('session.field.date')?></th>
						<th data-field="participants"  data-sortable="true" class="col-md-1"><?=__('session.role.participant_plural')?></th>
						<th data-field="cooks"  class="col-md-2"><?=__('session.role.cook_plural')?></th>
						<th data-field="dishwashers"  class="col-md-2"><?=__('session.role.dishwasher_plural')?></th>
						<th data-formatter="costFormatter" data-field="cost"  data-sortable="true" class="col-md-1"><?=__('product.field.cost')?></th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>




