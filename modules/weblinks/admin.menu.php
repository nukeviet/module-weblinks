<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN')) {
    exit('Stop!!!');
}

$submenu['content'] = $nv_Lang->getModule('weblink_add_link');
$submenu['cat'] = $nv_Lang->getModule('weblink_catlist');
$submenu['brokenlink'] = $nv_Lang->getModule('weblink_link_broken');
$submenu['config'] = $nv_Lang->getModule('weblink_config');
