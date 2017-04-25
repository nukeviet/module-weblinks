<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 10 April 2017 17:00
 */

if (! defined('NV_IS_MOD_WEBLINKS')) {
    die('Stop!!!');
}

global $global_array_cat;

$page_title = $global_array_cat[$catid]['title'];
$key_words = $global_array_cat[$catid]['keywords'];
$description = $global_array_cat[$catid]['description'];

$items = array();
$array_subcat = array();
$array_cat = array();
foreach ($global_array_cat as $array_cat_i) {
    if ($array_cat_i['parentid'] == $catid) {
        $array_subcat[] = array(
            'title' => $array_cat_i['title'],
            'link' => $array_cat_i['link'],
            'count_link' => $array_cat_i['count_link']
        );
    }
}

$array_cat[] = array(
    'title' => $global_array_cat[$catid]['title'],
    'link' => $global_array_cat[$catid]['link'],
    'description' => $global_array_cat[$catid]['description']
);

$sort = ($weblinks_config['sort'] == 'des') ? 'desc' : 'asc';
if ($weblinks_config['sortoption'] == 'byhit') {
    $orderby = 'hits_total ';
} elseif ($weblinks_config['sortoption'] == 'byid') {
    $orderby = 'id ';
} elseif ($weblinks_config['sortoption'] == 'bytime') {
    $orderby = 'add_time ';
} else {
    $orderby = 'rand() ';
}
$base_url = $global_array_cat[$catid]['link'];

$db->sqlreset()
    ->select('COUNT(*)')
    ->from(NV_PREFIXLANG . '_' . $module_data . '_rows')
    ->where('status=1 AND catid=' . intval($catid));
    
$num_items = $db->query($db->sql())->fetchColumn();

$db->select('id, author, title, alias, url, urlimg, add_time, description, hits_total')
    ->order($orderby . $sort)
    ->limit($per_page)
    ->offset(($page - 1) * $per_page);
    
$result = $db->query($db->sql());

while ($row = $result->fetch()) {
    $author = explode('|', $row['author']);

    if ($author[0] == 1) {
        $sql1 = 'SELECT * FROM ' . NV_AUTHORS_GLOBALTABLE . ' WHERE admin_id=' . $author[1] . '';
        $result1 = $db->query($sql1);
        $row1 = $result1->fetch();
        $row['author'] = $row1;
    }

    $row['link'] = $global_array_cat[$catid]['link'] . '/' . $row['alias'] . '-' . $row['id'];
    $row['visit'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=visitlink-' . $row['alias'] . '-' . $row['id'];
    $row['report'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=reportlink-' . $row['alias'] . '-' . $row['id'];
    $items[] = $row;
}

$contents = call_user_func('viewcat', $array_subcat, $array_cat, $items);
$contents .= nv_alias_page($page_title, $base_url, $num_items, $per_page, $page);

if ($page > 1) {
    $page_title .= ' ' . NV_TITLEBAR_DEFIS . ' ' . $lang_global['page'] . ' ' . $page;
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
