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
        global $global_array_cat, $module_file, $module_info;

        $xtpl = new XTemplate('block_category.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
        $xtpl->assign('TEMPLATE', $module_info['template']);
        $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
        $xtpl->assign('BLOCK_ID', 'web' . rand(1, 1000));

        if (!empty($global_array_cat)) {
            $title_length = 20;
            $html = '';

            foreach ($global_array_cat as $cat) {
                if ($cat['parentid'] == 0) {
                    $html .= '<li>';
                    $html .= '<a title="' . $cat['title'] . '" href="' . $cat['link'] . '">' . nv_clean60($cat['title'], $title_length) . '</a>';
                    $html .= nv_weblink_sub_category($cat['catid'], $title_length);
                    $html .= '</li>';
                }
            }

            $xtpl->assign('HTML_CONTENT', $html);
            $xtpl->parse('main');

            return $xtpl->text('main');
        }
    }

    function nv_weblink_sub_category($catid, $title_length)
    {
        global $global_array_cat;

        if (empty($catid)) {
            return '';
        }
        $html = '<ul>';
        foreach ($global_array_cat as $cat) {
            if ($cat['parentid'] == $catid) {
                $html .= '<li>';
                $html .= '<a title="' . $global_array_cat[$cat['catid']]['title'] . '" href="' . $global_array_cat[$cat['catid']]['link'] . '">' . nv_clean60($global_array_cat[$cat['catid']]['title'], $title_length) . '</a>';
                $html .= nv_weblink_sub_category($cat['catid'], $title_length);
                $html .= '</li>';
            }
        }
        $html .= '</ul>';

        return $html;
    }
}

$content = nv_weblink_category();
