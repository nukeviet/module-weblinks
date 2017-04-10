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

$key_words = $module_info['keywords'];
$mod_title = isset($lang_module['main_title']) ? $lang_module['main_title'] : $module_info['custom_title'];

global $global_array_cat;

$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id = ' . intval($id);
$result = $db->query($sql);
$row = $result->fetch();

$page_title = $row['title'] . ' - ' . $global_array_cat[$row['catid']]['title'];

$row['visit'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=visitlink-' . $row['alias'] . '-' . $row['id'];
$row['report'] = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=reportlink-' . $row['alias'] . '-' . $row['id'], true);

if (! empty($row['urlimg'])) {
    $row['urlimg'] = NV_BASE_SITEURL . NV_ASSETS_DIR . '/' . $row['urlimg'];
} else {
    $row['urlimg'] = NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/images/' . $module_file . '/no_image.gif';
}
$contents = call_user_func('detail', $row);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
