<?php

/**
 * @name      ElkArte Forum
 * @copyright ElkArte Forum contributors
 * @license   BSD http://opensource.org/licenses/BSD-3-Clause
 *
 * This software is a derived product, based on:
 *
 * Simple Machines Forum (SMF)
 * copyright:	2011 Simple Machines (http://www.simplemachines.org)
 * license:  	BSD, See included LICENSE.TXT for terms and conditions.
 *
 * @version 1.0 Alpha
 *
 * This file has functions in it to do with authentication, user handling, and the like.
 *
 */

if (!defined('ELKARTE'))
	die('No access...');

/**
 * Sets the login cookie and session based on the id_member and password passed.
 * - password should be already encrypted with the cookie salt.
 * - logs the user out if id_member is zero.
 * - sets the cookie and session to last the number of seconds specified by cookie_length.
 * - when logging out, if the globalCookies setting is enabled, attempts to clear the subdomain's cookie too.
 *
 * @param int $cookie_length
 * @param int $id The id of the member
 * @param string $password = ''
 */
function setLoginCookie($cookie_length, $id, $password = '')
{
	global $cookiename, $boardurl, $modSettings;

	// If changing state force them to re-address some permission caching.
	$_SESSION['mc']['time'] = 0;

	// The cookie may already exist, and have been set with different options.
	$cookie_state = (empty($modSettings['localCookies']) ? 0 : 1) | (empty($modSettings['globalCookies']) ? 0 : 2);
	if (isset($_COOKIE[$cookiename]) && preg_match('~^a:[34]:\{i:0;(i:\d{1,6}|s:[1-8]:"\d{1,8}");i:1;s:(0|40):"([a-fA-F0-9]{40})?";i:2;[id]:\d{1,14};(i:3;i:\d;)?\}$~', $_COOKIE[$cookiename]) === 1)
	{
		$array = @unserialize($_COOKIE[$cookiename]);

		// Out with the old, in with the new!
		if (isset($array[3]) && $array[3] != $cookie_state)
		{
			$cookie_url = url_parts($array[3] & 1 > 0, $array[3] & 2 > 0);
			elk_setcookie($cookiename, serialize(array(0, '', 0)), time() - 3600, $cookie_url[1], $cookie_url[0]);
		}
	}

	// Get the data and path to set it on.
	$data = serialize(empty($id) ? array(0, '', 0) : array($id, $password, time() + $cookie_length, $cookie_state));
	$cookie_url = url_parts(!empty($modSettings['localCookies']), !empty($modSettings['globalCookies']));

	// Set the cookie, $_COOKIE, and session variable.
	elk_setcookie($cookiename, $data, time() + $cookie_length, $cookie_url[1], $cookie_url[0]);

	// If subdomain-independent cookies are on, unset the subdomain-dependent cookie too.
	if (empty($id) && !empty($modSettings['globalCookies']))
		elk_setcookie($cookiename, $data, time() + $cookie_length, $cookie_url[1], '');

	// Any alias URLs?  This is mainly for use with frames, etc.
	if (!empty($modSettings['forum_alias_urls']))
	{
		$aliases = explode(',', $modSettings['forum_alias_urls']);

		$temp = $boardurl;
		foreach ($aliases as $alias)
		{
			// Fake the $boardurl so we can set a different cookie.
			$alias = strtr(trim($alias), array('http://' => '', 'https://' => ''));
			$boardurl = 'http://' . $alias;

			$cookie_url = url_parts(!empty($modSettings['localCookies']), !empty($modSettings['globalCookies']));

			if ($cookie_url[0] == '')
				$cookie_url[0] = strtok($alias, '/');

			elk_setcookie($cookiename, $data, time() + $cookie_length, $cookie_url[1], $cookie_url[0]);
		}

		$boardurl = $temp;
	}

	$_COOKIE[$cookiename] = $data;

	// Make sure the user logs in with a new session ID.
	if (!isset($_SESSION['login_' . $cookiename]) || $_SESSION['login_' . $cookiename] !== $data)
	{
		// We need to meddle with the session.
		require_once(SOURCEDIR . '/Session.php');

		// Backup and remove the old session.
		$oldSessionData = $_SESSION;
		$_SESSION = array();
		session_destroy();

		// Recreate and restore the new session.
		loadSession();
		// @todo should we use session_regenerate_id(true); now that we are 5.1+
		session_regenerate_id();
		$_SESSION = $oldSessionData;

		$_SESSION['login_' . $cookiename] = $data;
	}
}

