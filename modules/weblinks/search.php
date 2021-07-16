<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_SEARCH')) {
    exit('Stop!!!');
}

$db->sqlreset()
    ->select('COUNT(*)')
    ->from(NV_PREFIXLANG . '_' . $m_values['module_data'] . '_rows')
    ->where('(' . nv_like_logic('title', $dbkeyword, $logic) . ' OR ' . nv_like_logic('url', $dbkeyword, $logic) . ' OR ' . nv_like_logic('description', $dbkeyword, $logic) . ')');

$num_items = $db->query($db->sql())
    ->fetchColumn();

$db->select('id, catid, title, alias, url, description')
    ->limit($limit)
    ->offset(($page - 1) * $limit);
$result = $db->query($db->sql());

if ($num_items) {
    $array_cat_url = [];
    $array_cat_url[0] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $m_values['module_name'] . '&amp;' . NV_OP_VARIABLE . '=other';

    $sql_cat = 'SELECT catid, alias FROM ' . NV_PREFIXLANG . '_' . $m_values['module_data'] . '_cat';
    $re_cat = $db->query($sql_cat);
    while (list($catid, $alias) = $re_cat->fetch(3)) {
        $array_cat_url[$catid] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $m_values['module_name'] . '&amp;' . NV_OP_VARIABLE . '=' . $alias;
    }

    while (list($id, $catid, $tilterow, $alias, $url, $content) = $result->fetch(3)) {
        $result_array[] = [
            'link' => $array_cat_url[$catid] . '/' . $alias,
            'title' => BoldKeywordInStr($tilterow, $key, $logic),
            'content' => BoldKeywordInStr($url, $key, $logic) . '<br/>' . BoldKeywordInStr($content, $key, $logic)
        ];
    }
}
