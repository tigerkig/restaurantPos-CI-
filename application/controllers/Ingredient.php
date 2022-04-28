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
  # This is Ingredient Controller
  ###########################################################
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Ingredient extends Cl_Controller {

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
     * ingredients info
     * @access public
     * @return void
     * @param no
     */
    public function ingredients() {
        $company_id = $this->session->userdata('company_id');

        $data = array();
        $data['ingredients'] = $this->Common_model->getAllByCompanyId($company_id, "tbl_ingredients");
        $data['main_content'] = $this->load->view('master/ingredient/ingredients', $data, TRUE);
        $this->load->view('userHome', $data);
    }
     /**
     * upload ingredients
     * @access public
     * @return void
     * @param no
     */
    public function uploadingredients() {
        $company_id = $this->session->userdata('company_id');

        $data = array();
        $data['ingredients'] = $this->Common_model->getAllByCompanyId($company_id, "tbl_ingredients");
        $data['main_content'] = $this->load->view('master/ingredient/uploadingredients', $data, TRUE);
        $this->load->view('userHome', $data);
    }
     /**
     * upload Food Menu ingredient
     * @access public
     * @return void
     * @param no
     */
    public function uploadFoodMenuingredient() {
        $company_id = $this->session->userdata('company_id');
        $data = array();
        $data['foodMenus'] = $this->Common_model->getAllByCompanyId($company_id, "tbl_food_menus");
        $data['foodMenuCategories'] = $this->Common_model->getAllByCompanyIdForDropdown($company_id, "tbl_food_menu_categories");
        $data['main_content'] = $this->load->view('master/foodMenu/uploadsfoodMenusingrediend', $data, TRUE);
        $this->load->view('userHome', $data);
    }
     /**
     * delete Ingredient
     * @access public
     * @return void
     * @param int
     */
    public function deleteIngredient($id) {
        $id = $this->custom->encrypt_decrypt($id, 'decrypt');

        $this->Common_model->deleteStatusChange($id, "tbl_ingredients");

        $this->session->set_flashdata('exception',lang('delete_success'));
        redirect('ingredient/ingredients');
    }
     /**
     * add/Edit Ingredient
     * @access public
     * @return void
     * @param int
     */
    public function addEditIngredient($encrypted_id = "") {
        $id = $this->custom->encrypt_decrypt($encrypted_id, 'decrypt');
        $company_id = $this->session->userdata('company_id');
        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('name', lang('name'), 'required|max_length[50]');
            $this->form_validation->set_rules('category_id', lang('category'), 'required');
            $this->form_validation->set_rules('purchase_price', lang('purchase_price'), 'required|numeric|max_length[15]');
            $this->form_validation->set_rules('alert_quantity',lang('alert_quantity'), 'required|numeric|max_length[15]');
            $this->form_validation->set_rules('unit_id',lang('unit'), 'required');
            if ($this->form_validation->run() == TRUE) {
                $fmc_info = array();
                $fmc_info['name'] = htmlspecialchars($this->input->post($this->security->xss_clean('name')));
                $fmc_info['code'] = htmlspecialchars($this->input->post($this->security->xss_clean('code')));
                $fmc_info['category_id'] =htmlspecialchars($this->input->post($this->security->xss_clean('category_id')));
                $fmc_info['purchase_price'] =htmlspecialchars($this->input->post($this->security->xss_clean('purchase_price')));
                $fmc_info['alert_quantity'] =htmlspecialchars($this->input->post($this->security->xss_clean('alert_quantity')));
                $fmc_info['unit_id'] =htmlspecialchars($this->input->post($this->security->xss_clean('unit_id')));
                $fmc_info['user_id'] = $this->session->userdata('user_id');
                $fmc_info['company_id'] = $this->session->userdata('company_id');
                if ($id == "") {
                    $this->Common_model->insertInformation($fmc_info, "tbl_ingredients");
                    $this->session->set_flashdata('exception', lang('insertion_success'));
                } else {
                    $this->Common_model->updateInformation($fmc_info, $id, "tbl_ingredients");
                    $this->session->set_flashdata('exception',lang('update_success'));
                }
                redirect('ingredient/ingredients');
            } else {
                if ($id == "") {
                    $data = array();
                    $data['categories'] = $this->Common_model->getAllByCompanyIdForDropdown($company_id, 'tbl_ingredient_categories');
                    $data['units'] = $this->Common_model->getAllByCompanyIdForDropdown($company_id, 'tbl_units');
                    $data['autoCode'] = $this->Master_model->generateIngredientCode();
                    $data['main_content'] = $this->load->view('master/ingredient/addIngredient', $data, TRUE);
                    $this->load->view('userHome', $data);
                } else {
                    $data = array();
                    $data['encrypted_id'] = $encrypted_id;
                    $data['categories'] = $this->Common_model->getAllByCompanyIdForDropdown($company_id, 'tbl_ingredient_categories');
                    $data['autoCode'] = $this->Master_model->generateIngredientCode();
                    $data['units'] = $this->Common_model->getAllByCompanyIdForDropdown($company_id, 'tbl_units');
                    $data['ingredient_information'] = $this->Common_model->getDataById($id, "tbl_ingredients");
                    $data['main_content'] = $this->load->view('master/ingredient/editIngredient', $data, TRUE);
                    $this->load->view('userHome', $data);
                }
            }
        } else {
            if ($id == "") {
                $data = array();
                $data['categories'] = $this->Common_model->getAllByCompanyIdForDropdown($company_id, 'tbl_ingredient_categories');
                $data['units'] = $this->Common_model->getAllByCompanyIdForDropdown($company_id, 'tbl_units');
                $data['autoCode'] = $this->Master_model->generateIngredientCode();
                $data['main_content'] = $this->load->view('master/ingredient/addIngredient', $data, TRUE);
                $this->load->view('userHome', $data);
            } else {
                $data = array();
                $data['encrypted_id'] = $encrypted_id;
                $data['categories'] = $this->Common_model->getAllByCompanyIdForDropdown($company_id, 'tbl_ingredient_categories');
                $data['autoCode'] = $this->Master_model->generateIngredientCode();
                $data['units'] = $this->Common_model->getAllByCompanyIdForDropdown($company_id, 'tbl_units');
                $data['ingredient_information'] = $this->Common_model->getDataById($id, "tbl_ingredients");
                $data['main_content'] = $this->load->view('master/ingredient/editIngredient', $data, TRUE);
                $this->load->view('userHome', $data);
            }
        }
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
     * Excel Data Add Ingredints
     * @access public
     * @return void
     * @param no
     */
    public function ExcelDataAddIngredints() {
        $company_id = $this->session->userdata('company_id');
        if ($_FILES['userfile']['name'] != "") {
            if ($_FILES['userfile']['name'] == "Ingredient_Upload.xlsx") {
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
                            $ingredint_name = htmlspecialchars(trim($objWorksheet->getCellByColumnAndRow(0, $i)->getValue()));
                            $ingredint_code = htmlspecialchars(trim($objWorksheet->getCellByColumnAndRow(1, $i)->getValue())); //Excel Column 1
                            $ingredint_category = htmlspecialchars(trim($objWorksheet->getCellByColumnAndRow(2, $i)->getValue())); //Excel Column 2
                            $ingredint_unit = htmlspecialchars(trim($objWorksheet->getCellByColumnAndRow(3, $i)->getValue())); //Excel Column 3
                            $ingredint_alertqty = htmlspecialchars(trim($objWorksheet->getCellByColumnAndRow(4, $i)->getValue())); //Excel Column 4
                            $ingredint_perchaseprice = htmlspecialchars(trim($objWorksheet->getCellByColumnAndRow(5, $i)->getValue())); //Excel Column 5

                            if ($ingredint_name == '') {
                                if ($arrayerror == '') {
                                    $arrayerror.=" Row Number $i column A required";
                                } else {
                                    $arrayerror.="<br> Row Number $i column A required";
                                }
                            }

                            if ($ingredint_code == '') {
                                if ($arrayerror == '') {
                                    $arrayerror.="Row Number $i column B required";
                                } else {
                                    $arrayerror.="<br> Row Number $i column B required";
                                }
                            }

                            if ($ingredint_category == '') {
                                if ($arrayerror == '') {
                                    $arrayerror.="Row Number $i column C required";
                                } else {
                                    $arrayerror.="<br> $i Row Number column C required";
                                }
                            }

                            if ($ingredint_unit == '') {
                                if ($arrayerror == '') {
                                    $arrayerror.="Row Number $i column D required";
                                } else {
                                    $arrayerror.="<br> Row Number $i column D required";
                                }
                            }

                            if ($ingredint_alertqty == '' || !is_numeric($ingredint_alertqty)) {
                                if ($arrayerror == '') {
                                    $arrayerror.="Row Number $i column E required or can not be text";
                                } else {
                                    $arrayerror.="<br> Row Number $i column E required  or can not be text";
                                }
                            }

                            if ($ingredint_perchaseprice == '' || !is_numeric($ingredint_perchaseprice)) {
                                if ($arrayerror == '') {
                                    $arrayerror.="Row Number $i column F required or can not be text";
                                } else {
                                    $arrayerror.="<br> Row Number $i column F required or can not be text";
                                }
                            }
                        }
                        if ($arrayerror == '') {
                            if(!is_null($this->input->post('remove_previous'))){
                                $this->db->query("TRUNCATE table `tbl_ingredients`");
                            }
                            for ($i = 4; $i <= $totalrows; $i++) {
                                $ingredint_name = htmlspecialchars(trim($objWorksheet->getCellByColumnAndRow(0, $i)->getValue()));
                                $ingredint_code = htmlspecialchars(trim($objWorksheet->getCellByColumnAndRow(1, $i)->getValue())); //Excel Column 1
                                $ingredint_category = htmlspecialchars(trim($objWorksheet->getCellByColumnAndRow(2, $i)->getValue())); //Excel Column 2
                                $ingredint_unit = htmlspecialchars(trim($objWorksheet->getCellByColumnAndRow(3, $i)->getValue())); //Excel Column 3
                                $ingredint_alertqty = htmlspecialchars(trim($objWorksheet->getCellByColumnAndRow(4, $i)->getValue())); //Excel Column 4
                                $ingredint_perchaseprice = htmlspecialchars(trim($objWorksheet->getCellByColumnAndRow(5, $i)->getValue())); //Excel Column 5

                                $ingredint_unit = $this->get_unit_id($ingredint_unit);
                                $ingredint_category = $this->get_cat_id($ingredint_category);

                                $fmc_info = array();
                                $fmc_info['name'] = $ingredint_name;
                                $fmc_info['code'] = $ingredint_code;
                                $fmc_info['category_id'] = $ingredint_category;
                                $fmc_info['purchase_price'] = $ingredint_perchaseprice;
                                $fmc_info['alert_quantity'] = $ingredint_alertqty;
                                $fmc_info['unit_id'] = $ingredint_unit;
                                $fmc_info['user_id'] = $this->session->userdata('user_id');
                                $fmc_info['company_id'] = $this->session->userdata('company_id');
                                $this->Common_model->insertInformation($fmc_info, "tbl_ingredients");
                            }
                            unlink(FCPATH . 'asset/excel/' . $file_name); //File Deleted After uploading in database .
                            $this->session->set_flashdata('exception', 'Imported successfully!');
                            redirect('ingredient/ingredients');
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
                $this->session->set_flashdata('exception_err', "We can not accept other files, please download the sample file 'Ingredient_Upload.xlsx', fill it up properly and upload it or rename the file name as 'Ingredient_Upload.xlsx' then fill it.");
            }
        } else {
            $this->session->set_flashdata('exception_err', 'File is required');
        }
        redirect('ingredient/uploadingredients');
    }
     /**
     * get unit by id
     * @access public
     * @return int
     * @param int
     */
    public function get_unit_id($ingredint_unit) {
        $company_id = $this->session->userdata('company_id');
        $user_id = $this->session->userdata('user_id');
        $id = $this->db->query("SELECT id FROM tbl_units WHERE company_id=$company_id and unit_name='" . $ingredint_unit . "'")->row('id');
        if ($id != '') {
            return $id;
        } else {
            $data = array('unit_name' => $ingredint_unit, 'company_id' => $company_id);
            $query = $this->db->insert('tbl_units', $data);
            $id = $this->db->insert_id();
            return $id;
        }
    }
     /**
     * get category id
     * @access public
     * @return int
     * @param string
     */
    public function get_cat_id($ingredint_category) {
        $company_id = $this->session->userdata('company_id');
        $user_id = $this->session->userdata('user_id');
        $id = $this->db->query("SELECT id FROM tbl_ingredient_categories WHERE user_id=$user_id and company_id=$company_id and category_name='" . $ingredint_category . "'")->row('id');
        if ($id != '') {
            return $id;
        } else {
            $data = array('category_name' => $ingredint_category, 'user_id' => $user_id, 'company_id' => $company_id);
            $query = $this->db->insert('tbl_ingredient_categories', $data);
            $id = $this->db->insert_id();
            return $id;
        }
    }

}
