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

$page_title = $lang_module['weblink_config'];

$submit = $nv_Request->get_string('submit', 'post');

$error = 0;

if (!empty($submit)) {
    $weblinks_config['homepage'] = $nv_Request->get_int('homepage', 'post');
    $weblinks_config['report_timeout'] = $nv_Request->get_int('report_timeout', 'post');
    $weblinks_config['timeout'] = $nv_Request->get_int('timeout', 'post');
    $weblinks_config['sort'] = ($nv_Request->get_string('sort', 'post') == 'asc') ? 'asc' : 'des';
    $weblinks_config['sortoption'] = nv_htmlspecialchars($nv_Request->get_string('sortoption', 'post', 'byid'));
    $weblinks_config['showlinkimage'] = $nv_Request->get_int('showlinkimage', 'post', 0);
    $weblinks_config['imgwidth'] = ($nv_Request->get_int('imgwidth', 'post') >= 0) ? $nv_Request->get_int('imgwidth', 'post') : 100;
    $weblinks_config['imgheight'] = ($nv_Request->get_int('imgheight', 'post') >= 0) ? $nv_Request->get_int('imgheight', 'post') : 75;
    $weblinks_config['per_page'] = ($nv_Request->get_int('per_page', 'post') >= 0) ? $nv_Request->get_int('per_page', 'post') : 10;
    $weblinks_config['new_icon'] = $nv_Request->get_int('new_icon', 'post');

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

$homepage_options = [1, 2];
foreach ($homepage_options as $option) {
    $xtpl->assign('HOMEPAGE', [
        'key' => $option,
        'title' => $lang_module['homepage_' . $option],
        'sel' => $weblinks_config['homepage'] == $option ? ' selected' : ''
    ]);
    $xtpl->parse('main.homepage_option');
}

for ($i = 1; $i <= 30; ++$i) {
    $xtpl->assign('TIMEOUT', [
        'key' => $i,
        'sel' => $weblinks_config['timeout'] == $i ? ' selected' : ''
    ]);
    $xtpl->parse('main.timeout_option');
}

for ($i = 1; $i <= 30; ++$i) {
    $y = $i * 5;
    $xtpl->assign('RTIMEOUT', [
        'key' => $y,
        'sel' => $weblinks_config['report_timeout'] == $y ? ' selected' : ''
    ]);
    $xtpl->parse('main.report_timeout_option');
}

for ($i = 0; $i <= 30; ++$i) {
    $xtpl->assign('NEWICON', [
        'key' => $i,
        'sel' => $weblinks_config['new_icon'] == $i ? ' selected' : ''
    ]);
    $xtpl->parse('main.new_icon');
}

$xtpl->parse('main');
$contents .= $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
