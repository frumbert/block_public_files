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
 * Print private files tree
 *
 * @package    block_public_files
 * @copyright  Tim St.Clair <tim.stclair@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

class block_public_files_renderer extends plugin_renderer_base {

    /**
     * Prints private files tree view
     * @return string
     */
    public function public_files_tree() {
        return $this->render(new public_files_tree);
    }

    public function render_public_files_tree(public_files_tree $tree) {
        $module = array('name'=>'block_public_files', 'fullpath'=>'/blocks/public_files/module.js', 'requires'=>array('yui2-treeview'));
        if (empty($tree->dir['subdirs']) && empty($tree->dir['files'])) {
            $html = $this->output->box(get_string('nofilesavailable', 'repository'));
        } else {
            $htmlid = 'public_files_tree_'.uniqid();
            $this->page->requires->js_init_call('M.block_public_files.init_tree', array(false, $htmlid));
            $html = '<div id="'.$htmlid.'">';
            $html .= $this->htmllize_tree($tree, $tree->dir);
            $html .= '</div>';
        }

        return $html;
    }

    /**
     * Internal function - creates htmls structure suitable for YUI tree.
     */
    protected function htmllize_tree($tree, $dir) {
        global $CFG;
        $yuiconfig = array();
        $yuiconfig['type'] = 'html';

        if (empty($dir['subdirs']) and empty($dir['files'])) {
            return '';
        }
        $result = '<ul>';
        foreach ($dir['subdirs'] as $subdir) {
            $image = $this->output->pix_icon(file_folder_icon(), $subdir['dirname'], 'moodle', array('class'=>'icon'));
            $result .= '<li yuiConfig=\''.json_encode($yuiconfig).'\'><div>'.$image.s($subdir['dirname']).'</div> '.$this->htmllize_tree($tree, $subdir).'</li>';
        }
        foreach ($dir['files'] as $file) {
            $props = null;
            $url = file_encode_url("$CFG->wwwroot/pluginfile.php", '/'.$tree->context->id.'/block_public_files/files'.$file->get_filepath().$file->get_filename(), true);
            $filename = $file->get_filename();
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            if ($ext === "webloc") { // macos url shortcut
                $obj = new \SimpleXMLElement($file->get_content());
                $str = $obj->xpath('//dict/string');
                if (isset($str)) {
                    $url = $str[0][0];
                    $props = ["target" => "_blank"];
                } // otherwise leave it alone

            } else if ($ext === "url") { // windows url shortcut
                $obj = $file->get_content();
                $obj = str_replace("\r\n","\n",$obj);
                if (preg_match('/^URL=(.*)$/m',$obj,$ar)) {
                    $url = $ar[1];
                    $props = ["target" => "_blank"];
                } // else wasn't something we recognise, so leave it
            }
            $image = $this->output->pix_icon(file_file_icon($file), $filename, 'moodle', array('class'=>'icon'));
            $filename = substr($filename, 0, -strlen($ext)-1); // trim dot and extension
            $result .= '<li yuiConfig=\''.json_encode($yuiconfig).'\'><div>'.html_writer::link($url, $image.$filename,$props).'</div></li>';
        }
        $result .= '</ul>';

        return $result;
    }
}

class public_files_tree implements renderable {
    public $context;
    public $dir;
    public function __construct() {
        $this->context = context_system::instance();
        $fs = get_file_storage();
        $this->dir = $fs->get_area_tree($this->context->id, 'block_public_files', 'files', 0);
    }
}
