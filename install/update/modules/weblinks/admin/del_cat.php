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

list($catid, $parentid, $title) = $db->query('SELECT catid, parentid,title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat WHERE catid=' . $catid)->fetch(3);
if (empty($catid)) {
    nv_jsonOutput([
        'status' => 'error',
        'mess' => 'NO_' . $catid
    ]);
}

$check_parentid = $db->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat WHERE parentid = ' . $catid)->fetchColumn();
if ((int) $check_parentid > 0) {
    nv_jsonOutput([
        'status' => 'error',
        'mess' => sprintf($lang_module['delcat_msg_cat'], $check_parentid)
    ]);
}

$check_rows = $db->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE catid = ' . $catid)->fetchColumn();
if ((int) $check_rows > 0) {
    nv_jsonOutput([
        'status' => 'error',
        'mess' => sprintf($lang_module['delcat_msg_rows'], $check_rows)
    ]);
}

$db->query('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat WHERE catid=' . $catid);
nv_fix_cat($parentid);
$nv_Cache->delMod($module_name);
nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['weblink_del_title'], $title, $admin_info['userid']);

nv_jsonOutput([
    'status' => 'OK'
]);
