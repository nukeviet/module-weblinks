<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 10 April 2017 17:00
 */

if (! defined('NV_IS_MOD_SEARCH')) {
    die('Stop!!!');
}

$db->sqlreset()->select('COUNT(*)')->from(NV_PREFIXLANG . '_' . $m_values['module_data'] . '_rows')->where('(' . nv_like_logic('title', $dbkeyword, $logic) . ' OR ' . nv_like_logic('url', $dbkeyword, $logic) . ' OR ' . nv_like_logic('description', $dbkeyword, $logic) . ')');

$num_items = $db->query($db->sql())->fetchColumn();

$db->select('id, catid, title, alias, description')->limit($limit)->offset(($page - 1) * $limit);
$result = $db->query($db->sql());

if ($num_items) {
    $array_cat_url = array();
    $array_cat_url[0] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $m_values['module_name'] . '&amp;' . NV_OP_VARIABLE . '=other';

    $sql_cat = 'SELECT catid, alias FROM ' . NV_PREFIXLANG . '_' . $m_values['module_data'] . '_cat';
    $re_cat = $db->query($sql_cat);
    while (list($catid, $alias) = $re_cat->fetch(3)) {
        $array_cat_url[$catid] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $m_values['module_name'] . '&amp;' . NV_OP_VARIABLE . '=' . $alias;
    }

    while (list($id, $catid, $tilterow, $alias, $content) = $result->fetch(3)) {
        $result_array[] = array(
            'link' => $array_cat_url[$catid] . '/' . $alias,
            'title' => BoldKeywordInStr($tilterow, $key, $logic),
            'content' => BoldKeywordInStr($content, $key, $logic) );
    }
}
