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

$id = $nv_Request->get_array('idcheck', 'post');

nv_insert_logs(NV_LANG_DATA, $module_name, 'log_del_broken', 'id ' . $id, $admin_info['userid']);

foreach ($id as $value) {
    $sql = 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_report WHERE id=' . $value;
    $db->query($sql);
}

header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=brokenlink');
exit();
