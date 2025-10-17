<?php
namespace Feedbo;

use Feedbo\SingletonTrait;

class Admin {

	use SingletonTrait;

	protected function __construct() {
		// add_shortcode( 'feedbo', array( $this, 'feedbo_shortcode_content' ) );
	}

	
}