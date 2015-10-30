<?php

class Pddk_Main {

    private static $initiated = false;

//    public function __construct()
//    {
//
//        add_action('admin_init', array($this, 'init'));
//        register_activation_hook(__FILE__, array($this, 'db_install'));
//        add_action('plugins_loaded', array($this, 'db_update'));
//
//        add_action('admin_enqueue_scripts', array($this, 'srty_enqueue'));
//    }

    public static function init() {
        if (!self::$initiated) {
            self::init_hooks();
        }
    }

    private static function init_hooks() {
        self::$initiated = true;
        add_action('admin_init', array('Pddk_Main', 'admin_init'));
        add_action('admin_enqueue_scripts', array('Pddk_Main', 'admin_enqueue'));
        add_action('admin_menu', array('Pddk_Main', 'admin_menu'));
    }

    public static function admin_init($hook) {
        $residents = Pddk_Residents::instance();
//        $residents->init();
//        
        $houses = Pddk_Houses::instance();
//        $houses->init();
//        load_plugin_textdomain('penduduk');
//        add_meta_box('akismet-status', __('Comment History', 'akismet'), array('Akismet_Admin', 'comment_status_meta_box'), 'comment', 'normal');
    }

//    public function init()
//    {
////        ob_start();
////        $link = new Srty_links();
////        $link->init();
//
//        add_action('admin_menu', array($this, 'menu'));
//        echo 'init';
//    }

    public static function db_install() {
        global $wpdb;
//    global $sh_db_version;
//

        $charset_collate = $wpdb->get_charset_collate();
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        $table_name = $wpdb->prefix . PDDK_PREFIX . 'residents';
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
		`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `first_name` VARCHAR(45) NULL,
                `last_name` VARCHAR(45) NULL,
                `gender` CHAR(1) NULL COMMENT 'M or F',
                `dob` DATE NULL,
                `nric` VARCHAR(12) NULL COMMENT '790121010000',
                `phone` VARCHAR(45) NULL,
                `email` VARCHAR(45) NULL,
                `facebook` VARCHAR(45) NULL,
                `occupation` VARCHAR(45) NULL,
                PRIMARY KEY (`id`)
	) $charset_collate;";
        dbDelta($sql);

        $table_name = $wpdb->prefix . PDDK_PREFIX . 'houses';
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
		`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `owner_id` INT UNSIGNED NULL COMMENT 'House Owner',
                `residents_id` INT UNSIGNED NULL COMMENT 'Who stay at that house. can be rental or owner. only head of family/team',
                `ptb` CHAR(10) NULL COMMENT 'eg: PTB10468 / 24JDJ23/4',
                `house_no` TINYINT UNSIGNED NULL,
                `addr1` VARCHAR(45) NULL COMMENT 'Jalan Dato Jaafar 19',
                `addr2` VARCHAR(45) NULL COMMENT 'Taman Mutiara Desaru',
                `city` VARCHAR(45) NULL COMMENT 'Bandar Penawar',
                `postcode` VARCHAR(45) NULL COMMENT '81930',
                `state` VARCHAR(45) NULL COMMENT 'Johor',
                `country` VARCHAR(45) NULL COMMENT 'Malaysia',
                `country_code` CHAR(2) NULL COMMENT 'MY',
                PRIMARY KEY (`id`),
                UNIQUE INDEX `ptb_idx` (`ptb` ASC),
                INDEX `fk_houses_owner_idx` (`owner_id` ASC),
                INDEX `fk_houses_residents_idx` (`residents_id` ASC),
                CONSTRAINT `fk_houses_owner`
                  FOREIGN KEY (`owner_id`)
                  REFERENCES `" . $wpdb->prefix . PDDK_PREFIX . "residents` (`id`)
                  ON DELETE SET NULL
                  ON UPDATE NO ACTION,
                CONSTRAINT `fk_houses_residents`
                  FOREIGN KEY (`residents_id`)
                  REFERENCES `" . $wpdb->prefix . PDDK_PREFIX . "residents` (`id`)
                  ON DELETE SET NULL
                  ON UPDATE NO ACTION
	) $charset_collate;";
        dbDelta($sql);

