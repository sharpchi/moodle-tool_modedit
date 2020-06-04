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

namespace tool_modedit\output;

use moodle_url;
use renderable;
use stdClass;
use templatable;

defined('MOODLE_INTERNAL') || die();

class modlist implements renderable, templatable {

    private $data;

    public function __construct($courseid) {
        $this->data = $this->get_activities($courseid);
    }

    public function export_for_template(\renderer_base $output) {
        $sections = new stdClass();
        $sections->sections = $this->data;
        return $sections;
    }

    /**
     * Gets an array of activities grouped by section.
     *
     * @param integer $courseid
     * @return array Multidimential array of activites grouped by section.
     */
    function get_activities(int $courseid) : array {
        global $DB;
        $mods = [];

        $sections = $DB->get_records('course_sections', ['course' => $courseid], 'section ASC');
        foreach ($sections as $key => $section) {
            $s = new stdClass();
            $s->name = $section->name ?? get_string('section') . ' ' . $section->section;
            $sectionurl = new moodle_url('/course/editsection.php', ['id' => $section->id]);
            $s->sectioneditlink = $sectionurl->out(false);
            $s->activities = [];

            $mods[$key] = $s;
            
            $activities = explode(",", $section->sequence);
            foreach ($activities as $activity) {
                $modinfo = get_fast_modinfo($courseid)->cms[$activity];
                $editurl = new moodle_url('/course/modedit.php', ['update' => $modinfo->id]);
                $mod = new stdClass();
                $mod->cm        = $modinfo->id;
                $mod->editurl   = $editurl->out(false);
                $mod->icon      = $modinfo->get_icon_url();
                $mod->id        = $modinfo->instance;
                $mod->mod       = $modinfo->modname;
                $mod->module    = $modinfo->module;
                $mod->name      = $modinfo->name;
                $mod->section   = $section->section;
                $mods[$key]->activities[] = $mod;
            }
        }
        return array_values($mods);
    }
}