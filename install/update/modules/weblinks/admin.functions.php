<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 10 April 2017 17:00
 */

if (! defined('NV_ADMIN') or ! defined('NV_MAINFILE') or ! defined('NV_IS_MODADMIN')) {
    die('Stop!!!');
}

$allow_func = array(
    'alias',
    'main',
    'cat',
    'change_cat',
    'del_cat',
    'content',
    'del_link',
    'config',
    'multidel',
    'checklink',
    'brokenlink',
    'delbroken'
);

define('NV_IS_FILE_ADMIN', true);

require_once(NV_ROOTDIR . '/modules/' . $module_file . '/global.functions.php');

/**
 * nv_fix_cat()
 *
 * @param mixed $parentid
 * @return
 */
function nv_fix_cat($parentid)
{
    global $db, $module_data;

    $sql = 'SELECT catid FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat WHERE parentid=' . $parentid . ' ORDER BY weight ASC';
    $result = $db->query($sql);

    $weight = 0;
    while ($row = $result->fetch()) {
        ++$weight;
        $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_cat SET weight=' . $weight . ' WHERE catid=' . intval($row['catid']);
        $db->query($sql);
    }

    $result->closeCursor();
}

/**
 * drawselect_number()
 *
 * @param string $select_name
 * @param integer $number_start
 * @param integer $number_end
 * @param integer $number_curent
 * @param string $func_onchange
 * @return
 */
function drawselect_number($select_name = '', $number_start = 0, $number_end = 1, $number_curent = 0, $func_onchange = '')
{
    $html = '<select class="form-control" name="' . $select_name . '" onchange="' . $func_onchange . '">';
    for ($i = $number_start; $i <= $number_end; ++$i) {
        $select = ($i == $number_curent) ? ' selected="selected"' : '';
        $html .= '<option value="' . $i . '"' . $select . '>' . $i . '</option>';
    }
    $html .= '</select>';
    return $html;
}

/**
 * getlevel()
 *
 * @param mixed $pid
 * @param mixed $array_cat
 * @param integer $numxtitle
 * @param string $xkey
 * @return
 */
function getlevel($pid, $array_cat, $numxtitle = 5, $xkey = '&nbsp;')
{
    $html = '';
    for ($i = 0; $i < $numxtitle; ++$i) {
        $html .= $xkey;
    }
    if ($array_cat[$pid]['parentid'] != 0) {
        $html .= getlevel($array_cat[$pid]['parentid'], $array_cat);
    }
    return $html;
}

/**
 * drawselect_yesno()
 *
 * @param string $select_name
 * @param integer $curent
 * @param string $lang_no
 * @param string $lang_yes
 * @param string $func_onchange
 * @return
 */
function drawselect_yesno($select_name = '', $curent = 1, $lang_no = '', $lang_yes = '', $func_onchange = '')
{
    $html = '<select class="form-control" name="' . $select_name . '" onchange="' . $func_onchange . '">';
    $select_yes = ($curent == 1) ? ' selected="selected"' : '';
    $select_no = ($curent == 0) ? ' selected="selected"' : '';
    $html .= '<option value="0"' . $select_no . '>' . $lang_no . '</option>';
    $html .= '<option value="1"' . $select_yes . '>' . $lang_yes . '</option>';
    $html .= '</select>';
    return $html;
}
