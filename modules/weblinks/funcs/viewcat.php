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

global $global_array_cat;

$page_title = $global_array_cat[$catid]['title'];
$key_words = $global_array_cat[$catid]['keywords'];
$description = $global_array_cat[$catid]['description'];

$items = [];
$subcats = [];
foreach ($global_array_cat as $array_cat_i) {
    if ($array_cat_i['parentid'] == $catid) {
        $subcats[] = [
            'title' => $array_cat_i['title'],
            'link' => $array_cat_i['link'],
            'count_link' => $array_cat_i['count_link']
        ];
    }
}

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

$page_url = $base_url = $global_array_cat[$catid]['link'];

if ($page > 1) {
    $page_url .= '/page-' . $page;
}
$canonicalUrl = getCanonicalUrl($page_url, true, true);

$db->sqlreset()
    ->select('COUNT(*)')
    ->from(NV_PREFIXLANG . '_' . $module_data . '_rows')
    ->where('status=1 AND catid=' . (int) $catid);

$num_items = $db->query($db->sql())
    ->fetchColumn();

betweenURLs($page, ceil($num_items / $per_page), $base_url, '/page-', $prevPage, $nextPage);

$db->select('id, title, alias, url, urlimg, add_time, hits_total')
    ->order($orderby)
    ->limit($per_page)
    ->offset(($page - 1) * $per_page);

$result = $db->query($db->sql());

while ($row = $result->fetch()) {
    $row['cat'] = '';
    rowcat($catid, $row['cat']);
    $row['link'] = $global_array_cat[$catid]['link'] . '/' . $row['alias'] . '-' . $row['id'];
    $row['visit'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=visitlink-' . $row['alias'] . '-' . $row['id'];
    $row['report'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=reportlink-' . $row['alias'] . '-' . $row['id'];
    $items[] = $row;
}

$pages = nv_alias_page($page_title, $base_url, $num_items, $per_page, $page);
$contents = call_user_func('viewcat', $subcats, $global_array_cat[$catid], $items, $pages);

if ($page > 1) {
    $page_title .= ' ' . NV_TITLEBAR_DEFIS . ' ' . $lang_global['page'] . ' ' . $page;
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
