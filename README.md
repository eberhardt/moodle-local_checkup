moodle-local_checkup
====================

Force every self-registered (authorisation method `email`) user to revalidate their data on login.

Usage
=====

To activate go to
`Site administration` -> `Plugins` -> `Local plugins` -> `Start forced checkup`

You'll have to confirm the activation by using the button on this page.

After Login the user might leave the notification and use Moodle without updating their profile. If you want to force - in the strong sense - a user to update his profile, then extend the function `require_login()` of your `lib/moodlelib.php` by following code:

`if (!$preventredirect
    and file_exists($CFG->dirroot . "/local/checkup/lib.php")
    and file_exists($CFG->dirroot . "/local/checkup/index.php")) {
    require_once($CFG->dirroot . "/local/checkup/lib.php");
    local_checkup_manual_redirect($setwantsurltome);
}`

You should place that snippet between the calls of `user_not_fully_set_up()` (which is in an if-clause) and  `sesskey()`. Since the lines may differ (depending on your Moodle version) I can't specify them here.
