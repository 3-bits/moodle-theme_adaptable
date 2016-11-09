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


    $temp = new admin_settingpage('theme_adaptable_blocks', get_string('blocksettings', 'theme_adaptable'));

    $name = 'theme_adaptable/blockicons';
    $title = get_string('blockicons', 'theme_adaptable');
    $description = get_string('blockiconsdesc', 'theme_adaptable');
    $default = true;
    $setting = new admin_setting_configcheckbox($name, $title, $description, $default, true, false);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $name = 'theme_adaptable/blockbackgroundcolor';
    $title = get_string('blockbackgroundcolor', 'theme_adaptable');
    $description = get_string('blockbackgroundcolordesc', 'theme_adaptable');
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '#FFFFFF', $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $name = 'theme_adaptable/blockheaderbackgroundcolor';
    $title = get_string('blockheaderbackgroundcolor', 'theme_adaptable');
    $description = get_string('blockheaderbackgroundcolordesc', 'theme_adaptable');
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '#FFFFFF', $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $name = 'theme_adaptable/blockheadercolor';
    $title = get_string('blockheadercolor', 'theme_adaptable');
    $description = get_string('blockheadercolordesc', 'theme_adaptable');
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '#009688', $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $name = 'theme_adaptable/blockbordercolor';
    $title = get_string('blockbordercolor', 'theme_adaptable');
    $description = get_string('blockbordercolordesc', 'theme_adaptable');
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '#59585D', $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $name = 'theme_adaptable/blocklinkcolor';
    $title = get_string('blocklinkcolor', 'theme_adaptable');
    $description = get_string('blocklinkcolordesc', 'theme_adaptable');
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '#009688', $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $name = 'theme_adaptable/blocklinkhovercolor';
    $title = get_string('blocklinkhovercolor', 'theme_adaptable');
    $description = get_string('blocklinkhovercolordesc', 'theme_adaptable');
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, '#009688', $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $name = 'theme_adaptable/blockregionbackgroundcolor';
    $title = get_string('blockregionbackground', 'theme_adaptable');
    $description = get_string('blockregionbackgrounddesc', 'theme_adaptable');
    $previewconfig = null;
    $setting = new admin_setting_configcolourpicker($name, $title, $description, 'transparent', $previewconfig);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $name = 'theme_adaptable/blockheaderbordertopstyle';
    $title = get_string('blockheaderbordertopstyle', 'theme_adaptable');
    $description = get_string('blockheaderbordertopstyledesc', 'theme_adaptable');
    $radchoices = $borderstyles;
    $setting = new admin_setting_configselect($name, $title, $description, 'dashed', $radchoices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $name = 'theme_adaptable/blockheadertopradius';
    $title = get_string('blockheadertopradius', 'theme_adaptable');
    $description = get_string('blockheadertopradiusdesc', 'theme_adaptable');
    $radchoices = $from0to20px;
    $setting = new admin_setting_configselect($name, $title, $description, '0px', $radchoices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $name = 'theme_adaptable/blockheaderbottomradius';
    $title = get_string('blockheaderbottomradius', 'theme_adaptable');
    $description = get_string('blockheaderbottomradiusdesc', 'theme_adaptable');
    $radchoices = $from0to20px;
    $setting = new admin_setting_configselect($name, $title, $description, '0px', $radchoices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $name = 'theme_adaptable/blockheaderbordertop';
    $title = get_string('blockheaderbordertop', 'theme_adaptable');
    $description = get_string('blockheaderbordertopdesc', 'theme_adaptable');
    $radchoices = $from0to6px;
    $setting = new admin_setting_configselect($name, $title, $description, '1px', $radchoices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $name = 'theme_adaptable/blockheaderborderleft';
    $title = get_string('blockheaderborderleft', 'theme_adaptable');
    $description = get_string('blockheaderborderleftdesc', 'theme_adaptable');
    $radchoices = $from0to6px;
    $setting = new admin_setting_configselect($name, $title, $description, '0px', $radchoices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $name = 'theme_adaptable/blockheaderborderright';
    $title = get_string('blockheaderborderright', 'theme_adaptable');
    $description = get_string('blockheaderborderrightdesc', 'theme_adaptable');
    $radchoices = $from0to6px;
    $setting = new admin_setting_configselect($name, $title, $description, '0px', $radchoices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $name = 'theme_adaptable/blockheaderborderbottom';
    $title = get_string('blockheaderborderbottom', 'theme_adaptable');
    $description = get_string('blockheaderborderbottomdesc', 'theme_adaptable');
    $radchoices = $from0to6px;
    $setting = new admin_setting_configselect($name, $title, $description, '0px', $radchoices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $name = 'theme_adaptable/blockmainbordertopstyle';
    $title = get_string('blockmainbordertopstyle', 'theme_adaptable');
    $description = get_string('blockmainbordertopstyledesc', 'theme_adaptable');
    $radchoices = $borderstyles;
    $setting = new admin_setting_configselect($name, $title, $description, 'none', $radchoices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $name = 'theme_adaptable/blockmaintopradius';
    $title = get_string('blockmaintopradius', 'theme_adaptable');
    $description = get_string('blockmaintopradiusdesc', 'theme_adaptable');
    $radchoices = $from0to20px;
    $setting = new admin_setting_configselect($name, $title, $description, '0px', $radchoices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $name = 'theme_adaptable/blockmainbottomradius';
    $title = get_string('blockmainbottomradius', 'theme_adaptable');
    $description = get_string('blockmainbottomradiusdesc', 'theme_adaptable');
    $radchoices = $from0to20px;
    $setting = new admin_setting_configselect($name, $title, $description, '0px', $radchoices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $name = 'theme_adaptable/blockmainbordertop';
    $title = get_string('blockmainbordertop', 'theme_adaptable');
    $description = get_string('blockmainbordertopdesc', 'theme_adaptable');
    $radchoices = $from0to6px;
    $setting = new admin_setting_configselect($name, $title, $description, '0px', $radchoices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $name = 'theme_adaptable/blockmainborderleft';
    $title = get_string('blockmainborderleft', 'theme_adaptable');
    $description = get_string('blockmainborderleftdesc', 'theme_adaptable');
    $radchoices = $from0to6px;
    $setting = new admin_setting_configselect($name, $title, $description, '0px', $radchoices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $name = 'theme_adaptable/blockmainborderright';
    $title = get_string('blockmainborderright', 'theme_adaptable');
    $description = get_string('blockmainborderrightdesc', 'theme_adaptable');
    $radchoices = $from0to6px;
    $setting = new admin_setting_configselect($name, $title, $description, '0px', $radchoices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $name = 'theme_adaptable/blockmainborderbottom';
    $title = get_string('blockmainborderbottom', 'theme_adaptable');
    $description = get_string('blockmainborderbottomdesc', 'theme_adaptable');
    $radchoices = $from0to6px;
    $setting = new admin_setting_configselect($name, $title, $description, '0px', $radchoices);
    $setting->set_updatedcallback('theme_reset_all_caches');
    $temp->add($setting);

    $ADMIN->add('theme_adaptable', $temp);
