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
 * This file handles the package servers and packages download from Package Manager.
 *
 */

if (!defined('ELKARTE'))
	die('No access...');

/**
 * PackageServers controller handles browsing, adding and removing
 * package servers, and download of a package from them.
 */
class PackageServers_Controller
{
	/**
	 * Browse the list of package servers, add servers...
	 */
	public function action_index()
	{
		global $txt, $context;

		isAllowedTo('admin_forum');
		require_once(SUBSDIR . '/Package.subs.php');

		// Use the Packages template... no reason to separate.
		loadLanguage('Packages');
		loadTemplate('Packages', 'admin');

		$context['page_title'] = $txt['package'];

		// Here is a list of all the potentially valid actions.
		$subActions = array(
			'servers' => array($this, 'action_list'),
			'add' => array($this, 'action_add'),
			'browse' => array($this, 'action_browse'),
			'download' => array($this, 'action_download'),
			'remove' => array($this, 'action_remove'),
			'upload' => array($this, 'action_update'),
		);

		// Now let's decide where we are taking this...
		if (isset($_REQUEST['sa']) && isset($subActions[$_REQUEST['sa']]))
			$subAction = $_REQUEST['sa'];
		// We need to support possible old javascript links...
		elseif (isset($_GET['pgdownload']))
			$subAction = 'download';
		else
			$subAction = 'servers';

		// Set up action/subaction stuff.
		$action = new Action();
		$action->initialize($subActions);

		$context['sub_action'] = $subAction;

		// We need to force the "Download" tab as selected.
		$context['menu_data_' . $context['admin_menu_id']]['current_subsection'] = 'packageget';

		// Now create the tabs for the template.
		$context[$context['admin_menu_name']]['tab_data'] = array(
			'title' => $txt['package_manager'],
			//'help' => 'registrations',
			'description' => $txt['package_manager_desc'],
			'tabs' => array(
				'browse' => array(
				),
				'packageget' => array(
					'description' => $txt['download_packages_desc'],
				),
				'installed' => array(
					'description' => $txt['installed_packages_desc'],
				),
				'perms' => array(
					'description' => $txt['package_file_perms_desc'],
				),
				'options' => array(
					'description' => $txt['package_install_options_ftp_why'],
				),
			),
		);

		// lets just do it!
		$action->dispatch($subAction);
	}

	/**
	 * Load a list of package servers.
	 */
	public function action_list()
	{
		global $txt, $context, $modSettings;

		require_once(SUBSDIR . '/PackageServers.subs.php');

		// Ensure we use the correct template, and page title.
		$context['sub_template'] = 'servers';
		$context['page_title'] .= ' - ' . $txt['download_packages'];

		// Load the list of servers.
		$context['servers'] = fetchPackageServers();
		$context['package_download_broken'] = !is_writable(BOARDDIR . '/packages') || !is_writable(BOARDDIR . '/packages/installed.list');

		if ($context['package_download_broken'])
		{
			@chmod(BOARDDIR . '/packages', 0777);
			@chmod(BOARDDIR . '/packages/installed.list', 0777);
		}

		$context['package_download_broken'] = !is_writable(BOARDDIR . '/packages') || !is_writable(BOARDDIR . '/packages/installed.list');

		if ($context['package_download_broken'])
		{
			if (isset($_POST['ftp_username']))
			{
				require_once(SUBSDIR . '/FTPConnection.class.php');
				$ftp = new Ftp_Connection($_POST['ftp_server'], $_POST['ftp_port'], $_POST['ftp_username'], $_POST['ftp_password']);

				if ($ftp->error === false)
				{
					// I know, I know... but a lot of people want to type /home/xyz/... which is wrong, but logical.
					if (!$ftp->chdir($_POST['ftp_path']))
					{
						$ftp_error = $ftp->error;
						$ftp->chdir(preg_replace('~^/home[2]?/[^/]+?~', '', $_POST['ftp_path']));
					}
				}
			}

			if (!isset($ftp) || $ftp->error !== false)
			{
				if (!isset($ftp))
				{
					require_once(SUBSDIR . '/FTPConnection.class.php');
					$ftp = new Ftp_Connection(null);
				}
				elseif ($ftp->error !== false && !isset($ftp_error))
					$ftp_error = $ftp->last_message === null ? '' : $ftp->last_message;

				list ($username, $detect_path, $found_path) = $ftp->detect_path(BOARDDIR);

				if ($found_path || !isset($_POST['ftp_path']))
					$_POST['ftp_path'] = $detect_path;

				if (!isset($_POST['ftp_username']))
					$_POST['ftp_username'] = $username;

				$context['package_ftp'] = array(
					'server' => isset($_POST['ftp_server']) ? $_POST['ftp_server'] : (isset($modSettings['package_server']) ? $modSettings['package_server'] : 'localhost'),
					'port' => isset($_POST['ftp_port']) ? $_POST['ftp_port'] : (isset($modSettings['package_port']) ? $modSettings['package_port'] : '21'),
					'username' => isset($_POST['ftp_username']) ? $_POST['ftp_username'] : (isset($modSettings['package_username']) ? $modSettings['package_username'] : ''),
					'path' => $_POST['ftp_path'],
					'error' => empty($ftp_error) ? null : $ftp_error,
				);
			}
			else
			{
				$context['package_download_broken'] = false;

				$ftp->chmod('packages', 0777);
				$ftp->chmod('packages/installed.list', 0777);

				$ftp->close();
			}
		}
	}

