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

function local_checkup_force_redirect($eventdata) {
	global $CFG;
	if (!isguestuser() && local_checkup_redirect($eventdata)) {
		redirect($CFG->wwwroot . "/local/checkup/index.php");
	}
	return true;
}

/**
 * Redirects to profile information verification
 *
 * @param stdClass $eventdata
 * @return boolean
 */
function local_checkup_redirect($eventdata) {
	global $SESSION, $CFG;
	if (isguestuser()) {
		return true;
	}
	$user = get_complete_user_data("id", $eventdata->userid);
	if ($user && $user->auth === "email") {
		$redirect = get_user_preferences("force_checkup", false, $user);
		$redirect = clean_param($redirect, PARAM_BOOL);
		if ($redirect) {
			if (isset($SESSION->wantsurl)) {
				set_user_preference("checkup_wantsurl", $SESSION->wantsurl, $user);
			} else {
				// Delete setting from previous login.
				set_user_preference("checkup_wantsurl", null, $user);
			}
			$SESSION->wantsurl = $CFG->wwwroot . "/local/checkup/index.php";
		}
	}
	return $user !== false;
}

/**
 * Sets preference `force_checkup` of related user to false.
 *
 * @param stdClass $eventdata
 * @return boolean
 */
function local_checkup_set_updated($eventdata) {
	$user = get_complete_user_data("id", $eventdata->relateduserid);
	$wantsurl = get_user_preferences("checkup_wantsurl", null, $user);
	if (!empty($wantsurl) && empty($SESSION->wantsurl)) {
		// Set new url only, if there is no other page the user wants to visit.
		$SESSION->wantsurl = $wantsurl;
	}
	$userprefs = array("force_checkup" => null,
	                   "checkup_wantsurl" => null);
	return set_user_preferences($userprefs, $user);
}

/**
 * Returns, if $user has to do a checkup
 *
 * @param stdClass $user
 * @return boolean
 */
function local_checkup_mustcheck(stdClass $user) {
	//TODO
	return true;
}