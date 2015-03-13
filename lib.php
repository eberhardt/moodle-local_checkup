<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Tool, um Nutzende dazu zu zwingen, ihre Daten zu überprüfen
 *
 * @package   local_checkup
 * @copyright 2015 Jan Eberhardt (@innoCampus, TU Berlin)
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Activates forced checkup of a user
 *
 * @param stdClass $user
 */
function local_checkup_activate_for(stdClass $user) {
	/*
	 * Tried workaround to prevent core code changes... didn't worked out. The code should force moodle mechanics by
	 * artificially letting `over_bounce_threshold()` return `true`. It would have lead to `user_not_fully_set_up()`
	 * returning `true` and redirect to profile edit page.
	 *
	 * But since, there's no possibility to have a message prompted, why there's this redirect (without changing of core
	 * code again) this would have lead to confusion.
	 *
	 * As general remark: `$CFG->handlebounces` must be set in `/config.php`.
	 *
	$prefarray = array();
	$bouncecount = get_user_preferences("email_bounce_count", null, $user);
	if (!empty($bouncecount)) {
		$prefarray["email_bounce_count"] = $bouncecount;
	}
	$sendcount = get_user_preferences("email_send_count", null, $user);
	if (!empty($sendcount)) {
		$prefarray["email_send_count"] = $bouncecount;
	}
	if (!empty($prefarray)) {
		// Save old settings, if exist.
		set_user_preference("checkup_counts", serialize($prefarray), $user);
	}
	// Ensure that over_bounce_threshold() returns TRUE.
	$prefarray = array("email_bounce_count" => 1000,
	                   "email_send_count" => 1);
	set_user_preferences($prefarray, $user);
	*/
	return set_user_preference("checkup_forcecheck", "1", $user);
}

/**
 * Deactivates forced checkup of a user
 *
 * @param stdClass $user
 * @return boolean
 */
function local_checkup_deactivate_for(stdClass $user = null) {
	/*
	 * Tried workaround to prevent core code changes... didn't worked out. See notes above.
	 *
	$counts = get_user_preferences("checkup_counts", null, $user);
	if (empty($counts)) {
		// Just delete artificial values.
		$prefarray = array("email_bounce_count" => null,
		                   "email_send_count" => null);
	} else {
		// Restore old settings first.
		$prefarray = unserialize($counts);
		// Ensure, that all artificial values are deleted
		if (empty($prefarray["email_bounce_count"])) {
			$prefarray["email_bounce_count"] = null;
		}
		if (empty($prefarray["email_send_count"])) {
			$prefarray["email_bounce_count"] = null;
		}
		// Delete temporal storage.
		$prefarray["checkup_counts"] = null;
	}
	return set_user_preferences($prefarray, $user);
	*/
	global $SESSION;
	$prefarray = array("checkup_forcecheck" => null);
	$wantsurl = get_user_preferences("checkup_wantsurl", null, $user);
	if (!empty($wantsurl)) {
		$SESSION->wantsurl = $wantsurl;
		$prefarray["checkup_wantsurl"] = null;
	}
	return set_user_preferences($prefarray, $user);
}

/**
 * Redirects to
 *
 * @param unknown $preventredirect
 * @param unknown $setwantsurltome
 * @see require_login()
 */
function local_checkup_manual_redirect() {
	global $CFG;
	if (local_checkup_mustcheck() && !\core\session\manager::is_loggedinas()) {
		local_checkup_wantsurl_update();
		$wwwroot = str_replace('http:', 'https:', $CFG->wwwroot);
		redirect($wwwroot . '/local/checkup/index.php');
	}
}

/**
 * Redirects to profile information verification
 *
 * @param stdClass $eventdata
 * @return boolean
 */
function local_checkup_force_redirect($eventdata) {
	global $CFG, $DB;

	// No authenticated user.
	if (empty($eventdata->userid)) {
		return true;
	}
	if (local_checkup_mustcheck()) {
		local_checkup_wantsurl_update();
		$wwwroot = str_replace('http:', 'https:', $CFG->wwwroot);
		redirect($wwwroot . "/local/checkup/index.php");
	}

	return true;
}

/**
 * Redirects to profile information verification via $SESSION->wantsurl
 *
 * @param stdClass $eventdata
 * @return boolean
 */
function local_checkup_session_redirect($eventdata) {
	global $SESSION, $CFG;

	if (local_checkup_mustcheck()) {
		local_checkup_wantsurl_update();
	}

	return true;
}

/**
 * Updates and saves wantsurl, if isset
 */
function local_checkup_wantsurl_update() {
	global $SESSION, $CFG;
	if (isset($SESSION->wantsurl)) {
		set_user_preference("checkup_wantsurl", $SESSION->wantsurl);
	} else {
		// Delete setting from previous login.
		set_user_preference("checkup_wantsurl", null);
	}
	$SESSION->wantsurl = $CFG->wwwroot . "/local/checkup/index.php";
}

/**
 * Sets preference `force_checkup` of related user to false.
 *
 * @param stdClass $eventdata
 * @return boolean
 */
function local_checkup_set_updated($eventdata) {
	global $SESSION;
	$user = get_complete_user_data("id", $eventdata->relateduserid);

	return local_checkup_deactivate_for($user);
}

/**
 * Returns, if $user has to do a checkup
 *
 * @param stdClass $user
 * @return boolean
 */
function local_checkup_mustcheck() {
	global $USER;

	return isloggedin()
	       && $USER->auth === "email"
	       && !isguestuser()
	       && get_user_preferences("checkup_forcecheck", false);
}
