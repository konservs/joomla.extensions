<?php
/**
 * Plural form
 */
function plural_form($number, $after) {
	$cases = array (2, 0, 1, 1, 1, 2);
	return $number.' '.$after[ ($number%100>4 && $number%100<20)? 2: $cases[min($number%10, 5)] ];
	}

/**
 * Get host + protocol
 */
function burl_origin( $s, $use_forwarded_host = false ){
        $ssl      = ( ! empty( $s['HTTPS'] ) && $s['HTTPS'] == 'on' );
        $sp       = strtolower( $s['SERVER_PROTOCOL'] );
        $protocol = substr( $sp, 0, strpos( $sp, '/' ) ) . ( ( $ssl ) ? 's' : '' );
        $port     = $s['SERVER_PORT'];
        $port     = ( ( ! $ssl && $port=='80' ) || ( $ssl && $port=='443' ) ) ? '' : ':'.$port;
        $host     = ( $use_forwarded_host && isset( $s['HTTP_X_FORWARDED_HOST'] ) ) ? $s['HTTP_X_FORWARDED_HOST'] : ( isset($s['HTTP_HOST'] ) ? $s['HTTP_HOST'] : null );
        $host     = isset( $host ) ? $host : $s['SERVER_NAME'] . $port;
        return $protocol . '://' . $host;
        }
/**
 * Get full current URL
 */
function bfull_url( $s, $use_forwarded_host = false ){
        return burl_origin( $s, $use_forwarded_host ) . $s['REQUEST_URI'];
        }
