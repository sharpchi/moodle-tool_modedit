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
 * Lib file for modedit.
 *
 * @package   tool_modedit
 * @author    Mark Sharp <m.sharp@chi.ac.uk>
 * @copyright 2020 University of Chichester {@link https://www.chi.ac.uk}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Extends the course menu for this plugin.
 *
 * @param navigation_node $navigation The navigatio node to extend.
 * @param stdClass $course Course object.
 * @param context $context Context of the course.
 * @return void
 */
function tool_modedit_extend_navigation_course($navigation, $course, $context) {
    if (has_capability('moodle/course:manageactivities', $context)) {
        $url = new moodle_url('/admin/tool/modedit/index.php', array('id' => $course->id));
        $settingsnode = navigation_node::create(get_string('editactivities', 'tool_modedit'), $url, navigation_node::TYPE_SETTING,
                null, null, new pix_icon('t/edit', ''));
        $navigation->add_node($settingsnode);
    }
}
