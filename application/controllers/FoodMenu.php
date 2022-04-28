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
  # This is FoodMenu Controller
  ###########################################################
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class FoodMenu extends Cl_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('excel'); //load PHPExcel library
        $this->load->model('Common_model');
        $this->load->model('Master_model');
        $this->load->library('form_validation');
        $this->Common_model->setDefaultTimezone();

        if (!$this->session->has_userdata('user_id')) {
            redirect('Authentication/index');
        }
        $getAccessURL = ucfirst($this->uri->segment(1));
        if (!in_array($getAccessURL, $this->session->userdata('menu_access'))) {
            redirect('Authentication/userProfile');
        }
        $login_session['active_menu_tmp'] = '';
        $this->session->set_userdata($login_session);
    }

     /**
     * food menu info
     * @access public
     * @return void
     * @param no
     */
    public function foodMenus() {
        $company_id = $this->session->userdata('company_id');

        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('category_id', lang('category'), 'required|max_length[50]');
            if ($this->form_validation->run() == TRUE) {
                $category_id =htmlspecialchars($this->input->post($this->security->xss_clean('category_id')));
                $data = array();
                $data['foodMenus'] = $this->Common_model->getAllFoodMenusByCategory($category_id, "tbl_food_menus");
                $data['foodMenuCategories'] = $this->Common_model->getAllByCompanyIdForDropdown($company_id, "tbl_food_menu_categories");
                $data['main_content'] = $this->load->view('master/foodMenu/foodMenus', $data, TRUE);
                $this->load->view('userHome', $data);
            } else {
                $data = array();

                $data['foodMenus'] = $this->Common_model->getAllByCompanyId($company_id, "tbl_food_menus");
                $data['foodMenuCategories'] = $this->Common_model->getAllByCompanyIdForDropdown($company_id, "tbl_food_menu_categories");
                $data['main_content'] = $this->load->view('master/foodMenu/foodMenus', $data, TRUE);
                $this->load->view('userHome', $data);
            }
        } else {
            $data = array();
            $data['foodMenus'] = $this->Common_model->getAllByCompanyId($company_id, "tbl_food_menus");
            $data['foodMenuCategories'] = $this->Common_model->getAllByCompanyIdForDropdown($company_id, "tbl_food_menu_categories");
            $data['main_content'] = $this->load->view('master/foodMenu/foodMenus', $data, TRUE);
            $this->load->view('userHome', $data);
        }
    }
     /**
     * delete food menu
     * @access public
     * @return void
     * @param int
     */
    public function deleteFoodMenu($id) {
        $id = $this->custom->encrypt_decrypt($id, 'decrypt');

        $this->Common_model->deleteStatusChangeWithChild($id, $id, "tbl_food_menus", "tbl_food_menus_ingredients", 'id', 'food_menu_id');
        $this->Common_model->deleteStatusChangeWithChild($id, $id, "tbl_food_menus", "tbl_food_menus_modifiers", 'id', 'food_menu_id');
        $this->session->set_flashdata('exception', lang('delete_success'));
        redirect('foodMenu/foodMenus');
    }
     /**
     * add/edit food menu
     * @access public
     * @return void
     * @param int
     */
    public function addEditFoodMenu($encrypted_id = "") {

        $id = $this->custom->encrypt_decrypt($encrypted_id, 'decrypt');
        $company_id = $this->session->userdata('company_id');
        $outlet_id = $this->session->userdata('outlet_id');
        if($outlet_id==""){
            $this->session->set_flashdata('exception_1', 'Select an outlet');
            redirect('Outlet/outlets');
        }
        if ($this->input->post('submit')) {
            $tax_information = array();
            $tax_string ='';
            if(!empty($_POST['tax_field_percentage'])){
                foreach($this->input->post('tax_field_percentage') as $key=>$value){
                    $single_info = array(
                        'tax_field_id' => $this->input->post('tax_field_id')[$key],
                        'tax_field_company_id' => $this->input->post('tax_field_company_id')[$key],
                        'tax_field_name' => $this->input->post('tax_field_name')[$key],
                        'tax_field_percentage' => ($this->input->post('tax_field_percentage')[$key]=="")?0:$this->input->post('tax_field_percentage')[$key]
                    );
                    $tax_string.=($this->input->post('tax_field_name')[$key]).":";
                    array_push($tax_information,$single_info);
                }
            }
            $tax_information = json_encode($tax_information);
            $this->form_validation->set_rules('name', lang('name'), 'required|max_length[50]');
            $this->form_validation->set_rules('category_id', lang('category'), 'required|max_length[50]');
            $this->form_validation->set_rules('veg_item', lang('is_it_veg'), 'required|max_length[50]');
            $this->form_validation->set_rules('beverage_item', lang('is_it_beverage'), 'required|max_length[50]');
            $this->form_validation->set_rules('bar_item',lang('is_it_bar'), 'required|max_length[50]');
            $this->form_validation->set_rules('description', lang('description'), 'max_length[200]');
            $this->form_validation->set_rules('sale_price', lang('sale_price'), 'required|max_length[50]');
            if ($_FILES['photo']['name'] != "") {
                $this->form_validation->set_rules('photo', lang('photo'), 'callback_validate_photo');
            }
            if ($this->form_validation->run() == TRUE) {
                $food_menu_info = array();
                $food_menu_info['name'] = htmlspecialchars($this->input->post($this->security->xss_clean('name')));
                $food_menu_info['code'] = htmlspecialchars($this->input->post($this->security->xss_clean('code')));
                $food_menu_info['category_id'] =htmlspecialchars($this->input->post($this->security->xss_clean('category_id')));
                $food_menu_info['veg_item'] =htmlspecialchars($this->input->post($this->security->xss_clean('veg_item')));
                $food_menu_info['beverage_item'] =htmlspecialchars($this->input->post($this->security->xss_clean('beverage_item')));
                $food_menu_info['bar_item'] =htmlspecialchars($this->input->post($this->security->xss_clean('bar_item')));
                $food_menu_info['description'] =htmlspecialchars($this->input->post($this->security->xss_clean('description')));
                $food_menu_info['sale_price'] =htmlspecialchars($this->input->post($this->security->xss_clean('sale_price')));
                $food_menu_info['tax_information'] = $tax_information;
                $food_menu_info['tax_string'] = $tax_string;
                $food_menu_info['user_id'] = $this->session->userdata('user_id');
                $food_menu_info['company_id'] = $this->session->userdata('company_id');
                if ($_FILES['photo']['name'] != "") {

                    $food_menu_info['photo'] = $this->session->userdata('photo');
                    $this->session->unset_userdata('photo');
                }

                if ($id == "") {
                    $food_menu_id = $this->Common_model->insertInformation($food_menu_info, "tbl_food_menus");
                    $this->saveFoodMenusIngredients($_POST['ingredient_id'], $food_menu_id, 'tbl_food_menus_ingredients');
                    $data['autoCode'] = $this->Master_model->generateFoodMenuCode();
                    $this->session->set_flashdata('exception',lang('insertion_success'));
                } else {
                    $this->Common_model->updateInformation($food_menu_info, $id, "tbl_food_menus");
                    $this->Common_model->deletingMultipleFormData('food_menu_id', $id, 'tbl_food_menus_ingredients');
                    $data['autoCode'] = $this->Master_model->generateFoodMenuCode();
                    $this->saveFoodMenusIngredients($_POST['ingredient_id'], $id, 'tbl_food_menus_ingredients');
                    $this->session->set_flashdata('exception', lang('update_success'));
                }

                redirect('foodMenu/foodMenus');
            } else {
                if ($id == "") {
                    $data = array();
                    $data['categories'] = $this->Common_model->getAllByCompanyId($company_id, 'tbl_food_menu_categories');
                    $data['autoCode'] = $this->Master_model->generateFoodMenuCode();
                    $data['ingredients'] = $this->Master_model->getIngredientListWithUnit($company_id);
                    $data['main_content'] = $this->load->view('master/foodMenu/addFoodMenu', $data, TRUE);
                    $this->load->view('userHome', $data);
                } else {
                    $data = array();
                    $data['encrypted_id'] = $encrypted_id;
                    $data['categories'] = $this->Common_model->getAllByCompanyId($company_id, 'tbl_food_menu_categories');
                    $data['autoCode'] = $this->Master_model->generateFoodMenuCode();
                    $data['ingredients'] = $this->Master_model->getIngredientListWithUnit($company_id);
                    $data['food_menu_details'] = $this->Common_model->getDataById($id, "tbl_food_menus");
                    $data['food_menu_ingredients'] = $this->Master_model->getFoodMenuIngredients($data['food_menu_details']->id);
                    $data['main_content'] = $this->load->view('master/foodMenu/editFoodMenu', $data, TRUE);
                    $this->load->view('userHome', $data);
                }
            }
        } else {
            if ($id == "") {
                $data = array();
                $data['categories'] = $this->Common_model->getAllByCompanyId($company_id, 'tbl_food_menu_categories');
                $data['autoCode'] = $this->Master_model->generateFoodMenuCode();
                $data['ingredients'] = $this->Master_model->getIngredientListWithUnit($company_id);

                $data['main_content'] = $this->load->view('master/foodMenu/addFoodMenu', $data, TRUE);
                $this->load->view('userHome', $data);
            } else {
                $data = array();
                $data['encrypted_id'] = $encrypted_id;
                $data['categories'] = $this->Common_model->getAllByCompanyId($company_id, 'tbl_food_menu_categories');
                $data['ingredients'] = $this->Master_model->getIngredientListWithUnit($company_id);
                $data['autoCode'] = $this->Master_model->generateFoodMenuCode();
                $data['food_menu_details'] = $this->Common_model->getDataById($id, "tbl_food_menus");
                $data['food_menu_ingredients'] = $this->Master_model->getFoodMenuIngredients($data['food_menu_details']->id);
                $data['main_content'] = $this->load->view('master/foodMenu/editFoodMenu', $data, TRUE);
                $this->load->view('userHome', $data);
            }
        }
    }
     /**
     * fix food menu
     * @access public
     * @return void
     * @param no
     */
    public function fixFoodMenus(){
        $result = $this->db->query("SELECT * FROM tbl_food_menus")->result();
        foreach ($result as $key => $value) {
            $this->db->set('veg_item', "Veg No");
            $this->db->set('beverage_item', "Beverage No");
            $this->db->update('tbl_food_menus');
            $this->db->where('id', $value->id);
        }
    }

    public function foodMenuBarcode() {
        $company_id = $this->session->userdata('company_id');
        $outlet_id = $this->session->userdata('outlet_id');
        if ($this->input->post('submit')) {
            $food_menu_id = $this->input->post($this->security->xss_clean('food_menu_id'));
            $qty = $this->input->post($this->security->xss_clean('qty'));
            $barcode_width = $this->input->post($this->security->xss_clean('barcode_width'));
            $barcode_height = $this->input->post($this->security->xss_clean('barcode_height'));
            $arr = array();
            if($food_menu_id){
                for ($i=0;$i<sizeof($food_menu_id);$i++){
                    $value = explode("|",$food_menu_id[$i]);
                    $arr[] = array(
                        'id' => $value[0],
                        'item_name' => $value[1],
                        'code' => $value[2],
                        'sale_price' => $value[3],
                        'qty' => $qty[$i]
                    );
                }
            }
            $data = array();
            $data['items'] = $arr;
            $data['barcode_width'] = $barcode_width;
            $data['barcode_height'] = $barcode_height;
            $data['main_content'] = $this->load->view('master/foodMenu/barcode_preview', $data, TRUE);
            $this->load->view('userHome', $data);
        } else {
            $data = array();
            $data['outlet_information'] = $this->Common_model->getDataById($outlet_id, "tbl_outlets");
            $data['foodMenus'] = $this->Common_model->getAllByCompanyId($company_id, "tbl_food_menus");
            $data['main_content'] = $this->load->view('master/foodMenu/foodMenuBarcodeGenerator', $data, TRUE);
            $this->load->view('userHome', $data);
        }

    }
     /**
     * validate photo
     * @access public
     * @return string
     * @param no
     */
    public function validate_photo() {

        if ($_FILES['photo']['name'] != "") {
            $config['upload_path'] = './images';
            $config['allowed_types'] = 'jpg|jpeg|png';
            $config['max_size'] = '2048';
            $config['maintain_ratio'] = TRUE;
            $config['encrypt_name'] = TRUE;
            $config['detect_mime'] = TRUE;
            $this->load->library('upload', $config);
            if ($this->upload->do_upload("photo")) {
                $upload_info = $this->upload->data();
                $photo = $upload_info['file_name'];

                $config['image_library'] = 'gd2';
                $config['source_image'] = './images/'.$photo;
                // $config['create_thumb'] = TRUE;
                $config['maintain_ratio'] = TRUE;
                $config['width'] = 200;
                $config['height'] = 100;

                $this->load->library('image_lib', $config);

                $this->image_lib->resize();
                $this->session->set_userdata('photo', $upload_info['file_name']);

            } else {
                $this->form_validation->set_message('validate_photo', $this->upload->display_errors());
                return FALSE;
            }
        }
    }
     /**
     * save food menus ingredients
     * @access public
     * @return void
     * @param string
     * @param int
     * @param string
     */
    public function saveFoodMenusIngredients($food_menu_ingredients, $food_menu_id, $table_name) {
        if(isset($food_menu_ingredients) && $food_menu_ingredients){
            foreach ($food_menu_ingredients as $row => $ingredient_id):
                $fmi = array();
                $fmi['ingredient_id'] = $ingredient_id;
                $fmi['consumption'] = $_POST['consumption'][$row];
                $fmi['food_menu_id'] = $food_menu_id;
                $fmi['user_id'] = $this->session->userdata('user_id');
                $fmi['company_id'] = $this->session->userdata('company_id');
                $this->Common_model->insertInformation($fmi, "tbl_food_menus_ingredients");
            endforeach;
        }

    }
     /**
     * save Food Menus Modifiers
     * @access public
     * @return void
     * @param string
     * @param int
     * @param string
     */
    public function saveFoodMenusModifiers($food_menu_modifiers, $food_menu_id, $table_name) {
        foreach ($food_menu_modifiers as $row => $modifier_id):
            $fmm = array();
            $fmm['modifier_id'] = $modifier_id;
            $fmm['food_menu_id'] = $food_menu_id;
            $fmm['user_id'] = $this->session->userdata('user_id');
            $fmm['company_id'] = $this->session->userdata('company_id');
            $this->Common_model->insertInformation($fmm, "tbl_food_menus_modifiers");
        endforeach;
    }
     /**
     * upload Food Menu
     * @access public
     * @return void
     * @param no
     */
    public function uploadFoodMenu() {
        $company_id = $this->session->userdata('company_id');
        $data = array();
        $data['foodMenus'] = $this->Common_model->getAllByCompanyId($company_id, "tbl_food_menus");
        $data['foodMenuCategories'] = $this->Common_model->getAllByCompanyIdForDropdown($company_id, "tbl_food_menu_categories");
        $data['main_content'] = $this->load->view('master/foodMenu/uploadsfoodMenus', $data, TRUE);
        $this->load->view('userHome', $data);
    }
     /**
     * download file
     * @access public
     * @return void
     * @param string
     */
    public function downloadPDF($file = "") {
        // load ci download helder
        $this->load->helper('download');
        $data = file_get_contents("asset/sample/" . $file); // Read the file's
        $name = $file;
        force_download($name, $data);
    }
     /**
     * Excel Data Add Food menus
     * @access public
     * @return void
     * @param no
     */
    public function ExcelDataAddFoodmenus() {
        $company_id = $this->session->userdata('company_id');
        if ($_FILES['userfile']['name'] != "") {
            if ($_FILES['userfile']['name'] == "Food_Menu_Upload.xlsx") {
                //Path of files were you want to upload on localhost (C:/xampp/htdocs/ProjectName/uploads/excel/)
                $configUpload['upload_path'] = FCPATH . 'asset/excel/';
                $configUpload['allowed_types'] = 'xls|xlsx';
                $configUpload['max_size'] = '5000';
                $this->load->library('upload', $configUpload);
                if ($this->upload->do_upload('userfile')) {
                    $upload_data = $this->upload->data(); //Returns array of containing all of the data related to the file you uploaded.
                    $file_name = $upload_data['file_name']; //uploded file name
                    $extension = $upload_data['file_ext'];    // uploded file extension
                    //$objReader =PHPExcel_IOFactory::createReader('Excel5');     //For excel 2003
                    $objReader = PHPExcel_IOFactory::createReader('Excel2007'); // For excel 2007
                    //Set to read only
                    $objReader->setReadDataOnly(true);
                    //Load excel file
                    $objPHPExcel = $objReader->load(FCPATH . 'asset/excel/' . $file_name);
                    $totalrows = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();   //Count Numbe of rows avalable in excel
                    $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);

                    //loop from first data untill last data
                    if ($totalrows > 2 && $totalrows < 54) {
                        $arrayerror = '';
                        for ($i = 4; $i <= $totalrows; $i++) {
                            $name = htmlspecialchars(trim($objWorksheet->getCellByColumnAndRow(0, $i)->getValue()));
                            $code = htmlspecialchars(trim($objWorksheet->getCellByColumnAndRow(1, $i)->getValue())); //Excel Column 1
                            $category = htmlspecialchars(trim($objWorksheet->getCellByColumnAndRow(2, $i)->getValue())); //Excel Column 2
                            $sale_prices = htmlspecialchars(trim($objWorksheet->getCellByColumnAndRow(3, $i)->getValue())); //Excel Column 3
                            $vat_name = htmlspecialchars(trim($objWorksheet->getCellByColumnAndRow(4, $i)->getValue())); //Excel Column 4
                            $vat_percent = htmlspecialchars(trim($objWorksheet->getCellByColumnAndRow(5, $i)->getValue())); //Excel Column 5
                            $description = htmlspecialchars(trim($objWorksheet->getCellByColumnAndRow(6, $i)->getValue())); //Excel Column 5
                            $isVegItem = htmlspecialchars(trim($objWorksheet->getCellByColumnAndRow(7, $i)->getValue())); //Excel Column 7
                            $isBeverage = htmlspecialchars(trim($objWorksheet->getCellByColumnAndRow(8, $i)->getValue())); //Excel Column 8
                            $isBar = htmlspecialchars(trim($objWorksheet->getCellByColumnAndRow(9, $i)->getValue())); //Excel Column 8

                            if ($name == '') {
                                if ($arrayerror == '') {
                                    $arrayerror.="Row Number $i column A required";
                                } else {
                                    $arrayerror.="<br>Row Number $i column A required";
                                }
                            }

                            if ($code == '') {
                                if ($arrayerror == '') {
                                    $arrayerror.="Row Number $i column B required";
                                } else {
                                    $arrayerror.="<br>Row Number $i column B required";
                                }
                            }

                            if ($category == '') {
                                if ($arrayerror == '') {
                                    $arrayerror.="Row Number $i column C required";
                                } else {
                                    $arrayerror.="<br>Row Number $i column C required";
                                }
                            }

                            if ($sale_prices == '' || !is_numeric($sale_prices)) {
                                if ($arrayerror == '') {
                                    $arrayerror.="Row Number $i column D required or can not be text";
                                } else {
                                    $arrayerror.="<br>Row Number $i column D required or can not be text";
                                }
                            }
                            $tmp_vat_name = explode(',',$vat_name);
                            $tmp_vat_percent = explode(',',$vat_percent);

                            if ($vat_name || $tmp_vat_percent) {
                                if(sizeof($tmp_vat_name) != sizeof($tmp_vat_percent))

                                if ($arrayerror == '') {
                                    $arrayerror.="Row Number $i column E & F does not match";
                                } else {
                                    $arrayerror.="<br>Row Number $i column E & F does not match";
                                }
                            }

                            if (($isVegItem == '')) {
                                if ($arrayerror == '') {
                                    $arrayerror.="Row Number $i column H required";
                                } else {
                                    $arrayerror.="<br>Row Number $i column H required";
                                }
                            }

                            if (($isVegItem != 'Veg Yes') && ($isVegItem != 'Veg No')) {
                                if ($arrayerror == '') {
                                    $arrayerror.="Row Number $i column H required or should be Veg Yes or Veg No";
                                } else {
                                    $arrayerror.="<br>Row Number $i column required H required or should be Veg Yes or Veg No";
                                }
                            }

                            if (($isBeverage == '')) {
                                if ($arrayerror == '') {
                                    $arrayerror.="Row Number $i column H required";
                                } else {
                                    $arrayerror.="<br>Row Number $i column H required";
                                }
                            }

                            if (($isBeverage != 'Bev Yes') && ($isBeverage != 'Bev No')) {
                                if ($arrayerror == '') {
                                    $arrayerror.="Row Number $i column H required or should be Bev Yes or Bev No";
                                } else {
                                    $arrayerror.="<br>Row Number $i column required H required or should be Bev Yes or Bev No";
                                }
                            }

                            if (($isBar == '')) {
                                if ($arrayerror == '') {
                                    $arrayerror.="Row Number $i column H required";
                                } else {
                                    $arrayerror.="<br>Row Number $i column H required";
                                }
                            }

                            if (($isBar != 'Bar Yes') && ($isBar != 'Bar No')) {
                                if ($arrayerror == '') {
                                    $arrayerror.="Row Number $i column H required or should be Bar Yes or Bar No";
                                } else {
                                    $arrayerror.="<br>Row Number $i column required H required or should be Bar Yes or Bar No";
                                }
                            }
                        }
                        if ($arrayerror == '') {
                            if(!is_null($this->input->post('remove_previous'))){
                                $this->db->query("TRUNCATE table `tbl_food_menus`");
                            }

                            $company = getCompanyInfo();
                            $outlet_taxes = json_decode($company->tax_setting);

                            for ($i = 4; $i <= $totalrows; $i++) {
                                $name = htmlspecialchars(trim($objWorksheet->getCellByColumnAndRow(0, $i)->getValue()));
                                $code = htmlspecialchars(trim($objWorksheet->getCellByColumnAndRow(1, $i)->getValue())); //Excel Column 1
                                $category = htmlspecialchars(trim($objWorksheet->getCellByColumnAndRow(2, $i)->getValue())); //Excel Column 2
                                $sale_prices = htmlspecialchars(trim($objWorksheet->getCellByColumnAndRow(3, $i)->getValue())); //Excel Column 3
                                $vat_name = htmlspecialchars(trim($objWorksheet->getCellByColumnAndRow(4, $i)->getValue())); //Excel Column 4
                                $vat_percent = htmlspecialchars(trim($objWorksheet->getCellByColumnAndRow(5, $i)->getValue())); //Excel Column 5
                                $description = htmlspecialchars(trim($objWorksheet->getCellByColumnAndRow(6, $i)->getValue())); //Excel Column 5
                                $isVegItem = htmlspecialchars(trim($objWorksheet->getCellByColumnAndRow(7, $i)->getValue())); //Excel Column 5
                                $isBeverage = htmlspecialchars(trim($objWorksheet->getCellByColumnAndRow(8, $i)->getValue())); //Excel Column 5
                                $isBar = htmlspecialchars(trim($objWorksheet->getCellByColumnAndRow(9, $i)->getValue())); //Excel Column 5
                                $image = htmlspecialchars(trim($objWorksheet->getCellByColumnAndRow(10, $i)->getValue())); //Excel Column 5

                                $tmp_vat_name = explode(',',$vat_name);
                                $tmp_vat_percent = explode(',',$vat_percent);

                                $tax_information = array();
                                $tax_string = '';
                                foreach($outlet_taxes as $key=>$value){
                                    foreach($tmp_vat_name as $key1=>$value1){
                                        if($value->tax==$value1){
                                            $get_tax = isset($tmp_vat_percent[$key1]) && $tmp_vat_percent[$key1]?$tmp_vat_percent[$key1]:0;
                                            $single_info = array(
                                                'tax_field_id' => $value->id,
                                                'tax_field_outlet_id' => 0,
                                                'tax_field_company_id' => 1,
                                                'tax_field_name' => $value->tax,
                                                'tax_field_percentage' => $get_tax
                                            );
                                            $tax_string.=($value->tax).":";
                                            array_push($tax_information,$single_info);
                                        }
                                    }
                                }
                                $ct_id = $this->get_foodmenu_ct_id_byname($category);
                                $fmc_info = array();
                                $fmc_info['name'] = $name;
                                $fmc_info['code'] = $code;
                                $fmc_info['category_id'] = $ct_id;
                                $fmc_info['sale_price'] = $sale_prices;
                                $fmc_info['description'] = $description;
                                $fmc_info['veg_item'] = $isVegItem;
                                $fmc_info['beverage_item'] = $isBeverage;
                                $fmc_info['bar_item'] = $isBar;
                                if($image){
                                    $fmc_info['photo'] = $image;
                                }
                                $fmc_info['tax_information'] = json_encode($tax_information);
                                $fmc_info['tax_string'] = $tax_string;
                                $fmc_info['user_id'] = $this->session->userdata('user_id');
                                $fmc_info['company_id'] = $this->session->userdata('company_id');
                                $this->Common_model->insertInformation($fmc_info, "tbl_food_menus");
                            }
                            unlink(FCPATH . 'asset/excel/' . $file_name); //File Deleted After uploading in database .
                            $this->session->set_flashdata('exception', 'Imported successfully!');
                            redirect('foodMenu/foodMenus');
                        } else {
                            unlink(FCPATH . 'asset/excel/' . $file_name); //File Deleted After uploading in database .
                            $this->session->set_flashdata('exception_err', "Required Data Missing:$arrayerror");
                        }
                    } else {
                        unlink(FCPATH . 'asset/excel/' . $file_name); //File Deleted After uploading in database .
                        $this->session->set_flashdata('exception_err', "Entry is more than 50 or No entry found.");
                    }
                } else {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('exception_err', "$error");
                }
            } else {
                $this->session->set_flashdata('exception_err', "We can not accept other files, please download the sample file 'Food_Menu_Upload.xlsx', fill it up properly and upload it or rename the file name as 'Food_Menu_Upload.xlsx' then fill it.");
            }
        } else {
            $this->session->set_flashdata('exception_err', 'File is required');
        }
        redirect('foodMenu/uploadFoodMenu');
    }
     /**
     * assign Food Menu Modifier
     * @access public
     * @return void
     * @param int
     */
    public function assignFoodMenuModifier($encrypted_id = "") {
        $id = $this->custom->encrypt_decrypt($encrypted_id, 'decrypt');
        $company_id = $this->session->userdata('company_id');
        $food_menu_modifiers = $this->Master_model->getFoodMenuModifiers($id);
        if (!empty($food_menu_modifiers)) {
            foreach ($food_menu_modifiers as $value) {
                $user_menu_modifier_arr[] = $value->modifier_id;
            }
        } else {
            $user_menu_modifier_arr = '';
        }

        if ($this->input->post('submit')) {
            $this->Common_model->deletingMultipleFormData('food_menu_id', $id, 'tbl_food_menus_modifiers');
            $this->saveFoodMenusModifiers($_POST['modifier_id'], $id, 'tbl_food_menus_modifiers');
            $this->session->set_flashdata('exception', 'Information has been updated successfully!');
            redirect('foodMenu/foodMenus');
        } else {
            $data['encrypted_id'] = $encrypted_id;
            $data['modifiers'] = $this->Common_model->getAllModifierByCompanyId($company_id, 'tbl_modifiers');
            $data['food_menu_details'] = $this->Common_model->getDataById($id, "tbl_food_menus");
            $data['food_menu_modifiers'] = $user_menu_modifier_arr;
            $data['main_content'] = $this->load->view('master/foodMenu/assignFoodMenuModifier', $data, TRUE);
            $this->load->view('userHome', $data);
        }
    }
     /**
     * food Menu Details
     * @access public
     * @return void
     * @param int
     */
    public function foodMenuDetails($id) {
        $encrypted_id = $id;
        $id = $this->custom->encrypt_decrypt($id, 'decrypt');

        $data = array();
        $data['encrypted_id'] = $encrypted_id;
        $data['food_menu_details'] = $this->Common_model->getDataById($id, "tbl_food_menus");
        $data['main_content'] = $this->load->view('master/foodMenu/foodMenuDetails', $data, TRUE);
        $this->load->view('userHome', $data);
    }
     /**
     * get food menu category by name
     * @access public
     * @return int
     * @param string
     */
    public function get_foodmenu_ct_id_byname($category) {
        $company_id = $this->session->userdata('company_id');
        $user_id = $this->session->userdata('user_id');
        $id = $this->db->query("SELECT id FROM tbl_food_menu_categories WHERE company_id=$company_id and user_id=$user_id and category_name='" . $category . "' and del_status='Live'")->row('id');
        if ($id != '') {
            return $id;
        } else {
            $data = array('category_name' => $category, 'company_id' => $company_id, 'user_id' => $user_id);
            $query = $this->db->insert('tbl_food_menu_categories', $data);
            $id = $this->db->insert_id();
            return $id;
        }
    }
     /**
     * get food menu vat id by name
     * @access public
     * @return int
     * @param string
     * @param float
     */
    public function get_foodmenu_vat_id_byname($vat_name, $vat_percent) {
        $company_id = $this->session->userdata('company_id');
        $user_id = $this->session->userdata('user_id');
        $id = $this->db->query("SELECT id FROM tbl_vats WHERE company_id=$company_id and name='" . $vat_name . "'")->row('id');
        if ($id) {
            return $id;
        } else {
            $data = array('name' => $vat_name, 'company_id' => $company_id, 'percentage' => $vat_percent);
            $query = $this->db->insert('tbl_vats', $data);
            $id = $this->db->insert_id();
            return $id;
        }
    }
     /**
     * upload Food Menu Ingredients
     * @access public
     * @return void
     * @param no
     */
    public function uploadFoodMenuIngredients() {
        $company_id = $this->session->userdata('company_id');
        $data = array();
        $data['main_content'] = $this->load->view('master/foodMenu/uploadsfoodMenusIngredients', $data, TRUE);
        $this->load->view('userHome', $data);
    }
     /**
     * Excel Data Add Food menus Ingredients
     * @access public
     * @return void
     * @param no
     */
    public function ExcelDataAddFoodmenusIngredients()
    {
        $company_id = $this->session->userdata('company_id');
        $user_id = $this->session->userdata('user_id');
        if ($_FILES['userfile']['name'] != "") {
            if ($_FILES['userfile']['name'] == "Food_Menu_Ingredients_Upload.xlsx") {
                //Path of files were you want to upload on localhost (C:/xampp/htdocs/ProjectName/uploads/excel/)
                $configUpload['upload_path'] = FCPATH . 'asset/excel/';
                $configUpload['allowed_types'] = 'xls|xlsx';
                $configUpload['max_size'] = '5000';
                $this->load->library('upload', $configUpload);
                if ($this->upload->do_upload('userfile')) {
                    $upload_data = $this->upload->data(); //Returns array of containing all of the data related to the file you uploaded.
                    $file_name = $upload_data['file_name']; //uploded file name
                    $extension = $upload_data['file_ext'];    // uploded file extension
                    //$objReader =PHPExcel_IOFactory::createReader('Excel5');     //For excel 2003
                    $objReader = PHPExcel_IOFactory::createReader('Excel2007'); // For excel 2007
                    //Set to read only
                    $objReader->setReadDataOnly(true);
                    //Load excel file
                    $objPHPExcel = $objReader->load(FCPATH . 'asset/excel/' . $file_name);
                    $totalrows = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();   //Count Numbe of rows avalable in excel
                    $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
                    //loop from first data untill last data
                    $totalFoodMenuToUpload = 0;

                    if ($totalrows > 2) {
                        $arrayerror = '';
                        for ($i = 3; $i <= $totalrows; $i++) {
                            $menuOrIngredient = htmlspecialchars(trim($objWorksheet->getCellByColumnAndRow(0, $i)->getValue()));
                            //it counts total number of food menus
                            if($menuOrIngredient=='FM'){
                                $totalFoodMenuToUpload++;
                            }
                        }
                        if($totalFoodMenuToUpload<10){
                            for ($i = 3; $i <= $totalrows; $i++) {
                                $menuOrIngredient = htmlspecialchars(trim($objWorksheet->getCellByColumnAndRow(0, $i)->getValue()));
                                $menuOrIngredientName = htmlspecialchars(trim($objWorksheet->getCellByColumnAndRow(1, $i)->getValue()));
                                $consumption = null;

                                $currentRowFor = ''; //it hold current row wether menu or ingredient
                                //it counts total number of food menus
                                if($menuOrIngredient=='FM'){
                                    $totalFoodMenuToUpload++;
                                    $record = $this->Common_model->getMenuByMenuName($menuOrIngredientName);
                                    $currentRowFor = 'Menu';
                                }else{
                                    $consumption = htmlspecialchars(trim($objWorksheet->getCellByColumnAndRow(2, $i)->getValue()));
                                    $record = $this->Common_model->getIngredientByIngredientName($menuOrIngredientName);
                                    $currentRowFor = 'Ingredient';
                                }

                                //get next menu or ingredient
                                $isNextMenuOrIngredient = htmlspecialchars(trim($objWorksheet->getCellByColumnAndRow(0, $i+1)->getValue()));

                                // if any record is not found then set this message
                                if ($record==NULL) {
                                    if ($arrayerror == '') {
                                        $arrayerror.="Row Number $i column B required & must be valid menu or ingredient name";
                                    } else {
                                        $arrayerror.="<br>Row Number $i column B required & must be valid menu or ingredient name";
                                    }
                                }


                                // //it sets message when it's not menu and ingredient as well
                                if ($menuOrIngredient!="FM" && $menuOrIngredient!="IG") {
                                    if ($arrayerror == '') {
                                        $arrayerror.="Row Number $i column A required & must be 'FM' or 'IG'";
                                    } else {
                                        $arrayerror.="<br>Row Number $i column A required & must be 'FM' or 'IG'";
                                    }
                                }

                                if ($menuOrIngredient == 'IG' && ($consumption == null || $consumption == '' || !is_numeric($consumption))) {
                                    if ($arrayerror == '') {
                                        $arrayerror.=" $i Row Number column C required, it must be numeric";
                                    } else {
                                        $arrayerror.="<br> $i Row Number column C required, it must be numeric";
                                    }
                                }

                                //it sets message when food menu number is greater than 10
                                if ($totalFoodMenuToUpload>10) {
                                    if ($arrayerror == '') {
                                        $arrayerror.="You can not upload more than 10 food menus at a time.";
                                    } else {
                                        $arrayerror.="<br>You can not upload more than 10 food menus at a time.";
                                    }
                                }

                                //it checks next one is food menu or ingredient. if current one is food menu and next one
                                //is food menu then it means current food menu doesn't have ingredients
                                if($menuOrIngredient=='FM' && $isNextMenuOrIngredient=='FM'){
                                    if ($arrayerror == '') {
                                        $arrayerror.="row number $i is a Food Menu, no ingredient found for $menuOrIngredientName";
                                    } else {
                                        $arrayerror.="<br>row number $i is a Food Menu, no ingredient found for $menuOrIngredientName";
                                    }
                                }
                            }
                            if ($arrayerror == '') {
                                if(!is_null($this->input->post('remove_previous'))){
                                    $this->db->query("TRUNCATE table `tbl_food_menus_ingredients`");
                                }
                                for ($i = 3; $i <= $totalrows; $i++) {
                                    $menuOrIngredient = htmlspecialchars(trim($objWorksheet->getCellByColumnAndRow(0, $i)->getValue()));
                                    $menuOrIngredientName = htmlspecialchars(trim($objWorksheet->getCellByColumnAndRow(1, $i)->getValue()));
                                    $consumption = null;
                                    if($menuOrIngredient=='FM'){
                                        $food_menu_record = $this->Common_model->getMenuByMenuName($menuOrIngredientName);
                                        $info_session['food_id_custom'] = $food_menu_record->id;
                                        $this->session->set_userdata($info_session);
                                    }else{
                                        $ingredient_record = $this->Common_model->getIngredientByIngredientName($menuOrIngredientName);
                                        $consumption = htmlspecialchars(trim($objWorksheet->getCellByColumnAndRow(2, $i)->getValue()));
                                        $food_menu_ingredient_info = array();
                                        $food_menu_ingredient_info['ingredient_id'] = $ingredient_record->id;
                                        $food_menu_ingredient_info['consumption'] = $consumption;
                                        $food_menu_ingredient_info['food_menu_id'] = $this->session->userdata('food_id_custom');
                                        $food_menu_ingredient_info['user_id'] = $this->session->userdata('user_id');
                                        $food_menu_ingredient_info['company_id'] = $this->session->userdata('company_id');
                                        $food_menu_ingredient_info['del_status'] = 'Live';
                                        $this->Common_model->insertInformation($food_menu_ingredient_info, "tbl_food_menus_ingredients");
                                    }
                                }
                                unlink(FCPATH . 'asset/excel/' . $file_name); //File Deleted After uploading in database .
                                $this->session->set_flashdata('exception', 'Imported successfully!');
                                redirect('foodMenu/foodMenus');
                            } else {
                                unlink(FCPATH . 'asset/excel/' . $file_name); //File Deleted After uploading in database .
                                $this->session->set_flashdata('exception_err', "Required Data Missing:$arrayerror");
                            }
                        }else{
                            unlink(FCPATH . 'asset/excel/' . $file_name); //File Deleted After uploading in database .
                            $this->session->set_flashdata('exception_err', "You can not upload more than 10 food menus at a time.");
                        }
                    } else {
                        unlink(FCPATH . 'asset/excel/' . $file_name); //File Deleted After uploading in database .
                        $this->session->set_flashdata('exception_err', "No entry found.");
                    }
                } else {
                    $error = $this->upload->display_errors();
                    $this->session->set_flashdata('exception_err', "$error");
                }
            } else {
                $this->session->set_flashdata('exception_err', "We can not accept other files, please download the sample file 'Food_Menu_Ingredients_Upload.xlsx', fill it up properly and upload it or rename the file name as 'Food_Menu_Ingredients_Upload.xlsx' then fill it.");
            }
        } else {
            $this->session->set_flashdata('exception_err', 'File is required');
        }
        redirect('foodMenu/uploadFoodMenuIngredients');
    }

}
