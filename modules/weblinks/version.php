<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 10 April 2017 17:00
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE')) {
    die('Stop!!!');
}

$module_version = array(
    'name' => 'weblinks',
    'modfuncs' => 'main,viewcat,detail',
    'is_sysmod' => 0,
    'virtual' => 1,
    'version' => '4.3.00',
    'date' => 'Tue, 14 Nov 2017 07:23:13 GMT',
    'author' => 'VINADES (contact@vinades.vn)',
    'note' => '',
    'uploads_dir' => array(
        $module_name,
        $module_name . '/cat'
    )
);