	/**
	 * Browse a server's list of packages.
	 */
	public function action_browse()
	{
		global $txt, $context, $scripturl, $forum_version, $context;

		require_once(SUBSDIR . '/PackageServers.subs.php');

		if (isset($_GET['server']))
		{
			if ($_GET['server'] == '')
				redirectexit('action=admin;area=packages;get');

			$server = (int) $_GET['server'];

			// Query the server list to find the current server.
			$packageserver = fetchPackageServers($server);
			$url = $packageserver[0]['url'];
			$name = $packageserver[0]['name'];
			
			// If the server does not exist, dump out.
			if (empty($url))
				fatal_lang_error('couldnt_connect', false);

			// If there is a relative link, append to the stored server url.
			if (isset($_GET['relative']))
				$url = $url . (substr($url, -1) == '/' ? '' : '/') . $_GET['relative'];

			// Clear any "absolute" URL.  Since "server" is present, "absolute" is garbage.
			unset($_GET['absolute']);
		}
		elseif (isset($_GET['absolute']) && $_GET['absolute'] != '')
		{
			// Initialize the requried variables.
			$server = '';
			$url = $_GET['absolute'];
			$name = '';
			$_GET['package'] = $url . '/packages.xml?language=' . $context['user']['language'];

			// Clear any "relative" URL.  Since "server" is not present, "relative" is garbage.
			unset($_GET['relative']);

			$token = checkConfirm('get_absolute_url');
			if ($token !== true)
			{
				$context['sub_template'] = 'package_confirm';

				$context['page_title'] = $txt['package_servers'];
				$context['confirm_message'] = sprintf($txt['package_confirm_view_package_content'], htmlspecialchars($_GET['absolute']));
				$context['proceed_href'] = $scripturl . '?action=admin;area=packages;get;sa=browse;absolute=' . urlencode($_GET['absolute']) . ';confirm=' . $token;

				return;
			}
		}
		// Minimum required parameter did not exist so dump out.
		else
			fatal_lang_error('couldnt_connect', false);

		// Attempt to connect.  If unsuccessful... try the URL.
		if (!isset($_GET['package']) || file_exists($_GET['package']))
			$_GET['package'] = $url . '/packages.xml?language=' . $context['user']['language'];

		// Check to be sure the packages.xml file actually exists where it is should be... or dump out.
		if ((isset($_GET['absolute']) || isset($_GET['relative'])) && !url_exists($_GET['package']))
			fatal_lang_error('packageget_unable', false, array($url . '/index.php'));

		// Might take some time.
		@set_time_limit(600);

		// Read packages.xml and parse into Xml_Array. (the true tells it to trim things ;).)
		require_once(SUBSDIR . '/XmlArray.class.php');
		$listing = new Xml_Array(fetch_web_data($_GET['package']), true);

		// Errm.... empty file?  Try the URL....
		if (!$listing->exists('package-list'))
			fatal_lang_error('packageget_unable', false, array($url . '/index.php'));

		// List out the packages...
		$context['package_list'] = array();

		$listing = $listing->path('package-list[0]');

		// Use the package list's name if it exists.
		if ($listing->exists('list-title'))
			$name = $listing->fetch('list-title');

		// Pick the correct template.
		$context['sub_template'] = 'package_list';

		$context['page_title'] = $txt['package_servers'] . ($name != '' ? ' - ' . $name : '');
		$context['package_server'] = $server;

		// By default we use an unordered list, unless there are no lists with more than one package.
		$context['list_type'] = 'ul';

		$instmods = loadInstalledPackages();

		$installed_mods = array();
		// Look through the list of installed mods...
		foreach ($instmods as $installed_mod)
			$installed_mods[$installed_mod['package_id']] = $installed_mod['version'];

		// Get default author and email if they exist.
		if ($listing->exists('default-author'))
		{
			$default_author = Util::htmlspecialchars($listing->fetch('default-author'));
			if ($listing->exists('default-author/@email'))
				$default_email = Util::htmlspecialchars($listing->fetch('default-author/@email'));
		}

		// Get default web site if it exists.
		if ($listing->exists('default-website'))
		{
			$default_website = Util::htmlspecialchars($listing->fetch('default-website'));
			if ($listing->exists('default-website/@title'))
				$default_title = Util::htmlspecialchars($listing->fetch('default-website/@title'));
		}

		$the_version = strtr($forum_version, array('ELKARTE ' => ''));
		if (!empty($_SESSION['version_emulate']))
			$the_version = $_SESSION['version_emulate'];

		$packageNum = 0;
		$packageSection = 0;

		$sections = $listing->set('section');
		foreach ($sections as $i => $section)
		{
			$context['package_list'][$packageSection] = array(
				'title' => '',
				'text' => '',
				'items' => array(),
			);

			$packages = $section->set('title|heading|text|remote|rule|modification|language|avatar-pack|theme|smiley-set');
			foreach ($packages as $thisPackage)
			{
				$package = array(
					'type' => $thisPackage->name(),
				);

				if (in_array($package['type'], array('title', 'text')))
					$context['package_list'][$packageSection][$package['type']] = Util::htmlspecialchars($thisPackage->fetch('.'));
				// It's a Title, Heading, Rule or Text.
				elseif (in_array($package['type'], array('heading', 'rule')))
					$package['name'] = Util::htmlspecialchars($thisPackage->fetch('.'));
				// It's a Remote link.
				elseif ($package['type'] == 'remote')
				{
					$remote_type = $thisPackage->exists('@type') ? $thisPackage->fetch('@type') : 'relative';

					if ($remote_type == 'relative' && substr($thisPackage->fetch('@href'), 0, 7) != 'http://')
					{
						if (isset($_GET['absolute']))
							$current_url = $_GET['absolute'] . '/';
						elseif (isset($_GET['relative']))
							$current_url = $_GET['relative'] . '/';
						else
							$current_url = '';

						$current_url .= $thisPackage->fetch('@href');
						if (isset($_GET['absolute']))
							$package['href'] = $scripturl . '?action=admin;area=packages;get;sa=browse;absolute=' . $current_url;
						else
							$package['href'] = $scripturl . '?action=admin;area=packages;get;sa=browse;server=' . $context['package_server'] . ';relative=' . $current_url;
					}
					else
					{
						$current_url = $thisPackage->fetch('@href');
						$package['href'] = $scripturl . '?action=admin;area=packages;get;sa=browse;absolute=' . $current_url;
					}

					$package['name'] = Util::htmlspecialchars($thisPackage->fetch('.'));
					$package['link'] = '<a href="' . $package['href'] . '">' . $package['name'] . '</a>';
				}
				// It's a package...
				else
				{
					if (isset($_GET['absolute']))
						$current_url = $_GET['absolute'] . '/';
					elseif (isset($_GET['relative']))
						$current_url = $_GET['relative'] . '/';
					else
						$current_url = '';

					$server_att = $server != '' ? ';server=' . $server : '';

					$package += $thisPackage->to_array();

					if (isset($package['website']))
						unset($package['website']);
					$package['author'] = array();

					if ($package['description'] == '')
						$package['description'] = $txt['package_no_description'];
					else
						$package['description'] = parse_bbc(preg_replace('~\[[/]?html\]~i', '', Util::htmlspecialchars($package['description'])));

					$package['is_installed'] = isset($installed_mods[$package['id']]);
					$package['is_current'] = $package['is_installed'] && ($installed_mods[$package['id']] == $package['version']);
					$package['is_newer'] = $package['is_installed'] && ($installed_mods[$package['id']] > $package['version']);

					// This package is either not installed, or installed but old.  Is it supported on this version?
					if (!$package['is_installed'] || (!$package['is_current'] && !$package['is_newer']))
					{
						if ($thisPackage->exists('version/@for'))
							$package['can_install'] = matchPackageVersion($the_version, $thisPackage->fetch('version/@for'));
					}
					// Okay, it's already installed AND up to date.
					else
						$package['can_install'] = false;

					$already_exists = getPackageInfo(basename($package['filename']));
					$package['download_conflict'] = is_array($already_exists) && $already_exists['id'] == $package['id'] && $already_exists['version'] != $package['version'];

					$package['href'] = $url . '/' . $package['filename'];
					$package['name'] = Util::htmlspecialchars($package['name']);
					$package['link'] = '<a href="' . $package['href'] . '">' . $package['name'] . '</a>';
					$package['download']['href'] = $scripturl . '?action=admin;area=packages;get;sa=download' . $server_att . ';package=' . $current_url . $package['filename'] . ($package['download_conflict'] ? ';conflict' : '') . ';' . $context['session_var'] . '=' . $context['session_id'];
					$package['download']['link'] = '<a href="' . $package['download']['href'] . '">' . $package['name'] . '</a>';

					if ($thisPackage->exists('author') || isset($default_author))
					{
						if ($thisPackage->exists('author/@email'))
							$package['author']['email'] = $thisPackage->fetch('author/@email');
						elseif (isset($default_email))
							$package['author']['email'] = $default_email;

						if ($thisPackage->exists('author') && $thisPackage->fetch('author') != '')
							$package['author']['name'] = Util::htmlspecialchars($thisPackage->fetch('author'));
						else
							$package['author']['name'] = $default_author;

						if (!empty($package['author']['email']))
						{
							// Only put the "mailto:" if it looks like a valid email address.  Some may wish to put a link to an IM Form or other web mail form.
							$package['author']['href'] = preg_match('~^[\w\.\-]+@[\w][\w\-\.]+[\w]$~', $package['author']['email']) != 0 ? 'mailto:' . $package['author']['email'] : $package['author']['email'];
							$package['author']['link'] = '<a href="' . $package['author']['href'] . '">' . $package['author']['name'] . '</a>';
						}
					}

					if ($thisPackage->exists('website') || isset($default_website))
					{
						if ($thisPackage->exists('website') && $thisPackage->exists('website/@title'))
							$package['author']['website']['name'] = Util::htmlspecialchars($thisPackage->fetch('website/@title'));
						elseif (isset($default_title))
							$package['author']['website']['name'] = $default_title;
						elseif ($thisPackage->exists('website'))
							$package['author']['website']['name'] = Util::htmlspecialchars($thisPackage->fetch('website'));
						else
							$package['author']['website']['name'] = $default_website;

						if ($thisPackage->exists('website') && $thisPackage->fetch('website') != '')
							$authorhompage = $thisPackage->fetch('website');
						else
							$authorhompage = $default_website;

						if (stripos($authorhompage, 'a href') === false)
						{
							$package['author']['website']['href'] = $authorhompage;
							$package['author']['website']['link'] = '<a href="' . $authorhompage . '">' . $package['author']['website']['name'] . '</a>';
						}
						else
						{
							if (preg_match('/a href="(.+?)"/', $authorhompage, $match) == 1)
								$package['author']['website']['href'] = $match[1];
							else
								$package['author']['website']['href'] = '';
							$package['author']['website']['link'] = $authorhompage;
						}
					}
					else
					{
						$package['author']['website']['href'] = '';
						$package['author']['website']['link'] = '';
					}
				}

				$package['is_remote'] = $package['type'] == 'remote';
				$package['is_title'] = $package['type'] == 'title';
				$package['is_heading'] = $package['type'] == 'heading';
				$package['is_text'] = $package['type'] == 'text';
				$package['is_line'] = $package['type'] == 'rule';

				$packageNum = in_array($package['type'], array('title', 'heading', 'text', 'remote', 'rule')) ? 0 : $packageNum + 1;
				$package['count'] = $packageNum;

				if (!in_array($package['type'], array('title', 'text')))
					$context['package_list'][$packageSection]['items'][] = $package;

				if ($package['count'] > 1)
					$context['list_type'] = 'ol';
			}

			$packageSection++;
		}

		// Lets make sure we get a nice new spiffy clean $package to work with.  Otherwise we get PAIN!
		unset($package);

		foreach ($context['package_list'] as $ps_id => $packageSection)
		{
			foreach ($packageSection['items'] as $i => $package)
			{
				if ($package['count'] == 0 || isset($package['can_install']))
					continue;

				$context['package_list'][$ps_id]['items'][$i]['can_install'] = false;

				$packageInfo = getPackageInfo($url . '/' . $package['filename']);
				if (is_array($packageInfo) && $packageInfo['xml']->exists('install'))
				{
					$installs = $packageInfo['xml']->set('install');
					foreach ($installs as $install)
						if (!$install->exists('@for') || matchPackageVersion($the_version, $install->fetch('@for')))
						{
							// Okay, this one is good to go.
							$context['package_list'][$ps_id]['items'][$i]['can_install'] = true;
							break;
						}
				}
			}
		}
	}