/**
 * Get the domain and path for the cookie
 * - normally, local and global should be the localCookies and globalCookies settings, respectively.
 * - uses boardurl to determine these two things.
 *
 * @param bool $local
 * @param bool $global
 * @return array an array to set the cookie on with domain and path in it, in that order
 */
function url_parts($local, $global)
{
	global $boardurl, $modSettings;

	// Parse the URL with PHP to make life easier.
	$parsed_url = parse_url($boardurl);

	// Is local cookies off?
	if (empty($parsed_url['path']) || !$local)
		$parsed_url['path'] = '';

	if (!empty($modSettings['globalCookiesDomain']) && strpos($boardurl, $modSettings['globalCookiesDomain']) !== false)
		$parsed_url['host'] = $modSettings['globalCookiesDomain'];

	// Globalize cookies across domains (filter out IP-addresses)?
	elseif ($global && preg_match('~^\d{1,3}(\.\d{1,3}){3}$~', $parsed_url['host']) == 0 && preg_match('~(?:[^\.]+\.)?([^\.]{2,}\..+)\z~i', $parsed_url['host'], $parts) == 1)
			$parsed_url['host'] = '.' . $parts[1];

	// We shouldn't use a host at all if both options are off.
	elseif (!$local && !$global)
		$parsed_url['host'] = '';

	// The host also shouldn't be set if there aren't any dots in it.
	elseif (!isset($parsed_url['host']) || strpos($parsed_url['host'], '.') === false)
		$parsed_url['host'] = '';

	return array($parsed_url['host'], $parsed_url['path'] . '/');
}

/**
 * Question the verity of the admin by asking for his or her password.
 * - loads Login.template.php and uses the admin_login sub template.
 * - sends data to template so the admin is sent on to the page they
 *   wanted if their password is correct, otherwise they can try again.
 *
 * @param string $type = 'admin'
 */
function adminLogin($type = 'admin')
{
	global $context, $scripturl, $txt, $user_info, $user_settings;

	loadLanguage('Admin');
	loadTemplate('Login');

	// Validate what type of session check this is.
	$types = array();
	call_integration_hook('integrate_validateSession', array(&$types));
	$type = in_array($type, $types) || $type == 'moderate' ? $type : 'admin';

	// They used a wrong password, log it and unset that.
	if (isset($_POST[$type . '_hash_pass']) || isset($_POST[$type . '_pass']))
	{
		$txt['security_wrong'] = sprintf($txt['security_wrong'], isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : $txt['unknown'], $_SERVER['HTTP_USER_AGENT'], $user_info['ip']);
		log_error($txt['security_wrong'], 'critical');

		if (isset($_POST[$type . '_hash_pass']))
			unset($_POST[$type . '_hash_pass']);
		if (isset($_POST[$type . '_pass']))
			unset($_POST[$type . '_pass']);

		$context['incorrect_password'] = true;
	}

	createToken('admin-login');

	// Figure out the get data and post data.
	$context['get_data'] = '?' . construct_query_string($_GET);
	$context['post_data'] = '';

	// Now go through $_POST.  Make sure the session hash is sent.
	$_POST[$context['session_var']] = $context['session_id'];
	foreach ($_POST as $k => $v)
		$context['post_data'] .= adminLogin_outputPostVars($k, $v);

	// Now we'll use the admin_login sub template of the Login template.
	$context['sub_template'] = 'admin_login';

	// And title the page something like "Login".
	if (!isset($context['page_title']))
		$context['page_title'] = $txt['login'];

	// The type of action.
	$context['sessionCheckType'] = $type;

	obExit();

	// We MUST exit at this point, because otherwise we CANNOT KNOW that the user is privileged.
	trigger_error('Hacking attempt...', E_USER_ERROR);
}

