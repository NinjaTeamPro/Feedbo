<?php
namespace Feedbo;

use Feedbo\SingletonTrait;

class Frontend {

	use SingletonTrait;

	protected function __construct() {
		// add_shortcode( 'feedbo', array( $this, 'feedbo_shortcode_content' ) );
        // hook replace content frontend with custom html
        add_filter( 'the_content', array( $this, 'replace_content_frontend' ) );
	}

	public function replace_content_frontend() {
		return '<div id="feedbo-content"></div>';
	}
}