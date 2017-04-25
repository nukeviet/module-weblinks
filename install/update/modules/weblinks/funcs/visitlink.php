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

global $timeout;

$id = 0;
$temp = explode('-', $array_op[0]);
$id =  end($temp) ;
$sql = 'SELECT url FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id=' . intval($id) . ' LIMIT 0,1';
$result = $db->query($sql);
$row = $result->fetch();

if ($row['url'] != '') {
    if (! preg_match('/http:\/\//i', $row['url'])) {
        $url = 'http://' . $url;
    }

    if (isset($_COOKIE['timeout']) and $_COOKIE['timeout'] == $id) {
        $contents .= sprintf($lang_module['notimeout'], $timeout);
    } else {
        setcookie('timeout', $id, time() + $timeout * 60);
        $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET hits_total=hits_total+1 WHERE id=' . intval($id));
    }

    Header('Location: ' . $row['url']);
    exit();
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
