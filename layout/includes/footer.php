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

?>
<footer id="page-footer">
<?php

echo $OUTPUT->get_footer_blocks();

if ($PAGE->theme->settings->moodledocs) {
    $footnoteclass = 'span4';
} else {
    $footnoteclass = 'span8';
}


if ($PAGE->theme->settings->showfooterblocks) {
?>
    <div class="info container2 clearfix">
        <div class="container">
            <div class="row-fluid">
                <div class="<?php echo $footnoteclass; ?>">
<?php echo
    $OUTPUT->get_setting('footnote', 'format_html');
?>
                </div>

<?php
    if ($PAGE->theme->settings->moodledocs) {
?>
                <div class="span4 helplink">
<?php
    echo $OUTPUT->page_doc_link(); ?>
                </div>
<?php
    }
?>
                <div class="span4">
                    <?php echo $OUTPUT->standard_footer_html(); ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>
</footer>
<a class="back-to-top" href="#top" ><i class="fa fa-angle-up "></i></a>
    <?php echo $OUTPUT->standard_end_of_body_html() ?>

</div>
<?php echo $PAGE->theme->settings->jssection; ?>
<?php echo $OUTPUT->get_all_tracking_methods(); ?>
</body>
</html>
