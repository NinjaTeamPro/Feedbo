<?php
namespace Feedbo\Classes;

defined( 'ABSPATH' ) || exit;

class Captcha {

	public static function isValid( $response ) {
		$data = array(
			'secret'   => MV_RECAPTCHA_SECRET,
			'response' => $response,
		);
		$res  = self::cURL( 'https://www.google.com/recaptcha/api/siteverify', $data );
		return is_array( $res ) && $res['success'] == 1;
	}
	private static function cURL( $url, $data ) {
		$ch     = curl_init();
		$data   = http_build_query( $data );
		$getUrl = $url . '?' . $data;
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_URL, $getUrl );
		curl_setopt( $ch, CURLOPT_TIMEOUT, 80 );

		$response = curl_exec( $ch );

		if ( curl_error( $ch ) ) {
			$err = curl_error( $ch );
			curl_close( $ch );
			return 'Request Error:' . $err;
		} else {
			curl_close( $ch );
			return json_decode( $response, true );
		}
	}
}
