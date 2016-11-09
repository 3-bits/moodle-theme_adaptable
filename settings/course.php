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

    $temp = new admin_settingpage('theme_adaptable_course', get_string('coursesettings', 'theme_adaptable'));
    $temp->add(new admin_setting_heading('theme_adaptable_course', get_string('coursesettingsheading', 'theme_adaptable'),
        format_text(get_string('coursedesc', 'theme_adaptable'), FORMAT_MARKDOWN)));

    // Course section header background color.
    $name = 'theme_adaptable/coursesectionheaderbg';
    $title = get_string('coursesectionheaderbg', 'theme_adaptable');
    $description = get_string('coursesectionheaderbgdesc', 'theme_adaptable');
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '#F3F3F3', $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    // Course section heading text color.
    $name = 'theme_adaptable/sectionheadingcolor';
    $title = get_string('sectionheadingcolor', 'theme_adaptable');
    $description = get_string('sectionheadingcolordesc', 'theme_adaptable');
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '#5f588a', $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    // Current course section header background color.
    $name = 'theme_adaptable/currentcolor';
    $title = get_string('currentcolor', 'theme_adaptable');
    $description = get_string('currentcolordesc', 'theme_adaptable');
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '#d9edf7', $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    // Course section header border bottom style.
    $name = 'theme_adaptable/coursesectionheaderborderstyle';
    $title = get_string('coursesectionheaderborderstyle', 'theme_adaptable');
    $description = get_string('coursesectionheaderborderstyledesc', 'theme_adaptable');
    $radchoices = $borderstyles;
    $setting = new admin_setting_configselect($name, $title, $description, 'none', $radchoices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    // Course section header border bottom color.
    $name = 'theme_adaptable/coursesectionheaderbordercolor';
    $title = get_string('coursesectionheaderbordercolor', 'theme_adaptable');
    $description = get_string('coursesectionheaderbordercolordesc', 'theme_adaptable');
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '#F3F3F3', $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    // Course section header border bottom width.
    $name = 'theme_adaptable/coursesectionheaderborderwidth';
    $title = get_string('coursesectionheaderborderwidth', 'theme_adaptable');
    $description = get_string('coursesectionheaderborderwidthdesc', 'theme_adaptable');
    $radchoices = $from0to6px;;
    $setting = new admin_setting_configselect($name, $title, $description, '0px', $radchoices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    // Course section border radius.
    $name = 'theme_adaptable/coursesectionheaderborderradiustop';
    $title = get_string('coursesectionheaderborderradiustop', 'theme_adaptable');
    $description = get_string('coursesectionheaderborderradiustopdesc', 'theme_adaptable');
    $radchoices = $from0to50px;
    $setting = new admin_setting_configselect($name, $title, $description, '0px', $radchoices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    // Course section border radius.
    $name = 'theme_adaptable/coursesectionheaderborderradiusbottom';
    $title = get_string('coursesectionheaderborderradiusbottom', 'theme_adaptable');
    $description = get_string('coursesectionheaderborderradiusbottomdesc', 'theme_adaptable');
    $radchoices = $from0to50px;
    $setting = new admin_setting_configselect($name, $title, $description, '0px', $radchoices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    // Course section border style.
    $name = 'theme_adaptable/coursesectionborderstyle';
    $title = get_string('coursesectionborderstyle', 'theme_adaptable');
    $description = get_string('coursesectionborderstyledesc', 'theme_adaptable');
    $radchoices = $borderstyles;
    $setting = new admin_setting_configselect($name, $title, $description, 'solid', $radchoices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    // Course section border width.
    $name = 'theme_adaptable/coursesectionborderwidth';
    $title = get_string('coursesectionborderwidth', 'theme_adaptable');
    $description = get_string('coursesectionborderwidthdesc', 'theme_adaptable');
    $radchoices = $from0to6px;;
    $setting = new admin_setting_configselect($name, $title, $description, '1px', $radchoices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    // Course section border color.
    $name = 'theme_adaptable/coursesectionbordercolor';
    $title = get_string('coursesectionbordercolor', 'theme_adaptable');
    $description = get_string('coursesectionbordercolordesc', 'theme_adaptable');
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '#e8eaeb', $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    // Course section border radius.
    $name = 'theme_adaptable/coursesectionborderradius';
    $title = get_string('coursesectionborderradius', 'theme_adaptable');
    $description = get_string('coursesectionborderradiusdesc', 'theme_adaptable');
    $radchoices = $from0to50px;
    $setting = new admin_setting_configselect($name, $title, $description, '0px', $radchoices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $ADMIN->add('theme_adaptable', $temp);