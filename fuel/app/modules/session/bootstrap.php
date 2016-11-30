<?php

namespace Session;

\Event::register('gather_widgets',function(\Data $data) {
	$data->put_item('session');
});

