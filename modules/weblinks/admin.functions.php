<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) {
    exit('Stop!!!');
}

$allow_func = [
    'alias',
    'main',
    'cat',
    'change_cat',
    'del_cat',
    'content',
    'del_link',
    'config',
    'multidel',
    'brokenlink'
];

define('NV_IS_FILE_ADMIN', true);

require_once NV_ROOTDIR . '/modules/' . $module_file . '/global.functions.php';

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
        $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_cat SET weight=' . $weight . ' WHERE catid=' . (int) ($row['catid']);
        $db->query($sql);
    }

    $result->closeCursor();
}

/**
 * drawselect_number()
 *
 * @param string $select_name
 * @param int    $number_start
 * @param int    $number_end
 * @param int    $number_curent
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
 * @param string $title
 * @param array  $cat
 * @param array  $array_cat
 */
function getlevel(&$title, $cat, $array_cat)
{
    if (!empty($title)) {
        $title = ' > ' . $title;
    }
    $title = $cat['title'] . $title;

    if (!empty($cat['parentid'])) {
        getlevel($title, $array_cat[$cat['parentid']], $array_cat);
    }
}

/**
 * getcattitle()
 *
 * @param string $title
 * @param array  $cat
 * @param array  $array_cat
 */
function getcattitle(&$title, $cat, $array_cat)
{
    global $module_name;

    $link = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=cat&amp;pid=' . $cat['catid'];

    if (!empty($title)) {
        $title = ' > ' . $title;
    }

    $title = '<a href="' . $link . '"/>' . $cat['title'] . '</a>' . $title;
    if (!empty($cat['parentid'])) {
        getcattitle($title, $array_cat[$cat['parentid']], $array_cat);
    }
}

function getCatPageTitle(&$page_title, $pid, $array_cat)
{
    global $module_name;

    array_push($page_title, [
        'title' => $array_cat[$pid]['title'],
        'link' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=cat&amp;pid=' . $pid
    ]);
    if (!empty($array_cat[$pid]['parentid'])) {
        getCatPageTitle($page_title, $array_cat[$pid]['parentid'], $array_cat);
    }
}

/**
 * drawselect_yesno()
 *
 * @param string $select_name
 * @param int    $curent
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

/**
 * setInCat()
 *
 * @param mixed $catid1
 * @param mixed $catid2
 * @param mixed $array_cat
 * @return mixed
 */
function setInCat($catid1, $catid2, $array_cat)
{
    if ($catid2 == $catid1) {
        return false;
    }
    if (!empty($array_cat[$catid2]['parentid'])) {
        return setInCat($catid1, $array_cat[$catid2]['parentid'], $array_cat);
    }

    return true;
}

/**
 * checkAlias()
 *
 * @param string $alias
 * @param string $type
 * @param int    $id
 * @return bool
 */
function checkAlias($alias, $type, $id)
{
    global $db, $module_data;

    if ($type == 'cat') {
        $isExists = $db->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . "_cat WHERE alias = '" . $alias . "' AND catid != " . (int) $id)->fetchColumn();
    } else {
        $isExists = $db->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . "_rows WHERE alias = '" . $alias . "' AND id != " . (int) $id)->fetchColumn();
    }

    return $isExists ? false : true;
}

/**
 * setAlias()
 *
 * @param string $title
 * @param string $type
 * @param int    $id
 * @param int    $num
 * @return string
 */
function setAlias($title, $type, $id, $num = 0)
{
    $atitle = $title;
    if ($num) {
        $atitle .= '-' . $num;
    }
    ++$num;
    $alias = change_alias($atitle);
    if (!checkAlias($alias, $type, $id)) {
        return setAlias($title, $type, $id, $num);
    }

    return $alias;
}

/**
 * formatUrl()
 *
 * @param string $url
 * @return false|string
 */
function formatUrl($url)
{
    $url_info = parse_url($url);

    if (!isset($url_info['host'])) {
        return false;
    }

    $url_info['port'] = isset($url_info['port']) ? $url_info['port'] : 80;

    $url_info['login'] = '';
    if (isset($url_info['user'])) {
        $url_info['login'] = $url_info['user'];
        if (isset($url_info['pass'])) {
            $url_info['login'] .= ':' . $url_info['pass'];
        }
        $url_info['login'] .= '@';
    }

    if (isset($url_info['path'])) {
        if (substr($url_info['path'], 0, 1) != '/') {
            $url_info['path'] = '/' . $url_info['path'];
        }
        $path_array = explode('/', $url_info['path']);
        $path_array = array_map('rawurlencode', $path_array);
        $url_info['path'] = implode('/', $path_array);
    } else {
        $url_info['path'] = '/';
    }

    $url_info['query'] = !empty($url_info['query']) ? '?' . $url_info['query'] : '';

    $uri = $url_info['scheme'] . '://' . $url_info['login'] . $url_info['host'];
    if ($url_info['port'] != 80) {
        $uri .= ':' . $url_info['port'];
    }
    $uri .= $url_info['path'] . $url_info['query'];

    return $uri;
}
