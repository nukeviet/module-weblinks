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

$id = 0;
$temp = explode('-', $array_op[0]);
$id = (int) (end($temp));
$sql = 'SELECT url FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id=' . $id . ' LIMIT 0,1';
$result = $db->query($sql);
$row = $result->fetch();
$contents = '';

if (!empty($row['url'])) {
    $difftimeout = !empty($weblinks_config['timeout']) ? ((int) $weblinks_config['timeout'] * 60) : 3600;
    $dir = NV_ROOTDIR . '/' . NV_LOGS_DIR . '/weblinks_logs';
    $log_fileext = preg_match('/[a-z]+/i', NV_LOGS_EXT) ? NV_LOGS_EXT : 'log';
    $pattern = '/^(.*)\.' . $log_fileext . '$/i';

    if (!is_dir($dir)) {
        nv_mkdir(NV_ROOTDIR . '/' . NV_LOGS_DIR, 'weblinks_logs');
    }

    $logs = nv_scandir($dir, $pattern);

    if (!empty($logs)) {
        foreach ($logs as $file) {
            $vtime = filemtime($dir . '/' . $file);

            if (!$vtime or $vtime <= NV_CURRENTTIME - $difftimeout) {
                @unlink($dir . '/' . $file);
            }
        }
    }

    $logfile = 'wl' . $id . '_' . md5(NV_LANG_DATA . $global_config['sitekey'] . $client_info['ip'] . $id) . '.' . $log_fileext;
    if (!file_exists($dir . '/' . $logfile)) {
        if (!preg_match('/^https?\:\/\//i', $row['url'])) {
            $url = 'http://' . $url;
        }

        $db->query('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET hits_total=hits_total+1 WHERE id=' . (int) $id);
        file_put_contents($dir . '/' . $logfile, '', LOCK_EX);
        header('Location: ' . $row['url']);
        exit();
    }

    $timeout = filemtime($dir . '/' . $logfile);
    $timeout = $difftimeout - NV_CURRENTTIME + $timeout;
    $contents .= sprintf($lang_module['notimeout'], nv_convertfromSec($timeout));
}

nv_info_die('', '', $contents);
