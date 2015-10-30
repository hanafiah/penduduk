<?php

class Pddk_Residents extends Pddk_Core {

    private $table_relationship_type;
    private $table_roles;
    private $table_houses;
    private $table_relationships;
    private $table_residents;

    public function init() {
        global $wpdb;
        $this->wpdb = $wpdb;

        $this->table_name = $this->wpdb->prefix . PDDK_PREFIX . 'residents';
        $this->table_relationship_type = $this->wpdb->prefix . PDDK_PREFIX . 'relationship_type';
        $this->table_roles = $this->wpdb->prefix . PDDK_PREFIX . 'roles';
        $this->table_houses = $this->wpdb->prefix . PDDK_PREFIX . 'houses';
        $this->table_relationships = $this->wpdb->prefix . PDDK_PREFIX . 'relationships';
        $this->table_residents = $this->wpdb->prefix . PDDK_PREFIX . 'residents';

        //pddk_residents_datatable
        add_action('wp_ajax_' . PDDK_PREFIX . 'residents_datatable', array($this, 'ajax_datatable'));
        add_action('wp_ajax_' . PDDK_PREFIX . 'delete', array($this, 'ajax_delete'));
    }

    public function display() {

        add_action('admin_footer', array($this, 'page_js'));

        $this->view('residents/v_residents', array('data' => 'hello world'));
    }

    public function add() {
        $this->view_data['house_id'] = isset($_GET['house_id']) ? $_GET['house_id'] : FALSE;
        if ($this->view_data['house_id'] !== FALSE) {
            $this->view_data['relationship_type'] = $this->_get_relationship_type();
            $this->view_data['roles'] = $this->_get_roles();
            $this->view_data['house'] = $this->_house_by_id($this->view_data['house_id']);
            $this->view_data['owner'] = $this->_owner_by_id($this->view_data['house_id']);
            $this->view_data['hof'] = $this->_hof_by_id($this->view_data['house_id']);
        }

        if (isset($_POST['btnAdd'])) {
            $data = array(
                'first_name' => ucwords(strtolower($this->_post('first_name'))),
                'last_name' => ucwords(strtolower($this->_post('last_name'))),
                'gender' => $this->_post('gender'),
                'nric' => strtoupper($this->_post('nric')),
                'dob' => strtolower($this->_post('dob')),
                'phone' => strtolower($this->_post('phone')),
                'email' => strtolower($this->_post('email')),
                'facebook' => strtolower($this->_post('facebook')),
                'occupation' => ucwords(strtolower($this->_post('occupation'))),
            );
            $this->wpdb->insert($this->table_name, $data);
            $id = $this->wpdb->insert_id;

            $redirect = '?page=pddk-residents&action=edit&id=' . $id;

            if ($this->view_data['house_id'] !== FALSE) {
                /**
                 * update house owner & head of family
                 */
                $data = array();

                /**
                 * set owner
                 */
                if ($this->_post('is_owner', FALSE)) {
                    $data['owner_id'] = $id;
                }

                /**
                 * set tenant / head of family
                 */
                if ($this->_post('is_hof', FALSE)) {
                    $data['residents_id'] = $id;
                }
                if (count($data) > 0) {
                    $this->wpdb->update($this->table_houses, $data, array('id' => $this->view_data['house_id']));
                }

                /**
                 * update relation
                 */
                if (!(bool) $this->_post('is_owner', FALSE) && !(bool) $this->_post('is_hof', FALSE)) {
                    $data = array(
                        $this->view_data['house_id'],
                        $this->_post('person_id'),
                        $id,
                        $this->_post('relationship_type_id'),
                        $this->_post('person1_roles_id'),
                        $this->_post('person2_roles_id'),
                        $this->_post('relationship_type_id'),
                        $this->_post('relationship_type_id') != 3 ? $this->_post('person1_roles_id') : NULL,
                        $this->_post('relationship_type_id') != 3 ? $this->_post('person2_roles_id') : NULL,
                    );
                    $sql = $this->wpdb->prepare("INSERT INTO {$this->table_relationships} (houses_id, person1_id, person2_id, relationship_type_id, person1_roles_id, person2_roles_id) VALUES (%d,%d,%d,%d,%d,%d) ON DUPLICATE KEY UPDATE relationship_type_id=%d ,person1_roles_id=%d , person2_roles_id=%d", $data);
                    $this->wpdb->query($sql);
                    $redirect = isset($_GET['return']) ? '?' . urldecode($_GET['return']) : $redirect;
                } else {
                    /**
                     * is owner or hof
                     */
                    if ($this->_post('is_owner', FALSE) && $this->_post('is_hof', FALSE)) {
                        $role = ROLE_HOUSE_OWNER_HOF;
                    } else if ($this->_post('is_owner', FALSE)) {
                        $role = ROLE_HOUSE_OWNER;
                    } else if ($this->_post('is_hof', FALSE)) {
                        $role = ROLE_HOF;
                    }

                    $data = array(
                        $this->view_data['house_id'],
                        $id,
                        $id,
                        TENANT,
                        $role,
                        TENANT,
                        $role
                    );
                    $sql = $this->wpdb->prepare("INSERT INTO {$this->table_relationships} (houses_id, person1_id, person2_id, relationship_type_id, person1_roles_id, person2_roles_id) VALUES (%d,%d,%d,%d,NULL,%d) ON DUPLICATE KEY UPDATE relationship_type_id=%d ,person1_roles_id=NULL , person2_roles_id=%d", $data);
                    $this->wpdb->query($sql);
//                    $this->wpdb->show_errors();
                    $redirect = isset($_GET['return']) ? '?' . urldecode($_GET['return']) : $redirect;
                }
            }
            $this->view_data['msg'] = array(
                'status' => 'alert-success',
                'text' => 'Success! Save changes'
            );

            $this->set_top_message($this->view_data['msg']);
            wp_redirect($redirect);
            exit();
        }

        $this->view('residents/v_residents-new', $this->view_data);
    }

