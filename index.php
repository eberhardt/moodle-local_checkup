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
require_once($CFG->dirroot . "/local/checkup/checkup_form.php");
require_once($CFG->dirroot . "/user/editlib.php");
require_once($CFG->dirroot . "/user/profile/lib.php");
require_once($CFG->dirroot . "/user/edit_form.php");

require_login($SITE, false);

$context = context_system::instance();
$PAGE->set_url(new moodle_url("/local/checkup/index.php"));
$PAGE->set_context($context);
$PAGE->set_title(get_string("checkdata", "local_checkup"));
$PAGE->navbar->add(get_string("checkdata", "local_checkup"));

$force = clean_param(get_user_preferences("force_checkup", false), PARAM_BOOL);
if ($USER->auth !== "email" || !$force) {
	redirect($CFG->wwwroot . "/my", get_string("nocheckup", "local_checkup"), 10);
}

/*
$form = new user_edit_form();
$form->set_data($USER);

if ($form->is_submitted() && $form->is_validated()) {
	if ($data = $form->get_data()) {
		echo "<pre>"; var_dump($data); die;
		$user = get_complete_user_data("id", $USER->id);
		foreach ($data as $key => $value) {
			$user->$key = $value;
		}
		foreach ($user as $key => $value) {
			$user->$key = clean_text($value, FORMAT_PLAIN);
		}
		$DB->update_record("user", $user);
	}
	set_user_preference("force_checkup", null);
	$url = empty($SESSION->wantsurl2) ? $CFG->wwwroot . "/my" : $SESSION->wantsurl2;
	$SESSION->wantsurl = null;
	$SESSION->wantsurl2 = null;
	redirect($url);
}
*/
echo $OUTPUT->header();

echo $OUTPUT->heading(get_string("checkdata", "local_checkup"))
   . $OUTPUT->box_start("generalbox", "notice")
   . html_writer::tag("p", get_string("checkinfo", "local_checkup", $CFG->wwwroot . "/user/edit.php"))
   . $OUTPUT->box_end();
//$form->display();

echo $OUTPUT->footer();