/**
 * Used by the adminLogin() function.
 * if 'value' is an array, the function is called recursively.
 *
 * @param string $k key
 * @param string $v value
 * @return string 'hidden' HTML form fields, containing key-value-pairs
 */
function adminLogin_outputPostVars($k, $v)
{
	$db = database();

	if (!is_array($v))
		return '
<input type="hidden" name="' . htmlspecialchars($k) . '" value="' . strtr($v, array('"' => '&quot;', '<' => '&lt;', '>' => '&gt;')) . '" />';
	else
	{
		$ret = '';
		foreach ($v as $k2 => $v2)
			$ret .= adminLogin_outputPostVars($k . '[' . $k2 . ']', $v2);

		return $ret;
	}
}

/**
 * Properly urlencodes a string to be used in a query
 *
 * @global type $scripturl
 * @param type $get
 * @return our query string
 */
function construct_query_string($get)
{
	global $scripturl;

	$query_string = '';

	// Awww, darn.  The $scripturl contains GET stuff!
	$q = strpos($scripturl, '?');
	if ($q !== false)
	{
		parse_str(preg_replace('/&(\w+)(?=&|$)/', '&$1=', strtr(substr($scripturl, $q + 1), ';', '&')), $temp);

		foreach ($get as $k => $v)
		{
			// Only if it's not already in the $scripturl!
			if (!isset($temp[$k]))
				$query_string .= urlencode($k) . '=' . urlencode($v) . ';';
			// If it changed, put it out there, but with an ampersand.
			elseif ($temp[$k] != $get[$k])
				$query_string .= urlencode($k) . '=' . urlencode($v) . '&amp;';
		}
	}
	else
	{
		// Add up all the data from $_GET into get_data.
		foreach ($get as $k => $v)
			$query_string .= urlencode($k) . '=' . urlencode($v) . ';';
	}

	$query_string = substr($query_string, 0, -1);
	return $query_string;
}

/**
 * Finds members by email address, username, or real name.
 * - searches for members whose username, display name, or e-mail address match the given pattern of array names.
 * - searches only buddies if buddies_only is set.
 *
 * @param array $names
 * @param bool $use_wildcards = false, accepts wildcards ? and * in the patern if true
 * @param bool $buddies_only = false,
 * @param int $max = 500 retrieves a maximum of max members, if passed
 * @return array containing information about the matching members
 */
