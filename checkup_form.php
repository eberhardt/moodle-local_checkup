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

require_once($CFG->libdir . "/formslib.php");

class local_checkup_form extends moodleform {

	/**
	 * (non-PHPdoc)
	 * @see moodleform::definition()
	 */
	function definition() {
		global $USER, $DB, $OUTPUT;

		$form = &$this->_form;

		$form->addElement("header", "base", get_string("other"));
		$form->addElement("text", "email", get_string("email"));
		$form->addElement("text", "email2", get_string("emailagain"));
		$form->addElement("text", "firstname", get_string("firstname"));
		$form->addElement("text", "lastname", get_string("lastname"));
		$form->addElement("text", "phone", get_string("phone"));
		$form->addElement("text", "city", get_string("city"));

		$country = get_string_manager()->get_list_of_countries();
		$form->addElement("select", "country", get_string("country"), $country);

		/* Types */
		$types = array("email" => PARAM_EMAIL,
		               "email2" => PARAM_EMAIL,
		               "firstname" => PARAM_ALPHAEXT,
		               "lastname" => PARAM_ALPHAEXT,
		               "phone" => PARAM_ALPHANUMEXT,
		               "city" => PARAM_ALPHAEXT,
		               "country" => PARAM_ALPHAEXT);
		$form->setTypes($types);

		/* Rules */
		$form->addRule("email", get_string("required"), "required");
		$form->addRule("email2", get_string("required"), "required");
		$form->addRule("firstname", get_string("required"), "required");
		$form->addRule("lastname", get_string("required"), "required");
		$form->addRule("city", get_string("required"), "required");
		$form->addRule("country", get_string("required"), "required");

		/* Defaults */
		$this->set_data($USER);

		/* Custom profile fields */

		$sql = <<<EOS
SELECT
	f.*,
	c.name as category
FROM
	{user_info_field} f
	JOIN {user_info_category} c ON f.categoryid = c.id
WHERE
	f.signup = 1
ORDER BY
	c.sortorder,
	f.sortorder
EOS;
		$fields = $DB->get_records_sql($sql);
		$categoryid = -1;
		foreach ($fields as $field) {
			$element = "profile_field_" . $field->shortname;
			if ($categoryid != $field->categoryid) {
				$categoryid = $field->categoryid;
				$form->addElement("header", "header-category-" . $categoryid, $field->category);
			}
			if ($field->datatype == "menu") {
				$options = explode("\n", $field->param1);
				$select = &$form->addElement("select", $element, $field->name, array_combine($options, $options));
				$select->setSelected($USER->profile[$field->shortname]);
			} else {
				$form->addElement("text", $element, $field->name, $USER->profile[$field]);
			}
			$form->setType($element, PARAM_TEXT);
			$required = clean_param($field->required, PARAM_BOOL);
			if ($required) {
				$form->addRule($element, get_string("required"), "required");
			}
		}

		$form->closeHeaderBefore("submit");
		$form->addElement("submit", "submit", get_string("submit"));
	}

	/**
	 *
	 * @param array $data
	 * @return Ambigous boolean, array<string>
	 */
	public function validate($data) {
		$errors = array();
		if (!validate_email($data["email"])) {
			$errors["email"] = get_string("err_email", "form");
		}
		if ($data["email"] === $data["email2"]) {
			$errors["email2"] = get_string("err_email", "form");
		}
		return empty($errors) ? true : $errors;
	}
}
