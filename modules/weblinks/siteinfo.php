<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_SITEINFO')) {
    exit('Stop!!!');
}

$lang_siteinfo = nv_get_lang_module($mod);

// Tong so link
$number = $db->query('SELECT COUNT(*) as number FROM ' . NV_PREFIXLANG . '_' . $mod_data . '_rows where status= 1')->fetchColumn();
if ($number > 0) {
    $siteinfo[] = [
        'key' => $lang_siteinfo['siteinfo_numberlink'],
        'value' => $number
    ];
}

// So bao cao link hong
$number = $db->query('SELECT COUNT(*) as number FROM ' . NV_PREFIXLANG . '_' . $mod_data . '_report')->fetchColumn();
if ($number > 0) {
    $pendinginfo[] = [
        'key' => $lang_siteinfo['siteinfo_error'],
        'value' => $number,
        'link' => NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=' . $mod . '&amp;' . NV_OP_VARIABLE . '=brokenlink'
    ];
}
