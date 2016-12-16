<?php

namespace DevTool;

If(\Fuel::$env == \Fuel::DEVELOPMENT) {
	\Event::register('gather_widgets',function(\Data $data) {
		//$data->put_item('devtool/widget');
	});
}

