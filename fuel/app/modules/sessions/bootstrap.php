<?php

namespace Sessions;

\Asset::add_path('assets/js/sessions', 'js');

\Event::register('gather_widgets',function(\Data $data) {
	$data->put_item('sessions/stats/widget');
	$data->put_item('sessions/widget');
});



