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

$submit = $nv_Request->get_string('submit', 'post');
$report_id = $nv_Request->get_int('report_id', 'post');
$id = ($id == 0) ? $report_id : $id;

$sql = 'SELECT title, alias FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id=' . (int) $id;
$result = $db->query($sql);
$row = $result->fetch();
unset($sql, $result);

if (!empty($row)) {
    $row['error'] = '';
    $row['action'] = nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=reportlink-' . $row['alias'] . '-' . $id, true);
    $row['id'] = $id;

    $sql = 'SELECT report_time FROM ' . NV_PREFIXLANG . '_' . $module_data . '_report WHERE id=' . $id . ' AND report_ip=' . $db->quote($client_info['ip']) . ' ORDER BY report_time DESC LIMIT 1';
    $last_report = (int) ($db->query($sql)->fetchColumn());
    $pass = !empty($weblinks_config['report_timeout']) ? ((int) $weblinks_config['report_timeout'] * 60) : 3600;
    $pass = $pass - NV_CURRENTTIME + $last_report;
    if ($pass > 0) {
        nv_htmlOutput(sprintf($lang_module['report_wait'], nv_convertfromSec($pass)));
    }

    if ($submit and $report_id) {
        $report = $nv_Request->get_int('report', 'post', 0);
        if ($report != 1 and $report != 2) {
            $report = 0;
        }
        $report_note = empty($report) ? nv_substr($nv_Request->get_title('report_note', 'post', '', 1), 0, 255) : '';

        if ($report == 0 and 10 >= nv_strlen($report_note)) {
            nv_jsonOutput([
                'status' => 'error',
                'mess' => $lang_module['error_word_min']
            ]);
        }

        $isExists = false;
        if (!empty($report)) {
            $sql = 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_report WHERE id=' . $report_id . ' AND type=' . $report;
            $isExists = (bool) ($db->query($sql)->fetchColumn());
        }

        if (!$isExists) {
            $report_note = nv_nl2br($report_note);

            $sql = 'INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_report SET
				id=' . $report_id . ',
				type=' . $report . ',
				report_time=' . NV_CURRENTTIME . ',
				report_ip=' . $db->quote($client_info['ip']) . ',
				report_browse_key=' . $db->quote($client_info['browser']['key']) . ',
				report_browse_name=' . $db->quote($client_info['browser']['name']) . ',
				report_os_key=' . $db->quote($client_info['client_os']['key']) . ',
				report_os_name=' . $db->quote($client_info['client_os']['name']) . ',
				report_note=' . $db->quote($report_note);

            $db->query($sql);
        }

        nv_jsonOutput([
            'status' => 'success',
            'mess' => $lang_module['report_success']
        ]);
    }

    $contents = call_user_func('report', $row);
    nv_htmlOutput($contents);
} else {
    trigger_error("you don't permission to access!!!", 256);
}
