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

// Frontpage Block Regions Section.
$temp = new admin_settingpage('theme_adaptable_frontpage_block_regions',
    get_string('frontpageblockregionsettings', 'theme_adaptable'));

$temp->add(new admin_setting_heading('theme_adaptable_marketing', get_string('blocklayoutbuilder', 'theme_adaptable'),
    format_text(get_string('blocklayoutbuilderdesc', 'theme_adaptable'), FORMAT_MARKDOWN)));

$name = 'theme_adaptable/frontpageblocksenabled';
$title = get_string('frontpageblocksenabled', 'theme_adaptable');
$description = get_string('frontpageblocksenableddesc', 'theme_adaptable');
$setting = new admin_setting_configcheckbox($name, $title, $description, false);
$setting->set_updatedcallback('theme_reset_all_caches');
$temp->add($setting);

// Block region builder.
$noregions = 20; // Number of block regions defined in config.php.
$totalblocks = 0;
$imgpath = $CFG->wwwroot.'/theme/adaptable/pix/layout-builder/';
$imgblder = '';
for ($i = 1; $i <= 8; $i++) {
    $name = 'theme_adaptable/blocklayoutlayoutrow' . $i;
    $title = get_string('blocklayoutlayoutrow', 'theme_adaptable');
    $description = get_string('blocklayoutlayoutrowdesc', 'theme_adaptable');
    $default = $bootstrap12defaults[$i - 1];
    $choices = $bootstrap12;
    $setting = new admin_setting_configselect($name, $title, $description, $default, $choices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $settingname = 'blocklayoutlayoutrow' . $i;

    if (!isset($PAGE->theme->settings->$settingname)) {
        $PAGE->theme->settings->$settingname = '0-0-0-0';
    }

    if ($PAGE->theme->settings->$settingname != '0-0-0-0') {
        $imgblder .= '<img src="' . $imgpath . $PAGE->theme->settings->$settingname . '.png' . '" style="padding-top: 5px">';
    }

    $vals = explode('-', $PAGE->theme->settings->$settingname);
    foreach ($vals as $val) {
        if ($val > 0) {
            $totalblocks ++;
        }
    }
}

$temp->add(new admin_setting_heading('theme_adaptable_blocklayoutcheck', get_string('layoutcheck', 'theme_adaptable'),
    format_text(get_string('layoutcheckdesc', 'theme_adaptable'), FORMAT_MARKDOWN)));

$checkcountcolor = '#00695C';
if ($totalblocks > $noregions) {
    $mktcountcolor = '#D7542A';
}
$mktcountmsg = '<span style="color: ' . $checkcountcolor . '">';
$mktcountmsg .= get_string('layoutcount1', 'theme_adaptable') . '<strong>' . $noregions . '</strong>';
$mktcountmsg .= get_string('layoutcount2', 'theme_adaptable') . '<strong>' . $totalblocks . '/' . $noregions . '</strong>.';

$temp->add(new admin_setting_heading('theme_adaptable_layoutblockscount', '', $mktcountmsg));

$temp->add(new admin_setting_heading('theme_adaptable_layoutbuilder', '', $imgblder));

$ADMIN->add('theme_adaptable', $temp);