    public function edit() {
        $id = $_GET['id'];

        $this->view_data['house_id'] = isset($_GET['house_id']) ? $_GET['house_id'] : FALSE;
        if ($this->view_data['house_id'] !== FALSE) {
            $this->view_data['relationship_type'] = $this->_get_relationship_type();

            $this->view_data['roles'] = $this->_get_roles();
            $this->view_data['house'] = $this->_house_by_id($this->view_data['house_id']);
            $this->view_data['owner'] = $this->_owner_by_id($this->view_data['house_id']);
            $this->view_data['hof'] = $this->_hof_by_id($this->view_data['house_id']);

            $this->view_data['relationship'] = $this->_get_relationship($this->view_data['house_id'], $id);
        }


        if (isset($_POST['btnEdit'])) {

            $data = array(
                'first_name' => ucwords(strtolower($this->_post('first_name'))),
                'last_name' => ucwords(strtolower($this->_post('last_name'))),
                'gender' => $this->_post('gender'),
                'nric' => strtoupper($this->_post('nric')),
                'dob' => strtolower($this->_post('dob')),
                'phone' => strtolower($this->_post('phone')),
                'email' => strtolower($this->_post('email')),
                'facebook' => strtolower($this->_post('facebook')),
                'occupation' => ucwords(strtolower($this->_post('occupation'))),
            );

            $updates = $this->wpdb->update($this->table_name, $data, array('id' => $id));

            $redirect = '?page=pddk-residents&action=edit&id=' . $id;
            if ($this->view_data['house_id'] !== FALSE) {
                /**
                 * update house owner & head of family
                 */
                $data = array();

                /**
                 * set owner
                 */
                if ($this->_post('is_owner', FALSE)) {
                    $data['owner_id'] = $id;
                }

                /**
                 * set tenant / head of family
                 */
                if ($this->_post('is_hof', FALSE)) {
                    $data['residents_id'] = $id;
                }
                if (count($data) > 0) {
                    $this->wpdb->update($this->table_houses, $data, array('id' => $this->view_data['house_id']));
                }

                /**
                 * update relation
                 */
                if (!$this->_post('is_owner', FALSE) && !$this->_post('is_hof', FALSE)) {
                    $data = array(
                        $this->view_data['house_id'],
                        $this->_post('person_id'),
                        $id,
                        $this->_post('relationship_type_id'),
                        $this->_post('person1_roles_id'),
                        $this->_post('person2_roles_id'),
                        $this->_post('relationship_type_id'),
                        $this->_post('relationship_type_id') != 3 ? $this->_post('person1_roles_id') : NULL,
                        $this->_post('relationship_type_id') != 3 ? $this->_post('person2_roles_id') : NULL,
                    );
//                    $this->wpdb->prepare("DELETE FROM {$this->table_relationships} WHERE houses_id=%d AND person2_id=%d ");
                    $sql = $this->wpdb->prepare("INSERT INTO {$this->table_relationships} (houses_id, person1_id, person2_id, relationship_type_id, person1_roles_id, person2_roles_id) VALUES (%d,%d,%d,%d,%d,%d) ON DUPLICATE KEY UPDATE relationship_type_id=%d ,person1_roles_id=%d , person2_roles_id=%d", $data);
                    $this->wpdb->query($sql);
                }
            }

            $this->view_data['msg'] = array(
                'status' => 'alert-success',
                'text' => 'Success! Save changes'
            );
            $this->set_top_message($this->view_data['msg']);

            $redirect = isset($_GET['return']) ? '?' . urldecode($_GET['return']) : $redirect;
            wp_redirect($redirect);
        }
        $this->view_data['resident'] = $this->_by_id();
        if (!isset($this->view_data['resident']->id)) {
            $this->view();
            exit;
        }
        $this->view('residents/v_residents-edit', $this->view_data);
    }

