<?php

namespace DevTool;

\Event::register('gather_widgets',function(\Data $data) {
	$data->put_item('devtool');
});

