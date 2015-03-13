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

defined("MOODLE_INTERNAL") || die();

$observers = array(

		array('eventname' => '\core\event\user_loggedin',
		      'includefile' => '/local/checkup/lib.php',
		      'callback' => 'local_checkup_session_redirect',
		      'priority' => 9999),

		array('eventname' => '\core\event\user_updated',
		      'includefile' => '/local/checkup/lib.php',
		      'callback' => 'local_checkup_set_updated',
		      'priority' => 1000),

		array('eventname' => '\core\event\course_viewed',
		      'includefile' => '/local/checkup/lib.php',
		      'callback' => 'local_checkup_force_redirect',
		      'priority' => 9999));
