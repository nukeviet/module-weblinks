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

$key_words = $module_info['keywords'];
$mod_title = isset($lang_module['main_title']) ? $lang_module['main_title'] : $module_info['custom_title'];

global $global_array_cat;

$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id = ' . (int) $id;
$result = $db->query($sql);
$row = $result->fetch();

if (empty($row)) {
    nv_redirect_location(nv_url_rewrite($global_array_cat[$catid]['link'], true));
}

$page_url = $global_array_cat[$catid]['link'] . '/' . $row['alias'] . '-' . $row['id'];
$canonicalUrl = getCanonicalUrl($page_url, true, true);

$page_title = $row['title'] . ' - ' . $global_array_cat[$row['catid']]['title'];

$row['cat'] = '';
rowcat($row['catid'], $row['cat']);
$row['visit'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=visitlink-' . $row['alias'] . '-' . $row['id'];
$row['report'] = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=reportlink-' . $row['alias'] . '-' . $row['id'], true);

if (!empty($row['urlimg'])) {
    if (!nv_is_url($row['urlimg'])) {
        $row['urlimg'] = NV_BASE_SITEURL . NV_ASSETS_DIR . '/' . $row['urlimg'];
    }
}

$contents = call_user_func('detail', $row);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
