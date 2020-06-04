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
 * @package   tool_modedit
 * @author    Mark Sharp <m.sharp@chi.ac.uk>
 * @copyright 2020 University of Chichester {@link www.chi.ac.uk}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/

require('../../../config.php');
require_once($CFG->dirroot . '/course/format/lib.php');

$courseid = required_param('id', PARAM_INT);
$course = $DB->get_record('course', ['id' => $courseid]);
$coursecontext = context_course::instance($courseid);

require_login($course);
require_capability('moodle/course:manageactivities', $coursecontext);

$PAGE->set_context($coursecontext);
$PAGE->set_url('/admin/tool/modedit.php', ['id' => $courseid]);
$PAGE->set_pagelayout('course');
$courseformat = course_get_format($course)->get_format();

$PAGE->set_pagetype('course-view-' . $courseformat);
$PAGE->set_title(get_string('editactivities', 'tool_modedit'));
$PAGE->set_heading($course->fullname);
echo $OUTPUT->header();

$activities = new \tool_modedit\output\modlist($courseid);
echo $OUTPUT->render($activities);

echo $OUTPUT->footer();