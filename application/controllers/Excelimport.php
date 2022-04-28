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
  # This is Excelimport Controller
  ###########################################################
 */

defined('BASEPATH') OR exit('No direct script access allowed');
 
class Excelimport extends Cl_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('excel'); //load PHPExcel library
        $this->load->library('form_validation');
        $this->load->model(array('Admin_model'));
        $login_session['active_menu_tmp'] = '';
        $this->session->set_userdata($login_session);
    }

    /**
     * download files
     * @access public
     * @return void
     * @param string
     */
    public function downloadPDF($file = "") {
        // load ci download helder
        $this->load->helper('download');
        $data = file_get_contents("asset/customersample/" . $file); // Read the file's 
        $name = $file;
        force_download($name, $data);
    }
    /**
     * excel data add
     * @access public
     * @return void
     * @param no
     */
    public function ExcelDataAdd() {
        if ($_FILES['userfile']['name'] != "") {
            if ($_FILES['userfile']['name'] == "Customer.xlsx") {
                    //Path of files were you want to upload on localhost (C:/xampp/htdocs/ProjectName/uploads/excel/)
                $configUpload['upload_path'] = FCPATH . 'asset/excel/';
                $configUpload['allowed_types'] = 'xls|xlsx|csv';
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
                    if ($totalrows > 2) {
                        $arrayerror = '';

                        for ($i = 3; $i <= $totalrows; $i++) {
                            $customer_name = $objWorksheet->getCellByColumnAndRow(0, $i)->getValue();
                            $contact_person = $objWorksheet->getCellByColumnAndRow(1, $i)->getValue(); //Excel Column 1
                            $phone = $objWorksheet->getCellByColumnAndRow(2, $i)->getValue(); //Excel Column 2
                            $email_address = $objWorksheet->getCellByColumnAndRow(3, $i)->getValue(); //Excel Column 3
                            $website = $objWorksheet->getCellByColumnAndRow(4, $i)->getValue(); //Excel Column 4
                            $billing_address = $objWorksheet->getCellByColumnAndRow(5, $i)->getValue(); //Excel Column 5
                            $service_address = $objWorksheet->getCellByColumnAndRow(6, $i)->getValue(); //Excel 
                            if ($customer_name == '' || $contact_person == '' || $phone == '' || $billing_address == '' || $service_address == '') {
                                if ($arrayerror == '') {
                                    $arrayerror.=" Row Number: $i";
                                } else {
                                    $arrayerror.=", Row Number: $i";
                                }
                            }
                        }
                        if ($arrayerror == '') {
                            for ($i = 3; $i <= $totalrows; $i++) {
                                $customer_name = $objWorksheet->getCellByColumnAndRow(0, $i)->getValue();
                                $contact_person = $objWorksheet->getCellByColumnAndRow(1, $i)->getValue(); //Excel Column 1
                                $phone = $objWorksheet->getCellByColumnAndRow(2, $i)->getValue(); //Excel Column 2
                                $email_address = $objWorksheet->getCellByColumnAndRow(3, $i)->getValue(); //Excel Column 3
                                $website = $objWorksheet->getCellByColumnAndRow(4, $i)->getValue(); //Excel Column 4
                                $billing_address = $objWorksheet->getCellByColumnAndRow(5, $i)->getValue(); //Excel Column 5
                                $service_address = $objWorksheet->getCellByColumnAndRow(6, $i)->getValue(); //Excel Column 6
                                $data_user = array();
                                $data_user['customer_name'] = $customer_name;
                                $data_user['contact_person'] = $contact_person;
                                $data_user['phone'] = $phone;
                                $data_user['email_address'] = $email_address;
                                $data_user['website'] = $website;
                                $data_user['billing_address'] = $billing_address;
                                $data_user['service_address'] = $service_address;
                                $data_user['org_id'] = $this->session->userdata('org_id');
                                $data_user['user_id'] = $this->session->userdata('user_id');
                                $this->Admin_model->AddCustomer($data_user);
                            }
                            unlink('./excel/' . $file_name); //File Deleted After uploading in database .		
                            $this->session->set_userdata('exception', 'Imported successfully!');
                        } else {
                            $this->session->set_userdata('exception_err', "Required Data Missing:$arrayerror");
                        }
                    } else {
                        $this->session->set_userdata('exception_err', "No data found.");
                    }
                } else {
                    $error = $this->upload->display_errors();
                    $this->session->set_userdata('exception_err', "$error");
                }
            } else {
                $this->session->set_userdata('exception_err', "We can not accept other files, please download the sample file 'Customer.xlsx', fill it up properly and upload it.");
            }
        } else {
            $this->session->set_userdata('exception_err', 'File is required');
        }
        redirect(base_url() . "admin/importCutomer");
    }

}

?>