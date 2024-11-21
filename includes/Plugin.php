<?php

namespace Feedbo;

defined('ABSPATH') || exit;

class Plugin
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
        Functions::getInstance();
    }
    //Create table board_activity when plugin active
    public static function activate()
    {
        Helper\Installer::getInstance();
        global $wpdb;
        $board_activity = $wpdb->prefix . 'board_activity';
        $term_users = $wpdb->prefix . 'term_users';
        $user_notification = $wpdb->prefix . 'user_notification';
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $board_activity (
            activity_ID BIGINT(20) NOT NULL AUTO_INCREMENT,
            term_id BIGINT(20) NOT NULL,
            post_id BIGINT(20) NOT NULL,
            activity_name varchar(100) NOT NULL,
            activity_date datetime NOT NULL,
            user_id BIGINT(20) NOT NULL,
            PRIMARY KEY  (activity_ID)
        ) $charset_collate;";
        $sql1 = "CREATE TABLE $term_users (
            ID BIGINT(20) NOT NULL AUTO_INCREMENT,
            term_id BIGINT(20) NOT NULL,
            user_email VARCHAR(100) NOT NULL,
            term_role VARCHAR(50) NOT NULL,
            level INT NOT NULL,
            status VARCHAR(10) NOT NULL,
            PRIMARY KEY (ID)
        ) $charset_collate;";
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
        dbDelta($sql1);
        self::copyFileToRoot();
        global $wp_rewrite;
        $wp_rewrite->flush_rules(false);
    }
    public static function deactivate()
    {
        global $wp_rewrite;
        $wp_rewrite->flush_rules(false);
    }
    public static function copyFileToRoot()
    {
        $rootDirFile = dirname(dirname(dirname(FB_PLUGIN_PATH))).'/widget.js';
        $pluginPath = FB_PLUGIN_PATH;
        $dirFile = $pluginPath .'assets/widget.js';
        if(!file_exists($rootDirFile)) {
            if (!copy($dirFile, $rootDirFile)) {
                echo "failed to copy $dirFile to $rootDirFile...\n";
            }
        }
    }

}
