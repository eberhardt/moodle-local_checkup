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

require_once("../../config.php");
require_once($CFG->libdir . "/adminlib.php");
require_once(__DIR__ . "/lib.php");

$context = context_system::instance();
$confirm = optional_param("confirm", false, PARAM_BOOL);

require_login($SITE, false);
require_capability("local/checkup:manage", $context);

admin_externalpage_setup("checkup");

echo $OUTPUT->header()
   . $OUTPUT->heading(get_string("start", "local_checkup"));

if ($confirm && isloggedin() && confirm_sesskey()) {
	$users = $DB->get_records("user", array("auth" => "email"));
	$failed = 0;
	$errors = array();
	foreach ($users as $user) {
		try {
			local_checkup_activate_for($user);
		} catch (moodle_exception $e) {
			++$failed;
			$message = html_writer::div(get_string("usernotupdated", "local_checkup", $user))
			. html_writer::start_div()
			. $e->getMessage()
			. html_writer::tag("pre", $e->getTraceAsString())
			. html_writer::end_div();
			echo $OUTPUT->notification($message);
		}
	}
	echo $OUTPUT->notification(get_string("userupdated", "local_checkup", count($users)-$failed), "notifysuccess");
	if ($failed > 0) {
		echo $OUTPUT->notification(get_string("failedupdate", "local_checkup", $failed));
	}
} else {
	$actionurl = new moodle_url("/local/checkup/start.php", array("sesskey" => sesskey(), "confirm" => 1));
	echo $OUTPUT->box_start("generalbox", "notice")
	   . html_writer::tag("p", get_string("startinfo", "local_checkup"))
	   . $OUTPUT->single_button($actionurl, get_string("start", "local_checkup"))
	   . $OUTPUT->box_end();
}

echo $OUTPUT->footer();
