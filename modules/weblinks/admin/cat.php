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

$error = '';

$catid = $nv_Request->get_int('catid', 'get', 0);
$pid = $nv_Request->get_int('pid', 'get', 0);

$data_content = [
    'catid' => $catid,
    'parentid_old' => 0,
    'parentid' => $pid,
    'title' => '',
    'alias' => '',
    'description' => '',
    'keywords' => ''
];

// Get array catid
$querysubcat = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat ORDER BY parentid, weight ASC');
$array_cat = [];
$numcat = 0;

while ($row = $querysubcat->fetch()) {
    $array_cat[$row['catid']] = $row;
    $array_cat[$row['catid']]['subs'] = 0;
    if ($row['parentid'] == $pid) {
        ++$numcat;
    }
    if (!empty($row['parentid'])) {
        ++$array_cat[$row['parentid']]['subs'];
    }
}

$page_title = $lang_module['categories'];

if ($pid > 0) {
    $ptitles = [];
    getCatPageTitle($ptitles, $pid, $array_cat);
    array_push($ptitles, [
        'title' => $lang_module['categories'],
        'link' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=cat'
    ]);

    $page_title = '';
    foreach ($ptitles as $ptitle) {
        if (empty($page_title)) {
            $page_title = $ptitle['title'];
        } else {
            $page_title = '<a href="' . $ptitle['link'] . '">' . $ptitle['title'] . '</a> <em class="fa fa-angle-right"></em> ' . $page_title;
        }
    }
}

//post data
$savecat = $nv_Request->get_int('savecat', 'post', 0);

if (!empty($savecat)) {
    $data_content['parentid_old'] = $nv_Request->get_int('parentid_old', 'post', 0);
    $data_content['parentid'] = $nv_Request->get_int('parentid', 'post', 0);
    $data_content['title'] = nv_substr($nv_Request->get_title('title', 'post', '', 1), 0, 100);
    $data_content['catimage'] = $nv_Request->get_title('catimage', 'post');
    $data_content['keywords'] = $nv_Request->get_title('keywords', 'post');
    $data_content['alias'] = nv_substr($nv_Request->get_title('alias', 'post', '', 1), 0, 100);
    $data_content['description'] = $nv_Request->get_textarea('description', '', NV_ALLOWED_HTML_TAGS);
    $data_content['alias'] = (empty($data_content['alias'])) ? setAlias($data_content['title'], 'cat', $data_content['catid']) : setAlias($data_content['alias'], 'cat', $data_content['catid']);

    if (!nv_is_url($data_content['catimage']) and file_exists(NV_DOCUMENT_ROOT . $data_content['catimage'])) {
        $lu = strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/');
        if (substr($data_content['catimage'], 0, $lu) == NV_BASE_SITEURL . NV_UPLOADS_DIR . '/') {
            $data_content['catimage'] = substr($data_content['catimage'], $lu);
        }
    }

    if (empty($data_content['title'])) {
        $error = $lang_module['weblink_sub_input'];
    } else {
        if ($data_content['catid'] == 0) {
            $weight = $db->query('SELECT max(weight) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat WHERE parentid=' . (int) ($data_content['parentid']) . '')->fetchColumn();
            $weight = (int) $weight + 1;

            $stmt = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_cat SET
				parentid =' . (int) ($data_content['parentid']) . ',
				weight =' . (int) $weight . ',
				inhome =1,
				title =:title,
				catimage =:catimage,
				alias =:alias,
				description =:description,
				keywords =:keywords,
				add_time = ' . NV_CURRENTTIME . ',
				edit_time =' . NV_CURRENTTIME);
            $stmt->bindParam(':title', $data_content['title'], PDO::PARAM_STR);
            $stmt->bindParam(':catimage', $data_content['catimage'], PDO::PARAM_STR);
            $stmt->bindParam(':alias', $data_content['alias'], PDO::PARAM_STR);
            $stmt->bindParam(':description', $data_content['description'], PDO::PARAM_STR);
            $stmt->bindParam(':keywords', $data_content['keywords'], PDO::PARAM_STR);
            $stmt->execute();
            if ($idnew = $db->lastInsertId()) {
                nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['add_cat'], $data_content['title'], $admin_info['userid']);
                $nv_Cache->delMod($module_name);
                header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&pid=' . $data_content['parentid']);
                exit();
            }
            $error = $lang_module['errorsave'];
        } elseif ($data_content['catid'] > 0) {
            $check_exit = 0;

            if ($data_content['parentid'] != $data_content['parentid_old']) {
                $check_exit = $db->query('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE catid = ' . (int) ($data_content['catid']))->fetchColumn();
            }

            if ((int) $check_exit > 0) {
                $error = 'error delete cat';
            } else {
                $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_cat SET
					parentid=' . (int) ($data_content['parentid']) . ',
					title=:title,
					catimage=:catimage,
					alias=:alias,
					description=:description,
					keywords=:keywords,
					edit_time=' . NV_CURRENTTIME . '
					WHERE catid =' . $data_content['catid']);
                $stmt->bindParam(':title', $data_content['title'], PDO::PARAM_STR);
                $stmt->bindParam(':catimage', $data_content['catimage'], PDO::PARAM_STR);
                $stmt->bindParam(':alias', $data_content['alias'], PDO::PARAM_STR);
                $stmt->bindParam(':description', $data_content['description'], PDO::PARAM_STR);
                $stmt->bindParam(':keywords', $data_content['keywords'], PDO::PARAM_STR);

                if ($stmt->execute()) {
                    if ($data_content['parentid'] != $data_content['parentid_old']) {
                        $weight = $db->query('SELECT max(weight) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat WHERE parentid=' . (int) ($data_content['parentid']) . '')->fetchColumn();
                        $weight = (int) $weight + 1;
                        $sql = 'UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_cat SET weight=' . $weight . ' WHERE catid=' . (int) ($data_content['catid']);
                        $db->query($sql);
                        nv_fix_cat($data_content['parentid']);
                        nv_fix_cat($data_content['parentid_old']);
                    }

                    $nv_Cache->delMod($module_name);
                    nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['edit_cat'], $data_content['title'], $admin_info['userid']);

                    header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op . '&pid=' . $data_content['parentid']);
                    exit();
                }
                $error = $lang_module['errorsave'];
            }
        }
    }
}

