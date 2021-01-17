<?php

debug( '=======================THIS IS A POD PAGE=============================', null, true );
debug( $Xeno->Router->params, null, true );

function pod_load( $pod_id ) {
	if ( is_numeric( $pod_id )) {
		$pod = DB::queryFirstRow( "SELECT * FROM %b WHERE pod_id=%i", 'pod', $pod_id );
		$podraw['fields'] = DB::query( "SELECT * FROM %b WHERE lang_id = %i", 'podfields', 1 );
		foreach ($podraw['fields'] as $k => $v) {
			
			$field_data = DB::query( "SELECT * FROM %b WHERE pod_id = %i AND lang_id = %i", 'podfield_' . $v['field_name'], $pod_id, 1 );
			foreach ($field_data as $field_data => $field_value) {
				$pod['field'][$v['field_name']][] = $field_value['field_content'];
			}
		}
	}
	return $pod;
}