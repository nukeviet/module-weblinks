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

$page_title = $lang_module['weblink_link_broken'];

$xtpl = new XTemplate('link_broken.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);

$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name;

$per_page = 10;

$page = $nv_Request->get_int('page', 'get', 0);

$all_page = $db->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows a INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_report b ON a.id=b.id')->fetchColumn();

$sql = 'SELECT a.url, a.title, b.type, a.id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows a INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_report b ON a.id=b.id LIMIT ' . $page . ', ' . $per_page;

// GROUP BY a.url

if ($all_page > 0) {
    $xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=delbroken');

    $result = $db->query($sql);
    while ($row = $result->fetch()) {
        $xtpl->assign('ROW', array(
            'id' => $row['id'],
            'title' => $row['title'],
            'url' => $row['url'],
            'type' => $row['type'] == 1 ? $lang_module['weblink_link_broken_die'] : $lang_module['weblink_link_broken_bad'],
            'url_edit' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=content&amp;id=' . $row['id'] ));

        $xtpl->parse('main.data.loop');
    }

    $xtpl->parse('main.data');
} else {
    $xtpl->parse('main.empty');
}

$generate_page = nv_generate_page($base_url, $all_page, $per_page, $page);
if (! empty($generate_page)) {
    $xtpl->assign('GENERATE_PAGE', $generate_page);
    $xtpl->parse('main.generate_page');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
