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
  # This is Customer Controller
  ###########################################################
 */

defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends Cl_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('excel'); //load PHPExcel library
        $this->load->model('Common_model');
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
     * customers info
     * @access public
     * @return void
     * @param no
     */
    public function customers() {
        $company_id = $this->session->userdata('company_id');

        $data = array();
        $data['customers'] = $this->Common_model->getAllByCompanyId($company_id, "tbl_customers");
        $data['main_content'] = $this->load->view('master/customer/customers', $data, TRUE);
        $this->load->view('userHome', $data);
    }
     /**
     * delete customer
     * @access public
     * @return void
     * @param int
     */
    public function deleteCustomer($id) {
        $id = $this->custom->encrypt_decrypt($id, 'decrypt');

        $this->Common_model->deleteStatusChange($id, "tbl_customers");

        $this->session->set_flashdata('exception',lang('delete_success'));
        redirect('customer/customers');
    }
     /**
     * upload customer from excel file
     * @access public
     * @return void
     * @param no
     */
    public function uploadCustomer()
    {
        $company_id = $this->session->userdata('company_id');
        $data = array();
        $data['main_content'] = $this->load->view('master/customer/uploadsCustomer', $data, TRUE);
        $this->load->view('userHome', $data);
    }
     /**
     * add/edit customer
     * @access public
     * @return void
     * @param int
     */
    public function addEditCustomer($encrypted_id = "") {
        $id = $this->custom->encrypt_decrypt($encrypted_id, 'decrypt');
        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('name', lang('category_name'), 'required|max_length[50]');
            $this->form_validation->set_rules('phone', lang('phone'), 'required|max_length[50]');
            if ($this->form_validation->run() == TRUE) {
                $customer_info = array();
                $customer_info['name'] = htmlspecialchars($this->input->post($this->security->xss_clean('name')));
                $customer_info['phone'] =htmlspecialchars($this->input->post($this->security->xss_clean('phone')));
                $customer_info['email'] =htmlspecialchars($this->input->post($this->security->xss_clean('email')));
                if(htmlspecialchars($this->input->post($this->security->xss_clean('date_of_birth')))){
                    $customer_info['date_of_birth'] =htmlspecialchars($this->input->post($this->security->xss_clean('date_of_birth')));
                }
                if(htmlspecialchars($this->input->post($this->security->xss_clean('date_of_anniversary')))){
                    $customer_info['date_of_anniversary'] =htmlspecialchars($this->input->post($this->security->xss_clean('date_of_anniversary')));
                }
                $customer_info['address'] =htmlspecialchars($this->input->post($this->security->xss_clean('address')));
                if(collectGST()=="Yes"){
                    $customer_info['gst_number'] =htmlspecialchars($this->input->post($this->security->xss_clean('gst_number')));
                }
                $customer_info['user_id'] = $this->session->userdata('user_id');
                $customer_info['company_id'] = $this->session->userdata('company_id');
                if ($id == "") {
                    $this->Common_model->insertInformation($customer_info, "tbl_customers");
                    $this->session->set_flashdata('exception', lang('insertion_success'));
                } else {
                    $this->Common_model->updateInformation($customer_info, $id, "tbl_customers");
                    $this->session->set_flashdata('exception',lang('update_success'));
                }
                redirect('customer/customers');
            } else {
                if ($id == "") {
                    $data = array();
                    $data['main_content'] = $this->load->view('master/customer/addCustomer', $data, TRUE);
                    $this->load->view('userHome', $data);
                } else {
                    $data = array();
                    $data['encrypted_id'] = $encrypted_id;
                    $data['customer_information'] = $this->Common_model->getDataById($id, "tbl_customers");
                    $data['main_content'] = $this->load->view('master/customer/editCustomer', $data, TRUE);
                    $this->load->view('userHome', $data);
                }
            }
        } else {
            if ($id == "") {
                $data = array();
                $data['main_content'] = $this->load->view('master/customer/addCustomer', $data, TRUE);
                $this->load->view('userHome', $data);
            } else {
                $data = array();
                $data['encrypted_id'] = $encrypted_id;
                $data['customer_information'] = $this->Common_model->getDataById($id, "tbl_customers");
                $data['main_content'] = $this->load->view('master/customer/editCustomer', $data, TRUE);
                $this->load->view('userHome', $data);
            }
        }
    }
     /**
     * excel data add form
     * @access public
     * @return void
     * @param no
     */
    public function ExcelDataAddCustomers()
    {
        $company_id = $this->session->userdata('company_id');
        if ($_FILES['userfile']['name'] != "") {
            if ($_FILES['userfile']['name'] == "Customer_Upload.xlsx") {
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
                    if ($totalrows < 54) {
                        $arrayerror = '';
                        for ($i = 4; $i <= $totalrows; $i++) {
                            $name = htmlspecialchars(trim($objWorksheet->getCellByColumnAndRow(0, $i)->getValue()));
                            $phone = htmlspecialchars(trim($objWorksheet->getCellByColumnAndRow(1, $i)->getValue()));
                            $email = htmlspecialchars(trim($objWorksheet->getCellByColumnAndRow(2, $i)->getValue()));
                            $dob = htmlspecialchars(trim($objWorksheet->getCellByColumnAndRow(3, $i)->getValue()));
                            $doa = htmlspecialchars(trim($objWorksheet->getCellByColumnAndRow(4, $i)->getValue()));
                            $delivery_address = htmlspecialchars(trim($objWorksheet->getCellByColumnAndRow(5, $i)->getValue()));

                            if ($name == '') {
                                if ($arrayerror == '') {
                                    $arrayerror.="Row Number $i column A required";
                                } else {
                                    $arrayerror.="<br>Row Number $i column A required";
                                }
                            }

                            if ($phone == '') {
                                if ($arrayerror == '') {
                                    $arrayerror.="Row Number $i column B required";
                                } else {
                                    $arrayerror.="<br>Row Number $i column B required";
                                }
                            }

                            if ($email != '' && $this->validateEmail($email)==false) {
                                if ($arrayerror == '') {
                                    $arrayerror.="Row Number $i column C should be valid email";
                                } else {
                                    $arrayerror.="<br>Row Number $i column C should be valid email";
                                }
                            }

                            if ($dob != '' && $this->isValidDate($dob)==false) {
                                if ($arrayerror == '') {
                                    $arrayerror.="Row Number $i column D should be in YYYY-MM-DD format";
                                } else {
                                    $arrayerror.="<br>Row Number $i column D should be in YYYY-MM-DD format";
                                }
                            }

                            if ($doa != '' && $this->isValidDate($doa)==false) {
                                if ($arrayerror == '') {
                                    $arrayerror.="Row Number $i column E should be in YYYY-MM-DD format";
                                } else {
                                    $arrayerror.="<br>Row Number $i column E should be in YYYY-MM-DD format";
                                }
                            }
                        }
                        if ($arrayerror == '') {
                            if(!is_null($this->input->post('remove_previous'))){
                                $this->db->query("TRUNCATE table `tbl_customers`");
                            }
                            for ($i = 4; $i <= $totalrows; $i++) {
                                $name = htmlspecialchars(trim($objWorksheet->getCellByColumnAndRow(0, $i)->getValue()));
                                $phone = htmlspecialchars(trim($objWorksheet->getCellByColumnAndRow(1, $i)->getValue()));
                                $email = htmlspecialchars(trim($objWorksheet->getCellByColumnAndRow(2, $i)->getValue()));
                                $dob = htmlspecialchars(trim($objWorksheet->getCellByColumnAndRow(3, $i)->getValue()));
                                $doa = htmlspecialchars(trim($objWorksheet->getCellByColumnAndRow(4, $i)->getValue()));
                                $delivery_address = htmlspecialchars(trim($objWorksheet->getCellByColumnAndRow(5, $i)->getValue()));


                                $customer_info = array();
                                $customer_info['name'] = $name;
                                $customer_info['phone'] = $phone;
                                $customer_info['email'] = $email;
                                $customer_info['date_of_birth'] = $dob;
                                $customer_info['date_of_anniversary'] = $doa;
                                $customer_info['address'] = $delivery_address;
                                $customer_info['user_id'] = $this->session->userdata('user_id');
                                $customer_info['company_id'] = $this->session->userdata('company_id');
                                $this->Common_model->insertInformation($customer_info, "tbl_customers");
                            }
                            unlink(FCPATH . 'asset/excel/' . $file_name); //File Deleted After uploading in database .
                            $this->session->set_flashdata('exception', 'Imported successfully!');
                            redirect('customer/customers');
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
                $this->session->set_flashdata('exception_err', "We can not accept other files, please download the sample file 'Customer_Upload.xlsx', fill it up properly and upload it or rename the file name as 'Customer_Upload.xlsx' then fill it.");
            }
        } else {
            $this->session->set_flashdata('exception_err', 'File is required');
        }
        redirect('customer/uploadCustomer');
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
     * check validate email address
     * @access public
     * @return object
     * @param string
     */
    function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
     /**
     * check valid dat4e
     * @access public
     * @return boolean
     * @param string
     */
    function isValidDate($date){
        if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$date)) {
            return true;
        } else {
            return false;
        }
    }

}
