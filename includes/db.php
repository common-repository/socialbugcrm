<?php

namespace SocialbugCRM\Includes;

require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

class DB
{
    const CR_TABLE = 'socialbugcrm_CustomerRewards';
    const DEFAULT_SALT = '$alt';

    protected $table;

    public function __construct()
    {
        global $wpdb;

        $this->table = $wpdb->prefix . self::CR_TABLE;
    }

    public function getTableName()
    {
        return $this->table;
    }

    public function createTable()
    {
        global $wpdb;

        if ($wpdb->get_var("SHOW TABLES LIKE '{$this->table}'") == $this->table) {
            $sql = "DROP TABLE `{$this->table}`;";
            dbDelta($sql);
        }

        $sql = "CREATE TABLE `{$this->table}` (
            `ApiKey` char(36) NOT NULL,
            `Salt` char(16) NOT NULL,
            `CreatedOnUtc` datetime NOT NULL,
            `UserId` int NOT NULL,
            `AppendHtml` text,

            PRIMARY KEY (ApiKey)
        );";

        dbDelta($sql);
    }

    public function dropTable()
    {
        global $wpdb;

        if ($wpdb->get_var("SHOW TABLES LIKE '{$this->table}'") == $this->table) {
            $sql = "DROP TABLE IF EXISTS `{$this->table}`;";
            $wpdb->query($sql);

            delete_option('socialbugcrm');
        }
    }

    public function addNewRecord($key, $time, $salt, $userId)
    {
        global $wpdb;

        $query = $wpdb->prepare("
            INSERT INTO `{$this->table}` (ApiKey, CreatedOnUtc, Salt, UserId)
            VALUES (%s, %s, %s, %s)", 
            $key, $time, $salt, $userId);
        $wpdb->query($query);
    }
}




