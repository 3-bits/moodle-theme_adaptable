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
 * @copyright  2015 Jeremy Hopkins (Coventry University)
 * @copyright  2015 Fernando Acedo (3-bits.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 */


// Load libraries.
require_once($CFG->dirroot.'/blocks/course_overview/locallib.php');
require_once($CFG->dirroot .'/course/renderer.php');
require_once($CFG->libdir. '/coursecatlib.php');

/**
 * @copyright 2015 Jeremy Hopkins (Coventry University)
 * @copyright 2015 Fernando Acedo (3-bits.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * Core renderers for Adaptable theme based on BCU Theme
 */
class theme_adaptable_core_renderer extends core_renderer {
    /** @var custom_menu_item language The language menu if created */
    protected $language = null;

    /**
     * Internal implementation of user image rendering.
     *
     * @param user_picture $userpicture
     * @return string
     */
    protected function render_user_picture(\user_picture $userpicture) {
        if ($this->page->pagetype == 'mod-forum-discuss' ||
        $this->page->pagetype == 'course-view-socialwall' ||
        $this->page->pagetype == 'site-index') {
            $userpicture->size = 1;
        }
        return parent::render_user_picture($userpicture);
    }

    /**
     * Returns the URL for the favicon.
     *
     * @return string The favicon URL
     */
    public function favicon() {
        if (!empty($this->page->theme->settings->favicon)) {
            return $this->page->theme->setting_file_url('favicon', 'favicon');
        }
        return parent::favicon();
    }

    /**
     * Returns settings as formatted text
     *
     * @param string $setting
     * @param string $format = false
     * @param string $theme = null
     * @return string
     */
    public function get_setting($setting, $format = false, $theme = null) {
        if (empty($theme)) {
            $theme = theme_config::load('adaptable');
        }

        if (empty($theme->settings->$setting)) {
            return false;
        } else if (!$format) {
            return $theme->settings->$setting;
        } else if ($format === 'format_text') {
            return format_text($theme->settings->$setting, FORMAT_PLAIN);
        } else if ($format === 'format_html') {
            return format_text($theme->settings->$setting, FORMAT_HTML, array('trusted' => true));
        } else {
            return format_string($theme->settings->$setting);
        }
    }

    /**
     * Returns the user menu
     *
     * @param string $user = null
     * @param string $withlinks = null
     * @return the user menu
     */
    public function user_menu($user = null, $withlinks = null) {
        global $CFG;
        $usermenu = new custom_menu('', current_language());
        return $this->render_user_menu($usermenu);
    }

    /**
     * Returns list of alert messages for the user
     *
     * @return string
     */
    public function get_alert_messages() {
        global $PAGE;
        $alerts = '';

        $alertcount = $PAGE->theme->settings->alertcount;

        for ($i = 1; $i <= $alertcount; $i++) {
            $enablealert = 'enablealert' . $i;
            $alerttext = 'alerttext' . $i;
            $alertsession = 'alert' . $i;

            if (empty($PAGE->theme->settings->enablealerts)) {
                return '';
            }

            if (isset($PAGE->theme->settings->$enablealert)) {
                $enablealert = $PAGE->theme->settings->$enablealert;
            } else {
                $enablealert = false;
            }

            if (isset($PAGE->theme->settings->$alerttext)) {
                $alerttext = $PAGE->theme->settings->$alerttext;
            } else {
                $alerttext = '';
            }

            if ($enablealert && !empty($alerttext)) {
                $alertprofilefield = 'alertprofilefield' . $i;
                $profilevals = array('', '');

                if (!empty($PAGE->theme->settings->$alertprofilefield)) {
                    $profilevals = explode('=', $PAGE->theme->settings->$alertprofilefield);
                }

                if (!empty($PAGE->theme->settings->enablealertstriptags)) {
                    $alerttext = strip_tags($alerttext);
                }

                $alerttype = 'alerttype' . $i;
                $alertaccess = 'alertaccess' . $i;
                $alertkey = 'alertkey' . $i;

                $alerttype = $PAGE->theme->settings->$alerttype;
                $alertaccess = $PAGE->theme->settings->$alertaccess;
                $alertkey = $PAGE->theme->settings->$alertkey;

                if ($this->get_alert_access($alertaccess, $profilevals[0], $profilevals[1], $alertsession)) {
                    $alerts .= $this->get_alert_message($alerttext, $alerttype, $i, $alertkey);
                }
            }
        }

        if (core\session\manager::is_loggedinas()) {
            $alertindex = $alertcount + 1;
            $alertkey = "undismissable";
            $logininfo = $this->login_info();
            $logininfo = str_replace('<div class="logininfo">', '', $logininfo);
            $logininfo = str_replace('</div>', '', $logininfo);
            $alerts = $this->get_alert_message($logininfo, 'warning', $alertindex, 'logedinas') . $alerts;
        }

        return $alerts;
    }

    /**
     * Returns formatted alert message
     *
     * @param string $text message text
     * @param string $type alert type
     * @param int $alertindex
     * @param int $alertkey
     */
    public function get_alert_message($text, $type, $alertindex, $alertkey) {
        if ($alertkey == '' || theme_adaptable_get_alertkey($alertindex) == $alertkey) {
            return '';
        }

        global $PAGE;

        $retval = '<div class="customalert alert alert-' . $type . ' fade in" role="alert">';
        $retval .= '<a href="#" class="close" data-dismiss="alert" data-alertkey="' . $alertkey .
        '" data-alertindex="' . $alertindex . '" aria-label="close">&times;</a>';
        $retval .= '<i class="fa fa-' . $this->alert_icon($type) . ' fa-lg"></i>&nbsp;';
        $retval .= $text;
        $retval .= '</div>';
        return $retval;
    }

    /**
     * Checks the users access to alerts
     * @param string $access the kind of access rule applied
     * @param string $profilefield the custom profile filed to check
     * @param string $profilevalue the expected value to be found in users profile
     * @param string $alertsession a token to be used to store access in session
     * @return boolean
     */
    public function get_alert_access($access, $profilefield, $profilevalue, $alertsession) {
        $retval = false;
        switch ($access) {
            case "global":
                $retval = true;
            break;
            case "user":
                if (isloggedin()) {
                    $retval = true;
                }
            break;
            case "admin":
                if (is_siteadmin()) {
                    $retval = true;
                }
            break;
            case "profile":
                if ($this->check_menu_access($profilefield, $profilevalue, $alertsession)) {
                    $retval = true;
                }
            break;
        }
        return $retval;
    }

    /**
     * Returns FA icon depending on the type of alert selected
     *
     * @param string $alertclassglobal     *
     * @return string
     */
    public function alert_icon($alertclassglobal) {
        switch ($alertclassglobal) {
            case "success":
                $alerticonglobal = "bullhorn";
                break;
            case "info":
                $alerticonglobal = "info-circle";
                break;
            case "warning":
                $alerticonglobal = "exclamation-triangle";
                break;
        }
        return $alerticonglobal;
    }

    /**
     * Returns Google Analytics code if analytics are enabled
     *
     * @return string
     */
    public function get_analytics() {
        global $PAGE;
        $analytics = '';
        $analyticscount = $PAGE->theme->settings->enableanalytics;

        if (isset($PAGE->theme->settings->enableanalytics)) {
            for ($i = 1; $i <= $analyticscount; $i++) {
                $analyticstext = 'analyticstext' . $i;
                $analyticsprofilefield = 'analyticsprofilefield' . $i;
                $analyticssession = 'analytics' . $i;
                $access = true;

                if (!empty($PAGE->theme->settings->$analyticsprofilefield)) {
                    $profilevals = explode('=', $PAGE->theme->settings->$analyticsprofilefield);
                    $profilefield = $profilevals[0];
                    $profilevalue = $profilevals[1];
                    if (!$this->check_menu_access($profilefield, $profilevalue, $analyticssession)) {
                        $access = false;
                    }
                }

                if (!empty($PAGE->theme->settings->$analyticstext) && $access) {
                    $analyticstext = $PAGE->theme->settings->$analyticstext;

                    // The closing tag of PHP heredoc doesn't like being indented so do not meddle with indentation of 'EOT;' below!
                    $analytics .= <<<EOT

                    <script type="text/javascript">
                        (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                        (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                        m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
                        })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

                        ga('create', '$analyticstext', 'auto');
                        ga('send', 'pageview');

                    </script>
EOT;
                }
            }
        }
        return $analytics;
    }

    /**
     * Returns Piwik code if enabled
     *
     * @copyright  2016 COMETE-UPO (Université Paris Ouest)
     *
     * @return string
     */
    public function get_piwik() {
        global $CFG, $DB, $PAGE, $COURSE, $SITE;

        $enabled = $PAGE->theme->settings->piwikenabled;
        $imagetrack = $PAGE->theme->settings->piwikimagetrack;
        $siteurl = $PAGE->theme->settings->piwiksiteurl;
        $siteid = $PAGE->theme->settings->piwiksiteid;
        $trackadmin = $PAGE->theme->settings->piwiktrackadmin;

        $enabled = $PAGE->theme->settings->piwikenabled;
        $imagetrack = $PAGE->theme->settings->piwikimagetrack;
        $siteurl = $PAGE->theme->settings->piwiksiteurl;
        $siteid = $PAGE->theme->settings->piwiksiteid;
        $trackadmin = $PAGE->theme->settings->piwiktrackadmin;

        $analytics = '';
        if ($enabled && !empty($siteurl) && !empty($siteid) && (!is_siteadmin() || $trackadmin)) {
            if ($imagetrack) {
                $addition = '<noscript><p>
                            <img src="//'.$siteurl.'/piwik.php?idsite='.$siteid.' style="border:0;" alt="" /></p></noscript>';
            } else {
                $addition = '';
            }
            // Cleanurl.
            $pageinfo = get_context_info_array($PAGE->context->id);
            $trackurl = '';
            // Adds course category name.
            if (isset($pageinfo[1]->category)) {
                if ($category = $DB->get_record('course_categories', array('id' => $pageinfo[1]->category))) {
                    $cats = explode("/", $category->path);
                    foreach (array_filter($cats) as $cat) {
                        if ($categorydepth = $DB->get_record("course_categories", array("id" => $cat))) {
                            $trackurl .= $categorydepth->name.'/';
                        }
                    }
                }
            }
            // Adds course full name.
            if (isset($pageinfo[1]->fullname)) {
                if (isset($pageinfo[2]->name)) {
                    $trackurl .= $pageinfo[1]->fullname.'/';
                } else if ($PAGE->user_is_editing()) {
                    $trackurl .= $pageinfo[1]->fullname.'/'.get_string('edit', 'local_analytics');
                } else {
                    $trackurl .= $pageinfo[1]->fullname.'/'.get_string('view', 'local_analytics');
                }
            }
            // Adds activity name.
            if (isset($pageinfo[2]->name)) {
                $trackurl .= $pageinfo[2]->modname.'/'.$pageinfo[2]->name;
            }
            $trackurl = '"'.str_replace('"', '\"', $trackurl).'"';
            // Here we go.
            $analytics .= '<!-- Start Piwik Code -->'."\n".
                '<script type="text/javascript">'."\n".
                '    var _paq = _paq || [];'."\n".
                '    _paq.push(["setDocumentTitle", '.$trackurl.']);'."\n".
                '    _paq.push(["trackPageView"]);'."\n".
                '    _paq.push(["enableLinkTracking"]);'."\n".
                '    (function() {'."\n".
                '      var u="//'.$siteurl.'/";'."\n".
                '      _paq.push(["setTrackerUrl", u+"piwik.php"]);'."\n".
                '      _paq.push(["setSiteId", '.$siteid.']);'."\n".
                '      var d=document, g=d.createElement("script"), s=d.getElementsByTagName("script")[0];'."\n".
                '    g.type="text/javascript"; g.async=true; g.defer=true; g.src=u+"piwik.js"; s.parentNode.insertBefore(g,s);'."\n".
                '    })();'."\n".
                '</script>'.$addition."\n".
                '<!-- End Piwik Code -->'."\n".
            '';
        }
        return $analytics;
    }

    /**
     * Returns all tracking methods (Analytics and Piwik)
     *
     * @return string
     */
    public function get_all_tracking_methods() {
        $analytics = '';
        $analytics .= $this->get_analytics();
        $analytics .= $this->get_piwik();
        return $analytics;
    }

    /**
     * Returns HTML to display a "Turn editing on/off" button in a form.
     *
     * @param moodle_url $url The URL + params to send through when clicking the button
     * @return string HTML the button
     * Written by G J Barnard
     */
    public function edit_button(moodle_url $url) {
        $url->param('sesskey', sesskey());
        if ($this->page->user_is_editing()) {
            $url->param('edit', 'off');
            $btn = 'btn-danger';
            $title = get_string('turneditingoff');
            $icon = 'fa-power-off';
        } else {
            $url->param('edit', 'on');
            $btn = 'btn-success';
            $title = get_string('turneditingon');
            $icon = 'fa-edit';
        }
        return html_writer::tag('a', html_writer::start_tag('i', array('class' => $icon . ' fa fa-fw')) .
            html_writer::end_tag('i') . $title, array('href' => $url, 'class' => 'btn ' . $btn, 'title' => $title));
    }

    /**
     * Returns the upper user menu
     *
     * @param custom_menu $menu
     * @return string
     */
    protected function render_user_menu(custom_menu $menu) {
        global $PAGE, $CFG, $USER, $DB, $OUTPUT;
        $addlangmenu = true;
        $addmessagemenu = true;

        if (!isloggedin() || isguestuser()) {
            $addmessagemenu = false;
        }
        if (!$CFG->messaging || !$PAGE->theme->settings->enablemessagemenu) {
            $addmessagemenu = false;
        } else {
            // Check whether or not the "popup" message output is enabled
            // This is after we check if messaging is enabled to possibly save a DB query.
            $popup = $DB->get_record('message_processors', array('name' => 'popup'));
            if (!$popup) {
                $addmessagemenu = false;
            }
        }

        if ($addmessagemenu) {
            $messages = $this->get_user_messages();
            $messagecount = count($messages);
            // Edit by Matthew Anguige, only display unread popover when unread messages are waiting.
            if ($messagecount > 0) {
                $messagemenu = $menu->add('<i class="fa fa-envelope"> </i>' . get_string('messages', 'message') .' '.
                '<span class="badge">' . $messagecount . '</span>', new moodle_url('#'), get_string('messages', 'message'), 9999);
            } else {
                $messagemenu = $menu->add('<i class="fa fa-envelope"> </i>' . get_string('messages', 'message'),
                                            new moodle_url('/message/index.php'), get_string('messages', 'message'), 9999);
            }

            foreach ($messages as $message) {
                if (!isset($message->from) || !isset($message->from->id) || !isset($message->from->firstname)) {
                    continue;
                }
                // Following if to be removed once we are happy with check above correctly limits messages.
                if (!isset($message->from)) {
                    $url = $OUTPUT->pix_url('u/f2');
                    $attributes = array(
                        'src' => $url
                    );
                    $senderpicture = html_writer::empty_tag('img', $attributes);
                } else {
                    $senderpicture = new user_picture($message->from);
                    $senderpicture->link = false;
                    $senderpicture = $this->render($senderpicture);
                }

                $messagecontent = $senderpicture;
                $messagecontent .= html_writer::start_tag('span', array('class' => 'msg-body'));
                $messagecontent .= html_writer::start_tag('span', array('class' => 'msg-title'));
                $messagecontent .= html_writer::tag('span', $message->from->firstname . ': ', array('class' => 'msg-sender'));
                $messagecontent .= $message->text;
                $messagecontent .= html_writer::end_tag('span');
                $messagecontent .= html_writer::start_tag('span', array('class' => 'msg-time'));
                $messagecontent .= html_writer::tag('i', '', array('class' => 'icon-time'));
                $messagecontent .= html_writer::tag('span', $message->date);
                $messagecontent .= html_writer::end_tag('span');

                $messagemenu->add($messagecontent, new moodle_url('/message/index.php', array('user1' => $USER->id,
                        'user2' => $message->from->id)));
            }
        }

        $langs = get_string_manager()->get_list_of_translations();
        if (count($langs) < 2 || empty($CFG->langmenu) || ($this->page->course != SITEID and !empty($this->page->course->lang))) {
            $addlangmenu = false;
        }

        $content = html_writer::start_tag('ul', array('class' => 'usermenu2 nav navbar-nav navbar-right'));
        foreach ($menu->get_children() as $item) {
            $content .= $this->render_custom_menu_item($item, 1);
        }

        return $content.html_writer::end_tag('ul');
    }

    /**
     * Returns formats messages in the header with user profile images
     *
     * @return array
     */
    protected function process_user_messages() {
        $messagelist = array();
        foreach ($usermessages as $message) {
            $cleanmsg = new stdClass();
            $cleanmsg->from = fullname($message);
            $cleanmsg->msguserid = $message->id;

            $userpicture = new user_picture($message);
            $userpicture->link = false;
            $picture = $this->render($userpicture);

            $cleanmsg->text = $picture . ' ' . $cleanmsg->text;

            $messagelist[] = $cleanmsg;
        }

        return $messagelist;
    }

    /**
     * Get list of user messages if there are any to process
     *
     * @return array
     */
    protected function get_user_messages() {
        global $USER, $DB;
        $messagelist = array();

        $newmessagesql = "SELECT id, smallmessage, useridfrom, useridto, timecreated, fullmessageformat, notification
                           FROM {message}
                           WHERE useridto = :userid
                           AND useridfrom > 2
                           AND notification <> 1";

        $newmessages = $DB->get_records_sql($newmessagesql, array('userid' => $USER->id));

        foreach ($newmessages as $message) {
            $messagelist[] = $this->process_message($message);
        }

        $showoldmessages = (empty($this->page->theme->settings->showoldmessages)) ? 0 : $this->page->theme->settings->showoldmessages;
        if ($showoldmessages) {
            $maxmessages = 5;
            $readmessagesql = "SELECT id, smallmessage, useridfrom, useridto, timecreated, fullmessageformat, notification
                               FROM {message_read}
                               WHERE useridto = :userid
                               AND useridfrom > 2
                               AND notification <> 1
                               ORDER BY timecreated DESC
                               LIMIT $maxmessages";

            $readmessages = $DB->get_records_sql($readmessagesql, array('userid' => $USER->id));

            foreach ($readmessages as $message) {
                $messagelist[] = $this->process_message($message);
            }
        }

        return $messagelist;
    }

    /**
     * Process user messages
     *
     * @param array $message
     * @return array
     */
    protected function process_message($message) {
        global $DB, $USER;
        $messagecontent = new stdClass();
        if ($message->notification || $message->useridfrom < 1) {
            $messagecontent->text = $message->smallmessage;
            $messagecontent->type = 'notification';

            if (empty($message->contexturl)) {
                $messagecontent->url = new moodle_url('/message/index.php',
                                        array('user1' => $USER->id, 'viewing' => 'recentnotifications'));
            } else {
                $messagecontent->url = new moodle_url($message->contexturl);
            }

        } else {
            $messagecontent->type = 'message';
            if ($message->fullmessageformat == FORMAT_HTML) {
                $message->smallmessage = html_to_text($message->smallmessage);
            }
            if (strlen($message->smallmessage) > 18) {
                $messagecontent->text = substr($message->smallmessage, 0, 15) . '...';
            } else {
                $messagecontent->text = $message->smallmessage;
            }
            $messagecontent->from = $DB->get_record('user', array('id' => $message->useridfrom));
            $messagecontent->url = new moodle_url('/message/index.php',
                                    array('user1' => $USER->id, 'user2' => $message->useridfrom));
        }
        $messagecontent->date = userdate($message->timecreated, get_string('strftimetime', 'langconfig'));
        $messagecontent->unread = empty($message->timeread);
        return $messagecontent;
    }

    /**
     * This renders a notification message.
     * Uses bootstrap compatible html.
     *
     * @param string $message
     * @param string $classes for css
     */
    public function notification($message, $classes = 'notifyproblem') {
        $message = clean_text($message);
        $type = '';

        if ($classes == 'notifyproblem') {
            $type = 'alert alert-error';
        }
        if ($classes == 'notifysuccess') {
            $type = 'alert alert-success';
        }
        if ($classes == 'notifymessage') {
            $type = 'alert alert-info';
        }
        if ($classes == 'redirectmessage') {
            $type = 'alert alert-block alert-info';
        }
        return '<div class="' . $type . '">' . $message . '</div>';
    }

    /**
     * Returns html to render socialicons
     *
     * @return string
     */
    public function socialicons() {
        global $CFG, $PAGE;

        if (!isset($PAGE->theme->settings->socialiconlist)) {
            return '';
        }

        $target = '_blank';
        if (isset($PAGE->theme->settings->socialtarget)) {
            $target = $PAGE->theme->settings->socialtarget;
        }

        $retval = '<div class="socialbox pull-right">';
        $socialiconlist = $PAGE->theme->settings->socialiconlist;
        $lines = explode("\n", $socialiconlist);

        foreach ($lines as $line) {
            if (strstr($line, '|')) {
                $fields = explode('|', $line);

                $val = '<a alt="' . $fields[1];
                $val .= '" target="' . $target;
                $val .= '" title="' . $fields[1];
                $val .= '" href="' . $fields[0] . '">';
                $val .= '<i class="fa ' . $fields[2] . '"></i>';
                $val .= '</a>';

                $retval .= $val;
            }
        }

        $retval .= '</div>';
        return $retval;
    }

    /**
     * Returns html to render news ticker
     *
     * @return string
     */
    public function get_news_ticker() {
        global $PAGE, $OUTPUT;
        $retval = '';

        if (!isset($PAGE->theme->settings->enabletickermy)) {
            $PAGE->theme->settings->enabletickermy = 0;
        }

        if ((!empty($PAGE->theme->settings->enableticker) &&
        $PAGE->theme->settings->enableticker &&
        $PAGE->bodyid == "page-site-index") ||
        ($PAGE->theme->settings->enabletickermy && $PAGE->bodyid == "page-my-index")) {
            $msg = '';
            $tickercount = $PAGE->theme->settings->newstickercount;

            for ($i = 1; $i <= $tickercount; $i++) {
                $textfield = 'tickertext' . $i;
                $profilefield = 'tickertext' . $i . 'profilefield';

// Add.
                format_text($textfield, FORMAT_HTML);
//            return format_text($theme->settings->$setting, FORMAT_HTML, array('trusted' => true));


                $access = true;

                if (!empty($PAGE->theme->settings->$profilefield)) {
                    $profilevals = explode('=', $PAGE->theme->settings->$profilefield);
                    if (!$this->check_menu_access($profilevals[0], $profilevals[1], $textfield)) {
                        $access = false;
                    }
                }

                if ($access) {
                    $msg .= $PAGE->theme->settings->$textfield;
                }
            }

            $msg = preg_replace('#\<[\/]{0,1}(p|ul|div|pre|blockquote)\>#', '', $msg);
            if ($msg == '') {
                $msg = '<li>' . get_string('tickerdefault', 'theme_adaptable') . '</li>';
            }

            $retval .= '<div id="ticker-wrap" class="clearfix container">';
            $retval .= '<div class="pull-left" id="ticker-announce">';
            $retval .= get_string('ticker', 'theme_adaptable');
            $retval .= '</div>';
            $retval .= '<ul id="ticker">';
            $retval .= $msg;
            $retval .= '</ul>';
            $retval .= '</div>';

        }

     return $retval;
    }


     /**
     * Renders block regions on front page
     *
     * @param string $settingsname
     */
    public function get_block_regions($settingsname = 'blocklayoutlayoutrow') {
        global $PAGE, $OUTPUT, $USER;
        $fields = array();
        $retval = '';
        $blockcount = 0;
        $style = '';
        $adminediting = false;

        if (is_siteadmin() && isset($USER->editing) && $USER->editing == 1) {
            $style = '" style="display: block; background: #EEEEEE; min-height: 50px; border: 2px dashed #BFBDBD; margin-top: 5px';
            $adminediting = true;
        }

        for ($i = 1; $i <= 8; $i++) {
            $marketrow = $settingsname . $i;
            $marketrow = $PAGE->theme->settings->$marketrow;
            if ($marketrow != '0-0-0-0') {
                $fields[] = $marketrow;
            }
        }

        foreach ($fields as $field) {
            $retval .= '<div class="row" style="margin-left: 0px;">';
            $vals = explode('-', $field);
            foreach ($vals as $val) {
                if ($val > 0) {
                    $retval .= '<div class="span' . $val . $style . '">';

                    // Moodle does not seem to like numbers in region names so using letter instead.
                    $blockcount ++;
                    $block = 'frnt-market-' .  chr(96 + $blockcount);

                    if ($adminediting) {
                        $retval .= '<span style="padding-left: 10px;"> ' . '' . '</span>';
                    }

                    $retval .= $OUTPUT->blocks($block, 'block-region-front');
                    $retval .= '</div>';
                }
            }
            $retval .= '</div>';
        }
        return $retval;
    }

    /**
     * Renders marketing blocks on front page
     *
     * @param string $layoutrow
     * @param string $settingname
     */
    public function get_marketing_blocks($layoutrow = 'marketlayoutrow', $settingname = 'market') {
        global $PAGE, $OUTPUT;
        $fields = array();
        $blockcount = 0;
        $style = '';

        $extramarketclass = $PAGE->theme->settings->frontpagemarketoption;

        $retval = '<div id="marketblocks" class="container '. $extramarketclass .'">';

        for ($i = 1; $i <= 5; $i++) {
            $marketrow = $layoutrow . $i;
            $marketrow = $PAGE->theme->settings->$marketrow;
            if ($marketrow != '0-0-0-0') {
                $fields[] = $marketrow;
            }
        }

        foreach ($fields as $field) {
            $retval .= '<div class="row-fluid marketrow">';
            $vals = explode('-', $field);
            foreach ($vals as $val) {
                if ($val > 0) {
                    $retval .= '<div class="span' . $val . ' ' . $extramarketclass . ' first">';
                    $blockcount ++;
                    $fieldname = $settingname . $blockcount;
                    if (isset($PAGE->theme->settings->$fieldname)) {
                         $retval .= $OUTPUT->get_setting($fieldname, 'format_html');
                    }
                    $retval .= '</div>';
                }
            }
            $retval .= '</div>';
        }
        $retval .= '</div>';
        if ($blockcount == 0 ) {
            $retval = '';
        }
        return $retval;
    }

    /**
     * Returns footer visibility setting
     *
     */
    public function get_footer_visibility() {
        global $PAGE, $COURSE;
        $value = $PAGE->theme->settings->footerblocksplacement;

        if ($value == 1) {
            return true;
        }

        if ($value == 2 && $COURSE->id != 1) {
            return false;
        }

        if ($value == 3) {
            return false;
        }
        return true;
    }

    /**
     * Renders footer blocks
     *
     * @param string $layoutrow
     */
    public function get_footer_blocks($layoutrow = 'footerlayoutrow') {
        global $PAGE, $OUTPUT;
        $fields = array();
        $blockcount = 0;
        $style = '';

        if (!$this->get_footer_visibility()) {
            return '';
        }

        $output = '<div id="course-footer">' . $OUTPUT->course_footer() . '</div>
                <div class="container blockplace1">';

        for ($i = 1; $i <= 3; $i++) {
            $footerrow = $layoutrow . $i;
            $footerrow = $PAGE->theme->settings->$footerrow;
            if ($footerrow != '0-0-0-0') {
                $fields[] = $footerrow;
            }
        }

        foreach ($fields as $field) {
            $output .= '<div class="row-fluid">';
            $vals = explode('-', $field);
            foreach ($vals as $val) {
                if ($val > 0) {
                    $blockcount ++;
                    $footerheader = 'footer' . $blockcount . 'header';
                    $footercontent = 'footer' . $blockcount . 'content';
                    if (!empty($PAGE->theme->settings->$footercontent)) {
                        $output .= '<div class="left-col span' . $val . '">';
                        $output .= '<h3 title="' . $OUTPUT->get_setting($footerheader, 'format_text') . '">';
                        $output .= $OUTPUT->get_setting($footerheader, 'format_text');
                        $output .= '</h3>';
                        $output .= $OUTPUT->get_setting($footercontent, 'format_html');
                        $output .= '</div>';
                    }
                }
            }
            $output .= '</div>';
        }
        $output .= '</div>';
        return $output;
    }

    /**
     * Renders frontpage slider
     *
     */
    public function get_frontpage_slider() {
        global $PAGE, $OUTPUT;
        $noslides = $PAGE->theme->settings->slidercount;
        $retval = '';

        if (!empty($PAGE->theme->settings->sliderfullscreen)) {
            $retval .= '<div class="slidewrap';
        } else {
            $retval .= '<div class="container slidewrap';
        }

        if ($PAGE->theme->settings->slideroption2 == 'slider2') {
            $retval .= " slidestyle2";
        }

        $retval .= '">
            <div id="main-slider" class="flexslider">
            <ul class="slides">';

        for ($i = 1; $i <= $noslides; $i++) {
            $sliderimage = 'p' . $i;
            $sliderurl = 'p' . $i . 'url';

            if (!empty($PAGE->theme->settings->$sliderimage)) {
                $slidercaption = 'p' . $i .'cap';
            }

            $closelink = '';
            if (!empty($PAGE->theme->settings->$sliderimage)) {
                $retval .= '<li>';

                if (!empty($PAGE->theme->settings->$sliderurl)) {
                    $retval .= '<a href="' . $PAGE->theme->settings->$sliderurl . '">';
                    $closelink = '</a>';
                }

                $retval .= '<img src="' . $PAGE->theme->setting_file_url($sliderimage, $sliderimage)
                    . '" alt="' . $sliderimage . '"/>';

                if (!empty($PAGE->theme->settings->$slidercaption)) {
                    $retval .= '<div class="flex-caption">';
                    $retval .= $OUTPUT->get_setting($slidercaption, 'format_html');
                    $retval .= '</div>';
                }
                $retval .= $closelink . '</li>';
            }
        }
        $retval .= '</ul></div></div>';
        return $retval;
    }

    /**
     * Renders the breadcrumb navbar.
     *
     */
    public function page_navbar($addbutton = false) {
        global $PAGE;
        $retval = '';

        if (!isset($PAGE->theme->settings->enabletickermy)) {
            $PAGE->theme->settings->enabletickermy = 0;
        }

        // Do not show navbar on dashboard / my home if news ticker is rendering.
        if (!($PAGE->theme->settings->enabletickermy && $PAGE->bodyid == "page-my-index")) {
            $retval = '<div id="page-navbar" class="span12">';
            if ($addbutton) {
                $retval .= '<nav class="breadcrumb-button">' . $this->page_heading_button() . '</nav>';
            }

            $retval .= $this->navbar();
            $retval .= '</div>';
        }
        return $retval;
    }


    /*
     * Render the breadcrumb
     * @param array $items
     * @param string $breadcrumbs
     *
     * return string
     */
    public function navbar() {
        global $PAGE;

        $items = $this->page->navbar->get_items();
        $breadcrumbseparator = $PAGE->theme->settings->breadcrumbseparator;

        $breadcrumbs = "";

        if (empty($items)) {
            return '';
        }

        $i = 0;

        foreach ($items as $item) {
            $item->hideicon = true;

            // Text / Icon home.
            if ($i++ == 0) {
                if ($PAGE->theme->settings->breadcrumbhome == 'icon') {
                    $breadcrumbs = html_writer::link(new moodle_url('/'),
                                   html_writer::tag('i', '', array('class' => 'fa fa-home fa-lg')));
                } else {
                    $breadcrumbs = html_writer::link(new moodle_url('/'), get_string('home'));
                }
                continue;
            }

            $breadcrumbs .= '<span class="separator"><i class="fa-'.$breadcrumbseparator.' fa"></i>
                             </span><li>'.$this->render($item).'</li>';

        } // End loop.

        return '<ul class="breadcrumb">'.$breadcrumbs.'</ul>';
    }


    /**
     * Returns html to render footer
     *
     * @return string
     */
    public function footer() {
        global $CFG;

        $output = $this->container_end_all(true);

        $footer = $this->opencontainers->pop('header/footer');

        // Provide some performance info if required.
        $performanceinfo = '';
        if (defined('MDL_PERF') || (!empty($CFG->perfdebug) and $CFG->perfdebug > 7)) {
            $perf = get_performance_info();

            if (defined('MDL_PERFTOFOOT') || debugging() || $CFG->perfdebug > 7) {
                $performanceinfo = theme_adaptable_performance_output($perf);
            }
        }

        $footer = str_replace($this->unique_performance_info_token, $performanceinfo, $footer);

        $footer = str_replace($this->unique_end_html_token, $this->page->requires->get_end_code(), $footer);

        $this->page->set_state(moodle_page::STATE_DONE);

        return $output . $footer;
    }

    /**
     * Returns html to render main navigation menu
     *
     * @return string
     */
    public function navigation_menu() {
        global $PAGE, $COURSE, $OUTPUT, $CFG;
        $menu = new custom_menu();
        $access = true;
        $overridelist = false;
        $overridestrings = false;
        $overridetype = 'off';
        $sessttl = 0;
        $cache = cache::make('theme_adaptable', 'userdata');

        if ($sessttl > 0 && time() <= $cache->get('usernavbarttl')) {
            return $cache->get('mysitesvisibility');
        }

        $usernavbar = 'excludehidden';
        if (!empty($PAGE->theme->settings->enablemysites)) {
            $mysitesvisibility = $PAGE->theme->settings->enablemysites;
        }

        $mysitesmaxlength = '30';
        if (!empty($PAGE->theme->settings->mysitesmaxlength)) {
            $mysitesmaxlength = $PAGE->theme->settings->mysitesmaxlength;
        }

        $mysitesmaxlengthhidden = $mysitesmaxlength - 3;

        if (isloggedin() && !isguestuser()) {
            if (!empty($PAGE->theme->settings->enablehome)) {
                $branchtitle = get_string('home');
                $branchlabel = '<i class="fa fa-home"></i> '.$branchtitle;
                if (!empty($PAGE->theme->settings->enablehomeredirect)) {
                    $branchurl   = new moodle_url('/?redirect=0');
                } else {
                    $branchurl   = new moodle_url('/');
                }
                $branchsort  = 9998;
                $branch = $menu->add($branchlabel, $branchurl, '', $branchsort);
            }

            if (!empty($PAGE->theme->settings->enablemyhome)) {
                $branchtitle = get_string('myhome');
                $branchlabel = '<i class="fa fa-dashboard"></i> '.$branchtitle;
                $branchurl   = new moodle_url('/my/index.php');
                $branchsort  = 9999;
                $branch = $menu->add($branchlabel, $branchurl, '', $branchsort);
            }

            if (!empty($PAGE->theme->settings->enableevents)) {
                $branchtitle = get_string('events', 'theme_adaptable');
                $branchlabel = '<i class="fa fa-calendar"></i> '.$branchtitle;
                $branchurl   = new moodle_url('/calendar/view.php');
                $branchsort  = 10000;
                $branch = $menu->add($branchlabel, $branchurl, '', $branchsort);
            }

            if (!empty($PAGE->theme->settings->mysitessortoverride) && $PAGE->theme->settings->mysitessortoverride != 'off'
                && !empty($PAGE->theme->settings->mysitessortoverridefield)) {

                $overridetype = $PAGE->theme->settings->mysitessortoverride;
                $overridelist = $PAGE->theme->settings->mysitessortoverridefield;

                if ($overridetype == 'profilefields' || $overridetype == 'profilefieldscohort') {
                    $overridelist = $this->get_profile_field_contents($overridelist);

                    if ($overridetype == 'profilefieldscohort') {
                        $overridelist = array_merge($this->get_cohort_enrollments(), $overridelist);
                    }
                }

                if ($PAGE->theme->settings->mysitessortoverride == 'strings') {
                    $overridelist = explode(',', $overridelist);
                }
            }

            if ($mysitesvisibility != 'disabled') {
                $branchtitle = get_string('mysites', 'theme_adaptable');
                $branchlabel = '<i class="fa fa-briefcase"></i>'.$branchtitle;
                $branchurl   = new moodle_url('/my/index.php');
                $branchsort  = 10001;

                $branch = $menu->add($branchlabel, $branchurl, '', $branchsort);
                list($sortedcourses, $sitecourses, $totalcourses) = block_course_overview_get_sorted_courses();

                $icon = '';

                if ($sortedcourses) {
                    foreach ($sortedcourses as $course) {
                        if ($course->visible) {
                            if (!$overridelist) { // Feature not in use, add to menu as normal.
                                $branch->add(mb_strimwidth(format_string($course->fullname), 0,  $mysitesmaxlength, '...', 'utf-8'),
                                    new moodle_url('/course/view.php?id='.$course->id), '');
                            } else { // We want to check against array from profile field.
                                if ((($overridetype == 'profilefields' ||
                                    $overridetype == 'profilefieldscohort') &&
                                    in_array($course->shortname, $overridelist)) ||
                                    ($overridetype == 'strings' &&
                                    $this->check_if_in_array_string($overridelist, $course->shortname))) {
                                        $icon = '';
                                        $branch->add($icon . mb_strimwidth(format_string($course->fullname),
                                                     0, $mysitesmaxlength, '...', 'utf-8'),
                                                     new moodle_url('/course/view.php?id='.$course->id), '', 100);
                                } else { // If not in array add to sub menu item.
                                    if (!isset($parent)) {
                                        $icon = '<i class="fa fa-history"></i> ';
                                        $parent = $branch->add($icon . $trunc = rtrim(
                                                    mb_strimwidth(format_string(get_string('pastcourses', 'theme_adaptable')),
                                                    0, $mysitesmaxlengthhidden)) . '...', new moodle_url('#'), '', 1000);
                                    }
                                    $parent->add($trunc = rtrim(mb_strimwidth(format_string($course->fullname),
                                                 0, $mysitesmaxlengthhidden)) . '...',
                                                 new moodle_url('/course/view.php?id='.$course->id),
                                                format_string($course->shortname));
                                }
                            }
                        }
                    }

                    $icon = '<i class="fa fa-eye-slash"></i> ';
                    $parent = null;
                    foreach ($sortedcourses as $course) {
                        if (!$course->visible && $mysitesvisibility == 'includehidden') {
                            if (empty($parent)) {
                                $parent = $branch->add($icon . $trunc =
                                    rtrim(mb_strimwidth(format_string(get_string('hiddencourses', 'theme_adaptable')),
                                        0, $mysitesmaxlengthhidden)) . '...',
                                        new moodle_url('#'), '', 2000);
                            }
                            $parent->add($icon . $trunc = rtrim(mb_strimwidth(format_string($course->fullname),
                                0, $mysitesmaxlengthhidden)) . '...',
                                new moodle_url('/course/view.php?id='.$course->id), format_string($course->shortname));
                        }
                    }
                } else {
                    $noenrolments = get_string('noenrolments', 'theme_adaptable');
                    $branch->add('<em>'.$noenrolments.'</em>', new moodle_url('/'), $noenrolments);
                }
            }

            if (!empty($PAGE->theme->settings->enablethiscourse)) {
                if (ISSET($COURSE->id) && $COURSE->id > 1) {
                    $branchtitle = get_string('thiscourse', 'theme_adaptable');
                    $branchlabel = '<i class="fa fa-sitemap"></i><span class="menutitle">'.$branchtitle.'</span>';
                    $branchurl = new moodle_url('#');
                    $branch = $menu->add($branchlabel, $branchurl, '', 10002);

                    $branchtitle = get_string('people', 'theme_adaptable');
                    $branchlabel = '<i class="fa fa-users"></i>'.$branchtitle;
                    $branchurl = new moodle_url('/user/index.php', array('id' => $PAGE->course->id));
                    $branch->add($branchlabel, $branchurl, '', 100003);

                    $branchtitle = get_string('grades');
                    $branchlabel = $OUTPUT->pix_icon('i/grades', '', '', array('class' => 'icon')).$branchtitle;
                    $branchurl = new moodle_url('/grade/report/index.php', array('id' => $PAGE->course->id));
                    $branch->add($branchlabel, $branchurl, '', 100004);

                    $data = theme_adaptable_get_course_activities();

                    foreach ($data as $modname => $modfullname) {
                        if ($modname === 'resources') {
                            $icon = $OUTPUT->pix_icon('icon', '', 'mod_page', array('class' => 'icon'));
                            $branch->add($icon.$modfullname, new moodle_url('/course/resources.php',
                                         array('id' => $PAGE->course->id)));
                        } else {
                            $icon = '<img src="'.$OUTPUT->pix_url('icon', $modname) . '" class="icon" alt="" />';
                            $branch->add($icon.$modfullname, new moodle_url('/mod/'.$modname.'/index.php',
                                         array('id' => $PAGE->course->id)));
                        }
                    }
                }
            }
        }

        if (!empty($PAGE->theme->settings->enablehelp)) {
            $access = true;

            if (!empty($PAGE->theme->settings->helpprofilefield)) {
                $fields = explode('=', $PAGE->theme->settings->helpprofilefield);
                $ftype = $fields[0];
                $setvalue = $fields[1];
                if (!$this->check_menu_access($ftype, $setvalue, 'help1')) {
                    $access = false;
                }
            }

            if ($access && !$this->hideinforum()) {
                $branchtitle = get_string('helptitle', 'theme_adaptable');
                $branchlabel = '<i class="fa fa-life-ring"></i>'.$branchtitle;
                $branchurl   = new moodle_url($PAGE->theme->settings->enablehelp.'" target="'.$PAGE->theme->settings->helptarget);
                $branchsort  = 10003;
                $branch = $menu->add($branchlabel, $branchurl, '', $branchsort);
            }
        }

        if (!empty($PAGE->theme->settings->enablehelp2 )) {
            $access = true;
            if (!empty($PAGE->theme->settings->helpprofilefield2)) {
                $fields = explode('=', $PAGE->theme->settings->helpprofilefield2);
                $ftype = $fields[0];
                $setvalue = $fields[1];
                if (!$this->check_menu_access($ftype, $setvalue, 'help2')) {
                    $access = false;
                }
            }

            if ($access && !$this->hideinforum()) {
                $branchtitle = get_string('helptitle2', 'theme_adaptable');
                $branchlabel = '<i class="fa fa-life-ring"></i>'.$branchtitle;
                $branchurl   = new moodle_url($PAGE->theme->settings->enablehelp2.'" target="'.$PAGE->theme->settings->helptarget);
                $branchsort  = 10003;
                $branch = $menu->add($branchlabel, $branchurl, '', $branchsort);
            }
        }

        if ($sessttl > 0) {
            $cache->set('usernavbarttl', $sessttl);
            $cache->set('usernavbar', $this->render_custom_menu($menu));
        }

        return $this->render_custom_menu($menu);
    }

    /**
     * Returns true if needs from array found in haystack
     * @param array $needles a list of strings to check
     * @param string $haystack value which may contain string
     * @return boolean
     */
    public function check_if_in_array_string($needles, $haystack) {
        foreach ($needles as $needle) {
            $needle = trim($needle);
            if (strstr($haystack, $needle)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Returns html to render tools menu in main navigation bar
     *
     * @return string
     */
    public function tools_menu() {
        global $PAGE;
        $class = "<i class='fa fa-wrench'></i><span class='menutitle'>";
        $custommenuitems = '';
        $access = true;
        $retval = '';

        if (!isset($PAGE->theme->settings->toolsmenuscount)) {
            return '';
        }
        $toolsmenuscount = $PAGE->theme->settings->toolsmenuscount;

        for ($i = 1; $i <= $toolsmenuscount; $i++) {
            $menunumber = 'toolsmenu' . $i;
            $menutitle = $menunumber . 'title';
            $requirelogin = $menunumber . 'requirelogin';
            $accessrules = $menunumber . 'field';
            $access = true;

            if (!empty($PAGE->theme->settings->$accessrules)) {
                $fields = explode ('=', $PAGE->theme->settings->$accessrules);
                $ftype = $fields[0];
                $setvalue = $fields[1];
                if (!$this->check_menu_access($ftype, $setvalue, $menunumber)) {
                    $access = false;
                }
            }

            if (!empty($PAGE->theme->settings->$menunumber) && $access == true && !$this->hideinforum()) {
                $menu = ($PAGE->theme->settings->$menunumber);
                $label = $PAGE->theme->settings->$menutitle;
                $custommenuitems = $this->parse_custom_menu($menu, $label, $class, '</span>');
                $custommenu = new custom_menu($custommenuitems);
                $retval .= $this->render_custom_menu($custommenu);
            }
        }
        return $retval;
    }

    /**
     * Returns html to render logo / title area
     *
     * @return string
     */
    public function get_logo_title() {
        global $PAGE, $COURSE, $CFG, $SITE, $OUTPUT;
        $retval = '';
        $display = $PAGE->theme->settings->sitetitle;

        $div = '<div id="titlecontainer" class="pull-left">';
        $retval .= $div;

        if (!empty($PAGE->theme->settings->logo)) {
            // Logo.
            $retval .= '<div id="logocontainer">';
            $retval .= "<a href='$CFG->wwwroot'>";
            $retval .= '<img src=' . $PAGE->theme->setting_file_url('logo', 'logo') . ' alt="logo" id="logo" />';
            $retval .= '</a></div>';
        } else {
            $retval .= '<div style="height: 20px"></div>';
        }

        if ($display == 'default') {
            // Default moodle title.
            if ($COURSE->id > 1) {
                // Course Title.
                switch ($PAGE->theme->settings->enableheading) {
                    case 'fullname':
                        // Full Course Name.
                        $retval .= '<div id="sitetitle">' . format_string($COURSE->fullname) . '</div>';
                        break;

                    case 'shortname':
                        // Short Course Name.
                        $retval .= '<div id="sitetitle">' . format_string($COURSE->shortname) . '</div>';
                        break;

                    default:
                        // None.
                        $retval .= '<div id="sitetitle"></div>';
                        break;
                }
            } else {
                // Site Title.
                $retval .= '<div id="sitetitle">' . $SITE->shortname . '</div>';
            }
        } else {
            if ($display == 'custom') {
                // Custom title.
                if (!empty($PAGE->theme->settings->sitetitletext)) {
                    $header = theme_adaptable_remove_site_fullname($PAGE->theme->settings->sitetitletext);
                    $PAGE->set_heading($header);

                    $retval .= '<div id="sitetitle">' . $PAGE->theme->settings->sitetitletext . '</div>';
                }
            }
        }

        $retval .= '</div>';

        return $retval;
    }

    /**
     * Returns html to render top menu items
     *
     * @return string
     */
    public function get_top_menus() {
        global $PAGE, $COURSE;
        $menus = '';
        $retval = '';
        $visibility = true;

        if (!empty($PAGE->theme->settings->menuuseroverride)) {
            $visibility = $this->check_menu_user_visibility();
        }

        if ($visibility) {
            if (!empty($PAGE->theme->settings->topmenuscount) && !empty($PAGE->theme->settings->enablemenus)
                && (!$PAGE->theme->settings->disablemenuscoursepages || $COURSE->id == 1)) {

                $topmenuscount = $PAGE->theme->settings->topmenuscount;
                for ($i = 1; $i <= $topmenuscount; $i++) {
                    $menunumber = 'menu' . $i;
                    $newmenu = 'newmenu' . $i;
                    $class = 'newmenu' . ($i + 4);
                    $fieldsetting = 'newmenu' . $i . 'field';
                    $valuesetting = 'newmenu' . $i . 'value';
                    $newmenutitle = 'newmenu' . $i . 'title';
                    $requirelogin = 'newmenu' . $i . 'requirelogin';
                    $logincheck = true;
                    $custommenuitems = '';
                    $access = true;
                    $pre = '<div class="dropdown pull-right newmenus ' . $class . '">';
                    $post = '</div>';

                    if (empty($PAGE->theme->settings->$requirelogin) || isloggedin()) {
                        if (!empty($PAGE->theme->settings->$fieldsetting)) {
                            $fields = explode('=', $PAGE->theme->settings->$fieldsetting);
                            $ftype = $fields[0];
                            $setvalue = $fields[1];
                            if (!$this->check_menu_access($ftype, $setvalue, $menunumber)) {
                                $access = false;
                            }
                        }

                        if (!empty($PAGE->theme->settings->$newmenu) && $access == true) {
                            $menu = ($PAGE->theme->settings->$newmenu);
                            $title = ($PAGE->theme->settings->$newmenutitle);
                            $custommenuitems = $this->parse_custom_menu($menu, $title);
                            $custommenu = new custom_menu($custommenuitems);
                            $retval .= $this->render_custom_menu($custommenu, $pre, $post);
                        }
                    }
                }
            }
        }
        return $retval;
    }

    /**
     * Checks menu visibility where setup to allow users to control via custom profile setting
     *
     * @return boolean
     */
    public function check_menu_user_visibility() {
        global $PAGE, $USER, $COURSE;
        $uservalue = '';

        if (empty($PAGE->theme->settings->menuuseroverride)) {
            return true;
        }

        if (isset($USER->theme_adaptable_menus['menuvisibility'])) {
            $uservalue = $USER->theme_adaptable_menus['menuvisibility'];
        } else {
            $profilefield = $PAGE->theme->settings->menuoverrideprofilefield;
            $profilefield = 'profile_field_' . $profilefield;
            $uservalue = $this->get_user_visibility($profilefield);
        }

        if ($uservalue == 0) {
            return true;
        }

        if ($uservalue == 1 && $COURSE->id != 1) {
            return false;
        }

        if ($uservalue == 2) {
            return false;
        }

        // Default to true means we dont have to evaluate sitewide setting and guarantees return value.
        return true;
    }

    /**
     * Check users menu visibility settings, will store in session to avaoid repeated loading of profile data
     * @param string $profilefield
     * @return boolean
     */
    public function get_user_visibility($profilefield) {
        global $USER, $CFG;
        $uservisibility = '';

        require_once($CFG->dirroot.'/user/profile/lib.php');
        require_once($CFG->dirroot.'/user/lib.php');
        profile_load_data($USER);

        $uservisibility = $USER->$profilefield;
        $USER->theme_adaptable_menus['menuvisibility'] = $uservisibility;
        return $uservisibility;
    }

    /**
     * Checks menu access based on admin settings and a users custom profile fields
     *
     * @param string $ftype the custom profile field
     * @param string $setvalue the expected value a user must have in their profile field
     * @param string $menu a token to identify the menu used to store access in session
     * @return boolean
     */
    public function check_menu_access($ftype, $setvalue, $menu) {
        global $PAGE, $USER, $CFG;
        $usersvalue = 'default-zz'; // Just want a value that will not be matched by accident.
        $sessttl = (time() + ($PAGE->theme->settings->menusessionttl * 60));
        $menuttl = $menu . 'ttl';

        if ($PAGE->theme->settings->menusession) {
            if (isset($USER->theme_adaptable_menus[$menu])) {

                if ($USER->theme_adaptable_menus[$menuttl] >= time()) {
                    if ($USER->theme_adaptable_menus[$menu] == true) {
                        return true;
                    } else if ($USER->theme_adaptable_menus[$menu] == false) {
                        return false;
                    }
                }
            }
        }

        require_once($CFG->dirroot.'/user/profile/lib.php');
        require_once($CFG->dirroot.'/user/lib.php');
        profile_load_data($USER);
        $ftype = "profile_field_$ftype";
        if (isset($USER->$ftype)) {
            $usersvalue = $USER->$ftype;
        }

        if ($usersvalue == $setvalue) {
            $USER->theme_adaptable_menus[$menu] = true;
            $USER->theme_adaptable_menus[$menuttl] = $sessttl;
            return true;
        }

        $USER->theme_adaptable_menus[$menu] = false;
        $USER->theme_adaptable_menus[$menuttl] = $sessttl;
        return false;
    }

    /**
     * Returns list of cohort enrollments
     *
     * @return array
     */
    public function get_cohort_enrollments() {
        global $DB, $USER;
        $userscohorts = $DB->get_records('cohort_members', array('userid' => $USER->id));
        $courses = array();
        if ($userscohorts) {
            $cohortedcourseslist = $DB->get_records_sql('select '
                    . 'courseid '
                    . 'from {enrol} '
                    . 'where enrol = "cohort" '
                    . 'and customint1 in (?)', array_keys($userscohorts));
            $cohortedcourses = $DB->get_records_list('course', 'id', array_keys($cohortedcourseslist), null, 'shortname');
            foreach ($cohortedcourses as $course) {
                $courses[] = $course->shortname;
            }
        }
        return($courses);
    }

    /**
     * Returns contents of multiple comma delimited custom profile fields
     *
     * @param string $profilefields delimited list of fields
     * @return array
     */
    public function get_profile_field_contents($profilefields) {
        global $PAGE, $USER, $CFG;
        $timestamp = 'currentcoursestime';
        $list = 'currentcourseslist';

        if (isset($USER->theme_adaptable_menus[$timestamp])) {
            if ($USER->theme_adaptable_menus[$timestamp] >= time()) {
                if (isset($USER->theme_adaptable_menus[$list])) {
                    return $USER->theme_adaptable_menus[$list];
                }
            }
        }

        $sessttl = 1000 * 60 * 3;
        $sessttl = 0;
        $sessttl = time() + $sessttl;
        $retval = array();

        require_once($CFG->dirroot.'/user/profile/lib.php');
        require_once($CFG->dirroot.'/user/lib.php');
        profile_load_data($USER);

        $fields = explode(',', $profilefields);

        foreach ($fields as $field) {
            $field = trim($field);
            $field = "profile_field_$field";
            if (isset($USER->$field)) {
                $vals = explode(',', $USER->$field);
                foreach ($vals as $value) {
                    $retval[] = trim($value);
                }
            }
        }

        $USER->theme_adaptable_menus[$list] = $retval;
        $USER->theme_adaptable_menus[$timestamp] = $sessttl;
        return $retval;
    }

    /**
     * Parses / wraps custom menus in HTML
     *
     * @param string $menu
     * @param string $label
     * @param string $class
     * @param string $close
     *
     * @return string
     */
    public function parse_custom_menu($menu, $label, $class = '', $close = '') {
        $custommenuitems = $class . $label. $close . "|#|".$label."\n";
        $arr = explode("\n", $menu);

        // We want to force everything inputted under this menu.
        foreach ($arr as $key => $value) {
            $arr[$key] = '-' . $arr[$key];
        }

        $custommenuitems .= implode("\n", $arr);
        return $custommenuitems;
    }

    /**
     * Hide tools menu in forum to make room for forum search optoin
     *
     * @return boolean
     */
    public function hideinforum() {
        global $PAGE;
        $hidelinks = false;
        if (!empty($PAGE->theme->settings->hideinforum)) {
            if (strstr($_SERVER['REQUEST_URI'], '/mod/forum/')) {
                $hidelinks = true;
            }
        }
        return $hidelinks;
    }

    /**
     * Wrap html round custom menu
     *
     * @param string $custommenu
     * @param string $classno
     *
     * @return string
     */
    public function wrap_custom_menu_top($custommenu, $classno) {
        $retval = '<div class="dropdown pull-right newmenus newmenu$classno">';
        $retval .= $custommenu;
        $retval .= '</div>';
        return $retval;
    }

    /**
     * Returns language menu
     *
     * @return string
     */
    public function lang_menu() {
        global $CFG;
        $langmenu = new custom_menu();

        $addlangmenu = true;
        $langs = get_string_manager()->get_list_of_translations();
        if (count($langs) < 2
            || empty($CFG->langmenu)
            || ($this->page->course != SITEID 
            && !empty($this->page->course->lang))) {
                $addlangmenu = false;
        }

        if ($addlangmenu) {
            $strlang = get_string('language');
            $currentlang = current_language();
            if (isset($langs[$currentlang])) {
                $currentlang = $langs[$currentlang];
            } else {
                $currentlang = $strlang;
            }
            $this->language = $langmenu->add('<i class="fa fa-globe fa-lg"></i><span class="langdesc">'.$currentlang.'</span>',
                    new moodle_url('#'), $strlang, 100);
            foreach ($langs as $langtype => $langname) {
                $this->language->add($langname, new moodle_url($this->page->url, array('lang' => $langtype)), $langname);
            }
        }
        return $this->render_custom_menu($langmenu);
    }

    /**
     * Returns html for custom menu
     *
     * @param string $custommenuitems = ''
     * @return array
     */
    public function custom_menu($custommenuitems = '') {
        global $CFG;

        if (empty($custommenuitems) && !empty($CFG->custommenuitems)) {
            $custommenuitems = $CFG->custommenuitems;
        }
        $custommenu = new custom_menu($custommenuitems, current_language());
        return $this->render_custom_menu($custommenu);
    }

    /**
     * This renders the bootstrap top menu.     *
     * This renderer is needed to enable the Bootstrap style navigation.
     *
     * @param custom_menu $menu
     * @param string $wrappre
     * @param string $wrappost
     * @return string
     */
    protected function render_custom_menu(custom_menu $menu, $wrappre = '', $wrappost = '') {
        global $CFG;

        // TODO: eliminate this duplicated logic, it belongs in core, not
        // here. See MDL-39565.
        $addlangmenu = true;
        $langs = get_string_manager()->get_list_of_translations();
        if (count($langs) < 2
            or empty($CFG->langmenu)
            or ($this->page->course != SITEID and !empty($this->page->course->lang))) {
            $addlangmenu = false;
        }

        if (!$menu->has_children() && $addlangmenu === false) {
            return '';
        }

        $content = '<ul class="nav">';
        foreach ($menu->get_children() as $item) {
            $content .= $this->render_custom_menu_item($item, 1);
        }
        $content = $wrappre . $content . '</ul>' . $wrappost;
        return $content;
    }

    /**
     * This code renders the custom menu items for the bootstrap dropdown menu.
     *
     * @param custom_menu_item $menunode
     * @param int $level = 0
     * @return string
     */
    protected function render_custom_menu_item(custom_menu_item $menunode, $level = 0) {
        static $submenucount = 0;

        if ($menunode->has_children()) {

            if ($level == 1) {
                $class = 'dropdown';
            } else {
                $class = 'dropdown-submenu';
            }

            if ($menunode === $this->language) {
                $class .= ' langmenu';
            }
            $content = html_writer::start_tag('li', array('class' => $class));
            // If the child has menus render it as a sub menu.
            $submenucount++;
            if ($menunode->get_url() !== null) {
                $url = $menunode->get_url();
            } else {
                $url = '#cm_submenu_'.$submenucount;
            }
            $content .= html_writer::start_tag('a', array('href' => $url, 'class' => 'dropdown-toggle',
                    'data-toggle' => 'dropdown', 'title' => $menunode->get_title()));
            $content .= $menunode->get_text();
            $content .= '</a>';
            $content .= '<ul class="dropdown-menu">';
            foreach ($menunode->get_children() as $menunode) {
                $content .= $this->render_custom_menu_item($menunode, 0);
            }
            $content .= '</ul>';
        } else {
            $content = '<li>';
            // The node doesn't have children so produce a final menuitem.
            if ($menunode->get_url() !== null) {
                $url = $menunode->get_url();
            } else {
                $url = '#';
            }
            $content .= html_writer::link($url, $menunode->get_text(), array('title' => $menunode->get_title()));
            $content .= "</li>";
        }
        return $content;
    }

    /**
     * Renders tabtree
     *
     * @param tabtree $tabtree
     * @return string
     */
    protected function render_tabtree(tabtree $tabtree) {
        if (empty($tabtree->subtree)) {
            return '';
        }
        $firstrow = $secondrow = '';
        foreach ($tabtree->subtree as $tab) {
            $firstrow .= $this->render($tab);
            if (($tab->selected || $tab->activated) && !empty($tab->subtree) && $tab->subtree !== array()) {
                $secondrow = $this->tabtree($tab->subtree);
            }
        }
        return html_writer::tag('ul', $firstrow, array('class' => 'nav nav-tabs')) . $secondrow;
    }

    /**
     * Renders tabobject (part of tabtree)
     *
     * This function is called from {@link core_renderer::render_tabtree()}
     * and also it calls itself when printing the $tabobject subtree recursively.
     *
     * @param tabobject $tab
     * @return string HTML fragment
     */
    protected function render_tabobject(tabobject $tab) {
        if ($tab->selected or $tab->activated) {
            return html_writer::tag('li', html_writer::tag('a', $tab->text), array('class' => 'active'));
        } else if ($tab->inactive) {
            return html_writer::tag('li', html_writer::tag('a', $tab->text), array('class' => 'disabled'));
        } else {
            if (!($tab->link instanceof moodle_url)) {
                // Backward compartibility when link was passed as quoted string.
                $link = "<a href=\"$tab->link\" title=\"$tab->title\">$tab->text</a>";
            } else {
                $link = html_writer::link($tab->link, $tab->text, array('title' => $tab->title));
            }
            return html_writer::tag('li', $link);
        }
    }

    /**
     * Returns empty string
     *
     * @return string
     */
    protected function theme_switch_links() {
        // We're just going to return nothing and fail nicely, whats the point in bootstrap if not for responsive?
        return '';
    }

    /**
     * Render blocks
     * @param string $region
     * @param array $classes
     * @param string $tag
     * @return string
     */
    public function adaptableblocks($region, $classes = array(), $tag = 'aside') {
        $classes = (array)$classes;
        $classes[] = 'block-region';
        $attributes = array(
            'id' => 'block-region-'.preg_replace('#[^a-zA-Z0-9_\-]+#', '-', $region),
            'class' => join(' ', $classes),
            'data-blockregion' => $region,
            'data-droptarget' => '1'
        );
        return html_writer::tag($tag, $this->blocks_for_region($region), $attributes);
    }
}

/**
 * @copyright 2015 Jeremy Hopkins (Coventry University)
 * @copyright 2015 Fernando Acedo (3-bits.com)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * Course renderers for Adaptable theme based on BCU Theme
 */
class theme_adaptable_core_course_renderer extends core_course_renderer {
    /**
     * REnder course category box
     *
     * @param coursecat_helper $chelper
     * @param string $course
     * @param string $additionalclasses
     * @return string
     */
    protected function coursecat_coursebox(coursecat_helper $chelper, $course, $additionalclasses = '') {
        global $CFG, $OUTPUT, $PAGE;
        $type = theme_adaptable_get_setting('frontpagerenderer');
        if ($type == 3 || $OUTPUT->body_id() != 'page-site-index') {
            return parent::coursecat_coursebox($chelper, $course, $additionalclasses = '');
        }
        $additionalcss = '';
        if ($type == 2) {
            $additionalcss = 'hover';
        }

        if ($type == 4) {
            $additionalcss = 'hover covtiles';
            $type = 2;
            $covhidebutton = "true";
        } else {
            $covhidebutton = "false";
        }

        if (!isset($this->strings->summary)) {
            $this->strings->summary = get_string('summary');
        }
        if ($chelper->get_show_courses() <= self::COURSECAT_SHOW_COURSES_COUNT) {
            return '';
        }
        if ($course instanceof stdClass) {
            require_once($CFG->libdir. '/coursecatlib.php');
            $course = new course_in_list($course);
        }
        $content = '';
        $classes = trim($additionalclasses);

        if ($chelper->get_show_courses() < self::COURSECAT_SHOW_COURSES_EXPANDED) {
            $classes .= ' collapsed';
        }

        // Control span to display course tiles.
        if (!isloggedin() || isguestuser()) {
            $spanclass = "span4";
        } else {
            $spanclass = "span4";
        }

        $content .= html_writer::start_tag('div',
                                            array('class' => ' '.$spanclass.' panel panel-default coursebox '.$additionalcss));
        $urlb = new moodle_url('/course/view.php', array('id' => $course->id));

        $content .= "<a href='$urlb'>";

        $coursename = $chelper->get_course_formatted_name($course);
        $content .= html_writer::start_tag('div', array('class' => 'panel-heading'));
        if ($type == 1) {
            $content .= html_writer::link(new moodle_url('/course/view.php', array('id' => $course->id)),
                    $coursename, array('class' => $course->visible ? '' : 'dimmed', 'title' => $coursename));
        }
        // If we display course in collapsed form but the course has summary or course contacts, display the link to the info page.
        if ($chelper->get_show_courses() < self::COURSECAT_SHOW_COURSES_EXPANDED) {
            if ($course->has_summary() || $course->has_course_contacts() || $course->has_course_overviewfiles()) {
                $url = new moodle_url('/course/info.php', array('id' => $course->id));
                $arrow = html_writer::tag('span', '', array('class' => 'glyphicon glyphicon-info-sign'));
                $content .= html_writer::link('#coursecollapse' . $course->id , '&nbsp;' . $arrow,
                        array('data-toggle' => 'collapse', 'data-parent' => '#frontpage-category-combo'));
            }
        }

        if ($type == 1) {
            $content .= $this->coursecat_coursebox_enrolmenticons($course, $type);
        }

        $content .= html_writer::end_tag('div'); // End .panel-heading.
        $content .= html_writer::end_tag('a'); // End a.

        if ($chelper->get_show_courses() < self::COURSECAT_SHOW_COURSES_EXPANDED) {
            $content .= html_writer::start_tag('div', array('id' => 'coursecollapse' . $course->id,
                    'class' => 'panel-collapse collapse'));
        }

        $content .= html_writer::start_tag('div', array('class' => 'panel-body clearfix'));

        // This gets the course image or files.
        $content .= $this->coursecat_coursebox_content($chelper, $course, $type);

        if ($chelper->get_show_courses() >= self::COURSECAT_SHOW_COURSES_EXPANDED) {
            $icondirection = 'left';
            if ('ltr' === get_string('thisdirection', 'langconfig')) {
                $icondirection = 'right';
            }
            $arrow = html_writer::tag('span', '', array('class' => 'fa fa-chevron-'.$icondirection));
            $btn = html_writer::tag('span', get_string('course', 'theme_adaptable') . ' ' . $arrow, array('class' => 'get_stringlink'));

            if (empty($PAGE->theme->settings->covhidebutton)) {
                $content .= html_writer::link(new moodle_url('/course/view.php',
                array('id' => $course->id)), $btn, array('class' => " coursebtn submit btn btn-info btn-sm pull-right"));
            }
        }

        $content .= html_writer::end_tag('div'); // End .panel-body.

        if ($chelper->get_show_courses() < self::COURSECAT_SHOW_COURSES_EXPANDED) {
            $content .= html_writer::end_tag('div'); // End .collapse.
        }

        $content .= html_writer::end_tag('div'); // End .panel.

        return $content;
    }

    /**
     * Returns enrolment icons
     *
     * @param string $course
     * @return string
     */
    protected function coursecat_coursebox_enrolmenticons($course) {
        $content = '';
        if ($icons = enrol_get_course_info_icons($course)) {
            $content .= html_writer::start_tag('div', array('class' => 'enrolmenticons'));
            foreach ($icons as $pixicon) {
                $content .= $this->render($pixicon);
            }
            $content .= html_writer::end_tag('div'); // Enrolmenticons.
        }
        return $content;
    }


     /**
      * Returns course box content for categories
      *
      * Type - 1 = No Overlay.
      * Type - 2 = Overlay.
      *
      * @param coursecat_helper $chelper
      * @param string $course
      * @param int $type = 3
      * @return string
      */
    protected function coursecat_coursebox_content(coursecat_helper $chelper, $course, $type=3) {
        global $CFG, $OUTPUT, $PAGE;
        if ($chelper->get_show_courses() < self::COURSECAT_SHOW_COURSES_EXPANDED) {
            return '';
        }
        if ($course instanceof stdClass) {
            require_once($CFG->libdir. '/coursecatlib.php');
            $course = new course_in_list($course);
        }
        if ($type == 3 || $OUTPUT->body_id() != 'page-site-index') {
            return parent::coursecat_coursebox_content($chelper, $course);
        }
        $content = '';

        // Display course overview files.
        $contentimages = '';
        $contentfiles = '';
        foreach ($course->get_course_overviewfiles() as $file) {
            $isimage = $file->is_valid_image();
            $url = file_encode_url("$CFG->wwwroot/pluginfile.php",
                    '/'. $file->get_contextid(). '/'. $file->get_component(). '/'.
                    $file->get_filearea(). $file->get_filepath(). $file->get_filename(), !$isimage);
            if ($isimage) {
                if ($type == 1) {
                    $contentimages .= html_writer::start_tag('div', array('class' => 'courseimage'));
                    $link = new moodle_url('/course/view.php', array('id' => $course->id));
                    $contentimages .= html_writer::link($link, html_writer::empty_tag('img', array('src' => $url)));
                    $contentimages .= html_writer::end_tag('div');
                } else {
                    $contentimages .= "<div class='cimbox' style='background: #FFF url($url) no-repeat center center;
                                                                  background-size: contain;'></div>";
                }
            } else {
                $image = $this->output->pix_icon(file_file_icon($file, 24), $file->get_filename(), 'moodle');
                $filename = html_writer::tag('span', $image, array('class' => 'fp-icon')).
                        html_writer::tag('span', $file->get_filename(), array('class' => 'fp-filename'));
                $contentfiles .= html_writer::tag('span',
                        html_writer::link($url, $filename),
                        array('class' => 'coursefile fp-filename-icon'));
            }
        }
        if (strlen($contentimages) == 0 && $type == 2) {
            // Default image.
            $url = $PAGE->theme->setting_file_url('frontpagerendererdefaultimage', 'frontpagerendererdefaultimage');
            $contentimages .= "<div class='cimbox' style='background: #FFF url($url) no-repeat center center;
                                                          background-size: contain;'></div>";
        }
        $content .= $contentimages. $contentfiles;

        if ($type == 2) {
            $content .= $this->coursecat_coursebox_enrolmenticons($course);
        }

        if ($type == 2) {
            $content .= html_writer::start_tag('div', array('class' => 'coursebox-content'));
            $coursename = $chelper->get_course_formatted_name($course);
            $content .= html_writer::tag('h3', html_writer::link(new moodle_url('/course/view.php', array('id' => $course->id)),
                    $coursename, array('class' => $course->visible ? '' : 'dimmed')));
        }
        $content .= html_writer::start_tag('div', array('class' => 'summary'));
        if (ISSET($coursename)) {
            $content .= html_writer::tag('p', html_writer::tag('strong', $coursename));
        }
        // Display course summary.
        if ($course->has_summary()) {
            $summs = $chelper->get_course_formatted_summary($course, array('overflowdiv' => false, 'noclean' => true,
                    'para' => false));
            $summs = strip_tags($summs);
            $truncsum = mb_strimwidth($summs, 0, 70, "...", 'utf-8');
            $content .= html_writer::tag('span', $truncsum, array('title' => $summs));
        }
        $coursecontacts = theme_adaptable_get_setting('tilesshowcontacts');
        if ($coursecontacts) {
            $coursecontacttitle = theme_adaptable_get_setting('tilescontactstitle');
            // Display course contacts. See course_in_list::get_course_contacts().
            if ($course->has_course_contacts()) {
                $content .= html_writer::start_tag('ul', array('class' => 'teachers'));
                foreach ($course->get_course_contacts() as $userid => $coursecontact) {
                    $name = ($coursecontacttitle ? $coursecontact['rolename'].': ' : html_writer::tag('i', '&nbsp;',
                            array('class' => 'fa fa-graduation-cap')) ).
                            html_writer::link(new moodle_url('/user/view.php',
                                    array('id' => $userid, 'course' => SITEID)),
                                $coursecontact['username']);
                    $content .= html_writer::tag('li', $name);
                }
                $content .= html_writer::end_tag('ul'); // Teachers.
            }
        }
        $content .= html_writer::end_tag('div'); // Summary.

        // Display course category if necessary (for example in search results).
        if ($chelper->get_show_courses() == self::COURSECAT_SHOW_COURSES_EXPANDED_WITH_CAT) {
            require_once($CFG->libdir. '/coursecatlib.php');
            if ($cat = coursecat::get($course->category, IGNORE_MISSING)) {
                $content .= html_writer::start_tag('div', array('class' => 'coursecat'));
                $content .= get_string('category').': '.
                        html_writer::link(new moodle_url('/course/index.php', array('categoryid' => $cat->id)),
                                $cat->get_formatted_name(), array('class' => $cat->visible ? '' : 'dimmed'));
                $content .= html_writer::end_tag('div'); // Coursecat.
            }
        }
        if ($type == 2) {
            $content .= html_writer::end_tag('div');
            // End course-content.
        }
        $content .= html_writer::tag('div', '', array('class' => 'boxfooter')); // Coursecat.

        return $content;
    }

    /**
     * Course search form
     *
     * @param string $value
     * @param string $format
     * @return string
     */
    public function course_search_form($value = '', $format = 'plain') {
        static $count = 0;
        $formid = 'coursesearch';
        if ((++$count) > 1) {
            $formid .= $count;
        }
        $inputid = 'coursesearchbox';
        $inputsize = 30;

        if ($format === 'navbar') {
            $formid = 'coursesearchnavbar';
            $inputid = 'navsearchbox';
        }

        $strsearchcourses = get_string("searchcourses");
        $searchurl = new moodle_url('/course/search.php');

        $form = array('id' => $formid, 'action' => $searchurl, 'method' => 'get', 'class' => "form-inline", 'role' => 'form');
        $output = html_writer::start_tag('form', $form);
        $output .= html_writer::start_div('form-group');
        $output .= html_writer::tag('label', $strsearchcourses, array('for' => $inputid, 'class' => 'sr-only'));
        $search = array('type' => 'text', 'id' => $inputid, 'size' => $inputsize, 'name' => 'search',
                        'class' => 'form-control', 'value' => s($value), 'placeholder' => $strsearchcourses);
        $output .= html_writer::empty_tag('input', $search);
        $output .= html_writer::end_div(); // Close form-group.
        $button = array('type' => 'submit', 'class' => 'btn btn-default');
        $output .= html_writer::tag('button', get_string('go'), $button);
        $output .= html_writer::end_tag('form');

        return $output;
    }

    /**
     * Frontpage course list
     *
     * @return string
     */
    public function frontpage_my_courses() {
        global $USER, $CFG, $DB;
        $output = '';
        if (!isloggedin() or isguestuser()) {
            return '';
        }

        $courses = block_course_overview_get_sorted_courses();
        list($sortedcourses, $sitecourses, $totalcourses) = block_course_overview_get_sorted_courses();
        if (!empty($sortedcourses) || !empty($rcourses) || !empty($rhosts)) {

            $chelper = new coursecat_helper();
            if (count($courses) > $CFG->frontpagecourselimit) {
                // There are more enrolled courses than we can display, display link to 'My courses'.
                $totalcount = count($sortedcourses);
                $courses = array_slice($sortedcourses, 0, $CFG->frontpagecourselimit, true);
                $chelper->set_courses_display_options(array(
                        'viewmoreurl' => new moodle_url('/my/'),
                        'viewmoretext' => new lang_string('mycourses')
                    ));
            } else {
                // All enrolled courses are displayed, display link to 'All courses' if there are more courses in system.
                $chelper->set_courses_display_options(array(
                        'viewmoreurl' => new moodle_url('/course/index.php'),
                        'viewmoretext' => new lang_string('fulllistofcourses')
                    ));
                $totalcount = $DB->count_records('course') - 1;
            }
            $chelper->set_show_courses(self::COURSECAT_SHOW_COURSES_EXPANDED)->set_attributes(
                    array('class' => 'frontpage-course-list-enrolled'));
            $output .= $this->coursecat_courses($chelper, $sortedcourses, $totalcount);

            if (!empty($rcourses)) {
                $output .= html_writer::start_tag('div', array('class' => 'courses'));
                foreach ($rcourses as $course) {
                    $output .= $this->frontpage_remote_course($course);
                }
                $output .= html_writer::end_tag('div');
            } else if (!empty($rhosts)) {
                $output .= html_writer::start_tag('div', array('class' => 'courses'));
                foreach ($rhosts as $host) {
                    $output .= $this->frontpage_remote_host($host);
                }
                $output .= html_writer::end_tag('div');
            }
        }
        return $output;
    }

    /**
     * Return the navbar content so that it can be echoed out by the layout
     *
     * @return string XHTML navbar
     */
    public function navbar() {
        $items = $this->page->navbar->get_items();
        $itemcount = count($items);
        if ($itemcount === 0) {
            return '';
        }

        $htmlblocks = array();
        // Iterate the navarray and display each node.
        $separator = get_separator();
        for ($i = 0; $i < $itemcount; $i++) {
            $item = $items[$i];
            $item->hideicon = true;
            if ($i === 0) {
                $content = html_writer::tag('li', $this->render($item));
            } else {
                $content = html_writer::tag('li', $separator.$this->render($item));
            }
            $htmlblocks[] = $content;
        }

        // Accessibility: heading for navbar list  (MDL-20446).
        $navbarcontent = html_writer::tag('span', get_string('pagepath'), array('class' => 'accesshide'));
        $navbarcontent .= html_writer::tag('ul', join('', $htmlblocks), array('role' => 'navigation'));
        return $navbarcontent;
    }

    /**
     * Renders a navigation node object.
     *
     * @param navigation_node $item The navigation node to render.
     * @return string HTML fragment
     */
    protected function render_navigation_node(navigation_node $item) {
        $content = $item->get_content();
        $title = $item->get_title();
        if ($item->icon instanceof renderable && !$item->hideicon) {
            $icon = $this->render($item->icon);
            $content = $icon.$content; // Use CSS for spacing of icons.
        }
        if ($item->helpbutton !== null) {
            $content = trim($item->helpbutton).html_writer::tag('span', $content, array('class' => 'clearhelpbutton',
                    'tabindex' => '0'));
        }
        if ($content === '') {
            return '';
        }
        if ($item->action instanceof action_link) {
            $link = $item->action;
            if ($item->hidden) {
                $link->add_class('dimmed');
            }
            if (!empty($content)) {
                // Providing there is content we will use that for the link content.
                $link->text = $content;
            }
            $content = $this->render($link);
        } else if ($item->action instanceof moodle_url) {
            $attributes = array();
            if ($title !== '') {
                $attributes['title'] = $title;
            }
            if ($item->hidden) {
                $attributes['class'] = 'dimmed_text';
            }
            $content = html_writer::link($item->action, $content, $attributes);

        } else if (is_string($item->action) || empty($item->action)) {
            $attributes = array('tabindex' => '0'); // Add tab support to span but still maintain character stream sequence.
            if ($title !== '') {
                $attributes['title'] = $title;
            }
            if ($item->hidden) {
                $attributes['class'] = 'dimmed_text';
            }
            $content = html_writer::tag('span', $content, $attributes);
        }
        return $content;
    }
}
