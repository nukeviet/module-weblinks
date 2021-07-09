<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

$catid = $nv_Request->get_int('catid', 'post', 0);

$contents = 'NO_' . $catid;
list($catid, $parentid, $title) = $db->query('SELECT catid, parentid,title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat WHERE catid=' . (int) $catid)->fetch(3);
if ($catid > 0) {
    $check_parentid = $db->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat WHERE parentid = ' . (int) $catid)->fetchColumn();
    if ((int) $check_parentid > 0) {
        $contents = 'ERR_' . sprintf($lang_module['delcat_msg_cat'], $check_parentid);
    } else {
        $check_rows = $db->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE catid = ' . (int) $catid)->fetchColumn();
        if ((int) $check_rows > 0) {
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
