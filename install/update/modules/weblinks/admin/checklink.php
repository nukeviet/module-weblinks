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

$page_title = $lang_module['weblink_checkalivelink'];

$submit = $nv_Request->get_string('submit', 'post');

if ($submit) {
    $nv_Request->set_Cookie('ok', 1);
}

$xtpl = new XTemplate('checklink.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);

if ($nv_Request->isset_request('ok', 'cookie')) {
    include NV_ROOTDIR . '/modules/' . $module_file . '/checkurl.class.php';
    $check = new CheckUrl();

    $page_title = $lang_module['weblink_checkalivelink'];

    $numcat = $db->query('SELECT id FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows ')->rowCount();
    $base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=checklink';
    $all_page = ($numcat > 1) ? $numcat : 1;
    $per_page = 5;
    $page = $nv_Request->get_int('page', 'get', 0);

    $sql = 'SELECT url FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows LIMIT ' . $page . ', ' . $per_page;
    $result = $db->query($sql);

    while ($row = $result->fetch()) {
        $xtpl->assign('URL', $row['url']);

        if ($check->check_curl($row['url'])) {
            $xtpl->parse('main.check.loop.ok');
        } else {
            $xtpl->parse('main.check.loop.error');
        }

        $xtpl->parse('main.check.loop');
    }

    $generate_page = nv_generate_page($base_url, $all_page, $per_page, $page);
    if (! empty($generate_page)) {
        $xtpl->assign('GENERATE_PAGE', $generate_page);
        $xtpl->parse('main.check.generate_page');
    }

    $xtpl->parse('main.check');
} else {
    $xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=checklink');
    $xtpl->parse('main.form');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
