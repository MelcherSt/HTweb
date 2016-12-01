<?php

namespace Sessions;

\Event::register('gather_widgets',function(\Data $data) {
	$data->put_item('sessions');
});

