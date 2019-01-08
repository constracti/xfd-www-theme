<?php

function mb_ereg_all( $pattern, $string ) {
	$matches = [];
	mb_ereg_search_init( $string, $pattern );
	while ( mb_ereg_search() ) {
		$matches[] = mb_ereg_search_getregs();
		mb_ereg_search_setpos( mb_ereg_search_getpos() );
	}
	return $matches;
}
