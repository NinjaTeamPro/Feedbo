<?php
namespace Feedbo;

defined('ABSPATH') || exit;

class I18n
{
    protected static $instance = null;
    public static function getInstance()
    {
        if (null == self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    private function __construct()
    {
        add_action('plugins_loaded', array($this, 'loadPluginTextdomain'));
    }
    public function loadPluginTextdomain()
    {
        load_plugin_textdomain(
            'feedbo',
            false,
            FB_PLUGIN_URL . 'i18n/languages/'
        );
    }
}
