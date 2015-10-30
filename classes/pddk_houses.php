<?php

class Pddk_Houses extends Pddk_Core {

    private $id;
    private $table_residents;
    private $table_relationship;
    private $table_relationship_type;
    private $table_roles;

    public function init() {
        global $wpdb;
        $this->wpdb = $wpdb;

        $this->table_name = $this->wpdb->prefix . PDDK_PREFIX . 'houses';
        $this->table_residents = $this->wpdb->prefix . PDDK_PREFIX . 'residents';
        $this->table_relationship = $this->wpdb->prefix . PDDK_PREFIX . 'relationships';
        $this->table_relationship_type = $this->wpdb->prefix . PDDK_PREFIX . 'relationship_type';
        $this->table_roles = $this->wpdb->prefix . PDDK_PREFIX . 'roles';
        //pddk_residents_datatable
        add_action('wp_ajax_' . PDDK_PREFIX . 'houses_datatable', array($this, 'ajax_datatable'));
        add_action('wp_ajax_' . PDDK_PREFIX . 'house_residents_datatable', array($this, 'ajax_residents_datatable'));
        add_action('wp_ajax_' . PDDK_PREFIX . 'delete', array($this, 'ajax_delete'));
    }

    public function display() {

        add_action('admin_footer', array($this, 'page_js'));

        $this->view('houses/v_houses', array('data' => 'hello world'));
    }

    public function add() {
        global $countries;
//        global $wpdb;
//        $this->wpdb = $wpdb;
//
//        $this->table_name = $this->wpdb->prefix . PDDK_PREFIX . 'houses';

        if (isset($_POST['btnAdd'])) {
            $this->wpdb->insert(
                    $this->table_name, array(
                'house_no' => $this->_post('house_no'),
                'ptb' => strtoupper($this->_post('ptb')),
                'addr1' => ucwords(strtolower($this->_post('addr1'))),
                'addr2' => ucwords(strtolower($this->_post('addr2'))),
                'postcode' => $this->_post('postcode'),
                'city' => ucwords(strtolower($this->_post('city'))),
                'state' => ucwords(strtolower($this->_post('state'))),
                'country_code' => $this->_post('country_code'),
                'country' => ucwords(strtolower($countries[$this->_post('country_code')])),
                    )
            );
            $id = $this->wpdb->insert_id;
            $this->view_data['msg'] = array(
                'status' => 'alert-success',
                'text' => 'Success! Save changes'
            );
            $this->set_top_message($this->view_data['msg']);
            wp_redirect('?page=pddk-houses&action=edit&id=' . $id);
            exit();
        }

        $this->view_data['countries'] = $countries;
        $this->view('houses/v_houses-new', $this->view_data);
    }

    public function edit() {
        global $countries;
//        global $wpdb;
//        $this->wpdb = $wpdb;
//
//        $this->table_name = $this->wpdb->prefix . PDDK_PREFIX . 'houses';

        $this->id = $_GET['id'];

        if (isset($_POST['btnEdit'])) {

            $updates = $this->wpdb->update(
                    $this->table_name, array(
                'house_no' => $this->_post('house_no'),
                'ptb' => strtoupper($this->_post('ptb')),
                'addr1' => ucwords(strtolower($this->_post('addr1'))),
                'addr2' => ucwords(strtolower($this->_post('addr2'))),
                'postcode' => $this->_post('postcode'),
                'city' => ucwords(strtolower($this->_post('city'))),
                'state' => ucwords(strtolower($this->_post('state'))),
                'country_code' => $this->_post('country_code'),
                'country' => ucwords(strtolower($countries[$this->_post('country_code')])),
                    ), array('id' => $this->id)
            );

            $this->view_data['msg'] = array(
                'status' => 'alert-success',
                'text' => 'Success! Save changes'
            );
        }
        $this->view_data['house'] = $this->_by_id();
        $this->view_data['countries'] = $countries;

        add_action('admin_footer', array($this, 'house_edit_js'));
        $this->view('houses/v_houses-edit', $this->view_data);
    }

