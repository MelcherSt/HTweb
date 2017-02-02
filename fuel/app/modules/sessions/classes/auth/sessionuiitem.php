<?php

namespace Sessions;

abstract class Auth_SessionUIItem extends \BasicEnum{
	const BTN_ENROLL_ADD = 0;
	const BTN_ENROLL = 1;
	const BTN_UNROLL = 2;
	const BTN_ENROLL_DISHWASHER = 8;
	const BTN_SESSION_UPDATE = 3;
	const COLUMN_ACTIONS = 4;
	const INPUT_DEADLINE = 5;
	const INPUT_COST = 6;
	const INPUT_PAYER_SELECT = 7;
	const ALERT_DEADLINE_CHANGED = 9;
}
