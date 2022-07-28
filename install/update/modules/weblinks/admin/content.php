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

/**
 * check_url()
 *
 * @param mixed $id
 * @param mixed $url
 * @return
 */
function check_url($id, $url)
{
    global $db, $module_data;
    $sql = 'SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id != ' . (int) $id . ' AND url = ' . $db->quote($url);
    $numurl = $db->query($sql)->fetchColumn();
    $msg = ($numurl > 0) ? false : true;

    return $msg;
}

if (defined('NV_EDITOR')) {
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}

$page_title = $lang_module['weblink_add_link'];

$data = [
    'id' => '',
    'catid' => '',
    'title' => '',
    'alias' => '',
    'url' => '',
    'urlimg' => '',
    'description' => '',
    'add_time' => '',
    'edit_time' => '',
    'hits_total' => '',
    'status' => 1
];

$error = [];

$data['id'] = $nv_Request->get_int('id', 'get', 0);
if ($data['id'] > 0) {
    $sql = $db->query('SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_rows WHERE id=' . $data['id']);
    $data = $sql->fetch();

    $page_title = $lang_module['weblink_edit_link'];
}

if ($nv_Request->get_int('save', 'post,get', 0)) {
    $data['catid'] = $nv_Request->get_int('catid', 'post', 0);
    $data['title'] = $nv_Request->get_title('title', 'post', '', 1);
    $data['alias'] = $nv_Request->get_title('alias', 'post', '', 1);
    $data['alias'] = empty($data['alias']) ? setAlias($data['title'], 'row', $data['id']) : setAlias($data['alias'], 'row', $data['id']);

    $data['url'] = $nv_Request->get_title('url', 'post', '');
    $data['urlimg'] = $nv_Request->get_title('urlimg', 'post', '');

    if (!nv_is_url($data['urlimg']) and file_exists(NV_DOCUMENT_ROOT . $data['urlimg'])) {
        $lu = strlen(NV_BASE_SITEURL . NV_UPLOADS_DIR . '/');
        if (substr($data['urlimg'], 0, $lu) == NV_BASE_SITEURL . NV_UPLOADS_DIR . '/') {
            $data['urlimg'] = substr($data['urlimg'], $lu);
        }
    }

    if (!empty($data['url'])) {
        if (!preg_match('#^(http|https|ftp|gopher)\:\/\/#', $data['url'])) {
            $data['url'] = 'http://' . $data['url'];
        }

        $data['url'] = formatUrl($data['url']);
    }

    $data['description'] = $nv_Request->get_editor('description', '', NV_ALLOWED_HTML_TAGS);

    $data['status'] = ($nv_Request->get_int('status', 'post') == 1) ? 1 : 0;
    // check url
    if (empty($data['url']) || !nv_is_url($data['url']) || !check_url($data['id'], $data['url'])) {
        $error[] = $lang_module['error_url'];
    }
    if (empty($data['title'])) {
        $error[] = $lang_module['error_title'];
    }

    if (empty($error)) {
        if ($data['id'] > 0) {
            $stmt = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET
				catid=' . (int) ($data['catid']) . ',
				title=:title,
				alias =:alias,
				url =:url,
				urlimg =:urlimg,
				description=:description,
				edit_time = ' . NV_CURRENTTIME . ',
				status=' . (int) ($data['status']) . '
				WHERE id =' . (int) ($data['id']));
            $stmt->bindParam(':title', $data['title'], PDO::PARAM_STR);
            $stmt->bindParam(':alias', $data['alias'], PDO::PARAM_STR);
            $stmt->bindParam(':url', $data['url'], PDO::PARAM_STR);
            $stmt->bindParam(':urlimg', $data['urlimg'], PDO::PARAM_STR);
            $stmt->bindParam(':description', $data['description'], PDO::PARAM_STR, strlen($data['description']));

            if ($stmt->execute()) {
                nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['weblink_edit_link'], $data['title'], $admin_info['userid']);
                header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
                exit();
            }
            $error[] = $lang_module['errorsave'];
        } else {
            $stmt = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_' . $module_data . '_rows SET
				catid =' . (int) ($data['catid']) . ',
				title =:title,
				alias =:alias,
				url =:url,
				urlimg =:urlimg,
				description =:description,
				add_time = ' . NV_CURRENTTIME . ',
				edit_time =' . NV_CURRENTTIME . ',
				hits_total = 0,
				status = ' . (int) ($data['status']));
            $stmt->bindParam(':title', $data['title'], PDO::PARAM_STR);
            $stmt->bindParam(':alias', $data['alias'], PDO::PARAM_STR);
            $stmt->bindParam(':url', $data['url'], PDO::PARAM_STR);
            $stmt->bindParam(':urlimg', $data['urlimg'], PDO::PARAM_STR);
            $stmt->bindParam(':description', $data['description'], PDO::PARAM_STR, strlen($data['description']));
            if ($stmt->execute()) {
                nv_insert_logs(NV_LANG_DATA, $module_name, $lang_module['weblink_add_link'], $data['title'], $admin_info['userid']);

                header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
                exit();
            }
            $error[] = $lang_module['errorsave'];
        }
    }
}

// dung de lay data tu CSDL
if (!empty($data['description'])) {
    $data['description'] = (defined('NV_EDITOR')) ? nv_editor_br2nl($data['description']) : nv_br2nl($data['description']);
    $data['description'] = nv_htmlspecialchars($data['description']);
}

if (!empty($data['urlimg']) and !nv_is_url($data['urlimg'])) {
    $data['urlimg'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $data['urlimg'];
}

if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
    $edits = nv_aleditor('description', '100%', '300px', $data['description']);
} else {
    $edits = '<textarea style="width: 100%" name="description" id="description" cols="20" rows="15">' . $data['description'] . '</textarea>';
}

$querysubcat = $db->query('SELECT catid, parentid, title FROM ' . NV_PREFIXLANG . '_' . $module_data . '_cat ORDER BY parentid, weight ASC');
$array_cat = [];
while ($row = $querysubcat->fetch()) {
    $array_cat[$row['catid']] = $row;
}

if (empty($array_cat)) {
    header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=cat');
    exit();
}

$xtpl = new XTemplate('content.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('DATA', $data);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('OP', $op);
$xtpl->assign('DESCRIPTION', $edits);
$xtpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op . (!empty($data['id']) ? '&amp;id=' . $data['id'] : ''));

if (!empty($array_cat)) {
    foreach ($array_cat as $cat) {
        $title = '';
        getlevel($title, $cat, $array_cat);
        $cat['title'] = $title;
        $cat['sl'] = ($cat['catid'] == $data['catid']) ? 'selected="selected"' : '';
        $xtpl->assign('CAT', $cat);
        $xtpl->parse('main.loopcat');
    }
}

$xtpl->assign('PATH', NV_UPLOADS_DIR . '/' . $module_upload);
$xtpl->assign('DATA', $data);

if (empty($data['alias'])) {
    $xtpl->parse('main.getalias');
}

if (!empty($error)) {
    $xtpl->assign('error', implode('<br />', $error));
    $xtpl->parse('main.error');
}

$xtpl->parse('main');
$contents .= $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