    public function page_js() {
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function () {

                var oTable = jQuery('#v_houses').DataTable({
                    "dom": '<"panel panel-default"<"panel-heading"<"form form-inline clearfix"<"pull-left"<"#bulk_action.form-group">><"pull-right"<"form-group"f><"form-group"l>>>> <"table-responsive"t><"pull-left"i><"pull-right"p>>',
                    "language": {
                        "search": '',
                        "lengthMenu": '&nbsp;_MENU_'
                    },
                    "serverSide": true,
                    "ajax": {
                        "url": ajaxurl,
                        "type": "POST",
                        "data": function (d) {
                            return jQuery.extend({}, d, {
                                "action": 'pddk_houses_datatable'
                            });
                        },
                    },
                    "order": [[0, "desc"]],
                    "columnDefs": [
                        {
                            "targets": 4,
                            "orderable": false
                        },
                    ]
                });

            });

        </script>
        <?php

    }

    public function house_edit_js() {
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function () {
                var post_name = jQuery('#v_house_residents').data('ajax-lookup');
                var post_value = jQuery('#v_house_residents').data('ajax-value');
                var oTableResident = jQuery('#v_house_residents').DataTable({
                    "dom": '<"panel panel-default"<"panel-heading"<"form form-inline clearfix"<"pull-left"<"#bulk_action.form-group">><"pull-right"<"form-group"f><"form-group"l>>>> <"table-responsive"t><"pull-left"i><"pull-right"p>>',
                    "language": {
                        "search": '',
                        "lengthMenu": '&nbsp;_MENU_'
                    },
                    "serverSide": true,
                    "ajax": {
                        "url": ajaxurl,
                        "type": "POST",
                        "data": function (d) {
                            return jQuery.extend({}, d, {
                                "action": 'pddk_house_residents_datatable',
                                [post_name]:post_value
                            });
                        },
                    },
                    "order": [[0, "desc"]],
                    "columnDefs": [
                        {
                            "targets": 7,
                            "orderable": false
                        },
                    ]
                });

                console.log(oTableResident);
            });

        </script>
        <?php

    }

    /**
     * ajax call
     */
    public function ajax_datatable() {
        wp_send_json($this->_datatable_get_all());
        wp_die();
    }

    public function ajax_residents_datatable() {
        $houses_id = isset($_POST['houses_id']) ? $_POST['houses_id'] : FALSE;
        wp_send_json($this->_datatable_get_all_residents($houses_id));
        wp_die();
    }

    public function ajax_delete() {

        $this->_delete_batch($this->explode_trim($_POST['ids']));
        $this->view_data['msg'] = array(
            'status' => 'alert-success',
            'text' => 'Success! links deleted'
        );
        $this->set_top_message($this->view_data['msg']);
        wp_send_json(array('result' => 1));
        wp_die();
    }

    /**
     * DB section
     */

    /**
     * 
     * @global type $wpdb
     * @return type
     */
    private function _by_id($id = FALSE) {
        if ($id === FALSE) {
            $id = isset($_GET['id']) ? $_GET['id'] : -1;
        }

        return $this->wpdb->get_row($this->wpdb->prepare("SELECT * FROM $this->table_name WHERE id=%d", $id));
    }

    private function _get_all() {
        return $this->wpdb->get_results("SELECT * FROM $this->table_name ");
    }

    private function _datatable_get_all() {

        $params = array();


        $where = '';
        if (isset($_POST['search'])) {
            $_search = $_POST['search'];
            if (trim($_search['value']) != '') {
                $where = " WHERE ( ptb LIKE %s OR house_no LIKE %s OR addr1 LIKE %s )";
                $params = array_merge($params, array('%%' . $_search['value'] . '%%', '%%' . $_search['value'] . '%%', '%%' . $_search['value'] . '%%'));
            }
        }

        $order = $this->_order($params);
        $limit = $this->_limit($params);

        $results = $this->wpdb->get_results($this->wpdb->prepare("SELECT SQL_CALC_FOUND_ROWS a.*,(SELECT COUNT(*) FROM {$this->table_relationship} WHERE {$this->table_relationship}.houses_id=a.id) AS total_residents  FROM {$this->table_name} a {$where} {$order} {$limit}", $params));
        $found = $this->wpdb->get_row("SELECT FOUND_ROWS() AS total;");
//        $record = $this->wpdb->get_row("SELECT COUNT(id) AS total FROM $this->table_name;");
        $data = array();
        foreach ($results as $row) {
            $data[] = array(
                $row->ptb,
                $row->house_no,
                $row->addr1,
                $row->total_residents,
                '<div class="btn-group btn-group-xs">'
                . '<a href="?page=pddk-houses&action=edit&id=' . $row->id . '" class="btn btn-default">details</a>'
                . '<a href="?page=pddk-houses&action=delete&id=' . $row->id . '" class="btn btn-default confirm">delete</a>'
                . '</div>',
                $row->addr2,
                $row->city,
                $row->postcode,
                $row->state,
                $row->country,
            );
        }

        return array(
            "draw" => intval($_POST['draw']),
            "recordsTotal" => intval($found->total),
            "recordsFiltered" => intval($found->total),
            "data" => $data
        );
    }

    private function _datatable_get_all_residents($houses_id = FALSE) {

        $params = array();


        $where = '';
        if (isset($_POST['search'])) {
            $_search = $_POST['search'];
            if (trim($_search['value']) != '') {
                $where = " WHERE ( a.first_name LIKE %s OR a.last_name LIKE %s OR a.nric LIKE %s )";
                $params = array_merge($params, array('%%' . $_search['value'] . '%%', '%%' . $_search['value'] . '%%', '%%' . $_search['value'] . '%%'));
            }
        }

        $where .= " AND b.houses_id = %d ";
        $params = array_merge($params, array($houses_id));

        $order = $this->_order($params);
        $limit = $this->_limit($params);

        $results = $this->wpdb->get_results($this->wpdb->prepare("SELECT SQL_CALC_FOUND_ROWS a.*, c.description AS relationship_desc FROM {$this->table_residents} a INNER JOIN {$this->table_relationship} b ON a.id = b.person2_id LEFT JOIN {$this->table_roles} c ON b.person2_roles_id = c.id {$where} {$order} {$limit}", $params));
        $found = $this->wpdb->get_row("SELECT FOUND_ROWS() AS total;");
        $record = $this->wpdb->get_row("SELECT COUNT(a.id) AS total FROM {$this->table_residents} a INNER JOIN {$this->table_relationship} b ON a.id = b.person1_id;");

        $data = array();
        foreach ($results as $row) {
            $data[] = array(
                $row->first_name,
                $row->last_name,
                $row->gender,
                $row->nric,
                $row->phone,
                $row->email,
                $row->relationship_desc,
                '<div class="btn-group btn-group-xs">'
                . '<a href="?page=pddk-residents&action=edit&id=' . $row->id . '&house_id=' . $houses_id . '&return=' . urlencode('page=pddk-houses&action=edit&id=' . $houses_id) . '" class="btn btn-default">Edit</a>'
                . '<a href="?page=pddk-residents&action=delete&id=' . $row->id . '&house_id=' . $houses_id . '&return=' . urlencode('page=pddk-houses&action=edit&id=' . $houses_id) . '" class="btn btn-default">Delete</a>'
                . '<div>',
            );
        }

        return array(
            "draw" => intval($_POST['draw']),
            "recordsTotal" => intval($found->total),
            "recordsFiltered" => intval($found->total),
            "data" => $data
        );
    }

    private function _filter($request, $columns) {
        $where = '';
        return $where;
    }

}
