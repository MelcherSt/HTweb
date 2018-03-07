<?=Form::open('sessions/enrollments/quick')?>
	<ul class="list-inline">
		<?php foreach($enrollments as $day => $enrollment) {?>
		<li>
			<div class="checkbox">
				<label><input name="dates[]" value="<?=$day?>" type="checkbox" <?=isset($enrollment) ? 'checked disabled' : '' ?>><?=strftime('%A (%d/%m)', (new DateTime($day))->getTimestamp())?></label>
			</div>
		</li>
		<?php } ?>
	</ul>
	<?=Form::submit(['value'=> __('session.index.quick_btn'), 'name'=>'submit', 'class' => 'btn btn-sm btn-primary btn-block'])?>	
<?=Form::close()?>