	/**
	 * Download a package.
	 */
	public function action_download()
	{
		global $txt, $scripturl, $context;

		require_once(SUBSDIR . '/PackageServers.subs.php');

		// Use the downloaded sub template.
		$context['sub_template'] = 'downloaded';

		// Security is good...
		checkSession('get');

		// To download something, we need a valid server or url.
		if (empty($_GET['server']) && (!empty($_GET['get']) && !empty($_REQUEST['package'])))
			fatal_lang_error('package_get_error_is_zero', false);

		if (isset($_GET['server']))
		{
			$server = (int) $_GET['server'];

			// Query the server table to find the requested server.
			$packageserver = fetchPackageServers($server);
			$url = $packageserver[0]['url'];

			// If server does not exist then dump out.
			if (empty($url))
				fatal_lang_error('couldnt_connect', false);

			$url = $url . '/';
		}
		else
		{
			// Initialize the requried variables.
			$server = '';
			$url = '';
		}

		if (isset($_REQUEST['byurl']) && !empty($_POST['filename']))
			$package_name = basename($_REQUEST['filename']);
		else
			$package_name = basename($_REQUEST['package']);

		if (isset($_REQUEST['conflict']) || (isset($_REQUEST['auto']) && file_exists(BOARDDIR . '/packages/' . $package_name)))
		{
			// Find the extension, change abc.tar.gz to abc_1.tar.gz...
			if (strrpos(substr($package_name, 0, -3), '.') !== false)
			{
				$ext = substr($package_name, strrpos(substr($package_name, 0, -3), '.'));
				$package_name = substr($package_name, 0, strrpos(substr($package_name, 0, -3), '.')) . '_';
			}
			else
				$ext = '';

			// Find the first available.
			$i = 1;
			while (file_exists(BOARDDIR . '/packages/' . $package_name . $i . $ext))
				$i++;

			$package_name = $package_name . $i . $ext;
		}

		// First make sure it's a package.
		$packageInfo = getPackageInfo($url . $_REQUEST['package']);
		if (!is_array($packageInfo))
			fatal_lang_error($packageInfo);

		// Use FTP if necessary.
		create_chmod_control(array(BOARDDIR . '/packages/' . $package_name), array('destination_url' => $scripturl . '?action=admin;area=packages;get;sa=download' . (isset($_GET['server']) ? ';server=' . $_GET['server'] : '') . (isset($_REQUEST['auto']) ? ';auto' : '') . ';package=' . $_REQUEST['package'] . (isset($_REQUEST['conflict']) ? ';conflict' : '') . ';' . $context['session_var'] . '=' . $context['session_id'], 'crash_on_error' => true));
		package_put_contents(BOARDDIR . '/packages/' . $package_name, fetch_web_data($url . $_REQUEST['package']));

		// Done!  Did we get this package automatically?
		if (preg_match('~^http://[\w_\-]+\.simplemachines\.org/~', $_REQUEST['package']) == 1 && strpos($_REQUEST['package'], 'dlattach') === false && isset($_REQUEST['auto']))
			redirectexit('action=admin;area=packages;sa=install;package=' . $package_name);

		// You just downloaded a mod from SERVER_NAME_GOES_HERE.
		$context['package_server'] = $server;

		$context['package'] = getPackageInfo($package_name);

		if (!is_array($context['package']))
			fatal_lang_error('package_cant_download', false);

		if ($context['package']['type'] == 'modification')
			$context['package']['install']['link'] = '<a href="' . $scripturl . '?action=admin;area=packages;sa=install;package=' . $context['package']['filename'] . '">[ ' . $txt['install_mod'] . ' ]</a>';
		elseif ($context['package']['type'] == 'avatar')
			$context['package']['install']['link'] = '<a href="' . $scripturl . '?action=admin;area=packages;sa=install;package=' . $context['package']['filename'] . '">[ ' . $txt['use_avatars'] . ' ]</a>';
		elseif ($context['package']['type'] == 'language')
			$context['package']['install']['link'] = '<a href="' . $scripturl . '?action=admin;area=packages;sa=install;package=' . $context['package']['filename'] . '">[ ' . $txt['add_languages'] . ' ]</a>';
		else
			$context['package']['install']['link'] = '';

		$context['package']['list_files']['link'] = '<a href="' . $scripturl . '?action=admin;area=packages;sa=list;package=' . $context['package']['filename'] . '">[ ' . $txt['list_files'] . ' ]</a>';

		// Free a little bit of memory...
		unset($context['package']['xml']);

		$context['page_title'] = $txt['download_success'];
	}

