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

// Custom CSS and JS section.
$temp = new admin_settingpage('theme_adaptable_socialwall', get_string('socialwall', 'theme_adaptable'));
$temp->add(new admin_setting_heading('theme_adaptable_socialwall', get_string('socialwallheading', 'theme_adaptable'),
    format_text(get_string('socialwalldesc', 'theme_adaptable'), FORMAT_MARKDOWN)));

// Socialwall background color.
$name = 'theme_adaptable/socialwallbackgroundcolor';
$title = get_string('socialwallbackgroundcolor', 'theme_adaptable');
$description = get_string('socialwallbackgroundcolordesc', 'theme_adaptable');
$previewconfig = null;
$setting = new admin_setting_configcolourpicker($name, $title, $description, '#ffffff', $previewconfig);
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

// Social Wall section border color.
$name = 'theme_adaptable/socialwallbordercolor';
$title = get_string('socialwallbordercolor', 'theme_adaptable');
$description = get_string('socialwallbordercolordesc', 'theme_adaptable');
$previewconfig = null;
$setting = new admin_setting_configcolourpicker($name, $title, $description, '#B9B9B9', $previewconfig);
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

// Social Wall section border style.
$name = 'theme_adaptable/socialwallbordertopstyle';
$title = get_string('socialwallbordertopstyle', 'theme_adaptable');
$description = get_string('socialwallbordertopstyledesc', 'theme_adaptable');
$radchoices = $borderstyles;
$setting = new admin_setting_configselect($name, $title, $description, 'solid', $radchoices);
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

// Social Wall section border width.
$name = 'theme_adaptable/socialwallborderwidth';
$title = get_string('socialwallborderwidth', 'theme_adaptable');
$description = get_string('socialwallborderwidthdesc', 'theme_adaptable');
$radchoices = $from0to12px;
$setting = new admin_setting_configselect($name, $title, $description, '2px', $radchoices);
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

// Social Wall section border radius.
$name = 'theme_adaptable/socialwallsectionradius';
$title = get_string('socialwallsectionradius', 'theme_adaptable');
$description = get_string('socialwallsectionradiusdesc', 'theme_adaptable');
$radchoices = $from0to12px;
$setting = new admin_setting_configselect($name, $title, $description, '6px', $radchoices);
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

// Social Wall action link color.
$name = 'theme_adaptable/socialwallactionlinkcolor';
$title = get_string('socialwallactionlinkcolor', 'theme_adaptable');
$description = get_string('socialwallactionlinkcolordesc', 'theme_adaptable');
$previewconfig = null;
$setting = new admin_setting_configcolourpicker($name, $title, $description, '#009688', $previewconfig);
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

// Social Wall hover link color.
$name = 'theme_adaptable/socialwallactionlinkhovercolor';
$title = get_string('socialwallactionlinkhovercolor', 'theme_adaptable');
$description = get_string('socialwallactionlinkhovercolordesc', 'theme_adaptable');
$previewconfig = null;
$setting = new admin_setting_configcolourpicker($name, $title, $description, '#009688', $previewconfig);
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

$ADMIN->add('theme_adaptable', $temp);