if ($data_content['catid'] > 0) {
    $data_content = $array_cat[$data_content['catid']];
    $caption = $lang_module['edit_cat'];
} else {
    $data_content['catimage'] = '';
    $caption = $lang_module['add_cat'];
}

if (!empty($data_content['catimage']) and !nv_is_url($data_content['catimage'])) {
    $data_content['catimage'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $data_content['catimage'];
}

$lang_module['edit'] = $lang_global['edit'];
$lang_module['delete'] = $lang_global['delete'];

$xtpl = new XTemplate('cat.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('DATA', $data_content);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('OP', $op);
$xtpl->assign('PATH', NV_UPLOADS_DIR . '/' . $module_upload);
$xtpl->assign('catid', $data_content['catid']);

// get catid
if (!empty($array_cat)) {
    foreach ($array_cat as $cat) {
        $xtitle = '';

        if (setInCat($data_content['catid'], $cat['catid'], $array_cat)) {
            //if ($cat['catid'] != $data_content['catid']) {
            $title = '';
            getlevel($title, $cat, $array_cat);
            $cat['xtitle'] = $title;
            $cat['sl'] = ($cat['catid'] == $data_content['parentid']) ? ' selected="selected"' : '';
            $xtpl->assign('CAT', $cat);
            $xtpl->parse('main.loopcat');
        }

        if ($cat['parentid'] == $data_content['parentid']) {
            $title = '';
            getcattitle($title, $cat, $array_cat);
            $subs = $cat['subs'] ? ' (&gt; ' . $cat['subs'] . ')' : '';
            $cat['fulltitle'] = $title . $subs;
            $cat['link_add'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=cat&amp;pid=' . $cat['catid'] . '';
            $cat['link_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=cat&amp;catid=' . $cat['catid'] . '';
            $cat['weight_select'] = drawselect_number('change', 1, $numcat, $cat['weight'], 'nv_chang_cat(this,' . $cat['catid'] . ',\'weight\');');
            $cat['inhome_select'] = drawselect_yesno($select_name = 'slinhome', $cat['inhome'], $lang_module['weblink_no'], $lang_module['weblink_yes'], 'nv_chang_cat(this,' . $cat['catid'] . ',\'inhome\');');
            $xtpl->assign('ROW', $cat);
            $xtpl->parse('main.data.loop');
        }
    }

    $xtpl->assign('url_back', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=cat&amp;pid=' . $data_content['parentid'] . '');
    if ($numcat > 0) {
        $xtpl->parse('main.data');
    }
}

$xtpl->assign('FORMACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=cat&amp;catid=' . $data_content['catid']);
$xtpl->assign('DATA', $data_content);
$xtpl->assign('CAPTION', $caption);
$xtpl->assign('PATH', NV_UPLOADS_DIR . '/' . $module_upload);
$xtpl->assign('UPLOAD_CURRENT', NV_UPLOADS_DIR . '/' . $module_upload . '/cat');

if (empty($data_content['alias'])) {
    $xtpl->parse('main.getalias');
}

if (!empty($error)) {
    $xtpl->assign('error', $error);
    $xtpl->parse('main.error');
}

if ($numcat and !$nv_Request->isset_request('catid', 'get') and !$nv_Request->isset_request('add', 'get')) {
    $xtpl->parse('main.add_hide');
} else {
    $xtpl->parse('main.list_hide');
}

$xtpl->parse('main');
$contents .= $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