	/**
	 * Upload a new package to the directory.
	 */
	public function action_update()
	{
		global $txt, $scripturl, $context;

		// Setup the correct template, even though I'll admit we ain't downloading ;)
		$context['sub_template'] = 'downloaded';

		// @todo Use FTP if the packages directory is not writable.

		// Check the file was even sent!
		if (!isset($_FILES['package']['name']) || $_FILES['package']['name'] == '')
			fatal_lang_error('package_upload_error_nofile');
		elseif (!is_uploaded_file($_FILES['package']['tmp_name']) || (ini_get('open_basedir') == '' && !file_exists($_FILES['package']['tmp_name'])))
			fatal_lang_error('package_upload_error_failed');

		// Make sure it has a sane filename.
		$_FILES['package']['name'] = preg_replace(array('/\s/', '/\.[\.]+/', '/[^\w_\.\-]/'), array('_', '.', ''), $_FILES['package']['name']);

		if (strtolower(substr($_FILES['package']['name'], -4)) != '.zip' && strtolower(substr($_FILES['package']['name'], -4)) != '.tgz' && strtolower(substr($_FILES['package']['name'], -7)) != '.tar.gz')
			fatal_lang_error('package_upload_error_supports', false, array('zip, tgz, tar.gz'));

		// We only need the filename...
		$packageName = basename($_FILES['package']['name']);

		// Setup the destination and throw an error if the file is already there!
		$destination = BOARDDIR . '/packages/' . $packageName;
		// @todo Maybe just roll it like we do for downloads?
		if (file_exists($destination))
			fatal_lang_error('package_upload_error_exists');

		// Now move the file.
		move_uploaded_file($_FILES['package']['tmp_name'], $destination);
		@chmod($destination, 0777);

		// If we got this far that should mean it's available.
		$context['package'] = getPackageInfo($packageName);
		$context['package_server'] = '';

		// Not really a package, you lazy bum!
		if (!is_array($context['package']))
		{
			@unlink($destination);
			loadLanguage('Errors');
			$txt[$context['package']] = str_replace('{MANAGETHEMEURL}', $scripturl . '?action=admin;area=theme;sa=admin;' . $context['session_var'] . '=' . $context['session_id'] . '#theme_install', $txt[$context['package']]);
			fatal_lang_error('package_upload_error_broken', false, $txt[$context['package']]);
		}
		// Is it already uploaded, maybe?
		elseif ($dir = @opendir(BOARDDIR . '/packages'))
		{
			while ($package = readdir($dir))
			{
				if ($package == '.' || $package == '..' || $package == 'temp' || $package == $packageName || (!(is_dir(BOARDDIR . '/packages/' . $package) && file_exists(BOARDDIR . '/packages/' . $package . '/package-info.xml')) && substr(strtolower($package), -7) != '.tar.gz' && substr(strtolower($package), -4) != '.tgz' && substr(strtolower($package), -4) != '.zip'))
					continue;

				$packageInfo = getPackageInfo($package);
				if (!is_array($packageInfo))
					continue;

				if ($packageInfo['id'] == $context['package']['id'] && $packageInfo['version'] == $context['package']['version'])
				{
					@unlink($destination);
					loadLanguage('Errors');
					fatal_lang_error('package_upload_error_exists');
				}
			}
			closedir($dir);
		}

		if ($context['package']['type'] == 'modification')
			$context['package']['install']['link'] = '<a href="' . $scripturl . '?action=admin;area=packages;sa=install;package=' . $context['package']['filename'] . '">[ ' . $txt['install_mod'] . ' ]</a>';
		elseif ($context['package']['type'] == 'avatar')
			$context['package']['install']['link'] = '<a href="' . $scripturl . '?action=admin;area=packages;sa=install;package=' . $context['package']['filename'] . '">[ ' . $txt['use_avatars'] . ' ]</a>';
		elseif ($context['package']['type'] == 'language')
			$context['package']['install']['link'] = '<a href="' . $scripturl . '?action=admin;area=packages;sa=install;package=' . $context['package']['filename'] . '">[ ' . $txt['add_languages'] . ' ]</a>';
		else
			$context['package']['install']['link'] = '';

		$context['package']['list_files']['link'] = '<a href="' . $scripturl . '?action=admin;area=packages;sa=list;package=' . $context['package']['filename'] . '">[ ' . $txt['list_files'] . ' ]</a>';

		unset($context['package']['xml']);

		$context['page_title'] = $txt['package_uploaded_success'];
	}

	/**
	 * Add a package server to the list.
	 */
	public function action_add()
	{
		require_once(SUBSDIR . '/PackageServers.subs.php');
		// Validate the user.
		checkSession();

		// If they put a slash on the end, get rid of it.
		if (substr($_POST['serverurl'], -1) == '/')
			$_POST['serverurl'] = substr($_POST['serverurl'], 0, -1);

		// Are they both nice and clean?
		$servername = trim(Util::htmlspecialchars($_POST['servername']));
		$serverurl = trim(Util::htmlspecialchars($_POST['serverurl']));

		// Make sure the URL has the correct prefix.
		if (strpos($serverurl, 'http://') !== 0 && strpos($serverurl, 'https://') !== 0)
			$serverurl = 'http://' . $serverurl;

		addPackageServer($servername, $serverurl);

		redirectexit('action=admin;area=packages;get');
	}

	/**
	 * Remove a server from the list.
	 */
	public function action_remove()
	{
		checkSession('get');

		require_once(SUBSDIR . '/PackageServer.subs.php');

		$_GET['server'] = (int) $_GET['server'];
		deletePackageServer($_GET['server']);

		redirectexit('action=admin;area=packages;get');
	}
}