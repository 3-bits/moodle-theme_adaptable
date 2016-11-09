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
 * Version details
 *
 * @package    theme_adaptable
 * @copyright 2015 Jeremy Hopkins (Coventry University)
 * @copyright 2015 Fernando Acedo (3-bits.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */


// Header Navbar.
$temp = new admin_settingpage('theme_adaptable_navbar', get_string('navbarsettings', 'theme_adaptable'));
$temp->add(new admin_setting_heading('theme_adaptable_navbar', get_string('navbarsettingsheading', 'theme_adaptable'),
format_text(get_string('navbardesc', 'theme_adaptable'), FORMAT_MARKDOWN)));

// Sticky Navbar at the top.
/****
$name = 'theme_adaptable/stickynavbar';
$title = get_string('stickynavbar', 'theme_adaptable');
$description = get_string('stickynavbardesc', 'theme_adaptable');
$default = false;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

$name = 'theme_adaptable/navbarcachetime';
$title = get_string('navbarcachetime', 'theme_adaptable');
$description = get_string('navbarcachetimedesc', 'theme_adaptable');
$setting = new admin_setting_configselect($name, $title, $description, '0', $from0to60inc5);
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);
******/

$name = 'theme_adaptable/enablehome';
$title = get_string('home');
$description = get_string('enablehomedesc', 'theme_adaptable');
$default = true;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

$name = 'theme_adaptable/enablehomeredirect';
$title = get_string('enablehomeredirect', 'theme_adaptable');
$description = get_string('enablehomeredirectdesc', 'theme_adaptable');
$default = true;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

$name = 'theme_adaptable/enablemyhome';
$title = get_string('myhome');
$description = get_string('enablemyhomedesc', 'theme_adaptable', get_string('myhome'));
$default = true;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

$name = 'theme_adaptable/enableevents';
$title = get_string('events', 'theme_adaptable');
$description = get_string('enableeventsdesc', 'theme_adaptable');
$default = true;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

$name = 'theme_adaptable/enablemysites';
$title = get_string('mysites', 'theme_adaptable');
$description = get_string('enablemysitesdesc', 'theme_adaptable');
$choices = array(
    'excludehidden' => get_string('mysitesexclude', 'theme_adaptable'),
    'includehidden' => get_string('mysitesinclude', 'theme_adaptable'),
    'disabled' => get_string('mysitesdisabled', 'theme_adaptable'),
);
$setting->set_updatedcallback('theme_reset_all_caches');
$setting = new admin_setting_configselect($name, $title, $description, 'excludehidden', $choices);
$temp->add($setting);

$name = 'theme_adaptable/mysitesmaxlength';
$title = get_string('mysitesmaxlength', 'theme_adaptable');
$description = get_string('mysitesmaxlengthdesc', 'theme_adaptable');
$setting = new admin_setting_configselect($name, $title, $description, '30', $from20to40);
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

$name = 'theme_adaptable/mysitessortoverride';
$title = get_string('mysitessortoverride', 'theme_adaptable');
$description = get_string('mysitessortoverridedesc', 'theme_adaptable');
$choices = array(
    'off' => get_string('mysitessortoverrideoff', 'theme_adaptable'),
    'strings' => get_string('mysitessortoverridestrings', 'theme_adaptable'),
    'profilefields' => get_string('mysitessortoverrideprofilefields', 'theme_adaptable'),
    'profilefieldscohort' => get_string('mysitessortoverrideprofilefieldscohort', 'theme_adaptable')
);
$setting = new admin_setting_configselect($name, $title, $description, 'off', $choices);
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

$name = 'theme_adaptable/mysitessortoverridefield';
$title = get_string('mysitessortoverridefield', 'theme_adaptable');
$description = get_string('mysitessortoverridefielddesc', 'theme_adaptable');
$default = '';
$setting = new admin_setting_configtext($name, $title, $description, $default, PARAM_RAW);
$temp->add($setting);

$name = 'theme_adaptable/enablethiscourse';
$title = get_string('thiscourse', 'theme_adaptable');
$description = get_string('enablethiscoursedesc', 'theme_adaptable');
$default = true;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

$name = 'theme_adaptable/enablezoom';
$title = get_string('enablezoom', 'theme_adaptable');
$description = get_string('enablezoomdesc', 'theme_adaptable');
$default = true;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

$name = 'theme_adaptable/enableshowhideblocks';
$title = get_string('enableshowhideblocks', 'theme_adaptable');
$description = get_string('enableshowhideblocksdesc', 'theme_adaptable');
$default = true;
$setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

// Enable help link.
$name = 'theme_adaptable/enablehelp';
$title = get_string('enablehelp', 'theme_adaptable');
$description = get_string('enablehelpdesc', 'theme_adaptable');
$setting = new admin_setting_configtext($name, $title, $description, '', PARAM_URL);
$temp->add($setting);

$name = 'theme_adaptable/helpprofilefield';
$title = get_string('helpprofilefield', 'theme_adaptable');
$description = get_string('helpprofilefielddesc', 'theme_adaptable');
$setting = new admin_setting_configtext($name, $title, $description, '', PARAM_RAW);
$temp->add($setting);

$name = 'theme_adaptable/enablehelp2';
$title = get_string('enablehelp', 'theme_adaptable');
$description = get_string('enablehelpdesc', 'theme_adaptable');
$setting = new admin_setting_configtext($name, $title, $description, '', PARAM_URL);
$temp->add($setting);

$name = 'theme_adaptable/helpprofilefield2';
$title = get_string('helpprofilefield', 'theme_adaptable');
$description = get_string('helpprofilefielddesc', 'theme_adaptable');
$setting = new admin_setting_configtext($name, $title, $description, '', PARAM_RAW);
$temp->add($setting);

$name = 'theme_adaptable/helptarget';
$title = get_string('helptarget', 'theme_adaptable');
$description = get_string('helptargetdesc', 'theme_adaptable');
$choices = array(
    '_blank' => get_string('targetnewwindow', 'theme_adaptable'),
    '_self' => get_string('targetsamewindow', 'theme_adaptable'),
);
$setting = new admin_setting_configselect($name, $title, $description, '_blank', $choices);

$temp->add($setting);

$ADMIN->add('theme_adaptable', $temp);
