<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_SYSTEM')) {
    exit('Stop!!!');
}

define('NV_IS_MOD_WEBLINKS', true);

require_once NV_ROOTDIR . '/modules/' . $module_file . '/global.functions.php';

/**
 * adminlink()
 *
 * @param mixed $id
 * @return
 */
function adminlink($id)
{
    global $lang_module, $module_name;
    $link = '<em class="fa fa-trash-o fa-lg">&nbsp;</em><a href="javascript:void(0);" onclick="nv_del_rows(\'' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=del_link\', ' . $id . ');">' . $lang_module['delete'] . '</a>&nbsp;&nbsp;';
    $link .= '<em class="fa fa-edit fa-lg">&nbsp;</em><a href="' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=content&amp;id=' . $id . '">' . $lang_module['edit'] . '</a>';

    return $link;
}

/**
 * rowcat()
 *
 * @param int    $catid
 * @param string $string
 */
function rowcat($catid, &$string)
{
    global $global_array_cat;

    if (isset($global_array_cat[$catid])) {
        $_string = '<li><a href="' . $global_array_cat[$catid]['link'] . '">' . $global_array_cat[$catid]['title'] . '</a></li>';
        $string = $_string . $string;

        if (!empty($global_array_cat[$catid]['parentid'])) {
            rowcat($global_array_cat[$catid]['parentid'], $string);
        }
    }
}

$catid = 0;
$parentid = 0;
$set_viewcat = '';
$alias_cat_url = isset($array_op[0]) ? $array_op[0] : '';
$array_mod_title = [];
$global_array_cat = [];

// Xac dinh RSS
if ($module_info['rss']) {
    $rss[] = [
        'title' => $module_info['custom_title'],
        'src' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['rss']
    ];
}

$sql = 'SELECT catid, parentid, title, description, catimage, alias, keywords, inhome  FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat ORDER BY parentid, weight';
$result = $db->query($sql);

while (list($catid_i, $parentid_i, $title_i, $description_i, $catimage_i, $alias_i, $keywords_i, $inhome_i) = $result->fetch(3)) {
    $link_i = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $alias_i;

    $sql1 = 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE catid = ' . $catid_i;
    $result1 = $db->query($sql1);
    $count_link = $result1->fetchColumn();

    $global_array_cat[$catid_i] = [
        'catid' => (int) $catid_i,
        'parentid' => (int) $parentid_i,
        'title' => $title_i,
        'alias' => $alias_i,
        'link' => $link_i,
        'description' => $description_i,
        'keywords' => $keywords_i,
        'catimage' => $catimage_i,
        'count_link' => (int) $count_link,
        'inhome' => (bool) $inhome_i
    ];

    if ($alias_cat_url == $alias_i) {
        $catid = $catid_i;
        $parentid = $parentid_i;
    }

    //Xac dinh RSS
    if ($module_info['rss']) {
        $rss[] = [
            'title' => $module_info['custom_title'] . ' - ' . $title_i,
            'src' => NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $module_info['alias']['rss'] . '/' . $alias_i
        ];
    }
}
unset($sql, $result);

$count_op = sizeof($array_op);
$per_page = $weblinks_config['per_page'];
$page = 1;

if (!empty($array_op)) {
    if ($catid == 0) {
        if (substr($array_op[0], 0, 10) == 'visitlink-') {
            $op = 'visitlink';
            $temp = explode('-', $array_op[0]);
            $id = (int) (end($temp));
        } elseif (substr($array_op[0], 0, 11) == 'reportlink-') {
            $op = 'reportlink';
            $temp = explode('-', $array_op[0]);
            $id = (int) (end($temp));
        } elseif (substr($array_op[0], 0, 5) == 'page-') {
            $op = 'main';
            $page = (int) (substr($array_op[0], 5));
        }
    } else {
        $op = 'main';
        if ($count_op == 1 or substr($array_op[1], 0, 5) == 'page-') {
            $op = 'viewcat';
            if ($count_op > 1) {
                $page = (int) (substr($array_op[1], 5));
            }
        } elseif ($count_op == 2) {
            $array_page = explode('-', $array_op[1]);
            $id = (int) (end($array_page));
            $alias_url = str_replace('-' . $id, '', $array_op[1]);
            if ($id > 0 and $alias_url != '') {
                $op = 'detail';
            }
        }
        $parentid = $catid;
        while ($parentid > 0) {
            $array_cat_i = $global_array_cat[$parentid];
            $array_mod_title[] = [
                'catid' => $parentid,
                'title' => $array_cat_i['title'],
                'link' => $array_cat_i['link']
            ];
            $parentid = $array_cat_i['parentid'];
        }
        krsort($array_mod_title, SORT_NUMERIC);
    }
}
