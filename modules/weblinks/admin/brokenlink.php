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

if ($nv_Request->isset_request('delBroken', 'post')) {
    $id = $nv_Request->get_typed_array('idcheck', 'post', 'int', []);
    if (!empty($id)) {
        $id = array_unique($id);
        $id = implode(',', $id);
        nv_insert_logs(NV_LANG_DATA, $module_name, 'log_del_broken', 'id ' . $id, $admin_info['userid']);

        $sql = 'DELETE FROM ' . NV_PREFIXLANG . '_' . $module_data . '_report WHERE id IN (' . $id . ')';
        $db->query($sql);
        exit('OK');
    }
}

$page_title = $lang_module['weblink_link_broken'];

$xtpl = new XTemplate('link_broken.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name;

$per_page = 10;

$page = $nv_Request->get_int('page', 'get', 0);

$all_page = $db->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows a INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_report b ON a.id=b.id')->fetchColumn();

$sql = 'SELECT a.url, a.title, b.type, b.report_time, b.report_ip, b.report_browse_key, b.report_browse_name, b.report_os_key, b.report_os_name, b.report_note, a.id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows a INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_report b ON a.id=b.id ORDER BY b.report_time DESC LIMIT ' . $page . ', ' . $per_page;

if ($all_page > 0) {
    $xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);

    $result = $db->query($sql);
    $tt = 0;
    while ($row = $result->fetch()) {
        ++$tt;
        $xtpl->assign('ROW', [
            'id' => $row['id'],
            'title' => $row['title'],
            'url' => $row['url'],
            'type' => $row['type'] == 1 ? $lang_module['weblink_link_broken_die'] : ($row['type'] == 2 ? $lang_module['weblink_link_broken_bad'] : $lang_module['weblink_link_broken_other']),
            'report_title' => sprintf($lang_module['weblink_link_broken2'], $row['title']),
            'report_note' => $row['report_note'],
            'report_time_format' => nv_date('d/m/Y H:i', $row['report_time']),
            'report_ip' => $row['report_ip'],
            'report_browse_name' => $row['report_browse_name'],
            'report_os_name' => $row['report_os_name'],
            'tt' => $tt,
            'url_edit' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=content&amp;id=' . $row['id']
        ]);

        $xtpl->parse('main.data.loop');
    }

    $xtpl->parse('main.data');
} else {
    $xtpl->parse('main.empty');
}

$generate_page = nv_generate_page($base_url, $all_page, $per_page, $page);
if (!empty($generate_page)) {
    $xtpl->assign('GENERATE_PAGE', $generate_page);
    $xtpl->parse('main.generate_page');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
