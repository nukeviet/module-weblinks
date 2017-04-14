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

$submit = $nv_Request->get_string('submit', 'post');
$report_id = $nv_Request->get_int('report_id', 'post');
$id = ($id == 0) ? $report_id : $id;

$sql = 'SELECT title, alias FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id=' . intval($id);
$result = $db->query($sql);
$row = $result->fetch();
unset($sql, $result);

if (!empty($row)) {
    $row['error'] = '';
    $row['action'] = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=reportlink-' . $row['alias'] . '-' . $id, true);
    $row['id'] = $id;

    $check = false;
    if ($submit and $report_id) {
        $sql = 'SELECT type FROM ' . NV_PREFIXLANG . '_' . $module_data . '_report WHERE id=' . intval($report_id);
        $result = $db->query($sql);
        $rows = $result->fetch();

        $report = $nv_Request->get_int('report', 'post');
        $report_note = nv_substr($nv_Request->get_title('report_note', 'post', '', 1), 0, 255);

        $row['report_note'] = $report_note;
        if ($report == 0 and empty($report_note)) {
            $row['error'] = $lang_module['error'];
        } elseif (! empty($report_note) and ! isset($report_note{9})) {
            $row['error'] = $lang_module['error_word_min'];
        } elseif ($rows['type'] == $report) {
            $check = true;
        } else {
            $report_note = nv_nl2br($report_note);

            $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_report SET
				id=' . $report_id . ',
				type=' . $report . ',
				report_time=' . NV_CURRENTTIME . ',
				report_userid=0,
				report_ip=' . $db->quote($client_info['ip']) . ',
				report_browse_key=' . $db->quote($client_info['browser']['key']) . ',
				report_browse_name=' . $db->quote($client_info['browser']['name']) . ',
				report_os_key=' . $db->quote($client_info['client_os']['key']) . ',
				report_os_name=' . $db->quote($client_info['client_os']['name']) . ',
				report_note=' . $db->quote($report_note);

            $check = $db->query($sql);
        }
    }

    $contents = call_user_func('report', $row, $check);
    
    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme($contents, 0);
    include NV_ROOTDIR . '/includes/footer.php';
} else {
    trigger_error("you don't permission to access!!!", 256);
}
