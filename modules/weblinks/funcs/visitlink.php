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

global $timeout;

$id = 0;
$temp = explode('-', $array_op[0]);
$id = end($temp);
$sql = 'SELECT url FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id=' . (int) $id . ' LIMIT 0,1';
$result = $db->query($sql);
$row = $result->fetch();

if ($row['url'] != '') {
    if (!preg_match('/http:\/\//i', $row['url'])) {
        $url = 'http://' . $url;
    }

    if (isset($_COOKIE['timeout']) and $_COOKIE['timeout'] == $id) {
        $contents .= sprintf($lang_module['notimeout'], $timeout);
    } else {
        setcookie('timeout', $id, time() + $timeout * 60);
        $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET hits_total=hits_total+1 WHERE id=' . (int) $id);
    }

    header('Location: ' . $row['url']);
    exit();
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
