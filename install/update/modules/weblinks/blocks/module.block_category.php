<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_WEBLINKS')) {
    exit('Stop!!!');
}

global $catid;

if (empty($catid)) {
    $catid = 0;
}

if (!nv_function_exists('nv_weblink_category')) {
    function nv_weblink_category()
    {
        global $global_array_cat, $module_info, $catid;

        $xtpl = new XTemplate('block_category.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
        $xtpl->assign('TEMPLATE', $module_info['template']);
        $xtpl->assign('BLOCK_ID', 'web' . rand(1, 1000));

        if (!empty($global_array_cat)) {
            $html = '';
            foreach ($global_array_cat as $cat) {
                if ($cat['parentid'] == 0) {
                    if (empty($html)) {
                        $html .= '<ul class="level-0">';
                    }

                    $licss = $cat['catid'] == $catid ? ' class="active"' : '';
                    $html .= '<li' . $licss . '>';
                    $html .= '<a title="' . $cat['title'] . '" href="' . $cat['link'] . '">' . $cat['title'] . '</a>';
                    $html .= nv_weblink_sub_category($cat['catid']);
                    $html .= '</li>';
                }
            }
            if (!empty($html)) {
                $html .= '</ul>';
            }

            $xtpl->assign('HTML_CONTENT', $html);
            $xtpl->parse('main');

            return $xtpl->text('main');
        }
    }

    function nv_weblink_sub_category($id, $level = 1)
    {
        global $global_array_cat, $catid;

        if (empty($id)) {
            return '';
        }
        $html = '';
        foreach ($global_array_cat as $cat) {
            if ($cat['parentid'] == $id) {
                if (empty($html)) {
                    $html .= '<ul class="level-' . $level . '">';
                }
                ++$level;
                $licss = $cat['catid'] == $catid ? ' class="active"' : '';
                $html .= '<li' . $licss . '>';
                $html .= '<a title="' . $cat['title'] . '" href="' . $cat['link'] . '">' . $cat['title'] . '</a>';
                $html .= nv_weblink_sub_category($cat['catid'], $level);
                $html .= '</li>';
            }
        }
        if (!empty($html)) {
            $html .= '</ul>';
        }

        return $html;
    }
}

$content = nv_weblink_category();