        $table_name = $wpdb->prefix . PDDK_PREFIX . 'relationship_type';
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
		`id` INT UNSIGNED NULL AUTO_INCREMENT,
                `description` VARCHAR(45) NULL COMMENT 'spouse, parent/child, tenant',
                PRIMARY KEY (`id`)
	) $charset_collate;";
        dbDelta($sql);
        dbDelta("INSERT INTO $table_name (description) VALUES ('Spouse'),('Parent/child,'),('Tenant');");

        $table_name = $wpdb->prefix . PDDK_PREFIX . 'roles';
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
		`id` INT UNSIGNED NULL AUTO_INCREMENT,
                `description` VARCHAR(45) NULL COMMENT 'father, mother, son, daughter, sister, brother, husband, wife',
                PRIMARY KEY (`id`)
	) $charset_collate;";
        dbDelta($sql);

        dbDelta("INSERT INTO $table_name (description) VALUES ('House Owner'),('Head Of Family/Tenant'),('House Owner & Head Of Family/Tenant'),('Father'),('Mother'),('Son'),('Daughter'),('Sister'),('Brother'),('Husband'),('Wife');");

        $table_name = $wpdb->prefix . PDDK_PREFIX . 'relationships';
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
		`id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `houses_id` INT UNSIGNED NOT NULL,
                `person1_id` INT UNSIGNED NOT NULL,
                `person2_id` INT UNSIGNED NOT NULL,
                `relationship_type_id` INT UNSIGNED NULL,
                `person1_roles_id` INT UNSIGNED NULL,
                `person2_roles_id` INT UNSIGNED NULL,
                PRIMARY KEY (`id`, `houses_id`, `person1_id`, `person2_id`, `relationship_type_id`),UNIQUE KEY `composit_id` (`houses_id`,`person1_id`,`person2_id`),
                INDEX `fk_relationships_residents1_idx` (`person1_id` ASC),
                INDEX `fk_relationships_residents2_idx` (`person2_id` ASC),
                INDEX `fk_relationships_houses1_idx` (`houses_id` ASC),
                INDEX `fk_relationships_relationship_type1_idx` (`relationship_type_id` ASC),
                INDEX `fk_relationships_roles1_idx` (`person1_roles_id` ASC),
                INDEX `fk_relationships_roles2_idx` (`person2_roles_id` ASC),
                CONSTRAINT `fk_relationships_residents1`
                  FOREIGN KEY (`person1_id`)
                  REFERENCES `" . $wpdb->prefix . PDDK_PREFIX . "residents` (`id`)
                  ON DELETE NO ACTION
                  ON UPDATE NO ACTION,
                CONSTRAINT `fk_relationships_residents2`
                  FOREIGN KEY (`person2_id`)
                  REFERENCES `" . $wpdb->prefix . PDDK_PREFIX . "residents` (`id`)
                  ON DELETE NO ACTION
                  ON UPDATE NO ACTION,
                CONSTRAINT `fk_relationships_houses1`
                  FOREIGN KEY (`houses_id`)
                  REFERENCES `" . $wpdb->prefix . PDDK_PREFIX . "houses` (`id`)
                  ON DELETE NO ACTION
                  ON UPDATE NO ACTION,
                CONSTRAINT `fk_relationships_relationship_type1`
                  FOREIGN KEY (`relationship_type_id`)
                  REFERENCES `" . $wpdb->prefix . PDDK_PREFIX . "relationship_type` (`id`)
                  ON DELETE NO ACTION
                  ON UPDATE NO ACTION,
                CONSTRAINT `fk_relationships_roles1`
                  FOREIGN KEY (`person1_roles_id`)
                  REFERENCES `" . $wpdb->prefix . PDDK_PREFIX . "roles` (`id`)
                  ON DELETE NO ACTION
                  ON UPDATE NO ACTION,
                CONSTRAINT `fk_relationships_roles2`
                  FOREIGN KEY (`person2_roles_id`)
                  REFERENCES `" . $wpdb->prefix . PDDK_PREFIX . "roles` (`id`)
                  ON DELETE NO ACTION
                  ON UPDATE NO ACTION
	) $charset_collate;";
        dbDelta($sql);
    }

    public function db_update() {
        
    }

    public static function plugin_activation() {
//        if (version_compare($GLOBALS['wp_version'], PDDK_MINIMUM_WP_VERSION, '<')) {
//            load_plugin_textdomain('penduduk');
//
//            $message = '<strong>' . sprintf(esc_html__('Penduduk %s requires WordPress %s or higher.', 'penduduk'), PDDK_VERSION, PDDK_MINIMUM_WP_VERSION) . '</strong> ' . sprintf(__('Please <a href="%1$s">upgrade WordPress</a> to a current version.', 'penduduk'), 'https://codex.wordpress.org/Upgrading_WordPress');
//
//            Akismet::bail_on_activation($message);
//        }
        Pddk_Main::db_install();
    }

    public static function plugin_deactivation() {
        //tidy up
    }

    private static function bail_on_activation($message, $deactivate = true) {
        ?>
        <!doctype html>
        <html>
            <head>
                <meta charset="<?php bloginfo('charset'); ?>">
                <style>
                    * {
                        text-align: center;
                        margin: 0;
                        padding: 0;
                        font-family: "Lucida Grande",Verdana,Arial,"Bitstream Vera Sans",sans-serif;
                    }
                    p {
                        margin-top: 1em;
                        font-size: 18px;
                    }
                </style>
            <body>
                <p><?php echo esc_html($message); ?></p>
            </body>
        </html>
        <?php
        if ($deactivate) {
            $plugins = get_option('active_plugins');
            $akismet = plugin_basename(AKISMET__PLUGIN_DIR . 'akismet.php');
            $update = false;
            foreach ($plugins as $i => $plugin) {
                if ($plugin === $akismet) {
                    $plugins[$i] = false;
                    $update = true;
                }
            }

            if ($update) {
                update_option('active_plugins', array_filter($plugins));
            }
        }
        exit;
    }

    public static function admin_enqueue($hook) {
//        if (!in_array($hook, array('toplevel_page_pddk', 'penduduk_page_pddk-residents', 'penduduk_page_pddk-houses', 'penduduk_page_pddk-settings', 'penduduk_page_pddk-helps'))) {
//            return;
//        }

        wp_enqueue_script('jquery', PDDK_JS_URL . '/jquery-1.11.3.min.js', array(), '1.11.3', TRUE);
        wp_enqueue_script('bootstrap', PDDK_JS_URL . '/bootstrap.min.js', array(), '3.3.5', TRUE);
        wp_enqueue_script('datatables', PDDK_JS_URL . '/datatables.min.js', array(), '1.10.8', TRUE);
        wp_enqueue_script(PDDK_PREFIX . 'js', PDDK_JS_URL . '/js.js', array(), '1.0.6', TRUE);


        wp_enqueue_style(PDDK_PREFIX . 'bootstrap', PDDK_CSS_URL . '/bootstrap.css', array(), '3.3.5.1');
        wp_enqueue_style(PDDK_PREFIX . 'datatables', PDDK_CSS_URL . '/dataTables.bootstrap.css', array(), '3.3.5.1');
        wp_enqueue_style(PDDK_PREFIX . 'fontawesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css', array(), '4.3.0');
        wp_enqueue_style(PDDK_PREFIX . 'style', PDDK_CSS_URL . '/style.css', array(), '1.0.2');
    }

    public static function admin_menu() {

        add_menu_page('Penduduk', 'Penduduk', 'administrator', 'pddk', array(Pddk_Overview::instance(), 'overview'), 'dashicons-groups');
//        add_submenu_page('pddk', 'Penduduk - Overview', 'Overview', 'administrator', 'pddk', array(Pddk_Overview::instance(), 'overview'));
        add_submenu_page('pddk', 'Penduduk - Residents', 'Residents', 'administrator', 'pddk-residents', array(Pddk_Residents::instance(), 'routes'));
        add_submenu_page('pddk', 'Penduduk - Houses', 'Houses', 'administrator', 'pddk-houses', array(Pddk_Houses::instance(), 'routes'));
        add_submenu_page('pddk', 'Penduduk - Settings', 'Settings', 'administrator', 'pddk-settings', array(Pddk_Settings::instance(), 'routes'));
        add_submenu_page('pddk', 'Penduduk - Helps', 'Helps', 'administrator', 'pddk-helps', array(Pddk_Helps::instance(), 'routes'));
    }

}
