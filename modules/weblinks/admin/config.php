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

$page_title = $lang_module['weblink_config'];

$submit = $nv_Request->get_string('submit', 'post');

$error = 0;

if (! empty($submit)) {
    $weblinks_config['sort'] = ($nv_Request->get_string('sort', 'post') == 'asc') ? 'asc' : 'des';
    $weblinks_config['sortoption'] =  nv_htmlspecialchars($nv_Request->get_string('sortoption', 'post', 'byid'));
    $weblinks_config['showlinkimage'] = $nv_Request->get_int('showlinkimage', 'post', 0);
    $weblinks_config['imgwidth'] = ($nv_Request->get_int('imgwidth', 'post') >= 0) ? $nv_Request->get_int('imgwidth', 'post') : 100;
    $weblinks_config['imgheight'] = ($nv_Request->get_int('imgheight', 'post') >= 0) ? $nv_Request->get_int('imgheight', 'post') : 75;
    $weblinks_config['per_page'] = ($nv_Request->get_int('per_page', 'post') >= 0) ? $nv_Request->get_int('per_page', 'post') : 10;

    $sth = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_config SET value = :value WHERE name = :name');
    foreach ($weblinks_config as $name => $value) {
        $sth->bindParam(':name', $name, PDO::PARAM_STR);
        $sth->bindParam(':value', $value, PDO::PARAM_STR);
        $sth->execute();
    }

    $sth->closeCursor();
    $nv_Cache->delMod($module_name);
}

// Set data adv
$weblinks_config['asc'] = $weblinks_config['des'] = '';
$weblinks_config['sort'] = ($weblinks_config['sort'] == 'asc') ? $weblinks_config['asc'] = 'checked' : $weblinks_config['des'] = 'checked';
$weblinks_config['byid'] = ($weblinks_config['sortoption'] == 'byid') ? ' checked' : '';
$weblinks_config['byrand'] = ($weblinks_config['sortoption'] == 'byrand') ? ' checked' : '';
$weblinks_config['bytime'] = ($weblinks_config['sortoption'] == 'bytime') ? ' checked' : '';
$weblinks_config['byhit'] = ($weblinks_config['sortoption'] == 'byhit') ? ' checked' : '';
$weblinks_config['ck_showlinkimage'] = ($weblinks_config['showlinkimage'] == 1) ? ' checked' : '';

$xtpl = new XTemplate('config.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('OP', $op);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('DATA', $weblinks_config);

$xtpl->parse('main');
$contents .= $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
