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

$string["pluginname"] = 'Nutzer&uuml;berpr&uuml;fung';
$string["userupdated"] = '{$a} Nutzer/innen wurden zur &Uuml;berpr&uuml;fung vorgemerkt.';
$string["usernotupdated"] = '{$a->firstname} {$a->lastname} mit ID {$a->id} konnte nicht aktualisiert werden.';
$string["failedupdate"] = 'Das Script konnte {$a} Profile nicht aktualisieren. Die genauen Fehlermeldungen entnehmen Sie bitte der oberen Ausschrift.';
$string["start"] = 'Nutzerdaten überprüfen lassen';
$string["startinfo"] = 'Durch Selbsteinschreibung erzeugte Nutzerdaten sind nicht immer auf dem aktuellsten Stand. Sollten Sie Zweifel an der Aktualit&auml;t der Daten haben, k&ouml;nnen Sie hiermit die Nutzer/innen dazu zu zwingen ihre Daten zu &uuml;berpr&uuml;fen. Bei jedem Login der Nutzer/innen wird dann eine Aufforderung zur &Uuml;berpr&uuml;fung der Daten angezeigt, bis das Formular erfolgreich abgeschickt wurde.';
$string["checkdata"] = '&Uuml;berprüfung der Profildaten';
$string["checkinfo"] = 'Dieses System h&auml;ngt von der Aktualit&auml;t Ihrer Profildaten ab. Bitte <a href="{$a}">pr&uuml;fen Sie daher ihr Profil</a>, ob die Angaben noch immer korrekt sind.';
$string["nocheckup"] = 'Diese Seite kann nicht direkt aufgerufen werden. Sie werden automatisch hierher weiter geleitet, sollten Sie Ihre Daten best&auml;tigen m&uuml;ssen.';
