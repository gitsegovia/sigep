<?php
/**
 * Not empty.
 */
	define('VALID_NOT_EMPTY', '/.+/');
/**
 * Numbers [0-9] only.
 */
	define('VALID_NUMBER', '/^[-+]?\\b[0-9]*\\.?[0-9]+\\b$/');
/**
 * A valid email address.
 */
	define('VALID_EMAIL', '/\\A(?:^([a-z0-9][a-z0-9_\\-\\.\\+]*)@([a-z0-9][a-z0-9\\.\\-]{0,63}\\.(com|org|net|biz|info|name|net|pro|aero|coop|museum|[a-z]{2,4}))$)\\z/i');
/**
 * A valid year (1000-2999).
 */
	define('VALID_YEAR', '/^[12][0-9]{3}$/');
?>