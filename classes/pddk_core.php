<?php

class Pddk_Core {

    public $view_data = array();
    public $wpdb;
    public $table_name;

    public static function instance()
    {

//        static $instance = array();
//        $calledClass = get_called_class();
//
//        if (!isset($instances[$calledClass])) {
//            $instances[$calledClass] = new $calledClass();
//        }
//        return $instances[$calledClass];
//        
        if (!isset($instance)) {
            $className = get_called_class();
            $instance = new $className;
            $instance->init();
        }


//        $this->wpdb = $wpdb;

        return $instance;
    }

    public function init()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
    }

    /**
     * Load view file
     * @param type $filename 
     * @param type $view_data array
     * 
     */
    public function view($filename = 'v_404', $view_data = array())
    {

        extract($view_data);
        $file_path = PDDK_VIEW_PATH . '/' . $filename . '.php';

        include(PDDK_VIEW_PATH . '/v_header.php');
        if (file_exists($file_path)) {
            include($file_path);
        }
        include(PDDK_VIEW_PATH . '/v_footer.php');
    }

    public function top_message()
    {
        if (get_option(PDDK_PREFIX . 'top_message') !== FALSE) {
            $this->view_data['msg'] = get_option(PDDK_PREFIX . 'top_message');
            delete_option(PDDK_PREFIX . 'top_message');
        }

        if (isset($this->view_data['msg']['status'])) {
            echo '<div class="alert ' . $this->view_data['msg']['status'] . '" role="alert">' . $this->view_data['msg']['text'] . '</div>';
        }
    }

    public function set_top_message($msg = array())
    {
        update_option(PDDK_PREFIX . 'top_message', $msg);
    }

    /**
     * default route
     */
    public function routes()
    {

        if (isset($_GET['action']) && $_GET['action'] == 'add') {
            $this->add();
        } else if (isset($_GET['action']) && $_GET['action'] == 'edit') {
            $this->edit();
        } else if (isset($_GET['action']) && $_GET['action'] == 'delete') {
            $this->delete();
        } else {
            $this->display();
        }
    }

    /**
     * add page
     */
    public function add()
    {
        $this->view();
    }

    /**
     * edit page
     */
    public function edit()
    {
        $this->view();
    }

    public function delete()
    {
        $class_name = strtolower(get_class($this));
        $this->_delete($_GET['id']);
        $this->view_data['msg'] = array(
            'status' => 'alert-success',
            'text' => 'Success! ' . str_replace(PDDK_PREFIX, '', $class_name) . ' deleted'
        );
        $this->set_top_message($this->view_data['msg']);
        wp_redirect('?page=' . $_GET['page']);
    }

    /**
     * display page
     */
    public function display()
    {
        $this->view();
    }

    public function current_domain($show_protocol = TRUE, $show_tldonly = FALSE)
    {
        $tracking_domain = get_option(PDDK_PREFIX . 'settings_tracking_domain') . '/';

        if (isset($_SERVER['HTTPS'])) {
            $protocol = ($_SERVER['HTTPS'] && $_SERVER['HTTPS'] != "off") ? "https" : "http";
        } else {
            $protocol = 'http';
        }
        return (($show_protocol) ? $protocol . "://" : '') . $_SERVER['HTTP_HOST'] . '/' . (($show_tldonly) ? $tracking_domain : '');
    }

    public function is_valid_integer($int)
    {
        if (!filter_var($int, FILTER_VALIDATE_INT) === false) {
            return TRUE;
        }
        return FALSE;
    }

    public function is_valid_url($url)
    {
        if (!filter_var($url, FILTER_VALIDATE_URL) === false) {
            return TRUE;
        }
        return FALSE;
    }

    public function explode_trim($str = '', $delimiter = ',')
    {
        return array_map('trim', explode($delimiter, $str));
    }

    protected function _delete($id)
    {
        $this->wpdb->delete($this->table_name, array('id' => $id), array('%d'));
    }

    protected function _delete_batch($ids = array())
    {
        foreach ($ids as $id) {
            $this->_delete($id);
        }
    }

    protected function _order(&$params)
    {
        $order = '';
        if (isset($_POST['order'])) {
            $_order = $_POST['order'];
            $order = "ORDER BY %d {$_order[0]['dir']} ";
            $params = array_merge($params, array(intval($_order[0]['column'] + 1)));
        }

        return $order;
    }

    protected function _limit(&$params)
    {

        $limit = '';
        if (isset($_POST['start']) && $_POST['length'] != -1) {
            $limit = "LIMIT %d, %d";
            $params = array_merge($params, array(intval($_POST['start']), intval($_POST['length'])));
        }
        return $limit;
    }

    /**
     * Protected constructor to prevent creating a new instance of the
     * *Singleton* via the `new` operator from outside of this class.
     */
    protected function __construct()
    {
        
    }

    /**
     * Private clone method to prevent cloning of the instance of the
     * *Singleton* instance.
     *
     * @return void
     */
    private function __clone()
    {
        
    }

    /**
     * Private unserialize method to prevent unserializing of the *Singleton*
     * instance.
     *
     * @return void
     */
    private function __wakeup()
    {
        
    }

    protected function _post($field_name, $default = '')
    {
        return isset($_POST[$field_name]) ? $_POST[$field_name] : $default;
    }

}
