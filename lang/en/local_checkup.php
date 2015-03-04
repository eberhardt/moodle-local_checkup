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

$string["pluginname"] = 'User Checkup';
$string["userupdated"] = '{$a} users were marked for a checkup.';
$string["usernotupdated"] = 'Could not update the user {$a->firstname} {$a->lastname} with ID {$a->id}.';
$string["failedupdate"] = 'The script failed on {$a} updates. Check above for specific error messages.';
$string["start"] = 'Start forced checkup';
$string["startinfo"] = 'If you rely on up-to-date user data it is sometimes necessary to force self-signed users to verify their information. If you doubt the correctness of your data you may force every user to verify the information, by starting the checkup. On every login of a user he/she will redirect to a verification page, until the form is submitted successfully.';
$string["checkdata"] = 'Check your profile data';
$string["checkinfo"] = 'This system depends on up-to-date data. Please <a href="{$a}">check your profile</a>, if the given information is still valid. Although nothing may have been changed, you have to submit the data at least once to disabled this notification.';
$string["nocheckup"] = 'You don\'t need to call this page by yourself. You will be automatically redirected to this page, if you need to verify your data.';
