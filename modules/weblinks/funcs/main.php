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

$page_title = $module_info['site_title'];
$key_words = $module_info['keywords'];
$mod_title = isset($lang_module['main_title']) ? $lang_module['main_title'] : $module_info['custom_title'];

if ($weblinks_config['sortoption'] == 'byhit') {
    $orderby = 'hits_total';
} elseif ($weblinks_config['sortoption'] == 'byid') {
    $orderby = 'id';
} elseif ($weblinks_config['sortoption'] == 'bytime') {
    $orderby = 'add_time';
} else {
    $orderby = 'rand()';
}

$orderby .= ($weblinks_config['sort'] == 'des') ? ' DESC' : ' ASC';

$page_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;

if ((int) $weblinks_config['homepage'] == 2) {
    $canonicalUrl = getCanonicalUrl($page_url, true, true);

    $array_cat = [];

    foreach ($global_array_cat as $catid_i => $array_cat_i) {
        if ($array_cat_i['inhome']) {
            if ($array_cat_i['parentid'] == 0) {
                $array_cat[$catid_i] = [
                    'title' => $array_cat_i['title'],
                    'link' => $array_cat_i['link'],
                    'count_link' => $array_cat_i['count_link'],
                    'subcat' => [],
                    'contents' => []
                ];

                $sql = 'SELECT id, title, alias, url, urlimg, add_time, hits_total FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE status = 1 AND catid =' . $catid_i . ' ORDER BY ' . $orderby . ' LIMIT 0,3';
                $result = $db->query($sql);
                while ($row = $result->fetch()) {
                    $row['cat'] = '';
                    rowcat($catid_i, $row['cat']);
                    $row['link'] = $global_array_cat[$catid_i]['link'] . '/' . $row['alias'] . '-' . $row['id'];
                    $row['linkvi'] = $page_url . '&amp;' . NV_OP_VARIABLE . '=visitlink-' . $row['alias'] . '-' . $row['id'];
                    $array_cat[$catid_i]['contents'][] = $row;
                }
            } else {
                $parentid = $array_cat_i['parentid'];

                if (isset($array_cat[$parentid])) {
                    if (!isset($array_cat[$parentid]['subcat'])) {
                        $array_cat[$parentid]['subcat'] = [];
                    }
                    $array_cat[$parentid]['subcat'][] = [
                        'title' => $global_array_cat[$catid_i]['title'],
                        'link' => $global_array_cat[$catid_i]['link'],
                        'count_link' => $global_array_cat[$catid_i]['count_link']
                    ];
                }
            }
        }
    }

    $contents = call_user_func('main_theme', $array_cat);
} else {
    $base_url = $page_url;
    if ($page > 1) {
        $page_url .= '/page-' . $page;
    }
    $canonicalUrl = getCanonicalUrl($page_url, true, true);

    $db->sqlreset()
        ->select('COUNT(*)')
        ->from(NV_PREFIXLANG . '_' . $module_data . '_rows')
        ->where('status=1');

    $num_items = $db->query($db->sql())
        ->fetchColumn();

    betweenURLs($page, ceil($num_items / $per_page), $base_url, '/page-', $prevPage, $nextPage);

    $db->select('id, catid, title, alias, url, urlimg, add_time, hits_total')
        ->order($orderby)
        ->limit($per_page)
        ->offset(($page - 1) * $per_page);

    $result = $db->query($db->sql());

    $items = [];
    while ($row = $result->fetch()) {
        $row['cat'] = '';
        rowcat($row['catid'], $row['cat']);
        $row['link'] = $global_array_cat[$row['catid']]['link'] . '/' . $row['alias'] . '-' . $row['id'];
        $row['visit'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=visitlink-' . $row['alias'] . '-' . $row['id'];
        $row['report'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=reportlink-' . $row['alias'] . '-' . $row['id'];
        $items[] = $row;
    }

    $pages = nv_alias_page($page_title, $base_url, $num_items, $per_page, $page);
    $contents = call_user_func('main_list_theme', $items, $pages);

    if ($page > 1) {
        $page_title .= ' ' . NV_TITLEBAR_DEFIS . ' ' . $lang_global['page'] . ' ' . $page;
    }
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