function findMembers($names, $use_wildcards = false, $buddies_only = false, $max = 500)
{
	global $scripturl, $user_info, $modSettings;

	$db = database();

	// If it's not already an array, make it one.
	if (!is_array($names))
		$names = explode(',', $names);

	$maybe_email = false;
	foreach ($names as $i => $name)
	{
		// Trim, and fix wildcards for each name.
		$names[$i] = trim(Util::strtolower($name));

		$maybe_email |= strpos($name, '@') !== false;

		// Make it so standard wildcards will work. (* and ?)
		if ($use_wildcards)
			$names[$i] = strtr($names[$i], array('%' => '\%', '_' => '\_', '*' => '%', '?' => '_', '\'' => '&#039;'));
		else
			$names[$i] = strtr($names[$i], array('\'' => '&#039;'));
	}

	// What are we using to compare?
	$comparison = $use_wildcards ? 'LIKE' : '=';

	// Nothing found yet.
	$results = array();

	// This ensures you can't search someones email address if you can't see it.
	$email_condition = allowedTo('moderate_forum') ? '' : 'hide_email = 0 AND ';

	if ($use_wildcards || $maybe_email)
		$email_condition = '
			OR (' . $email_condition . 'email_address ' . $comparison . ' \'' . implode( '\') OR (' . $email_condition . ' email_address ' . $comparison . ' \'', $names) . '\')';
	else
		$email_condition = '';

	// Get the case of the columns right - but only if we need to as things like MySQL will go slow needlessly otherwise.
	$member_name = $db->db_case_sensitive() ? 'LOWER(member_name)' : 'member_name';
	$real_name = $db->db_case_sensitive() ? 'LOWER(real_name)' : 'real_name';

	// Search by username, display name, and email address.
	$request = $db->query('', '
		SELECT id_member, member_name, real_name, email_address, hide_email
		FROM {db_prefix}members
		WHERE ({raw:member_name_search}
			OR {raw:real_name_search} {raw:email_condition})
			' . ($buddies_only ? 'AND id_member IN ({array_int:buddy_list})' : '') . '
			AND is_activated IN (1, 11)
		LIMIT {int:limit}',
		array(
			'buddy_list' => $user_info['buddies'],
			'member_name_search' => $member_name . ' ' . $comparison . ' \'' . implode( '\' OR ' . $member_name . ' ' . $comparison . ' \'', $names) . '\'',
			'real_name_search' => $real_name . ' ' . $comparison . ' \'' . implode( '\' OR ' . $real_name . ' ' . $comparison . ' \'', $names) . '\'',
			'email_condition' => $email_condition,
			'limit' => $max,
		)
	);
	while ($row = $db->fetch_assoc($request))
	{
		$results[$row['id_member']] = array(
			'id' => $row['id_member'],
			'name' => $row['real_name'],
			'username' => $row['member_name'],
			'email' => in_array(showEmailAddress(!empty($row['hide_email']), $row['id_member']), array('yes', 'yes_permission_override')) ? $row['email_address'] : '',
			'href' => $scripturl . '?action=profile;u=' . $row['id_member'],
			'link' => '<a href="' . $scripturl . '?action=profile;u=' . $row['id_member'] . '">' . $row['real_name'] . '</a>'
		);
	}
	$db->free_result($request);

	// Return all the results.
	return $results;
}

/**
 * Generates a random password for a user and emails it to them.
 * - called by ProfileOptions controller when changing someone's username.
 * - checks the validity of the new username.
 * - generates and sets a new password for the given user.
 * - mails the new password to the email address of the user.
 * - if username is not set, only a new password is generated and sent.
 *
 * @param int $memID
 * @param string $username = null
 */
function resetPassword($memID, $username = null)
{
	global $modSettings, $language;

	// Language... and a required file.
	loadLanguage('Login');
	require_once(SUBSDIR . '/Mail.subs.php');

	// Get some important details.
	require_once(SUBSDIR . '/Members.subs.php');
	$result = getBasicMemberData($memID, array('preferences' => true));
	$user = $result['member_name'];
	$email = $result['email_address'];
	$lngfile = $result['lngfile'];

	if ($username !== null)
	{
		$old_user = $user;
		$user = trim($username);
	}

	// Generate a random password.
	$newPassword = substr(preg_replace('/\W/', '', md5(mt_rand())), 0, 10);
	$newPassword_sha1 = sha1(strtolower($user) . $newPassword);

	// Do some checks on the username if needed.
	if ($username !== null)
	{
		validateUsername($memID, $user);

		// Update the database...
		updateMemberData($memID, array('member_name' => $user, 'passwd' => $newPassword_sha1));
	}
	else
		updateMemberData($memID, array('passwd' => $newPassword_sha1));

	call_integration_hook('integrate_reset_pass', array($old_user, $user, $newPassword));

	$replacements = array(
		'USERNAME' => $user,
		'PASSWORD' => $newPassword,
	);

	$emaildata = loadEmailTemplate('change_password', $replacements, empty($lngfile) || empty($modSettings['userLanguage']) ? $language : $lngfile);

	// Send them the email informing them of the change - then we're done!
	sendmail($email, $emaildata['subject'], $emaildata['body'], null, null, false, 0);
}

/**
 * Checks a username obeys a load of rules
 *
 * @param int $memID
 * @param string $username
 * @param boolean $return_error
 * @param boolean $check_reserved_name
 * @return string Returns null if fine
 */
function validateUsername($memID, $username, $return_error = false, $check_reserved_name = true)
{
	global $txt, $user_info;

	$errors = array();

	// Don't use too long a name.
	if (Util::strlen($username) > 25)
		$errors[] = array('lang', 'error_long_name');

	// No name?!  How can you register with no name?
	if ($username == '')
		$errors[] = array('lang', 'need_username');

	// Only these characters are permitted.
	if (in_array($username, array('_', '|')) || preg_match('~[<>&"\'=\\\\]~', preg_replace('~&#(?:\\d{1,7}|x[0-9a-fA-F]{1,6});~', '', $username)) != 0 || strpos($username, '[code') !== false || strpos($username, '[/code') !== false)
		$errors[] = array('lang', 'error_invalid_characters_username');

	if (stristr($username, $txt['guest_title']) !== false)
		$errors[] = array('lang', 'username_reserved', 'general', array($txt['guest_title']));

	if ($check_reserved_name)
	{
		require_once(SUBSDIR . '/Members.subs.php');
		if (isReservedName($username, $memID, false))
			$errors[] = array('done', '(' . htmlspecialchars($username) . ') ' . $txt['name_in_use']);
	}

	if ($return_error)
		return $errors;
	elseif (empty($errors))
		return null;

	loadLanguage('Errors');
	$error = $errors[0];

	$message = $error[0] == 'lang' ? (empty($error[3]) ? $txt[$error[1]] : vsprintf($txt[$error[1]], $error[3])) : $error[1];
	fatal_error($message, empty($error[2]) || $user_info['is_admin'] ? false : $error[2]);
}

/**
 * Checks whether a password meets the current forum rules
 * - called when registering/choosing a password.
 * - checks the password obeys the current forum settings for password strength.
 * - if password checking is enabled, will check that none of the words in restrict_in appear in the password.
 * - returns an error identifier if the password is invalid, or null.
 *
 * @param string $password
 * @param string $username
 * @param array $restrict_in = array()
 * @return string an error identifier if the password is invalid
 */
function validatePassword($password, $username, $restrict_in = array())
{
	global $modSettings;

	// Perform basic requirements first.
	if (Util::strlen($password) < (empty($modSettings['password_strength']) ? 4 : 8))
		return 'short';

	// Is this enough?
	if (empty($modSettings['password_strength']))
		return null;

	// Otherwise, perform the medium strength test - checking if password appears in the restricted string.
	if (preg_match('~\b' . preg_quote($password, '~') . '\b~', implode(' ', $restrict_in)) != 0)
		return 'restricted_words';
	elseif (Util::strpos($password, $username) !== false)
		return 'restricted_words';

	// If just medium, we're done.
	if ($modSettings['password_strength'] == 1)
		return null;

	// Otherwise, hard test next, check for numbers and letters, uppercase too.
	$good = preg_match('~(\D\d|\d\D)~', $password) != 0;
	$good &= Util::strtolower($password) != $password;

	return $good ? null : 'chars';
}

/**
 * Quickly find out what moderation authority this user has
 * - builds the moderator, group and board level querys for the user
 * - stores the information on the current users moderation powers in $user_info['mod_cache'] and $_SESSION['mc']
 */
function rebuildModCache()
{
	global $user_info;

	$db = database();

	// What groups can they moderate?
	$group_query = allowedTo('manage_membergroups') ? '1=1' : '0=1';

	if ($group_query == '0=1')
	{
		$request = $db->query('', '
			SELECT id_group
			FROM {db_prefix}group_moderators
			WHERE id_member = {int:current_member}',
			array(
				'current_member' => $user_info['id'],
			)
		);
		$groups = array();
		while ($row = $db->fetch_assoc($request))
			$groups[] = $row['id_group'];
		$db->free_result($request);

		if (empty($groups))
			$group_query = '0=1';
		else
			$group_query = 'id_group IN (' . implode(',', $groups) . ')';
	}

	// Then, same again, just the boards this time!
	$board_query = allowedTo('moderate_forum') ? '1=1' : '0=1';

	if ($board_query == '0=1')
	{
		$boards = boardsAllowedTo('moderate_board', true);

		if (empty($boards))
			$board_query = '0=1';
		else
			$board_query = 'id_board IN (' . implode(',', $boards) . ')';
	}

	// What boards are they the moderator of?
	$boards_mod = array();
	if (!$user_info['is_guest'])
	{
		$request = $db->query('', '
			SELECT id_board
			FROM {db_prefix}moderators
			WHERE id_member = {int:current_member}',
			array(
				'current_member' => $user_info['id'],
			)
		);
		while ($row = $db->fetch_assoc($request))
			$boards_mod[] = $row['id_board'];
		$db->free_result($request);
	}

	$mod_query = empty($boards_mod) ? '0=1' : 'b.id_board IN (' . implode(',', $boards_mod) . ')';

	$_SESSION['mc'] = array(
		'time' => time(),
		// This looks a bit funny but protects against the login redirect.
		'id' => $user_info['id'] && $user_info['name'] ? $user_info['id'] : 0,
		// If you change the format of 'gq' and/or 'bq' make sure to adjust 'can_mod' in Load.php.
		'gq' => $group_query,
		'bq' => $board_query,
		'ap' => boardsAllowedTo('approve_posts'),
		'mb' => $boards_mod,
		'mq' => $mod_query,
	);
	call_integration_hook('integrate_mod_cache');

	$user_info['mod_cache'] = $_SESSION['mc'];

	// Might as well clean up some tokens while we are at it.
	cleanTokens();
}

/**
 * The same thing as setcookie but gives support for HTTP-Only cookies in PHP < 5.2
 *
 * @param string $name
 * @param string $value = ''
 * @param int $expire = 0
 * @param string $path = ''
 * @param string $domain = ''
 * @param bool $secure = false
 * @param bool $httponly = null
 */
function elk_setcookie($name, $value = '', $expire = 0, $path = '', $domain = '', $secure = null, $httponly = null)
{
	global $modSettings;

	// In case a customization wants to override the default settings
	if ($httponly === null)
		$httponly = !empty($modSettings['httponlyCookies']);
	if ($secure === null)
		$secure = !empty($modSettings['secureCookies']);

	// Intercept cookie?
	call_integration_hook('integrate_cookie', array($name, $value, $expire, $path, $domain, $secure, $httponly));

	// This function is pointless if we have PHP >= 5.2.
	if (version_compare(PHP_VERSION, '5.2', '>='))
		return setcookie($name, $value, $expire, $path, $domain, $secure, $httponly);

	// $httponly is the only reason I made this function. If it's not being used, use setcookie().
	if (!$httponly)
		return setcookie($name, $value, $expire, $path, $domain, $secure);

	// Ugh, looks like we have to resort to using a manual process.
	header('Set-Cookie: '.rawurlencode($name).'='.rawurlencode($value)
			.(empty($domain) ? '' : '; Domain='.$domain)
			.(empty($expire) ? '' : '; Max-Age='.$expire)
			.(empty($path) ? '' : '; Path='.$path)
			.(!$secure ? '' : '; Secure')
			.(!$httponly ? '' : '; HttpOnly'), false);
}

/**
 * Set the passed users online or not, in the online log table
 *
 * @param array|int $ids ids of the member(s) to log
 * @param bool $on = false if true, add the user(s) to online log, if false, remove 'em
 */
function logOnline($ids, $on = false)
{
	$db = database();

	if (!is_array($ids))
		$ids = array($ids);

	if (empty($on))
	{
		// set the user(s) out of log_online
		$db->query('', '
			DELETE FROM {db_prefix}log_online
			WHERE id_member IN ({array_int:members})',
			array(
				'members' => $ids,
			)
		);
	}
}

/**
 * Delete expired/outdated session from log_online
 *
 * @param string $session
 */
function deleteOnline($session)
{
	$db = database();

	$db->query('', '
		DELETE FROM {db_prefix}log_online
		WHERE session = {string:session}',
		array(
			'session' => $session,
		)
	);
}

/**
 * This functions determines whether this is the first login of the given user.
 *
 * @param int $id_member the id of the member to check for
 */
function isFirstLogin($id_member)
{
	$db = database();

	$isFirstLogin = false;

	// First login?
	require_once(SUBSDIR . '/Members.subs.php');
	$member = getBasicMemberData($id_member, array('moderation' => true));

	return !empty($member) && $member['last_login'] == 0;
}

function findUser($where, $whereparams)
{
	$db = database();

	// Find the user!
	$request = $db->query('', '
		SELECT id_member, real_name, member_name, email_address, is_activated, validation_code, lngfile, openid_uri, secret_question
		FROM {db_prefix}members
		WHERE ' . $where . '
		LIMIT 1',
		array_merge($where_params, array(
		))
	);

	// Maybe email?
	if ($db->num_rows($request) == 0 && empty($_REQUEST['uid']))
	{
		$db->free_result($request);

		$request = $db->query('', '
			SELECT id_member, real_name, member_name, email_address, is_activated, validation_code, lngfile, openid_uri, secret_question
			FROM {db_prefix}members
			WHERE email_address = {string:email_address}
			LIMIT 1',
			array_merge($where_params, array(
			))
		);
		if ($db->num_rows($request) == 0)
			fatal_lang_error('no_user_with_email', false);
	}

	$member = $db->fetch_assoc($request);
	$db->free_result($request);

	return $member;
}

/**
 * Generate a random validation code.
 *
 * @return type
 */
function generateValidationCode()
{
	global $modSettings;

	$db = database();

	$request = $db->query('get_random_number', '
		SELECT RAND()',
		array(
		)
	);

	list ($dbRand) = $db->fetch_row($request);
	$db->free_result($request);

	return substr(preg_replace('/\W/', '', sha1(microtime() . mt_rand() . $dbRand . $modSettings['rand_seed'])), 0, 10);
}

/**
 * This function loads many settings of a user given by
 * name or email.
 *
 * @param string $name
 *
 * @return mixed, array or false if nothing is found
 */
function loadExistingMember($name)
{
	$db = database();

	// Try to find the user, assuming a member_name was passed...
	$request = $db->query('', '
		SELECT passwd, id_member, id_group, lngfile, is_activated, email_address, additional_groups, member_name, password_salt,
			openid_uri, passwd_flood
		FROM {db_prefix}members
		WHERE ' . ($db->db_case_sensitive() ? 'LOWER(member_name) = LOWER({string:user_name})' : 'member_name = {string:user_name}') . '
		LIMIT 1',
		array(
			'user_name' => $db->db_case_sensitive() ? strtolower($name) : $name,
		)
	);
	// Didn't work. Try it as an email address.
	if ($db->num_rows($request) == 0 && strpos($name, '@') !== false)
	{
		$db->free_result($request);

		$request = $db->query('', '
			SELECT passwd, id_member, id_group, lngfile, is_activated, email_address, additional_groups, member_name, password_salt, openid_uri,
			passwd_flood
			FROM {db_prefix}members
			WHERE email_address = {string:user_name}
			LIMIT 1',
			array(
				'user_name' => $name,
			)
		);
	}

	// Nothing? Ah the horror...
	if ($db->num_rows($request) == 0)
		return false;

	$user_settings = $db->fetch_assoc($request);
	$db->free_result($request);

	return $user_settings;
}