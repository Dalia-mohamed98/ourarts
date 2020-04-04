<?php

namespace Zprint;

function date_i18n($format, $time = false)
{
	if ($time instanceof \WC_DateTime) {
		return $time->date_i18n($format);
	}

	return \date_i18n($format, $time);
}
