<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 12/31/2009 0:51
 */

if( ! defined( 'NV_MAINFILE' ) ) die( 'Stop!!!' );

/**
 * _substr()
 *
 * @param mixed $str
 * @param mixed $length
 * @param integer $minword
 * @return
 */
function _substr( $str, $length, $minword = 3 )
{
	$sub = '';
	$len = 0;

	foreach( explode( ' ', $str ) as $word )
	{
		$part = ( ( $sub != '' ) ? ' ' : '' ) . $word;
		$sub .= $part;
		$len += strlen( $part );

		if( isset( $word{$minword} ) && isset( $sub{$length - 1} ) )
		{
			break;
		}
	}

	return $sub . ( ( isset( $str{$len} ) ) ? '...' : '' );
}

$array_config = array();
$sql = 'SELECT * FROM ' . NV_PREFIXLANG . '_' . $module_data . '_config';
$list = nv_db_cache( $sql, '', $module_name );
foreach( $list as $l )
{

	$array_config[$l['name']] = $l['value'];
}
unset( $sql, $list );
 