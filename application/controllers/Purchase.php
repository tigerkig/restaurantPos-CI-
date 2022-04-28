<?php
/*
  ###########################################################
  # PRODUCT NAME: 	iRestora PLUS - Next Gen Restaurant POS
  ###########################################################
  # AUTHER:		Doorsoft
  ###########################################################
  # EMAIL:		info@doorsoft.co
  ###########################################################
  # COPYRIGHTS:		RESERVED BY Door Soft
  ###########################################################
  # WEBSITE:		http://www.doorsoft.co
  ###########################################################
  # This is Purchase Controller
  ###########################################################
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Purchase extends Cl_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Authentication_model');
        $this->load->model('Purchase_model');
        $this->load->model('Master_model');
        $this->load->model('Common_model');
        $this->Common_model->setDefaultTimezone();
        $this->load->library('form_validation');
        
        if (!$this->session->has_userdata('user_id')) {
            redirect('Authentication/index');
        }
        if (!$this->session->has_userdata('outlet_id')) {
            $this->session->set_flashdata('exception_2', lang('please_click_green_button'));

            $this->session->set_userdata("clicked_controller", $this->uri->segment(1));
            $this->session->set_userdata("clicked_method", $this->uri->segment(2));
            redirect('Outlet/outlets');
        }
        $getAccessURL = ucfirst($this->uri->segment(1));
        if (!in_array($getAccessURL, $this->session->userdata('menu_access'))) {
            redirect('Authentication/userProfile');
        }
        $login_session['active_menu_tmp'] = '';
        $this->session->set_userdata($login_session);
    }

     /**
     * purchases info
     * @access public
     * @return void
     * @param no
     */
    public function purchases() {
        $outlet_id = $this->session->userdata('outlet_id');
        $data = array();
        $data['purchases'] = $this->Common_model->getAllByOutletId($outlet_id, "tbl_purchase");
        $data['main_content'] = $this->load->view('purchase/purchases', $data, TRUE);
        $this->load->view('userHome', $data);
    }
     /**
     * delete Purchase
     * @access public
     * @return void
     * @param int
     */
    public function deletePurchase($id) {
        $id = $this->custom->encrypt_decrypt($id, 'decrypt');
        $this->Common_model->deleteStatusChangeWithChild($id, $id, "tbl_purchase", "tbl_purchase_ingredients", 'id', 'purchase_id');
        $this->session->set_flashdata('exception', lang('delete_success'));
        redirect('Purchase/purchases');
    }
     /**
     * add/Edit Purchase
     * @access public
     * @return void
     * @param int
     */
    public function addEditPurchase($encrypted_id = "") {


        $id = $this->custom->encrypt_decrypt($encrypted_id, 'decrypt');
        $outlet_id = $this->session->userdata('outlet_id');
        $company_id = $this->session->userdata('company_id');

        $purchase_info = array();

        if ($id == "") {
            $purchase_info['reference_no'] = $this->Purchase_model->generatePurRefNo($outlet_id);
        } else {
            $purchase_info['reference_no'] = $this->Common_model->getDataById($id, "tbl_purchase")->reference_no;
        }

        if ($this->input->post('submit')) {

            $this->form_validation->set_rules('reference_no', lang('ref_no'), 'required|max_length[50]');
            $this->form_validation->set_rules('supplier_id', lang('supplier'), 'required|max_length[50]');
            $this->form_validation->set_rules('date', lang('date'), 'required|max_length[50]');
            $this->form_validation->set_rules('note', lang('note'), 'max_length[200]');
            $this->form_validation->set_rules('paid', lang('paid_amount'), 'required|numeric|max_length[50]');
            if ($this->form_validation->run() == TRUE) {
                $purchase_info['reference_no'] =htmlspecialchars($this->input->post($this->security->xss_clean('reference_no')));
                $purchase_info['supplier_id'] =htmlspecialchars($this->input->post($this->security->xss_clean('supplier_id')));
                $purchase_info['date'] = date('Y-m-d', strtotime($this->input->post($this->security->xss_clean('date'))));
                $purchase_info['note'] =htmlspecialchars($this->input->post($this->security->xss_clean('note')));
                $purchase_info['grand_total'] =htmlspecialchars($this->input->post($this->security->xss_clean('grand_total')));
                $purchase_info['paid'] =htmlspecialchars($this->input->post($this->security->xss_clean('paid')));
                $purchase_info['due'] =htmlspecialchars($this->input->post($this->security->xss_clean('due')));
                $purchase_info['user_id'] = $this->session->userdata('user_id');
                $purchase_info['outlet_id'] = $this->session->userdata('outlet_id');

                if ($id == "") {
                    $purchase_id = $this->Common_model->insertInformation($purchase_info, "tbl_purchase");
                    $this->savePurchaseIngredients($_POST['ingredient_id'], $purchase_id, 'tbl_purchase_ingredients');
                    $this->session->set_flashdata('exception', lang('insertion_success'));
                } else {
                    $this->Common_model->updateInformation($purchase_info, $id, "tbl_purchase");
                    $this->Common_model->deletingMultipleFormData('purchase_id', $id, 'tbl_purchase_ingredients');
                    $this->savePurchaseIngredients($_POST['ingredient_id'], $id, 'tbl_purchase_ingredients');
                    $this->session->set_flashdata('exception',lang('update_success'));
                }

                redirect('Purchase/purchases');
            } else {
                if ($id == "") {
                    $data = array();
                    $data['pur_ref_no'] = $this->Purchase_model->generatePurRefNo($outlet_id);
                    $data['suppliers'] = $this->Common_model->getAllByCompanyIdForDropdown($company_id, 'tbl_suppliers');
                    $data['ingredients'] = $this->Purchase_model->getIngredientListWithUnitAndPrice($company_id);
                    $data['main_content'] = $this->load->view('purchase/addPurchase', $data, TRUE);
                    $this->load->view('userHome', $data);
                } else {
                    $data = array();
                    $data['encrypted_id'] = $encrypted_id;
                    $data['purchase_details'] = $this->Common_model->getDataById($id, "tbl_purchase");
                    $data['suppliers'] = $this->Common_model->getAllByCompanyIdForDropdown($company_id, 'tbl_suppliers');
                    $data['ingredients'] = $this->Purchase_model->getIngredientListWithUnitAndPrice($company_id);
                    $data['purchase_ingredients'] = $this->Purchase_model->getPurchaseIngredients($id);
                    $data['main_content'] = $this->load->view('purchase/editPurchase', $data, TRUE);
                    $this->load->view('userHome', $data);
                }
            }
        } else {
            if ($id == "") {
                $data = array();
                $data['pur_ref_no'] = $this->Purchase_model->generatePurRefNo($outlet_id);
                $data['suppliers'] = $this->Common_model->getAllByCompanyIdForDropdown($company_id, 'tbl_suppliers');
                ;
                $data['ingredients'] = $this->Purchase_model->getIngredientListWithUnitAndPrice($company_id);
                $data['main_content'] = $this->load->view('purchase/addPurchase', $data, TRUE);
                $this->load->view('userHome', $data);
            } else {
                $data = array();
                $data['encrypted_id'] = $encrypted_id;
                $data['purchase_details'] = $this->Common_model->getDataById($id, "tbl_purchase");
                $data['suppliers'] = $this->Common_model->getAllByCompanyIdForDropdown($company_id, 'tbl_suppliers');
                $data['ingredients'] = $this->Purchase_model->getIngredientListWithUnitAndPrice($company_id);
                $data['purchase_ingredients'] = $this->Purchase_model->getPurchaseIngredients($id);
                $data['main_content'] = $this->load->view('purchase/editPurchase', $data, TRUE);
                $this->load->view('userHome', $data);
            }
        }
    }
     /**
     * save Purchase Ingredients
     * @access public
     * @return void
     * @param string
     * @param int
     * @param string
     */
    public function savePurchaseIngredients($purchase_ingredients, $purchase_id, $table_name) {
        foreach ($purchase_ingredients as $row => $ingredient_id):
            $fmi = array();
            $fmi['ingredient_id'] = $_POST['ingredient_id'][$row];
            $fmi['unit_price'] = $_POST['unit_price'][$row];
            $fmi['quantity_amount'] = $_POST['quantity_amount'][$row];
            $fmi['total'] = $_POST['total'][$row];
            $fmi['purchase_id'] = $purchase_id;
            $fmi['outlet_id'] = $this->session->userdata('outlet_id');
            $this->Common_model->insertInformation($fmi, "tbl_purchase_ingredients");
        endforeach;
    }
     /**
     * purchase Details
     * @access public
     * @return void
     * @param int
     */
    public function purchaseDetails($id) {
        $encrypted_id = $id;
        $id = $this->custom->encrypt_decrypt($id, 'decrypt');

        $data = array();
        $data['encrypted_id'] = $encrypted_id;
        $data['purchase_details'] = $this->Common_model->getDataById($id, "tbl_purchase");
        $data['purchase_ingredients'] = $this->Purchase_model->getPurchaseIngredients($id);
        $data['main_content'] = $this->load->view('purchase/purchaseDetails', $data, TRUE);
        $this->load->view('userHome', $data);
    }
     /**
     * add New Supplier By Ajax
     * @access public
     * @return object
     * @param no
     */
    public function addNewSupplierByAjax() {
        $data['name'] = $_GET['name'];
        $data['contact_person'] = $_GET['contact_person'];
        $data['phone'] = $_GET['phone'];
        $data['email'] = $_GET['emailAddress'];
        $data['address'] = $_GET['supAddress'];
        $data['description'] = $_GET['description'];
        $data['user_id'] = $this->session->userdata('user_id');
        $data['company_id'] = $this->session->userdata('company_id');
        $this->db->insert('tbl_suppliers', $data);
        $supplier_id = $this->db->insert_id();
        $data1 = array('supplier_id' => $supplier_id);
        echo json_encode($data1);
    }
     /**
     * get Supplier List
     * @access public
     * @return void
     * @param no
     */
    public function getSupplierList() {
        $company_id = $this->session->userdata('company_id');
        $data1 = $this->db->query("SELECT * FROM tbl_suppliers 
              WHERE company_id=$company_id")->result();
        //generate html content for view
        echo '<option value="">Select</option>';
        foreach ($data1 as $value) {
            echo '<option value="' . $value->id . '" >' . $value->name . '</option>';
        }
        exit;
    }
}