    public function delete() {
        $class_name = strtolower(get_class($this));

        $this->_delete($_GET['id']);
        $this->view_data['msg'] = array(
            'status' => 'alert-success',
            'text' => 'Success! ' . str_replace(PDDK_PREFIX, '', $class_name) . ' deleted'
        );
        $this->set_top_message($this->view_data['msg']);

        $redirect = '?page=' . $_GET['page'];
        $redirect = isset($_GET['return']) ? '?' . urldecode($_GET['return']) : $redirect;
        wp_redirect($redirect);
    }

    protected function _delete($id) {
        /**
         * delete related data to current resident
         */
        $this->wpdb->delete($this->table_relationships, array('person2_id' => $id), array('%d'));
        $this->wpdb->delete($this->table_relationships, array('person1_id' => $id), array('%d'));
        $this->wpdb->delete($this->table_name, array('id' => $id), array('%d'));
    }

    public function page_js() {
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function () {
                var oTable = jQuery('#v_residents').DataTable({
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
                                "action": 'pddk_residents_datatable'
                            });
                        },
                    },
                    "order": [[0, "desc"]],
                    "columnDefs": [
                        {
                            "targets": 6,
                            "orderable": false
                        },
                    ]
                });
                //                jQuery('#bulk_action').html('<select name="slcAction" class="form-control input-sm slcAction"><option>Bulk Actions</option><option>Delete</option></select> <button data-action="srty_delete" class="btnAction btn btn-default btn-sm" type="submit">Apply</button>');
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
                $where = " WHERE ( first_name LIKE %s OR last_name LIKE %s OR nric LIKE %s )";
                $params = array_merge($params, array('%%' . $_search['value'] . '%%', '%%' . $_search['value'] . '%%', '%%' . $_search['value'] . '%%'));
            }
        }

        $order = $this->_order($params);
        $limit = $this->_limit($params);

        $results = $this->wpdb->get_results($this->wpdb->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM {$this->table_name} {$where} {$order} {$limit}", $params));
        $found = $this->wpdb->get_row("SELECT FOUND_ROWS() AS total;");

        $data = array();
        foreach ($results as $row) {
            $data[] = array(
                $row->first_name,
                $row->last_name,
                $row->gender,
                $row->nric,
                $row->phone,
                $row->email,
                '<div class="btn-group btn-group-xs">'
                . '<a href="?page=pddk-residents&action=edit&id=' . $row->id . '" class="btn btn-default">edit</a>'
                . '<a href="?page=pddk-residents&action=delete&id=' . $row->id . '" class="btn btn-default confirm">delete</a>'
                . '</div>',
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

    private function _get_relationship_type() {
        return $this->wpdb->get_results("SELECT * FROM $this->table_relationship_type ");
    }

    private function _get_relationship($house_id, $person_2) {
        return $this->wpdb->get_row($this->wpdb->prepare("SELECT * FROM $this->table_relationships WHERE houses_id=%d AND person2_id=%d ", $house_id, $person_2));
    }

    private function _get_roles() {
        return $this->wpdb->get_results("SELECT * FROM $this->table_roles ");
    }

    private function _house_by_id($id = FALSE) {
        if ($id === FALSE) {
            return FALSE;
        }

        return $this->wpdb->get_row($this->wpdb->prepare("SELECT * FROM $this->table_houses WHERE id=%d", $id));
    }

    private function _owner_by_id($id = FALSE) {
        if ($id === FALSE) {
            return FALSE;
        }

        return $this->wpdb->get_row($this->wpdb->prepare("SELECT a.* FROM $this->table_residents a INNER JOIN $this->table_houses b ON a.id = b.owner_id WHERE b.id=%d", $id));
    }

    private function _hof_by_id($id = FALSE) {
        if ($id === FALSE) {
            return FALSE;
        }

        return $this->wpdb->get_row($this->wpdb->prepare("SELECT a.* FROM $this->table_residents a INNER JOIN $this->table_houses b ON a.id = b.residents_id WHERE b.id=%d", $id));
    }

}
