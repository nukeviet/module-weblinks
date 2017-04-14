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

$id = $nv_Request->get_array('idcheck', 'post');

nv_insert_logs(NV_LANG_DATA, $module_name, 'log_del_broken', 'id ' . $id, $admin_info['userid']);

foreach ($id as $value) {
    $sql = 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_report WHERE id=' . $value;
    $db->query($sql);
}

Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=brokenlink');
exit();
