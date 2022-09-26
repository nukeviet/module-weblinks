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

$title = $nv_Request->get_title('title', 'post', '');
$type = $nv_Request->get_title('type', 'post', '');
$id = $nv_Request->get_int('id', 'post', 0);
$alias = setAlias($title, $type, $id);

include NV_ROOTDIR . '/includes/header.php';
echo $alias;
include NV_ROOTDIR . '/includes/footer.php';
