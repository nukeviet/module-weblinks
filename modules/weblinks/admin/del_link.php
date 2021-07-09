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

if (!defined('NV_IS_AJAX')) {
    exit('Wrong URL');
}

$id = $nv_Request->get_int('id', 'post', 0);

$listid = $nv_Request->get_string('listid', 'post', '');

if ($listid != '') {
    $del_array = array_map('intval', explode(',', $listid));
} elseif ($id) {
    $del_array = [
        $id
    ];
}

if (!empty($del_array)) {
    $a = 0;
    foreach ($del_array as $id) {
        list($id, $title, $urlimg) = $db->query('SELECT id, title, urlimg  FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id=' . $id)->fetch(3);
        if ($id > 0) {
            nv_insert_logs(NV_LANG_DATA, $module_name, 'log_del_rows', $title, $admin_info['userid']);

            $db->exec('DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id=' . $id);

            if (!empty($urlimg)) {
                @unlink(NV_ROOTDIR . '/' . NV_UPLOADS_DIR . '/' . $urlimg);
                $_did = $db->query('SELECT did FROM ' . NV_UPLOAD_GLOBALTABLE . '_dir WHERE dirname=' . $db->quote(dirname(NV_UPLOADS_DIR . '/' . $urlimg)))
                    ->fetchColumn();
                $db->query('DELETE FROM ' . NV_UPLOAD_GLOBALTABLE . '_file WHERE did = ' . $_did . ' AND title=' . $db->quote(basename($urlimg)));
            }
            ++$a;
        }
        if ($a > 0) {
            $nv_Cache->delMod($module_name);
            $contents = 'OK_' . $lang_module['weblink_del_success'];
        } else {
            $contents = 'NO_' . $lang_module['weblink_del_error'];
        }
    }
} else {
    $contents = 'NO_' . $lang_module['weblink_del_err'];
}

include NV_ROOTDIR . '/includes/header.php';
echo $contents;
include NV_ROOTDIR . '/includes/footer.php';
