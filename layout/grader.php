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
$fixedheader = false;
require_once(dirname(__FILE__) . '/includes/header.php');
$left = theme_adaptable_get_block_side();
?>

<div class="container outercont">
    <div id="page-content" class="row-fluid">
        <div id="page-navbar" class="span12">
            <?php echo $OUTPUT->navbar(); ?>
        </div>
        <?php
            echo $OUTPUT->blocks('side-post', 'span3 desktop-first-column');
        ?>
        <section id="region-main" class="span9 <?php
if ($left) {
    echo ' ';
} else {
    echo 'desktop-first-column';
}
?>">

<?php
    echo $OUTPUT->course_content_header();
    echo $OUTPUT->main_content();
    echo $OUTPUT->course_content_footer();
?>
        </section>
    </div>
</div>

    <?php
    require_once(dirname(__FILE__) . '/includes/footer.php');
