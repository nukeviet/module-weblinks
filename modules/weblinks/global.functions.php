<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

/**
 * _substr()
 *
 * @param string $str
 * @param int    $length
 * @param int    $minword
 * @return string
 */
function _substr($str, $length, $minword = 3)
{
    $sub = '';
    $len = 0;

    foreach (explode(' ', $str) as $word) {
        $part = (($sub != '') ? ' ' : '') . $word;
        $sub .= $part;
        $len += strlen($part);

        if (isset($word[$minword], $sub[$length - 1])) {
            break;
        }
    }

    return $sub . ((isset($str[$len])) ? '...' : '');
}

$weblinks_config = [];
$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_config';
$list = $nv_Cache->db($sql, '', $module_name);
foreach ($list as $l) {
    $weblinks_config[$l['name']] = $l['value'];
}
unset($sql, $list);
