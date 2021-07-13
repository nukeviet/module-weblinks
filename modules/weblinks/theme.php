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

/**
 * main_theme()
 *
 * @param array $array_cat
 * @return string
 */
function main_theme($array_cat)
{
    global $module_info, $lang_module, $weblinks_config;

    $xtpl = new XTemplate('main_page.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);

    foreach ($array_cat as $catid => $array_cat_i) {
        if (!empty($array_cat_i) and !empty($array_cat_i['count_link'])) {
            $xtpl->assign('CAT', $array_cat_i);

            if (!empty($array_cat_i['subcat'])) {
                $i = 0;
                foreach ($array_cat_i['subcat'] as $subcat) {
                    if ($i == 2) {
                        $xtpl->parse('main.cat_loop.sub.next');
                        break;
                    }
                    $xtpl->assign('SUBCAT', $subcat);
                    $xtpl->parse('main.cat_loop.sub.loop');
                    ++$i;
                }
                $xtpl->parse('main.cat_loop.sub');
            }

            if (!empty($array_cat_i['contents'])) {
                foreach ($array_cat_i['contents'] as $content) {
                    $add_time = $content['add_time'];
                    $content['add_time'] = nv_date('H:i l - d/m/Y', $content['add_time']);
                    $content['url'] = nv_clean60($content['url'], 70) . '...';

                    $xtpl->assign('ITEM', $content);

                    if (((int) $weblinks_config['new_icon'] * 86400 + (int) $add_time) > NV_CURRENTTIME) {
                        $xtpl->parse('main.cat_loop.item.new_icon');
                    }

                    if ($weblinks_config['showlinkimage'] == '1') {
                        if ($content['urlimg'] != '') {
                            if (!nv_is_url($content['urlimg'])) {
                                $urlimg = NV_BASE_SITEURL . NV_ASSETS_DIR . '/' . $content['urlimg'];
                            } else {
                                $urlimg = $content['urlimg'];
                            }
                        } else {
                            $urlimg = NV_STATIC_URL . 'themes/' . $module_info['template'] . '/images/' . $module_info['module_theme'] . '/no_image.svg';
                        }
                        $xtpl->assign('SRC_IMG', $urlimg);
                        $xtpl->parse('main.cat_loop.item.img');
                    }

                    if (defined('NV_IS_ADMIN')) {
                        $xtpl->assign('ADMIN_LINK', adminlink($content['id']));
                        $xtpl->parse('main.cat_loop.item.admin_links');
                    }
                    $xtpl->parse('main.cat_loop.item');
                }
            }
            $xtpl->parse('main.cat_loop');
        }
    }
    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * main_list_theme()
 *
 * @param array  $items
 * @param string $pages
 * @return string
 */
function main_list_theme($items, $pages = '')
{
    global $module_info, $lang_module, $weblinks_config;

    $xtpl = new XTemplate('main_list.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);

    if (!empty($items)) {
        foreach ($items as $item) {
            $item['url'] = nv_clean60($item['url'], 70) . '...';
            $add_time = $item['add_time'];
            $item['add_time'] = nv_date('H:i l - d/m/Y', $item['add_time']);
            $xtpl->assign('ITEM', $item);

            if (((int) $weblinks_config['new_icon'] * 86400 + (int) $add_time) > NV_CURRENTTIME) {
                $xtpl->parse('main.item.new_icon');
            }

            if ($weblinks_config['showlinkimage'] == '1') {
                if (!empty($item['urlimg'])) {
                    if (!nv_is_url($item['urlimg'])) {
                        $urlimg = NV_BASE_SITEURL . NV_ASSETS_DIR . '/' . $item['urlimg'];
                    } else {
                        $urlimg = $item['urlimg'];
                    }
                } else {
                    $urlimg = NV_STATIC_URL . 'themes/' . $module_info['template'] . '/images/' . $module_info['module_theme'] . '/no_image.svg';
                }
                $xtpl->assign('SRC_IMG', $urlimg);
                $xtpl->parse('main.item.img');
            }

            if (defined('NV_IS_ADMIN')) {
                $xtpl->assign('ADMIN_LINK', adminlink($item['id']));
                $xtpl->parse('main.item.admin_links');
            }

            $xtpl->parse('main.item');
        }
    }

    if (!empty($pages)) {
        $xtpl->assign('PAGES', $pages);
        $xtpl->parse('main.pages');
    }
    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * viewcat()
 *
 * @param array  $subcats
 * @param array  $cat
 * @param array  $items
 * @param string $pages
 * @return string
 */
function viewcat($subcats, $cat, $items, $pages = '')
{
    global $module_info, $lang_module, $weblinks_config;

    $xtpl = new XTemplate('viewcat.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);

    if (!empty($cat['catimage'])) {
        if (!nv_is_url($cat['catimage'])) {
            $cat['catimage'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $cat['catimage'];
        }
        $cat['catimagewidth'] = $weblinks_config['imgwidth'];
    }

    $xtpl->assign('CAT', $cat);

    if (!empty($cat['description'])) {
        if (!empty($cat['catimage'])) {
            $xtpl->parse('main.cat.catimg');
        }

        $xtpl->parse('main.cat');
    }

    if (!empty($subcats)) {
        foreach ($subcats as $subcats_i) {
            $xtpl->assign('SUB', $subcats_i);
            $xtpl->parse('main.sub');
        }
    }

    if (!empty($items)) {
        foreach ($items as $items_i) {
            $add_time = $items_i['add_time'];
            $items_i['add_time'] = nv_date('H:i l - d/m/Y', $items_i['add_time']);
            $items_i['url'] = nv_clean60($items_i['url'], 70) . '...';
            $xtpl->assign('ITEM', $items_i);

            if (((int) $weblinks_config['new_icon'] * 86400 + (int) $add_time) > NV_CURRENTTIME) {
                $xtpl->parse('main.loop.new_icon');
            }

            if ($weblinks_config['showlinkimage'] == '1') {
                if (!empty($items_i['urlimg'])) {
                    if (!nv_is_url($items_i['urlimg'])) {
                        $xtpl->assign('SRC_IMG', NV_BASE_SITEURL . NV_ASSETS_DIR . '/' . $items_i['urlimg']);
                    } else {
                        $xtpl->assign('SRC_IMG', $items_i['urlimg']);
                    }
                } else {
                    $xtpl->assign('SRC_IMG', NV_STATIC_URL . 'themes/' . $module_info['template'] . '/images/' . $module_info['module_theme'] . '/no_image.svg');
                }
                $xtpl->parse('main.loop.img');
            }

            if (defined('NV_IS_ADMIN')) {
                $xtpl->assign('ADMIN_LINK', adminlink($items_i['id']));
                $xtpl->parse('main.loop.admin_links');
            }

            $xtpl->parse('main.loop');
        }
    }

    if (!empty($pages)) {
        $xtpl->assign('PAGES', $pages);
        $xtpl->parse('main.pages');
    }
    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * detail()
 *
 * @param array $row
 * @return string
 */
function detail($row)
{
    global $module_info, $lang_module;

    $xtpl = new XTemplate('detail.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);

    $row['add_time'] = nv_date('H:i l - d/m/Y', $row['add_time']);
    $row['edit_time'] = nv_date('H:i l - d/m/Y', $row['edit_time']);
    if (!empty($row['urlimg'])) {
        $xtpl->assign('IMG', $row['urlimg']);
        $xtpl->parse('main.img');
    }

    $row['url'] = nv_clean60($row['url'], 60) . '...';
    $xtpl->assign('DETAIL', $row);

    if (!empty($row['description'])) {
        $xtpl->parse('main.des');
    }

    if (defined('NV_IS_ADMIN')) {
        $xtpl->assign('ADMIN_LINK', adminlink($row['id']));
    }
    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * report()
 *
 * @param array $row
 * @return string
 */
function report($row)
{
    global $module_info, $lang_module;

    $xtpl = new XTemplate('report.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('ROW', $row);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);

    $xtpl->parse('main');

    return $xtpl->text('main');
}
