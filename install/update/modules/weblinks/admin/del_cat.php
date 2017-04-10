<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 10 April 2017 17:00
 */

if (! defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$catid = $nv_Request->get_int('catid', 'post', 0);

$contents = 'NO_' . $catid;
list($catid, $parentid, $title) = $db->query('SELECT catid, parentid,title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat WHERE catid=' . intval($catid))->fetch(3);
if ($catid > 0) {
    $check_parentid = $db->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat WHERE parentid = ' . ( int )$catid)->fetchColumn();
    if (intval($check_parentid) > 0) {
        $contents = 'ERR_' . sprintf($lang_module['delcat_msg_cat'], $check_parentid);
    } else {
        $check_rows = $db->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE catid = ' . ( int )$catid)->fetchColumn();
        if (intval($check_rows) > 0) {
            $contents = 'ERR_' . sprintf($lang_module['delcat_msg_rows'], $check_rows);
        }
    }
    if ($contents == 'NO_' . $catid) {
        $query = 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat WHERE catid=' . $catid;
        if ($db->query($query)) {
            nv_fix_cat($parentid);
            $nv_Cache->delMod($module_name);
            $contents = 'OK_' . $catid;
        }
    }
    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['weblink_del_title'], $title, $admin_info['userid']);
}

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';
