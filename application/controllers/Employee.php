<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    date_default_timezone_set('Asia/Kolkata');
    
    class Employee extends CI_Controller
    {
        public function __construct()
        {
            parent::__construct();
            $this->load->library('ajax_pagination_dashboard');
            $this->load->model('user_model');
            
            if ($this->session->userdata('RoleType')) {
                if (empty($this->session->userdata('UserId'))) {
                    return redirect(base_url() . 'index');
                }
            } else {
                if (empty($this->session->userdata('user_id'))) {
                    return redirect(base_url() . 'index');
                }
            }
        }
        
        public function Index() 
        {
            $Where = ['IsDeleted' => 1, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id')];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            $Data['Employees'] = $this->db->select('Id, Date, EmployeeId, RoleId, Name, Email, Mobile, IsActive, Status, Photograph')->where($Where)->order_by('Id', 'DESC')->get('employees')->result();
            $Data['page'] = 'Employee/Index';
            return $this->load->view('inventory_sep/inventory', $Data);
        }
        
        public function Position() 
        {
            if (get_title('roles', ['id' => $this->session->userdata('roles')], 'full_name') == "Admin") {
                $Where = ['IsDeleted' => 1];
                $Data['Positions'] = $this->db->select('Id, PostName, Status, Created, Updated')->where($Where)->order_by('Id', 'DESC')->get('position')->result();
                $Data['page'] = 'Employee/Position';
                return $this->load->view('inventory_sep/inventory', $Data);
            } else {
                $this->session->set_flashdata(['msg' => 'You do not permission to access this page.', 'class' => 'danger']);
                return redirect(base_url() . 'inventory/dashboard');
            }
        }
        
        public function PositionSave() 
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
                $this->form_validation->set_rules('PostName', 'Post Name', 'trim|required|is_unique[position.PostName]');
                if ($this->form_validation->run() == false) {
                    $Where = ['IsDeleted' => 1];
                    $Data['Positions'] = $this->db->select('Id, PostName, Status, Created, Updated')->where($Where)->order_by('Id', 'DESC')->get('position')->result();
                    $Data['page'] = 'Employee/Position';
                    $Data['error'] = validation_errors();
                    return $this->load->view('inventory_sep/inventory', $Data);
                } else {
                    $PositionData = [
                        'PostName' => $this->input->post('PostName'),
                        'Created' => date('d-m-Y h:i:s A'),
                        'CreatedBy' => get_title('users', ['id' => $this->session->userdata('user_id')], 'name'),
                    ];
                    
                    $Result = $this->user_model->saveAry('position', $PositionData);
                    if (!empty($Result)) {
                        $this->session->set_flashdata(['msg' => 'Record save successfully.', 'class' => 'success']);
                        return redirect(base_url() . 'Employee/Position');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to save record.', 'class' => 'danger']);
                        return redirect(base_url() . 'Employee/Position');
                    }
                }
            }
        }
        
        public function PositionEdit($Id) 
        {
            if (!empty($Id)) {
                $Where = ['IsDeleted' => 1];
                if ($this->db->select('Id, PostName')->where('Id', $Id)->where($Where)->get('position')->num_rows()) {
                    $Data['PositionId'] = $Id;
                    $Data['PositionData'] = $this->db->select('Id, PostName')->where('Id', $Id)->where($Where)->get('position')->row();
                    $Data['Positions'] = $this->db->select('Id, PostName, Status, Created, Updated')->where($Where)->order_by('Id', 'DESC')->get('position')->result();
                    $Data['page'] = 'Employee/Position';
                    return $this->load->view('inventory_sep/inventory', $Data);
                } else {
                    $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                    return redirect(base_url() . 'Employee/Position');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Position id is missing.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/Position');
            }
        }
        
        public function IsUniquePostName($PostName, $Id) 
        {
            $Where = ['IsDeleted' => 1, 'PostName' => $PostName, 'Id != ' => $Id];
            $QueryResult = $this->db->select('Id, PostName')->where($Where)->get('position')->num_rows();
            if ($QueryResult === 0) {
                return true;
            } else {
                $this->form_validation->set_message('IsUniquePostName', 'The Position name must be unique.');
                return false;
            }
        }
        
        public function PositionUpdate($Id) 
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') { 
                $this->form_validation->set_rules('PostName', 'Post Name', 'trim|required|callback_IsUniquePostName[' . $Id . ']');
                if ($this->form_validation->run() == false) {
                    $Where = ['IsDeleted' => 1];
                    $Data['PositionId'] = $Id;
                    $Data['PositionData'] = $this->db->select('Id, PostName')->where('Id', $Id)->where($Where)->get('position')->row();
                    $Data['Positions'] = $this->db->select('Id, PostName, Status, Created, Updated')->where($Where)->order_by('Id', 'DESC')->get('position')->result();
                    $Data['page'] = 'Employee/Position';
                    $Data['error'] = validation_errors();
                    return $this->load->view('inventory_sep/inventory', $Data);
                } else {
                    $PositionData = [
                        'PostName' => $this->input->post('PostName'),
                        'Updated' => date('d-m-Y h:i:s A'),
                        'UpdatedBy' => get_title('users', ['id' => $this->session->userdata('user_id')], 'name'),
                    ];
                    
                    $UpdateWhere = ['Id' => $this->input->post('PositionId'), 'IsDeleted' => 1];
                    $Result = $this->user_model->updateAry('position', $PositionData, $UpdateWhere);
                    
                    if (!empty($Result)) {
                        $this->session->set_flashdata(['msg' => 'Record update successfully.', 'class' => 'success']);
                        return redirect(base_url() . 'Employee/Position');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to update record.', 'class' => 'danger']);
                        return redirect(base_url() . 'Employee/Position');
                    }
                }
            }
        }
        
        public function PositionStatus($Id)
        {
            if ($Id) {
                $Where = ['Id' => $Id, 'IsDeleted' => 1];
                $Position = $this->user_model->getRow('position', $Where);
                if ($Position) {
                    if ($Position->Status == 1) {
                        $Status = 0;
                    } else {
                        $Status = 1;
                    }
                    $Result = $this->user_model->updateAry('position', ['Status' => $Status], $Where);
                    if (!empty($Result)) {
                        $this->session->set_flashdata(['msg' => 'Status update successfully.', 'class' => 'success']);
                        return redirect(base_url() . 'Employee/Position');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to update record.', 'class' => 'danger']);
                        return redirect(base_url() . 'Employee/Position');
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'Somethings went wrong, please try again.', 'class' => 'danger']);
                    return redirect(base_url() . 'Employee/Position');                    
                }
            } else {
                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/Position');
            }
        }
        
        public function PositionDelete($Id)
        {
            if ($Id) {
                $Where = ['Id' => $Id, 'IsDeleted' => 1];
                if ($this->db->select('Id')->where($Where)->get('position')->num_rows()) {
                    if ($this->user_model->updateAry('position', ['IsDeleted' => 0], $Where)) {
                        $this->session->set_flashdata(['msg' => 'Record delete successfully.', 'class' => 'success']);
                        return redirect(base_url() . 'Employee/Position');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to delete record.', 'class' => 'danger']);
                        return redirect(base_url() . 'Employee/Position');
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                    return redirect(base_url() . 'Employee/Position');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Position id is missing.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/Position');
            }
        }
        
        public function Create()
        {
            $Where = ['UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id')];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            
            if ($this->db->select('Prefix, Starting')->where($Where)->where(['Prefix != ' => '', 'Starting != ' => ''])->get('employee_config')->num_rows()) {
                $SeriesData = $this->db->select('Prefix, Starting')->where($Where)->get('employee_config')->row();
                $PreviousEmployeeId = $this->db->select('EmployeeId')->where($Where)->where('IsDeleted', 1)->order_by('Id', 'DESC')->get('employees')->row('EmployeeId');
                if ($PreviousEmployeeId) {
                    $EmployeeIdStr = explode("/", $PreviousEmployeeId);
                    $Series = $EmployeeIdStr[1];
                    $SeriesNumber = $Series += 1;
                    if ($SeriesNumber < '10') {
                        $NewSeries = "00$Series";
                    } elseif ($SeriesNumber < '100') {
                        $NewSeries = "0$Series";
                    } elseif ($SeriesNumber < '1000') {
                        $NewSeries = "$SeriesNumber";
                    } else {
                        $NewSeries = "$SeriesNumber";
                    }
                    $EmployeeId = $SeriesData->Prefix . "/" . $NewSeries . "/" . date('Y');
                } else {
                    $EmployeeId = $SeriesData->Prefix . "/" . $SeriesData->Starting . "/" . date('Y');
                }
            } else {
                $EmployeeId = $this->db->select('Id')->where($Where)->where('IsDeleted', 1)->order_by('Id', 'DESC')->get('employees')->row('Id') + 1;
            }
            $data['EmployeeId'] = $EmployeeId;
            
            $WarehouseWhere = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
            if ($this->session->userdata('BranchRole')) {
                $WarehouseWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            $data['Positions'] = $this->db->select('Id, PostName')->where(['IsDeleted' => 1, 'Status' => 1])->order_by('Id', 'DESC')->get('position')->result();
            $data['Warehouses'] = $this->user_model->getResult('warehouse', $WarehouseWhere);
            $data['page'] = "Employee/Create";
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function getWarehouseSalesman() 
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $WarehouseId = $this->input->post('WarehouseId');
                if (!isset($WarehouseId) && empty($WarehouseId)) {
                    echo json_encode(['Status' => false, 'Data' => "Please select warehouse first."]);
                } else {
                    $UserId = $this->session->userdata('user_id');
                    $CompId = $this->session->userdata('comp_id');
                    $CompBranchId = $this->session->userdata('CompBranchId');
                    $SalemanRoleId = get_title('position', ['PostName' => 'Salesman'], 'Id');
                    $Where = ['IsDeleted' => 1, 'Status' => 1, 'WarehouseId' => $WarehouseId, 'RoleId' => $SalemanRoleId, 'UserId' => $UserId, 'CompId' => $CompId];
                    if ($this->session->userdata('BranchRole')) {
                        $Where['CompanyBranchId'] = $CompBranchId;
                    }
                    if ($this->db->select('Id')->where($Where)->get('employees')->num_rows()) {
                        $Salesmans = $this->db->select('Id, Name')->where($Where)->order_by('Id', 'DESC')->get('employees')->result();
                        echo json_encode(['Status' => true, 'Data' => $Salesmans]);
                    } else {
                        echo json_encode(['Status' => false, 'Data' => "No record found."]);
                    }
                }
            }
        }
        
        public function IsUniqeMobile()
        {
            $Mobile = $this->input->post('Mobile');
            $UserId = $this->session->userdata('user_id');
            $CompId = $this->session->userdata('comp_id');
            $CompBranchId = $this->session->userdata('CompBranchId');
            
            $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1, 'Mobile' => $Mobile];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $CompBranchId;
            }
            
            if ($this->db->select('id')->where($Where)->get('employees')->num_rows() === 0) {
                return true;
            } else {
                $this->form_validation->set_message('IsUniqeMobile', 'The mobile must be unique.');
                return false;
            }
        }
        
        public function IsUniqeEmail()
        {
            $Email = $this->input->post('Email');
            $UserId = $this->session->userdata('UserId');
            $CompId = $this->session->userdata('CompId');
            $CompBranchId = $this->session->userdata('CompBranchId');
            
            $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1, 'Email' => $Email];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $CompBranchId;
            }
            
            if ($this->db->select('id')->where($Where)->get('employees')->num_rows() === 0) {
                return true;
            } else {
                $this->form_validation->set_message('IsUniqeEmail', 'The email must be unique.');
                return false;
            }
        }
        
        public function Save()
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $this->form_validation->set_rules('EmployeeId', 'Employee Id', 'trim|required');
                $this->form_validation->set_rules('Name', 'Name', 'trim|required');
                $this->form_validation->set_rules('Mobile', 'Mobile', 'trim|required|numeric|max_length[10]|min_length[10]|callback_IsUniqeMobile');
                $this->form_validation->set_rules('Email', 'Email', 'trim|required|valid_email|callback_IsUniqeEmail');
                $this->form_validation->set_rules('Gender', 'Gender', 'trim|required');
                $this->form_validation->set_rules('DateOfBirth', 'Date of Birth', 'trim|required');
                $this->form_validation->set_rules('BloodGroup', 'Blood Group', 'trim');
                $this->form_validation->set_rules('EmergencyNumber', 'Emergency Number', 'trim|numeric|max_length[10]|min_length[10]');
                $this->form_validation->set_rules('TemporaryAddress', 'Temporary Address', 'trim');
                $this->form_validation->set_rules('PermanentAddress', 'Permanent Address', 'trim');
                $this->form_validation->set_rules('FatherName', 'Father Name', 'trim');
                $this->form_validation->set_rules('FatherMobile', 'Father Mobile', 'trim|numeric|max_length[10]|min_length[10]');
                $this->form_validation->set_rules('MotherName', 'Mother Name', 'trim');
                $this->form_validation->set_rules('MotherMobile', 'Mother Mobile', 'trim|numeric|max_length[10]|min_length[10]');
                $this->form_validation->set_rules('RoleId', 'Employee Post', 'trim|required');
                $this->form_validation->set_rules('WarehouseId', 'Warehouse', 'trim|required');
                $this->form_validation->set_rules('AadhaarNumber', 'Aadhaar Number', 'trim|numeric|max_length[12]|min_length[12]');
                $this->form_validation->set_rules('PANNumber', 'PAN Number', 'trim|max_length[10]|min_length[10]');
                $this->form_validation->set_rules('UANNumber', 'UAN Number', 'trim|max_length[12]|min_length[12]');
                $this->form_validation->set_rules('ESINumber', 'ESI Number', 'trim|max_length[17]|min_length[17]');
                $this->form_validation->set_rules('JoiningDate', 'Joining Date', 'trim');
                $this->form_validation->set_rules('ExpiryDate', 'Expiry Date', 'trim');
                $this->form_validation->set_rules('Password', 'Password', 'trim|required|max_length[20]|min_length[6]');
                $this->form_validation->set_rules('IPAddress', 'IPAddress', 'trim|valid_ip');
                $this->form_validation->set_rules('BankName', 'Bank Name', 'trim');
                $this->form_validation->set_rules('Branch Name', 'Branch Name', 'trim');
                $this->form_validation->set_rules('IFSC Code', 'IFSC Code', 'trim|max_length[11]|min_length[11]');
                $this->form_validation->set_rules('Account Number', 'Account Number', 'trim|numeric|max_length[20]');
                $this->form_validation->set_rules('Basic', 'Basic', 'trim|required|numeric|min_length[1]|max_length[10]');
                $this->form_validation->set_rules('HRA', 'House Rent Allowance', 'trim|required|numeric|min_length[1]|max_length[10]');
                $this->form_validation->set_rules('ConveyanceAllowance', 'Conveyance Allowance', 'trim|required|numeric|min_length[1]|max_length[10]');
                $this->form_validation->set_rules('StaffSpecialAllowance', 'Staff Special Allowance', 'trim|numeric|required|min_length[1]|max_length[10]');
                $this->form_validation->set_rules('GrossMonthlyTotal', 'Gross Monthly Total', 'trim|numeric|required|min_length[1]|max_length[10]');
                $this->form_validation->set_rules('EmployeePF', 'Employee PF', 'trim|required|numeric|min_length[1]|max_length[10]');
                $this->form_validation->set_rules('EmployeeESI', 'Employee ESI', 'trim|required|numeric|min_length[1]|max_length[10]');
                $this->form_validation->set_rules('ProfessionTax', 'Profession Tax', 'trim|required|numeric|min_length[1]|max_length[10]');
                $this->form_validation->set_rules('IncomeTax', 'Income Tax', 'trim|required|numeric|min_length[1]|max_length[10]');
                $this->form_validation->set_rules('TDS', 'TDS', 'trim|required|numeric|min_length[1]|max_length[2]');
                $this->form_validation->set_rules('TDSAmount', 'TDS Amount', 'trim|required|numeric|min_length[1]|max_length[10]');
                $this->form_validation->set_rules('MonthlyDeduction', 'Monthly Deduction', 'trim|required|numeric|min_length[1]|max_length[10]');
                $this->form_validation->set_rules('MonthlyNetTakeHome', 'Monthly Net Take Home', 'trim|required|numeric|min_length[1]|max_length[10]');
                $this->form_validation->set_rules('EmployerPF', 'Employer PF', 'trim|required|numeric|min_length[1]|max_length[10]');
                $this->form_validation->set_rules('EmployerESI', 'Employer ESI', 'trim|required|numeric|min_length[1]|max_length[10]');
                $this->form_validation->set_rules('AnnualComponents', 'Annual Components', 'trim|required|numeric|min_length[1]|max_length[10]');
                $this->form_validation->set_rules('PerformanceBonus', 'Performance Bonus', 'trim|required|numeric|min_length[1]|max_length[10]');
                $this->form_validation->set_rules('Gratuity', 'Gratuity', 'trim|required|numeric|min_length[1]|max_length[10]');
                $this->form_validation->set_rules('LeaveTravelAllowance', 'Leave Travel Allowance', 'trim|required|numeric|min_length[1]|max_length[10]');
                $this->form_validation->set_rules('CostToCompany', 'CTC', 'trim|required|numeric|min_length[1]|max_length[10]');
                if ($this->form_validation->run() == false) {
                    $Where = ['UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id')];
                    if ($this->session->userdata('BranchRole')) {
                        $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                    }
                    if ($this->db->select('Prefix, Starting')->where($Where)->where(['Prefix != ' => '', 'Starting != ' => ''])->get('employee_config')->num_rows()) {
                        $SeriesData = $this->db->select('Prefix, Starting')->where($Where)->get('employee_config')->row();
                        $PreviousEmployeeId = $this->db->select('EmployeeId')->where($Where)->where('IsDeleted', 1)->order_by('Id', 'DESC')->get('employees')->row('EmployeeId');
                        if ($PreviousEmployeeId) {
                            $EmployeeIdStr = explode("/", $PreviousEmployeeId);
                            $Series = $EmployeeIdStr[1];
                            $SeriesNumber = $Series += 1;
                            if ($SeriesNumber < '10') {
                                $NewSeries = "00$Series";
                            } elseif ($SeriesNumber < '100') {
                                $NewSeries = "0$Series";
                            } elseif ($SeriesNumber < '1000') {
                                $NewSeries = "$Series";
                            } else {
                                $NewSeries = "$Series";
                            }
                            $EmployeeId = $SeriesData->Prefix . "/" . $NewSeries . "/" . date('Y');
                        } else {
                            $EmployeeId = $SeriesData->Prefix . "/" . $SeriesData->Starting . "/" . date('Y');
                        }
                    } else {
                        $EmployeeId = $this->db->select('EmployeeId')->where($Where)->where('IsDeleted', 1)->order_by('Id', 'DESC')->get('employees')->row('Id') + 1;
                    }
                    $data['EmployeeId'] = $EmployeeId;
                    $WarehouseWhere = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                    if ($this->session->userdata('BranchRole')) {
                        $WarehouseWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                    }
                    $data['Positions'] = $this->db->select('Id, PostName')->where(['IsDeleted' => 1, 'Status' => 1])->order_by('Id', 'DESC')->get('position')->result();
                    $data['Warehouses'] = $this->user_model->getResult('warehouse', $WarehouseWhere);
                    $data['page'] = "Employee/Create";
                    $data['error'] = validation_errors();
                    return $this->load->view('inventory_sep/inventory', $data);
                } else {
                    if ($_FILES['Photograph']['name']) {
                        $PhotographFileName = time() . $_FILES['Photograph']['name'];
                        move_uploaded_file($_FILES['Photograph']['tmp_name'], 'uploads/Employee/Photograph/' . $PhotographFileName);
                        $Photograph = $PhotographFileName;
                    } else {
                        $Photograph = "";
                    }
                    
                    if ($_FILES['IdentityProof']['name']) {
                        $IdentityProofFileName = time() . $_FILES['IdentityProof']['name'];
                        move_uploaded_file($_FILES['IdentityProof']['tmp_name'], 'uploads/Employee/IdentityProof/' . $IdentityProofFileName);
                        $IdentityProof = $IdentityProofFileName;
                    } else {
                        $IdentityProof = "";
                    }
                    
                    if ($_FILES['AddressProof']['name']) {
                        $AddressProofFileName = time() . $_FILES['AddressProof']['name'];
                        move_uploaded_file($_FILES['AddressProof']['tmp_name'], 'uploads/Employee/AddressProof/' . $AddressProofFileName);
                        $AddressProof = $AddressProofFileName;
                    } else {
                        $AddressProof = "";
                    }
                    
                    if ($_FILES['BankPassbook']['name']) {
                        $BankPassbookFileName = time() . $_FILES['IdentityProof']['name'];
                        move_uploaded_file($_FILES['BankPassbook']['tmp_name'], 'uploads/Employee/BankPassbook/' . $BankPassbookFileName);
                        $BankPassbook = $BankPassbookFileName;
                    } else {
                        $BankPassbook = "";
                    }
                    
                    $EmployeeData = [
                        'Date' => date('Y-m-d'),
                        'EmployeeId' => $this->input->post('EmployeeId'),
                        'Name' => $this->input->post('Name'),
                        'Mobile' => $this->input->post('Mobile'),
                        'Email' => $this->input->post('Email'),
                        'Gender' => $this->input->post('Gender'),
                        'DateOfBirth' => $this->input->post('DateOfBirth'),
                        'BloodGroup' => $this->input->post('BloodGroup'),
                        'EmergencyNumber' => $this->input->post('EmergencyNumber'),
                        'TemporaryAddress' => $this->input->post('TemporaryAddress'),
                        'PermanentAddress' => $this->input->post('PermanentAddress'),
                        'FatherName' => $this->input->post('FatherName'),
                        'FatherMobile' => $this->input->post('FatherMobile'),
                        'MotherName' => $this->input->post('MotherName'),
                        'MotherMobile' => $this->input->post('MotherMobile'),
                        'AadhaarNumber' => $this->input->post('AadhaarNumber'),
                        'PANNumber' => $this->input->post('PANNumber'),
                        'UANNumber' => $this->input->post('UANNumber'),
                        'ESINumber' => $this->input->post('ESINumber'),
                        'RoleId' => $this->input->post('RoleId'),
                        'WarehouseId' => $this->input->post('WarehouseId'),
                        'WarehouseSalesman' => json_encode($this->input->post('WarehouseSalesman')),
                        'JoiningDate' => $this->input->post('JoiningDate'),
                        'ExpiryDate' => $this->input->post('ExpiryDate'),
                        'Password' => $this->input->post('Password'),
                        'IPAddress' => $this->input->post('IPAddress'),
                        'SalesTarget' => $this->input->post('SalesTarget'),
                        'Photograph' => $Photograph,
                        'IdentityProof' => $IdentityProof,
                        'AddressProof' => $AddressProof,
                        'BankPassbook' => $BankPassbook,
                        'BankName' => $this->input->post('BankName'),
                        'BranchName' => $this->input->post('BranchName'),
                        'IFSCCode' => $this->input->post('IFSCCode'),
                        'AccountNumber' => $this->input->post('AccountNumber'),
                        'Basic' => $this->input->post('Basic'),
                        'HRA' => $this->input->post('HRA'),
                        'ConveyanceAllowance' => $this->input->post('ConveyanceAllowance'),
                        'StaffSpecialAllowance' => $this->input->post('StaffSpecialAllowance'),
                        'GrossMonthlyTotal' => $this->input->post('GrossMonthlyTotal'),
                        'EmployeePF' => $this->input->post('EmployeePF'),
                        'EmployeeESI' => $this->input->post('EmployeeESI'),
                        'ProfessionTax' => $this->input->post('ProfessionTax'),
                        'IncomeTax' => $this->input->post('IncomeTax'),
                        'TDS' => $this->input->post('TDS'),
                        'TDSAmount' => $this->input->post('TDSAmount'),
                        'MonthlyDeduction' => $this->input->post('MonthlyDeduction'),
                        'MonthlyNetTakeHome' => $this->input->post('MonthlyNetTakeHome'),
                        'EmployerPF' => $this->input->post('EmployerPF'),
                        'EmployerESI' => $this->input->post('EmployerESI'),
                        'AnnualComponents' => $this->input->post('AnnualComponents'),
                        'PerformanceBonus' => $this->input->post('PerformanceBonus'),
                        'Gratuity' => $this->input->post('Gratuity'),
                        'LeaveTravelAllowance' => $this->input->post('LeaveTravelAllowance'),
                        'CostToCompany' => $this->input->post('CostToCompany'),
                        'UserId' => $this->session->userdata('user_id'),
                        'CompId' => $this->session->userdata('comp_id'),
                        'CompanyBranchId' => $this->session->userdata('CompBranchId'),
                        'Created' => date('d-m-Y h:i:s A'),
                        'CreatedBy' => get_title('users', ['id' => $this->session->userdata('user_id')], 'name'),
                    ];
                    $Result = $this->user_model->saveAry('employees', $EmployeeData);
                                        
                    $EmployeePermission = [
                        "EmployeeId" => $Result,
                        "UserId" => $this->session->userdata('user_id'),
                        "CompId" => $this->session->userdata('comp_id'),
                        "CompanyBranchId" => $this->session->userdata('CompBranchId'),
                        "Created" => date('d-m-Y h:i:s A'),
                        "CreatedBy" => get_title('users', ['id' => $this->session->userdata('user_id')], 'name')
                    ];
                    $this->user_model->saveAry('employee_permission', $EmployeePermission);
                    
                    if (!empty($Result)) {
                        $this->session->set_flashdata(['msg' => 'Record save successfully.', 'class' => 'success']);
                        return redirect(base_url() . 'Employee');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to save record.', 'class' => 'danger']);
                        return redirect(base_url() . 'Employee');
                    }
                }
            }
        }
        
        public function Permission($Id)
        {
            if (!empty($Id)) {
                $Where = ['EmployeeId' => $Id, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id')];
                $EmpWhere = ['Id' => $Id, 'IsDeleted' => 1, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id')];
                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                    $EmpWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
              
                if ($this->db->select('EmployeeId')->where($Where)->get('employee_permission')->num_rows()) {
                    $Data['Employee'] = $this->db->select('EmployeeId, Name, RoleId')->where($EmpWhere)->get('employees')->row();
                    $Data['Permission'] = $this->db->where($Where)->get('employee_permission')->row();
                    $Data['page'] = 'Employee/Permission'; 
                    return $this->load->view('inventory_sep/inventory', $Data);
                } else {
                    $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                    return redirect(base_url() . 'Employee');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Employee id is missing.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee');
            }
        }
        
        public function PermissionUpdate()
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $Sales = [
                    'Create' => $this->input->post('SalesCreate'),
                    'View' => $this->input->post('SalesView'),
                    'Edit' => $this->input->post('SalesEdit'),
                    'Delete' => $this->input->post('SalesDelete'),
                    'EInvoiceCreate' => $this->input->post('EInvoiceCreate'),
                    'EWayCreate' => $this->input->post('EWayCreate'),
                ];
                $Purchases = [
                    'Create' => $this->input->post('PurchasesCreate'),
                    'View' => $this->input->post('PurchasesView'),
                    'Edit' => $this->input->post('PurchasesEdit'),
                    'Delete' => $this->input->post('PurchasesDelete'),
                ];
                $Estimates = [
                    'Create' => $this->input->post('EstimatesCreate'),
                    'View' => $this->input->post('EstimatesView'),
                    'Edit' => $this->input->post('EstimatesEdit'),
                    'Delete' => $this->input->post('EstimatesDelete'),
                ];
                $receipts = [
                    'Create' => $this->input->post('ReceiptsCreate'),
                    'View' => $this->input->post('ReceiptsView'),
                    'Edit' => $this->input->post('ReceiptsEdit'),
                    'Delete' => $this->input->post('ReceiptsDelete'),
                ];                
                $Payments = [
                    'Create' => $this->input->post('PaymentsCreate'),
                    'View' => $this->input->post('PaymentsView'),
                    'Edit' => $this->input->post('PaymentsEdit'),
                    'Delete' => $this->input->post('PaymentsDelete'),
                ];
                $PaymentsRequest = [
                    'Create' => $this->input->post('PaymentsRequestCreate'),
                    'View' => $this->input->post('PaymentsRequestView'),
                    'Edit' => $this->input->post('PaymentsRequestEdit'),
                    'Delete' => $this->input->post('PaymentsRequestDelete'),
                ];
                $Stocks = [
                    'View' => $this->input->post('StocksView')
                ];
                $Parties = [
                    'Create' => $this->input->post('PartiesCreate'),
                    'View' => $this->input->post('PartiesView'),
                    'Edit' => $this->input->post('PartiesEdit'),
                    'Delete' => $this->input->post('PartiesDelete'),
                ];
                $Products = [
                    'Create' => $this->input->post('ProductsCreate'),
                    'View' => $this->input->post('ProductsView'),
                    'Edit' => $this->input->post('ProductsEdit'),
                    'Delete' => $this->input->post('ProductsDelete'),
                ];
                $Orderbook = [
                    'Create' => $this->input->post('OrderbookCreate'),
                    'View' => $this->input->post('OrderbookView'),
                    'Edit' => $this->input->post('OrderbookEdit'),
                    'Delete' => $this->input->post('OrderbookDelete'),
                ];
                $Attendance = [
                    'Create' => $this->input->post('AttendanceCreate'),
                    'View' => $this->input->post('AttendanceView'),
                    'Edit' => $this->input->post('AttendanceEdit'),
                    'Delete' => $this->input->post('AttendanceDelete'),
                ];
                $EstimatePurchase = [
                    'Create' => $this->input->post('EstimatePurchaseCreate'),
                    'View' => $this->input->post('EstimatePurchaseView'),
                    'Edit' => $this->input->post('EstimatePurchaseEdit'),
                    'Delete' => $this->input->post('EstimatePurchaseDelete'),
                ];
                $PurchaseOrder = [
                    'Create' => $this->input->post('PurchaseOrderCreate'),
                    'View' => $this->input->post('PurchaseOrderView'),
                    'Edit' => $this->input->post('PurchaseOrderEdit'),
                    'Delete' => $this->input->post('PurchaseOrderDelete'),
                ];
                $SalesDeliveries = [
                    'Edit' => $this->input->post('SalesDeliveriesEdit')
                ];
                $Ledger = [
                    'Create' => $this->input->post('LedgerCreate'),
                    'View' => $this->input->post('LedgerView'),
                    'Edit' => $this->input->post('LedgerEdit'),
                    'Delete' => $this->input->post('LedgerDelete'),
                ];

                $EmployeePermissionData = [
                    'Sales' => json_encode($Sales),
                    'Purchases' => json_encode($Purchases),
                    'Estimates' => json_encode($Estimates),
                    'receipts' => json_encode($receipts),
                    'Payments' => json_encode($Payments),
                    'PaymentsRequest' => json_encode($PaymentsRequest),
                    'Stocks' => json_encode($Stocks),
                    'Parties' => json_encode($Parties),
                    'Products' => json_encode($Products),
                    'Orderbook' => json_encode($Orderbook),
                    'Attendance' => json_encode($Attendance),
                    'EstimatePurchase' => json_encode($EstimatePurchase),
                    'PurchaseOrder' => json_encode($PurchaseOrder),
                    'SalesDeliveries' => json_encode($SalesDeliveries),
                    'Ledger' => json_encode($Ledger),
                    'Updated' => date('d-m-Y h:i:s A'),
                    'UpdatedBy' => get_title('users', ['id' => $this->session->userdata('user_id')], 'name'),
                ];
                $EmployeeId = $this->input->post('EmployeePrimaryId');
                $Where = ['Id' => $this->input->post('PermissionId'), 'EmployeeId' => $EmployeeId, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id')];
                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                $Result = $this->user_model->updateAry('employee_permission', $EmployeePermissionData, $Where);
                
                if ($Result) {
                    $this->session->set_flashdata(['msg' => 'Record update successfully.', 'class' => 'success']);
                    return redirect(base_url() . 'Employee');
                } else {
                    $this->session->set_flashdata(['msg' => 'Unable to update record.', 'class' => 'danger']);
                    return redirect(base_url() . 'Employee/Permission/' . $EmployeeId);
                }
            }
        }
        
        public function Card($Id) 
        {
            if (!empty($Id)) {
                $CompBranchWhere = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                $Where = ['Id' => $Id, 'IsDeleted' => 1, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id')];
                if ($this->session->userdata('BranchRole')) {
                    $CompBranchWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                    $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                if ($this->db->select('Id')->where($Where)->get('employees')->num_rows()) {
                    $Data['CompBranchInfo'] = $this->user_model->getRow('comp_profile', $CompBranchWhere);
                    $Data['Employee'] = $this->user_model->getRow('employees', $Where);
                    return $this->load->view('inventory_sep/Employee/Card', $Data);
                } else {
                    $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                    return redirect(base_url() . 'Employee');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Employee id missing.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee');
            }
        }
        
        public function Info($Id) 
        {
            if (!empty($Id)) {
                $Where = ['Id' => $Id, 'IsDeleted' => 1, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id')];
                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                if ($this->db->select('Id')->where($Where)->get('employees')->num_rows()) {
                    $Data['Employee'] = $this->user_model->getRow('employees', $Where);
                    $Data['page'] = 'Employee/Info';
                    return $this->load->view('inventory_sep/inventory', $Data);
                } else {
                    $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                    return redirect(base_url() . 'Employee');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Employee id missing.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee');
            }
        }
        
        public function Edit($Id)
        {
            if ($Id) {
                $Where = ['UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id')];
                $WarehouseWhere = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                    $WarehouseWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                if ($this->db->select('Id')->where('Id', $Id)->where($Where)->get('employees')->num_rows()) {
                    $data['EmpId'] = $Id;
                    $data['Employee'] = $this->db->where('Id', $Id)->where($Where)->get('employees')->row();
                    $data['Positions'] = $this->db->select('Id, PostName')->where(['IsDeleted' => 1, 'Status' => 1])->order_by('Id', 'DESC')->get('position')->result();
                    $data['Warehouses'] = $this->user_model->getResult('warehouse', $WarehouseWhere);
                    $data['page'] = "Employee/Edit";
                    return $this->load->view('inventory_sep/inventory', $data);
                } else {
                    $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                    return redirect(base_url() . 'Employee');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Employee id missing.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee');
            }   
        }
        
        public function IsUniqueMobile($Mobile, $Id) 
        {
            $Where = ['IsDeleted' => 1, 'Mobile' => $Mobile, 'Id != ' => $Id, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id')];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            $QueryResult = $this->db->select('Id, Mobile')->where($Where)->get('employees')->num_rows();
            if ($QueryResult === 0) {
                return true;
            } else {
                $this->form_validation->set_message('IsUniqueMobile', 'The mobile must be unique.');
                return false;
            }
        }
        
        public function IsUniqueEmail($Email, $Id) 
        {
            $Where = ['IsDeleted' => 1, 'Email' => $Email, 'Id != ' => $Id, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id')];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            $QueryResult = $this->db->select('Id, Email')->where($Where)->get('employees')->num_rows();
            if ($QueryResult === 0) {
                return true;
            } else {
                $this->form_validation->set_message('IsUniqueEmail', 'The Email must be unique.');
                return false;
            }
        }
        
        public function Update($Id)
        {
            if ($Id) {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $this->form_validation->set_rules('EmployeeId', 'Employee Id', 'trim|required');
                    $this->form_validation->set_rules('Name', 'Name', 'trim|required');
                    $this->form_validation->set_rules('Mobile', 'Mobile', 'trim|required|numeric|max_length[10]|min_length[10]|callback_IsUniqueMobile[' . $Id . ']');
                    $this->form_validation->set_rules('Email', 'Email', 'trim|required|valid_email|callback_IsUniqueEmail[' . $Id . ']');
                    $this->form_validation->set_rules('Gender', 'Gender', 'trim|required');
                    $this->form_validation->set_rules('DateOfBirth', 'Date of Birth', 'trim|required');
                    $this->form_validation->set_rules('BloodGroup', 'Blood Group', 'trim');
                    $this->form_validation->set_rules('EmergencyNumber', 'Emergency Number', 'trim|numeric|max_length[10]|min_length[10]');
                    $this->form_validation->set_rules('TemporaryAddress', 'Temporary Address', 'trim');
                    $this->form_validation->set_rules('PermanentAddress', 'Permanent Address', 'trim');
                    $this->form_validation->set_rules('FatherName', 'Father Name', 'trim');
                    $this->form_validation->set_rules('FatherMobile', 'Father Mobile', 'trim|numeric|max_length[10]|min_length[10]');
                    $this->form_validation->set_rules('MotherName', 'Mother Name', 'trim');
                    $this->form_validation->set_rules('MotherMobile', 'Mother Mobile', 'trim|numeric|max_length[10]|min_length[10]');
                    $this->form_validation->set_rules('RoleId', 'Employee Post', 'trim|required');
                    $this->form_validation->set_rules('WarehouseId', 'WarehouseId', 'trim|required');
                    $this->form_validation->set_rules('AadhaarNumber', 'Aadhaar Number', 'trim|numeric|max_length[12]|min_length[12]');
                    $this->form_validation->set_rules('PANNumber', 'PAN Number', 'trim|max_length[10]|min_length[10]');
                    $this->form_validation->set_rules('UANNumber', 'UAN Number', 'trim|max_length[12]|min_length[12]');
                    $this->form_validation->set_rules('ESINumber', 'ESI Number', 'trim|max_length[17]|min_length[17]');
                    $this->form_validation->set_rules('JoiningDate', 'Joining Date', 'trim');
                    $this->form_validation->set_rules('ExpiryDate', 'Expiry Date', 'trim');
                    $this->form_validation->set_rules('Password', 'Password', 'trim|required|max_length[20]|min_length[6]');
                    $this->form_validation->set_rules('IPAddress', 'IPAddress', 'trim|valid_ip');
                    $this->form_validation->set_rules('BankName', 'Bank Name', 'trim');
                    $this->form_validation->set_rules('Branch Name', 'Branch Name', 'trim');
                    $this->form_validation->set_rules('IFSC Code', 'IFSC Code', 'trim|max_length[11]|min_length[11]');
                    $this->form_validation->set_rules('Account Number', 'Account Number', 'trim|numeric|max_length[20]');
                    $this->form_validation->set_rules('Basic', 'Basic', 'trim|required|numeric|min_length[1]|max_length[10]');
                    $this->form_validation->set_rules('HRA', 'House Rent Allowance', 'trim|required|numeric|min_length[1]|max_length[10]');
                    $this->form_validation->set_rules('ConveyanceAllowance', 'Conveyance Allowance', 'trim|required|numeric|min_length[1]|max_length[10]');
                    $this->form_validation->set_rules('StaffSpecialAllowance', 'Staff Special Allowance', 'trim|numeric|required|min_length[1]|max_length[10]');
                    $this->form_validation->set_rules('GrossMonthlyTotal', 'Gross Monthly Total', 'trim|numeric|required|min_length[1]|max_length[10]');
                    $this->form_validation->set_rules('EmployeePF', 'Employee PF', 'trim|required|numeric|min_length[1]|max_length[10]');
                    $this->form_validation->set_rules('EmployeeESI', 'Employee ESI', 'trim|required|numeric|min_length[1]|max_length[10]');
                    $this->form_validation->set_rules('ProfessionTax', 'Profession Tax', 'trim|required|numeric|min_length[1]|max_length[10]');
                    $this->form_validation->set_rules('IncomeTax', 'Income Tax', 'trim|required|numeric|min_length[1]|max_length[10]');
                    $this->form_validation->set_rules('TDS', 'TDS', 'trim|required|numeric|min_length[1]|max_length[2]');
                    $this->form_validation->set_rules('TDSAmount', 'TDS Amount', 'trim|required|numeric|min_length[1]|max_length[10]');
                    $this->form_validation->set_rules('MonthlyDeduction', 'Monthly Deduction', 'trim|required|numeric|min_length[1]|max_length[10]');
                    $this->form_validation->set_rules('MonthlyNetTakeHome', 'Monthly Net Take Home', 'trim|required|numeric|min_length[1]|max_length[10]');
                    $this->form_validation->set_rules('EmployerPF', 'Employer PF', 'trim|required|numeric|min_length[1]|max_length[10]');
                    $this->form_validation->set_rules('EmployerESI', 'Employer ESI', 'trim|required|numeric|min_length[1]|max_length[10]');
                    $this->form_validation->set_rules('AnnualComponents', 'Annual Components', 'trim|required|numeric|min_length[1]|max_length[10]');
                    $this->form_validation->set_rules('PerformanceBonus', 'Performance Bonus', 'trim|required|numeric|min_length[1]|max_length[10]');
                    $this->form_validation->set_rules('Gratuity', 'Gratuity', 'trim|required|numeric|min_length[1]|max_length[10]');
                    $this->form_validation->set_rules('LeaveTravelAllowance', 'Leave Travel Allowance', 'trim|required|numeric|min_length[1]|max_length[10]');
                    $this->form_validation->set_rules('CostToCompany', 'CTC', 'trim|required|numeric|min_length[1]|max_length[10]');
                    if ($this->form_validation->run() == false) {
                        $data['EmpId'] = $Id;
                        $Where = ['UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id')];
                        $WarehouseWhere = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                        if ($this->session->userdata('BranchRole')) {
                            $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                            $WarehouseWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                        }
                        $data['Employee'] = $this->db->where('Id', $Id)->where($Where)->get('employees')->row();
                        $data['Positions'] = $this->db->select('Id, PostName')->where(['IsDeleted' => 1, 'Status' => 1])->order_by('Id', 'DESC')->get('position')->result();
                        $data['Warehouses'] = $this->user_model->getResult('warehouse', $WarehouseWhere);
                        $data['page'] = "Employee/Edit";
                        $data['error'] = validation_errors();
                        return $this->load->view('inventory_sep/inventory', $data);
                    } else {
                        if ($_FILES['Photograph']['name']) {
                            unlink('uploads/Employee/Photograph/' . $this->input->post('OldPhotograph'));
                            $PhotographFileName = time() . $_FILES['Photograph']['name'];
                            move_uploaded_file($_FILES['Photograph']['tmp_name'], 'uploads/Employee/Photograph/' . $PhotographFileName);
                            $Photograph = $PhotographFileName;
                        } else {
                            $Photograph = $this->input->post('OldPhotograph');
                        }

                        if ($_FILES['IdentityProof']['name']) {
                            unlink('uploads/Employee/IdentityProof/' . $this->input->post('OldIdentityProof'));
                            $IdentityProofFileName = time() . $_FILES['IdentityProof']['name'];
                            move_uploaded_file($_FILES['IdentityProof']['tmp_name'], 'uploads/Employee/IdentityProof/' . $IdentityProofFileName);
                            $IdentityProof = $IdentityProofFileName;
                        } else {
                            $IdentityProof = $this->input->post('OldIdentityProof');
                        }

                        if ($_FILES['AddressProof']['name']) {
                            unlink('uploads/Employee/AddressProof/' . $this->input->post('OldAddressProof'));
                            $AddressProofFileName = time() . $_FILES['AddressProof']['name'];
                            move_uploaded_file($_FILES['AddressProof']['tmp_name'], 'uploads/Employee/AddressProof/' . $AddressProofFileName);
                            $AddressProof = $AddressProofFileName;
                        } else {
                            $AddressProof = $this->input->post('OldAddressProof');
                        }

                        if ($_FILES['BankPassbook']['name']) {
                            unlink('uploads/Employee/BankPassbook/' . $this->input->post('OldBankPassbook'));
                            $BankPassbookFileName = time() . $_FILES['IdentityProof']['name'];
                            move_uploaded_file($_FILES['BankPassbook']['tmp_name'], 'uploads/Employee/BankPassbook/' . $BankPassbookFileName);
                            $BankPassbook = $BankPassbookFileName;
                        } else {
                            $BankPassbook = $this->input->post('OldBankPassbook');
                        }
                        
                        $EmpId = $this->input->post('EmpId');
                        $EmployeeData = [
                            'Name' => $this->input->post('Name'),
                            'Mobile' => $this->input->post('Mobile'),
                            'Email' => $this->input->post('Email'),
                            'Gender' => $this->input->post('Gender'),
                            'DateOfBirth' => $this->input->post('DateOfBirth'),
                            'BloodGroup' => $this->input->post('BloodGroup'),
                            'EmergencyNumber' => $this->input->post('EmergencyNumber'),
                            'TemporaryAddress' => $this->input->post('TemporaryAddress'),
                            'PermanentAddress' => $this->input->post('PermanentAddress'),
                            'FatherName' => $this->input->post('FatherName'),
                            'FatherMobile' => $this->input->post('FatherMobile'),
                            'MotherName' => $this->input->post('MotherName'),
                            'MotherMobile' => $this->input->post('MotherMobile'),
                            'RoleId' => $this->input->post('RoleId'),
                            'WarehouseId' => $this->input->post('WarehouseId'),
                            'WarehouseSalesman' => json_encode($this->input->post('WarehouseSalesman')),
                            'AadhaarNumber' => $this->input->post('AadhaarNumber'),
                            'PANNumber' => $this->input->post('PANNumber'),
                            'UANNumber' => $this->input->post('UANNumber'),
                            'ESINumber' => $this->input->post('ESINumber'),
                            'JoiningDate' => $this->input->post('JoiningDate'),
                            'ExpiryDate' => $this->input->post('ExpiryDate'),
                            'Password' => $this->input->post('Password'),
                            'IPAddress' => $this->input->post('IPAddress'),
                            'SalesTarget' => $this->input->post('SalesTarget'),
                            'Photograph' => $Photograph,
                            'IdentityProof' => $IdentityProof,
                            'AddressProof' => $AddressProof,
                            'BankPassbook' => $BankPassbook,
                            'BankName' => $this->input->post('BankName'),
                            'BranchName' => $this->input->post('BranchName'),
                            'IFSCCode' => $this->input->post('IFSCCode'),
                            'AccountNumber' => $this->input->post('AccountNumber'),
                            'Basic' => $this->input->post('Basic'),
                            'HRA' => $this->input->post('HRA'),
                            'ConveyanceAllowance' => $this->input->post('ConveyanceAllowance'),
                            'StaffSpecialAllowance' => $this->input->post('StaffSpecialAllowance'),
                            'GrossMonthlyTotal' => $this->input->post('GrossMonthlyTotal'),
                            'EmployeePF' => $this->input->post('EmployeePF'),
                            'EmployeeESI' => $this->input->post('EmployeeESI'),
                            'ProfessionTax' => $this->input->post('ProfessionTax'),
                            'IncomeTax' => $this->input->post('IncomeTax'),
                            'TDS' => $this->input->post('TDS'),
                            'TDSAmount' => $this->input->post('TDSAmount'),
                            'MonthlyDeduction' => $this->input->post('MonthlyDeduction'),
                            'MonthlyNetTakeHome' => $this->input->post('MonthlyNetTakeHome'),
                            'EmployerPF' => $this->input->post('EmployerPF'),
                            'EmployerESI' => $this->input->post('EmployerESI'),
                            'AnnualComponents' => $this->input->post('AnnualComponents'),
                            'PerformanceBonus' => $this->input->post('PerformanceBonus'),
                            'Gratuity' => $this->input->post('Gratuity'),
                            'LeaveTravelAllowance' => $this->input->post('LeaveTravelAllowance'),
                            'CostToCompany' => $this->input->post('CostToCompany'),
                            'Updated' => date('d-m-Y h:i:s A'),
                            'UpdatedBy' => get_title('users', ['id' => $this->session->userdata('user_id')], 'name'),
                        ];
                        
                        $Where = ['Id' => $EmpId, 'IsDeleted' => 1, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id')];
                        if ($this->session->userdata('BranchRole')) {
                            $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                        }
                        $Result = $this->user_model->updateAry('employees', $EmployeeData, $Where);
                        
                        if (!empty($Result)) {
                            $this->session->set_flashdata(['msg' => 'Record update successfully.', 'class' => 'success']);
                            return redirect(base_url() . 'Employee');
                        } else {
                            $this->session->set_flashdata(['msg' => 'Unable to update record.', 'class' => 'danger']);
                            return redirect(base_url() . 'Employee/Edit/' . $Id);
                        }
                    }
                }
            } else {
                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee');
            }   
        }
        
        public function IsActive($Id)
        {
            if ($Id) {
                $Where = ['Id' => $Id, 'IsDeleted' => 1, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id')];
                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                $Employee = $this->db->select('IsActive')->where($Where)->get('employees')->row();
                if ($Employee) {
                    if ($Employee->IsActive == 1) {
                        $IsActive = 0;
                    } else {
                        $IsActive = 1;
                    }
                    $Result = $this->user_model->updateAry('employees', ['IsActive' => $IsActive], $Where);
                    if (!empty($Result)) {
                        $this->session->set_flashdata(['msg' => 'Status update successfully.', 'class' => 'success']);
                        return redirect(base_url() . 'Employee');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to update record.', 'class' => 'danger']);
                        return redirect(base_url() . 'Employee');
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                    return redirect(base_url() . 'Employee');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Somethings went wrong, please try again.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee');
            }
        }
        
        public function Status($Id)
        {
            if ($Id) {
                $Where = ['Id' => $Id, 'IsDeleted' => 1, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id')];
                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                $Employee = $this->db->select('Status')->where($Where)->get('employees')->row();
                if ($Employee) {
                    if ($Employee->Status == 1) {
                        $Status = 0;
                    } else {
                        $Status = 1;
                    }
                    $Result = $this->user_model->updateAry('employees', ['Status' => $Status], $Where);
                    if (!empty($Result)) {
                        $this->session->set_flashdata(['msg' => 'Status update successfully.', 'class' => 'success']);
                        return redirect(base_url() . 'Employee');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to update record.', 'class' => 'danger']);
                        return redirect(base_url() . 'Employee');
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'Somethings went wrong, please try again.', 'class' => 'danger']);
                    return redirect(base_url() . 'Employee');                    
                }
            } else {
                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee');
            }
        }
        
        public function Delete($Id)
        {
            if ($Id) {
                $Where = ['Id' => $Id, 'IsDeleted' => 1, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id')];
                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                if ($this->db->select('Id')->where($Where)->get('employees')->num_rows()) {
                    if ($this->user_model->updateAry('employees', ['IsDeleted' => 0], $Where)) {
                        $this->session->set_flashdata(['msg' => 'Record delete successfully.', 'class' => 'success']);
                        return redirect(base_url() . 'Employee');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to delete record.', 'class' => 'danger']);
                        return redirect(base_url() . 'Employee');
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                    return redirect(base_url() . 'Employee');
                }
            }
        }
        
        public function Configuration() 
        {
            $Where = ['UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id')];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            $data['EmployeeConfig'] = $this->db->where($Where)->get('employee_config')->row();
            $data['page'] = "Employee/Configuration";
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function ConfigurationUpdate() 
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $ConfigurationData = [
                    "Prefix" => $this->input->post('Prefix'),
                    "Starting" => $this->input->post('Starting'),
                    "TermsAndConditions" => $this->input->post('TermsAndConditions'),
                    "LoginIPValidation" => $this->input->post('LoginIPValidation'),
                    "Updated" => date('d-m-Y h:i:s A'),
                    "UpdatedBy" => get_title('users', ['id' => $this->session->userdata('user_id')], 'name')
                ];
                
                $Where = ['Id' => $this->input->post('EmployeeConfigId'), 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id')];
                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                $Result = $this->user_model->updateAry('employee_config', $ConfigurationData, $Where);
                
                if ($Result) {
                    $this->session->set_flashdata(['msg' => 'Record update successfully.', 'class' => 'success']);
                    return redirect(base_url() . 'Employee/Configuration');
                } else {
                    $this->session->set_flashdata(['msg' => 'Unable to update record.', 'class' => 'danger']);
                    return redirect(base_url() . 'Employee/Configuration');
                }
            }
        }
        
        public function Attendance() 
        {
            $Where = ['UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id'), 'Status' => 1, 'IsDeleted' => 1];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            $data['Id'] = "";
            $data['Employees'] = $this->db->select('Id, Name, RoleId')->where($Where)->order_by('Id', 'DESC')->get('employees')->result();
            $data['page'] = "Employee/Attendance";
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function TakeAttendance() 
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $CompProfileWhere = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                $Where = ['UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id')];
                if ($this->session->userdata('BranchRole')) {
                    $CompProfileWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                    $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                $FinancialYear = get_title('comp_profile', $CompProfileWhere, 'financial_year');
            
                if (count($this->input->post('EmpId')) > 0) {
                    foreach ($this->input->post('EmpId') as $key => $data) {
                        $Where['EmpId'] = $this->input->post('EmpId')[$key];
                        $Where['FinancialYear'] = $FinancialYear;
                        $Where['Date'] = $this->input->post('Date')[$key];
                        $Where['Month'] = date('F');
                        if ($this->db->where($Where)->get('employee_attendance')->num_rows()) {
                            $this->session->set_flashdata(['msg' => 'Attendance already marked.', 'class' => 'danger']);
                            return redirect(base_url() . 'Employee/Attendance');                            
                        }
                        
                        $AttendanceData = [
                            'UserId' => $this->session->userdata('user_id'),
                            'CompId' => $this->session->userdata('comp_id'),
                            'CompanyBranchId' => $this->session->userdata('CompBranchId'),
                            'EmpId' => $this->input->post('EmpId')[$key],
                            'Date' => $this->input->post('Date')[$key],
                            'InTime' => $this->input->post('InTime')[$key],
                            'OutTime' => $this->input->post('OutTime')[$key],
                            'ShiftType' => $this->input->post('ShiftType')[$key],
                            'Attendance' => $this->input->post('Attendance')[$key],
                            'Month' => date('F'),
                            'FinancialYear' => $FinancialYear,
                            'Created' => date('d-m-Y h:i:s A'),
                            'CreatedBy' => $this->session->userdata('user_id')
                        ];
                        $Result = $this->user_model->saveAry('employee_attendance', $AttendanceData);
                    }
                    
                    if (!empty($Result)) {
                        $this->session->set_flashdata(['msg' => 'Record saved successfully.', 'class' => 'success']);
                        return redirect(base_url() . 'Employee/AttendanceReport');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to save record.', 'class' => 'danger']);
                        return redirect(base_url() . 'Employee/Attendance');
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'Please select employee to mark the record.', 'class' => 'danger']);
                    return redirect(base_url() . 'Employee/Attendance');
                }
            }
        }
        
        public function AttendanceReport() 
        {
            if (!empty($this->input->get('From')) && !empty($this->input->get('To'))) {
                $From = $this->input->get('From');
                $To = $this->input->get('To');
            } else {
                $From = date('Y-m-d');
                $To = date('Y-m-d');
            }
            
            $EmployeeWhere = ['UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id'), 'IsDeleted' => 1];
            $Where = ['Date >= ' => $From, 'Date <= ' => $To, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id'), 'FinancialYear' => $this->session->userdata('financial_year')];
            
            if (!empty($this->input->get('EmpId')) && $this->input->get('EmpId') != 'All') {
                $Where['EmpId'] = $this->input->get('EmpId');
            }
            
            if ($this->session->userdata('BranchRole')) {
                $EmployeeWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            $data['Employees'] = $this->db->select('Id, Name, RoleId')->where($EmployeeWhere)->order_by('Id', 'DESC')->get('employees')->result();
            $data['Attendances'] = $this->db->select('*')->where($Where)->order_by('Id', 'DESC')->get('employee_attendance')->result();
            $data['page'] = "Employee/AttendanceReport";
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function AttendanceEdit() 
        {
            if (!empty($this->input->get('From')) && !empty($this->input->get('To'))) {
                $Where = ['Date >= ' => $this->input->get('From'), 'Date <= ' => $this->input->get('To'), 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id'), 'FinancialYear' => $this->session->userdata('financial_year')];
                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                $data['EmployeeAttendance'] = $this->db->select('*')->where($Where)->order_by('Id', 'DESC')->get('employee_attendance')->result();
                $data['page'] = "Employee/AttendanceEdit";
                return $this->load->view('inventory_sep/inventory', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/AttendanceReport');
            }
        }
        
        public function UpdateAttendance() 
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $From = $this->input->post('From');
                $To = $this->input->post('To');

                if (count($this->input->post('AttendanceId')) > 0) {
                    foreach ($this->input->post('EmpAttendanceId') as $key => $AttendanceId) {
                        if (in_array($AttendanceId, $this->input->post('AttendanceId'))) {
                            $AttendanceData = [
                                'Date' => $this->input->post('Date')[$key],
                                'InTime' => $this->input->post('InTime')[$key],
                                'OutTime' => $this->input->post('OutTime')[$key],
                                'ShiftType' => $this->input->post('ShiftType')[$key],
                                'Attendance' => $this->input->post('Attendance')[$key],
                                'Updated' => date('d-m-Y h:i:s A'),
                                'UpdatedBy' => $this->session->userdata('user_id')
                            ];
                        
                            $Where = ['Id' => $AttendanceId, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id'), 'FinancialYear' => $this->session->userdata('financial_year')];
                            if ($this->session->userdata('BranchRole')) {
                                $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                            }
                            $Result = $this->user_model->updateAry('employee_attendance', $AttendanceData, $Where);
                        }
                    }
                    if (!empty($Result)) {
                        $this->session->set_flashdata(['msg' => 'Record updated successfully.', 'class' => 'success']);
                        return redirect(base_url() . 'Employee/AttendanceReport');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to save record.', 'class' => 'danger']);
                        return redirect(base_url() . 'Employee/AttendanceEdit?From=' . $From . '&To=' . $To);
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'Please select employee to change the record.', 'class' => 'danger']);
                    return redirect(base_url() . 'Employee/AttendanceEdit?From=' . $From . '&To=' . $To);
                }
            }
        }
        
        public function Salary() 
        {
            $Where = ['UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id'), 'Status' => 1, 'IsDeleted' => 1];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            $data['Employees'] = $this->db->select('Id, Name, RoleId')->where($Where)->order_by('Id', 'DESC')->get('employees')->result();
            $data['page'] = "Employee/Salary";
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function GetEmployeeAttendance()
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $EmployeeId = $this->input->post('EmployeeId');
                $Month = $this->input->post('Month');
                if (!empty($EmployeeId) && !empty($Month)) {
                    $Where = ['EmpId' => $EmployeeId, 'Month' => $Month, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id'), 'FinancialYear' => $this->session->userdata('financial_year')];
                    if ($this->session->userdata('BranchRole')) {
                        $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                    }
                    $PresentDays = $this->db->select('Attendance')->where($Where)->where('Attendance', 'Present')->get('employee_attendance')->num_rows();
                    $HalfDays = $this->db->select('Attendance')->where($Where)->where('Attendance', 'Half Day')->get('employee_attendance')->num_rows();
                    $AbsentDays = $this->db->select('Attendance')->where($Where)->where_in('Attendance', array('Absent', 'Leave Without Pay'))->get('employee_attendance')->num_rows();
                    $HoliDays = $this->db->select('Attendance')->where($Where)->where('Attendance', 'Holiday')->get('employee_attendance')->num_rows();
                    $LeaveDays = $this->db->select('Attendance')->where($Where)->where_in('Attendance', array('EL', 'CL', 'Leave With Pay'))->get('employee_attendance')->num_rows();
                    $DaysData = array(
                        "PresentDays" => $PresentDays + ($HalfDays / 2),
                        "LeaveDays" => $LeaveDays,
                        "HoliDays" => $HoliDays,
                        "AbsentDays" => $AbsentDays,
                        "TotalDays" => (($PresentDays + ($HalfDays / 2)) + $LeaveDays + $HoliDays)
                    );
                    if ($DaysData) {
                        echo json_encode(array('Status' => true, 'DaysData' => $DaysData));
                    } else {
                        echo json_encode(array('Status' => false, 'DaysData' => ""));
                    }
                } else {
                    echo json_encode(array('Status' => false, 'DaysData' => ""));
                }
            }
        }
        
        public function GetEmployeeSalary()
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $EmployeeId = $this->input->post('EmployeeId');
                if (!empty($EmployeeId)) {
                    $Where = ['Id' => $EmployeeId, 'Status' => 1, 'IsDeleted' => 1, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id')];
                    if ($this->session->userdata('BranchRole')) {
                        $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                    }
                    $EmployeeSalary = $this->db->select('Basic, HRA, ConveyanceAllowance, StaffSpecialAllowance, GrossMonthlyTotal, EmployeePF, EmployeeESI, ProfessionTax, IncomeTax, TDSAmount, MonthlyDeduction, MonthlyNetTakeHome, EmployerPF, EmployerESI, AnnualComponents, PerformanceBonus, Gratuity, LeaveTravelAllowance, CostToCompany')
                            ->where($Where)
                            ->get('employees')
                            ->row();
                    if ($EmployeeSalary) {
                        echo json_encode(array('Status' => true, 'EmployeeSalaryData' => $EmployeeSalary));
                    } else {
                        echo json_encode(array('Status' => false, 'EmployeeSalaryData' => ""));
                    }
                } else {
                    echo json_encode(array('Status' => false, 'EmployeeSalaryData' => ""));
                }
            }
        }
        
        public function SaveSalary() 
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $this->form_validation->set_rules('Month', 'Month', 'trim|required');
                $this->form_validation->set_rules('WorkingDays', 'Working Days', 'trim|required|numeric');
                $this->form_validation->set_rules('PresentDays', 'Present Days', 'trim|required|numeric');
                $this->form_validation->set_rules('LeaveDays', 'Leave Days', 'trim|required|numeric');
                $this->form_validation->set_rules('Holidays', 'Holidays', 'trim|required|numeric');
                $this->form_validation->set_rules('AbsentDays', 'Absent Days', 'trim|required|numeric');
                $this->form_validation->set_rules('TotalDays', 'Total Days', 'trim|required|numeric');
                $this->form_validation->set_rules('OverTimeHours', 'Over Time Hours', 'trim|numeric');
                $this->form_validation->set_rules('Basic', 'Basic', 'trim|required|numeric|min_length[1]|max_length[10]');
                $this->form_validation->set_rules('HRA', 'House Rent Allowance', 'trim|required|numeric|min_length[1]|max_length[10]');
                $this->form_validation->set_rules('ConveyanceAllowance', 'Conveyance Allowance', 'trim|required|numeric|min_length[1]|max_length[10]');
                $this->form_validation->set_rules('StaffSpecialAllowance', 'Staff Special Allowance', 'trim|numeric|required|min_length[1]|max_length[10]');
                $this->form_validation->set_rules('GrossMonthlyTotal', 'Gross Monthly Total', 'trim|numeric|required|min_length[1]|max_length[10]');
                $this->form_validation->set_rules('EmployeePF', 'Employee PF', 'trim|required|numeric|min_length[1]|max_length[10]');
                $this->form_validation->set_rules('EmployeeESI', 'Employee ESI', 'trim|required|numeric|min_length[1]|max_length[10]');
                $this->form_validation->set_rules('ProfessionTax', 'Profession Tax', 'trim|required|numeric|min_length[1]|max_length[10]');
                $this->form_validation->set_rules('IncomeTax', 'Income Tax', 'trim|required|numeric|min_length[1]|max_length[10]');
                $this->form_validation->set_rules('TDSAmount', 'TDS Amount', 'trim|required|numeric|min_length[1]|max_length[10]');
                $this->form_validation->set_rules('MonthlyDeduction', 'Monthly Deduction', 'trim|required|numeric|min_length[1]|max_length[10]');
                $this->form_validation->set_rules('MonthlyNetTakeHome', 'Monthly Net Take Home', 'trim|required|numeric|min_length[1]|max_length[10]');
                $this->form_validation->set_rules('EmployerPF', 'Employer PF', 'trim|required|numeric|min_length[1]|max_length[10]');
                $this->form_validation->set_rules('EmployerESI', 'Employer ESI', 'trim|required|numeric|min_length[1]|max_length[10]');
                $this->form_validation->set_rules('AnnualComponents', 'Annual Components', 'trim|required|numeric|min_length[1]|max_length[10]');
                $this->form_validation->set_rules('PerformanceBonus', 'Performance Bonus', 'trim|required|numeric|min_length[1]|max_length[10]');
                $this->form_validation->set_rules('Gratuity', 'Gratuity', 'trim|required|numeric|min_length[1]|max_length[10]');
                $this->form_validation->set_rules('LeaveTravelAllowance', 'Leave Travel Allowance', 'trim|required|numeric|min_length[1]|max_length[10]');
                $this->form_validation->set_rules('SecurityDeduction', 'Security Deduction', 'trim|numeric|min_length[1]|max_length[10]');
                $this->form_validation->set_rules('OverTimeAmount', 'Over Time Amount', 'trim|numeric|min_length[1]|max_length[10]');
                $this->form_validation->set_rules('NetTakeHomeSalary', 'Net Take Home Salary', 'trim|numeric|min_length[1]|max_length[10]');
                $this->form_validation->set_rules('EmpId', 'Employee Name', 'trim|required');
                if ($this->form_validation->run() == false) {
                    $Where = ['UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id'), 'Status' => 1, 'IsDeleted' => 1];
                    if ($this->session->userdata('BranchRole')) {
                        $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                    }
                    $data['Employees'] = $this->db->select('Id, Name, Post')->where($Where)->order_by('Id', 'DESC')->get('employees')->result();
                    $data['page'] = "Employee/Salary";
                    $data['error'] = validation_errors();
                    return $this->load->view('inventory_sep/inventory', $data);
                } else {
                    $CompProfileWhere = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                    if ($this->session->userdata('BranchRole')) {
                        $CompProfileWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                    }
                    $FinancialYear = get_title('comp_profile', $CompProfileWhere, 'financial_year');
                    
                    $EmployeeSalary = [
                        'Date' => date('Y-m-d'),
                        'Month' => $this->input->post('Month'),
                        'Year' => date('Y'),
                        'WorkingDays' => $this->input->post('WorkingDays'),
                        'PresentDays' => $this->input->post('PresentDays'),
                        'LeaveDays' => $this->input->post('LeaveDays'),
                        'Holidays' => $this->input->post('Holidays'),
                        'AbsentDays' => $this->input->post('AbsentDays'),
                        'TotalDays' => $this->input->post('TotalDays'),
                        'OverTimeHours' => $this->input->post('OverTimeHours'),
                        'Basic' => $this->input->post('Basic'),
                        'HRA' => $this->input->post('HRA'),
                        'ConveyanceAllowance' => $this->input->post('ConveyanceAllowance'),
                        'StaffSpecialAllowance' => $this->input->post('StaffSpecialAllowance'),
                        'GrossMonthlyTotal' => $this->input->post('GrossMonthlyTotal'),
                        'EmployeePF' => $this->input->post('EmployeePF'),
                        'EmployeeESI' => $this->input->post('EmployeeESI'),
                        'ProfessionTax' => $this->input->post('ProfessionTax'),
                        'IncomeTax' => $this->input->post('IncomeTax'),
                        'TDSAmount' => $this->input->post('TDSAmount'),
                        'MonthlyDeduction' => $this->input->post('MonthlyDeduction'),
                        'MonthlyNetTakeHome' => $this->input->post('MonthlyNetTakeHome'),
                        'EmployerPF' => $this->input->post('EmployerPF'),
                        'EmployerESI' => $this->input->post('EmployerESI'),
                        'AnnualComponents' => $this->input->post('AnnualComponents'),
                        'PerformanceBonus' => $this->input->post('PerformanceBonus'),
                        'Gratuity' => $this->input->post('Gratuity'),
                        'LeaveTravelAllowance' => $this->input->post('LeaveTravelAllowance'),
                        'SecurityDeduction' => $this->input->post('SecurityDeduction'),
                        'OverTimeAmount' => $this->input->post('OverTimeAmount'),
                        'NetTakeHomeSalary' => $this->input->post('NetTakeHomeSalary'),
                        'EmpId' => $this->input->post('EmpId'),
                        'UserId' => $this->session->userdata('user_id'),
                        'CompId' => $this->session->userdata('comp_id'),
                        'CompanyBranchId' => $this->session->userdata('CompBranchId'), 
                        'FinancialYear' => $FinancialYear,
                        'Created' => date('d-m-Y h:i:s A'),
                        'CreatedBy' => get_title('users', ['id' => $this->session->userdata('user_id')], 'name'),
                    ];
                    $Result = $this->user_model->saveAry('employee_salary', $EmployeeSalary);
                    
                    if (!empty($Result)) {
                        $this->SalarySlipMailSend($Result);
                        $this->session->set_flashdata(['msg' => 'Record save successfully.', 'class' => 'success']);
                        return redirect(base_url() . 'Employee/SalaryReport');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to save record.', 'class' => 'danger']);
                        return redirect(base_url() . 'Employee/SalaryReport');
                    }
                }
            }
        }
        
        public function SalarySlipMailSend($SalaryId) 
        {
            if (!empty($SalaryId)) {
                $CompBranchWhere = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                $Where = ['Id' => $SalaryId, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id'), 'FinancialYear' => $this->session->userdata('financial_year'), 'IsDeleted' => 1]; 
                if ($this->session->userdata('BranchRole')) {
                    $CompBranchWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                    $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                $Data['CompBranchInfo'] = $this->user_model->getRow('comp_profile', $CompBranchWhere);
                $Data['EmployeeInfo'] = $this->user_model->getRow('employees', ['Id' => get_title('employee_salary', ['Id' => $SalaryId], 'EmpId')]);
                $Data['SalaryInfo'] = $this->user_model->getRow('employee_salary', $Where);
                
                $CompBranchInfo = $this->db->select('name')->where($CompBranchWhere)->get('comp_profile')->row();
                $EmployeeId = get_title('employee_salary', ['Id' => $SalaryId], 'EmpId');
                $EmployeeInfo = $this->db->select('Email')->where('Id', $EmployeeId)->get('employees')->row();
                $SalaryInfo = $this->db->select('Month, Year')->where('Id', $SalaryId)->get('employee_salary')->row();
                
                $this->load->library('html2pdf');
                $this->html2pdf->folder('./uploads/PaySlip/');
                $this->html2pdf->filename("PaySlip_" . $SalaryInfo->Month . "_" . $SalaryInfo->Year . '.pdf');
                $this->html2pdf->paper('a4', 'portrait');
                $this->html2pdf->html(preg_replace('/>\s+</', '><', $this->load->view('inventory_sep/Employee/PaySlip', $Data, true)));
                if ($Path = $this->html2pdf->create('save')) {
                    $Message = "Greetings from " . $CompBranchInfo->name . "!!!"
                        . "<br />"
                        . "This is the payslip of month " . $SalaryInfo->Month . " - " . $SalaryInfo->Year
                        . "<br />"
                        . "Kindly find the attachment."
                        . "<p style='text-align: justify;'>"
                        . "Thanks & Regards"
                        . "<br />"
                        . $CompBranchInfo->name
                        . "</p>";
                    
                    $config['smtp_host'] = 'smtp.gmail.com';
                    $config['smtp_port'] = '465';
                    $config['smtp_user'] = 'info@udayplastic.co.in';
                    $config['_smtp_auth'] = TRUE;
                    $config['smtp_pass'] = 'kslxokprunrxipsx';
                    $config['smtp_crypto'] = 'ssl';
                    $config['protocol'] = 'mail';
                    $config['mailtype'] = 'html';
                    $config['priority'] = 1;
                    $config['charset'] = 'utf-8';
                    $config['wordwrap'] = TRUE;
                        
                    $this->email->initialize($config);                        
                    $this->email->set_newline("\r\n");
                    $this->email->from('info@udayplastic.co.in', 'Uday Plastic.');
                    $this->email->to($EmployeeInfo->Email);
                    $this->email->subject('PaySlip month of ' . $SalaryInfo->Month . ' - ' . $SalaryInfo->Year);
                    $this->email->message($Message);
                    $this->email->attach($Path);
                    $this->email->send();
                    unlink($Path);
                }
            }
        }
        
        public function SalaryReport()
        {
            if (!empty($this->input->get('From')) && !empty($this->input->get('To'))) {
                $From = $this->input->get('From');
                $To = $this->input->get('To');
            } else {
                $From = date('Y-m-d');
                $To = date('Y-m-d');
            }
            
            if ($this->input->get('Status') == "All" || $this->input->get('Status') == "") {
                $Where = ['Date >= ' => $From, 'Date <= ' => $To, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id'), 'FinancialYear' => $this->session->userdata('financial_year'), 'IsDeleted' => 1];
            } else {
                $Status = $this->input->get('Status');
                $Where = ['Date >= ' => $From, 'Date <= ' => $To, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id'), 'FinancialYear' => $this->session->userdata('financial_year'), 'Status' => $Status, 'IsDeleted' => 1];
            }
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            $data['EmployeeSalary'] = $this->db->select('Id, Date, EmpId, Month, TotalDays, NetTakeHomeSalary, Status')->where($Where)->order_by('Id', 'DESC')->get('employee_salary')->result();
            $data['page'] = "Employee/SalaryReport";
            return $this->load->view('inventory_sep/inventory', $data);
        }
        
        public function SalaryInfo($Id) 
        {
            if (!empty($Id)) {
                $Where = ['Id' => $Id, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id'), 'FinancialYear' => $this->session->userdata('financial_year'), 'IsDeleted' => 1]; 
                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                $Data['EmployeeSalary'] = $this->user_model->getRow('employee_salary', $Where);
                $Data['page'] = 'Employee/SalaryInfo';
                return $this->load->view('inventory_sep/inventory', $Data);
            } else {
                $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/SalaryReport');
            }
        }
        
        public function PaySlip($Id) 
        {
            if (!empty($Id)) {
                $CompBranchWhere = ['user_id' => $this->session->userdata('user_id'), 'comp_id' => $this->session->userdata('comp_id')];
                $Where = ['Id' => $Id, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id'), 'FinancialYear' => $this->session->userdata('financial_year'), 'IsDeleted' => 1]; 
                if ($this->session->userdata('BranchRole')) {
                    $CompBranchWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                    $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                $Data['CompBranchInfo'] = $this->user_model->getRow('comp_profile', $CompBranchWhere);
                $Data['EmployeeInfo'] = $this->user_model->getRow('employees', ['Id' => get_title('employee_salary', ['Id' => $Id], 'EmpId')]);
                $Data['SalaryInfo'] = $this->user_model->getRow('employee_salary', $Where);
                return $this->load->view('inventory_sep/Employee/PaySlip', $Data);
            } else {
                $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/SalaryReport');
            }
        }
        
        public function SalaryEdit($Id)
        {
            if ($Id) {
                $data['SalaryId'] = $Id;
                $SalaryWhere = ['Id' => $Id, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id'), 'FinancialYear' => $this->session->userdata('financial_year'), 'IsDeleted' => 1];
                $Where = ['UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id'), 'Status' => 1, 'IsDeleted' => 1];
                if ($this->session->userdata('BranchRole')) {
                    $SalaryWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                    $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                $data['EmployeeSalary'] = $this->user_model->getRow('employee_salary', $SalaryWhere);
                $data['Employees'] = $this->db->select('Id, Name, RoleId')->where($Where)->order_by('Id', 'DESC')->get('employees')->result();
                $data['page'] = "Employee/SalaryEdit";
                return $this->load->view('inventory_sep/inventory', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/SalaryReport');
            }   
        }
        
        public function UpdateSalary($Id)
        {
            if ($Id) {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $this->form_validation->set_rules('Month', 'Month', 'trim|required');
                    $this->form_validation->set_rules('WorkingDays', 'Working Days', 'trim|required|numeric');
                    $this->form_validation->set_rules('PresentDays', 'Present Days', 'trim|required|numeric');
                    $this->form_validation->set_rules('LeaveDays', 'Leave Days', 'trim|required|numeric');
                    $this->form_validation->set_rules('Holidays', 'Holidays', 'trim|required|numeric');
                    $this->form_validation->set_rules('AbsentDays', 'Absent Days', 'trim|required|numeric');
                    $this->form_validation->set_rules('TotalDays', 'Total Days', 'trim|required|numeric');
                    $this->form_validation->set_rules('OverTimeHours', 'Over Time Hours', 'trim|numeric');
                    $this->form_validation->set_rules('Basic', 'Basic', 'trim|required|numeric|min_length[1]|max_length[10]');
                    $this->form_validation->set_rules('HRA', 'House Rent Allowance', 'trim|required|numeric|min_length[1]|max_length[10]');
                    $this->form_validation->set_rules('ConveyanceAllowance', 'Conveyance Allowance', 'trim|required|numeric|min_length[1]|max_length[10]');
                    $this->form_validation->set_rules('StaffSpecialAllowance', 'Staff Special Allowance', 'trim|numeric|required|min_length[1]|max_length[10]');
                    $this->form_validation->set_rules('GrossMonthlyTotal', 'Gross Monthly Total', 'trim|numeric|required|min_length[1]|max_length[10]');
                    $this->form_validation->set_rules('EmployeePF', 'Employee PF', 'trim|required|numeric|min_length[1]|max_length[10]');
                    $this->form_validation->set_rules('EmployeeESI', 'Employee ESI', 'trim|required|numeric|min_length[1]|max_length[10]');
                    $this->form_validation->set_rules('ProfessionTax', 'Profession Tax', 'trim|required|numeric|min_length[1]|max_length[10]');
                    $this->form_validation->set_rules('IncomeTax', 'Income Tax', 'trim|required|numeric|min_length[1]|max_length[10]');
                    $this->form_validation->set_rules('TDSAmount', 'TDS Amount', 'trim|required|numeric|min_length[1]|max_length[10]');
                    $this->form_validation->set_rules('MonthlyDeduction', 'Monthly Deduction', 'trim|required|numeric|min_length[1]|max_length[10]');
                    $this->form_validation->set_rules('MonthlyNetTakeHome', 'Monthly Net Take Home', 'trim|required|numeric|min_length[1]|max_length[10]');
                    $this->form_validation->set_rules('EmployerPF', 'Employer PF', 'trim|required|numeric|min_length[1]|max_length[10]');
                    $this->form_validation->set_rules('EmployerESI', 'Employer ESI', 'trim|required|numeric|min_length[1]|max_length[10]');
                    $this->form_validation->set_rules('AnnualComponents', 'Annual Components', 'trim|required|numeric|min_length[1]|max_length[10]');
                    $this->form_validation->set_rules('PerformanceBonus', 'Performance Bonus', 'trim|required|numeric|min_length[1]|max_length[10]');
                    $this->form_validation->set_rules('Gratuity', 'Gratuity', 'trim|required|numeric|min_length[1]|max_length[10]');
                    $this->form_validation->set_rules('LeaveTravelAllowance', 'Leave Travel Allowance', 'trim|required|numeric|min_length[1]|max_length[10]');
                    $this->form_validation->set_rules('SecurityDeduction', 'Security Deduction', 'trim|numeric');
                    $this->form_validation->set_rules('OverTimeAmount', 'Over Time Amount', 'trim|numeric');
                    $this->form_validation->set_rules('NetTakeHomeSalary', 'Net Take Home Salary', 'trim|numeric|required');
                    $this->form_validation->set_rules('EmpId', 'Employee Name', 'trim|required');
                    if ($this->form_validation->run() == false) {
                        $data['SalaryId'] = $Id;
                        $SalaryWhere = ['Id' => $Id, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id'), 'FinancialYear' => $this->session->userdata('financial_year'), 'IsDeleted' => 1];
                        $Where = ['UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id'), 'Status' => 1, 'IsDeleted' => 1];
                        if ($this->session->userdata('BranchRole')) {
                            $SalaryWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                            $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                        }
                        $data['EmployeeSalary'] = $this->user_model->getRow('employee_salary', $SalaryWhere);
                        $data['Employees'] = $this->db->select('Id, Name, RoleId')->where($Where)->order_by('Id', 'DESC')->get('employees')->result();
                        $data['page'] = "Employee/SalaryEdit";
                        $data['error'] = validation_errors();
                        return $this->load->view('inventory_sep/inventory', $data);
                    } else {                        
                        $SalaryId = $this->input->post('SalaryId');
                        $EmployeeSalary = [
                            'Month' => $this->input->post('Month'),
                            'WorkingDays' => $this->input->post('WorkingDays'),
                            'PresentDays' => $this->input->post('PresentDays'),
                            'LeaveDays' => $this->input->post('LeaveDays'),
                            'Holidays' => $this->input->post('Holidays'),
                            'AbsentDays' => $this->input->post('AbsentDays'),
                            'TotalDays' => $this->input->post('TotalDays'),
                            'OverTimeHours' => $this->input->post('OverTimeHours'),
                            'Basic' => $this->input->post('Basic'),
                            'HRA' => $this->input->post('HRA'),
                            'ConveyanceAllowance' => $this->input->post('ConveyanceAllowance'),
                            'StaffSpecialAllowance' => $this->input->post('StaffSpecialAllowance'),
                            'GrossMonthlyTotal' => $this->input->post('GrossMonthlyTotal'),
                            'EmployeePF' => $this->input->post('EmployeePF'),
                            'EmployeeESI' => $this->input->post('EmployeeESI'),
                            'ProfessionTax' => $this->input->post('ProfessionTax'),
                            'IncomeTax' => $this->input->post('IncomeTax'),
                            'TDSAmount' => $this->input->post('TDSAmount'),
                            'MonthlyDeduction' => $this->input->post('MonthlyDeduction'),
                            'MonthlyNetTakeHome' => $this->input->post('MonthlyNetTakeHome'),
                            'EmployerPF' => $this->input->post('EmployerPF'),
                            'EmployerESI' => $this->input->post('EmployerESI'),
                            'AnnualComponents' => $this->input->post('AnnualComponents'),
                            'PerformanceBonus' => $this->input->post('PerformanceBonus'),
                            'Gratuity' => $this->input->post('Gratuity'),
                            'LeaveTravelAllowance' => $this->input->post('LeaveTravelAllowance'),
                            'SecurityDeduction' => $this->input->post('SecurityDeduction'),
                            'OverTimeAmount' => $this->input->post('OverTimeAmount'),
                            'NetTakeHomeSalary' => $this->input->post('NetTakeHomeSalary'),
                            'Updated' => date('d-m-Y h:i:s A'),
                            'UpdatedBy' => get_title('users', ['id' => $this->session->userdata('user_id')], 'name'),
                        ];
                        
                        $UpdateWhere = ['Id' => $SalaryId, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id'), 'FinancialYear' => $this->session->userdata('financial_year'), 'IsDeleted' => 1];
                        if ($this->session->userdata('BranchRole')) {
                            $UpdateWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                        }
                        $Result = $this->user_model->updateAry('employee_salary', $EmployeeSalary, $UpdateWhere);
                        if (!empty($Result)) {
                            $this->session->set_flashdata(['msg' => 'Record update successfully.', 'class' => 'success']);
                            return redirect(base_url() . 'Employee/SalaryReport');
                        } else {
                            $this->session->set_flashdata(['msg' => 'Unable to update record.', 'class' => 'danger']);
                            return redirect(base_url() . 'Employee/SalaryEdit/' . $Id);
                        }
                    }
                }
            } else {
                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/SalaryReport');
            }   
        }
        
        public function SalaryDelete($Id)
        {
            if ($Id) {
                $Where = ['Id' => $Id, 'UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id'), 'FinancialYear' => $this->session->userdata('financial_year'), 'IsDeleted' => 1];
                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                if ($this->db->select('Id')->where($Where)->get('employee_salary')->num_rows()) {
                    if ($this->user_model->updateAry('employee_salary', ['IsDeleted' => 0], $Where)) {
                        $this->session->set_flashdata(['msg' => 'Record delete successfully.', 'class' => 'success']);
                        return redirect(base_url() . 'Employee/SalaryReport');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to delete record.', 'class' => 'danger']);
                        return redirect(base_url() . 'Employee/SalaryReport');
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                    return redirect(base_url() . 'Employee/SalaryReport');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Is is missing.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/SalaryReport');
            }
        }
        
//////////////////////////////////////////// Employee Login /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        public function Dashboard() 
        {
            $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
            
            $EmployeeId = $this->session->userdata('EmployeeId');
            $UserId = $this->session->userdata('UserId');
            $CompId = $this->session->userdata('CompId');
            $CompBranchId = $this->session->userdata('CompBranchId');
            
            $Where = ['user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
            $EmployeeSalesWhere = [$EmployeeId];
            $PopullerProductWhere = ['invoice_product.user_id' => $UserId, 'invoice_product.comp_id' => $CompId, 'invoice_product.type' => 'Sale', 'invoice_product.status' => 1];
            $EmployeeWhere = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
            $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $CompBranchId;
                $PopullerProductWhere['invoice_product.CompanyBranchId'] = $CompBranchId;
                $EmployeeWhere['CompanyBranchId'] = $CompBranchId;
                $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
            }
            if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                $EmployeeWhere = ['Id' => $EmployeeId];
                $EmployeeIds = json_decode(get_title('employees', $EmployeeWhere, 'WarehouseSalesman'), true) ?: [];
                foreach ($EmployeeIds as $key => $Id) {
                    $EmployeeSalesWhere[] = $Id;
                }
            }

            // Outstatding Payment ** Get record count ** 
            $conditions1['returnType'] = 'count';
            $conditions1['where'] = $Where;
            $totalRecOutstatding = $this->user_model->getRowsDataOutstatdingEmployee($conditions1, $EmployeeSalesWhere, 'sales');
            
            // Pagination configuration 
            $config1['target']      = '#OutstatdingList'; 
            $config1['base_url']    = base_url('Employee/AjaxOutstatdingData'); 
            $config1['total_rows']  = $totalRecOutstatding; 
            $config1['per_page']    = 5; 
            $config1['link_func']   = 'searchFilter1'; 
            
            // Initialize pagination library 
            $this->ajax_pagination_dashboard->initialize($config1); 

            // Get records 
            $conditions1 = array('limit' => 5);
            $conditions1['where'] = $Where;
            $data['PaymentInfo'] = $this->user_model->getRowsDataOutstatdingEmployee($conditions1, $EmployeeSalesWhere, 'sales');
            $data['PaymentPaging'] = $this->ajax_pagination_dashboard->create_links();

            // Previous Sale ** Get record count ********************************************************************************
            $conditions2['returnType'] = 'count'; 
            $conditions2['where'] = $Where;
            $totalRecPreviousSale = $this->user_model->getRowsDataPreviousSaleEmployee($conditions2, $EmployeeSalesWhere, 'sales');
            
            // Pagination configuration 
            $config2['target']      = '#PreviousSaleList'; 
            $config2['base_url']    = base_url('Employee/AjaxPreviousSaleData'); 
            $config2['total_rows']  = $totalRecPreviousSale; 
            $config2['per_page']    = 5; 
            $config2['link_func']   = 'searchFilter2'; 
            
            // Initialize pagination library 
            $this->ajax_pagination_dashboard->initialize($config2);

            // Get records 
            $conditions2 = array('limit' => 5);
            $conditions2['where'] = $Where;
            $data['PreviousSale'] = $this->user_model->getRowsDataPreviousSaleEmployee($conditions2, $EmployeeSalesWhere, 'sales');
            $data['SalePaging'] = $this->ajax_pagination_dashboard->create_links();
            
            // Populler Products ** Get record count ******************************************************************************
            $conditions3['returnType'] = 'count'; 
            $conditions3['where'] = $PopullerProductWhere;
            $totalRecPopullerProducts = $this->user_model->getRowsDataPopullerProductsEmployee($conditions3, $EmployeeSalesWhere, 'invoice_product');
            
            // Pagination configuration 
            $config3['target']      = '#PopullerProductsList'; 
            $config3['base_url']    = base_url('Employee/AjaxPopullerProductsData'); 
            $config3['total_rows']  = $totalRecPopullerProducts; 
            $config3['per_page']    = 5; 
            $config3['link_func']   = 'searchFilter3'; 

            // Initialize pagination library 
            $this->ajax_pagination_dashboard->initialize($config3);

            // Get records 
            $conditions3 = array('limit' => 5); 
            $conditions3['where'] = $PopullerProductWhere;
            $data['SallingProducts'] = $this->user_model->getRowsDataPopullerProductsEmployee($conditions3, $EmployeeSalesWhere, 'invoice_product');
            $data['ProductPaging'] = $this->ajax_pagination_dashboard->create_links();
            
            $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($EmployeeWhere)->get('employees')->row();
            $data['EmployeePermission'] = $this->user_model->getRow('employee_permission', $EmployeePermissionWhere);

            $Sales = $this->db->select('id, MONTH(invoiceDate) AS Month, SUM(total) AS total, SUM(paid_amount) AS paid_amount')->where($Where)->where('financial_year', $this->session->userdata('financial_year'))->where_in('SalesmanId', $EmployeeSalesWhere)->group_by('Month')->get_compiled_select('sales');
            $Estimates = $this->db->select('id, MONTH(invoiceDate) AS Month, SUM(total) AS total, SUM(paid_amount) AS paid_amount')->where($Where)->where('financial_year', $this->session->userdata('financial_year'))->where_in('SalesmanId', $EmployeeSalesWhere)->group_by('Month')->get_compiled_select('estimates');
            $SalesEstimatesQuery = '(' . $Sales . ' UNION ' . $Estimates . ') AS SalesEstimatesData';
            $this->db->reset_query();
            
            $this->db->select('MONTHNAME(CONCAT("2000-", Month, "-01")) AS month_name', FALSE);
            $this->db->select_sum('total', 'total');
            $this->db->select_sum('paid_amount', 'paid_amount');
            $this->db->group_by('Month');
            $this->db->order_by('(Month + 8) % 12 + 1');
            $data['SalesTotalPaidBalance'] = $this->db->get($SalesEstimatesQuery)->result();

            $SalesDelivery = $this->db->select('id, delivery_status, delivery_date')->where($Where)->where('financial_year', $this->session->userdata('financial_year'))->where_in('SalesmanId', $EmployeeSalesWhere)->get_compiled_select('sales');
            $EstimatesDelivery = $this->db->select('id, delivery_status, delivery_date')->where($Where)->where('financial_year', $this->session->userdata('financial_year'))->where_in('SalesmanId', $EmployeeSalesWhere)->get_compiled_select('estimates');
            $DeliveryQuery = '(' . $SalesDelivery . ' UNION ' . $EstimatesDelivery . ') AS SaleEstimateDelivery';
            $this->db->reset_query();
            
            $this->db->select('id, delivery_status, delivery_date');
            $this->db->order_by('id', 'DESC');
            $data['Assignments'] = $this->db->get($DeliveryQuery)->result();
            return $this->load->view('inventory_sep/Employee/Dashboard', $data);
        }
        
        public function AjaxOutstatdingData()
        {
            $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
            
            $EmployeeId = $this->session->userdata('EmployeeId');
            $UserId = $this->session->userdata('UserId');
            $CompId = $this->session->userdata('CompId');
            $CompBranchId = $this->session->userdata('CompBranchId');
            $EmployeeSalesWhere = [$EmployeeId];
            $Where = ['user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
            $EmployeeWhere = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $CompBranchId;
                $EmployeeWhere['CompanyBranchId'] = $CompBranchId;
            }
            if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                $EmployeeWhere['Id'] = $EmployeeId;
                $EmployeeIds = json_decode(get_title('employees', $EmployeeWhere, 'WarehouseSalesman'), true) ?: [];
                foreach ($EmployeeIds as $key => $Id) {
                    $EmployeeSalesWhere[] = $Id;
                }
            }
            
            $this->perPage = 5;
            // Define offset 
            $page = $this->input->post('page'); 
            if (!$page){ 
                $offset = 0; 
            } else { 
                $offset = $page; 
            } 

            // Set conditions for search and filter 
            $keywords = $this->input->post('keywords'); 
            $sortBy = $this->input->post('sortBy'); 
            if (!empty($keywords)) { 
                $conditions['search']['keywords'] = $keywords; 
            } 
            if (!empty($sortBy)) { 
                $conditions['search']['sortBy'] = $sortBy; 
            } 

            // Get record count 
            $conditions['returnType'] = 'count';
            $conditions['where'] = $Where;
            $totalRec = $this->user_model->getRowsDataOutstatdingEmployee($conditions, $EmployeeSalesWhere, 'sales');

            // Pagination configuration 
            $config['target']      = '#OutstatdingList'; 
            $config['base_url']    = base_url('Employee/AjaxOutstatdingData'); 
            $config['total_rows']  = $totalRec; 
            $config['per_page']    = $this->perPage; 
            $config['link_func']   = 'searchFilter1'; 

            // Initialize pagination library 
            $this->ajax_pagination_dashboard->initialize($config); 

            // Get records 
            $conditions['start'] = $offset; 
            $conditions['limit'] = $this->perPage;
            $conditions['where'] = $Where;
            unset($conditions['returnType']); 
            $data['PaymentInfo'] = $this->user_model->getRowsDataOutstatdingEmployee($conditions, $EmployeeSalesWhere, 'sales');
            $data['PaymentPaging'] = $this->ajax_pagination_dashboard->create_links();
            
            return $this->load->view('inventory_sep/Employee/Search/Outstatdings', $data, false);
        }
        
        public function AjaxPreviousSaleData()
        {
            $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");

            $EmployeeId = $this->session->userdata('EmployeeId');
            $UserId = $this->session->userdata('UserId');
            $CompId = $this->session->userdata('CompId');
            $CompBranchId = $this->session->userdata('CompBranchId');
            $EmployeeSalesWhere = [$EmployeeId];
            $Where = ['user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
            $EmployeeWhere = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $CompBranchId;
                $EmployeeWhere['CompanyBranchId'] = $CompBranchId;
            }
            if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                $EmployeeWhere['Id'] = $EmployeeId;
                $EmployeeIds = json_decode(get_title('employees', $EmployeeWhere, 'WarehouseSalesman'), true) ?: [];
                foreach ($EmployeeIds as $key => $Id) {
                    $EmployeeSalesWhere[] = $Id;
                }
            }
            
            $this->perPage = 5;
            // Define offset 
            $page = $this->input->post('page'); 
            if (!$page){ 
                $offset = 0; 
            } else { 
                $offset = $page; 
            } 

            // Set conditions for search and filter 
            $keywords = $this->input->post('keywords'); 
            $sortBy = $this->input->post('sortBy'); 
            if (!empty($keywords)) { 
                $conditions['search']['keywords'] = $keywords;
            } 
            if (!empty($sortBy)) { 
                $conditions['search']['sortBy'] = $sortBy; 
            } 

            // Get record count 
            $conditions['returnType'] = 'count';
            $conditions['where'] = $Where;
            $totalRec = $this->user_model->getRowsDataPreviousSaleEmployee($conditions, $EmployeeSalesWhere, 'sales');

            // Pagination configuration 
            $config['target']      = '#PreviousSaleList'; 
            $config['base_url']    = base_url('Employee/AjaxPreviousSaleData'); 
            $config['total_rows']  = $totalRec; 
            $config['per_page']    = $this->perPage; 
            $config['link_func']   = 'searchFilter2'; 

            // Initialize pagination library 
            $this->ajax_pagination_dashboard->initialize($config); 
            
            // Get records 
            $conditions['start'] = $offset; 
            $conditions['limit'] = $this->perPage;
            $conditions['where'] = $Where;
            unset($conditions['returnType']);
            $data['PreviousSale'] = $this->user_model->getRowsDataPreviousSaleEmployee($conditions, $EmployeeSalesWhere, 'sales');
            $data['SalePaging'] = $this->ajax_pagination_dashboard->create_links();
            return $this->load->view('inventory_sep/Employee/Search/PreviousSales', $data, false);
        }
        
        public function AjaxPopullerProductsData()
        {
            $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
            
            $EmployeeId = $this->session->userdata('EmployeeId');
            $UserId = $this->session->userdata('UserId');
            $CompId = $this->session->userdata('CompId');
            $CompBranchId = $this->session->userdata('CompBranchId');
            
            $EmployeeSalesWhere = [$EmployeeId];
            $PopullerProductWhere = ['invoice_product.user_id' => $UserId, 'invoice_product.comp_id' => $CompId, 'invoice_product.type' => 'Sale', 'invoice_product.status' => 1];
            $EmployeeWhere = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
            if ($this->session->userdata('BranchRole')) {
                $PopullerProductWhere['invoice_product.CompanyBranchId'] = $CompBranchId;
                $EmployeeWhere['CompanyBranchId'] = $CompBranchId;
            }
            if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                $EmployeeWhere['Id'] = $EmployeeId;
                $EmployeeIds = json_decode(get_title('employees', $EmployeeWhere, 'WarehouseSalesman'), true) ?: [];
                foreach ($EmployeeIds as $key => $Id) {
                    $EmployeeSalesWhere[] = $Id;
                }
            }
            
            $this->perPage = 5;
            // Define offset 
            $page = $this->input->post('page'); 
            if (!$page){ 
                $offset = 0; 
            } else { 
                $offset = $page; 
            } 

            // Set conditions for search and filter 
            $keywords = $this->input->post('keywords'); 
            $sortBy = $this->input->post('sortBy'); 
            if (!empty($keywords)) { 
                $conditions['search']['keywords'] = $keywords; 
            } 
            if (!empty($sortBy)) { 
                $conditions['search']['sortBy'] = $sortBy; 
            } 

            // Get record count 
            $conditions['returnType'] = 'count';
            $conditions['where'] = $PopullerProductWhere;
            $totalRec = $this->user_model->getRowsDataPopullerProductsEmployee($conditions, $EmployeeSalesWhere, 'invoice_product');

            // Pagination configuration 
            $config['target']      = '#PopullerProductsList'; 
            $config['base_url']    = base_url('Employee/AjaxPopullerProductsData'); 
            $config['total_rows']  = $totalRec; 
            $config['per_page']    = $this->perPage; 
            $config['link_func']   = 'searchFilter3'; 

            // Initialize pagination library 
            $this->ajax_pagination_dashboard->initialize($config); 
            
            // Get records 
            $conditions['start'] = $offset; 
            $conditions['limit'] = $this->perPage;
            $conditions['where'] = $PopullerProductWhere;
            unset($conditions['returnType']); 
            $data['SallingProducts'] = $this->user_model->getRowsDataPopullerProductsEmployee($conditions, $EmployeeSalesWhere, 'invoice_product');
            $data['ProductPaging'] = $this->ajax_pagination_dashboard->create_links();
            return $this->load->view('inventory_sep/Employee/Search/PopullerProducts', $data, false);
        }
        
        public function SalePurchaseAndEstimateData ()
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
                
                $Type = $this->input->post('type');
                $FYear = $this->input->post('year');
                $Month = $this->input->post('month');

                if ($Type != "" && $FYear != "" && $Month != "") {
                    $getfyear = get_title('year', ['id' => $FYear], 'year');
                    $yearstr = explode("-", $getfyear);
                    if (date('m') >= 4) {  
                        if ($Month >= 4) {    
                            $year = $yearstr[0];
                        }
                        if ($Month < 4) {
                            $year = '20' . $yearstr[1];
                        }
                    } elseif (date('m') < 4) {
                        if ($Month >= 4) {    
                            $year = $yearstr[0];
                        }
                        if ($Month < 4) {
                            $year = '20' . $yearstr[1];
                        }
                    }
                    
                    if ($this->session->userdata('RoleType')) {
                        $EmployeeId = $this->session->userdata('EmployeeId');
                        $UserId = $this->session->userdata('UserId');
                        $CompId = $this->session->userdata('CompId');
                        $CompanyBranchId = $this->session->userdata('CompBranchId');
                        $Where = ['user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1, 'financial_year' => $FYear, 'invoiceDate >=' => date($year . '-' . $Month . '-01'), 'invoiceDate <=' => date($year . '-' . $Month . '-31')];
                        $EmployeeWhere = [$EmployeeId];
                        $WhereEmployee = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
                        if ($this->session->userdata('BranchRole')) {
                            $Where['CompanyBranchId'] = $CompanyBranchId;
                            $WhereEmployee['CompanyBranchId'] = $CompanyBranchId;
                        }
                        if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                            $WhereEmployee['Id'] = $EmployeeId;
                            $EmployeeIds = json_decode(get_title('employees', $WhereEmployee, 'WarehouseSalesman'), true) ?: [];
                            foreach ($EmployeeIds as $key => $Id) {
                                $EmployeeWhere[] = $Id;
                            }
                        }
                        
                        if ($Type == "All") {
                            $SaleData = $this->db->select('SUM(total) AS total, invoiceDate, type')->where($Where)->where_in('SalesmanId', $EmployeeWhere)->group_by('invoiceDate')->get_compiled_select('sales');
                            $PurchaseData = $this->db->select('SUM(total) AS total, invoiceDate, type')->where($Where)->where_in('EmployeeId', $EmployeeWhere)->group_by('invoiceDate')->get_compiled_select('purchases');
                            $EstimateData = $this->db->select('SUM(total) AS total, invoiceDate, type')->where($Where)->where_in('SalesmanId', $EmployeeWhere)->group_by('invoiceDate')->get_compiled_select('estimates');
                            $InvoiceInfo = $this->db->query($SaleData . " UNION " . $PurchaseData . " UNION " . $EstimateData . " ORDER BY invoiceDate ASC")->result();
                            echo json_encode(['status' => true, 'InvoiceData' => $InvoiceInfo]);
                        } elseif ($Type == "Sale") {
                            $SaleData = $this->db->select('SUM(total) AS total, invoiceDate, type')->where($Where)->where_in('SalesmanId', $EmployeeWhere)->group_by('invoiceDate')->order_by('invoiceDate', 'ASC')->get('sales')->result();
                            echo json_encode(['status' => true, 'InvoiceData' => $SaleData]);
                        } elseif ($Type == "Purchase") {
                            $PurchaseData = $this->db->select('SUM(total) AS total, invoiceDate, type')->where($Where)->where_in('EmployeeId', $EmployeeWhere)->group_by('invoiceDate')->order_by('invoiceDate', 'ASC')->get('purchases')->result();
                            echo json_encode(['status' => true, 'InvoiceData' => $PurchaseData]);
                        } elseif ($Type == "Estimate") {
                            $EstimateData = $this->db->select('SUM(total) AS total, invoiceDate, type')->where($Where)->where_in('SalesmanId', $EmployeeWhere)->group_by('invoiceDate')->order_by('invoiceDate', 'ASC')->get('estimates')->result();
                            echo json_encode(['status' => true, 'InvoiceData' => $EstimateData]);
                        } else {
                            echo json_encode(['status' => false, 'data' => ""]);
                        }
                    }
                }
            }
        }
        
        public function GetAccountSummary() 
        {
            $CreditorsAmount = array('Credit' => 1000);
            $DebtorsAmount = array('Debit' => 1000);
            $CashInHand = 5000;
            echo json_encode(['status' => true, 'CreditorsBalance' => $CreditorsAmount, 'DebtorsBalance' => $DebtorsAmount, 'CashBalance' => $CashInHand]);
        }
        
        public function Profile()
        {
            $EmployeeId = $this->session->userdata('EmployeeId');
            $UserId = $this->session->userdata('UserId');
            $CompId = $this->session->userdata('CompId');
            $CompBranchId = $this->session->userdata('CompBranchId');
            $EmployeeWhere = ['Id' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId, 'IsActive' => 1, 'IsDeleted' => 1, 'Status' => 1];
            $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
            if ($this->session->userdata('BranchRole')) {
                $EmployeeWhere['CompanyBranchId'] = $CompBranchId;
                $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
            }            
            $data['EmployeeInfo'] = $this->user_model->getRow('employees', $EmployeeWhere);
            $data['EmployeePermission'] = $this->user_model->getRow('employee_permission', $EmployeePermissionWhere);
            $data['page'] = "Profile";
            return $this->load->view('inventory_sep/Employee/Employee', $data);
        }
        
        public function ChangePassword()
        {
            if ($this->session->userdata('EmployeeId') != "") {
                if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                    $this->form_validation->set_rules('CurrentPassword', 'Current Password', 'trim|required|max_length[15]|min_length[6]');
                    $this->form_validation->set_rules('NewPassword', 'New Password', 'trim|required|max_length[15]|min_length[6]');
                    $this->form_validation->set_rules('ConfirmPassword', 'Confirm Password', 'trim|required|max_length[15]|min_length[6]|matches[NewPassword]');
                    if ($this->form_validation->run() == false) {
                        $EmployeeId = $this->session->userdata('EmployeeId');
                        $UserId = $this->session->userdata('UserId');
                        $CompId = $this->session->userdata('CompId');
                        $CompBranchId = $this->session->userdata('CompBranchId');
                        $EmployeeWhere = ['Id' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId, 'IsActive' => 1, 'IsDeleted' => 1, 'Status' => 1];
                        $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
                        if ($this->session->userdata('BranchRole')) {
                            $EmployeeWhere['CompanyBranchId'] = $CompBranchId;
                            $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                        }
                        $data['EmployeeInfo'] = $this->user_model->getRow('Employees', $EmployeeWhere);
                        $data['EmployeePermission'] = $this->user_model->getRow('employee_permission', $EmployeePermissionWhere);
                        $data['error'] = validation_errors();
                        $data['page'] = "Profile";
                        return $this->load->view('inventory_sep/Employee/Employee', $data);
                    } else {
                        $getoldpassword = get_title('employees', ['Id' => $this->session->userdata('EmployeeId')], 'Password');
                        if ($getoldpassword == $this->input->post('CurrentPassword')) {
                            $EmployeeData = [
                                'Password' => $this->input->post('ConfirmPassword'),
                                'Updated' => date('d-m-Y h:i:s A'),
                                'UpdatedBy' => get_title('employees', ['Id' => $this->session->userdata('EmployeeId')], 'Name')
                            ];
                            
                            $EmployeeId = $this->session->userdata('EmployeeId');
                            $UserId = $this->session->userdata('UserId');
                            $CompId = $this->session->userdata('CompId');
                            $CompBranchId = $this->session->userdata('CompBranchId');
                            $EmployeeUpdateWhere = ['Id' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId, 'IsActive' => 1, 'IsDeleted' => 1, 'Status' => 1];
                            if ($this->session->userdata('BranchRole')) {
                                $EmployeeUpdateWhere['CompanyBranchId'] = $CompBranchId;
                            }
                            
                            $result = $this->user_model->updateAry('employees', $EmployeeData, $EmployeeUpdateWhere);
                            if (!empty($result)) {
                                $this->session->set_flashdata(['msg' => 'Password update successfully.', 'class' => 'success']);
                                return redirect(base_url() . 'Employee/Profile');
                            } else {
                                $this->session->set_flashdata(['msg' => 'Unable to update record.', 'class' => 'danger']);
                                return redirect(base_url() . 'Employee/Profile');
                            }
                        } else {
                            $this->session->set_flashdata(['msg' => 'Current Password not match, try again', 'class' => 'danger']);
                            return redirect(base_url() . 'Employee/Profile');
                        }
                    }
                }
            } else {
                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/Profile');
            }
        }
        
        public function PartiesReport () 
        {
            $EmployeeId = $this->session->userdata('EmployeeId');
            $UserId = $this->session->userdata('UserId');
            $CompId = $this->session->userdata('CompId');
            $CompBranchId = $this->session->userdata('CompBranchId');
            $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted', 1];
            $EmployeeWhere = [$EmployeeId];
            $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
            $PartyWhere = ['user_id' => $UserId, 'comp_id' => $CompId];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $CompBranchId;
                $EmployeeWhere['CompanyBranchId'] = $CompBranchId;
                $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                $PartyWhere['CompanyBranchId'] = $CompBranchId;
            }
            
            if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                $Where['Id'] = $EmployeeId;
                $EmployeeIds = json_decode(get_title('employees', $Where, 'WarehouseSalesman'), true) ?: [];
                foreach ($EmployeeIds as $key => $Id) {
                    $EmployeeWhere[] = $Id;
                }
            }
            
            if (employee_has_perm('Parties', 'View', $EmployeePermissionWhere)) {
                $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                $data['partiesdata'] = $this->db->where($PartyWhere)->where_in('SalesmanId', $EmployeeWhere)->order_by('id', 'DESC')->get('parties')->result();
                $data['page'] = "PartiesReport";
                return $this->load->view('inventory_sep/Employee/Employee', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/Dashboard');
            }
        }
        
        public function PartyInfo ($Id)
        {
            $EmployeeId = $this->session->userdata('EmployeeId');
            $UserId = $this->session->userdata('UserId');
            $CompId = $this->session->userdata('CompId');
            $CompBranchId = $this->session->userdata('CompBranchId');
            $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
            $EmployeeWhere = [$EmployeeId];
            $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
            $PartyWhere = ['id' => $Id, 'user_id' => $UserId, 'comp_id' => $CompId];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $CompBranchId;
                $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                $PartyWhere['CompanyBranchId'] = $CompBranchId;
            }
            if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                $Where['Id'] = $EmployeeId;
                $EmployeeIds = json_decode(get_title('employees', $Where, 'WarehouseSalesman'), true) ?: [];
                foreach ($EmployeeIds as $key => $Id) {
                    $EmployeeWhere[] = $Id;
                }
            }
            $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
            $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
            $data['partydata'] = $this->db->where($PartyWhere)->where_in('SalesmanId', $EmployeeWhere)->get('parties')->row();
            $data['page'] = "PartyInfo";
            return $this->load->view('inventory_sep/Employee/Employee', $data);
        }
        
        public function CreateParty() 
        {
            $EmployeeId = $this->session->userdata('EmployeeId');
            $UserId = $this->session->userdata('UserId');
            $CompId = $this->session->userdata('CompId');
            $CompBranchId = $this->session->userdata('CompBranchId');
            $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
            if ($this->session->userdata('BranchRole')) {
                $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
            }
            if (employee_has_perm('Parties', 'Create', $EmployeePermissionWhere)) {
                return $this->load->view('inventory_sep/sale/add_party');
            } else {
                $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/Dashboard');
            } 
        }
        
        public function EditParty($Id) 
        {
            if (!empty($Id)) {
                $EmployeeId = $this->session->userdata('EmployeeId');
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
                $CompBranchId = $this->session->userdata('CompBranchId');
                
                $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
                $PartyWhere = ['id' => $Id, 'user_id' => $UserId, 'comp_id' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                    $PartyWhere['CompanyBranchId'] = $CompBranchId;
                }
                if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                    $Where['Id'] = $EmployeeId;
                    $EmployeeIds = json_decode(get_title('employees', $Where, 'WarehouseSalesman'), true) ?: [];
                    foreach ($EmployeeIds as $key => $Id) {
                        $EmployeeWhere[] = $Id;
                    }
                }
                
                if (employee_has_perm('Parties', 'Edit', $EmployeePermissionWhere)) {
                    $data['party'] = $this->db->where($PartyWhere)->where_in('SalesmanId', $EmployeeWhere)->get('parties')->row();
                    return $this->load->view('inventory_sep/sale/party_edit', $data);
                } else {
                    $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                    return redirect(base_url() . 'Employee/Dashboard');
                } 
            } else {
                $this->session->set_flashdata(['msg' => 'Party id missing.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/PartiesReport');
            }
        }
        
        public function PartiesSalesReport()
        {
            $EmployeeId = $this->session->userdata('EmployeeId');
            $UserId = $this->session->userdata('UserId');
            $CompId = $this->session->userdata('CompId');
            $CompBranchId = $this->session->userdata('CompBranchId');
            
            $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
            $EmployeeWhere = [$EmployeeId];
            $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
            $PartyWhere = ['user_id' => $UserId, 'comp_id' => $CompId];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $CompBranchId;
                $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                $PartyWhere['CompanyBranchId'] = $CompBranchId;
            }
            if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                $Where['Id'] = $EmployeeId;
                $EmployeeIds = json_decode(get_title('employees', $Where, 'WarehouseSalesman'), true) ?: [];
                foreach ($EmployeeIds as $key => $Id) {
                    $EmployeeWhere[] = $Id;
                }
            }
            
            if (!empty($this->input->get('salesman'))) {
                $salesmanFilter = input_get_array('salesman');
                if (!in_array('All', $salesmanFilter)) {
                    $SalesmanIds = $salesmanFilter;
                } else {
                    $SalesmanIds = $EmployeeWhere;
                }
            } else {
                $SalesmanIds = $EmployeeWhere;
            }
            
            if (employee_has_perm('Sales', 'View', $EmployeePermissionWhere)) {
                $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                $data['partydata'] = $this->db->where($PartyWhere)->where_in('SalesmanId', $SalesmanIds)->get('parties')->result();
                $data['page'] = "PartiesSalesReport";
                return $this->load->view('inventory_sep/Employee/Employee', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/Dashboard');
            }
        }
        
        //Products        
        public function productsReport()
        {
            $EmployeeId = $this->session->userdata('EmployeeId');
            $UserId = $this->session->userdata('UserId');
            $CompId = $this->session->userdata('CompId');
            $CompBranchId = $this->session->userdata('CompBranchId');
            
            $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted', 1];
            $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
            $productWhere = ['user_id' => $UserId, 'comp_id' => $CompId];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $CompBranchId;
                $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                $productWhere['CompanyBranchId'] = $CompBranchId;
            }
            
            if (employee_has_perm('Products', 'View', $EmployeePermissionWhere)) {
                $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                $data['productsData'] = $this->db->where($productWhere)->order_by('id', 'DESC')->get('product')->result();
                $data['page'] = "productsReport";
                return $this->load->view('inventory_sep/Employee/Employee', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/Dashboard');
            }
        }
        
        // Orderbook
        public function Orderbook($PartyId = null) 
        {
            $EmployeeId = $this->session->userdata('EmployeeId');
            $UserId = $this->session->userdata('UserId');
            $CompId = $this->session->userdata('CompId');
            $CompBranchId = $this->session->userdata('CompBranchId');
            
            $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
            $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
            $PartiesWhere = ['user_id' => $UserId, 'comp_id' => $CompId];
            $ProductWhere = ['user_id' => $UserId, 'comp_id' => $CompId, 'OrderBook' => 'Yes', 'status' => 1];
            $EmployeeWhere = [$EmployeeId];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $CompBranchId;
                $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                $PartiesWhere['CompanyBranchId'] = $CompBranchId;
                $ProductWhere['CompanyBranchId'] = $CompBranchId;
            }
            if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                $Where['Id'] = $EmployeeId;
                $EmployeeIds = json_decode(get_title('employees', $Where, 'WarehouseSalesman'), true) ?: [];
                foreach ($EmployeeIds as $key => $Id) {
                    $EmployeeWhere[] = $Id;
                }
            }
            
            if (employee_has_perm('Orderbook', 'Create', $EmployeePermissionWhere)) {
                $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                $data['Parties'] = $this->db->select('id, name, area, contact')->where($PartiesWhere)->where_in('SalesmanId', $EmployeeWhere)->order_by('id', 'DESC')->get('parties')->result();
                $data['Products'] = $this->db->select('id, p_name, price')->where($ProductWhere)->order_by('id', 'DESC')->get('product')->result();
                $data['PartyData'] = $this->db->select('id, name, area, contact')->where('id', $PartyId)->get('parties')->row();
                $data['page'] = "Orderbook";
                return $this->load->view('inventory_sep/Employee/Employee', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/Dashboard');
            }
        }
        
        public function SalesmanForOrderbook()
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if ($this->input->post('PartyId')) {
                    $PartyId = $this->input->post('PartyId');
                    $EmployeeId = $this->session->userdata('EmployeeId');
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
                    $CompanyBranchId = $this->session->userdata('CompBranchId');
                    
                    $Where = ['IsDeleted' => 1, 'Status' => 1, 'UserId' => $UserId, 'CompId' => $CompId];
                    $SalesmanWhere = ['IsDeleted' => 1, 'Status' => 1, 'UserId' => $UserId, 'CompId' => $CompId];
                    $PartySalesmanWhere = ['id' => $PartyId, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
                    $EmployeeWhere = [$EmployeeId];
                    if ($this->session->userdata('BranchRole')){
                        $Where['CompanyBranchId'] = $CompanyBranchId; 
                        $SalesmanWhere['CompanyBranchId'] = $CompanyBranchId; 
                        $PartySalesmanWhere['CompanyBranchId'] = $CompanyBranchId; 
                    }
                    if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                        $Where['Id'] = $EmployeeId;
                        $EmployeeIds = json_decode(get_title('employees', $Where, 'WarehouseSalesman'), true) ?: [];
                        foreach ($EmployeeIds as $key => $Id) {
                            $EmployeeWhere[] = $Id;
                        }
                    }
                    
                    $SalesmanId = $this->db->select('SalesmanId')->where($PartySalesmanWhere)->get('parties')->row('SalesmanId');
                    $Salesmans = $this->db->select('Id, Name')->where($SalesmanWhere)->where_in('Id', $EmployeeWhere)->get('employees')->result();
                    foreach ($Salesmans as $key => $Salesman) {
                        $Selected = $SalesmanId == $Salesman->Id ? 'selected' : '';
                        echo "<option $Selected value='$Salesman->Id'" . set_select('SalesmanId', $Salesman->Id) .">" . $Salesman->Name . "</option>";
                    }
                } else {
                    echo "<option selected='' value=''>No salesman found</option>";
                }
            }
        }
        
        public function getSalesmanForSale()
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if ($this->input->post('SalesmanId')) {
                    $SalesmanId = $this->input->post('SalesmanId');
                    if ($this->session->userdata('RoleType')) {
                        $EmployeeId = $this->session->userdata('EmployeeId');
                        $UserId = $this->session->userdata('UserId');
                        $CompId = $this->session->userdata('CompId');
                        $SalesmanIds = json_decode(get_title('employees', ['Id' => $EmployeeId], 'WarehouseSalesman'), true) ?: [];
                    } else {
                        $UserId = $this->session->userdata('user_id');
                        $CompId = $this->session->userdata('comp_id');
                    }
                    $CompanyBranchId = $this->session->userdata('CompBranchId');
                    
                    $SalemanRoleId = get_title('position', ['PostName' => 'Salesman'], 'Id');
                    $SalesmanWhere = ['IsDeleted' => 1, 'Status' => 1, 'RoleId' => $SalemanRoleId, 'UserId' => $UserId, 'CompId' => $CompId];
                    if ($this->session->userdata('BranchRole')) {
                        $SalesmanWhere['CompanyBranchId'] = $CompanyBranchId;
                    }
                    
                    $this->db->select('Id, Name');
                    $this->db->where($SalesmanWhere);
                    if ($this->session->userdata('RoleType')) {
                        $this->db->where_in('Id', $SalesmanIds);
                    }
                    $Salesmans = $this->db->get('employees')->result();
                    foreach ($Salesmans as $key => $Salesman) {
                        $Selected = $SalesmanId == $Salesman->Id ? 'selected' : '';
                        echo "<option $Selected value='$Salesman->Id'" . set_select('SalesmanId', $Salesman->Id) .">" . $Salesman->Name . "</option>";
                    }
                } else {
                    echo "<option selected='' value=''>No salesman found</option>";
                }
            }
        }
        
        public function getSalesmanName()
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if ($this->input->post('PartyId')) {
                    $PartyId = $this->input->post('PartyId');
                    if ($this->session->userdata('RoleType')) {
                        $UserId = $this->session->userdata('UserId');
                        $CompId = $this->session->userdata('CompId');
                    } else {
                        $UserId = $this->session->userdata('user_id');
                        $CompId = $this->session->userdata('comp_id');
                    }
                    $CompanyBranchId = $this->session->userdata('CompBranchId');
                    
                    $PartyWhere = ['id' => $PartyId, 'status' => 1, 'user_id' => $UserId, 'comp_id' => $CompId];
                    if ($this->session->userdata('BranchRole')) {
                        $PartyWhere['CompanyBranchId'] = $CompanyBranchId;
                    }
                    $SalesmanId = get_title('parties', $PartyWhere, 'SalesmanId');

                    $Salesman = $this->db->select('Name')->where('Id', $SalesmanId)->get('employees')->row('Name');
                    if ($Salesman) {
                        echo $Salesman;
                    } else {
                        echo "No salesman found";
                    }
                } else {
                    echo "Party id not found";
                }
            }
        }
        
        public function OrderbookSave() 
        {                    
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $EmployeeId = $this->session->userdata('EmployeeId');
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
                $CompBranchId = $this->session->userdata('CompBranchId');
                $OrderDate = $this->input->post('Date');
                $PartyId = $this->input->post('PartyId');
                $VoucherType = $this->input->post('VoucherType');
                $SubTotal = $this->input->post('SubTotal');
                
                $OrderbookWhere = ['OrderDate' => $OrderDate, 'PartyId' => $PartyId, 'VoucherType' => $VoucherType, 'SubTotal' => $SubTotal, 'UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
                $CompProfileWhere = ['user_id' => $UserId, 'comp_id' => $CompId];
                $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
                $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
                $PartiesWhere = ['user_id' => $UserId, 'comp_id' => $CompId];
                $ProductWhere = ['user_id' => $UserId, 'comp_id' => $CompId, 'OrderBook' => 'Yes', 'status' => 1];
                $EmployeeWhere = [$EmployeeId];
                $OrderNumberWhere = ['IsDeleted' => 1, 'UserId' => $UserId, 'CompId' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $OrderbookWhere['CompanyBranchId'] = $CompBranchId;
                    $Where['CompanyBranchId'] = $CompBranchId;
                    $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                    $PartiesWhere['CompanyBranchId'] = $CompBranchId;
                    $ProductWhere['CompanyBranchId'] = $CompBranchId;
                    $OrderNumberWhere['CompanyBranchId'] = $CompBranchId;
                    $CompProfileWhere['CompanyBranchId'] = $CompBranchId;
                }
                
                $FinancialYear = get_title('comp_profile', $CompProfileWhere, 'financial_year');
                
                if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                    $Where['Id'] = $EmployeeId;
                    $EmployeeIds = json_decode(get_title('employees', $Where, 'WarehouseSalesman'), true) ?: [];
                    foreach ($EmployeeIds as $key => $Id) {
                        $EmployeeWhere[] = $Id;
                    }
                }
                
                if (employee_has_perm('Orderbook', 'Create', $EmployeePermissionWhere)) {
                    $this->form_validation->set_rules('Date', 'Date', 'trim|required');
                    $this->form_validation->set_rules('PartyId', 'Party Name', 'trim|required');
                    $this->form_validation->set_rules('VoucherType', 'Voucher Type', 'trim|required');
                    $this->form_validation->set_rules('SubTotal', 'Sub Total', 'trim|required|numeric');
                    $this->form_validation->set_rules('SalesmanId', 'Salesman', 'trim|required');
                    if ($this->form_validation->run() == false) {
                        $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                        $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                        $data['Parties'] = $this->db->select('id, name, area, contact')->where($PartiesWhere)->where_in('SalesmanId', $EmployeeWhere)->order_by('id', 'DESC')->get('parties')->result();
                        $data['Products'] = $this->db->select('id, p_name, price')->where($ProductWhere)->order_by('id', 'DESC')->get('product')->result();
                        $data['page'] = "Orderbook";
                        $data['error'] = validation_errors();
                        return $this->load->view('inventory_sep/Employee/Employee', $data);
                    } else {
                        if (empty($this->db->where($OrderbookWhere)->get('orderbook')->num_rows())) {
                            $OrderNumber = mt_rand(00000, 99999);
                            $OrderNumberWhere['OrderNumber'] = $OrderNumber;
                            if ($this->db->select('OrderNumber')->where($OrderNumberWhere)->where_in('EmployeeId', $EmployeeWhere)->get('orderbook')->num_rows()) {
                                $OrderNumber = mt_rand(00000, 99999);
                            }

                            $OrderbookData = [
                                'OrderDate' => $OrderDate,
                                'OrderNumber' => $OrderNumber,
                                'PartyId' => $PartyId,
                                'VoucherType' => $VoucherType,
                                'SubTotal' => $SubTotal,
                                'SalesmanId' => $this->input->post('SalesmanId'),
                                'EmployeeId' => $EmployeeId,
                                'UserId' => $UserId,
                                'CompId' => $CompId,
                                'CompanyBranchId' => $CompBranchId,
                                'FinancialYear' => $FinancialYear,
                                'Remark' => $this->input->post('Remark'),
                                'Created' => date('d-m-Y h:i:s A'),
                                'CreatedBy' => get_title('employees', ['Id' => $EmployeeId], 'Name'),
                            ];

                            $Result = $this->user_model->saveAry('orderbook', $OrderbookData);

                            foreach ($this->input->post('Products') as $key => $Product) {
                                if (!empty($Product['Quantity']) && !empty($Product['ProductId'])) {
                                    $OrderbookDataItems = [
                                        "OrderbookId" => $Result,
                                        'OrderNumber' => $OrderNumber,
                                        "OrderDate" => $OrderDate,
                                        "ProductName" => $Product['ProductName'],
                                        "ProductId" => $Product['ProductId'],
                                        "Price" => $Product['ProductPrice'],
                                        "Quantity" => $Product['Quantity'],
                                        "Total" => $Product['Total'],
                                        "EmployeeId" => $EmployeeId,
                                        "UserId" => $UserId,
                                        "CompId" => $CompId,
                                        "CompanyBranchId" => $CompBranchId,
                                        'FinancialYear' => $FinancialYear,
                                        "Created" => date('d-m-Y h:i:s A'),
                                        "CreatedBy" => get_title('employees', ['Id' => $EmployeeId], 'Name')
                                    ];
                                    $this->user_model->saveAry('orderbook_products', $OrderbookDataItems);
                                }
                            }

                            if (!empty($Result)) {
                                $this->session->set_flashdata(['msg' => 'Record saved successfully.', 'class' => 'success']);
                                return redirect(base_url() . 'Employee/OrderbookReport');
                            } else {
                                $this->session->set_flashdata(['msg' => 'Unable to save record.', 'class' => 'danger']);
                                return redirect(base_url() . 'Employee/Orderbook');
                            }
                        } else {
                            $this->session->set_flashdata(['msg' => 'Duplicate entry not allowed on same date.', 'class' => 'danger']);
                            return redirect(base_url() . 'Employee/Orderbook');
                        }
                    }
                }   
            } else {
                $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/Dashboard');                
            }
        }
        
        public function OrderbookReport() 
        {
            $EmployeeId = $this->session->userdata('EmployeeId');
            $UserId = $this->session->userdata('UserId');
            $CompId = $this->session->userdata('CompId');
            $CompBranchId = $this->session->userdata('CompBranchId');
            $FinancialYear = $this->session->userdata('financial_year');
            
            $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
            $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
            $EmployeeWhere = [$EmployeeId];
            $OrderBookWhere = ['IsDeleted' => 1, 'UserId' => $UserId, 'CompId' => $CompId, 'FinancialYear' => $FinancialYear];
            
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $CompBranchId;
                $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                $OrderBookWhere['CompanyBranchId'] = $CompBranchId;
            }
            
            if (employee_has_perm('Orderbook', 'View', $EmployeePermissionWhere)) {
                if ($this->input->get('From') && $this->input->get('To')) {
                    $From = $this->input->get('From');
                    $To = $this->input->get('To');
                } else {
                    $From = date('Y-m-d');
                    $To = date('Y-m-d');
                }

                $OrderBookWhere['OrderDate >= '] = $From;
                $OrderBookWhere['OrderDate <= '] = $To;
                if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                    $Where['Id'] = $EmployeeId;
                    $EmployeeIds = json_decode(get_title('employees', $Where, 'WarehouseSalesman'), true) ?: [];
                    foreach ($EmployeeIds as $key => $Id) {
                        $EmployeeWhere[] = $Id;
                    }
                }
                
                if ($this->input->get('Status') != "") {
                    $OrderBookWhere['InvoiceStatus'] = $this->input->get('Status');
                }
                
                $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                $data['OrderBook'] = $this->db->select('Id, OrderDate, OrderNumber, PartyId, VoucherType, SubTotal, InvoiceStatus, SalesmanId')->where($OrderBookWhere)->where_in('EmployeeId', $EmployeeWhere)->order_by('Id', 'DESC')->get('orderbook')->result();
                $data['page'] = "OrderbookReport";
                return $this->load->view('inventory_sep/Employee/Employee', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/Dashboard');
            }
        }

        
        public function OrderbookReportGrid() 
        {
            $EmployeeId = $this->session->userdata('EmployeeId');
            $UserId = $this->session->userdata('UserId');
            $CompId = $this->session->userdata('CompId');
            $CompBranchId = $this->session->userdata('CompBranchId');
            
            $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
            $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $CompBranchId;
                $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
            }
            
            if (employee_has_perm('Orderbook', 'View', $EmployeePermissionWhere)) {
                $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                $data['page'] = "OrderbookReportGrid";
                return $this->load->view('inventory_sep/Employee/Employee', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/Dashboard');
            }
        }
        
        public function OrderbookReportGridAjax() 
        {
            if ($this->input->is_ajax_request()) {
                $Limit = $this->input->post('Limit');
                $Start = $this->input->post('Start');
                $PartyName = $this->input->post('PartyName');
                $EmployeeId = $this->session->userdata('EmployeeId');
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
                $CompBranchId = $this->session->userdata('CompBranchId');
                $FinancialYear = $this->session->userdata('financial_year');

                $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
                $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
                $EmployeeWhere = [$EmployeeId];
                $OrderBookWhere = ['IsDeleted' => 1, 'UserId' => $UserId, 'CompId' => $CompId, 'FinancialYear' => $FinancialYear];

                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $CompBranchId;
                    $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                    $OrderBookWhere['CompanyBranchId'] = $CompBranchId;
                }

                if (employee_has_perm('Orderbook', 'View', $EmployeePermissionWhere)) {
                    if ($this->input->post('fromDate') && $this->input->post('toDate')) {
                        $From = $this->input->post('fromDate');
                        $To = $this->input->post('toDate');
                    } else {
                        $From = date('Y-m-d');
                        $To = date('Y-m-d');
                    }

                    $OrderBookWhere['OrderDate >= '] = $From;
                    $OrderBookWhere['OrderDate <= '] = $To;
                    if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                        $Where['Id'] = $EmployeeId;
                        $EmployeeIds = json_decode(get_title('employees', $Where, 'WarehouseSalesman'), true) ?: [];
                        foreach ($EmployeeIds as $key => $Id) {
                            $EmployeeWhere[] = $Id;
                        }
                    }

                    if ($this->input->post('Status') != "") {
                        $OrderBookWhere['InvoiceStatus'] = $this->input->post('Status');
                    }
                    
                    $EmployeePermission = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                    $this->db->select('orderbook.Id, orderbook.OrderDate, orderbook.OrderNumber, orderbook.PartyId, orderbook.VoucherType, orderbook.SubTotal, orderbook.InvoiceStatus, orderbook.SalesmanId');
                    if (isset($PartyName) && !empty($PartyName)) {
                        $this->db->join('parties', 'parties.id = orderbook.PartyId', 'LEFT');
                        $this->db->like('parties.name', $PartyName);
                    }
                    $this->db->where($OrderBookWhere);
                    $this->db->where_in('orderbook.EmployeeId', $EmployeeWhere);
                    $this->db->order_by('orderbook.Id', 'DESC');
                    $this->db->limit($Limit, $Start);
                    $OrderBook = $this->db->get('orderbook')->result();
                    
                    if ($OrderBook) {
                        $Html = "";
                        foreach ($OrderBook as $key => $Data) {
                            $PartyInfo = $this->db->select('name, contact, gst, area, SalesmanId')->where('id', $Data->PartyId)->get('parties')->row();
                            $PartyArea = $PartyInfo->area ? '(' . $PartyInfo->area . ')' : '';
                            
                            if ($Data->VoucherType == "Sale") {
                                if ($Data->InvoiceStatus == 1) {
                                    if (employee_perm($EmployeePermission, 'Sales', 'Create')) {
                                        $OrderBookStatus = "<a href='" . base_url() . "Employee/OrderbookToSaleInvoice/" . $Data->Id . "' class='nav-link' title='Create Sale Invoice' target='_blank'>"
                                                         . "OrderBook Status "
                                                         . "<span class='float-right badge bg-primary'>"   
                                                         . "<i class='fa fa-shopping-bag text-white'></i> Create"
                                                         . "</span>"
                                                         . "</a>";
                                    } else {
                                        $OrderBookStatus = "<a href='javascript:void(0);' class='nav-link'>"
                                                         . "OrderBook Status "
                                                         . "<span class='float-right badge bg-warning'>"   
                                                         . "<i class='fa fa-spinner'></i> Wait"
                                                         . "</span>"
                                                         . "</a>";
                                    } 
                                } elseif ($Data->InvoiceStatus == 2) {
                                    if (employee_perm($EmployeePermission, 'Sales', 'Edit')) {
                                        $OrderBookStatus = "<a href='" . base_url() . "Employee/SaleEdit/" . get_title('sales', ['order_book_id' => $Data->Id], 'id') . "' class='nav-link'>"
                                                         . "OrderBook Status "
                                                         . "<span class='float-right badge bg-success'>"   
                                                         . get_title('sales', ['order_book_id' => $Data->Id] ,'invoiceNumber')
                                                         . "</span>"
                                                         . "</a>";
                                    } else {
                                        $OrderBookStatus = "<a href='javascript:void(0);' class='nav-link'>"
                                                         . "OrderBook Status "
                                                         . "<span class='float-right badge bg-success'>"
                                                         . get_title('sales', ['order_book_id' => $Data->Id], 'invoiceNumber')
                                                         . "</span>"
                                                         . "</a>";
                                    }
                                } else {
                                        $OrderBookStatus = "<a href='javascript:void(0);' class='nav-link'>"
                                                         . "OrderBook Status "
                                                         . "<span class='float-right badge bg-danger'>"
                                                         . "<i class='fa fa-ban text-white'></i> Cancelled"
                                                         . "</span>"
                                                         . "</a>";
                                }
                            }
                            
                            if ($Data->VoucherType == "Estimate") {
                                if ($Data->InvoiceStatus == 1) {
                                    if (employee_perm($EmployeePermission, 'Estimates', 'Create')) {
                                        $OrderBookStatus = "<a href='" . base_url() . "Employee/OrderbookToEstimateInvoice/" . $Data->Id ."' class='nav-link' title='Create Estimate Invoice' target='_blank'>"
                                                         . "OrderBook Status "
                                                         . "<span class='float-right badge bg-primary'>"
                                                         . "<i class='fa fa-shopping-bag text-white'></i> Create"
                                                         . "</span>"
                                                         . "</a>";
                                    } else {
                                        $OrderBookStatus = "<a href='javascript:void(0);' class='nav-link'>"
                                                         . "OrderBook Status "
                                                         . "<span class='float-right badge bg-warning'>"
                                                         . "<i class='fa fa-spinner'></i> Wait"
                                                         . "</span>"
                                                         . "</a>";
                                    }
                                } elseif ($Data->InvoiceStatus == 2) {    
                                    if (employee_perm($EmployeePermission, 'Estimates', 'Edit')) {
                                        $OrderBookStatus = "<a href='" . base_url() . "Employee/EstimateEdit/" . get_title('estimates', ['order_book_id' => $Data->Id], 'id') ."' class='nav-link'>"
                                                         . "OrderBook Status "
                                                         . "<span class='float-right badge bg-success'>"
                                                         . get_title('estimates', ['order_book_id' => $Data->Id] ,'invoiceNumber')
                                                         . "</span>"
                                                         . "</a>";
                                    } else {
                                        $OrderBookStatus = "<a href='javascript:void(0);' class='nav-link'>"
                                                         . "OrderBook Status "
                                                         . "<span class='float-right badge bg-success'>"
                                                         . get_title('estimates', ['order_book_id' => $Data->Id], 'invoiceNumber')
                                                         . "</span>"
                                                         . "</a>";
                                    }
                                } else {
                                    $OrderBookStatus = "<a href='javascript:void(0);' class='nav-link'>"
                                                     . "OrderBook Status "
                                                     . "<span class='float-right badge bg-danger'>"
                                                     . "<i class='fa fa-ban text-white'></i> Cancelled"
                                                     . "</span>"
                                                     . "</a>";
                                }
                            }
                                    
                            if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == 'Supervisor') {
                                $SalesmanName = "<li class='nav-item'>"
                                              . "<a href='javascript:void(0);' class='nav-link'>"
                                              . "Salesman "
                                              . "<span class='float-right'>"
                                              . get_title('employees', ['Id' => $PartyInfo->SalesmanId], 'Name')
                                              . "</span>"
                                              . "</a>"
                                              . "</li>";
                            } else {
                                $SalesmanName = "";
                            }
                                    
                            if (employee_perm($EmployeePermission, 'Orderbook', 'View')) {
                                $OrderBookInfo = "<a href='" . base_url() . "Employee/OrderbookInfo/" . $Data->Id . "' class='btn btn-primary btn-xs' title='View'>"
                                               . "<i class='fa fa-info-circle text-white'></i>"
                                               . "</a>";
                            }
                            
                            if ($Data->InvoiceStatus == 1) {
                                if (employee_perm($EmployeePermission, 'Orderbook', 'Edit')) {
                                    $OrderBookEdit = "<a href='" . base_url() . "Employee/OrderbookEdit/" . $Data->Id . "' class='btn btn-success btn-xs' title='Edit'>"
                                                   . "<i class='fa fa-edit text-white'></i>"
                                                   . "</a>";
                                }
                                
                                if (employee_perm($EmployeePermission, 'Orderbook', 'Delete')) {
                                    $OrderBookDelete = "<a href='" . base_url() . "Employee/OrderbookDelete/" . $Data->Id . "' class='btn btn-danger btn-xs' title='Delete' onclick='return confirm(\"Are you sure want to delete this record!\");'>"
                                                     . "<i class='fa fa-trash text-white'></i>"
                                                     . "</a>";
                                }
                            }

                            $Html   .=  '<div class="col-md-3 col-sm-3 col-lg-3 col-xl-3 col-xxl-3 col-xs-3 pb-2">'
                                    .   '<div class="card card-widget widget-user-2 shadow-sm">'
                                    .   '<div class="widget-user-header bg-maroon">'
                                    .   '<h3 class="widget-user-username">'
                                    .   $PartyInfo->name
                                    .   '<small class="text-white">'
                                    .   $PartyArea
                                    .   '</small>'
                                    .   '</h3>'
                                    .   '<h5 class="widget-user-desc">'
                                    .   $PartyInfo->contact
                                    .   '</h5>'
                                    .   '<span class="widget-user-desc text-white">'
                                    .   $PartyInfo->gst
                                    .   '</span>'
                                    .   '</div>'
                                    .   '<div class="card-body p-0">'
                                    .   '<ul class="nav flex-column">'
                                    .   '<li class="nav-item">'
                                    .   '<a href="javascript:void(0);" class="nav-link">'
                                    .   'Date '
                                    .   '<span class="float-right">'
                                    .   date("d-m-Y", strtotime($Data->OrderDate))
                                    .   '</span>'
                                    .   '</a>'
                                    .   '</li>'
                                    .   '<li class="nav-item">'
                                    .   '<a href="javascript:void(0);" class="nav-link">'
                                    .   'Order Number '
                                    .   '<span class="float-right">'
                                    .   $Data->OrderNumber
                                    .   '</span>'
                                    .   '</a>'
                                    .   '</li>'
                                    .   '<li class="nav-item">'
                                    .   '<a href="javascript:void(0);" class="nav-link">'
                                    .   'Total '
                                    .   '<span class="float-right">'
                                    .   '<i class="fa fa-inr"></i> ' . number_format($Data->SubTotal, 2)
                                    .   '</span>'
                                    .   '</a>'
                                    .   '</li>'
                                    .   '<li class="nav-item">'
                                    .   $OrderBookStatus
                                    .   '</li>'
                                    .   $SalesmanName
                                    .   '</ul>'
                                    .   '</div>'
                                    .   '<div class="card-footer p-2 text-center">'
                                    .   $OrderBookInfo
                                    .   '&nbsp;&nbsp;'
                                    .   $OrderBookEdit
                                    .   '&nbsp;&nbsp;'
                                    .   $OrderBookDelete
                                    .   '</div>'
                                    .   '</div>'
                                    .   '</div>';
                        }
                        if ($Html) {
                            echo $Html;
                        } else {
                            echo '<span class="text-danger">No Record found.</span>';
                        }
                    }
                }            
            }
        }
        
        public function OrderbookInfo($Id) 
        {
            if (!empty($Id)) {
                $EmployeeId = $this->session->userdata('EmployeeId');
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
                $CompBranchId = $this->session->userdata('CompBranchId');
                $FinancialYear = $this->session->userdata('financial_year');
                
                $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
                $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
                $OrderBookWhere = ['Id' => $Id, 'IsDeleted' => 1, 'UserId' => $UserId, 'CompId' => $CompId, 'FinancialYear' => $FinancialYear];
                $ItemsWhere = ['OrderbookId' => $Id, 'IsDeleted' => 1, 'UserId' => $UserId, 'CompId' => $CompId, 'FinancialYear' => $FinancialYear];
                $EmployeeWhere = [$EmployeeId];
                
                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $CompBranchId;
                    $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                    $OrderBookWhere['CompanyBranchId'] = $CompBranchId;
                    $ItemsWhere['CompanyBranchId'] = $CompBranchId;
                }
                
                if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                    $Where['Id'] = $EmployeeId;
                    $EmployeeIds = json_decode(get_title('employees', $Where, 'WarehouseSalesman'), true) ?: [];
                    foreach ($EmployeeIds as $key => $Id) {
                        $EmployeeWhere[] = $Id;
                    }
                }
                
                if (employee_has_perm('Orderbook', 'View', $EmployeePermissionWhere)) {
                    if ($this->db->where($OrderBookWhere)->where_in('EmployeeId', $EmployeeWhere)->get('orderbook')->num_rows()) {
                        $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                        $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                        $data['OrderBook'] = $this->db->select('*')->where($OrderBookWhere)->where_in('EmployeeId', $EmployeeWhere)->get('orderbook')->row();
                        $data['OrderBookItems'] = $this->db->select('ProductName, Price, Quantity, Total')->where($ItemsWhere)->where_in('EmployeeId', $EmployeeWhere)->order_by('Id', 'DESC')->get('orderbook_products')->result();
                        $data['page'] = "OrderbookInfo";
                        return $this->load->view('inventory_sep/Employee/Employee', $data);
                    } else {
                        $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                        return redirect(base_url() . 'Employee/OrderbookReport');
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                    return redirect(base_url() . 'Employee/Dashboard');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Orderbook id is missing.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/OrderbookReport');
            }
        }
        
        public function OrderbookEdit($OrderBookId) 
        {
            if (!empty($OrderBookId)) {
                $EmployeeId = $this->session->userdata('EmployeeId');
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
                $CompBranchId = $this->session->userdata('CompBranchId');
                $FinancialYear = $this->session->userdata('financial_year');
                
                $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
                $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
                $PartiesWhere = ['user_id' => $UserId, 'comp_id' => $CompId];
                $ProductWhere = ['user_id' => $UserId, 'comp_id' => $CompId, 'OrderBook' => 'Yes', 'status' => 1];
                $OrderBookWhere = ['Id' => $OrderBookId, 'IsDeleted' => 1, 'UserId' => $UserId, 'CompId' => $CompId, 'FinancialYear' => $FinancialYear];
                $ItemsWhere = ['OrderbookId' => $OrderBookId, 'IsDeleted' => 1, 'UserId' => $UserId, 'CompId' => $CompId, 'FinancialYear' => $FinancialYear];
                $EmployeeWhere = [$EmployeeId];
                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $CompBranchId;
                    $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                    $PartiesWhere['CompanyBranchId'] = $CompBranchId;
                    $OrderBookWhere['CompanyBranchId'] = $CompBranchId;
                    $ItemsWhere['CompanyBranchId'] = $CompBranchId;
                    $ProductWhere['CompanyBranchId'] = $CompBranchId;
                }
                if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                    $Where['Id'] = $EmployeeId;
                    $EmployeeIds = json_decode(get_title('employees', $Where, 'WarehouseSalesman'), true) ?: [];
                    foreach ($EmployeeIds as $key => $Id) {
                        $EmployeeWhere[] = $Id;
                    }
                }
                
                if (employee_has_perm('Orderbook', 'Edit', $EmployeePermissionWhere)) {
                    if ($this->db->where($OrderBookWhere)->where_in('EmployeeId', $EmployeeWhere)->get('orderbook')->num_rows()) {
                        $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                        $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                        $data['Parties'] = $this->db->select('id, name, area, contact')->where($PartiesWhere)->where_in('SalesmanId', $EmployeeWhere)->order_by('id', 'DESC')->get('parties')->result();
                        $data['Products'] = $this->db->select('id, p_name, price')->where($ProductWhere)->order_by('id', 'DESC')->get('product')->result();
                        $data['OrderBook'] = $this->db->select('Id, OrderDate, PartyId, VoucherType, SubTotal, Remark')->where($OrderBookWhere)->where_in('EmployeeId', $EmployeeWhere)->get('orderbook')->row();
                        $data['OrderBookItems'] = $this->db->select('Id, ProductId, Price, Quantity, Total')->where($ItemsWhere)->where_in('EmployeeId', $EmployeeWhere)->order_by('Id', 'DESC')->get('orderbook_products')->result();
                        $data['OrderBookId'] = $OrderBookId;
                        $data['page'] = "OrderbookEdit";
                        return $this->load->view('inventory_sep/Employee/Employee', $data);
                    } else {
                        $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                        return redirect(base_url() . 'Employee/OrderbookReport');
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                    return redirect(base_url() . 'Employee/Dashboard');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Orderbook id is missing.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/OrderbookReport');
            }
        }
        
        public function getOrderbookProduct($OrderBookId)
        {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                if (!empty($OrderBookId)) {
                    $EmployeeId = $this->session->userdata('EmployeeId');
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
                    $CompBranchId = $this->session->userdata('CompBranchId');
                    $FinancialYear = $this->session->userdata('financial_year');
                    
                    $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
                    $EmployeeWhere = [$EmployeeId];
                    $ItemsWhere = ['OrderbookId' => $OrderBookId, 'IsDeleted' => 1, 'UserId' => $UserId, 'CompId' => $CompId, 'FinancialYear' => $FinancialYear];
                    if ($this->session->userdata('BranchRole')) {
                        $Where['CompanyBranchId'] = $CompBranchId;
                        $ItemsWhere['CompanyBranchId'] = $CompBranchId;
                    }
                    if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                        $Where['Id'] = $EmployeeId;
                        $EmployeeIds = json_decode(get_title('employees', $Where, 'WarehouseSalesman'), true) ?: [];
                        foreach ($EmployeeIds as $key => $Id) {
                            $EmployeeWhere[] = $Id;
                        }
                    }
                    
                    $OrderBookItems = $this->db->select('Id, ProductName, ProductId, Price, Quantity, Total')->where($ItemsWhere)->where_in('EmployeeId', $EmployeeWhere)->order_by('Id', 'DESC')->get('orderbook_products')->result();
                    if ($OrderBookItems) {
                        echo json_encode(array('Status' => true, "Data" => $OrderBookItems));
                    } else {
                        echo json_encode(array('Status' => false, "Data" => "No Product found."));
                    }
                } else {
                    echo json_encode(array('Status' => false, "Data" => "Orderbook id missing."));
                }
            }
        }
        
        public function OrderbookUpdate($Id) 
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $EmployeeId = $this->session->userdata('EmployeeId');
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
                $CompBranchId = $this->session->userdata('CompBranchId');
                
                $CompProfileWhere = ['user_id' => $UserId, 'comp_id' => $CompId];
                $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
                $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
                $PartiesWhere = ['user_id' => $UserId, 'comp_id' => $CompId];
                $EmployeeWhere = [$EmployeeId];
                
                if ($this->session->userdata('BranchRole')) {
                    $CompProfileWhere['CompanyBranchId'] = $CompBranchId;
                }
                $FinancialYear = get_title('comp_profile', $CompProfileWhere, 'financial_year');
                
                $OrderBookWhere = ['Id' => $Id, 'IsDeleted' => 1, 'UserId' => $UserId, 'CompId' => $CompId, 'FinancialYear' => $FinancialYear];
                $OrderbookUpdateWhere = ['IsDeleted' => 1, 'UserId' => $UserId, 'CompId' => $CompId, 'FinancialYear' => $FinancialYear];
                $ItemsWhere = ['IsDeleted' => 1, 'UserId' => $UserId, 'CompId' => $CompId, 'FinancialYear' => $FinancialYear];
                
                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $CompBranchId;
                    $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                    $PartiesWhere['CompanyBranchId'] = $CompBranchId;
                    $OrderBookWhere['CompanyBranchId'] = $CompBranchId;
                    $OrderbookUpdateWhere['CompanyBranchId'] = $CompBranchId;
                    $ItemsWhere['CompanyBranchId'] = $CompBranchId;
                }
                
                if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                    $Where['Id'] = $EmployeeId;
                    $EmployeeIds = json_decode(get_title('employees', $Where, 'WarehouseSalesman'), true) ?: [];
                    foreach ($EmployeeIds as $key => $Id) {
                        $EmployeeWhere[] = $Id;
                    }
                }
                if (employee_has_perm('Orderbook', 'Edit', $EmployeePermissionWhere)) {
                    $this->form_validation->set_rules('Date', 'Date', 'trim|required');
                    $this->form_validation->set_rules('PartyId', 'Party Name', 'trim|required');
                    $this->form_validation->set_rules('VoucherType', 'Voucher Type', 'trim|required');
                    $this->form_validation->set_rules('SubTotal', 'Sub Total', 'trim|required|numeric');
                    $this->form_validation->set_rules('SalesmanId', 'Salesman', 'trim|required');
                    if ($this->form_validation->run() == false) {
                        $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                        $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                        $data['Parties'] = $this->db->select('id, name, area, contact')->where($PartiesWhere)->where_in('SalesmanId', $EmployeeWhere)->order_by('id', 'DESC')->get('parties')->result();
                        $data['OrderBook'] = $this->db->select('Id, OrderDate, OrderNumber, PartyId, SubTotal, Remark')->where($OrderBookWhere)->where_in('EmployeeId', $EmployeeWhere)->get('orderbook')->row();
                        $data['OrderBookId'] = $Id;
                        $data['page'] = "OrderbookEdit";
                        $data['error'] = validation_errors();
                        return $this->load->view('inventory_sep/Employee/Employee', $data);
                    } else {
                        $OrderbookData = [
                            'OrderDate' => $this->input->post('Date'),
                            'PartyId' => $this->input->post('PartyId'),
                            'VoucherType' => $this->input->post('VoucherType'),
                            'SalesmanId' => $this->input->post('SalesmanId'),
                            'SubTotal' => $this->input->post('SubTotal'),
                            'Remark' => $this->input->post('Remark'),
                            'Updated' => date('d-m-Y h:i:s A'),
                            'UpdatedBy' => get_title('employees', ['Id' => $EmployeeId], 'Name'),
                        ];
                        $OrderbookUpdateWhere['Id'] = $this->input->post('OrderBookId');
                        $Result = $this->db->where($OrderbookUpdateWhere)->where_in('EmployeeId', $EmployeeWhere)->update('orderbook', $OrderbookData);
                        
                        $ItemsWhere['OrderbookId'] = $this->input->post('OrderBookId');
                        $PreviewsOrderItems = $this->db->where($ItemsWhere)->where_in('EmployeeId', $EmployeeWhere)->get('orderbook_products')->result();
                        foreach ($PreviewsOrderItems as $key => $Items) {
                            $this->db->where('Id', $Items->Id)->delete('orderbook_products');
                        }

                        foreach ($this->input->post('Products') as $key => $Product) {
                            if (!empty($Product['Quantity']) && !empty($Product['ProductId'])) {
                                $OrderbookDataItems = [
                                    "OrderbookId" => $this->input->post('OrderBookId'),
                                    'OrderNumber' => get_title('orderbook', ['Id' => $this->input->post('OrderBookId')], 'OrderNumber'),
                                    "OrderDate" => $this->input->post('Date'),
                                    "ProductName" => $Product['ProductName'],
                                    "ProductId" => $Product['ProductId'],
                                    "Price" => $Product['ProductPrice'],
                                    "Quantity" => $Product['Quantity'],
                                    "Total" => $Product['Total'],
                                    "EmployeeId" => $EmployeeId,
                                    "UserId" => $UserId,
                                    "CompId" => $CompId,
                                    "CompanyBranchId" => $CompBranchId,
                                    'FinancialYear' => $FinancialYear,
                                    "Updated" => date('d-m-Y h:i:s A'),
                                    "UpdatedBy" => get_title('employees', ['Id' => $EmployeeId], 'Name')
                                ];
                                $this->user_model->saveAry('orderbook_products', $OrderbookDataItems);
                            }
                        }

                        if (!empty($Result)) {
                            $this->session->set_flashdata(['msg' => 'Record saved successfully.', 'class' => 'success']);
                            return redirect(base_url() . 'Employee/OrderbookReport');
                        } else {
                            $this->session->set_flashdata(['msg' => 'Unable to save record.', 'class' => 'danger']);
                            return redirect(base_url() . 'Employee/OrderbookEdit/' . $Id);
                        }
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                    return redirect(base_url() . 'Employee/Dashboard');
                }
            }   
        }
        
        public function OrderbookDelete($Id) 
        {
            if (!empty($Id)) {
                $EmployeeId = $this->session->userdata('EmployeeId');
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
                $CompBranchId = $this->session->userdata('CompBranchId');
                $FinancialYear = $this->session->userdata('financial_year');
                
                $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
                $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
                $OrderBookWhere = ['Id' => $Id, 'IsDeleted' => 1, 'UserId' => $UserId, 'CompId' => $CompId, 'FinancialYear' => $FinancialYear];
                $EmployeeWhere = [$EmployeeId];
                $ItemsWhere = ['OrderbookId' => $Id, 'IsDeleted' => 1, 'UserId' => $UserId, 'CompId' => $CompId, 'FinancialYear' => $FinancialYear];
                
                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $CompBranchId;
                    $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                    $OrderBookWhere['CompanyBranchId'] = $CompBranchId;
                    $ItemsWhere['CompanyBranchId'] = $CompBranchId;
                }
                
                if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                    $Where['Id'] = $EmployeeId;
                    $EmployeeIds = json_decode(get_title('employees', $Where, 'WarehouseSalesman'), true) ?: [];
                    foreach ($EmployeeIds as $key => $Id) {
                        $EmployeeWhere[] = $Id;
                    }
                }
                
                if (employee_has_perm('Orderbook', 'Delete', $EmployeePermissionWhere)) {
                    if ($this->db->where($OrderBookWhere)->where_in('EmployeeId', $EmployeeWhere)->get('orderbook')->num_rows()) {
                        if ($this->db->where($OrderBookWhere)->where_in('EmployeeId', $EmployeeWhere)->update('orderbook', ['IsDeleted' => 0])) {
                            $this->db->where($ItemsWhere)->where_in('EmployeeId', $EmployeeWhere)->update('orderbook_products', ['IsDeleted' => 0]);
                            $this->session->set_flashdata(['msg' => 'Record delete successfully.', 'class' => 'success']);
                            return redirect(base_url() . 'Employee/OrderbookReport');
                        } else {
                            $this->session->set_flashdata(['msg' => 'Unable to delete record.', 'class' => 'danger']);
                            return redirect(base_url() . 'Employee/OrderbookReport');
                        }
                    } else {
                        $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                        return redirect(base_url() . 'Employee/OrderbookReport/');
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                    return redirect(base_url() . 'Employee/Dashboard');                
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Orderbook id is missing.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/OrderbookReport');
            }
        }
        
        public function OrderbookToSaleInvoice($OrderBookId) 
        {
            if (!empty($OrderBookId)) {
                $EmployeeId = $this->session->userdata('EmployeeId');
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
                $CompBranchId = $this->session->userdata('CompBranchId');
                $FinancialYear = $this->session->userdata('financial_year');
                
                $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
                $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
                $OrderbookWhere = ['Id' => $OrderBookId, 'IsDeleted' => 1, 'UserId' => $UserId, 'CompId' => $CompId, 'FinancialYear' => $FinancialYear];
                $OrderbookProductWhere = ['OrderbookId' => $OrderBookId, 'IsDeleted' => 1, 'UserId' => $UserId, 'CompId' => $CompId, 'FinancialYear' => $FinancialYear];
                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $CompBranchId;
                    $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                    $OrderbookWhere['CompanyBranchId'] = $CompBranchId;
                    $OrderbookProductWhere['CompanyBranchId'] = $CompBranchId;
                }
                
                if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                    $Where['Id'] = $EmployeeId;
                    $EmployeeIds = json_decode(get_title('employees', $Where, 'WarehouseSalesman'), true) ?: [];
                    foreach ($EmployeeIds as $key => $Id) {
                        $EmployeeWhere[] = $Id;
                    }
                }

                if (employee_has_perm('Sales', 'Create', $EmployeePermissionWhere)) {
                    if ($this->db->where($OrderbookWhere)->get('orderbook')->num_rows()) {
                        $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                        $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                        $data['OrderbookInfo'] = $this->db->where($OrderbookWhere)->where_in('SalesmanId', $EmployeeWhere)->get('orderbook')->row();
                        $data['OrderBookId'] = $OrderBookId;
                        $data['page'] = 'inventory_sep/OrderForm/OrderbookToSaleInvoice';
                        return $this->load->view('Employees', $data);
                    } else {
                        $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                        return redirect(base_url() . 'Employee/OrderbookReport');
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                    return redirect(base_url() . 'Employee/Dashboard');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Orderbook id missing.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/OrderbookReport');
            }
        }
        
        public function OrderbookToEstimateInvoice($OrderBookId) 
        {
            if (!empty($OrderBookId)) {
                $EmployeeId = $this->session->userdata('EmployeeId');
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
                $CompBranchId = $this->session->userdata('CompBranchId');
                $FinancialYear = $this->session->userdata('financial_year');
                
                $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
                $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
                $OrderbookWhere = ['Id' => $OrderBookId, 'IsDeleted' => 1, 'UserId' => $UserId, 'CompId' => $CompId, 'FinancialYear' => $FinancialYear];
                $OrderbookProductWhere = ['OrderbookId' => $OrderBookId, 'IsDeleted' => 1, 'UserId' => $UserId, 'CompId' => $CompId, 'FinancialYear' => $FinancialYear];
                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $CompBranchId;
                    $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                    $OrderbookWhere['CompanyBranchId'] = $CompBranchId;
                    $OrderbookProductWhere['CompanyBranchId'] = $CompBranchId;
                }
                
                if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                    $Where['Id'] = $EmployeeId;
                    $EmployeeIds = json_decode(get_title('employees', $Where, 'WarehouseSalesman'), true) ?: [];
                    foreach ($EmployeeIds as $key => $Id) {
                        $EmployeeWhere[] = $Id;
                    }
                }

                if (employee_has_perm('Estimates', 'Create', $EmployeePermissionWhere)) {
                    if ($this->db->where($OrderbookWhere)->get('orderbook')->num_rows()) {
                        $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                        $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                        $data['OrderbookInfo'] = $this->db->where($OrderbookWhere)->where_in('SalesmanId', $EmployeeWhere)->get('orderbook')->row();
                        $data['OrderBookId'] = $OrderBookId;
                        $data['page'] = 'inventory_sep/OrderForm/OrderbookToEstimateInvoice';
                        return $this->load->view('Employees', $data);
                    } else {
                        $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                        return redirect(base_url() . 'Employee/OrderbookReport');
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                    return redirect(base_url() . 'Employee/Dashboard');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Orderbook id missing.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/OrderbookReport');
            }
        }
        
        // Sales //
        public function Sales()
        {
            $EmployeeId = $this->session->userdata('EmployeeId');
            $UserId = $this->session->userdata('UserId');
            $CompId = $this->session->userdata('CompId');
            $CompBranchId = $this->session->userdata('CompBranchId');
            $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
            $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $CompBranchId;
                $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
            }
            if (employee_has_perm('Sales', 'Create', $EmployeePermissionWhere)) {
                $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                $data['page'] = 'inventory_sep/sale/index';
                return $this->load->view('Employees', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/Dashboard');
            }
        }
        
        public function SalesReport()
        {
            $EmployeeId = $this->session->userdata('EmployeeId');
            $UserId = $this->session->userdata('UserId');
            $CompId = $this->session->userdata('CompId');
            $CompBranchId = $this->session->userdata('CompBranchId');
            $FinancialYear = $this->session->userdata('financial_year');
            
            if ($this->input->get('fromDate') != "" && $this->input->get('toDate') != "") {
                $from = $this->input->get('fromDate');
                $to = $this->input->get('toDate');
            } else {
                $from = date('Y-m-d');
                $to = date('Y-m-d');
            }
            
            $SalesWhere = ['invoiceDate >=' => $from, 'invoiceDate <=' => $to, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1, 'financial_year' => $FinancialYear];
            $EmployeeWhere = [$EmployeeId];
            $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
            $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
            if ($this->session->userdata('BranchRole')) {
                $SalesWhere['CompanyBranchId'] = $CompBranchId;
                $Where['CompanyBranchId'] = $CompBranchId;
                $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
            }
            if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                $Where['Id'] = $EmployeeId;
                $EmployeeIds = json_decode(get_title('employees', $Where, 'WarehouseSalesman'), true) ?: [];
                foreach ($EmployeeIds as $key => $Id) {
                    $EmployeeWhere[] = $Id;
                }
            }
            
            if (!empty($this->input->get('salesman'))) {
                $salesmanFilter = input_get_array('salesman');
                if (!in_array('All', $salesmanFilter)) {
                    $SalesmanId = $salesmanFilter;
                } else {
                    $SalesmanId = $EmployeeWhere;
                } 
            } else {
                $SalesmanId = $EmployeeWhere;
            }
            
            if (!empty($this->input->get('Party'))) {
                if ($this->input->get('Party') != 'All') {
                    $SalesWhere['customerId'] = $this->input->get('Party');
                }
            }
            
            if ($this->input->get('Delivery') != "") {
                if ($this->input->get('Delivery') != 'All') {
                    $SalesWhere['delivery_status'] = $this->input->get('Delivery');
                }
            }
            
            if (employee_has_perm('Sales', 'View', $EmployeePermissionWhere)) {
                $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                $data['Sales'] = $this->db->where($SalesWhere)->where_in('SalesmanId', $SalesmanId)->order_by('id', 'DESC')->get('sales')->result();
                $data['page'] = "SalesReport";
                return $this->load->view('inventory_sep/Employee/Employee', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/Dashboard');
            }
        }
        
        public function SalesReportGrid()
        {
            $EmployeeId = $this->session->userdata('EmployeeId');
            $UserId = $this->session->userdata('UserId');
            $CompId = $this->session->userdata('CompId');
            $CompBranchId = $this->session->userdata('CompBranchId');
            
            $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
            $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $CompBranchId;
                $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
            }            
            
            if (employee_has_perm('Sales', 'View', $EmployeePermissionWhere)) {
                $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                $data['page'] = "SalesReportGrid";
                return $this->load->view('inventory_sep/Employee/Employee', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/Dashboard');
            }
        }
        
        public function SalesReportGridAjax() 
        {
            if ($this->input->is_ajax_request()) {
                $Limit = $this->input->post('Limit');
                $Start = $this->input->post('Start');
                $PartyName = $this->input->post('PartyName');
                $EmployeeId = $this->session->userdata('EmployeeId');
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
                $CompBranchId = $this->session->userdata('CompBranchId');
                $FinancialYear = $this->session->userdata('financial_year');

                if ($this->input->post('fromDate') != "" && $this->input->post('toDate') != "") {
                    $from = $this->input->post('fromDate');
                    $to = $this->input->post('toDate');
                } else {
                    $from = date('Y-m-d');
                    $to = date('Y-m-d');
                }

                $SalesWhere = ['invoiceDate >=' => $from, 'invoiceDate <=' => $to, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1, 'financial_year' => $FinancialYear];
                $EmployeeWhere = [$EmployeeId];
                $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
                $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $SalesWhere['CompanyBranchId'] = $CompBranchId;
                    $Where['CompanyBranchId'] = $CompBranchId;
                    $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                }
                if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                    $Where['Id'] = $EmployeeId;
                    $EmployeeIds = json_decode(get_title('employees', $Where, 'WarehouseSalesman'), true) ?: [];
                    foreach ($EmployeeIds as $key => $Id) {
                        $EmployeeWhere[] = $Id;
                    }
                }

                if (!empty($this->input->post('salesman'))) {
                    $salesmanFilter = (array) ($this->input->post('salesman') ?: []);
                    if (!in_array('All', $salesmanFilter)) {
                        $SalesmanId = $salesmanFilter;
                    } else {
                        $SalesmanId = $EmployeeWhere;
                    } 
                } else {
                    $SalesmanId = $EmployeeWhere;
                }

                if (!empty($this->input->post('Party'))) {
                    if ($this->input->post('Party') != 'All') {
                        $SalesWhere['customerId'] = $this->input->post('Party');
                    }
                }

                if (employee_has_perm('Sales', 'View', $EmployeePermissionWhere)) {
                    $EmployeePermission = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                    if (isset($PartyName) && !empty($PartyName)) {
                        $this->db->like('customerName', $PartyName);
                    }
                    $this->db->where($SalesWhere);
                    $this->db->where_in('SalesmanId', $SalesmanId);
                    $this->db->order_by('id', 'DESC');
                    $this->db->limit($Limit, $Start);
                    $Sales = $this->db->get('sales')->result();

                    if ($this->input->post('estimate') == "") {
                        if ($Sales) {
                            $Html = "";
                            foreach ($Sales as $key => $row) {
                                $sql = "SELECT invoice_id, paymentmode, cashDiscountAmount, ChequeStatus FROM payment WHERE invoice_id IS NOT NULL AND JSON_VALID(invoice_id) AND JSON_CONTAINS(invoice_id, '[\"$row->id\"]') AND transaction_type = 'DR'";
                                $query = $this->db->query($sql);
                                $Payment = $query->row();
                                $PaymentInfo = $Payment ? $Payment : $this->db->select('paymentmode, ChequeStatus')->where('invoice_id', $row->id)->where('transaction_type', 'DR')->get('payment')->row();
                                $PartyInfo = $this->db->select('name, contact, gst, area, SalesmanId')->where('id', $row->customerId)->get('parties')->row();
                                
                                $PartyArea = $PartyInfo->area ? '(' . $PartyInfo->area . ')' : '';
                                
                                $InvoiceLink =    "<a href='" . base_url() . "Employee/PrintSale/" . $row->id . "' class='nav-link' target='_blank' title='Print'>"
                                                . "Invoice Number "
                                                . "<span class='float-right text-primary'>"
                                                . $row->invoiceNumber
                                                . "</span>"
                                                . "</a>";
                                
                                if ($row->paid_amount == $row->total) {
                                    if (get_title('payment_mode', ['id' => $PaymentInfo->paymentmode], 'payment_method') == "Cheque") {
                                        if ($PaymentInfo->ChequeStatus == 0) {
                                            $PaymentSatus = "<span class='float-right badge bg-maroon'>UnPaid/PDC</span>";
                                        } elseif ($PaymentInfo->ChequeStatus == 1) {
                                            $PaymentSatus = "<span class='float-right badge bg-success'>Paid</span>";
                                        } 
                                    } else {
                                        $PaymentSatus = "<span class='float-right badge bg-success'>Paid</span>";
                                    } 
                                } elseif ($row->paid_amount == NULL || $row->paid_amount == 0) {
                                    if (employee_perm($EmployeePermission, 'Payments', 'Create')) {
                                        $PaymentSatus = "<a href='" . base_url() . "Employee/Payments/" . $row->id . "' class='float-right badge bg-danger'>"
                                                      . "Unpaid"
                                                      . "</a>";
                                    } else {
                                        $PaymentSatus = "<span class='float-right badge bg-danger'>Unpaid</span>";
                                    } 
                                } else {    
                                    if (employee_perm($EmployeePermission, 'Payments', 'Create')) {
                                        if (get_title('payment_mode', ['id' => $PaymentInfo->paymentmode], 'payment_method') == "Cheque") {
                                            if ($PaymentInfo->ChequeStatus == 0) {
                                                $PaymentSatus = "<span class='float-right badge bg-maroon'>Unpaid/PDC</span>";
                                            } elseif ($PaymentInfo->ChequeStatus == 1) {
                                                $PaymentSatus = "<a href='" . base_url() . "Employee/Payments/" . $row->id . "' class='float-right badge bg-warning'>"
                                                              . "Partial"
                                                              . "</a>";
                                            }
                                        } else {
                                            $PaymentSatus = "<a href='" . base_url() . "Employee/Payments/" . $row->id . "' class='float-right badge bg-warning'>"
                                                          . "Partial"
                                                          . "</a>";
                                        }
                                    } else {
                                        if (get_title('payment_mode', ['id' => $PaymentInfo->paymentmode], 'payment_method') == "Cheque") {
                                            if ($PaymentInfo->ChequeStatus == 0) {
                                                $PaymentSatus = "<span class='float-right badge bg-maroon'>Unpaid/PDC</span>";
                                            } elseif ($PaymentInfo->ChequeStatus == 1) {
                                                $PaymentSatus = "<span class='float-right badge bg-warning'>Partial</span>";
                                            }
                                        } else {
                                            $PaymentSatus = "<span class='float-right badge bg-warning'>Partial</span>";
                                        }
                                    }
                                }
                                
                                $DeliveryStatus = $row->delivery_status == 1 ? "Yes" : "No";
                                
                                if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == 'Supervisor') {
                                    $SalesmanName = "<li class='nav-item'>"
                                                  . "<a href='javascript:void(0);' class='nav-link'>"
                                                  . "Salesman "
                                                  . "<span class='float-right'>"
                                                  . get_title('employees', ['Id' => $PartyInfo->SalesmanId], 'Name')
                                                  . "</span>"
                                                  . "</a>"
                                                  . "</li>";
                                } else {
                                    $SalesmanName = "";
                                }
                                
                                if (employee_perm($EmployeePermission, 'SalesDeliveries', 'Edit')) {
                                    $DeliveryInfo = "<a href='javascript:void(0);' onclick='onDeliveryInfoHandler(" . $row->id . ");' title=Delivery'>"
                                                  . "<i class='fa fa-bus text-danger'></i>"
                                                  . "</a>";
                                } else {
                                    $DeliveryInfo = "";
                                }
                                
                                if (employee_perm($EmployeePermission, 'Sales', 'Edit')) {
                                    $SaleEdit = "<a href='" . base_url() . "Employee/SaleEdit/" . $row->id . "' title='Edit'>"
                                              . "<i class='fa fa-edit text-success'></i>"
                                              . "</a>";
                                } else {
                                    $SaleEdit = "";
                                } 
                                
                                if (employee_perm($EmployeePermission, 'PaymentsRequest', 'Create')) {
                                    if ($row->paid_amount != $row->total) {
                                        $PaymentsRequest = "<a href='javascript:void(0);' onclick='PaymentRequestForm(" . $row->id . ");' title='Payment Request'>"
                                                         . "<i class='fa fa-money text-danger'></i>"
                                                         . "</a>";
                                    } else {
                                        $PaymentsRequest = "";
                                    }
                                } else {
                                    $PaymentsRequest = "";
                                }
                                
                                $Print = "<a href='" . base_url() . "Employee/PrintSale/" . $row->id . "' target='_blank' title='Print'>"
                                       . "<i class='fa fa-print text-primary'></i>"
                                       . "</a>";

                                if (employee_perm($EmployeePermission, 'Sales', 'Create')) {
                                    $CopySale = "<a href='" . base_url() . "Employee/CopySale/" . $row->id . "' title='Copy Invoice' target='_blank'>"
                                              . "<i class='fa fa-copy text-navy'></i>"
                                              . "</a>";
                                } else {
                                    $CopySale = "";
                                }

                                $DeliveryChat = "<a href='javascript:void(0);' onclick='onDeliveryChatHandler(" . $row->id . ");' title='Delivery Chat'>"
                                              . "<i class='fa fa-comment text-fuchsia'></i>"
                                              . "</a>";
                                
                                if (employee_perm($EmployeePermission, 'Sales', 'Delete')) {
                                    $DeleteSale = "<a href='" . base_url() . "Employee/DeleteSale/" . $row->id . "' title='Delete' onclick='return confirm(\"Are you sure want to delete this record!\");'>"
                                                . "<i class='fa fa-trash text-danger'></i>"
                                                . "</a>";
                                } else {
                                    $DeleteSale = "";
                                }
                                
                                $Html   .=  '<div class="col-md-3 col-sm-3 col-lg-3 col-xl-3 col-xxl-3 col-xs-3 pb-2">'
                                        .   '<div class="card card-widget widget-user-2 shadow-sm">'
                                        .   '<div class="widget-user-header bg-maroon">'
                                        .   '<h3 class="widget-user-username">'
                                        .   $PartyInfo->name
                                        .   '<small class="text-white">'
                                        .   $PartyArea
                                        .   '</small>'
                                        .   '</h3>'
                                        .   '<h5 class="widget-user-desc">'
                                        .   $PartyInfo->contact
                                        .   '</h5>'
                                        .   '<span class="widget-user-desc text-white">'
                                        .   $PartyInfo->gst
                                        .   '</span>'
                                        .   '</div>'
                                        .   '<div class="card-body p-0">'
                                        .   '<ul class="nav flex-column">'
                                        .   '<li class="nav-item">'
                                        .   $InvoiceLink
                                        .   '</li>'
                                        .   '<li class="nav-item">'
                                        .   '<a href="javascript:void(0);" class="nav-link">'
                                        .   'Invoice Date '
                                        .   '<span class="float-right">'
                                        .   date("d-m-Y", strtotime($row->invoiceDate))
                                        .   '</span>'
                                        .   '</a>'
                                        .   '</li>'
                                        .   '<li class="nav-item">'
                                        .   '<a href="javascript:void(0);" class="nav-link">'
                                        .   'Total '
                                        .   '<span class="float-right">'
                                        .   '<i class="fa fa-inr"></i> ' . number_format($row->total, 2)
                                        .   '</span>'
                                        .   '</a>'
                                        .   '</li>'
                                        .   '<li class="nav-item">'
                                        .   '<a href="javascript:void(0);" class="nav-link">'
                                        .   'Paid Amount '
                                        .   '<span class="float-right">'
                                        .   '<i class="fa fa-inr"></i> ' . number_format($row->paid_amount, 2)
                                        .   '</span>'
                                        .   '</a>'
                                        .   '</li>'
                                        .   '<li class="nav-item">'
                                        .   '<a href="javascript:void(0);" class="nav-link">'
                                        .   'Balance '
                                        .   '<span class="float-right">'
                                        .   '<i class="fa fa-inr"></i> ' . number_format(($row->total - $row->paid_amount), 2)
                                        .   '</span>'
                                        .   '</a>'
                                        .   '</li>'
                                        .   '<li class="nav-item">'
                                        .   '<div class="nav-link">'
                                        .   'Payment '
                                        .   $PaymentSatus
                                        .   '</div>'
                                        .   '</li>'
                                        .   '<li class="nav-item">'
                                        .   '<a href="javascript:void(0);" class="nav-link">'
                                        .   'Delivery '
                                        .   '<span class="float-right">'
                                        .   $DeliveryStatus
                                        .   '</span>'
                                        .   '</a>'
                                        .   '</li>'
                                        .   $SalesmanName
                                        .   '</ul>'
                                        .   '</div>'
                                        .   '<div class="card-footer p-2 text-center">'
                                        .   $DeliveryInfo
                                        .   '&nbsp;&nbsp;'
                                        .   $SaleEdit
                                        .   '&nbsp;&nbsp;'
                                        .   $PaymentsRequest
                                        .   '&nbsp;&nbsp;'
                                        .   $Print
                                        .   '&nbsp;&nbsp;'
                                        .   $CopySale
                                        .   '&nbsp;&nbsp;'
                                        .   $DeliveryChat
                                        .   '&nbsp;&nbsp;'
                                        .   $DeleteSale
                                        .   '</div>'
                                        .   '</div>'
                                        .   '</div>';
                            }
                            if ($Html) {
                                echo $Html;
                            } else {
                                echo '<span class="text-danger">No Record found.</span>';
                            }
                        }
                    } elseif ($this->input->post('estimate') == true) {
                        $salesman = [];
                        foreach ($this->input->post('salesman') as $key => $wdata) {
                            $salesman[] = $wdata;
                        }

                        $salesmans = json_decode(get_title('employees', ['Id' => $EmployeeId, 'IsDeleted' => 1], 'WarehouseSalesman'), true) ?: [];

                        $this->db->select('id, invoiceNumber, invoiceDate, customerId, customerMobile, gstNo, voucherType, taxableAmount, totalTax, total, type, paid_amount, delivery_status, SalesmanId');
                        $this->db->from('sales');
                        if (isset($PartyName) && !empty($PartyName)) {
                            $this->db->like('customerName', $PartyName);
                        }
                        if (!empty($this->input->post('salesman'))) {
                            if (!in_array('All', $salesman)) {
                                $this->db->where_in('SalesmanId', $salesman);
                            } else {
                                $this->db->where_in('SalesmanId', $salesmans);
                            }
                        } else {
                            if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                                $this->db->where_in('SalesmanId', $salesmans);
                            } else {
                                $this->db->where('SalesmanId', $EmployeeId);
                            }
                        }
                        if (!empty($this->input->post('Party'))) {
                            if ($this->input->post('Party') != 'All') {
                                $this->db->where('customerId', $this->input->post('Party'));
                            }
                        }
                        $this->db->where('invoiceDate >=', $from);
                        $this->db->where('invoiceDate <=', $to);
                        $this->db->where('user_id', $UserId);
                        $this->db->where('comp_id', $CompId);
                        if ($this->session->userdata('BranchRole')) {
                            $this->db->where('CompanyBranchId', $CompBranchId);
                        }
                        $this->db->where('status', 1);
                        $this->db->where('financial_year', $FinancialYear);
                        $query1 = $this->db->get_compiled_select();
                                            
                        $this->db->select('id, invoiceNumber, invoiceDate, customerId, customerMobile, gstNo, voucherType, taxableAmount, totalTax, total, type, paid_amount, delivery_status, SalesmanId');
                        $this->db->from('estimates');
                        if (isset($PartyName) && !empty($PartyName)) {
                            $this->db->like('customerName', $PartyName);
                        }
                        if (!empty($this->input->post('salesman'))) {
                            if (!in_array('All', $salesman)) {
                                $this->db->where_in('SalesmanId', $salesman);
                            } else {
                                $this->db->where_in('SalesmanId', $salesmans);
                            }
                        } else {
                            $this->db->where('SalesmanId', $EmployeeId);
                        }
                        if (!empty($this->input->post('Party'))) {
                            if ($this->input->post('Party') != 'All') {
                                $this->db->where('customerId', $this->input->post('Party'));
                            }
                        }
                        $this->db->where('invoiceDate >=', $from);
                        $this->db->where('invoiceDate <=', $to);
                        $this->db->where('user_id', $UserId);
                        $this->db->where('comp_id', $CompId);
                        if ($this->session->userdata('BranchRole')) {
                            $this->db->where('CompanyBranchId', $CompBranchId);
                        }
                        $this->db->where('status', 1);
                        $this->db->where('financial_year', $FinancialYear);
                        $query2 = $this->db->get_compiled_select();
                                        
                        $allsales = $this->db->query($query1 . " UNION " . $query2 . " ORDER BY invoiceDate DESC LIMIT " . $Start . ", " . $Limit)->result();
                        if (!empty($allsales)) {
                            $Html = "";
                            foreach ($allsales as $key => $data) {
                                $Payment = $this->db->select('paymentmode, ChequeStatus')->where("JSON_CONTAINS(invoice_id, '\"$data->id\"')")->where('transaction_type', 'DR')->get('payment')->row();
                                $PaymentInfo = $Payment ? $Payment : $this->db->select('paymentmode, ChequeStatus')->where('invoice_id', $data->id)->where('transaction_type', 'DR')->get('payment')->row();
                                $PartyInfo = $this->db->select('name, contact, gst, area, SalesmanId')->where('id', $data->customerId)->get('parties')->row();
                                
                                $PartyArea = $PartyInfo->area ? '(' . $PartyInfo->area . ')' : '';
                                
                                if ($data->type == "Sale") {
                                    $InvoiceLink =    "<a href='" . base_url() . "Employee/PrintSale/" . $data->id . "' class='nav-link' target='_blank' title='Print'>"
                                                    . "Invoice Number "
                                                    . "<span class='float-right text-primary'>"
                                                    . $data->invoiceNumber
                                                    . "</span>"
                                                    . "</a>";
                                } elseif ($data->type == "Estimate") {
                                    $InvoiceLink =    "<a href='" . base_url() . "Employee/PrintEstimate/" . $data->id . "' class='nav-link' target='_blank' title='Print'>"
                                                    . "Invoice Number "
                                                    . "<span class='float-right text-primary'>"
                                                    . $data->invoiceNumber
                                                    . "</span>"
                                                    . "</a>";                                    
                                }
                                
                                if ($data->paid_amount == $data->total) {
                                    if (get_title('payment_mode', ['id' => $PaymentInfo->paymentmode], 'payment_method') == "Cheque") {
                                        if ($PaymentInfo->ChequeStatus == 0) {
                                            $PaymentSatus = "<span class='float-right badge bg-maroon'>UnPaid/PDC</span>";
                                        } elseif ($PaymentInfo->ChequeStatus == 1) {
                                            $PaymentSatus = "<span class='float-right badge bg-success'>Paid</span>";
                                        } 
                                    } else {
                                        $PaymentSatus = "<span class='float-right badge bg-success'>Paid</span>";
                                    } 
                                } elseif ($data->paid_amount == NULL || $data->paid_amount == 0) {
                                    if (employee_perm($EmployeePermission, 'Payments', 'Create')) {
                                        $PaymentSatus = "<a href='" . base_url() . "Employee/Payments/" . $data->id . "' class='float-right badge bg-danger'>"
                                                      . "Unpaid"
                                                      . "</a>";
                                    } else {
                                        $PaymentSatus = "<span class='float-right badge bg-danger'>Unpaid</span>";
                                    } 
                                } else {    
                                    if (employee_perm($EmployeePermission, 'Payments', 'Create')) {
                                        if (get_title('payment_mode', ['id' => $PaymentInfo->paymentmode], 'payment_method') == "Cheque") {
                                            if ($PaymentInfo->ChequeStatus == 0) {
                                                $PaymentSatus = "<span class='float-right badge bg-maroon'>Unpaid/PDC</span>";
                                            } elseif ($PaymentInfo->ChequeStatus == 1) {
                                                $PaymentSatus = "<a href='" . base_url() . "Employee/Payments/" . $data->id . "' class='float-right badge bg-warning'>"
                                                              . "Partial"
                                                              . "</a>";
                                            }
                                        } else {
                                            $PaymentSatus = "<a href='" . base_url() . "Employee/Payments/" . $data->id . "' class='float-right badge bg-warning'>"
                                                          . "Partial"
                                                          . "</a>";
                                        }
                                    } else {
                                        if (get_title('payment_mode', ['id' => $PaymentInfo->paymentmode], 'payment_method') == "Cheque") {
                                            if ($PaymentInfo->ChequeStatus == 0) {
                                                $PaymentSatus = "<span class='float-right badge bg-maroon'>Unpaid/PDC</span>";
                                            } elseif ($PaymentInfo->ChequeStatus == 1) {
                                                $PaymentSatus = "<span class='float-right badge bg-warning'>Partial</span>";
                                            }
                                        } else {
                                            $PaymentSatus = "<span class='float-right badge bg-warning'>Partial</span>";
                                        }
                                    }
                                }
                                
                                $DeliveryStatus = $data->delivery_status == 1 ? "Yes" : "No";
                                
                                if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == 'Supervisor') {
                                    $SalesmanName = "<li class='nav-item'>"
                                                  . "<a href='javascript:void(0);' class='nav-link'>"
                                                  . "Salesman "
                                                  . "<span class='float-right'>"
                                                  . get_title('employees', ['Id' => $PartyInfo->SalesmanId], 'Name')
                                                  . "</span>"
                                                  . "</a>"
                                                  . "</li>";
                                } else {
                                    $SalesmanName = "";
                                }
                                
                                if (employee_perm($EmployeePermission, 'SalesDeliveries', 'Edit')) {
                                    $DeliveryInfo = "<a href='javascript:void(0);' onclick='onDeliveryInfoHandler(" . $data->id . ");' title=Delivery'>"
                                                  . "<i class='fa fa-bus text-danger'></i>"
                                                  . "</a>";
                                } else {
                                    $DeliveryInfo = "";
                                }
                                
                                if (employee_perm($EmployeePermission, 'Sales', 'Edit') && $data->type == 'Sale') {
                                    $SaleEdit = "<a href='" . base_url() . "Employee/SaleEdit/" . $data->id . "' title='Edit'>"
                                              . "<i class='fa fa-edit text-success'></i>"
                                              . "</a>";
                                } elseif (employee_perm($EmployeePermission, 'Estimates', 'Edit') && $data->type == 'Estimate') {
                                    $SaleEdit = "<a href='" . base_url() . "Employee/EstimateEdit/" . $data->id . "' title='Edit'>"
                                              . "<i class='fa fa-edit text-success'></i>"
                                              . "</a>";
                                } 
                                
                                if (employee_perm($EmployeePermission, 'PaymentsRequest', 'Create')) {
                                    if ($data->paid_amount != $data->total) {
                                        $PaymentsRequest = "<a href='javascript:void(0);' onclick='PaymentRequestForm(" . $data->id . ");' title='Payment Request'>"
                                                         . "<i class='fa fa-money text-danger'></i>"
                                                         . "</a>";
                                    } else {
                                        $PaymentsRequest = "";
                                    }
                                } else {
                                    $PaymentsRequest = "";
                                }
                                
                                if ($data->type == 'Sale') {
                                    $Print = "<a href='" . base_url() . "Employee/PrintSale/" . $data->id . "' target='_blank' title='Print'>"
                                           . "<i class='fa fa-print text-primary'></i>"
                                       . "</a>";
                                } elseif ($data->type == 'Estimate') {
                                    $Print = "<a href='" . base_url() . "Employee/PrintEstimate/" . $data->id . "' target='_blank' title='Print'>"
                                           . "<i class='fa fa-print text-primary'></i>"
                                       . "</a>";
                                }

                                if (employee_perm($EmployeePermission, 'Sales', 'Create') && $data->type == 'Sale') {
                                    $CopySale = "<a href='" . base_url() . "Employee/CopySale/" . $data->id . "' title='Copy Invoice' target='_blank'>"
                                              . "<i class='fa fa-copy text-navy'></i>"
                                              . "</a>";
                                } else {
                                    $CopySale = "";
                                }

                                $DeliveryChat = "<a href='javascript:void(0);' onclick='onDeliveryChatHandler(" . $data->id . ");' title='Delivery Chat'>"
                                              . "<i class='fa fa-comment text-fuchsia'></i>"
                                              . "</a>";
                                
                                if (employee_perm($EmployeePermission, 'Sales', 'Delete') && $data->type == 'Sale') {
                                    $DeleteSale = "<a href='" . base_url() . "Employee/DeleteSale/" . $data->id . "' title='Delete' onclick='return confirm(\"Are you sure want to delete this record!\");'>"
                                                . "<i class='fa fa-trash text-danger'></i>"
                                                . "</a>";
                                } elseif (employee_perm($EmployeePermission, 'Estimates', 'Delete') && $data->type == 'Estimate') {
                                    $DeleteSale = "<a href='" . base_url() . "Employee/DeleteEstimate/" . $data->id . "' title='Delete' onclick='return confirm(\"Are you sure want to delete this record!\");'>"
                                                . "<i class='fa fa-trash text-danger'></i>"
                                                . "</a>";
                                }
                                
                                $Html   .=  '<div class="col-md-3 col-sm-3 col-lg-3 col-xl-3 col-xxl-3 col-xs-3 pb-2">'
                                        .   '<div class="card card-widget widget-user-2 shadow-sm">'
                                        .   '<div class="widget-user-header bg-maroon">'
                                        .   '<h3 class="widget-user-username">'
                                        .   $PartyInfo->name
                                        .   '<small class="text-white">'
                                        .   $PartyArea
                                        .   '</small>'
                                        .   '</h3>'
                                        .   '<h5 class="widget-user-desc">'
                                        .   $PartyInfo->contact
                                        .   '</h5>'
                                        .   '<span class="widget-user-desc text-white">'
                                        .   $PartyInfo->gst
                                        .   '</span>'
                                        .   '</div>'
                                        .   '<div class="card-body p-0">'
                                        .   '<ul class="nav flex-column">'
                                        .   '<li class="nav-item">'
                                        .   '<a href="javascript:void(0);" class="nav-link">'
                                        .   'Invoice Type '
                                        .   '<span class="float-right">'
                                        .   $data->type
                                        .   '</span>'
                                        .   '</a>'
                                        .   '</li>'
                                        .   '<li class="nav-item">'
                                        .   $InvoiceLink
                                        .   '</li>'
                                        .   '<li class="nav-item">'
                                        .   '<a href="javascript:void(0);" class="nav-link">'
                                        .   'Invoice Date '
                                        .   '<span class="float-right">'
                                        .   date("d-m-Y", strtotime($data->invoiceDate))
                                        .   '</span>'
                                        .   '</a>'
                                        .   '</li>'
                                        .   '<li class="nav-item">'
                                        .   '<a href="javascript:void(0);" class="nav-link">'
                                        .   'Total '
                                        .   '<span class="float-right">'
                                        .   '<i class="fa fa-inr"></i> ' . number_format($data->total, 2)
                                        .   '</span>'
                                        .   '</a>'
                                        .   '</li>'
                                        .   '<li class="nav-item">'
                                        .   '<a href="javascript:void(0);" class="nav-link">'
                                        .   'Paid Amount '
                                        .   '<span class="float-right">'
                                        .   '<i class="fa fa-inr"></i> ' . number_format($data->paid_amount, 2)
                                        .   '</span>'
                                        .   '</a>'
                                        .   '</li>'
                                        .   '<li class="nav-item">'
                                        .   '<a href="javascript:void(0);" class="nav-link">'
                                        .   'Balance '
                                        .   '<span class="float-right">'
                                        .   '<i class="fa fa-inr"></i> ' . number_format(($data->total - $data->paid_amount), 2)
                                        .   '</span>'
                                        .   '</a>'
                                        .   '</li>'
                                        .   '<li class="nav-item">'
                                        .   '<div class="nav-link">'
                                        .   'Payment '
                                        .   $PaymentSatus
                                        .   '</div>'
                                        .   '</li>'
                                        .   '<li class="nav-item">'
                                        .   '<a href="javascript:void(0);" class="nav-link">'
                                        .   'Delivery '
                                        .   '<span class="float-right">'
                                        .   $DeliveryStatus
                                        .   '</span>'
                                        .   '</a>'
                                        .   '</li>'
                                        .   $SalesmanName
                                        .   '</ul>'
                                        .   '</div>'
                                        .   '<div class="card-footer p-2 text-center">'
                                        .   $DeliveryInfo
                                        .   '&nbsp;&nbsp;'
                                        .   $SaleEdit
                                        .   '&nbsp;&nbsp;'
                                        .   $PaymentsRequest
                                        .   '&nbsp;&nbsp;'
                                        .   $Print
                                        .   '&nbsp;&nbsp;'
                                        .   $CopySale
                                        .   '&nbsp;&nbsp;'
                                        .   $DeliveryChat
                                        .   '&nbsp;&nbsp;'
                                        .   $DeleteSale
                                        .   '</div>'
                                        .   '</div>'
                                        .   '</div>';
                            }
                            
                            if ($Html) {
                                echo $Html;
                            } else {
                                echo '<span class="text-danger">No Record found.</span>';
                            }
                        }
                    }
                }
            }
        }
        
        public function ViewSale($Id = null)
        {
            if (isset($Id) && !empty($Id)) {
                $EmployeeId = $this->session->userdata('EmployeeId');
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
                $CompBranchId = $this->session->userdata('CompBranchId');
                $FinancialYear = $this->session->userdata('financial_year');

                $SalesWhere = ['id' => $Id, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1, 'financial_year' => $FinancialYear];
                $InvoiceProductWhere = ['invoiceID' => $Id, 'type' => 'Sale', 'user_id' => $UserId, 'comp_id' => $CompId];
                $EmployeeWhere = [$EmployeeId];
                $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
                $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $SalesWhere['CompanyBranchId'] = $CompBranchId;
                    $InvoiceProductWhere['CompanyBranchId'] = $CompBranchId;
                    $Where['CompanyBranchId'] = $CompBranchId;
                    $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                }
                if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                    $Where['Id'] = $EmployeeId;
                    $EmployeeIds = json_decode(get_title('employees', $Where, 'WarehouseSalesman'), true) ?: [];
                    foreach ($EmployeeIds as $key => $Id) {
                        $EmployeeWhere[] = $Id;
                    }
                }

                if (employee_has_perm('Sales', 'View', $EmployeePermissionWhere)) {
                    $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                    $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                    $SaleResult = $this->db->where($SalesWhere)->where_in('SalesmanId', $EmployeeWhere)->get('sales')->row();
                    $InvoiceProductResult = $this->db->where($InvoiceProductWhere)->get('invoice_product')->result();
                    $SaleResult->product = $InvoiceProductResult;
                    $data['result'] = $SaleResult;
                    $data['page'] = 'inventory_sep/sale/viewsale';
                    return $this->load->view('Employees', $data);
                } else {
                    $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                    return redirect(base_url() . 'Employee/Dashboard');
                }
            } else {
                return redirect(base_url() . 'Employee/SalesReport');
            }
        }
        
        public function PrintSale($Id = null)
        {
            if (isset($Id) && !empty($Id)) {
                $EmployeeId = $this->session->userdata('EmployeeId');
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
                $CompBranchId = $this->session->userdata('CompBranchId');
                $format =  get_title('billing_format', ['id' => $this->session->userdata('format_of_bill')], 'name');

                $SalesWhere = ['id' => $Id, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
                $InvoiceProductWhere = ['invoiceID' => $Id, 'type' => 'Sale', 'user_id' => $UserId, 'comp_id' => $CompId];
                $EmployeeWhere = [$EmployeeId];
                $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
                $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $SalesWhere['CompanyBranchId'] = $CompBranchId;
                    $InvoiceProductWhere['CompanyBranchId'] = $CompBranchId;
                    $Where['CompanyBranchId'] = $CompBranchId;
                    $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                }
                if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                    $Where['Id'] = $EmployeeId;
                    $EmployeeIds = json_decode(get_title('employees', $Where, 'WarehouseSalesman'), true) ?: [];
                    foreach ($EmployeeIds as $key => $Id) {
                        $EmployeeWhere[] = $Id;
                    }
                }

                if (employee_has_perm('Sales', 'View', $EmployeePermissionWhere)) {
                    $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                    $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                    $SaleResult = $this->db->where($SalesWhere)->where_in('SalesmanId', $EmployeeWhere)->get('sales')->row();
                    $InvoiceProductResult = $this->db->where($InvoiceProductWhere)->get('invoice_product')->result();
                    $SaleResult->product = $InvoiceProductResult;
                    $data['result'] = $SaleResult;
                                                           
                    if ($format == 'mc_formatA4') {
                        return $this->load->view('inventory_sep/sale/print/mc_formatA4', $data);
                    } else {
                        return $this->load->view('inventory_sep/sale/a5print', $data);
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                    return redirect(base_url() . 'Employee/Dashboard');
                }
            } else {
                return redirect(base_url() . 'Employee/SalesReport');
            }
        }
        
        public function downloadInvoice($Id = null)
        {
            ini_set('memory_limit', '-1');
            set_time_limit(5000);
            
            if (isset($Id) && !empty($Id)) {
                $EmployeeId = $this->session->userdata('EmployeeId');
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
                $CompBranchId = $this->session->userdata('CompBranchId');
                $format =  get_title('billing_format', ['id' => $this->session->userdata('format_of_bill')], 'name');

                $SalesWhere = ['id' => $Id, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
                $InvoiceProductWhere = ['invoiceID' => $Id, 'type' => 'Sale', 'user_id' => $UserId, 'comp_id' => $CompId];
                $EmployeeWhere = [$EmployeeId];
                $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
                $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $SalesWhere['CompanyBranchId'] = $CompBranchId;
                    $InvoiceProductWhere['CompanyBranchId'] = $CompBranchId;
                    $Where['CompanyBranchId'] = $CompBranchId;
                    $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                }
                if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                    $Where['Id'] = $EmployeeId;
                    $EmployeeIds = json_decode(get_title('employees', $Where, 'WarehouseSalesman'), true) ?: [];
                    foreach ($EmployeeIds as $key => $Id) {
                        $EmployeeWhere[] = $Id;
                    }
                }

                if (employee_has_perm('Sales', 'View', $EmployeePermissionWhere)) {
                    $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                    $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                    $SaleResult = $this->db->where($SalesWhere)->where_in('SalesmanId', $EmployeeWhere)->get('sales')->row();
                    $InvoiceProductResult = $this->db->where($InvoiceProductWhere)->get('invoice_product')->result();
                    $SaleResult->product = $InvoiceProductResult;
                    $data['result'] = $SaleResult;
                                                           
                    $fileName = str_replace(' ', '_', get_title('parties', ['id' => $SaleResult->customerId], 'name')) . '_' . date('d-m-Y', strtotime($SaleResult->invoiceDate));
                    
                    if ($format == 'mc_formatA4') {
                        $page = $this->load->view('inventory_sep/sale/print/mc_formatA4', $data, true);
                    } else {
                        $page = $this->load->view('inventory_sep/sale/a5print', $data, true);
                    }
                    
                    $this->load->library('pdf');
                    $this->pdf->loadHtml($page);
                    $this->pdf->set_option('isRemoteEnabled', TRUE);
                    $this->pdf->setPaper('A4', 'portrait');
                    $this->pdf->render();
                    $this->pdf->output();
                    $this->pdf->stream($fileName . ".pdf", array("Attachment" => true));
                } else {
                    $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                    return redirect(base_url() . 'Employee/Dashboard');
                }
            } else {
                return redirect(base_url() . 'Employee/SalesReport');
            }            
        }
        
        public function SaleEdit($Id = null)
        {
            if (isset($Id) && !empty($Id)) {
                $EmployeeId = $this->session->userdata('EmployeeId');
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
                $CompBranchId = $this->session->userdata('CompBranchId');
                $FinancialYear = $this->session->userdata('financial_year');

                $SalesWhere = ['id' => $Id, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1, 'financial_year' => $FinancialYear];
                $EmployeeWhere = [$EmployeeId];
                $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
                $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $SalesWhere['CompanyBranchId'] = $CompBranchId;
                    $Where['CompanyBranchId'] = $CompBranchId;
                    $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                }
                if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                    $Where['Id'] = $EmployeeId;
                    $EmployeeIds = json_decode(get_title('employees', $Where, 'WarehouseSalesman'), true) ?: [];
                    foreach ($EmployeeIds as $key => $Id) {
                        $EmployeeWhere[] = $Id;
                    }
                }

                if (employee_has_perm('Sales', 'Edit', $EmployeePermissionWhere)) {
                    $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                    $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                    $SaleResult = $this->db->select('id, invoiceNumber, customerId, Irn')->where($SalesWhere)->where_in('SalesmanId', $EmployeeWhere)->get('sales')->row();
                    
                    $SaleResult->einvoie_details = false;
                    if ($SaleResult->Irn != null) {
                        $SaleResult->einvoie_details = $this->user_model->getRow('e_invoice_details', ['Irn' => $SaleResult->Irn]);  
                    } 
                    $partyData = $this->user_model->getRow('parties',['id' => $SaleResult->customerId]);                    
                    
                    if ($SaleResult->voucherType != 1) {
                        if ($SaleResult->paid_amount != 0) {
                            $this->session->set_flashdata(['msg' => 'Invoice payment partial or full paid, you can not change the invoice.', 'class' => 'danger']);
                            return redirect(base_url() . 'Employee/SalesReport');
                        }
                    }
                    
                    $data['id'] = $SaleResult->id;
                    $data['partyData']= $partyData;
                    $data['invoiceNumber'] = $SaleResult->invoiceNumber;
                    $data['page'] = 'inventory_sep/sale/sale_edit';
                    return $this->load->view('Employees', $data);
                } else {
                    $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                    return redirect(base_url() . 'Employee/Dashboard');
                }
            } else {
                return redirect(base_url() . 'Employee/SalesReport');
            }
        }
        
        // From Voucher Page
        public function SaleDelete($Id) 
        {
            if (!$Id) {
                echo json_encode(['status' => false, 'msg' => 'Id not found.']);
                return;
            }
             
            $UserId = $this->session->userdata('UserId');
            $CompId = $this->session->userdata('CompId');
            if (employee_has_perm('Sales', 'Delete', ['EmployeeId' => $this->session->userdata('EmployeeId')])) {
                $SaleInfo = $this->user_model->getRow('sales', ['id' => $Id]);
                if ($SaleInfo->paid_amount != 0) {
                    echo json_encode(['status' => false, 'msg' => 'Invoice payment partial or full paid, you can not change this.']);
                    return;
                }

                $data['status'] = 0;
                $where = ['id' => $Id, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1, 'financial_year' => $this->session->userdata('financial_year')];
                if ($this->session->userdata('BranchRole')) {
                    $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                $return = $this->user_model->updateAry('sales', $data, $where);

                if ($return) {
                    $where = ['invoiceID' => $Id, 'type' => "Sale"];
                    $res = $this->user_model->getAry('invoice_product', '', '', '', $where);
                    if ($res) {
                        $statusChange['status'] = 0;
                        foreach ($res as $da) {
                            $this->user_model->updateAry('invoice_product', $statusChange, $where);
                        }
                        $this->user_model->updateAry('payment', $statusChange, ['invoice_id' => $Id, 'invoice_type' => 'Sale', 'transaction_type' => 'CR']);
                    }
                    echo json_encode(['status' => true, 'msg' => 'Sale deleted successfully.']);
                    return;
                } else {
                    echo json_encode(['status' => false, 'msg' => 'There is some issue, Please try again.']);
                    return;
                }
            } else {
                echo json_encode(['status' => false, 'msg' => 'you do not have permission.']);
                return;
            }
        }
        
        // From Sale Report Page
        public function DeleteSale($Id) 
        {
            if (!$Id) {
                $this->session->set_flashdata(['msg' => 'Id missing.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }
             
            $UserId = $this->session->userdata('UserId');
            $CompId = $this->session->userdata('CompId');
            if (employee_has_perm('Sales', 'Delete', ['EmployeeId' => $this->session->userdata('EmployeeId')])) {
                $SaleInfo = $this->user_model->getRow('sales', ['id' => $Id]);
                if ($SaleInfo->paid_amount != 0) {
                    $this->session->set_flashdata(['msg' => 'Invoice payment partial or full paid, you can not change this.', 'class' => 'danger']);
                    return redirect($_SERVER['HTTP_REFERER']);
                }

                $data['status'] = 0;
                $where = ['id' => $Id, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1, 'financial_year' => $this->session->userdata('financial_year')];
                if ($this->session->userdata('BranchRole')) {
                    $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                $return = $this->user_model->updateAry('sales', $data, $where);

                if ($return) {
                    $where = ['invoiceID' => $Id, 'type' => "Sale"];
                    $res = $this->user_model->getAry('invoice_product', '', '', '', $where);
                    if ($res) {
                        $statusChange['status'] = 0;
                        foreach ($res as $da) {
                            $this->user_model->updateAry('invoice_product', $statusChange, $where);
                        }
                        $this->user_model->updateAry('payment', $statusChange, ['invoice_id' => $Id, 'invoice_type' => 'Sale', 'transaction_type' => 'CR']);
                    }
                    $this->session->set_flashdata(['msg' => 'Sale deleted successfully.', 'class' => 'success']);
                    return redirect($_SERVER['HTTP_REFERER']);
                } else {
                    $this->session->set_flashdata(['msg' => 'There is some issue, Please try again.', 'class' => 'danger']);
                    return redirect($_SERVER['HTTP_REFERER']);
                }
            } else {
                $this->session->set_flashdata(['msg' => 'you do not have permission.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }
        }
        
        public function CopySale($Id = null)
        {
            if (isset($Id) && !empty($Id)) {
                $EmployeeId = $this->session->userdata('EmployeeId');
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
                $CompBranchId = $this->session->userdata('CompBranchId');
                $FinancialYear = $this->session->userdata('financial_year');

                $SalesWhere = ['id' => $Id, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1, 'financial_year' => $FinancialYear];
                $EmployeeWhere = [$EmployeeId];
                $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
                $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $SalesWhere['CompanyBranchId'] = $CompBranchId;
                    $Where['CompanyBranchId'] = $CompBranchId;
                    $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                }
                if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                    $Where['Id'] = $EmployeeId;
                    $EmployeeIds = json_decode(get_title('employees', $Where, 'WarehouseSalesman'), true) ?: [];
                    foreach ($EmployeeIds as $key => $Id) {
                        $EmployeeWhere[] = $Id;
                    }
                }

                if (employee_has_perm('Sales', 'Create', $EmployeePermissionWhere)) {
                    $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                    $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                    $SaleResult = $this->db->select('invoiceNumber')->where($SalesWhere)->where_in('SalesmanId', $EmployeeWhere)->get('sales')->row();
                    $data['CopiedInvoiceNumber'] = $SaleResult->invoiceNumber;
                    $data['page'] = 'inventory_sep/sale/CopySale';
                    return $this->load->view('Employees', $data);
                } else {
                    $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                    return redirect(base_url() . 'Employee/Dashboard');
                }
            } else {
                return redirect(base_url() . 'Employee/SalesReport');
            }
        }
        
        // Estimates //
        public function Estimates()
        {
            $EmployeeId = $this->session->userdata('EmployeeId');
            $UserId = $this->session->userdata('UserId');
            $CompId = $this->session->userdata('CompId');
            $CompBranchId = $this->session->userdata('CompBranchId');
            
            $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
            $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $CompBranchId;
                $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
            }
            if (employee_has_perm('Estimates', 'Create', $EmployeePermissionWhere)) {
                $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                $data['page'] = 'inventory_sep/estimate/index';
                return $this->load->view('Employees', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/Dashboard');
            }
        }
        
        public function EstimatesReport()
        {
            $EmployeeId = $this->session->userdata('EmployeeId');
            $UserId = $this->session->userdata('UserId');
            $CompId = $this->session->userdata('CompId');
            $CompBranchId = $this->session->userdata('CompBranchId');
            $FinancialYear = $this->session->userdata('financial_year');
            
            if ($this->input->get('fromDate') != "" && $this->input->get('toDate') != "") {
                $from = $this->input->get('fromDate');
                $to = $this->input->get('toDate');
            } else {
                $from = date('Y-m-d');
                $to = date('Y-m-d');
            }
            
            $EstimateWhere = ['invoiceDate >=' => $from, 'invoiceDate <=' => $to, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1, 'financial_year' => $FinancialYear];
            $EmployeeWhere = [$EmployeeId];
            $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
            $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
            if ($this->session->userdata('BranchRole')) {
                $EstimateWhere['CompanyBranchId'] = $CompBranchId;
                $Where['CompanyBranchId'] = $CompBranchId;
                $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
            }
            if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                $Where['Id'] = $EmployeeId;
                $EmployeeIds = json_decode(get_title('employees', $Where, 'WarehouseSalesman'), true) ?: [];
                foreach ($EmployeeIds as $key => $Id) {
                    $EmployeeWhere[] = $Id;
                }
            }

            if (!empty($this->input->get('salesman'))) {
                $salesmanFilter = input_get_array('salesman');
                if (!in_array('All', $salesmanFilter)) {
                    $SalesmanId = $salesmanFilter;
                } else {
                    $SalesmanId = $EmployeeWhere;
                } 
            } else {
                $SalesmanId = $EmployeeWhere;
            }
            
            if (employee_has_perm('Estimates', 'View', $EmployeePermissionWhere)) {
                $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                $data['Estimates'] = $this->db->where($EstimateWhere)->where_in('SalesmanId', $EmployeeWhere)->order_by('id', 'DESC')->get('estimates')->result();
                $data['page'] = "EstimatesReport";
                return $this->load->view('inventory_sep/Employee/Employee', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/Dashboard');
            }
        }
        
        public function EstimatesReportGrid()
        {
            $EmployeeId = $this->session->userdata('EmployeeId');
            $UserId = $this->session->userdata('UserId');
            $CompId = $this->session->userdata('CompId');
            $CompBranchId = $this->session->userdata('CompBranchId');
            
            $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
            $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $CompBranchId;
                $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
            }
            
            if (employee_has_perm('Estimates', 'View', $EmployeePermissionWhere)) {
                $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                $data['page'] = "EstimatesReportGrid";
                return $this->load->view('inventory_sep/Employee/Employee', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/Dashboard');
            }
        }
        
        public function EstimatesReportGridAjax()
        {
            if ($this->input->is_ajax_request()) {
                $Limit = $this->input->post('Limit');
                $Start = $this->input->post('Start');
                $PartyName = $this->input->post('PartyName');
                $EmployeeId = $this->session->userdata('EmployeeId');
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
                $CompBranchId = $this->session->userdata('CompBranchId');
                $FinancialYear = $this->session->userdata('financial_year');

                if ($this->input->post('fromDate') != "" && $this->input->post('toDate') != "") {
                    $from = $this->input->post('fromDate');
                    $to = $this->input->post('toDate');
                } else {
                    $from = date('Y-m-d');
                    $to = date('Y-m-d');
                }

                $EstimateWhere = ['invoiceDate >=' => $from, 'invoiceDate <=' => $to, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1, 'financial_year' => $FinancialYear];
                $EmployeeWhere = [$EmployeeId];
                $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
                $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $EstimateWhere['CompanyBranchId'] = $CompBranchId;
                    $Where['CompanyBranchId'] = $CompBranchId;
                    $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                }
                if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                    $Where['Id'] = $EmployeeId;
                    $EmployeeIds = json_decode(get_title('employees', $Where, 'WarehouseSalesman'), true) ?: [];
                    foreach ($EmployeeIds as $key => $Id) {
                        $EmployeeWhere[] = $Id;
                    }
                }

                if (!empty($this->input->post('salesman'))) {
                    $salesmanFilter = (array) ($this->input->post('salesman') ?: []);
                    if (!in_array('All', $salesmanFilter)) {
                        $SalesmanId = $salesmanFilter;
                    } else {
                        $SalesmanId = $EmployeeWhere;
                    } 
                } else {
                    $SalesmanId = $EmployeeWhere;
                }

                if (employee_has_perm('Estimates', 'View', $EmployeePermissionWhere)) {
                    $EmployeePermission = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                    if (isset($PartyName) && !empty($PartyName)) {
                        $this->db->like('customerName', $PartyName);
                    }
                    $this->db->where($EstimateWhere);
                    $this->db->where_in('SalesmanId', $SalesmanId);
                    $this->db->order_by('id', 'DESC');
                    $this->db->limit($Limit, $Start);
                    $Estimates = $this->db->get('estimates')->result();
                        
                    if ($Estimates) {
                        $Html = "";
                        foreach ($Estimates as $key => $row) {
                            $Payment = $this->db->select('paymentmode, ChequeStatus')->where("JSON_CONTAINS(invoice_id, '\"$row->id\"')")->where('transaction_type', 'DR')->get('payment')->row();
                            $PaymentInfo = $Payment ? $Payment : $this->db->select('paymentmode, ChequeStatus')->where('invoice_id', $row->id)->where('transaction_type', 'DR')->get('payment')->row();
                            $PartyInfo = $this->db->select('name, contact, gst, area, SalesmanId')->where('id', $row->customerId)->get('parties')->row();

                            $PartyArea = $PartyInfo->area ? '(' . $PartyInfo->area . ')' : '';

                            $InvoiceLink =    "<a href='" . base_url() . "Employee/PrintEstimate/" . $row->id . "' class='nav-link' target='_blank' title='Print'>"
                                            . "Invoice Number "
                                            . "<span class='float-right text-primary'>"
                                            . $row->invoiceNumber
                                            . "</span>"
                                            . "</a>";

                            if ($row->paid_amount == $row->total) {
                                if (get_title('payment_mode', ['id' => $PaymentInfo->paymentmode], 'payment_method') == "Cheque") {
                                    if ($PaymentInfo->ChequeStatus == 0) {
                                        $PaymentSatus = "<span class='float-right badge bg-maroon'>UnPaid/PDC</span>";
                                    } elseif ($PaymentInfo->ChequeStatus == 1) {
                                        $PaymentSatus = "<span class='float-right badge bg-success'>Paid</span>";
                                    } 
                                } else {
                                    $PaymentSatus = "<span class='float-right badge bg-success'>Paid</span>";
                                } 
                            } elseif ($row->paid_amount == NULL || $row->paid_amount == 0) {
                                if (employee_perm($EmployeePermission, 'Payments', 'Create')) {
                                    $PaymentSatus = "<a href='" . base_url() . "Employee/Payments/" . $row->id . "' class='float-right badge bg-danger'>"
                                                  . "Unpaid"
                                                  . "</a>";
                                } else {
                                    $PaymentSatus = "<span class='float-right badge bg-danger'>Unpaid</span>";
                                } 
                            } else {    
                                if (employee_perm($EmployeePermission, 'Payments', 'Create')) {
                                    if (get_title('payment_mode', ['id' => $PaymentInfo->paymentmode], 'payment_method') == "Cheque") {
                                        if ($PaymentInfo->ChequeStatus == 0) {
                                            $PaymentSatus = "<span class='float-right badge bg-maroon'>Unpaid/PDC</span>";
                                        } elseif ($PaymentInfo->ChequeStatus == 1) {
                                            $PaymentSatus = "<a href='" . base_url() . "Employee/Payments/" . $row->id . "' class='float-right badge bg-warning'>"
                                                          . "Partial"
                                                          . "</a>";
                                        }
                                    } else {
                                        $PaymentSatus = "<a href='" . base_url() . "Employee/Payments/" . $row->id . "' class='float-right badge bg-warning'>"
                                                      . "Partial"
                                                      . "</a>";
                                    }
                                } else {
                                    if (get_title('payment_mode', ['id' => $PaymentInfo->paymentmode], 'payment_method') == "Cheque") {
                                        if ($PaymentInfo->ChequeStatus == 0) {
                                            $PaymentSatus = "<span class='float-right badge bg-maroon'>Unpaid/PDC</span>";
                                        } elseif ($PaymentInfo->ChequeStatus == 1) {
                                            $PaymentSatus = "<span class='float-right badge bg-warning'>Partial</span>";
                                        }
                                    } else {
                                        $PaymentSatus = "<span class='float-right badge bg-warning'>Partial</span>";
                                    }
                                }
                            }

                            $DeliveryStatus = $row->delivery_status == 1 ? "Yes" : "No";

                            if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == 'Supervisor') {
                                $SalesmanName = "<li class='nav-item'>"
                                              . "<a href='javascript:void(0);' class='nav-link'>"
                                              . "Salesman "
                                              . "<span class='float-right'>"
                                              . get_title('employees', ['Id' => $PartyInfo->SalesmanId], 'Name')
                                              . "</span>"
                                              . "</a>"
                                              . "</li>";
                            } else {
                                $SalesmanName = "";
                            }

                            if (employee_perm($EmployeePermission, 'SalesDeliveries', 'Edit')) {
                                $DeliveryInfo = "<a href='javascript:void(0);' onclick='onDeliveryInfoHandler(" . $row->id . ");' title=Delivery'>"
                                              . "<i class='fa fa-bus text-danger'></i>"
                                              . "</a>";
                            } else {
                                $DeliveryInfo = "";
                            }

                            if (employee_perm($EmployeePermission, 'Estimates', 'Edit')) {
                                $EstimateEdit = "<a href='" . base_url() . "Employee/EstimateEdit/" . $row->id . "' title='Edit'>"
                                              . "<i class='fa fa-edit text-success'></i>"
                                              . "</a>";
                            } else {
                                $EstimateEdit = "";
                            } 

                            if (employee_perm($EmployeePermission, 'PaymentsRequest', 'Create')) {
                                if ($row->paid_amount != $row->total) {
                                    $PaymentsRequest = "<a href='javascript:void(0);' onclick='PaymentRequestForm(" . $row->id . ");' title='Payment Request'>"
                                                     . "<i class='fa fa-money text-danger'></i>"
                                                     . "</a>";
                                } else {
                                    $PaymentsRequest = "";
                                }
                            } else {
                                $PaymentsRequest = "";
                            }

                            $Print = "<a href='" . base_url() . "Employee/PrintEstimate/" . $row->id . "' target='_blank' title='Print'>"
                                   . "<i class='fa fa-print text-primary'></i>"
                                   . "</a>";

                            $DeliveryChat = "<a href='javascript:void(0);' onclick='onDeliveryChatHandler(" . $row->id . ");' title='Delivery Chat'>"
                                          . "<i class='fa fa-comment text-fuchsia'></i>"
                                          . "</a>";

                            if (employee_perm($EmployeePermission, 'Estimates', 'Delete')) {
                                $DeleteEstimate = "<a href='" . base_url() . "Employee/DeleteEstimate/" . $row->id . "' title='Delete' onclick='return confirm(\"Are you sure want to delete this record!\");'>"
                                            . "<i class='fa fa-trash text-danger'></i>"
                                            . "</a>";
                            } else {
                                $DeleteEstimate = "";
                            }

                            $Html   .=  '<div class="col-md-3 col-sm-3 col-lg-3 col-xl-3 col-xxl-3 col-xs-3 pb-2">'
                                    .   '<div class="card card-widget widget-user-2 shadow-sm">'
                                    .   '<div class="widget-user-header bg-maroon">'
                                    .   '<h3 class="widget-user-username">'
                                    .   $PartyInfo->name
                                    .   '<small class="text-white">'
                                    .   $PartyArea
                                    .   '</small>'
                                    .   '</h3>'
                                    .   '<h5 class="widget-user-desc">'
                                    .   $PartyInfo->contact
                                    .   '</h5>'
                                    .   '<span class="widget-user-desc text-white">'
                                    .   $PartyInfo->gst
                                    .   '</span>'
                                    .   '</div>'
                                    .   '<div class="card-body p-0">'
                                    .   '<ul class="nav flex-column">'
                                    .   '<li class="nav-item">'
                                    .   $InvoiceLink
                                    .   '</li>'
                                    .   '<li class="nav-item">'
                                    .   '<a href="javascript:void(0);" class="nav-link">'
                                    .   'Invoice Date '
                                    .   '<span class="float-right">'
                                    .   date("d-m-Y", strtotime($row->invoiceDate))
                                    .   '</span>'
                                    .   '</a>'
                                    .   '</li>'
                                    .   '<li class="nav-item">'
                                    .   '<a href="javascript:void(0);" class="nav-link">'
                                    .   'Total '
                                    .   '<span class="float-right">'
                                    .   '<i class="fa fa-inr"></i> ' . number_format($row->total, 2)
                                    .   '</span>'
                                    .   '</a>'
                                    .   '</li>'
                                    .   '<li class="nav-item">'
                                    .   '<a href="javascript:void(0);" class="nav-link">'
                                    .   'Paid Amount '
                                    .   '<span class="float-right">'
                                    .   '<i class="fa fa-inr"></i> ' . number_format($row->paid_amount, 2)
                                    .   '</span>'
                                    .   '</a>'
                                    .   '</li>'
                                    .   '<li class="nav-item">'
                                    .   '<a href="javascript:void(0);" class="nav-link">'
                                    .   'Balance '
                                    .   '<span class="float-right">'
                                    .   '<i class="fa fa-inr"></i> ' . number_format(($row->total - $row->paid_amount), 2)
                                    .   '</span>'
                                    .   '</a>'
                                    .   '</li>'
                                    .   '<li class="nav-item">'
                                    .   '<div class="nav-link">'
                                    .   'Payment '
                                    .   $PaymentSatus
                                    .   '</div>'
                                    .   '</li>'
                                    .   '<li class="nav-item">'
                                    .   '<a href="javascript:void(0);" class="nav-link">'
                                    .   'Delivery '
                                    .   '<span class="float-right">'
                                    .   $DeliveryStatus
                                    .   '</span>'
                                    .   '</a>'
                                    .   '</li>'
                                    .   $SalesmanName
                                    .   '</ul>'
                                    .   '</div>'
                                    .   '<div class="card-footer p-2 text-center">'
                                    .   $DeliveryInfo
                                    .   '&nbsp;&nbsp;'
                                    .   $EstimateEdit
                                    .   '&nbsp;&nbsp;'
                                    .   $PaymentsRequest
                                    .   '&nbsp;&nbsp;'
                                    .   $Print
                                    .   '&nbsp;&nbsp;'
                                    .   $DeliveryChat
                                    .   '&nbsp;&nbsp;'
                                    .   $DeleteEstimate
                                    .   '</div>'
                                    .   '</div>'
                                    .   '</div>';
                        }
                        if ($Html) {
                            echo $Html;
                        } else {
                            echo '<span class="text-danger">No Record found.</span>';
                        }
                    }
                }
            }
        }
        
        public function ViewEstimate($Id = null)
        {
            if (isset($Id) && !empty($Id)) {
                $EmployeeId = $this->session->userdata('EmployeeId');
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
                $CompBranchId = $this->session->userdata('CompBranchId');
                $FinancialYear = $this->session->userdata('financial_year');

                $EstimatesWhere = ['id' => $Id, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1, 'financial_year' => $FinancialYear];
                $EstimateProductWhere = ['invoiceID' => $Id, 'type' => 'Sale', 'user_id' => $UserId, 'comp_id' => $CompId];
                $EmployeeWhere = [$EmployeeId];
                $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
                $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $EstimatesWhere['CompanyBranchId'] = $CompBranchId;
                    $EstimateProductWhere['CompanyBranchId'] = $CompBranchId;
                    $Where['CompanyBranchId'] = $CompBranchId;
                    $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                }
                if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                    $Where['Id'] = $EmployeeId;
                    $EmployeeIds = json_decode(get_title('employees', $Where, 'WarehouseSalesman'), true) ?: [];
                    foreach ($EmployeeIds as $key => $Id) {
                        $EmployeeWhere[] = $Id;
                    }
                }

                if (employee_has_perm('Estimates', 'View', $EmployeePermissionWhere)) {
                    $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                    $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                    $EstimateResult = $this->db->where($EstimatesWhere)->where_in('SalesmanId', $EmployeeWhere)->get('estimates')->row();
                    $EstimateProductResult = $this->db->where($EstimateProductWhere)->get('estimate_product')->result();
                    $EstimateResult->product = $EstimateProductResult;
                    $data['result'] = $EstimateResult;
                    $data['page'] = 'inventory_sep/estimate/view';
                    return $this->load->view('Employees', $data);
                } else {
                    $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                    return redirect(base_url() . 'Employee/Dashboard');
                }
            } else {
                return redirect(base_url() . 'Employee/EstimatesReport');
            }
        }
        
        public function PrintEstimate($Id = null)
        {
            if (isset($Id) && !empty($Id)) {
                $EmployeeId = $this->session->userdata('EmployeeId');
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
                $CompBranchId = $this->session->userdata('CompBranchId');

                $EstimatesWhere = ['id' => $Id, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
                $EstimateProductWhere = ['invoiceID' => $Id, 'type' => 'Sale', 'user_id' => $UserId, 'comp_id' => $CompId];
                $EmployeeWhere = [$EmployeeId];
                $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
                $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $EstimatesWhere['CompanyBranchId'] = $CompBranchId;
                    $EstimateProductWhere['CompanyBranchId'] = $CompBranchId;
                    $Where['CompanyBranchId'] = $CompBranchId;
                    $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                }
                if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                    $Where['Id'] = $EmployeeId;
                    $EmployeeIds = json_decode(get_title('employees', $Where, 'WarehouseSalesman'), true) ?: [];
                    foreach ($EmployeeIds as $key => $Id) {
                        $EmployeeWhere[] = $Id;
                    }
                }

                if (employee_has_perm('Estimates', 'View', $EmployeePermissionWhere)) {
                    $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                    $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                    $EstimateResult = $this->db->where($EstimatesWhere)->where_in('SalesmanId', $EmployeeWhere)->get('estimates')->row();
                    $EstimateProductResult = $this->db->where($EstimateProductWhere)->get('estimate_product')->result();
                    $EstimateResult->product = $EstimateProductResult;
                    $data['result'] = $EstimateResult;
                    return $this->load->view('inventory_sep/estimate/a5print', $data);
                } else {
                    $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                    return redirect(base_url() . 'Employee/Dashboard');
                }
            } else {
                return redirect(base_url() . 'Employee/EstimatesReport');
            }
        }
        
        public function EstimateEdit($Id = null)
        {
            if (isset($Id) && !empty($Id)) {
                $EmployeeId = $this->session->userdata('EmployeeId');
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
                $CompBranchId = $this->session->userdata('CompBranchId');
                $FinancialYear = $this->session->userdata('financial_year');

                $EstimateWhere = ['id' => $Id, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1, 'financial_year' => $FinancialYear];
                $EmployeeWhere = [$EmployeeId];
                $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
                $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $EstimateWhere['CompanyBranchId'] = $CompBranchId;
                    $Where['CompanyBranchId'] = $CompBranchId;
                    $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                }
                if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                    $Where['Id'] = $EmployeeId;
                    $EmployeeIds = json_decode(get_title('employees', $Where, 'WarehouseSalesman'), true) ?: [];
                    foreach ($EmployeeIds as $key => $Id) {
                        $EmployeeWhere[] = $Id;
                    }
                }

                if (employee_has_perm('Estimates', 'Edit', $EmployeePermissionWhere)) {
                    $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                    $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                    $EstimateResult = $this->db->select('invoiceNumber')->where($EstimateWhere)->where_in('SalesmanId', $EmployeeWhere)->get('estimates')->row();
                    $data['invoiceNumber'] = $EstimateResult->invoiceNumber;
                    $data['page'] = 'inventory_sep/estimate/edit';
                    return $this->load->view('Employees', $data);
                } else {
                    $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                    return redirect(base_url() . 'Employee/Dashboard');
                }
            } else {
                return redirect(base_url() . 'Employee/EstimatesReport');
            }
        }
        
        // From voucher page
        public function EstimateDelete($Id) 
        {
            if (!$Id) {
                echo json_encode(['status' => false, 'msg' => 'Id not found.']);
                return;
            }
             
            $UserId = $this->session->userdata('UserId');
            $CompId = $this->session->userdata('CompId');
            if (employee_has_perm('Estimates', 'Delete', ['EmployeeId' => $this->session->userdata('EmployeeId')])) {
                $EstimateInfo = $this->user_model->getRow('estimates', ['id' => $Id]);
                if ($EstimateInfo->paid_amount != 0) {
                    echo json_encode(['status' => false, 'msg' => 'Invoice payment partial or full paid, you can not change this.']);
                    return;
                }

                $data['status'] = 0;
                $where = ['id' => $Id, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1, 'financial_year' => $this->session->userdata('financial_year')];
                if ($this->session->userdata('BranchRole')) {
                    $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                $return = $this->user_model->updateAry('estimates', $data, $where);

                if ($return) {
                    $where = ['invoiceID' => $Id, 'type' => "Sale"];
                    $res = $this->user_model->getAry('estimate_product', '', '', '', $where);
                    if ($res) {
                        $statusChange['status'] = 0;
                        foreach ($res as $da) {
                            $this->user_model->updateAry('estimate_product', $statusChange, $where);
                        }
                        $this->user_model->updateAry('payment', $statusChange, ['invoice_id' => $Id, 'invoice_type' => 'Estimate', 'transaction_type' => 'CR']);
                    }
                    echo json_encode(['status' => true, 'msg' => 'Estimate deleted successfully.']);
                    return;
                } else {
                    echo json_encode(['status' => false, 'msg' => 'There is some issue, Please try again.']);
                    return;
                }
            } else {
                echo json_encode(['status' => false, 'msg' => 'you do not have permission.']);
                return;
            }
        }
        
        // From Estimate Report page
        public function DeleteEstimate($Id) 
        {
            if (!$Id) {
                $this->session->set_flashdata(['msg' => 'Id missing.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }
             
            $UserId = $this->session->userdata('UserId');
            $CompId = $this->session->userdata('CompId');
            if (employee_has_perm('Estimates', 'Delete', ['EmployeeId' => $this->session->userdata('EmployeeId')])) {
                $EstimateInfo = $this->user_model->getRow('estimates', ['id' => $Id]);
                if ($EstimateInfo->paid_amount != 0) {
                    $this->session->set_flashdata(['msg' => 'Invoice payment partial or full paid, you can not change this.', 'class' => 'danger']);
                    return redirect($_SERVER['HTTP_REFERER']);
                }

                $data['status'] = 0;
                $where = ['id' => $Id, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1, 'financial_year' => $this->session->userdata('financial_year')];
                if ($this->session->userdata('BranchRole')) {
                    $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                $return = $this->user_model->updateAry('estimates', $data, $where);

                if ($return) {
                    $where = ['invoiceID' => $Id, 'type' => "Sale"];
                    $res = $this->user_model->getAry('estimate_product', '', '', '', $where);
                    if ($res) {
                        $statusChange['status'] = 0;
                        foreach ($res as $da) {
                            $this->user_model->updateAry('estimate_product', $statusChange, $where);
                        }
                        $this->user_model->updateAry('payment', $statusChange, ['invoice_id' => $Id, 'invoice_type' => 'Estimate', 'transaction_type' => 'CR']);
                    }
                    $this->session->set_flashdata(['msg' => 'Estimate deleted successfully.', 'class' => 'success']);
                    return redirect($_SERVER['HTTP_REFERER']);
                } else {
                    $this->session->set_flashdata(['msg' => 'There is some issue, Please try again.', 'class' => 'danger']);
                    return redirect($_SERVER['HTTP_REFERER']);
                }
            } else {
                $this->session->set_flashdata(['msg' => 'you do not have permission.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }
        }
          
        public function EstimateToSale($Id = null)
        {
            if (isset($Id) && !empty($Id)) {
                $EmployeeId = $this->session->userdata('EmployeeId');
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
                $CompBranchId = $this->session->userdata('CompBranchId');
                $FinancialYear = $this->session->userdata('financial_year');

                $EstimateWhere = ['id' => $Id, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1, 'financial_year' => $FinancialYear];
                $EmployeeWhere = [$EmployeeId];
                $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
                $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $EstimateWhere['CompanyBranchId'] = $CompBranchId;
                    $Where['CompanyBranchId'] = $CompBranchId;
                    $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                }
                if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                    $Where['Id'] = $EmployeeId;
                    $EmployeeIds = json_decode(get_title('employees', $Where, 'WarehouseSalesman'), true) ?: [];
                    foreach ($EmployeeIds as $key => $Id) {
                        $EmployeeWhere[] = $Id;
                    }
                }

                if (employee_has_perm('Sales', 'Create', $EmployeePermissionWhere)) {
                    $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                    $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                    $EstimateResult = $this->db->select('invoiceNumber')->where($EstimateWhere)->where_in('SalesmanId', $EmployeeWhere)->get('estimates')->row();
                    $data['invoiceNumber'] = $EstimateResult->invoiceNumber;
                    $data['page'] = 'inventory_sep/estimate/converSale';
                    return $this->load->view('Employees', $data);
                } else {
                    $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                    return redirect(base_url() . 'Employee/Dashboard');
                }
            } else {
                return redirect(base_url() . 'Employee/EstimatesReport');
            }
        }
        
        // Purchases //
        public function Purchases()
        {
            $EmployeeId = $this->session->userdata('EmployeeId');
            $UserId = $this->session->userdata('UserId');
            $CompId = $this->session->userdata('CompId');
            $CompBranchId = $this->session->userdata('CompBranchId');
            
            $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
            $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $CompBranchId;
                $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
            }
            if (employee_has_perm('Purchases', 'Create', $EmployeePermissionWhere)) {
                $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                $data['page'] = 'inventory_sep/purchase/index';
                return $this->load->view('Employees', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/Dashboard');
            }
        }
        
        public function PurchasesReport()
        {
            $EmployeeId = $this->session->userdata('EmployeeId');
            $UserId = $this->session->userdata('UserId');
            $CompId = $this->session->userdata('CompId');
            $CompBranchId = $this->session->userdata('CompBranchId');
            $FinancialYear = $this->session->userdata('financial_year');
            
            if ($this->input->get('fromDate') != "" && $this->input->get('toDate') != "") {
                $from = $this->input->get('fromDate');
                $to = $this->input->get('toDate');
            } else {
                $from = date('Y-m-d');
                $to = date('Y-m-d');
            }
            
            $PurchasesWhere = ['invoiceDate >=' => $from, 'invoiceDate <=' => $to, 'user_id' => $UserId, 'comp_id' => $CompId, 'EmployeeId' => $EmployeeId, 'status' => 1, 'financial_year' => $FinancialYear];
            $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
            $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
            if ($this->session->userdata('BranchRole')) {
                $PurchasesWhere['CompanyBranchId'] = $CompBranchId;
                $Where['CompanyBranchId'] = $CompBranchId;
                $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
            }
            
            if (employee_has_perm('Purchases', 'View', $EmployeePermissionWhere)) {
                $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                $data['Purchases'] = $this->db->where($PurchasesWhere)->order_by('id', 'DESC')->get('purchases')->result();
                $data['page'] = "PurchasesReport";
                return $this->load->view('inventory_sep/Employee/Employee', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/Dashboard');
            }
        }
        
        public function ViewPurchases($Id = null)
        {
            if (isset($Id) && !empty($Id)) {
                $EmployeeId = $this->session->userdata('EmployeeId');
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
                $CompBranchId = $this->session->userdata('CompBranchId');
                $FinancialYear = $this->session->userdata('financial_year');

                $PurchasesWhere = ['id' => $Id, 'user_id' => $UserId, 'comp_id' => $CompId, 'EmployeeId' => $EmployeeId, 'status' => 1, 'financial_year' => $FinancialYear];
                $PurchasesProductWhere = ['invoiceID' => $Id, 'type' => 'Purchase', 'user_id' => $UserId, 'comp_id' => $CompId];
                $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
                $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $PurchasesWhere['CompanyBranchId'] = $CompBranchId;
                    $PurchasesProductWhere['CompanyBranchId'] = $CompBranchId;
                    $Where['CompanyBranchId'] = $CompBranchId;
                    $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                }

                if (employee_has_perm('Purchases', 'View', $EmployeePermissionWhere)) {
                    $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                    $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                    $PurchasesResult = $this->db->where($PurchasesWhere)->get('purchases')->row();
                    $PurchasesProductResult = $this->db->where($PurchasesProductWhere)->get('invoice_product')->result();
                    $PurchasesResult->product = $PurchasesProductResult;
                    $data['result'] = $PurchasesResult;
                    $data['page'] = 'inventory_sep/purchase/viewpurchase';
                    return $this->load->view('Employees', $data);
                } else {
                    $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                    return redirect(base_url() . 'Employee/Dashboard');
                }
            } else {
                return redirect(base_url() . 'Employee/PurchasesReport');
            }
        }
        
        public function PrintPurchases($Id)
        {
            if (isset($Id) && !empty($Id)) {
                $EmployeeId = $this->session->userdata('EmployeeId');
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
                $CompBranchId = $this->session->userdata('CompBranchId');
                $FinancialYear = $this->session->userdata('financial_year');

                $PurchasesWhere = ['id' => $Id, 'user_id' => $UserId, 'comp_id' => $CompId, 'EmployeeId' => $EmployeeId, 'status' => 1, 'financial_year' => $FinancialYear];
                $PurchasesProductWhere = ['invoiceID' => $Id, 'type' => 'Purchase', 'user_id' => $UserId, 'comp_id' => $CompId];
                $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
                $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $PurchasesWhere['CompanyBranchId'] = $CompBranchId;
                    $PurchasesProductWhere['CompanyBranchId'] = $CompBranchId;
                    $Where['CompanyBranchId'] = $CompBranchId;
                    $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                }

                if (employee_has_perm('Purchases', 'View', $EmployeePermissionWhere)) {
                    $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                    $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                    $PurchasesResult = $this->db->where($PurchasesWhere)->get('purchases')->row();
                    $PurchasesProductResult = $this->db->where($PurchasesProductWhere)->get('invoice_product')->result();
                    $PurchasesResult->product = $PurchasesProductResult;
                    $data['result'] = $PurchasesResult;
                    return $this->load->view('inventory_sep/purchase/a5print', $data);
                } else {
                    $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                    return redirect(base_url() . 'Employee/Dashboard');
                }
            } else {
                return redirect(base_url() . 'Employee/PurchasesReport');
            }
        }
        
        public function PurchasesEdit($Id = null)
        {
            if (isset($Id) && !empty($Id)) {
                $EmployeeId = $this->session->userdata('EmployeeId');
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
                $CompBranchId = $this->session->userdata('CompBranchId');
                $FinancialYear = $this->session->userdata('financial_year');

                $PurchasesWhere = ['id' => $Id, 'user_id' => $UserId, 'comp_id' => $CompId, 'EmployeeId' => $EmployeeId, 'status' => 1, 'financial_year' => $FinancialYear];
                $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
                $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $PurchasesWhere['CompanyBranchId'] = $CompBranchId;
                    $Where['CompanyBranchId'] = $CompBranchId;
                    $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                }

                if (employee_has_perm('Purchases', 'Edit', $EmployeePermissionWhere)) {
                    $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                    $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                    $PurchasesResult = $this->db->select('invoiceNumber')->where($PurchasesWhere)->get('purchases')->row();
                    $data['invoiceNumber'] = $PurchasesResult->invoiceNumber;
                    $data['page'] = 'inventory_sep/purchase/index';
                    return $this->load->view('Employees', $data);
                } else {
                    $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                    return redirect(base_url() . 'Employee/Dashboard');
                }
            } else {
                return redirect(base_url() . 'Employee/PurchasesReport');
            }
        }
        
        public function PurchasesDelete($Id) 
        {
            if (!$Id) {
                echo json_encode(['status' => false, 'msg' => 'Id not found.']);
                return;
            }
             
            $EmployeeId = $this->session->userdata('EmployeeId');
            $UserId = $this->session->userdata('UserId');
            $CompId = $this->session->userdata('CompId');
            if (employee_has_perm('Purchases', 'Delete', ['EmployeeId' => $this->session->userdata('EmployeeId')])) {
                $PurchasesInfo = $this->user_model->getRow('purchases', ['id' => $Id]);
                if ($PurchasesInfo->paid_amount != 0) {
                    echo json_encode(['status' => false, 'msg' => 'Invoice payment partial or full paid, you can not change this.']);
                    return;
                }

                $data['status'] = 0;
                $where = ['id' => $Id, 'user_id' => $UserId, 'comp_id' => $CompId, 'EmployeeId' => $EmployeeId, 'status' => 1, 'financial_year' => $this->session->userdata('financial_year')];
                if ($this->session->userdata('BranchRole')) {
                    $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                $return = $this->user_model->updateAry('purchases', $data, $where);

                if ($return) {
                    $PurchaseProductWhere = ['invoiceID' => $Id, 'type' => "Purchase"];
                    $res = $this->user_model->getAry('invoice_product', '', '', '', $PurchaseProductWhere);
                    if ($res) {
                        $statusChange['status'] = 0;
                        foreach ($res as $da) {
                            $this->user_model->updateAry('invoice_product', $statusChange, $PurchaseProductWhere);
                        }
                    }
                    echo json_encode(['status' => true, 'msg' => 'Purchase deleted successfully.']);
                    return;
                } else {
                    echo json_encode(['status' => false, 'msg' => 'There is some issue, Please try again.']);
                    return;
                }
            } else {
                echo json_encode(['status' => false, 'msg' => 'you do not have permission.']);
                return;
            }
        }
        
        // Estimates Purchases //
        public function EstimatesPurchases()
        {
            $EmployeeId = $this->session->userdata('EmployeeId');
            $UserId = $this->session->userdata('UserId');
            $CompId = $this->session->userdata('CompId');
            $CompBranchId = $this->session->userdata('CompBranchId');
            
            $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
            $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $CompBranchId;
                $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
            }
            if (employee_has_perm('EstimatePurchase', 'Create', $EmployeePermissionWhere)) {
                $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                $data['page'] = 'inventory_sep/estimate_purchase/index';
                return $this->load->view('Employees', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/Dashboard');
            }
        }
        
        public function EstimatesPurchasesReport()
        {
            $EmployeeId = $this->session->userdata('EmployeeId');
            $UserId = $this->session->userdata('UserId');
            $CompId = $this->session->userdata('CompId');
            $CompBranchId = $this->session->userdata('CompBranchId');
            $FinancialYear = $this->session->userdata('financial_year');
            
            if ($this->input->get('fromDate') != "" && $this->input->get('toDate') != "") {
                $from = $this->input->get('fromDate');
                $to = $this->input->get('toDate');
            } else {
                $from = date('Y-m-d');
                $to = date('Y-m-d');
            }
            
            $EstimatesPurchasesWhere = ['invoiceDate >=' => $from, 'invoiceDate <=' => $to, 'user_id' => $UserId, 'comp_id' => $CompId, 'EmployeeId' => $EmployeeId, 'status' => 1, 'financial_year' => $FinancialYear];
            $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
            $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
            if ($this->session->userdata('BranchRole')) {
                $EstimatesPurchasesWhere['CompanyBranchId'] = $CompBranchId;
                $Where['CompanyBranchId'] = $CompBranchId;
                $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
            }
            
            if (employee_has_perm('EstimatePurchase', 'View', $EmployeePermissionWhere)) {
                $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                $data['EstimatesPurchases'] = $this->db->where($EstimatesPurchasesWhere)->order_by('id', 'DESC')->get('estimate_purchase')->result();
                $data['page'] = "EstimatesPurchasesReport";
                return $this->load->view('inventory_sep/Employee/Employee', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/Dashboard');
            }
        }
        
        public function ViewEstimatesPurchases($Id = null)
        {
            if (isset($Id) && !empty($Id)) {
                $EmployeeId = $this->session->userdata('EmployeeId');
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
                $CompBranchId = $this->session->userdata('CompBranchId');
                $FinancialYear = $this->session->userdata('financial_year');

                $EstimatesPurchasesWhere = ['id' => $Id, 'user_id' => $UserId, 'comp_id' => $CompId, 'EmployeeId' => $EmployeeId, 'status' => 1, 'financial_year' => $FinancialYear];
                $EstimatesPurchasesProductWhere = ['invoiceID' => $Id, 'type' => 'Purchase', 'user_id' => $UserId, 'comp_id' => $CompId];
                $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
                $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $EstimatesPurchasesWhere['CompanyBranchId'] = $CompBranchId;
                    $EstimatesPurchasesProductWhere['CompanyBranchId'] = $CompBranchId;
                    $Where['CompanyBranchId'] = $CompBranchId;
                    $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                }

                if (employee_has_perm('EstimatePurchase', 'View', $EmployeePermissionWhere)) {
                    $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                    $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                    $EstimatesPurchasesResult = $this->db->where($EstimatesPurchasesWhere)->get('estimate_purchase')->row();
                    $EstimatePurchasesProductResult = $this->db->where($EstimatesPurchasesProductWhere)->get('estimate_product')->result();
                    $EstimatesPurchasesResult->product = $EstimatePurchasesProductResult;
                    $data['result'] = $EstimatesPurchasesResult;
                    $data['page'] = 'inventory_sep/estimate_purchase/view';
                    return $this->load->view('Employees', $data);
                } else {
                    $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                    return redirect(base_url() . 'Employee/Dashboard');
                }
            } else {
                return redirect(base_url() . 'Employee/PurchasesReport');
            }
        }
        
        public function PrintEstimatesPurchases($Id)
        {
            if (isset($Id) && !empty($Id)) {
                $EmployeeId = $this->session->userdata('EmployeeId');
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
                $CompBranchId = $this->session->userdata('CompBranchId');
                $FinancialYear = $this->session->userdata('financial_year');

                $EstimatesPurchasesWhere = ['id' => $Id, 'user_id' => $UserId, 'comp_id' => $CompId, 'EmployeeId' => $EmployeeId, 'status' => 1, 'financial_year' => $FinancialYear];
                $EstimatesPurchasesProductWhere = ['invoiceID' => $Id, 'type' => 'Purchase', 'user_id' => $UserId, 'comp_id' => $CompId];
                $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
                $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $EstimatesPurchasesWhere['CompanyBranchId'] = $CompBranchId;
                    $EstimatesPurchasesProductWhere['CompanyBranchId'] = $CompBranchId;
                    $Where['CompanyBranchId'] = $CompBranchId;
                    $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                }

                if (employee_has_perm('EstimatePurchase', 'View', $EmployeePermissionWhere)) {
                    $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                    $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                    $EstimatesPurchasesResult = $this->db->where($EstimatesPurchasesWhere)->get('estimate_purchase')->row();
                    $EstimatesPurchasesProductResult = $this->db->where($EstimatesPurchasesProductWhere)->get('estimate_product')->result();
                    $EstimatesPurchasesResult->product = $EstimatesPurchasesProductResult;
                    $data['result'] = $EstimatesPurchasesResult;
                    return $this->load->view('inventory_sep/estimate_purchase/print/a5', $data);
                } else {
                    $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                    return redirect(base_url() . 'Employee/Dashboard');
                }
            } else {
                return redirect(base_url() . 'Employee/PurchasesReport');
            }
        }
        
        public function EstimatesPurchasesEdit($Id = null)
        {
            if (isset($Id) && !empty($Id)) {
                $EmployeeId = $this->session->userdata('EmployeeId');
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
                $CompBranchId = $this->session->userdata('CompBranchId');
                $FinancialYear = $this->session->userdata('financial_year');

                $EstimatesPurchasesWhere = ['id' => $Id, 'user_id' => $UserId, 'comp_id' => $CompId, 'EmployeeId' => $EmployeeId, 'status' => 1, 'financial_year' => $FinancialYear];
                $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
                $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $EstimatesPurchasesWhere['CompanyBranchId'] = $CompBranchId;
                    $Where['CompanyBranchId'] = $CompBranchId;
                    $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                }

                if (employee_has_perm('EstimatePurchase', 'Edit', $EmployeePermissionWhere)) {
                    $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                    $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                    $EstimatesPurchasesResult = $this->db->select('invoiceNumber')->where($EstimatesPurchasesWhere)->get('estimate_purchase')->row();
                    $data['invoiceNumber'] = $EstimatesPurchasesResult->invoiceNumber;
                    $data['page'] = 'inventory_sep/estimate_purchase/EditEstimatePurchase';
                    return $this->load->view('Employees', $data);
                } else {
                    $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                    return redirect(base_url() . 'Employee/Dashboard');
                }
            } else {
                return redirect(base_url() . 'Employee/PurchasesReport');
            }
        }
        
        public function EstimatesPurchasesDelete($Id) 
        {
            if (!$Id) {
                echo json_encode(['status' => false, 'msg' => 'Id not found.']);
                return;
            }
             
            $EmployeeId = $this->session->userdata('EmployeeId');
            $UserId = $this->session->userdata('UserId');
            $CompId = $this->session->userdata('CompId');
            if (employee_has_perm('EstimatePurchase', 'Delete', ['EmployeeId' => $this->session->userdata('EmployeeId')])) {
                $PurchasesInfo = $this->user_model->getRow('purchases', ['id' => $Id]);
                if ($PurchasesInfo->paid_amount != 0) {
                    echo json_encode(['status' => false, 'msg' => 'Invoice payment partial or full paid, you can not change this.']);
                    return;
                }

                $data['status'] = 0;
                $where = ['id' => $Id, 'user_id' => $UserId, 'comp_id' => $CompId, 'EmployeeId' => $EmployeeId, 'status' => 1, 'financial_year' => $this->session->userdata('financial_year')];
                if ($this->session->userdata('BranchRole')) {
                    $where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                $return = $this->user_model->updateAry('estimate_purchase', $data, $where);

                if ($return) {
                    $EstimatePurchaseProductWhere = ['invoiceID' => $Id, 'type' => "Purchase"];
                    $res = $this->user_model->getAry('estimate_product', '', '', '', $EstimatePurchaseProductWhere);
                    if ($res) {
                        $statusChange['status'] = 0;
                        foreach ($res as $da) {
                            $this->user_model->updateAry('estimate_product', $statusChange, $EstimatePurchaseProductWhere);
                        }
                    }
                    echo json_encode(['status' => true, 'msg' => 'Estimate Purchase deleted successfully.']);
                    return;
                } else {
                    echo json_encode(['status' => false, 'msg' => 'There is some issue, Please try again.']);
                    return;
                }
            } else {
                echo json_encode(['status' => false, 'msg' => 'you do not have permission.']);
                return;
            }
        }

        // Receits //
        public function receipts($InvoiceId = null)
        {
            $EmployeeId = $this->session->userdata('EmployeeId');
            $UserId = $this->session->userdata('UserId');
            $CompId = $this->session->userdata('CompId');
            $CompBranchId = $this->session->userdata('CompBranchId');
            
            $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
            $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $CompBranchId;
                $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
            }
            if (employee_has_perm('receipts', 'Create', $EmployeePermissionWhere)) {
                $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                $data['InvoiceId'] = $InvoiceId;
                $data['page'] = 'inventory_sep/receipt/index';
                return $this->load->view('Employees', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/Dashboard');
            }
        }
        
        // Payments //
        public function Payments($InvoiceId = null)
        {
            $EmployeeId = $this->session->userdata('EmployeeId');
            $UserId = $this->session->userdata('UserId');
            $CompId = $this->session->userdata('CompId');
            $CompBranchId = $this->session->userdata('CompBranchId');
            
            $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
            $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $CompBranchId;
                $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
            }
            if (employee_has_perm('Payments', 'Create', $EmployeePermissionWhere)) {
                $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                $data['InvoiceId'] = $InvoiceId;
                $data['page'] = 'inventory_sep/payment/index';
                return $this->load->view('Employees', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/Dashboard');
            }
        }        
        
        public function paymentDeleteAjax($Id)
        {
            if (isset($Id) && !empty($Id)) {
                $EmployeeId = $this->session->userdata('EmployeeId');
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
                $updatedBy = get_title('employees', ['Id' => $EmployeeId], 'Name');
                $CompBranchId = $this->session->userdata('CompBranchId');

                $Where = ['id' => $Id, 'transaction_type' => 'DR', 'user_id' => $UserId, 'comp_id' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $CompBranchId;
                }
                
                if (employee_has_perm('Payments', 'Delete', ['EmployeeId' => $EmployeeId])) {
                    $PaymentInfo = $this->user_model->getRow('payment', $Where);
                    if ($PaymentInfo) {
                        $updated = date('d-m-Y h:i:s A');
                        $invoiceIds = json_decode($PaymentInfo->invoice_id ?? '[]', true);
                        $invoiceTypes = json_decode($PaymentInfo->invoice_type ?? '[]', true);
                        if (is_array($invoiceIds) && is_array($invoiceTypes)) {
                            foreach ($invoiceIds as $key => $InvoiceId) {
                                $invoiceType = $invoiceTypes[$key] ?? '';
                                if ($invoiceType == "Sale") {
                                    $UpdateSale = [
                                        'paid_amount' => ($this->db->select('paid_amount')->where('id', $InvoiceId)->get('sales')->row('paid_amount') - $PaymentInfo->debit),
                                        'updated' => $updated,
                                        'updated_by' => $updatedBy
                                    ];
                                    $this->user_model->updateAry('sales', $UpdateSale, ['id' => $InvoiceId, 'type' => $invoiceType]);
                                }

                                if ($invoiceType == "Estimate") {
                                    $UpdateEstimate = [
                                        'paid_amount' => ($this->db->select('paid_amount')->where('id', $InvoiceId)->get('estimates')->row('paid_amount') - $PaymentInfo->debit),
                                        'updated' => $updated,
                                        'updated_by' => $updatedBy
                                    ];
                                    $this->user_model->updateAry('estimates', $UpdateEstimate, ['id' => $InvoiceId, 'type' => $invoiceType]);
                                }

                                if ($invoiceType == "Purchase") {
                                    $UpdatePurchase = [
                                        'paid_amount' => ($this->db->select('paid_amount')->where('id', $InvoiceId)->get('purchases')->row('paid_amount') - $PaymentInfo->debit),
                                        'updated' => $updated,
                                        'updated_by' => $updatedBy
                                    ];
                                    $this->user_model->updateAry('purchases', $UpdatePurchase, ['id' => $InvoiceId, 'type' => $invoiceType]);
                                }
                                if ($invoiceType == "Estimate Purchase") {
                                    $UpdateEstimatePurchase = [
                                        'paid_amount' => ($this->db->select('paid_amount')->where('id', $InvoiceId)->get('estimate_purchase')->row('paid_amount') - $PaymentInfo->debit),
                                        'updated' => $updated,
                                        'updated_by' => $updatedBy
                                    ];
                                    $this->user_model->updateAry('estimate_purchase', $UpdateEstimatePurchase, ['id' => $InvoiceId, 'type' => $invoiceType]);
                                }
                            }
                        } else {
                            if ($PaymentInfo->invoice_type == "Sale") {
                                $UpdateSale = [
                                    'paid_amount' => ($this->db->select('paid_amount')->where('id', $PaymentInfo->invoice_id)->get('sales')->row('paid_amount') - $PaymentInfo->debit),
                                    'updated' => $updated,
                                    'updated_by' => $updatedBy
                                ];
                                $this->user_model->updateAry('sales', $UpdateSale, ['id' => $PaymentInfo->invoice_id, 'type' => $PaymentInfo->invoice_type]);
                            }

                            if ($PaymentInfo->invoice_type == "Estimate") {
                                $UpdateEstimate = [
                                    'paid_amount' => ($this->db->select('paid_amount')->where('id', $PaymentInfo->invoice_id)->get('estimates')->row('paid_amount') - $PaymentInfo->debit),
                                    'updated' => $updated,
                                    'updated_by' => $updatedBy
                                ];
                                $this->user_model->updateAry('estimates', $UpdateEstimate, ['id' => $PaymentInfo->invoice_id, 'type' => $PaymentInfo->invoice_type]);
                            }

                            if ($PaymentInfo->invoice_type == "Purchase") {
                                $UpdatePurchase = [
                                    'paid_amount' => ($this->db->select('paid_amount')->where('id', $PaymentInfo->invoice_id)->get('purchases')->row('paid_amount') - $PaymentInfo->debit),
                                    'updated' => $updated,
                                    'updated_by' => $updatedBy
                                ];
                                $this->user_model->updateAry('purchases', $UpdatePurchase, ['id' => $PaymentInfo->invoice_id, 'type' => $PaymentInfo->invoice_type]);
                            }
                            if ($PaymentInfo->invoice_type == "Estimate Purchase") {
                                $UpdateEstimatePurchase = [
                                    'paid_amount' => ($this->db->select('paid_amount')->where('id', $PaymentInfo->invoice_id)->get('estimate_purchase')->row('paid_amount') - $PaymentInfo->debit),
                                    'updated' => $updated,
                                    'updated_by' => $updatedBy
                                ];
                                $this->user_model->updateAry('estimate_purchase', $UpdateEstimatePurchase, ['id' => $PaymentInfo->invoice_id, 'type' => $PaymentInfo->invoice_type]);
                            }
                        }

                        $this->user_model->deleteRow('payment', $Where);
                        echo json_encode(['status' => true, 'msg' => 'Payment deleted successfully.']);
                        return;
                    } else {
                        echo json_encode(['status' => false, 'msg' => 'There is some issue, Please try again.']);
                        return;
                    }
                } else {
                    echo json_encode(['status' => false, 'msg' => 'you do not have permission.']);
                    return;
                }
            } else {
                echo json_encode(['status' => false, 'msg' => 'Payment id not found.']);
                return;
            }
        }
        
        public function paymentsStatement()
        {
            $employeeId = $this->session->userdata('EmployeeId');
            $userId = $this->session->userdata('UserId');
            $compId = $this->session->userdata('CompId');
            $compBranchId = $this->session->userdata('CompBranchId');
            
            $accountGroupId = $this->input->get('accountGroupId');
            if ($this->input->get('ledgerId')) {
                $ledgerId = $this->input->get('ledgerId');
            }
            if ($this->input->get('fromDate') && $this->input->get('toDate')) {
                $fromDate = $this->input->get('fromDate');
                $toDate = $this->input->get('toDate');
            } else {
                $fromDate = date('Y-m-d');
                $toDate = date('Y-m-d');
            }
            
            $where = ['UserId' => $userId, 'CompId' => $compId, 'IsDeleted' => 1];
            $employeeWhere = [$employeeId];
            $employeePermissionWhere = ['EmployeeId' => $employeeId, 'UserId' => $userId, 'CompId' => $compId];
            $partiesWhere = ['user_id' => $userId, 'comp_id' => $compId, 'status' => 1];
            $paymentWhere = ['account_group' => $accountGroupId, 'date >=' => $fromDate, 'date <=' => $toDate, 'status' => 1, 'user_id' => $userId, 'comp_id' => $compId];
            $openingBalanceWhere = ['account_group' => $accountGroupId, 'party_id' => $ledgerId, 'date <' => $fromDate, 'status' => 1, 'user_id' => $userId, 'comp_id' => $compId];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $compBranchId;
                $employeePermissionWhere['CompanyBranchId'] = $compBranchId;
                $partiesWhere['CompanyBranchId'] = $compBranchId;
                $paymentWhere['CompanyBranchId'] = $compBranchId;
                $openingBalanceWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
            }
            
            $openingBalanceData = $this->db->select('SUM(credit) AS totalCredit, SUM(debit) AS totalDebit')->where($openingBalanceWhere)->get('payment')->row();

            if (get_title('accounting_group', ['id' => $accountGroupId], 'name') == "Sundry Debitors") {
                $openingBalanceValue = ($openingBalanceData->totalCredit - $openingBalanceData->totalDebit);
            } elseif (get_title('accounting_group', ['id' => $accountGroupId], 'name') == "Sundry Creditors") {
                $openingBalanceValue = ($openingBalanceData->totalDebit - $openingBalanceData->totalCredit);
            } else {
                $openingBalanceValue = ($openingBalanceData->totalCredit - $openingBalanceData->totalDebit);
            }
            
            if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                $where['Id'] = $employeeId;
                $employeeIds = json_decode(get_title('employees', $where, 'WarehouseSalesman'), true) ?: [];
                foreach ($employeeIds as $key => $id) {
                    $employeeWhere[] = $id;
                }
            }
            
            if (employee_has_perm('Payments', 'View', $employeePermissionWhere)) {
                $data['EmployeeInfo'] = $this->db->where('Id', $employeeId)->where($where)->get('employees')->row();
                $data['EmployeePermission'] = $this->db->where($employeePermissionWhere)->get('employee_permission')->row();
                $data['openingBalance'] = $openingBalanceValue;
                $data['ledgerStatement'] = $this->user_model->getResultArray('payment', $paymentWhere, $ledgerId);
                $data['page'] = 'inventory_sep/payment/statement';
                return $this->load->view('Employees', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/Dashboard');
            }
        }
        
        public function PrintPaymentStatement()
        {
            $EmployeeId = $this->session->userdata('EmployeeId');
            $UserId = $this->session->userdata('UserId');
            $CompId = $this->session->userdata('CompId');
            $CompBranchId = $this->session->userdata('CompBranchId');
            
            $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
            $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $CompBranchId;
                $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
            }
            
            if (employee_has_perm('Payments', 'View', $EmployeePermissionWhere)) {
                $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                return $this->load->view('inventory_sep/payment/print-statement', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/Dashboard');
            }
        }
        
        //Payment Request        
        public function PaymentRequestForm($InvoiceId) 
        {
            if (!empty($InvoiceId)) {
                $EmployeeId = $this->session->userdata('EmployeeId');
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
                $CompBranchId = $this->session->userdata('CompBranchId');

                $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
                $CommonWhere = ['id' => $InvoiceId, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
                if ($this->session->userdata('BranchRole')) {
                    $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                    $CommonWhere['CompanyBranchId'] = $CompBranchId;
                }
                
                if (employee_has_perm('PaymentsRequest', 'Create', $EmployeePermissionWhere)) {
                    if ($this->db->select('id')->where($CommonWhere)->where('type', 'Sale')->get('sales')->num_rows()) {
                        $data['InvoiceData'] = $this->db->select('id, invoiceNumber, invoiceDate, customerId, type, total, paid_amount')->where($CommonWhere)->get('sales')->row();
                    }
                    if ($this->db->select('id')->where($CommonWhere)->where('type', 'Estimate')->get('estimates')->num_rows()) {
                        $data['InvoiceData'] = $this->db->select('id, invoiceNumber, invoiceDate, customerId, type, total, paid_amount')->where($CommonWhere)->get('estimates')->row();
                    }
                    $data['PaymentMode'] = $this->db->select('id, payment_method')->get('payment_mode')->result();
                    return $this->load->view('inventory_sep/Employee/PaymentRequest', $data);
                } else {
                    $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                    return redirect(base_url() . 'Employee/Dashboard');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Invoice id missing.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/Dashboard');
            }
        }
        
        public function PaymentRequest() 
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $EmployeeId = $this->session->userdata('EmployeeId');
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
                $CompBranchId = $this->session->userdata('CompBranchId');
                $PaymentDate = $this->input->post('payment_date');
                $PartyId = $this->input->post('PartyId');
                $PaymentAmount = $this->input->post('payment_amount');
                $InvoiceNumber = $this->input->post('invoiceNumber');
                
                $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
                $Where = ['payment_date' => $PaymentDate, 'payment_amount' => $PaymentAmount, 'party_id' => $PartyId, 'user_id' => $UserId, 'comp_id' => $CompId, 'SalesmanId' => $EmployeeId, 'status' => 0];
                if ($this->session->userdata('BranchRole')) {
                    $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                    $Where['CompanyBranchId'] = $CompBranchId;
                }
                
                if (employee_has_perm('PaymentsRequest', 'Create', $EmployeePermissionWhere)) {
                    $JSONInvoiceNumber = json_encode(array($InvoiceNumber), JSON_UNESCAPED_SLASHES);
                    $this->db->where($Where);
                    $this->db->group_start();
                    $this->db->where('invoiceNumber', $InvoiceNumber);
                    $this->db->or_where("JSON_CONTAINS(CAST(invoiceNumber AS JSON), '" . $JSONInvoiceNumber . "')");
                    $this->db->group_end();
                    $PaymentRequestQuery = $this->db->get('paymentrequest')->num_rows();

                    if ($PaymentRequestQuery) {
                        $this->session->set_flashdata(['msg' => 'Payment Request already submitted.', 'class' => 'danger']);
                        return redirect(base_url() . 'Employee/PaymentRequestReport');
                    } else {
                        $PaymentData = [
                            'date' => date('Y-m-d'),
                            'invoiceId' => $this->input->post('InvoiceId'),
                            'Type' => $this->input->post('Type'),
                            'invoiceNumber' => $InvoiceNumber,
                            'party_id' => $PartyId,
                            'payment_date' => $PaymentDate,
                            'payment_mode' => $this->input->post('payment_mode'),
                            'ChequeDate' => $this->input->post('ChequeDate'),
                            'payment_amount' => $PaymentAmount,
                            'remark' => $this->input->post('remark'),
                            'user_id' => $UserId,
                            'comp_id' => $CompId,
                            'CompanyBranchId' => $CompBranchId,
                            'SalesmanId' => $EmployeeId,
                            'created' => date('d-m-Y h:i:s A'),
                            'created_by' => get_title('employees', ['Id' => $EmployeeId], 'Name')
                        ];

                        $result = $this->user_model->saveAry('paymentrequest', $PaymentData);
                        if (!empty($result)) {
                            $this->session->set_flashdata(['msg' => 'Record saved successfully.', 'class' => 'success']);
                            return redirect(base_url() . 'Employee/PaymentRequestReport');
                        } else {
                            $this->session->set_flashdata(['msg' => 'Unable to save record.', 'class' => 'danger']);
                            return redirect(base_url() . 'Employee/PaymentRequestReport');
                        }                        
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                    return redirect(base_url() . 'Employee/Dashboard');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Direct entry not allowed.', 'class' => 'danger']);
                return redirect('Employee/Dashboard');
            }
        }
        
        public function PaymentRequestReport() 
        {
            $EmployeeId = $this->session->userdata('EmployeeId');
            $UserId = $this->session->userdata('UserId');
            $CompId = $this->session->userdata('CompId');
            $CompBranchId = $this->session->userdata('CompBranchId');
            
            $PaymentsRequestsWhere = ['user_id' => $UserId, 'comp_id' => $CompId];
            $EmployeeWhere = [$EmployeeId];
            $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
            $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
            if ($this->session->userdata('BranchRole')) {
                $PaymentsRequestsWhere['CompanyBranchId'] = $CompBranchId;
                $Where['CompanyBranchId'] = $CompBranchId;
                $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
            }
            
            if ($this->input->get('from') && $this->input->get('from')) {
                $PaymentsRequestsWhere['date >= '] = $this->input->get('from');
                $PaymentsRequestsWhere['date <= '] = $this->input->get('to');
            } else {
                $PaymentsRequestsWhere['date >= '] = date('Y-m-d');
                $PaymentsRequestsWhere['date <= '] = date('Y-m-d');
            }
            
            if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                $Where['Id'] = $EmployeeId;
                $EmployeeIds = json_decode(get_title('employees', $Where, 'WarehouseSalesman'), true) ?: [];
                foreach ($EmployeeIds as $key => $Id) {
                    $EmployeeWhere[] = $Id;
                }
            }
                        
            if ($this->input->get('status') != "") {
                if ($this->input->get('status') != "All") {
                    $PaymentsRequestsWhere['status'] = $this->input->get('status');
                }
            }
            if (employee_has_perm('PaymentsRequest', 'View', $EmployeePermissionWhere)) {
                $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                $data['PaymentsRequests'] = $this->db->where($PaymentsRequestsWhere)->where_in('SalesmanId', $EmployeeWhere)->order_by('id', 'DESC')->get('paymentrequest')->result();
                $data['page'] = "PaymentRequestReport";
                return $this->load->view('inventory_sep/Employee/Employee', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/Dashboard');
            }
        }
        
        public function PaymentRequestData($Id)
        {
            if (!empty($Id)) {
                $EmployeeId = $this->session->userdata('EmployeeId');
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
                $CompBranchId = $this->session->userdata('CompBranchId');

                $PaymentsRequestsWhere = ['id' => $Id, 'SalesmanId' => $EmployeeId, 'user_id' => $UserId, 'comp_id' => $CompId];
                $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $PaymentsRequestsWhere['CompanyBranchId'] = $CompBranchId;
                    $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                }
            
                if (employee_has_perm('PaymentsRequest', 'View', $EmployeePermissionWhere)) {
                    $data['PaymentRequest'] = $this->db->select('*')->where($PaymentsRequestsWhere)->get('paymentrequest')->row();
                    return $this->load->view('inventory_sep/Employee/PaymentRequestData', $data);
                } else {
                    $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                    return redirect(base_url() . 'Employee/Dashboard');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Payment request id is missing.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/PaymentRequestReport');
            }
        }
        
        public function paymentRequestEdit($Id) 
        {
            if (!empty($Id)) {
                $EmployeeId = $this->session->userdata('EmployeeId');
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
                $CompBranchId = $this->session->userdata('CompBranchId');
                $InvoiceId = get_title('paymentrequest', ['id' => $Id], 'invoiceId');
                
                $CommonWhere = ['id' => $InvoiceId, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
                $SalesWhere = ['user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
                $PaymentsRequestsWhere = ['id' => $Id, 'SalesmanId' => $EmployeeId, 'user_id' => $UserId, 'comp_id' => $CompId];
                $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $CommonWhere['CompanyBranchId'] = $CompBranchId;
                    $SalesWhere['CompanyBranchId'] = $CompBranchId;
                    $PaymentsRequestsWhere['CompanyBranchId'] = $CompBranchId;
                    $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                }
            
                if (employee_has_perm('PaymentsRequest', 'Edit', $EmployeePermissionWhere)) {
                    if (is_array(json_decode(get_title('paymentrequest', ['id' => $Id], 'invoiceId'))) && is_array(json_decode(get_title('paymentrequest', ['id' => $Id], 'Type')))) {
                        $Type = json_decode(get_title('paymentrequest', ['id' => $Id], 'Type'));
                        $Sales = $this->db->select('id, invoiceDate, invoiceNumber, type, total, paid_amount')->where($SalesWhere)->where_in('id', json_decode($InvoiceId))->where_in('type', $Type)->get_compiled_select('sales');
                        $Estimates = $this->db->select('id, invoiceDate, invoiceNumber, type, total, paid_amount')->where($SalesWhere)->where_in('id', json_decode($InvoiceId))->where_in('type', $Type)->get_compiled_select('estimates');
                        $data['PaymentRequestInvoices'] = $this->db->query($Sales . " UNION " . $Estimates . " ORDER BY id ASC")->result();                        
                    } else {
                        if ($this->db->select('id')->where($CommonWhere)->where('type', 'Sale')->get('sales')->num_rows()) {
                            $data['InvoiceData'] = $this->db->select('id, invoiceNumber, invoiceDate, customerId, type, total, paid_amount')->where($CommonWhere)->get('sales')->row();
                        }
                        if ($this->db->select('id')->where($CommonWhere)->where('type', 'Estimate')->get('estimates')->num_rows()) {
                            $data['InvoiceData'] = $this->db->select('id, invoiceNumber, invoiceDate, customerId, type, total, paid_amount')->where($CommonWhere)->get('estimates')->row();
                        }
                    }
                    
                    $data['PaymentMode'] = $this->db->select('id, payment_method')->get('payment_mode')->result();
                    $data['PaymentRequest'] = $this->db->select('*')->where($PaymentsRequestsWhere)->get('paymentrequest')->row();
                    return $this->load->view('inventory_sep/Employee/PaymentRequestEdit', $data);
                } else {
                    $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                    return redirect(base_url() . 'Employee/Dashboard');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Payment request id is missing.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/PaymentRequestReport');
            }
        }
        
        public function paymentRequestUpdate() 
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $requestId = $this->input->post('requestId');
                $EmployeeId = $this->session->userdata('EmployeeId');
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
                $CompBranchId = $this->session->userdata('CompBranchId');

                $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
                $requestUpdateWhere = ['id' => $requestId, 'SalesmanId' => $EmployeeId, 'user_id' => $UserId, 'comp_id' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $requestUpdateWhere['CompanyBranchId'] = $CompBranchId;
                    $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                }
                
                if (employee_has_perm('PaymentsRequest', 'Edit', $EmployeePermissionWhere)) {
                    $PaymentData = [
                        'payment_date' => $this->input->post('payment_date'),
                        'payment_mode' => $this->input->post('payment_mode'),
                        'ChequeDate' => $this->input->post('ChequeDate'),
                        'payment_amount' => $this->input->post('payment_amount'),
                        'remark' => $this->input->post('remark'),
                        'updated' => date('d-m-Y h:i:s A'),
                        'updated_by' => get_title('employees', ['Id' => $EmployeeId], 'Name')
                    ];

                    $result = $this->user_model->updateAry('paymentrequest', $PaymentData, $requestUpdateWhere);
                    if (!empty($result)) {
                        $this->session->set_flashdata(['msg' => 'Record update successfully.', 'class' => 'success']);
                        return redirect(base_url() . 'Employee/PaymentRequestReport');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to update record.', 'class' => 'danger']);
                        return redirect(base_url() . 'Employee/PaymentRequestReport');
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                    return redirect(base_url() . 'Employee/Dashboard');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Direct entry not allowed.', 'class' => 'danger']);
                return redirect('Employee/Dashboard');
            }
        }
        
        public function paymentRequestDelete($Id) 
        {
            if (!empty($Id)) {
                $EmployeeId = $this->session->userdata('EmployeeId');
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
                $CompBranchId = $this->session->userdata('CompBranchId');

                $PaymentsRequestsWhere = ['id' => $Id, 'SalesmanId' => $EmployeeId, 'user_id' => $UserId, 'comp_id' => $CompId];
                $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $PaymentsRequestsWhere['CompanyBranchId'] = $CompBranchId;
                    $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                }
            
                if (employee_has_perm('PaymentsRequest', 'Delete', $EmployeePermissionWhere)) {
                    $result = $this->user_model->deleteRow('paymentrequest', $PaymentsRequestsWhere);
                    if (!empty($result)) {
                        $this->session->set_flashdata(['msg' => 'Record update successfully.', 'class' => 'success']);
                        return redirect(base_url() . 'Employee/PaymentRequestReport');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to update record.', 'class' => 'danger']);
                        return redirect(base_url() . 'Employee/PaymentRequestReport');
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                    return redirect(base_url() . 'Employee/Dashboard');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Payment request id is missing.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/PaymentRequestReport');
            }
        }
        
        // Stocks
        public function StockReport() 
        {
            $EmployeeId = $this->session->userdata('EmployeeId');
            $UserId = $this->session->userdata('UserId');
            $CompId = $this->session->userdata('CompId');
            $WarehouseId = $this->session->userdata('WarehouseId');
            $CompBranchId = $this->session->userdata('CompBranchId');

            $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
            $WarehouseWhere = ['id' => $WarehouseId, 'user_id' => $UserId, 'comp_id' => $CompId];
            $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $CompBranchId;
                $WarehouseWhere['CompanyBranchId'] = $CompBranchId;
                $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
            }

            if (employee_has_perm('Stocks', 'View', $EmployeePermissionWhere)) {
                $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                $data['Warehouses'] = $this->db->select('id, name, location')->where($WarehouseWhere)->get('warehouse')->result();
                $data['page'] = "StockReport";
                return $this->load->view('inventory_sep/Employee/Employee', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/Dashboard');
            }
        }
        
        public function getWarehouseStockReport() 
        {
            $EmployeeId = $this->session->userdata('EmployeeId');
            $UserId = $this->session->userdata('UserId');
            $CompId = $this->session->userdata('CompId');
            $WarehouseId = $this->session->userdata('WarehouseId');
            $CompBranchId = $this->session->userdata('CompBranchId');

            $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
            $ProductWhere = ['user_id' => $UserId, 'comp_id' => $CompId];
            $WarehouseWhere = ['id' => $WarehouseId, 'user_id' => $UserId, 'comp_id' => $CompId];
            $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $CompBranchId;
                $ProductWhere['CompanyBranchId'] = $CompBranchId;
                $WarehouseWhere['CompanyBranchId'] = $CompBranchId;
                $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
            }

            if (employee_has_perm('Stocks', 'View', $EmployeePermissionWhere)) {
                $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                $data['Warehouses'] = $this->db->select('id, name, location')->where($WarehouseWhere)->get('warehouse')->result();
                $data['Products'] = $this->db->select('*')->where($ProductWhere)->get('product')->result();
                $data['page'] = "getWarehouseStockReport";
                return $this->load->view('inventory_sep/Employee/Employee', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/Dashboard');
            }
        }
        
        // Purchase order
        public function getProductDetails()
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $postData = $this->input->post('product_out_stock');
                $product = [];
                $status = 0;
                if (count($postData) > 0) {
                    $status = 1;
                    $product = $this->db->select('id, p_name, price, qty, unit, tax, unit_name, hsn')->where(['status' => 1])->where_in('id', $postData)->get('product')->result();
                }
                echo json_encode(['status' => $status, 'result' => $product]);
            } else {
                echo json_encode(['status' => $status, 'result' => '']);
            }
        }
        
        public function PurchaseOrderSave() 
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $EmployeeId = $this->session->userdata('EmployeeId');
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
                $CompBranchId = $this->session->userdata('CompBranchId');
                $Date = $this->input->post('Date');
                $CompanyId = $this->input->post('CompanyId');
                $SubTotal = $this->input->post('SubTotal');
                
                $PurchaseOrderWhere = ['PurchaseDate' => $Date, 'CompanyId' => $CompanyId, 'SubTotal' => $SubTotal, 'UserId' =>$UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
                $CompProfileWhere = ['user_id' => $UserId, 'comp_id' => $CompId];
                $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
                if ($this->session->userdata('BranchRole')) {
                    $PurchaseOrderWhere['CompanyBranchId'] = $CompBranchId;
                    $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                    $CompProfileWhere['CompanyBranchId'] = $CompBranchId;
                }
            
                if (employee_has_perm('PurchaseOrder', 'Create', $EmployeePermissionWhere)) {
                    $FinancialYear = get_title('comp_profile', $CompProfileWhere, 'financial_year');
                    
                    if (empty($this->db->where($PurchaseOrderWhere)->get('purchaseorder')->num_rows())) {
                        $OrderNumber = mt_rand(00000, 99999);
                        $PurchaseOrderData = [
                            'PurchaseDate' => $Date,
                            'PurchaseNumber' => $OrderNumber,
                            'CompanyId' => $CompanyId,
                            'SubTotal' => $SubTotal, 
                            'UserId' => $UserId,
                            'CompId' => $CompId,
                            'CompanyBranchId' => $CompBranchId,
                            'EmployeeId' => $EmployeeId,
                            'FinancialYear' => $FinancialYear,
                            'Remark' => $this->input->post('Remark'),
                            'Created' => date('d-m-Y h:i:s A'),
                            'CreatedBy' => get_title('employees', ['Id' => $EmployeeId], 'Name'),
                        ];
                        $Result = $this->user_model->saveAry('purchaseorder', $PurchaseOrderData);

                        foreach ($this->input->post('Products') as $key => $Product) {
                            if (!empty($Product['Quantity']) && !empty($Product['ProductId'])) {
                                $PurchaseOrderDataItems = [
                                    "PurchaseOrderId" => $Result,
                                    'PurchaseOrderNumber' => $OrderNumber,
                                    "PurchaseOrderDate" => $Date,
                                    "ProductName" => $Product['ProductName'],
                                    "ProductId" => $Product['ProductId'],
                                    "Price" => $Product['ProductPrice'],
                                    "Quantity" => $Product['Quantity'],
                                    "Total" => $Product['Total'],
                                    'UserId' => $UserId,
                                    'CompId' => $CompId,
                                    'CompanyBranchId' => $CompBranchId,
                                    'EmployeeId' => $EmployeeId,
                                    'FinancialYear' => $FinancialYear,
                                    "Created" => date('d-m-Y h:i:s A'),
                                    "CreatedBy" => get_title('employees', ['Id' => $EmployeeId], 'Name')
                                ];
                                $this->user_model->saveAry('purchaseorder_products', $PurchaseOrderDataItems);
                            }
                        }

                        if (!empty($Result)) {
                            $this->session->set_flashdata(['msg' => 'Record saved successfully.', 'class' => 'success']);
                            return redirect(base_url() . 'Employee/PurchaseOrderReport');
                        } else {
                            $this->session->set_flashdata(['msg' => 'Unable to save record.', 'class' => 'danger']);
                            $Warehouse = $this->input->get('warehouse');
                            $TransferType = $this->input->get('transfer_type');
                            return redirect(base_url() . 'Employee/getWarehouseStockReport?warehouse=' . $Warehouse . '&transfer_type=' . $TransferType);
                        }
                    } else {
                        $this->session->set_flashdata(['msg' => 'Duplicate entry not allowed on same date.', 'class' => 'danger']);
                        return redirect(base_url() . 'Employee/PurchaseOrderReport');                        
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                    return redirect(base_url() . 'Employee/Dashboard');
                }
            }   
        }
        
        public function PurchaseOrderReport() 
        {
            $EmployeeId = $this->session->userdata('EmployeeId');
            $UserId = $this->session->userdata('UserId');
            $CompId = $this->session->userdata('CompId');
            $CompBranchId = $this->session->userdata('CompBranchId');
            $FinancialYear = $this->session->userdata('financial_year');

            $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
            $PurchaseOrderWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1, 'FinancialYear' => $FinancialYear];
            $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $CompBranchId;
                $PurchaseOrderWhere['CompanyBranchId'] = $CompBranchId;
                $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
            }

            if (employee_has_perm('PurchaseOrder', 'View', $EmployeePermissionWhere)) {
                $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                $data['PurchasesOrders'] = $this->db->select('Id, PurchaseDate, PurchaseNumber, CompanyId, SubTotal, CompanyBranchId, InvoiceStatus')->where($PurchaseOrderWhere)->order_by('Id', 'DESC')->get('purchaseorder')->result();
                $data['page'] = "PurchaseOrderReport";
                return $this->load->view('inventory_sep/Employee/Employee', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/Dashboard');
            }
        }
        
        public function PurchaseOrderInfo($Id) 
        {
            $EmployeeId = $this->session->userdata('EmployeeId');
            $UserId = $this->session->userdata('UserId');
            $CompId = $this->session->userdata('CompId');
            $CompBranchId = $this->session->userdata('CompBranchId');
            $FinancialYear = $this->session->userdata('financial_year');

            $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
            $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
            $PurchaseOrderWhere = ['Id' => $Id, 'EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1, 'FinancialYear' => $FinancialYear];
            $ItemsWhere = ['PurchaseOrderId' => $Id, 'IsDeleted' => 1, 'EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId, 'FinancialYear' => $FinancialYear];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $CompBranchId;
                $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                $PurchaseOrderWhere['CompanyBranchId'] = $CompBranchId;
                $ItemsWhere['CompanyBranchId'] = $CompBranchId;
            }

            if (employee_has_perm('PurchaseOrder', 'View', $EmployeePermissionWhere)) {
                $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                $data['PurchaseOrder'] = $this->db->select('Id, PurchaseDate, PurchaseNumber, CompanyId, CompanyBranchId, SubTotal, Remark, Created, CreatedBy, Updated, UpdatedBy')->where($PurchaseOrderWhere)->get('purchaseorder')->row();
                $data['PurchaseOrderItems'] = $this->db->select('ProductId, Price, Quantity, Total')->where($ItemsWhere)->order_by('Id', 'DESC')->get('purchaseorder_products')->result(); 
                $data['page'] = "PurchaseOrderInfo";
                return $this->load->view('inventory_sep/Employee/Employee', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/Dashboard');
            }
        }
        
        public function PurchaseOrderEdit($Id) 
        {
            $EmployeeId = $this->session->userdata('EmployeeId');
            $UserId = $this->session->userdata('UserId');
            $CompId = $this->session->userdata('CompId');
            $CompBranchId = $this->session->userdata('CompBranchId');
            $FinancialYear = $this->session->userdata('financial_year');

            $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
            $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
            $PurchaseOrderWhere = ['Id' => $Id, 'EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1, 'FinancialYear' => $FinancialYear];
            $ItemsWhere = ['PurchaseOrderId' => $Id, 'IsDeleted' => 1, 'EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId, 'FinancialYear' => $FinancialYear];
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $CompBranchId;
                $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                $PurchaseOrderWhere['CompanyBranchId'] = $CompBranchId;
                $ItemsWhere['CompanyBranchId'] = $CompBranchId;
            }

            if (employee_has_perm('PurchaseOrder', 'Edit', $EmployeePermissionWhere)) {
                $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                $data['ParentCompany'] = $this->db->select('id, company_name')->where('id', $CompId)->order_by('id', 'DESC')->get('inventory_billing')->result();
                $data['PurchaseOrder'] = $this->db->select('Id, PurchaseDate, CompanyId, SubTotal, Remark')->where($PurchaseOrderWhere)->get('purchaseorder')->row(); 
                $data['PurchaseOrderId'] = $Id;
                $data['page'] = "PurchaseOrderEdit";
                return $this->load->view('inventory_sep/Employee/Employee', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/Dashboard');
            }
        }
        
        public function getPurchaseOrderProduct($OrderBookId)
        {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                if (!empty($OrderBookId)) {
                    $ItemsWhere = ['PurchaseOrderId' => $OrderBookId, 'EmployeeId' => $this->session->userdata('EmployeeId'), 'IsDeleted' => 1, 'UserId' => $this->session->userdata('UserId'), 'CompId' => $this->session->userdata('CompId'), 'FinancialYear' => $this->session->userdata('financial_year')];
                    if ($this->session->userdata('BranchRole')) {
                        $ItemsWhere['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                    }
                    $OrderBookItems = $this->db->select('Id, ProductName, ProductId, Price, Quantity, Total')->where($ItemsWhere)->order_by('Id', 'DESC')->get('purchaseorder_products')->result();
                    if ($OrderBookItems) {
                        echo json_encode(array('Status' => true, "Data" => $OrderBookItems));
                    } else {
                        echo json_encode(array('Status' => false, "Data" => ""));
                    }
                } else {
                    echo json_encode(array('Status' => false, "Data" => ""));
                }
            }
        }
        
        public function PurchaseOrderUpdate($Id) 
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $EmployeeId = $this->session->userdata('EmployeeId');
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
                $CompBranchId = $this->session->userdata('CompBranchId');
            
                $CompProfileWhere = ['user_id' => $UserId, 'comp_id' => $CompId];
                $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
                
                if ($this->session->userdata('BranchRole')) {
                    $CompProfileWhere['CompanyBranchId'] = $CompBranchId;
                }
                $FinancialYear = get_title('comp_profile', $CompProfileWhere, 'financial_year');
                
                $Where = ['Id' => $this->input->post('PurchaseOrderId'), 'EmployeeId' => $EmployeeId, 'IsDeleted' => 1, 'UserId' => $UserId, 'CompId' => $CompId, 'FinancialYear' => $FinancialYear];
                $ItemsWhere = ['PurchaseOrderId' => $this->input->post('PurchaseOrderId'), 'EmployeeId' => $EmployeeId, 'IsDeleted' => 1, 'UserId' => $UserId, 'CompId' => $CompId, 'FinancialYear' => $FinancialYear];
                if ($this->session->userdata('BranchRole')) {
                    $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                    $Where['CompanyBranchId'] = $CompBranchId;
                    $ItemsWhere['CompanyBranchId'] = $CompBranchId;
                }
            
                if (employee_has_perm('PurchaseOrder', 'Edit', $EmployeePermissionWhere)) {
                    $OrderbookData = [
                        'PurchaseDate' => $this->input->post('Date'),
                        'CompanyId' => $this->input->post('CompanyId'),
                        'SubTotal' => $this->input->post('SubTotal'),
                        'Remark' => $this->input->post('Remark'),
                        'Updated' => date('d-m-Y h:i:s A'),
                        'UpdatedBy' => get_title('employees', ['Id' => $EmployeeId], 'Name'),
                    ];                    
                    $Result = $this->user_model->updateAry('purchaseorder', $OrderbookData, $Where);
                    
                    $PreviewsOrderItems = $this->db->where($ItemsWhere)->get('purchaseorder_products')->result();
                    foreach ($PreviewsOrderItems as $key => $Items) {
                        $this->db->where('Id', $Items->Id)->delete('purchaseorder_products');
                    }

                    foreach ($this->input->post('Products') as $key => $Product) {
                        if (!empty($Product['Quantity']) && !empty($Product['ProductId'])) {
                            $OrderbookDataItems = [
                                "PurchaseOrderId" => $this->input->post('PurchaseOrderId'),
                                'PurchaseOrderNumber' => get_title('purchaseorder', ['Id' => $this->input->post('PurchaseOrderId')], 'PurchaseNumber'),
                                "PurchaseOrderDate" => $this->input->post('Date'),
                                "ProductName" => $Product['ProductName'],
                                "ProductId" => $Product['ProductId'],
                                "Price" => $Product['ProductPrice'],
                                "Quantity" => $Product['Quantity'],
                                "Total" => $Product['Total'],
                                "UserId" => $UserId,
                                "CompId" => $CompId,
                                'CompanyBranchId' => $CompBranchId,
                                'EmployeeId' => $EmployeeId,
                                'FinancialYear' => $FinancialYear,
                                "Created" => date('d-m-Y h:i:s A'),
                                "CreatedBy" => get_title('employees', ['Id' => $EmployeeId], 'Name')
                            ];
                            $this->user_model->saveAry('purchaseorder_products', $OrderbookDataItems);
                        }
                    } 
                    if (!empty($Result)) {
                        $this->session->set_flashdata(['msg' => 'Record saved successfully.', 'class' => 'success']);
                        return redirect(base_url() . 'Employee/PurchaseOrderReport');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to save record.', 'class' => 'danger']);
                        return redirect(base_url() . 'Employee/PurchaseOrderEdit/' . $Id);
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                    return redirect(base_url() . 'Employee/Dashboard');
                }
            }
        }
        
        public function PurchaseOrderDelete($Id) 
        {
            if (!empty($Id)) {
                $EmployeeId = $this->session->userdata('EmployeeId');
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
                $CompBranchId = $this->session->userdata('CompBranchId');
                $FinancialYear = $this->session->userdata('financial_year');
                
                $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
                $Where = ['Id' => $Id, 'IsDeleted' => 1, 'EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId, 'FinancialYear' => $FinancialYear];
                if ($this->session->userdata('BranchRole')) {
                    $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                    $Where['CompanyBranchId'] = $CompBranchId;
                }

                if (employee_has_perm('PurchaseOrder', 'Delete', $EmployeePermissionWhere)) {
                    if ($this->db->where($Where)->get('PurchaseOrder')->num_rows()) {
                        if ($this->user_model->updateAry('purchaseorder', ['IsDeleted' => 0], $Where)) {
                            $ItemsWhere = ['PurchaseOrderId' => $Id, 'IsDeleted' => 1, 'EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId, 'FinancialYear' => $FinancialYear];
                            if ($this->session->userdata('BranchRole')) {
                                $ItemsWhere['CompanyBranchId'] = $CompBranchId;
                            }
                            $this->user_model->updateAry('purchaseorder_products', ['IsDeleted' => 0], $ItemsWhere);
                            $this->session->set_flashdata(['msg' => 'Record delete successfully.', 'class' => 'success']); 
                            return redirect(base_url() . 'Employee/PurchaseOrderReport');
                        } else {
                            $this->session->set_flashdata(['msg' => 'Unable to delete record.', 'class' => 'danger']);
                            return redirect(base_url() . 'Employee/PurchaseOrderReport');
                        }
                    } else {
                        $this->session->set_flashdata(['msg' => 'Record not found.', 'class' => 'danger']);
                        return redirect(base_url() . 'Employee/PurchaseOrderReport');
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                    return redirect(base_url() . 'Employee/Dashboard');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Purchase Order id is missing.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/PurchaseOrderReport');
            }
        }
        
        // Attendance
        public function EMPAttendance() 
        {
            $EmployeeId = $this->session->userdata('EmployeeId');
            $UserId = $this->session->userdata('UserId');
            $CompId = $this->session->userdata('CompId');
            $CompBranchId = $this->session->userdata('CompBranchId');
            $FinancialYear = $this->session->userdata('financial_year');
                
            $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
            $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
            $EmployeeAttendanceWhere = ['UserId' => $UserId, 'CompId' => $CompId, 'FinancialYear' => $FinancialYear];
            $EmployeeWhere = [$EmployeeId];
                
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $CompBranchId;
                $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                $EmployeeAttendanceWhere['CompanyBranchId'] = $CompBranchId;
            }
                
            if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                $Where['Id'] = $EmployeeId;
                $EmployeeIds = json_decode(get_title('employees', $Where, 'WarehouseSalesman'), true) ?: [];
                foreach ($EmployeeIds as $key => $Id) {
                    $EmployeeWhere[] = $Id;
                }
            }
                
            if (employee_has_perm('Attendance', 'View', $EmployeePermissionWhere)) {
                $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                $data['EmployeesAttendance'] = $this->db->select('*')->where($EmployeeAttendanceWhere)->where_in('EmpId', $EmployeeWhere)->get('employee_attendance')->result();
                $data['page'] = "EMPAttendance";
                return $this->load->view('inventory_sep/Employee/Employee', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/Dashboard');
            }
        }
        
        public function EMPAttendanceCreate() 
        {
            $EmployeeId = $this->session->userdata('EmployeeId');
            $UserId = $this->session->userdata('UserId');
            $CompId = $this->session->userdata('CompId');
            $CompBranchId = $this->session->userdata('CompBranchId');
                
            $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
            $EMPWhere = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
            $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
            $EmployeeWhere = [$EmployeeId];
                
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $CompBranchId;
                $EMPWhere['CompanyBranchId'] = $CompBranchId;
                $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
            }
                
            if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                $EMPWhere['Id'] = $EmployeeId;
                $EmployeeIds = json_decode(get_title('employees', $EMPWhere, 'WarehouseSalesman'), true) ?: [];
                foreach ($EmployeeIds as $key => $Id) {
                    $EmployeeWhere[] = $Id;
                }
            }
                
            if (employee_has_perm('Attendance', 'Create', $EmployeePermissionWhere)) {
                $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                $data['Employees'] = $this->db->select('Id, Name, RoleId')->where($Where)->where_in('Id', $EmployeeWhere)->get('employees')->result();
                $data['page'] = "EMPAttendanceCreate";
                return $this->load->view('inventory_sep/Employee/Employee', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/Dashboard');
            }
        }
        
        public function EMPAttendanceSave() 
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $EmployeeId = $this->session->userdata('EmployeeId');
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
                $CompBranchId = $this->session->userdata('CompBranchId');
                $FormData = $this->input->post();  
                
                $CompProfileWhere = ['user_id' => $UserId, 'comp_id' => $CompId];
                $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
                $EMPConfigWhere = ['UserId' => $UserId, 'CompId' => $CompId];
                $EMPWhere = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
                $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
                $EmployeeWhere = [$EmployeeId];
                $EmployeeAttendanceWhere = ['EmpId' => $EmployeeId, 'Date' => $FormData['Date'], 'UserId' => $UserId, 'CompId' => $CompId, 'FinancialYear' => $FinancialYear];
                
                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $CompBranchId;
                    $EMPConfigWhere['CompanyBranchId'] = $CompBranchId;
                    $EMPWhere['CompanyBranchId'] = $CompBranchId;
                    $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                    $EmployeeAttendanceWhere['CompanyBranchId'] = $CompBranchId;
                    $CompProfileWhere['CompanyBranchId'] = $CompBranchId;
                }
                
                $FinancialYear = get_title('comp_profile', $CompProfileWhere, 'financial_year');

                if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                    $EMPWhere['Id'] = $EmployeeId;
                    $EmployeeIds = json_decode(get_title('employees', $EMPWhere, 'WarehouseSalesman'), true) ?: [];
                    foreach ($EmployeeIds as $key => $Id) {
                        $EmployeeWhere[] = $Id;
                    }
                }
                
                $this->form_validation->set_rules('Date', 'Date', 'required|trim');
                $this->form_validation->set_rules('Attendance', 'Attendance', 'required|trim');
                $this->form_validation->set_rules('EmpId', 'Employee', 'required|trim');
                if (($this->input->post('Attendance') == "Half Day") || $this->input->post('Attendance') == "Present") {
                    $this->form_validation->set_rules('InTime', 'In Time', 'required|trim');
                    $this->form_validation->set_rules('ShiftType', 'Shift Type', 'required|trim');
                } 

                // rule 1 :- formal validation
                if ($this->form_validation->run() == FALSE) {
                    $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                    $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                    $data['Employees'] = $this->db->select('Id, Name, RoleId')->where($Where)->where_in('Id', $EmployeeWhere)->get('employees')->result();
                    $data['error'] = validation_errors();
                    $data['page'] = "EMPAttendanceCreate";
                    return $this->load->view('inventory_sep/Employee/Employee', $data);
                } else {
                    // rule 2 :- check today attendacne 
                    if ($this->db->select('Id')->where($EmployeeAttendanceWhere)->get('employee_attendance')->num_rows()) {
                        $this->session->set_flashdata(['msg' => 'Your attendance has been already marked.', 'class' => 'danger']);
                        $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                        $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                        $data['Employees'] = $this->db->select('Id, Name, RoleId')->where($Where)->where_in('Id', $EmployeeWhere)->get('employees')->result();
                        $data['error'] = validation_errors();
                        $data['page'] = "EMPAttendanceCreate";
                        return $this->load->view('inventory_sep/Employee/Employee', $data);
                    }
                    

                    if ($this->db->select('LoginIPValidation')->where($EMPConfigWhere)->get('employee_config')->row('LoginIPValidation') == "Yes") {
                        // rule 3 :- check system ip address 
                        if ($this->db->select('IPAddress')->where($Where)->where_in('Id', $EmployeeWhere)->get('employees')->row('IPAddress') != ip_address()) {
                            $this->session->set_flashdata(['msg' => 'Your ip address does not match', 'class' => 'danger']);
                            $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                            $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                            $data['Employees'] = $this->db->select('Id, Name, RoleId')->where($Where)->where_in('Id', $EmployeeWhere)->get('employees')->result();
                            $data['error'] = validation_errors();
                            $data['page'] = "EMPAttendanceCreate";
                            return $this->load->view('inventory_sep/Employee/Employee', $data);
                        }
                    }
                    
                    if (employee_has_perm('Attendance', 'Create', $EmployeePermissionWhere)) {
                        $EmployeeAttendanceData = [
                            "Date" => $FormData['Date'],
                            "InTime" => $FormData['InTime'],
                            "ShiftType" => $FormData['ShiftType'],
                            "Attendance" => $FormData['Attendance'],
                            "Month" => date('F', strtotime($FormData['Date'])),
                            "EmpId" => $FormData['EmpId'],
                            "UserId" => $UserId,
                            "CompId" => $CompId,
                            "CompanyBranchId" => $CompBranchId,
                            "FinancialYear" => $FinancialYear,
                            "Created" => date('d-m-Y h:i:s A'),
                            "CreatedBy" => get_title('employees', ['Id' => $EmployeeId], 'Name')
                        ];

                        $Result = $this->user_model->saveAry('employee_attendance', $EmployeeAttendanceData);
                        if ($Result) {
                            $this->session->set_flashdata(['msg' => 'Attendance marked successfully.', 'class' => 'success']);
                            return redirect(base_url() . 'Employee/EMPAttendance');
                        } else {
                            $this->session->set_flashdata(['msg' => 'Failed, try again', 'class' => 'danger']);
                            return redirect('Employee/EMPAttendanceCreate');
                        }
                    } else {
                        $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                        return redirect(base_url() . 'Employee/Dashboard');
                    }
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Direct entry not allowed.', 'class' => 'danger']);
                return redirect('Employee/EMPAttendanceCreate');
            }
        }
        
        public function EMPAttendanceEdit($AttendanceId) 
        {
            if (isset($AttendanceId) && !empty($AttendanceId)) {
                $EmployeeId = $this->session->userdata('EmployeeId');
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
                $CompBranchId = $this->session->userdata('CompBranchId');
                $FinancialYear = $this->session->userdata('financial_year');

                $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
                $EMPWhere = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
                $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
                $EmployeeAttendanceWhere = ['Id' => $AttendanceId, 'UserId' => $UserId, 'CompId' => $CompId, 'FinancialYear' => $FinancialYear];
                $EmployeeWhere = [$EmployeeId];

                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $CompBranchId;
                    $EMPWhere['CompanyBranchId'] = $CompBranchId;
                    $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                    $EmployeeAttendanceWhere['CompanyBranchId'] = $CompBranchId;
                }

                if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                    $EMPWhere['Id'] = $EmployeeId;
                    $EmployeeIds = json_decode(get_title('employees', $EMPWhere, 'WarehouseSalesman'), true) ?: [];
                    foreach ($EmployeeIds as $key => $Id) {
                        $EmployeeWhere[] = $Id;
                    }
                }

                if (employee_has_perm('Attendance', 'Edit', $EmployeePermissionWhere)) {
                    $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                    $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                    $data['Employees'] = $this->db->select('Id, Name, RoleId')->where($Where)->where_in('Id', $EmployeeWhere)->get('employees')->result();
                    $data['EmployeeAttendance'] = $this->db->select('*')->where($EmployeeAttendanceWhere)->where_in('EmpId', $EmployeeWhere)->get('employee_attendance')->row();
                    $data['page'] = "EMPAttendanceEdit";
                    return $this->load->view('inventory_sep/Employee/Employee', $data);
                } else {
                    $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                    return redirect(base_url() . 'Employee/Dashboard');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Attendance id is missing.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/EMPAttendance');
            }
        }
        
        public function EMPAttendanceUpdate($AttendanceId) 
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $EmployeeId = $this->session->userdata('EmployeeId');
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
                $CompBranchId = $this->session->userdata('CompBranchId');
                $FinancialYear = $this->session->userdata('financial_year');

                $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
                $EMPWhere = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
                $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
                $EmployeeAttendanceWhere = ['Id' => $AttendanceId, 'UserId' => $UserId, 'CompId' => $CompId, 'FinancialYear' => $FinancialYear];
                $EmployeeWhere = [$EmployeeId];
                $EmployeeAttendanceUpdateWhere = ['Id' => $this->input->post('AttendanceId'), 'UserId' => $UserId, 'CompId' => $CompId, 'FinancialYear' => $FinancialYear];
                        
                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $CompBranchId;
                    $EMPWhere['CompanyBranchId'] = $CompBranchId;
                    $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                    $EmployeeAttendanceWhere['CompanyBranchId'] = $CompBranchId;
                    $EmployeeAttendanceUpdateWhere['CompanyBranchId'] = $CompBranchId;
                }

                if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                    $EMPWhere['Id'] = $EmployeeId;
                    $EmployeeIds = json_decode(get_title('employees', $EMPWhere, 'WarehouseSalesman'), true) ?: [];
                    foreach ($EmployeeIds as $key => $Id) {
                        $EmployeeWhere[] = $Id;
                    }
                }
                
                $this->form_validation->set_rules('EmpId', 'Employee', 'required|trim');
                $this->form_validation->set_rules('OutTime', 'Out Time', 'required|trim');
                if ($this->form_validation->run() == FALSE) {                    
                    $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                    $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                    $data['Employees'] = $this->db->select('Id, Name, RoleId')->where($Where)->where_in('Id', $EmployeeWhere)->get('employees')->result();
                    $data['EmployeeAttendance'] = $this->db->select('*')->where($EmployeeAttendanceWhere)->where_in('EmpId', $EmployeeWhere)->get('employee_attendance')->row();
                    $data['error'] = validation_errors();
                    $data['page'] = "EMPAttendanceEdit";
                    return $this->load->view('inventory_sep/Employee/Employee', $data);
                } else {
                    if (employee_has_perm('Attendance', 'Edit', $EmployeePermissionWhere)) {
                        $InTime = $this->db->select('InTime')->where($EmployeeAttendanceWhere)->where_in('EmpId', $EmployeeWhere)->get('employee_attendance')->row('InTime');

                        $EmployeeAttendanceData = [
                            "EmpId" => $this->input->post('EmpId'),
                            "OutTime" => $this->input->post('OutTime'),
                            "WorkingHours" => differenceInHours($InTime, $this->input->post('OutTime')),
                            "Updated" => date('d-m-Y h:i:s A'),
                            "UpdatedBy" => get_title('employees', ['Id' => $EmployeeId], 'Name')
                        ];

                        $Result = $this->user_model->updateAry('employee_attendance', $EmployeeAttendanceData, $EmployeeAttendanceUpdateWhere);

                        if ($Result) {
                            $this->session->set_flashdata(['msg' => 'Out time updated Successfully.', 'class' => 'success']);
                            return redirect(base_url() . 'Employee/EMPAttendance');
                        } else {
                            $this->session->set_flashdata(['msg' => 'Failed. try again', 'class' => 'danger']);
                            return redirect(base_url() . 'Employee/EMPAttendanceEdit/' . $AttendanceId);
                        }
                    } else {
                        $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                        return redirect(base_url() . 'Employee/Dashboard');
                    }
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Direct entry not allowed.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/EMPAttendanceEdit/' . $AttendanceId);
            }
        }
        
        public function Calendar()
        {
            $EmployeeId = $this->session->userdata('EmployeeId');
            $CompId = $this->session->userdata('CompId');
            $UserId = $this->session->userdata('UserId');
            $CompanyBranchId = $this->session->userdata('CompBranchId');
            $FinancialYear = $this->session->userdata('financial_year');
            
            $EMPWhere = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
            $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
            $Where = ['user_id' => $UserId, 'comp_id' => $CompId, 'finacial_year' => $FinancialYear, 'status' => 1];
            $HolidaysWhere = ['user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
            if ($this->session->userdata('BranchRole')) {
                $EMPWhere['CompanyBranchId'] = $CompanyBranchId;
                $EmployeePermissionWhere['CompanyBranchId'] = $CompanyBranchId;
                $Where['CompanyBranchId'] = $CompanyBranchId;
                $HolidaysWhere['CompanyBranchId'] = $CompanyBranchId;
            }
            
            $HolidaysData = $this->db->where($Where)->get('holiday_details')->result();
            
            $tem_arr = []; 
            foreach ($HolidaysData as $key => $value) { 
                $return = $this->user_model->getRow('holidays', ['id' => $value->holiday_id]);  
                $value->title = $return->name;
                $value->start = $value->start_date; 
                $value->end = date('Y-m-d', strtotime($value->end_date . ' +1 day')); 
                
                unset($value->id);
                unset($value->holiday_id);
                unset($value->start_date);
                unset($value->end_date);
                unset($value->finacial_year);
                unset($value->month);
                unset($value->status);
                unset($value->created_at);
                unset($value->CompanyBranchId);
                unset($value->comp_id);
                unset($value->user_id);
                $tem_arr[] = $value;
            } 
            
            $Data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($EMPWhere)->get('employees')->row();
            $Data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
            $Data['holidays'] = $tem_arr;
            $Data['page'] = 'calendar';  
            return $this->load->view('inventory_sep/Employee/Employee', $Data);
        }
        
        
        public function DeliveryInfo($Id) 
        {
            if (!empty($Id)) {
                $EmployeeId = $this->session->userdata('EmployeeId');
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
                $CompanyBranchId = $this->session->userdata('CompBranchId');
                $FinancialYear = $this->session->userdata('financial_year');
                $SalesmanRoleId = get_title('position', ['PostName' => 'Salesman'], 'Id');
                $EMPWhere = ['Id' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
                
                $Where = ['id' => $Id, 'financial_year' => $FinancialYear, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
                $SalesmanWhere = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1, 'RoleId' => $SalesmanRoleId];
                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $CompanyBranchId;
                    $SalesmanWhere['CompanyBranchId'] = $CompanyBranchId;
                    $EMPWhere['CompanyBranchId'] = $CompanyBranchId;
                }
                
                $EmployeeWhere = [];
                $EmployeeIds = json_decode(get_title('employees', $EMPWhere, 'WarehouseSalesman'), true) ?: [];
                foreach ($EmployeeIds as $key => $Id) {
                    $EmployeeWhere[] = $Id;
                }
                
                if ($this->db->select('id')->where($Where)->where('type', 'Sale')->get('sales')->num_rows()) {
                    $Data['DeliveryInfo'] = $this->db->select('id, invoiceDate, invoiceNumber, customerId, total, type, delivery_date, delivery_status, DeliveredBy')->where($Where)->where('type', 'Sale')->get('sales')->row();
                } elseif ($this->db->select('id')->where($Where)->where('type', 'Estimate')->get('estimates')->num_rows()) {
                    $Data['DeliveryInfo'] = $this->db->select('id, invoiceDate, invoiceNumber, customerId, total, type, delivery_date, delivery_status, DeliveredBy')->where($Where)->where('type', 'Estimate')->get('estimates')->row();
                }
                $Data['Salesmans'] = $this->db->select('Id, Name')->where($SalesmanWhere)->where_in('Id', $EmployeeWhere)->order_by('Id', 'DESC')->get('employees')->result();
                return $this->load->view('inventory_sep/Employee/DeliveryInfoData', $Data);
            } else {
                $this->session->set_flashdata(['msg' => 'Invoice id missing', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);                
            }
        }
        
        public function UpdateDelivery()
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $InvoiceData = [
                    'delivery_date' => $this->input->post('DeliveryDate'),
                    'DeliveredBy' => $this->input->post('DeliveredBy'),
                    'delivery_status' => $this->input->post('DeliveryStatus')
                ];

                $Where = ['id' => $this->input->post('InvoiceId'), 'user_id' => $this->session->userdata('UserId'), 'comp_id' => $this->session->userdata('CompId')];
                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                }
                if ($this->input->post('InvoiceType') == "Sale") {
                    $Where['type'] = "Sale";
                    $InvoiceUpdate = $this->user_model->updateAry('sales', $InvoiceData, $Where);
                } elseif ($this->input->post('InvoiceType') == "Estimate") {
                    $Where['type'] = "Estimate";
                    $InvoiceUpdate = $this->user_model->updateAry('estimates', $InvoiceData, $Where);
                }
                
                if ($InvoiceUpdate) {
                    $this->session->set_flashdata(['msg' => 'Invoice data updated successfully', 'class' => 'success']);
                    return redirect($_SERVER['HTTP_REFERER']);
                } else {
                    $this->session->set_flashdata(['msg' => 'Unable to update data please try again', 'class' => 'danger']);
                    return redirect($_SERVER['HTTP_REFERER']);
                }
            }
        }
        
        public function DeliveryChatHandler($InvoiceId) 
        {
            if (!empty($InvoiceId)) {
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
                $CompBranchId = $this->session->userdata('CompBranchId');
                
                $SaleWhere = ['id' => $InvoiceId, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
                if ($this->session->userdata('BranchRole')) {
                    $SaleWhere['CompanyBranchId'] = $CompBranchId;
                }
                
                if ($this->db->select('id')->where($SaleWhere)->where('type', 'Sale')->get('sales')->num_rows()) {
                    $data['SaleInfo'] = $this->db->select('id, invoiceNumber, invoiceDate, customerId, total')->where($SaleWhere)->get('sales')->row();
                } elseif ($this->db->select('id')->where($SaleWhere)->where('type', 'Estimate')->get('estimates')->num_rows()) {
                    $data['SaleInfo'] = $this->db->select('id, invoiceNumber, invoiceDate, customerId, total')->where($SaleWhere)->get('estimates')->row();
                }
                return $this->load->view('inventory_sep/Employee/DeliveryChatHandler', $data);
            }
        }
        
        public function DeliveryChatSave() 
        {
            if ($this->input->is_ajax_request()) {
                $EmployeeId = $this->session->userdata('EmployeeId');
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
                $CompBranchId = $this->session->userdata('CompBranchId');
                
                $this->form_validation->set_rules('Chat', 'Chat', 'trim|required');
                if ($this->form_validation->run() == false) {
                    echo json_encode(['Status' => false, 'Message' => validation_errors()]);
                    return;
                } else {
                    $DeliveryChatData = [
                        'Date' => date('Y-m-d'),
                        'InvoiceId' => $this->input->post('InvoiceId'),
                        'Chat' => $this->input->post('Chat'),
                        'EmployeeId' => $EmployeeId,
                        'UserId' => $UserId,
                        'CompId' => $CompId,
                        'CompanyBranchId' => $CompBranchId,
                        'Created' => date('d-m-Y h:i:s A'),
                        'CreatedBy' => get_title('employees', ['Id' => $EmployeeId], 'Name'),
                    ];
                    
                    $Result = $this->user_model->saveAry('sale_delivery_chat', $DeliveryChatData);
                    if (!empty($Result)) {
                        echo json_encode(['Status' => true, 'Message' => 'Message send successfully.']);
                        return;
                    } else {
                        echo json_encode(['Status' => false, 'Message' => 'Unable to send Message.']);
                        return;
                    }
                }
            }
        }
        
        public function getSaleDeliveryChat() 
        {
            if ($this->input->is_ajax_request()) {
                $InvoiceId = $this->input->post('InvoiceId');
                if ($InvoiceId == "" && $InvoiceId == null) {
                    echo json_encode(['Status' => false, 'Chat' => '<span class="text-danger text-center">Enter required fields.</span>', 'From' => '', 'To' => '']);
                } else {
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
                    $CompBranchId = $this->session->userdata('CompBranchId');
                                
                    $Where = ['InvoiceId' => $InvoiceId, 'UserId' => $UserId, 'CompId' => $CompId];
                    if ($this->session->userdata('BranchRole')) {
                        $Where['CompanyBranchId'] = $CompBranchId; 
                    }
                    
                    $DeliveryChat = $this->db->select('Chat, Created, CreatedBy')->where($Where)->order_by('Id', 'ASC')->get('sale_delivery_chat')->result();

                    if ($DeliveryChat) {
                        $ImageUrl = base_url() . 'assets/images/avatar.png';
                        $chatHtml = '';

                        foreach ($DeliveryChat as $key => $Chat) {
                            $chatHtml .= '<div class="card-comment">'
                                      . '<img class="img-circle img-sm" src="' . $ImageUrl . '" alt="User-Image" />'
                                      . '<div class="comment-text">'
                                      . '<span class="username">'
                                      . $Chat->CreatedBy
                                      . '<span class="text-muted float-right">'
                                      . $Chat->Created
                                      . '</span>'
                                      . '</span>'
                                      . $Chat->Chat
                                      . '</div>'
                                      . '</div>';
                        }
                        echo json_encode(['Status' => true, 'Chat' => $chatHtml]);
                    } else {
                        echo json_encode(['Status' => false, 'Chat' => '<span class="text-danger text-center">No Record found.</span>', 'From' => '', 'To' => '']);
                    }
                }
            }
        }
        
        public function PartiesOutstanding() 
        {
            $employeeId = $this->session->userdata('EmployeeId');
            $userId = $this->session->userdata('UserId');
            $compId = $this->session->userdata('CompId');
            $companyBranchId = $this->session->userdata('CompBranchId');
            $salesmanRoleId = get_title('position', ['PostName' => 'Salesman'], 'Id');
            $empWhere = ['Id' => $employeeId, 'UserId' => $userId, 'CompId' => $compId, 'IsDeleted' => 1];

            if ($this->input->get('fromDate') != "" && $this->input->get('toDate') != "") {
                $from = $this->input->get('fromDate');
                $to = $this->input->get('toDate');
            } else {
                $from = date('Y-m-d');
                $to = date('Y-m-d');
            }            
            
            $partyWhere = ['user_id' => $userId, 'comp_id' => $compId, 'status' => 1];
            $salesmanWhere = ['UserId' => $userId, 'CompId' => $compId, 'IsDeleted' => 1, 'RoleId' => $salesmanRoleId];
            $salesWhere = ['invoiceDate >=' => $from, 'invoiceDate <=' => $to, 'user_id' => $userId, 'comp_id' => $compId, 'status' => 1];
            $employeePermissionWhere = ['EmployeeId' => $employeeId, 'UserId' => $userId, 'CompId' => $compId];
            if ($this->session->userdata('BranchRole')) {
                $partyWhere['CompanyBranchId'] = $companyBranchId;
                $salesmanWhere['CompanyBranchId'] = $companyBranchId;
                $empWhere['CompanyBranchId'] = $companyBranchId;
                $salesWhere['CompanyBranchId'] = $companyBranchId;
                $employeePermissionWhere['CompanyBranchId'] = $companyBranchId;
            }

            if (!empty($this->input->get('salesmanId')) && $this->input->get('salesmanId') != "All") {
                $salesWhere['SalesmanId'] = $this->input->get('salesmanId');
            }
            if (!empty($this->input->get('partyId')) && $this->input->get('partyId') != "All") {
                $salesWhere['customerId'] = $this->input->get('partyId');
            }
            
            $employeeWhere = [$employeeId];
            $employeeIds = json_decode(get_title('employees', $empWhere, 'WarehouseSalesman'), true) ?: [];
            foreach ($employeeIds as $key => $empId) {
                $employeeWhere[] = $empId;
            }
     
            $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
            $data['EmployeeInfo'] = $this->db->where('Id', $employeeId)->where($empWhere)->get('employees')->row();
            $data['EmployeePermission'] = $this->db->where($employeePermissionWhere)->get('employee_permission')->row();
            $data['parties'] = $this->db->select('id, name, contact')->where($partyWhere)->where_in('SalesmanId', $employeeWhere)->order_by('name', 'ASC')->get('parties')->result();
            $data['salesmans'] = $this->db->select('Id, Name')->where($salesmanWhere)->where_in('Id', $employeeWhere)->order_by('Id', 'DESC')->get('employees')->result();
            if ($this->input->get('estimate') == "") {
                $data['partiesSales'] = $this->db->select('customerId, invoiceDate, COUNT(customerId) AS saleInvoice, SUM(total) AS saleTotal, SUM(paid_amount) AS salePaidAmount, SalesmanId')->where($salesWhere)->where_in('SalesmanId', $employeeWhere)->group_by('customerId')->order_by('saleTotal', 'DESC')->get('sales')->result();
            } elseif ($this->input->get('estimate') == true) {
                $saleQuery = $this->db->select('type, customerId, invoiceDate, COUNT(customerId) AS saleInvoice, SUM(total) AS saleTotal, SUM(paid_amount) AS salePaidAmount, SalesmanId')->where($salesWhere)->where_in('SalesmanId', $employeeWhere)->group_by('customerId')->get_compiled_select('sales');
                $estimateSaleQuery = $this->db->select('type, customerId, invoiceDate, COUNT(customerId) AS saleInvoice, SUM(total) AS saleTotal, SUM(paid_amount) AS salePaidAmount, SalesmanId')->where($salesWhere)->where_in('SalesmanId', $employeeWhere)->group_by('customerId')->get_compiled_select('estimates');
                $data['partiesSales'] = $this->db->query("($saleQuery) UNION ALL ($estimateSaleQuery) ORDER BY saleTotal DESC")->result();
            }
            $data['page'] = 'inventory_sep/Employee/PartiesOutstanding';
            return $this->load->view('Employees', $data);
        }
        
        // Leave
        public function Leave() 
        {
            $EmployeeId = $this->session->userdata('EmployeeId');
            $UserId = $this->session->userdata('UserId');
            $CompId = $this->session->userdata('CompId');
            $CompBranchId = $this->session->userdata('CompBranchId');
                
            if ($this->input->get('From') && $this->input->get('To')) {
                $From = $this->input->get('From');
                $To = $this->input->get('To');
            } else {
                $From = date('Y-m-d');
                $To = date('Y-m-d');
            }
            
            $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
            $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
            $EmployeeLeaveWhere = ['Date >= ' => $From, 'Date <= ' => $To, 'UserId' => $UserId, 'CompId' => $CompId];
            $EmployeeWhere = [$EmployeeId];
                
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $CompBranchId;
                $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                $EmployeeLeaveWhere['CompanyBranchId'] = $CompBranchId;
            }
                
            if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                $Where['Id'] = $EmployeeId;
                $EmployeeIds = json_decode(get_title('employees', $Where, 'WarehouseSalesman'), true) ?: [];
                foreach ($EmployeeIds as $key => $Id) {
                    $EmployeeWhere[] = $Id;
                }
            }
            
            if ($this->input->get('Status') != "") {
                $EmployeeLeaveWhere['Status'] = $this->input->get('Status');
            }
                
            $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
            $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
            $data['EmployeesLeave'] = $this->db->select('*')->where($EmployeeLeaveWhere)->where_in('EmployeeId', $EmployeeWhere)->get('leave_request')->result();
            $data['page'] = "LeaveReport";
            return $this->load->view('inventory_sep/Employee/Employee', $data);
        }
        
        public function LeaveCreate() 
        {
            $EmployeeId = $this->session->userdata('EmployeeId');
            $UserId = $this->session->userdata('UserId');
            $CompId = $this->session->userdata('CompId');
            $CompBranchId = $this->session->userdata('CompBranchId');
                
            $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
            $EMPWhere = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
            $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
            $EmployeeWhere = [$EmployeeId];
                
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $CompBranchId;
                $EMPWhere['CompanyBranchId'] = $CompBranchId;
                $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
            }
                
            if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                $EMPWhere['Id'] = $EmployeeId;
                $EmployeeIds = json_decode(get_title('employees', $EMPWhere, 'WarehouseSalesman'), true) ?: [];
                foreach ($EmployeeIds as $key => $Id) {
                    $EmployeeWhere[] = $Id;
                }
            }
                
            $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
            $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
            $data['Employees'] = $this->db->select('Id, Name, RoleId')->where($Where)->where_in('Id', $EmployeeWhere)->get('employees')->result();
            $data['page'] = "LeaveCreate";
            return $this->load->view('inventory_sep/Employee/Employee', $data);
        }
        
        public function LeaveSave() 
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $EmployeeId = $this->session->userdata('EmployeeId');
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
                $CompBranchId = $this->session->userdata('CompBranchId');
                $FormData = $this->input->post();  
                
                $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
                $EMPWhere = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
                $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
                $EmployeeWhere = [$EmployeeId];
                $EmployeeAttendanceWhere = ['EmpId' => $EmployeeId, 'Date' => $FormData['Date'], 'UserId' => $UserId, 'CompId' => $CompId, 'FinancialYear' => $FinancialYear];
                
                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $CompBranchId;
                    $EMPWhere['CompanyBranchId'] = $CompBranchId;
                    $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                    $EmployeeAttendanceWhere['CompanyBranchId'] = $CompBranchId;
                }

                if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                    $EMPWhere['Id'] = $EmployeeId;
                    $EmployeeIds = json_decode(get_title('employees', $EMPWhere, 'WarehouseSalesman'), true) ?: [];
                    foreach ($EmployeeIds as $key => $Id) {
                        $EmployeeWhere[] = $Id;
                    }
                }
                
                $this->form_validation->set_rules('EmployeeId', 'Employee', 'required|trim');
                $this->form_validation->set_rules('StartDate', 'Start Date', 'required|trim');
                $this->form_validation->set_rules('EndDate', 'End Date', 'required|trim');

                // rule 1 :- formal validation
                if ($this->form_validation->run() == FALSE) {
                    $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                    $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                    $data['Employees'] = $this->db->select('Id, Name, RoleId')->where($Where)->where_in('Id', $EmployeeWhere)->get('employees')->result();
                    $data['error'] = validation_errors();
                    $data['page'] = "LeaveCreate";
                    return $this->load->view('inventory_sep/Employee/Employee', $data);
                } else {
                    $EmployeeLeaveData = [
                        "Date" => date('Y-m-d'),
                        "StartDate" => $FormData['StartDate'],
                        "EndDate" => $FormData['EndDate'],
                        "Remark" => $FormData['Remark'],
                        "CompId" => $CompId,
                        "UserId" => $UserId,
                        "CompanyBranchId" => $CompBranchId,
                        "EmployeeId" => $FormData['EmployeeId'],
                        "Created" => date('d-m-Y h:i:s A'),
                        "CreatedBy" => get_title('employees', ['Id' => $EmployeeId], 'Name')
                    ];

                    $Result = $this->user_model->saveAry('leave_request', $EmployeeLeaveData);
                    if ($Result) {
                        $this->session->set_flashdata(['msg' => 'Leave requested successfully.', 'class' => 'success']);
                        return redirect(base_url() . 'Employee/Leave');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Failed, try again', 'class' => 'danger']);
                        return redirect('Employee/LeaveCreate');
                    }
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Direct entry not allowed.', 'class' => 'danger']);
                return redirect('Employee/Leave');
            }
        }
        
        public function LeaveEdit($LeaveId) 
        {
            if (isset($LeaveId) && !empty($LeaveId)) {
                $EmployeeId = $this->session->userdata('EmployeeId');
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
                $CompBranchId = $this->session->userdata('CompBranchId');

                $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
                $EMPWhere = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
                $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
                $EmployeeLeaveWhere = ['Id' => $LeaveId, 'UserId' => $UserId, 'CompId' => $CompId];
                $EmployeeWhere = [$EmployeeId];

                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $CompBranchId;
                    $EMPWhere['CompanyBranchId'] = $CompBranchId;
                    $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                    $EmployeeLeaveWhere['CompanyBranchId'] = $CompBranchId;
                }

                if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                    $EMPWhere['Id'] = $EmployeeId;
                    $EmployeeIds = json_decode(get_title('employees', $EMPWhere, 'WarehouseSalesman'), true) ?: [];
                    foreach ($EmployeeIds as $key => $Id) {
                        $EmployeeWhere[] = $Id;
                    }
                }

                $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                $data['Employees'] = $this->db->select('Id, Name, RoleId')->where($Where)->where_in('Id', $EmployeeWhere)->get('employees')->result();
                $data['EmployeeLeave'] = $this->db->select('*')->where($EmployeeLeaveWhere)->where_in('EmployeeId', $EmployeeWhere)->get('leave_request')->row();
                $data['page'] = "LeaveEdit";
                return $this->load->view('inventory_sep/Employee/Employee', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'Leave id is missing.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/Leave');
            }
        }
        
        public function LeaveUpdate($LeaveId) 
        {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $EmployeeId = $this->session->userdata('EmployeeId');
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
                $CompBranchId = $this->session->userdata('CompBranchId');

                $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
                $EMPWhere = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
                $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
                $EmployeeLeaveWhere = ['Id' => $LeaveId, 'UserId' => $UserId, 'CompId' => $CompId];
                $EmployeeWhere = [$EmployeeId];
                $EmployeeLeaveUpdateWhere = ['Id' => $this->input->post('LeaveId'), 'UserId' => $UserId, 'CompId' => $CompId];
                        
                if ($this->session->userdata('BranchRole')) {
                    $Where['CompanyBranchId'] = $CompBranchId;
                    $EMPWhere['CompanyBranchId'] = $CompBranchId;
                    $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                    $EmployeeLeaveWhere['CompanyBranchId'] = $CompBranchId;
                    $EmployeeLeaveUpdateWhere['CompanyBranchId'] = $CompBranchId;
                }

                if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                    $EMPWhere['Id'] = $EmployeeId;
                    $EmployeeIds = json_decode(get_title('employees', $EMPWhere, 'WarehouseSalesman'), true) ?: [];
                    foreach ($EmployeeIds as $key => $Id) {
                        $EmployeeWhere[] = $Id;
                    }
                }
                
                $this->form_validation->set_rules('EmployeeId', 'Employee', 'required|trim');
                $this->form_validation->set_rules('StartDate', 'Start Date', 'required|trim');
                $this->form_validation->set_rules('EndDate', 'End Date', 'required|trim');
                if ($this->form_validation->run() == FALSE) {                    
                    $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                    $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                    $data['Employees'] = $this->db->select('Id, Name, RoleId')->where($Where)->where_in('Id', $EmployeeWhere)->get('employees')->result();
                    $data['EmployeeLeave'] = $this->db->select('*')->where($EmployeeLeaveWhere)->where_in('EmployeeId', $EmployeeWhere)->get('leave_request')->row();
                    $data['error'] = validation_errors();
                    $data['page'] = "LeaveEdit";
                    return $this->load->view('inventory_sep/Employee/Employee', $data);
                } else {
                    $EmployeeLeaveData = [
                        "EmployeeId" => $this->input->post('EmployeeId'),
                        "StartDate" => $this->input->post('StartDate'),
                        "EndDate" => $this->input->post('EndDate'),
                        "Remark" => $this->input->post('Remark'),
                        "Updated" => date('d-m-Y h:i:s A'),
                        "UpdatedBy" => get_title('employees', ['Id' => $EmployeeId], 'Name')
                    ];

                    $Result = $this->user_model->updateAry('leave_request', $EmployeeLeaveData, $EmployeeLeaveUpdateWhere);

                    if ($Result) {
                        $this->session->set_flashdata(['msg' => 'Leave updated Successfully.', 'class' => 'success']);
                        return redirect(base_url() . 'Employee/Leave');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Failed. try again', 'class' => 'danger']);
                        return redirect(base_url() . 'Employee/LeaveEdit/' . $LeaveId);
                    }
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Direct entry not allowed.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/EMPAttendanceEdit/' . $LeaveId);
            }
        }
        
        public function LeaveDelete($Id) 
        {
            if ($Id) {
                $Where = ['Id' => $Id];
                if ($this->db->select('Id')->where($Where)->get('leave_request')->num_rows()) {
                    if ($this->user_model->deleteRow('leave_request', $Where)) {
                        $this->session->set_flashdata(['msg' => 'Record delete successfully.', 'class' => 'success']);
                        return redirect(base_url() . 'Employee/Leave');
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to delete record.', 'class' => 'danger']);
                        return redirect(base_url() . 'Employee/Leave');
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                    return redirect(base_url() . 'Employee/Leave');
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Leave id is missing.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/Leave');
            }
        }
        
        public function PaymentFollowUpChatHandler($CustomerId) 
        {
            if (!empty($CustomerId)) {
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
                $CompBranchId = $this->session->userdata('CompBranchId');
                
                $SaleWhere = ['customerId' => $CustomerId, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
                if ($this->session->userdata('BranchRole')) {
                    $SaleWhere['CompanyBranchId'] = $CompBranchId;
                }
                
                $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
                if ($this->db->select('id')->where($SaleWhere)->where('type', 'Sale')->get('sales')->num_rows()) {
                    $data['SaleInfo'] = $this->db->select('customerId, SUM(total) AS Total, SUM(paid_amount) AS PaidAmount')->where($SaleWhere)->get('sales')->row();
                } elseif ($this->db->select('id')->where($SaleWhere)->where('type', 'Estimate')->get('estimates')->num_rows()) {
                    $data['SaleInfo'] = $this->db->select('customerId, SUM(total) AS Total, SUM(paid_amount) AS PaidAmount')->where($SaleWhere)->get('estimates')->row();
                }
                return $this->load->view('inventory_sep/Employee/PaymentFollowUpChatHandler', $data);
            }
        }
        
        public function PaymentFollowUpChatSave() 
        {
            if ($this->input->is_ajax_request()) {
                $EmployeeId = $this->session->userdata('EmployeeId');
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
                $CompBranchId = $this->session->userdata('CompBranchId');
                
                $this->form_validation->set_rules('Chat', 'Chat', 'trim|required');
                if ($this->form_validation->run() == false) {
                    echo json_encode(['Status' => false, 'Message' => validation_errors()]);
                    return;
                } else {
                    $PaymentFollowUpChatData = [
                        'Date' => date('Y-m-d'),
                        'CustomerId' => $this->input->post('CustomerId'),
                        'Chat' => $this->input->post('Chat'),
                        'EmployeeId' => $EmployeeId,
                        'UserId' => $UserId,
                        'CompId' => $CompId,
                        'CompanyBranchId' => $CompBranchId,
                        'Created' => date('d-m-Y h:i:s A'),
                        'CreatedBy' => get_title('employees', ['Id' => $EmployeeId], 'Name'),
                    ];
                    
                    $Result = $this->user_model->saveAry('payment_followup_chat', $PaymentFollowUpChatData);
                    if (!empty($Result)) {
                        echo json_encode(['Status' => true, 'Message' => 'Message send successfully.']);
                        return;
                    } else {
                        echo json_encode(['Status' => false, 'Message' => 'Unable to send Message.']);
                        return;
                    }
                }
            }
        }
        
        public function getPaymentFollowUpChat() 
        {
            if ($this->input->is_ajax_request()) {
                $CustomerId = $this->input->post('CustomerId');
                if ($CustomerId == "" && $CustomerId == null) {
                    echo json_encode(['Status' => false, 'Chat' => '<span class="text-danger text-center">Enter required fields.</span>', 'From' => '', 'To' => '']);
                } else {
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
                    $CompBranchId = $this->session->userdata('CompBranchId');
                                
                    $Where = ['CustomerId' => $CustomerId, 'UserId' => $UserId, 'CompId' => $CompId];
                    if ($this->session->userdata('BranchRole')) {
                        $Where['CompanyBranchId'] = $CompBranchId; 
                    }
                    
                    $PaymentFollowUpChat = $this->db->select('Chat, Created, CreatedBy')->where($Where)->order_by('Id', 'ASC')->get('payment_followup_chat')->result();

                    if ($PaymentFollowUpChat) {
                        $ImageUrl = base_url() . 'assets/images/avatar.png';
                        $chatHtml = '';

                        foreach ($PaymentFollowUpChat as $key => $Chat) {
                            $chatHtml .= '<div class="card-comment">'
                                      . '<img class="img-circle img-sm" src="' . $ImageUrl . '" alt="User-Image" />'
                                      . '<div class="comment-text">'
                                      . '<span class="username">'
                                      . $Chat->CreatedBy
                                      . '<span class="text-muted float-right">'
                                      . $Chat->Created
                                      . '</span>'
                                      . '</span>'
                                      . $Chat->Chat
                                      . '</div>'
                                      . '</div>';
                        }
                        echo json_encode(['Status' => true, 'Chat' => $chatHtml]);
                    } else {
                        echo json_encode(['Status' => false, 'Chat' => '<span class="text-danger text-center">No Record found.</span>', 'From' => '', 'To' => '']);
                    }
                }
            }
        }
        
        public function PartiesBalanceReport() 
        {
            $EmployeeId = $this->session->userdata('EmployeeId');
            $UserId = $this->session->userdata('UserId');
            $CompId = $this->session->userdata('CompId');
            $CompBranchId = $this->session->userdata('CompBranchId');
                
            $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
            $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
            
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $CompBranchId;
                $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
            }
                
            $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
            $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
            $data['page'] = "PartiesBalanceReport";
            return $this->load->view('inventory_sep/Employee/Employee', $data);
        }
        
        public function PartiesBalanceReportAjax() 
        {
            if ($this->input->is_ajax_request()) {
                $Limit = $this->input->post('Limit');
                $Start = $this->input->post('Start');
                $PartyName = $this->input->post('PartyName');
                $EmployeeId = $this->session->userdata('EmployeeId');
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
                $CompBranchId = $this->session->userdata('CompBranchId');

                $EMPWhere = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
                $EmployeeWhere = [$EmployeeId];
                $PartiesWhere = ['user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];    

                if ($this->session->userdata('BranchRole')) {
                    $EMPWhere['CompanyBranchId'] = $CompBranchId;
                    $PartiesWhere['CompanyBranchId'] = $CompBranchId;
                }

                if ($this->session->userdata('RoleType') == "Account Operator" || $this->session->userdata('RoleType') == "Supervisor" || $this->session->userdata('RoleType') == "Team Leader") {
                    $EMPWhere['Id'] = $EmployeeId;
                    $EmployeeIds = json_decode(get_title('employees', $EMPWhere, 'WarehouseSalesman'), true) ?: [];
                    foreach ($EmployeeIds as $key => $Id) {
                        $EmployeeWhere[] = $Id;
                    }
                }
                
                $this->db->select('id, name, contact, gst, address_1, area');
                if (isset($PartyName) && !empty($PartyName)) {
                    $this->db->like('name', $PartyName);
                }
                $this->db->where_in('SalesmanId', $EmployeeWhere);
                $this->db->order_by('id', 'DESC');
                $this->db->limit($Limit, $Start);
                $Parties = $this->db->get('parties')->result();

                if ($Parties) {
                    $Html = "";
                    foreach ($Parties as $key => $Party) {
                        $Sale = $this->db->where(['customerId' => $Party->id, 'status' => 1])->group_start()->where('paid_amount', NULL)->or_where('total != paid_amount')->group_end()->get('sales')->num_rows();
                        $Estimate = $this->db->where(['customerId' => $Party->id, 'status' => 1])->group_start()->where('paid_amount', NULL)->or_where('total != paid_amount')->group_end()->get('estimates')->num_rows();
                        if ($Sale || $Estimate) {
                            $SaleInvoices = $this->db->select('id')->where(['customerId' => $Party->id, 'status' => 1])->get('sales')->num_rows(); 
                            $EstimateInvoices = $this->db->select('id')->where(['customerId' => $Party->id, 'status' => 1])->get('estimates')->num_rows(); 
                            
                            $SalesLink  = "<a href='" . base_url() . "Employee/PartySales/" . $Party->id . "' title='Sale Info'>"
                                        . "Sales"
                                        . "</a>";
                            
                            $TotalSale = $this->db->select_sum('total')->where(['customerId' => $Party->id, 'status' => 1])->get('sales')->row('total');
                            $TotalEstimate = $this->db->select_sum('total')->where(['customerId' => $Party->id, 'status' => 1])->get('estimates')->row('total');
                            
                            $PaidLink   = "<a href='" . base_url() . "Employee/PartyPaid/" . $Party->id . "' title='Paid Info'>"
                                        . "Paid"
                                        . "</a>";
                            
                            $PaidAmount = number_format((float) ($this->db->select_sum('debit')->where(['party_id' => $Party->id, 'status' => 1, 'transaction_type' => 'DR', 'payment_type' => 'Receipt'])->get('payment')->row('debit') ?? 0), 2);
                            
                            $BalanceLink  = "<a href='" . base_url() . "Employee/PartyBalance/" . $Party->id . "' title='Balance Info'>"
                                          . "Balance"
                                          . "</a>";
                            
                            $SaleBalance = $this->db->select('SUM(total) - SUM(COALESCE(paid_amount, 0)) AS Balance')->where(['customerId' => $Party->id, 'status' => 1])->get('sales')->row('Balance');
                            $EstimateBalance = $this->db->select('SUM(total) - SUM(COALESCE(paid_amount, 0)) AS Balance')->where(['customerId' => $Party->id, 'status' => 1])->get('estimates')->row('Balance');

                            $CollectPayment = "<a href='" . base_url() . "Employee/CollectPayment/" . $Party->id . "' class='btn bg-purple btn-sm mr-2' title='Collect Payment' style='font-size: 12.5px;'>"
                                            . "Collect Payment"
                                            . "</a>";
                            $PaymentStatement   = "<a href='javascript:void();' onclick='DownloadStatementOrSendWhatsApp(event, " . $Party->id . ");' class='btn bg-danger btn-sm mr-2' title='Dwonload OR Sent WhatsApp Statement' style='font-size: 12.5px;'>"
                                                . "Statement"
                                                . "</a>";
                            $NewOrder   = "<a href='" . base_url() . "Employee/Orderbook/" . $Party->id . "' class='btn bg-success btn-sm' title='New Order' style='font-size: 12.5px;'>"
                                        . "New Order"
                                        . "</a>";
                            
                            $Html   .= '<div class="col-md-3 col-lg-3 col-sm-3 col-xl-3 col-xxl-3 col-xs-3">'
                                    .  '<div class="card card-widget widget-user">'
                                    .  '<div class="widget-user-header bg-navy">'
                                    .  '<h4 class="widget-user-username">'
                                    .  $Party->name
                                    .  '</h4>'
                                    .  '<h6 class="widget-user-desc mt-1">'
                                    .  $Party->contact
                                    .  '</h6>'
                                    .  '<h6 class="widget-user-desc">'
                                    .  $Party->gst
                                    .  '</h6>'
                                    .  '<h6 class="widget-user-desc">'
                                    .  '<small class="text-white">'
                                    .  $Party->address_1 . ', ' . $Party->area
                                    .  '</small>'
                                    .  '</h6>'
                                    .  '</div>'
                                    .  '<div class="card-body">'
                                    .  '<div class="row">'
                                    .  '<div class="col-sm-12 border-right">'
                                    .  '<div class="description-block">'
                                    .  '<span class="description-text pull-left text-primary">'
                                    .  'No. of Invoices'
                                    .  '</span>'
                                    .  '<h5 class="description-header pull-right">'
                                    .  '<span class="badge badge-danger">'
                                    .  ($SaleInvoices + $EstimateInvoices)
                                    .  '</span>'
                                    .  '</h5>'
                                    .  '</div>'
                                    .  '</div>'
                                    .  '<div class="col-sm-12 border-right">'
                                    .  '<div class="description-block">'
                                    .  '<span class="description-text pull-left">'
                                    .  $SalesLink
                                    .  '</span>'
                                    .  '<h5 class="description-header pull-right">'
                                    .  '<span class="badge badge-primary">'
                                    .  number_format((float) ($TotalSale ?? 0) + (float) ($TotalEstimate ?? 0), 2)
                                    .  '</span>'
                                    .  '</h5>'
                                    .  '</div>'
                                    .  '</div>'
                                    .  '<div class="col-sm-12 border-right">'
                                    .  '<div class="description-block">'
                                    .  '<span class="description-text pull-left">'
                                    .  $PaidLink
                                    .  '</span>'
                                    .  '<h5 class="description-header pull-right">'
                                    .  '<span class="badge badge-success">'
                                    .  $PaidAmount
                                    .  '</span>'
                                    .  '</h5>'
                                    .  '</div>'
                                    .  '</div>'
                                    .  '<div class="col-sm-12">'
                                    .  '<div class="description-block">'
                                    .  '<span class="description-text pull-left">'
                                    .  $BalanceLink
                                    .  '</span>'
                                    .  '<h5 class="description-header pull-right">'
                                    .  '<span class="badge badge-warning">'
                                    .  number_format((float) ($SaleBalance ?? 0) + (float) ($EstimateBalance ?? 0), 2)
                                    .  '</span>'
                                    .  '</h5>'
                                    .  '</div>'
                                    .  '</div>'
                                    .  '</div>'
                                    .  '</div>'
                                    .  '<div class="card-footer" style="padding: .5rem .5rem;">'
                                    .  $CollectPayment
                                    .  $PaymentStatement
                                    .  $NewOrder
                                    .  '</div>'
                                    .  '</div>'
                                    .  '</div>';
                        }
                    }
                    if ($Html) {
                        echo $Html;
                    } else {
                        echo '<span class="text-danger">No Record found.</span>';
                    }
                }
            }
        }
        
        public function PartySales($Id) 
        {
            $EmployeeId = $this->session->userdata('EmployeeId');
            $UserId = $this->session->userdata('UserId');
            $CompId = $this->session->userdata('CompId');
            $CompBranchId = $this->session->userdata('CompBranchId');
                
            $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
            $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
            $SalesWhere = ['customerId' => $Id, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];    
            
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $CompBranchId;
                $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                $SalesWhere['CompanyBranchId'] = $CompBranchId;
            }
                
            $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
            $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
            $data['PartyName'] = get_title('parties', ['id' => $Id], 'name');
            $Sales = $this->db->select('id, invoiceNumber, type, total, paid_amount')->where($SalesWhere)->get_compiled_select('sales');
            $Estimates = $this->db->select('id, invoiceNumber, type, total, paid_amount')->where($SalesWhere)->get_compiled_select('estimates');
            $data['PartiesSales'] = $this->db->query($Sales . " UNION " . $Estimates . " ORDER BY id DESC")->result();
            $data['page'] = "PartySales";
            return $this->load->view('inventory_sep/Employee/Employee', $data);
        }
        
        public function PartyPaid($Id) 
        {
            $EmployeeId = $this->session->userdata('EmployeeId');
            $UserId = $this->session->userdata('UserId');
            $CompId = $this->session->userdata('CompId');
            $CompBranchId = $this->session->userdata('CompBranchId');
                
            $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
            $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
            $ReceiptWhere = ['party_id' => $Id, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1, 'transaction_type' => 'DR', 'payment_type' => 'Receipt'];
            
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $CompBranchId;
                $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                $ReceiptWhere['CompanyBranchId'] = $CompBranchId;
            }
                
            $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
            $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
            $data['PartiesPaid'] = $this->db->select('id, date, paymentmode, debit, invoice_type, invoice_id')->where($ReceiptWhere)->order_by('id', 'DESC')->get('payment')->result();
            $data['PartyName'] = get_title('parties', ['id' => $Id], 'name');
            $data['page'] = "PartyPaid";
            return $this->load->view('inventory_sep/Employee/Employee', $data);
        }
        
        public function PartyBalance($Id) 
        {
            $EmployeeId = $this->session->userdata('EmployeeId');
            $UserId = $this->session->userdata('UserId');
            $CompId = $this->session->userdata('CompId');
            $CompBranchId = $this->session->userdata('CompBranchId');
                
            $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
            $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
            $BalanceWhere = ['customerId' => $Id, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
            
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $CompBranchId;
                $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                $BalanceWhere['CompanyBranchId'] = $CompBranchId;
            }

            $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
            $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
            $Sales = $this->db->select('id, invoiceNumber, type, total, paid_amount')->where($BalanceWhere)->group_start()->where('paid_amount', NULL)->or_where('total != paid_amount')->group_end()->get_compiled_select('sales');
            $Estimates = $this->db->select('id, invoiceNumber, type, total, paid_amount')->where($BalanceWhere)->group_start()->where('paid_amount', NULL)->or_where('total != paid_amount')->group_end()->get_compiled_select('estimates');
            $data['PartiesBalance'] = $this->db->query($Sales . " UNION " . $Estimates . " ORDER BY id DESC")->result();
            $data['PartyName'] = get_title('parties', ['id' => $Id], 'name');
            $data['page'] = "PartyBalance";
            return $this->load->view('inventory_sep/Employee/Employee', $data);
        }
        
        public function CollectPayment($PartyId) 
        {
            $EmployeeId = $this->session->userdata('EmployeeId');
            $UserId = $this->session->userdata('UserId');
            $CompId = $this->session->userdata('CompId');
            $CompBranchId = $this->session->userdata('CompBranchId');
                
            $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
            $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
            $SalesWhere = ['customerId' => $PartyId, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];    
            
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $CompBranchId;
                $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                $SalesWhere['CompanyBranchId'] = $CompBranchId;
            }
                
            if (employee_has_perm('PaymentsRequest', 'Create', $EmployeePermissionWhere)) {
                $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                $data['PartyName'] = get_title('parties', ['id' => $PartyId], 'name');
                $data['CustomerId'] = $PartyId;
                $Sales = $this->db->select('id, invoiceNumber, type, total, paid_amount')->where($SalesWhere)->group_start()->where('paid_amount', NULL)->or_where('total != paid_amount')->group_end()->get_compiled_select('sales');
                $Estimates = $this->db->select('id, invoiceNumber, type, total, paid_amount')->where($SalesWhere)->group_start()->where('paid_amount', NULL)->or_where('total != paid_amount')->group_end()->get_compiled_select('estimates');
                $data['PartiesPayments'] = $this->db->query($Sales . " UNION " . $Estimates . " ORDER BY id ASC")->result();
                $data['PaymentMode'] = $this->db->select('id, payment_method')->get('payment_mode')->result();
                $data['page'] = "CollectPayment";
                return $this->load->view('inventory_sep/Employee/Employee', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/PartiesBalanceReport');                
            }
        }
        
        public function CheckPaymentRequestEntry() 
        {
            if ($this->input->is_ajax_request()) {
                $PaymentDate = $this->input->post('payment_date');
                $PartyId = $this->input->post('PartyId');
                $Amount = $this->input->post('payment_amount');
                $InvoiceId = $this->input->post('InvoiceId');
                if (!empty($PaymentDate) && !empty($PartyId) && !empty($Amount)) {
                    $InvoiceNumberData = [];
                    foreach ($InvoiceId as $key => $value) { 
                        $InvoiceNumberData[] = $this->input->post('InvoiceNumber_' . $value);
                    }                    
                    $InvoiceNumber = json_encode($InvoiceNumberData, JSON_UNESCAPED_SLASHES);
                    
                    $EmployeeId = $this->session->userdata('EmployeeId');
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
                    $CompBranchId = $this->session->userdata('CompBranchId');
                    
                    $Where = ['payment_date' => $PaymentDate, 'party_id' => $PartyId, 'payment_amount' => $Amount, 'user_id' => $UserId, 'comp_id' => $CompId, 'SalesmanId' => $EmployeeId, 'status' => 0];
                    if ($this->session->userdata('BranchRole')) {
                        $Where['CompanyBranchId'] = $CompBranchId;
                    }
                    
                    $this->db->where($Where);
                    $this->db->group_start();
                    $this->db->where("JSON_CONTAINS(invoiceNumber, '" . $InvoiceNumber . "')");
                    $this->db->or_where_in('invoiceNumber', json_decode($InvoiceNumber, true));
                    $this->db->group_end();
                    $paymentrequest = $this->db->get('paymentrequest')->num_rows();

                    if ($paymentrequest) {
                        echo json_encode(['Status' => false, 'Data' => 'NotUnique', 'Title' => 'Duplicate Data Detected', 'Text' => 'The data you are about to submit already exists. Do you want to proceed?']);
                    } else {
                        echo json_encode(['Status' => true, 'Data' => 'Unique', 'Title' => '', 'Text' => '']);
                    }
                } else {
                    echo json_encode(['Status' => false, 'Data' => 'NotUnique', 'Title' => 'Payment Amount is Ziro', 'Text' => 'The payment amount ziro. Do you want to proceed?']);
                }
            }
        }

        public function PaymentRequestSave() 
        {
            if ($this->input->is_ajax_request()) {
                if (empty($this->input->post('InvoiceId'))) {
                    echo json_encode(['Status' => false, 'Message' => 'Please select invoice for payment request.']);
                    return;
                }
                
                $this->form_validation->set_rules('payment_date', 'Payment Date', 'trim|required');
                $this->form_validation->set_rules('payment_mode', 'Payment Mode', 'trim|required');
                $this->form_validation->set_rules('payment_amount', 'Payment Amount', 'trim|required|numeric');
                if ($this->form_validation->run() == false) {
                    echo json_encode(['Status' => false, 'Message' => validation_errors()]);
                    return;
                } else {
                    $EmployeeId = $this->session->userdata('EmployeeId');
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
                    $CompBranchId = $this->session->userdata('CompBranchId');
                    $PartyId = $this->input->post('PartyId');

                    $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
                    $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
                    $SalesWhere = ['customerId' => $PartyId, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];    

                    if ($this->session->userdata('BranchRole')) {
                        $Where['CompanyBranchId'] = $CompBranchId;
                        $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                        $SalesWhere['CompanyBranchId'] = $CompBranchId;
                    }
                    
                    if (employee_has_perm('PaymentsRequest', 'Create', $EmployeePermissionWhere)) {
                        $InvoiceType = [];
                        foreach ($this->input->post('InvoiceId') as $key => $value) { 
                            $InvoiceType[] = $this->input->post('InvoiceType_' . $value);
                        }
                        $InvoiceNumber = [];
                        foreach ($this->input->post('InvoiceId') as $key => $value) { 
                            $InvoiceNumber[] = $this->input->post('InvoiceNumber_' . $value);
                        }
                        
                        $PaymentData = [
                            'date' => date('Y-m-d'),
                            'invoiceId' => json_encode($this->input->post('InvoiceId')),
                            'Type' => json_encode($InvoiceType),
                            'invoiceNumber' => json_encode($InvoiceNumber),
                            'party_id' => $PartyId,
                            'payment_date' => $this->input->post('payment_date'),
                            'payment_mode' => $this->input->post('payment_mode'),
                            'ChequeDate' => $this->input->post('ChequeDate'),
                            'payment_amount' => $this->input->post('payment_amount'),
                            'remark' => $this->input->post('remark'),
                            'user_id' => $UserId,
                            'comp_id' => $CompId,
                            'CompanyBranchId' => $CompBranchId,
                            'SalesmanId' => $EmployeeId,
                            'created' => date('d-m-Y h:i:s A'),
                            'created_by' => get_title('employees', ['Id' => $EmployeeId], 'Name')
                        ];

                        $result = $this->user_model->saveAry('paymentrequest', $PaymentData);
                        if (!empty($result)) {
                            echo json_encode(['Status' => true, 'RequestId' => $result, 'Message' => 'Record saved successfully.']);
                            return;
                        } else {
                            echo json_encode(['Status' => false, 'Message' => 'Unable to save record.']);
                            return;
                        }
                    } else {
                        $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                        return redirect(base_url() . 'Employee/Dashboard');
                    }
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Direct entry not allowed.', 'class' => 'danger']);
                return redirect('Employee/Dashboard');
            }
        }
        
        public function ViewReceipt($ReceiptId)
        {
            if (isset($ReceiptId) && !empty($ReceiptId)) {
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
                $CompBranchId = $this->session->userdata('CompBranchId');

                $ReceiptWhere = ['id' => $ReceiptId, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1, 'transaction_type' => 'DR', 'payment_type' => 'Receipt'];

                if ($this->session->userdata('BranchRole')) {
                    $ReceiptWhere['CompanyBranchId'] = $CompBranchId;
                    $Data['Branch'] = $this->user_model->getRow('companybranch', ['Id' => $CompBranchId]);
                } else {
                    $Data['Company'] = $this->user_model->getRow('inventory_billing', ['id' => $CompId]);
                }
                $Data['Payment'] = $this->db->where($ReceiptWhere)->get('payment')->row();
                return $this->load->view('inventory_sep/payment/print', $Data);
            } else {
                $PartyId = get_title('parties', ['id' => get_title('payment', ['id' => $ReceiptId], 'party_id')], 'id');
                return redirect(base_url() . "Employee/PartyPaid/" . $PartyId);
            }
        }
        
        public function SendReceiptVoucher()
        {
            if ($this->input->is_ajax_request()) {
                $RequestId = $this->input->post('RequestId');
                if (isset($RequestId) && !empty($RequestId)) {
                    $EmployeeId = $this->session->userdata('EmployeeId');
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
                    $CompBranchId = $this->session->userdata('CompBranchId');

                    $ReceiptWhere = ['id' => $RequestId, 'SalesmanId' => $EmployeeId, 'user_id' => $UserId, 'comp_id' => $CompId];
                    if ($this->session->userdata('BranchRole')) {
                        $ReceiptWhere['CompanyBranchId'] = $CompBranchId;
                        $Data['Branch'] = $this->user_model->getRow('companybranch', ['Id' => $CompBranchId]);
                    } else {
                        $Data['Company'] = $this->user_model->getRow('inventory_billing', ['id' => $CompId]);
                    }

                    $PartyContact = get_title('parties', ['id' => get_title('paymentrequest', ['id' => $RequestId], 'party_id')], 'contact');
                    $PartyName = get_title('parties', ['id' => get_title('paymentrequest', ['id' => $RequestId], 'party_id')], 'name');
                    $Data['RequestData'] = $this->db->where($ReceiptWhere)->get('paymentrequest')->row();
                    
                    if ($this->db->where($ReceiptWhere)->get('paymentrequest')->num_rows()) {
                        $NewPartyName = str_replace(" ", "_", $PartyName);
                        $this->load->library('html2pdf');
                        $this->html2pdf->folder('uploads/ReceiptVoucher/');
                        $this->html2pdf->filename($NewPartyName . '_Receipt.pdf');
                        $this->html2pdf->paper('a4', 'portrait');
                        $this->html2pdf->html(preg_replace('/>\s+</', '><', $this->load->view('inventory_sep/Employee/ReceiptVoucherPrint', $Data, true)));
                        $Path = $this->html2pdf->create('save');

                        $WhatsAppMessage = urlencode(base_url() . $Path);
                        $WhatsAppURL = "https://api.whatsapp.com/send?phone=+91$PartyContact&text=$WhatsAppMessage";
                        
                        echo json_encode(['Status' => true, 'URL' => $WhatsAppURL]);
                        return;
                    } else {
                        echo json_encode(['Status' => false]);
                        return;
                    }
                } else {
                    echo json_encode(['Status' => false]);
                    return;
                }
            }
        }
        
        public function DownloadReceiptVoucher()
        {
            if ($this->input->is_ajax_request()) {
                $RequestId = $this->input->post('RequestId');
                if (isset($RequestId) && !empty($RequestId)) {
                    $EmployeeId = $this->session->userdata('EmployeeId');
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
                    $CompBranchId = $this->session->userdata('CompBranchId');

                    $ReceiptWhere = ['id' => $RequestId, 'SalesmanId' => $EmployeeId, 'user_id' => $UserId, 'comp_id' => $CompId];
                    if ($this->session->userdata('BranchRole')) {
                        $ReceiptWhere['CompanyBranchId'] = $CompBranchId;
                        $Data['Branch'] = $this->user_model->getRow('companybranch', ['Id' => $CompBranchId]);
                    } else {
                        $Data['Company'] = $this->user_model->getRow('inventory_billing', ['id' => $CompId]);
                    }

                    $PartyName = get_title('parties', ['id' => get_title('paymentrequest', ['id' => $RequestId], 'party_id')], 'name');
                    $Data['RequestData'] = $this->db->where($ReceiptWhere)->get('paymentrequest')->row();
                    
                    if ($this->db->where($ReceiptWhere)->get('paymentrequest')->num_rows()) {
                        $NewPartyName = str_replace(" ", "_", $PartyName);
                        $FileName = $NewPartyName . '_Receipt.pdf';
                        $this->load->library('html2pdf');
                        $this->html2pdf->folder('uploads/ReceiptVoucher/');
                        $this->html2pdf->filename($FileName);
                        $this->html2pdf->paper('a4', 'portrait');
                        $this->html2pdf->html(preg_replace('/>\s+</', '><', $this->load->view('inventory_sep/Employee/ReceiptVoucherPrint', $Data, true)));
                        $Path = $this->html2pdf->create('save');
                        $URL = base_url() . $Path;
                        
                        echo json_encode(['Status' => true, 'URL' => $URL, 'FileName' => $FileName]);
                        return;
                    } else {
                        echo json_encode(['Status' => false]);
                        return;
                    }
                } else {
                    echo json_encode(['Status' => false]);
                    return;
                }
            }
        }
        
        public function DownloadReceiptVoucherViaReceiptId()
        {
            if ($this->input->is_ajax_request()) {
                $ReceiptId = $this->input->post('ReceiptId');
                if (isset($ReceiptId) && !empty($ReceiptId)) {
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
                    $CompBranchId = $this->session->userdata('CompBranchId');

                    $ReceiptWhere = ['id' => $ReceiptId, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1, 'transaction_type' => 'DR', 'payment_type' => 'Receipt'];

                    if ($this->session->userdata('BranchRole')) {
                        $ReceiptWhere['CompanyBranchId'] = $CompBranchId;
                        $Data['Branch'] = $this->user_model->getRow('companybranch', ['Id' => $CompBranchId]);
                    } else {
                        $Data['Company'] = $this->user_model->getRow('inventory_billing', ['id' => $CompId]);
                    }

                    $PartyName = get_title('parties', ['id' => get_title('payment', ['id' => $ReceiptId], 'party_id')], 'name');
                    $Data['Payment'] = $this->db->where($ReceiptWhere)->get('payment')->row();
                    
                    $NewPartyName = str_replace(" ", "_", $PartyName);
                    $FileName = $NewPartyName . '_Receipt.pdf';
                    $this->load->library('html2pdf');
                    $this->html2pdf->folder('uploads/ReceiptVoucher/');
                    $this->html2pdf->filename($FileName);
                    $this->html2pdf->paper('a4', 'portrait');
                    $this->html2pdf->html(preg_replace('/>\s+</', '><', $this->load->view('inventory_sep/payment/print', $Data, true)));
                    $Path = $this->html2pdf->create('save');
                    $URL = base_url() . $Path;
                        
                    echo json_encode(['Status' => true, 'URL' => $URL, 'FileName' => $FileName]);
                    return;
                } else {
                    echo json_encode(['Status' => false]);
                    return;
                }
            } else {
                echo json_encode(['Status' => false]);
                return;
            }
        }
        
        public function DownloadReceiptViaReceiptId($ReceiptId)
        {
            if (isset($ReceiptId) && !empty($ReceiptId)) {
                $UserId = $this->session->userdata('UserId');
                $CompId = $this->session->userdata('CompId');
                $CompBranchId = $this->session->userdata('CompBranchId');

                $ReceiptWhere = ['id' => $ReceiptId, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1, 'transaction_type' => 'DR', 'payment_type' => 'Receipt'];

                if ($this->session->userdata('BranchRole')) {
                    $ReceiptWhere['CompanyBranchId'] = $CompBranchId;
                    $Data['Branch'] = $this->user_model->getRow('companybranch', ['Id' => $CompBranchId]);
                } else {
                    $Data['Company'] = $this->user_model->getRow('inventory_billing', ['id' => $CompId]);
                }

                $PartyName = get_title('parties', ['id' => get_title('payment', ['id' => $ReceiptId], 'party_id')], 'name');
                $Data['Payment'] = $this->db->where($ReceiptWhere)->get('payment')->row();

                $NewPartyName = str_replace(" ", "_", $PartyName);
                $FileName = $NewPartyName . '_Receipt.pdf';
                $this->load->library('html2pdf');
                $this->html2pdf->folder('uploads/ReceiptVoucher/');
                $this->html2pdf->filename($FileName);
                $this->html2pdf->paper('a4', 'portrait');
                $this->html2pdf->html(preg_replace('/>\s+</', '><', $this->load->view('inventory_sep/payment/print', $Data, true)));
                $Path = $this->html2pdf->create('save');
                $URL = base_url() . $Path;
                return redirect($URL);
            } else {
                return redirect($_SERVER['HTTP_REFERER']);
            }
        }

        public function DownloadPaymentStatement() 
        {
            if ($this->input->is_ajax_request()) {
                $PartyId = $this->input->post('PartyId');
                if (isset($PartyId) && !empty($PartyId)) {
                    $Data['AccountGroup'] = get_title('parties', ['id' => $PartyId], 'group_head');
                    $Data['Party'] = $PartyId;
                    $Data['FromDate'] = date('Y-m-d', strtotime(get_title('parties', ['id' => $PartyId], 'added_at')));
                    $Data['ToDate'] = date('Y-m-d');

                    $PartyName = get_title('parties', ['id' => $PartyId], 'name');

                    $NewPartyName = str_replace(" ", "_", $PartyName);
                    $FileName = $NewPartyName . '_Statement.pdf';
                    $this->load->library('html2pdf');
                    $this->html2pdf->folder('uploads/ReceiptVoucher/');
                    $this->html2pdf->filename($FileName);
                    $this->html2pdf->paper('a4', 'portrait');
                    $this->html2pdf->html(preg_replace('/>\s+</', '><', $this->load->view('inventory_sep/Employee/PartyStatement', $Data, true)));
                    $Path = $this->html2pdf->create('save');
             
                    $URL = base_url() . $Path;
                        
                    echo json_encode(['Status' => true, 'URL' => $URL, 'FileName' => $FileName]);
                    return;
                } else {
                    echo json_encode(['Status' => false]);
                    return;
                }
            } else {
                echo json_encode(['Status' => false]);
                return;
            }
        }

        public function DownloadStatementViaPartyId($PartyId) 
        {
            if (isset($PartyId) && !empty($PartyId)) {
                $Data['AccountGroup'] = get_title('parties', ['id' => $PartyId], 'group_head');
                $Data['Party'] = $PartyId;
                $Data['FromDate'] = date('Y-m-d', strtotime(get_title('parties', ['id' => $PartyId], 'added_at')));
                $Data['ToDate'] = date('Y-m-d');

                $PartyName = get_title('parties', ['id' => $PartyId], 'name');

                $NewPartyName = str_replace(" ", "_", $PartyName);
                $FileName = $NewPartyName . '_Statement.pdf';
                $this->load->library('html2pdf');
                $this->html2pdf->folder('uploads/ReceiptVoucher/');
                $this->html2pdf->filename($FileName);
                $this->html2pdf->paper('a4', 'portrait');
                $this->html2pdf->html(preg_replace('/>\s+</', '><', $this->load->view('inventory_sep/Employee/PartyStatement', $Data, true)));
                $Path = $this->html2pdf->create('save');
                $URL = base_url() . $Path;
                return redirect($URL);
            } else {
                return redirect($_SERVER['HTTP_REFERER']);
            }
        }

        public function PartyPaymentStatement() 
        {
            if ($this->input->is_ajax_request()) {
                $PartyId = $this->input->post('PartyId');
                if (isset($PartyId) && !empty($PartyId)) {
                    $PartyContact = get_title('parties', ['id' => $PartyId], 'contact');
                    $Data['AccountGroup'] = get_title('parties', ['id' => $PartyId], 'group_head');
                    $Data['Party'] = $PartyId;
                    $Data['FromDate'] = date('Y-m-d', strtotime(get_title('parties', ['id' => $PartyId], 'added_at')));
                    $Data['ToDate'] = date('Y-m-d');

                    $PartyName = get_title('parties', ['id' => $PartyId], 'name');

                    $NewPartyName = str_replace(" ", "_", $PartyName);
                    $FileName = $NewPartyName . '_Statement.pdf';
                    $this->load->library('html2pdf');
                    $this->html2pdf->folder('uploads/ReceiptVoucher/');
                    $this->html2pdf->filename($FileName);
                    $this->html2pdf->paper('a4', 'portrait');
                    $this->html2pdf->html(preg_replace('/>\s+</', '><', $this->load->view('inventory_sep/Employee/PartyStatement', $Data, true)));
                    $Path = $this->html2pdf->create('save');

                    $WhatsAppMessage = urlencode(base_url() . $Path);
                    $WhatsAppURL = "https://api.whatsapp.com/send?phone=+91$PartyContact&text=$WhatsAppMessage";
                    
                    echo json_encode(['Status' => true, 'URL' => $WhatsAppURL]);
                    return;
                } else {
                    echo json_encode(['Status' => false]);
                    return;
                }
            } else {
                echo json_encode(['Status' => false]);
                return;
            }
        }
        
        public function SendReceiptVoucherViaReceiptId() 
        {
            if ($this->input->is_ajax_request()) {
                $ReceiptId = $this->input->post('ReceiptId');
                if (isset($ReceiptId) && !empty($ReceiptId)) {
                    $UserId = $this->session->userdata('UserId');
                    $CompId = $this->session->userdata('CompId');
                    $CompBranchId = $this->session->userdata('CompBranchId');

                    $ReceiptWhere = ['id' => $ReceiptId, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1, 'transaction_type' => 'DR', 'payment_type' => 'Receipt'];

                    if ($this->session->userdata('BranchRole')) {
                        $ReceiptWhere['CompanyBranchId'] = $CompBranchId;
                        $Data['Branch'] = $this->user_model->getRow('companybranch', ['Id' => $CompBranchId]);
                    } else {
                        $Data['Company'] = $this->user_model->getRow('inventory_billing', ['id' => $CompId]);
                    }

                    $PartyContact = get_title('parties', ['id' => get_title('payment', ['id' => $ReceiptId], 'party_id')], 'contact');
                    $PartyName = get_title('parties', ['id' => get_title('payment', ['id' => $ReceiptId], 'party_id')], 'name');
                    $Data['Payment'] = $this->db->where($ReceiptWhere)->get('payment')->row();

                    $NewPartyName = str_replace(" ", "_", $PartyName);
                    $this->load->library('html2pdf');
                    $this->html2pdf->folder('uploads/ReceiptVoucher/');
                    $this->html2pdf->filename($NewPartyName . '_Receipt.pdf');
                    $this->html2pdf->paper('a4', 'portrait');
                    $this->html2pdf->html(preg_replace('/>\s+</', '><', $this->load->view('inventory_sep/payment/print', $Data, true)));
                    $Path = $this->html2pdf->create('save');

                    $WhatsAppMessage = urlencode(base_url() . $Path);
                    $WhatsAppURL = "https://api.whatsapp.com/send?phone=+91$PartyContact&text=$WhatsAppMessage";
                        
                    echo json_encode(['Status' => true, 'URL' => $WhatsAppURL]);
                    return;
                } else {
                    echo json_encode(['Status' => false]);
                    return;
                }
            } else {
                echo json_encode(['Status' => false]);
                return;
            }
        }
        
        public function Ledger() 
        {
            $EmployeeId = $this->session->userdata('EmployeeId');
            $UserId = $this->session->userdata('UserId');
            $CompId = $this->session->userdata('CompId');
            $CompBranchId = $this->session->userdata('CompBranchId');
                
            $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
            $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
            $LedgerWhere = ['user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
            
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $CompBranchId;
                $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                $LedgerWhere['CompanyBranchId'] = $CompBranchId;
            }
                
            if (employee_has_perm('Ledger', 'Create', $EmployeePermissionWhere)) {
                $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                $data['Ledgers'] = $this->db->where($LedgerWhere)->get('ladger')->result();
                $data['page'] = "inventory_sep/ladger/index";
                return $this->load->view('Employees', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/Dashboard');                
            }       
        }
        
        public function LedgerEdit($Id) 
        {
            $EmployeeId = $this->session->userdata('EmployeeId');
            $UserId = $this->session->userdata('UserId');
            $CompId = $this->session->userdata('CompId');
            $CompBranchId = $this->session->userdata('CompBranchId');
                
            $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
            $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
            $LedgerWhere = ['id' => $Id, 'user_id' => $UserId, 'comp_id' => $CompId, 'status' => 1];
            
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $CompBranchId;
                $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                $LedgerWhere['CompanyBranchId'] = $CompBranchId;
            }
                
            if (employee_has_perm('Ledger', 'Edit', $EmployeePermissionWhere)) {
                $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                $data['result'] = $this->db->where($LedgerWhere)->get('ladger')->row();
                $data['page'] = "inventory_sep/ladger/edit";
                return $this->load->view('Employees', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/Dashboard');                
            }       
        }
        
        public function LedgerEntries() 
        {
            $EmployeeId = $this->session->userdata('EmployeeId');
            $UserId = $this->session->userdata('UserId');
            $CompId = $this->session->userdata('CompId');
            $CompBranchId = $this->session->userdata('CompBranchId');
                
            $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
            $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
            $LedgerWhere = ['user_id' => $UserId, 'comp_id' => $CompId];
            
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $CompBranchId;
                $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                $LedgerWhere['CompanyBranchId'] = $CompBranchId;
            }
                
            if (employee_has_perm('Ledger', 'Edit', $EmployeePermissionWhere)) {
                $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                $data['PaymentModes'] = $this->db->select('id, payment_method')->order_by('id', 'DESC')->get('payment_mode')->result();
                $data['Ledgers'] = $this->db->select('id, accounting_group, name, ContactPerson, Mobile')->where($LedgerWhere)->order_by('id', 'DESC')->get('ladger')->result();
                $data['page'] = "inventory_sep/Ledger/index";
                return $this->load->view('Employees', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/Dashboard');                
            }       
        }
        
        public function LedgerEntryEdit($Id) 
        {
            $EmployeeId = $this->session->userdata('EmployeeId');
            $UserId = $this->session->userdata('UserId');
            $CompId = $this->session->userdata('CompId');
            $CompBranchId = $this->session->userdata('CompBranchId');
                
            $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
            $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
            $LedgerWhere = ['user_id' => $UserId, 'comp_id' => $CompId];
            $LedgerEditWhere = ['Id' => $Id, 'UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
            
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $CompBranchId;
                $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                $LedgerWhere['CompanyBranchId'] = $CompBranchId;
                $LedgerEditWhere['CompanyBranchId'] = $CompBranchId;
            }
                
            if (employee_has_perm('Ledger', 'Edit', $EmployeePermissionWhere)) {
                $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                $data['PaymentModes'] = $this->db->select('id, payment_method')->order_by('id', 'DESC')->get('payment_mode')->result();
                $data['Ledgers'] = $this->db->select('id, accounting_group, name, ContactPerson, Mobile')->where($LedgerWhere)->order_by('id', 'DESC')->get('ladger')->result();
                $data['LedgerData'] = $this->db->where($LedgerEditWhere)->get('ledger_entries')->row();
                $data['page'] = "inventory_sep/Ledger/Edit";
                return $this->load->view('Employees', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/Dashboard');                
            }       
        }
        
        public function LedgerReport() 
        {
            $EmployeeId = $this->session->userdata('EmployeeId');
            $UserId = $this->session->userdata('UserId');
            $CompId = $this->session->userdata('CompId');
            $CompBranchId = $this->session->userdata('CompBranchId');
                
            $Where = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
            $EmployeePermissionWhere = ['EmployeeId' => $EmployeeId, 'UserId' => $UserId, 'CompId' => $CompId];
            $LedgerAccountWhere = ['user_id' => $UserId, 'comp_id' => $CompId];
            $LedgerWhere = ['UserId' => $UserId, 'CompId' => $CompId, 'IsDeleted' => 1];
            
            if ($this->session->userdata('BranchRole')) {
                $Where['CompanyBranchId'] = $CompBranchId;
                $EmployeePermissionWhere['CompanyBranchId'] = $CompBranchId;
                $LedgerAccountWhere['CompanyBranchId'] = $CompBranchId;
                $LedgerWhere['CompanyBranchId'] = $CompBranchId;
            }
                
            if (employee_has_perm('Ledger', 'Edit', $EmployeePermissionWhere)) {
                $data['EmployeeInfo'] = $this->db->where('Id', $EmployeeId)->where($Where)->get('employees')->row();
                $data['EmployeePermission'] = $this->db->where($EmployeePermissionWhere)->get('employee_permission')->row();
                $data['Ledgers'] = $this->db->select('id, accounting_group, name, ContactPerson, Mobile')->where($LedgerAccountWhere)->order_by('id', 'DESC')->get('ladger')->result();
                $data['LedgersEntries'] = $this->db->select('Id, Date, AccountGroupId, LedgerId, Amount, Type, PaymentMode')->where($LedgerWhere)->order_by('Id', 'DESC')->get('ledger_entries')->result();
                $data['page'] = "inventory_sep/Ledger/Report";
                return $this->load->view('Employees', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'You do not permission, Access denied.', 'class' => 'danger']);
                return redirect(base_url() . 'Employee/Dashboard');                
            }       
        }
        
        public function employeeSales()
        {
            $employeeId = $this->session->userdata('EmployeeId');
            $userId = $this->session->userdata('UserId');
            $compId = $this->session->userdata('CompId');
            $compBranchId = $this->session->userdata('CompBranchId');
            
            $where = ['Id' => $employeeId, 'UserId' => $userId, 'CompId' => $compId, 'IsDeleted' => 1];
            $employeePermissionWhere = ['EmployeeId' => $employeeId, 'UserId' => $userId, 'CompId' => $compId];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $compBranchId;
                $employeePermissionWhere['CompanyBranchId'] = $compBranchId;                
            }            
            
            $data['EmployeeInfo'] = $this->db->where($where)->get('employees')->row();
            $data['EmployeePermission'] = $this->db->where($employeePermissionWhere)->get('employee_permission')->row();
            $data['statesList'] = $this->db->select('id, state, code')->get('state_list')->result();
            $data['page'] = "employeeSales";
            return $this->load->view('inventory_sep/Employee/Employee', $data);
        }
        
        public function saveEmployeeSales()
        {
            if ($this->input->is_ajax_request()) {
                $arraySalesData = [];
                foreach ($this->input->post('parties') as $key => $row) {
                    if ($row['distributorName'] == '' || $row['retailerName'] == '' || $row['location'] == '') {
                        $arraySalesData[] = $row;
                    }
                }

                if (!empty($arraySalesData)) {
                    echo json_encode(['status' => false, 'message' => 'Please enter sales required fields.']);
                    return;
                }
                
                $this->form_validation->set_rules('date', 'Date', 'trim|required');
                $this->form_validation->set_rules('employeeName', 'Employee Name', 'trim|required');
                $this->form_validation->set_rules('designation', 'Designation', 'trim|required');
                $this->form_validation->set_rules('month', 'Month', 'trim|required');
                $this->form_validation->set_rules('stateId', 'State', 'trim|required');
                $this->form_validation->set_rules('grandTotal', 'Total', 'trim|numeric|required');
                if ($this->form_validation->run() == false) {
                    echo json_encode(['status' => false, 'message' => validation_errors()]);
                    return;                    
                } else {
                    $employeeId = $this->session->userdata('EmployeeId');
                    $userId = $this->session->userdata('UserId');
                    $compId = $this->session->userdata('CompId');
                    $compBranchId = $this->session->userdata('CompBranchId');
                    $created = date('d-m-Y h:i:s A');
                    $createdBy = get_title('employees', ['Id' => $employeeId], 'Name');
                    
                    $compProfileWhere = ['user_id' => $userId, 'comp_id' => $compId];
                    if ($this->session->userdata('BranchRole')) {
                        $compProfileWhere['CompanyBranchId'] = $compBranchId;
                    }
                    $financialYear = get_title('comp_profile', $compProfileWhere, 'financial_year');
                                        
                    $employeeSalesData = [
                        'date' => $this->input->post('date'),
                        'employeeId' => $this->input->post('employeeId'),
                        'baseLocation' => $this->input->post('baseLocation'),
                        'band' => $this->input->post('band'),
                        'stateId' => $this->input->post('stateId'),
                        'month' => $this->input->post('month'),
                        'department' => $this->input->post('department'),
                        'reportingSenior' => $this->input->post('reportingSenior'),
                        'remark' => $this->input->post('remark'),
                        'grandTotal' => $this->input->post('grandTotal'),
                        'userId' => $userId,
                        'compId' => $compId,
                        'compBranchId' => $compBranchId,
                        'financialYear' => $financialYear,
                        'created' => $created,
                        'createdBy' => $createdBy,
                    ];
                    
                    $result = $this->user_model->saveAry('employee_sales', $employeeSalesData);
                    
                    if ($this->session->userdata('BranchRole')) {
                        $sql = "SELECT Id FROM employees WHERE UserId = '" . $userId . "' AND CompId = '" . $compId . "' AND CompanyBranchId = '" . $compBranchId . "' AND IsDeleted = 1 AND Status = 1 AND WarehouseSalesman IS NOT NULL AND JSON_VALID(WarehouseSalesman) AND JSON_CONTAINS(WarehouseSalesman, '[\"$employeeId\"]')";                        
                    } else {
                        $sql = "SELECT Id FROM employees WHERE UserId = '" . $userId . "' AND CompId = '" . $compId . "' AND IsDeleted = 1 AND Status = 1 AND WarehouseSalesman IS NOT NULL AND JSON_VALID(WarehouseSalesman) AND JSON_CONTAINS(WarehouseSalesman, '[\"$employeeId\"]')";
                    }                    
                    $query = $this->db->query($sql);
                    
                    foreach ($this->input->post('parties') as $key => $party) {
                        if (!empty($party['distributorName']) && !empty($party['retailerName']) && !empty($party['location'])) {
                            $employeeSalesPartiesData = [
                                'date' => $this->input->post('date'),
                                'employeeSalesId' => $result,
                                'billingDate' => $party['billingDate'],
                                'distributorName' => $party['distributorName'],
                                'retailerName' => $party['retailerName'],
                                'location' => $party['location'],
                                'contactPerson' => $party['contactPerson'],
                                'mobileNumber' => $party['mobileNumber'],
                                'netSale' => $party['netSale'],
                                'remark' => $party['remark'],
                                'userId' => $userId,
                                'compId' => $compId,
                                'compBranchId' => $compBranchId,
                                'seniorEmployeeId' => $query->row()->Id,
                                'employeeId' => $this->input->post('employeeId'),
                                'financialYear' => $financialYear,
                                'created' => $created,
                                'createdBy' => $createdBy
                            ];
                            $this->user_model->saveAry('employee_sales_parties', $employeeSalesPartiesData);
                        }
                    }
                    
                    if (!empty($result)) {
                        echo json_encode(['status' => true, 'message' => 'Record save successfully']);
                        return;
                    } else {
                        echo json_encode(['status' => false, 'message' => 'Unable to save record']);
                        return;
                    }                    
                }                
            }
        }
        
        public function employeeSalesReport()
        {
            $employeeId = $this->session->userdata('EmployeeId');
            $userId = $this->session->userdata('UserId');
            $compId = $this->session->userdata('CompId');
            $compBranchId = $this->session->userdata('CompBranchId');
            $financialYear = $this->session->userdata('financial_year');
            if ($this->input->get('from') && $this->input->get('to')) {
                $from = $this->input->get('from');
                $to = $this->input->get('to');
            } else {
                $from = date('Y-m-d');
                $to = date('Y-m-d');
            }
            
            $where = ['Id' => $employeeId, 'UserId' => $userId, 'CompId' => $compId, 'IsDeleted' => 1];
            $employeePermissionWhere = ['EmployeeId' => $employeeId, 'UserId' => $userId, 'CompId' => $compId];
            $employeeSalesWhere = ['date >= ' => $from, 'date <= ' => $to, 'employeeId' => $employeeId, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $compBranchId;
                $employeePermissionWhere['CompanyBranchId'] = $compBranchId;                
                $employeeSalesWhere['compBranchId'] = $compBranchId;                
            }
            
            $data['EmployeeInfo'] = $this->db->where($where)->get('employees')->row();
            $data['EmployeePermission'] = $this->db->where($employeePermissionWhere)->get('employee_permission')->row();
            $data['employeeSales'] = $this->db->where($employeeSalesWhere)->order_by('id', 'DESC')->get('employee_sales')->result();
            $data['page'] = "employeeSalesReport";
            return $this->load->view('inventory_sep/Employee/Employee', $data);
        }
        
        public function employeeSalesInfo($id)
        {
            if (!empty($id)) {
                $employeeId = $this->session->userdata('EmployeeId');
                $userId = $this->session->userdata('UserId');
                $compId = $this->session->userdata('CompId');
                $compBranchId = $this->session->userdata('CompBranchId');
                $financialYear = $this->session->userdata('financial_year');
                
                $employeeSalesWhere = ['id' => $id, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear];
                $employeeSalesPartiesWhere = ['employeeSalesId' => $id, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear];
                if ($this->session->userdata('BranchRole')) {
                    $employeeSalesWhere['compBranchId'] = $compBranchId;
                    $employeeSalesPartiesWhere['compBranchId'] = $compBranchId;
                }

                if ($this->session->userdata('RoleType') == "Salesman") {
                    $employeeSalesWhere['employeeId'] = $employeeId;
                }
                
                $data['employeeSalesInfo'] = $this->db->where($employeeSalesWhere)->get('employee_sales')->row();
                $data['employeeSalesParties'] = $this->db->where($employeeSalesPartiesWhere)->get('employee_sales_parties')->result();
                return $this->load->view('inventory_sep/Employee/employeeSalesInfo', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }
        }
        
        public function employeeSalesEdit($id)
        {
            if (!empty($id)) {
                $employeeId = $this->session->userdata('EmployeeId');
                $userId = $this->session->userdata('UserId');
                $compId = $this->session->userdata('CompId');
                $compBranchId = $this->session->userdata('CompBranchId');
                $financialYear = $this->session->userdata('financial_year');
                
                $where = ['Id' => $employeeId, 'UserId' => $userId, 'CompId' => $compId, 'IsDeleted' => 1];
                $employeePermissionWhere = ['EmployeeId' => $employeeId, 'UserId' => $userId, 'CompId' => $compId];
                $employeeSalesWhere = ['id' => $id, 'isDeleted' => 1, 'employeeId' => $employeeId, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear];
                if ($this->session->userdata('BranchRole')) {
                    $where['CompanyBranchId'] = $compBranchId;
                    $employeePermissionWhere['CompanyBranchId'] = $compBranchId;
                    $employeeSalesWhere['compBranchId'] = $compBranchId;
                }

                $data['EmployeeInfo'] = $this->db->where($where)->get('employees')->row();
                $data['EmployeePermission'] = $this->db->where($employeePermissionWhere)->get('employee_permission')->row();
                $data['employeeSalesInfo'] = $this->db->where($employeeSalesWhere)->get('employee_sales')->row();
                $data['employeeSalesPartiesId'] = $id;
                $data['statesList'] = $this->db->select('id, state, code')->get('state_list')->result();
                $data['page'] = "employeeSalesEdit";
                return $this->load->view('inventory_sep/Employee/Employee', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }
        }
        
        public function getEmployeeSalesParties($employeeSalesPartiesId)
        {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                if (!empty($employeeSalesPartiesId)) {
                    $employeeId = $this->session->userdata('EmployeeId');
                    $userId = $this->session->userdata('UserId');
                    $compId = $this->session->userdata('CompId');
                    $compBranchId = $this->session->userdata('CompBranchId');
                    $financialYear = $this->session->userdata('financial_year');
                    
                    $salesPartiesWhere = ['employeeSalesId' => $employeeSalesPartiesId, 'employeeId' => $employeeId, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear];
                    if ($this->session->userdata('BranchRole')) {
                        $salesPartiesWhere['compBranchId'] = $compBranchId;
                    }
                    
                    $employeeSalesPartiesQuery = $this->db->select('id, billingDate, distributorName, retailerName, location, contactPerson, mobileNumber, netSale, remark')->where($salesPartiesWhere)->get('employee_sales_parties');
                    if ($employeeSalesPartiesQuery->num_rows()) {
                        echo json_encode(array('status' => true, "data" => $employeeSalesPartiesQuery->result()));
                    } else {
                        echo json_encode(array('status' => false, "data" => "No Product found."));
                    }
                } else {
                    echo json_encode(array('status' => false, "data" => "id missing."));
                }
            }
        }
                
        public function deleteEmployeeSalesData()
        {
            if ($this->input->is_ajax_request()) {
                if (!empty($this->input->post('rowId'))) {
                    $rowId = $this->input->post('rowId');
                    $employeeId = $this->session->userdata('EmployeeId');
                    $userId = $this->session->userdata('UserId');
                    $compId = $this->session->userdata('CompId');
                    $compBranchId = $this->session->userdata('CompBranchId');
                    
                    $where = ['employeeId' => $employeeId, 'userId' => $userId, 'compId' => $compId, 'isDeleted' => 1];
                    $salesDataWhere = ['employeeId' => $employeeId, 'id' => $rowId, 'userId' => $userId, 'compId' => $compId, 'isDeleted' => 1];
                    if ($this->session->userdata('BranchRole')) {
                        $where['compBranchId'] = $compBranchId;
                        $salesDataWhere['compBranchId'] = $compBranchId;
                    }
                    
                    $employeeSalesData = $this->db->select('employeeSalesId, netSale')->where($salesDataWhere)->get('employee_sales_parties')->row();
                    $where['id'] = $employeeSalesData->employeeSalesId;
                    $currentGrandTotal = get_title('employee_sales', $where, 'grandTotal');
                    $newGrandTotal = $currentGrandTotal - $employeeSalesData->netSale;
                    $this->db->where($where)->update('employee_sales', ['grandTotal' => $newGrandTotal]);
                    
                    $result = $this->db->where($salesDataWhere)->update('employee_sales_parties', ['isDeleted' => 0]);
                    if ($result) {
                        echo json_encode(array('status' => true, "message" => "Record successfully deleted."));
                        return;
                    } else {
                        echo json_encode(array('status' => false, "message" => "Somethings went wrong, try again."));
                        return;
                    }
                } else {
                    echo json_encode(array('status' => false, "message" => "Id not found."));
                    return;
                }
            }
        }        
        
        public function updateEmployeeSales()
        {
            if ($this->input->is_ajax_request()) {
                $arraySalesData = [];
                foreach ($this->input->post('parties') as $key => $row) {
                    if ($row['distributorName'] == '' || $row['retailerName'] == '' || $row['location'] == '') {
                        $arraySalesData[] = $row;
                    }
                }

                if (!empty($arraySalesData)) {
                    echo json_encode(['status' => false, 'message' => 'Please enter sales required fields.']);
                    return;
                }
                    
                $this->form_validation->set_rules('date', 'Date', 'trim|required');
                $this->form_validation->set_rules('employeeName', 'Employee Name', 'trim|required');
                $this->form_validation->set_rules('designation', 'Designation', 'trim|required');
                $this->form_validation->set_rules('month', 'Month', 'trim|required');
                $this->form_validation->set_rules('stateId', 'State', 'trim|required');
                $this->form_validation->set_rules('grandTotal', 'Total', 'trim|numeric|required');
                if ($this->form_validation->run() == false) {
                    echo json_encode(['status' => false, 'message' => validation_errors()]);
                    return;                    
                } else {
                    $employeeSalesId = $this->input->post('employeeSalesId');
                    $employeeId = $this->session->userdata('EmployeeId');
                    $userId = $this->session->userdata('UserId');
                    $compId = $this->session->userdata('CompId');
                    $compBranchId = $this->session->userdata('CompBranchId');
                    $updated = date('d-m-Y h:i:s A');
                    $updatedBy = get_title('employees', ['Id' => $employeeId], 'Name');
                    
                    $compProfileWhere = ['user_id' => $userId, 'comp_id' => $compId];
                    $updateWhere = ['id' => $employeeSalesId, 'employeeId' => $employeeId, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId];
                    $updateDataWhere = ['employeeSalesId' => $employeeSalesId, 'employeeId' => $employeeId, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId];
                    if ($this->session->userdata('BranchRole')) {
                        $compProfileWhere['CompanyBranchId'] = $compBranchId;
                        $updateWhere['compBranchId'] = $compBranchId;
                        $updateDataWhere['compBranchId'] = $compBranchId;
                    }
                    $financialYear = get_title('comp_profile', $compProfileWhere, 'financial_year');

                    $employeeSalesData = [
                        'date' => $this->input->post('date'),
                        'baseLocation' => $this->input->post('baseLocation'),
                        'band' => $this->input->post('band'),
                        'stateId' => $this->input->post('stateId'),
                        'month' => $this->input->post('month'),
                        'department' => $this->input->post('department'),
                        'reportingSenior' => $this->input->post('reportingSenior'),
                        'remark' => $this->input->post('remark'),
                        'grandTotal' => $this->input->post('grandTotal'),
                        'updated' => $updated,
                        'updatedBy' => $updatedBy,
                    ];                    
                    
                    $result = $this->user_model->updateAry('employee_sales', $employeeSalesData, $updateWhere);
                                                        
                    foreach ($this->input->post('parties') as $key => $party) {
                        if (!empty($party['rowId']) && !empty($party['distributorName']) && !empty($party['retailerName']) && !empty($party['location'])) {
                            $employeeSalesPartiesData = [
                                'date' => $this->input->post('date'),
                                'billingDate' => $party['billingDate'],
                                'distributorName' => $party['distributorName'],
                                'retailerName' => $party['retailerName'],
                                'location' => $party['location'],
                                'contactPerson' => $party['contactPerson'],
                                'mobileNumber' => $party['mobileNumber'],
                                'netSale' => $party['netSale'],
                                'remark' => $party['remark'],
                                'updated' => $updated,
                                'updatedBy' => $updatedBy
                            ];
                            $updateDataWhere['id'] = $party['rowId'];
                            $this->user_model->updateAry('employee_sales_parties', $employeeSalesPartiesData, $updateDataWhere);
                        }
                    }
                    
                    if ($this->session->userdata('BranchRole')) {
                        $sql = "SELECT Id FROM employees WHERE UserId = '" . $userId . "' AND CompId = '" . $compId . "' AND CompanyBranchId = '" . $compBranchId . "' AND IsDeleted = 1 AND Status = 1 AND WarehouseSalesman IS NOT NULL AND JSON_VALID(WarehouseSalesman) AND JSON_CONTAINS(WarehouseSalesman, '[\"$employeeId\"]')";                        
                    } else {
                        $sql = "SELECT Id FROM employees WHERE UserId = '" . $userId . "' AND CompId = '" . $compId . "' AND IsDeleted = 1 AND Status = 1 AND WarehouseSalesman IS NOT NULL AND JSON_VALID(WarehouseSalesman) AND JSON_CONTAINS(WarehouseSalesman, '[\"$employeeId\"]')";
                    }                    
                    $query = $this->db->query($sql);
                    
                    foreach ($this->input->post('newParties') as $key => $partyData) {
                        if (!empty($partyData['distributorName']) && !empty($partyData['retailerName']) && !empty($partyData['location'])) {
                            $employeeSalesNewPartiesData = [
                                'date' => $this->input->post('date'),
                                'employeeSalesId' => $employeeSalesId,
                                'billingDate' => $partyData['billingDate'],
                                'distributorName' => $partyData['distributorName'],
                                'retailerName' => $partyData['retailerName'],
                                'location' => $partyData['location'],
                                'contactPerson' => $partyData['contactPerson'],
                                'mobileNumber' => $partyData['mobileNumber'],
                                'netSale' => $partyData['netSale'],
                                'remark' => $partyData['remark'],
                                'userId' => $userId,
                                'compId' => $compId,
                                'compBranchId' => $compBranchId,
                                'seniorEmployeeId' => $query->row()->Id,
                                'employeeId' => $employeeId,
                                'financialYear' => $financialYear,
                                'created' => $updated,
                                'createdBy' => $updatedBy
                            ];
                            $this->user_model->saveAry('employee_sales_parties', $employeeSalesNewPartiesData);
                        }
                    }
                    
                    if (!empty($result)) {
                        echo json_encode(['status' => true, 'message' => 'Record update successfully']);
                        return;
                    } else {
                        echo json_encode(['status' => false, 'message' => 'Unable to save record']);
                        return;
                    }                    
                }                
            }
        }
        
        public function employeeSalesDelete($id)
        {
            if (!empty($id)) {
                $where = ['id' => $id, 'isDeleted' => 1];
                if ($this->db->select('id')->where($where)->get('employee_sales')->num_rows()) {
                    if ($this->user_model->updateAry('employee_sales', ['isDeleted' => 0], $where)) {
                        $this->user_model->updateAry('employee_sales_parties', ['isDeleted' => 0], ['employeeSalesId' => $id, 'isDeleted' => 1]);
                        $this->session->set_flashdata(['msg' => 'Record delete successfully.', 'class' => 'success']);
                        return redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to delete record.', 'class' => 'danger']);
                        return redirect($_SERVER['HTTP_REFERER']);
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                    return redirect($_SERVER['HTTP_REFERER']);
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Id is missing.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }
        }
        
        public function employeeSalesReportDownload($id) 
        {
            ini_set('memory_limit', '-1');
            set_time_limit(500);            
            
            $employeeId = $this->session->userdata('EmployeeId');
            $userId = $this->session->userdata('UserId');
            $compId = $this->session->userdata('CompId');
            $compBranchId = $this->session->userdata('CompBranchId');
            $financialYear = $this->session->userdata('financial_year');
            
            $where = ['id' => $id, 'employeeId' => $employeeId, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear, 'isDeleted' => 1];
            $partiesWhere = ['employeeSalesId' => $id, 'employeeId' => $employeeId, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear, 'isDeleted' => 1];
            $compProfileWhere = ['user_id' => $userId, 'comp_id' => $compId];
            if ($this->session->userdata('BranchRole')) {
                $Name = get_title('companybranch', ['Id' => $compBranchId], 'BranchName');
                $where['compBranchId'] = $compBranchId;
                $partiesWhere['compBranchId'] = $compBranchId;
                $compProfileWhere['CompanyBranchId'] = $compBranchId;
            } else {
                $Name = get_title('inventory_billing', ['id' => $compId], 'company_name');
            }
            
            $logoPath = 'uploads/complogo/' . get_title('comp_profile', $compProfileWhere, 'logo');
            if (!file_exists($logoPath)) {
                $logoPath = 'assets/images/avatar.png';
            }
            
            $SaleReportQuery = $this->db->where($where)->get('employee_sales');
            
            if ($SaleReportQuery->num_rows()) {
                $saleData = $SaleReportQuery->row();
                
                $PHPExcel = new PHPExcel();
                
                $PHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(50);

                // Add the logo image
                $PHPExcelDrawing1 = new PHPExcel_Worksheet_Drawing();
                $PHPExcelDrawing1->setName('Logo');
                $PHPExcelDrawing1->setDescription('Logo');
                $PHPExcelDrawing1->setPath($logoPath);
                $PHPExcelDrawing1->setHeight(65);
                $PHPExcelDrawing1->setCoordinates('A1');
                $PHPExcelDrawing1->setWorksheet($PHPExcel->getActiveSheet());
                
                $PHPExcelDrawing2 = new PHPExcel_Worksheet_Drawing();
                $PHPExcelDrawing2->setName('Logo');
                $PHPExcelDrawing2->setDescription('Logo');
                $PHPExcelDrawing2->setPath($logoPath);
                $PHPExcelDrawing2->setHeight(65);
                $PHPExcelDrawing2->setCoordinates('H1');
                $PHPExcelDrawing2->setWorksheet($PHPExcel->getActiveSheet());
                
                $PHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('B1', $Name . ' SALES REPORT')
                    ->mergeCells('B1:G1')
                    ->setCellValue('A2', 'Name')
                    ->setCellValue('C2', get_title('employees', ['Id' => $saleData->employeeId], 'Name'))
                    ->mergeCells('A2:B2')
                    ->mergeCells('D2:E2')
                    ->setCellValue('D2', 'Month')
                    ->setCellValue('F2', $saleData->month)
                    ->mergeCells('F2:H2')
                    ->setCellValue('A3', 'Designation')
                    ->setCellValue('C3', get_title('position', ['Id' => get_title('employees', ['Id' => $saleData->employeeId], 'RoleId')], 'PostName'))
                    ->mergeCells('A3:B3')
                    ->mergeCells('D3:E3')
                    ->setCellValue('D3', 'Band')
                    ->setCellValue('F3', $saleData->band)
                    ->mergeCells('F3:H3')
                    ->setCellValue('A4', 'Base Location')
                    ->setCellValue('C4', $saleData->baseLocation)
                    ->mergeCells('A4:B4')
                    ->mergeCells('D4:E4')
                    ->setCellValue('D4', 'State')
                    ->setCellValue('F4', get_title('state_list', ['id' => $saleData->stateId], 'state'))
                    ->mergeCells('F4:H4')
                    ->setCellValue('A5', 'Reporting Senior')
                    ->setCellValue('C5', $saleData->reportingSenior)
                    ->mergeCells('A5:B5')
                    ->mergeCells('D5:E5')
                    ->setCellValue('D5', 'Department')
                    ->setCellValue('F5', $saleData->department)
                    ->mergeCells('F5:H5');

                // Add table headers
                $PHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A6', 'Billing Date')
                    ->setCellValue('B6', 'Distributor Name from whom material supplied')
                    ->setCellValue('C6', 'Retailer / Sub dealer Name')
                    ->setCellValue('D6', 'Location')
                    ->setCellValue('E6', 'Contact Person Name')
                    ->setCellValue('F6', 'Contact Person Mobile No')
                    ->setCellValue('G6', 'Bill value (Net Sale) excluding GST')
                    ->setCellValue('H6', 'Remark'); 
                
                $PHPExcel->getActiveSheet()->getStyle('A6:H6')->getAlignment()->setWrapText(true);
                $PHPExcel->getActiveSheet()->getStyle('A6:H6')->applyFromArray([
                    'alignment' => [
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                    ]
                ]);
                
                $styleArray = array(
                    'font' => array(
                        'bold' => true,
                        'size' => 12
                    )
                );

                $PHPExcel->getActiveSheet()->getStyle('A6:H6')->applyFromArray($styleArray);

                $PHPExcel->getActiveSheet()->getStyle('B1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 25
                    ],
                    'alignment' => [
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                    ]
                ]);
                
                $SaleReportPartiesQuery = $this->db->where($partiesWhere)->get('employee_sales_parties');
                if ($SaleReportPartiesQuery->num_rows()) {
                    $rowCount = 7;
                    $totalNetSale = 0;
                    foreach ($SaleReportPartiesQuery->result() as $key => $salesPerties) {
                        $totalNetSale += $salesPerties->netSale;
                        $PHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, date('d-m-Y', strtotime($salesPerties->billingDate)));
                        $PHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $salesPerties->distributorName);
                        $PHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $salesPerties->retailerName);
                        $PHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $salesPerties->location);
                        $PHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $salesPerties->contactPerson);
                        $PHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $salesPerties->mobileNumber);
                        $PHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $salesPerties->netSale);
                        $PHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $salesPerties->remark);
                        $rowCount++;
                    }
                    
                    $rowCount++;
                    $PHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, 'Total');
                    $PHPExcel->getActiveSheet()->setCellValue('G' . $rowCount, $totalNetSale);
                    $PHPExcel->getActiveSheet()->getStyle('A' . $rowCount)->getFont()->setBold(true);
                    $PHPExcel->getActiveSheet()->getStyle('G' . $rowCount)->getFont()->setBold(true);
                }
                
                // Set column widths
                $PHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                $PHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
                $PHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
                $PHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
                $PHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
                $PHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
                $PHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
                $PHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(25);

                $PHPExcel->getActiveSheet()->getStyle('A1:H' . $rowCount)->applyFromArray(
                    array(
                        'borders' => array(
                            'allborders' => array(
                                'style' => PHPExcel_Style_Border::BORDER_THIN,
                                'color' => array('rgb' => '000435')
                            )
                        )
                    )
                );
                
                $PHPExcel->getActiveSheet()->setTitle('SALES_REPORT');
                
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="' . str_replace(' ', '_', $Name) . '_SALES_REPORT.xlsx"');
                header('Cache-Control: max-age=0');
                PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007')->save('php://output');
            } else {
                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);                
            }
        }
                
        public function seniorEmployeeSales()
        {
            $employeeId = $this->session->userdata('EmployeeId');
            $userId = $this->session->userdata('UserId');
            $compId = $this->session->userdata('CompId');
            $compBranchId = $this->session->userdata('CompBranchId');
            
            $where = ['Id' => $employeeId, 'UserId' => $userId, 'CompId' => $compId, 'IsDeleted' => 1];
            $employeePermissionWhere = ['EmployeeId' => $employeeId, 'UserId' => $userId, 'CompId' => $compId];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $compBranchId;
                $employeePermissionWhere['CompanyBranchId'] = $compBranchId;                
            }            
            
            $data['EmployeeInfo'] = $this->db->where($where)->get('employees')->row();
            $data['EmployeePermission'] = $this->db->where($employeePermissionWhere)->get('employee_permission')->row();
            $data['statesList'] = $this->db->select('id, state, code')->get('state_list')->result();
            $salesmanEmployeeIds = json_decode(get_title('employees', ['Id' => $employeeId, 'IsDeleted' => 1], 'WarehouseSalesman'), true) ?: [];
            $RoleId = get_title('position', ['PostName' => 'Salesman'], 'Id');
            $data['employees'] = $this->db->select('Id, Name')->where('RoleId', $RoleId)->where_in('Id', $salesmanEmployeeIds)->get('employees')->result();            
            $data['page'] = "seniorEmployeeSales";
            return $this->load->view('inventory_sep/Employee/Employee', $data);
        }
        
        public function saveSeniorEmployeeSales()
        {
            if ($this->input->is_ajax_request()) {
                $arraySalesData = [];
                foreach ($this->input->post('parties') as $key => $row) {
                    if ($row['employeeId'] == '' || $row['distributorName'] == '' || $row['retailerName'] == '' || $row['location'] == '') {
                        $arraySalesData[] = $row;
                    }
                }

                if (!empty($arraySalesData)) {
                    echo json_encode(['status' => false, 'message' => 'Please enter sales required fields.']);
                    return;
                }
                
                $this->form_validation->set_rules('date', 'Date', 'trim|required');
                $this->form_validation->set_rules('month', 'Month', 'trim|required');
                $this->form_validation->set_rules('stateId', 'State', 'trim|required');
                $this->form_validation->set_rules('grandTotal', 'Total', 'trim|numeric|required');
                if ($this->form_validation->run() == false) {
                    echo json_encode(['status' => false, 'message' => validation_errors()]);
                    return;                    
                } else {
                    $employeeId = $this->session->userdata('EmployeeId');
                    $userId = $this->session->userdata('UserId');
                    $compId = $this->session->userdata('CompId');
                    $compBranchId = $this->session->userdata('CompBranchId');
                    $created = date('d-m-Y h:i:s A');
                    $createdBy = get_title('employees', ['Id' => $employeeId], 'Name');
                    
                    $compProfileWhere = ['user_id' => $userId, 'comp_id' => $compId];
                    if ($this->session->userdata('BranchRole')) {
                        $compProfileWhere['CompanyBranchId'] = $compBranchId;
                    }
                    $financialYear = get_title('comp_profile', $compProfileWhere, 'financial_year');
                    
                    $employeeSalesData = [];
                    foreach ($this->input->post('parties') as $key => $partyData) {
                        if (!empty($partyData['employeeId'])) {
                            $SalesmanId = $partyData['employeeId'];
                            if (isset($employeeSalesData[$SalesmanId])) {
                                $employeeSalesData[$SalesmanId]['grandTotal'] += $partyData['netSale'];
                            } else {
                                $employeeSalesData[$SalesmanId] = [
                                    'date' => $this->input->post('date'),
                                    'employeeId' => $SalesmanId,
                                    'baseLocation' => $this->input->post('baseLocation'),
                                    'band' => $this->input->post('band'),
                                    'stateId' => $this->input->post('stateId'),
                                    'month' => $this->input->post('month'),
                                    'department' => $this->input->post('department'),
                                    'reportingSenior' => $this->input->post('reportingSenior'),
                                    'remark' => $this->input->post('remark'),                                    
                                    'grandTotal' => $partyData['netSale'],
                                    'userId' => $userId,
                                    'compId' => $compId,
                                    'compBranchId' => $compBranchId,
                                    'financialYear' => $financialYear,
                                    'created' => $created,
                                    'createdBy' => $createdBy
                                ];
                            }
                        }
                    }
                    
                    $employeeSalesIds = [];
                    foreach ($employeeSalesData as $SalesmanId => $salesmanData) {
                        $result = $this->user_model->saveAry('employee_sales', $salesmanData);
                        $employeeSalesIds[$SalesmanId] = $result;
                    }

                    foreach ($this->input->post('parties') as $key => $party) {
                        if (!empty($party['employeeId']) && !empty($party['distributorName']) && !empty($party['retailerName']) && !empty($party['location'])) {
                            $employeeSalesPartiesData = [
                                'date' => $this->input->post('date'),
                                'employeeSalesId' => $employeeSalesIds[$party['employeeId']],
                                'billingDate' => $party['billingDate'],
                                'distributorName' => $party['distributorName'],
                                'retailerName' => $party['retailerName'],
                                'location' => $party['location'],
                                'contactPerson' => $party['contactPerson'],
                                'mobileNumber' => $party['mobileNumber'],
                                'netSale' => $party['netSale'],
                                'remark' => $party['remark'],
                                'userId' => $userId,
                                'compId' => $compId,
                                'compBranchId' => $compBranchId,
                                'seniorEmployeeId' => $employeeId,
                                'employeeId' => $party['employeeId'],
                                'financialYear' => $financialYear,
                                'created' => $created,
                                'createdBy' => $createdBy
                            ];
                            $this->user_model->saveAry('employee_sales_parties', $employeeSalesPartiesData);
                        }
                    }
                    
                    if (!empty($result)) {
                        echo json_encode(['status' => true, 'message' => 'Record save successfully']);
                        return;
                    } else {
                        echo json_encode(['status' => false, 'message' => 'Unable to save record']);
                        return;
                    }                    
                }                
            }
        }        
        
        public function seniorEmployeeSalesReport()
        {
            $employeeId = $this->session->userdata('EmployeeId');
            $userId = $this->session->userdata('UserId');
            $compId = $this->session->userdata('CompId');
            $compBranchId = $this->session->userdata('CompBranchId');
            $financialYear = $this->session->userdata('financial_year');
            if ($this->input->get('from') && $this->input->get('to')) {
                $from = $this->input->get('from');
                $to = $this->input->get('to');
            } else {
                $from = date('Y-m-d');
                $to = date('Y-m-d');
            }
            
            $where = ['Id' => $employeeId, 'UserId' => $userId, 'CompId' => $compId, 'IsDeleted' => 1];
            $employeePermissionWhere = ['EmployeeId' => $employeeId, 'UserId' => $userId, 'CompId' => $compId];
            $employeeSalesWhere = ['date >= ' => $from, 'date <= ' => $to, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $compBranchId;
                $employeePermissionWhere['CompanyBranchId'] = $compBranchId;                
                $employeeSalesWhere['compBranchId'] = $compBranchId;                
            }
            
            if ($this->input->get('salesmanId') != "" && $this->input->get('salesmanId') != 'All') {
                $salesmanIds = $this->input->get('salesmanId');
            } else {
                $salesmanIds = json_decode(get_title('employees', ['Id' => $employeeId, 'IsDeleted' => 1], 'WarehouseSalesman'), true) ?: [];
            }
            
            $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
            $data['EmployeeInfo'] = $this->db->where($where)->get('employees')->row();
            $data['EmployeePermission'] = $this->db->where($employeePermissionWhere)->get('employee_permission')->row();
            $employeeSalesmanIds = json_decode(get_title('employees', ['Id' => $employeeId, 'IsDeleted' => 1], 'WarehouseSalesman'), true) ?: [];
            $RoleId = get_title('position', ['PostName' => 'Salesman'], 'Id');
            $data['salesmans'] = $this->db->select('Id, Name')->where('RoleId', $RoleId)->where_in('Id', $employeeSalesmanIds)->get('employees')->result();
            $data['employeeSales'] = $this->db->select('id, date, month, SUM(grandTotal) AS grandTotal')->where($employeeSalesWhere)->where_in('employeeId', $salesmanIds)->group_by('date')->order_by('id', 'DESC')->get('employee_sales')->result();
            $data['page'] = "seniorEmployeeSalesReport";
            return $this->load->view('inventory_sep/Employee/Employee', $data);
        }        
        
        public function seniorEmployeeSalesInfo()
        {
            $employeeId = $this->session->userdata('EmployeeId');
            $userId = $this->session->userdata('UserId');
            $compId = $this->session->userdata('CompId');
            $compBranchId = $this->session->userdata('CompBranchId');
            $financialYear = $this->session->userdata('financial_year');
            if ($this->input->get('from') && $this->input->get('to')) {
                $from = $this->input->get('from');
                $to = $this->input->get('to');
            } else {
                $from = date('Y-m-d');
                $to = date('Y-m-d');
            }
            $employeeSalesWhere = ['date >= ' => $from, 'date <= ' => $to, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear];
            $employeeSalesPartiesWhere = ['date >= ' => $from, 'date <= ' => $to, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear];
            if ($this->input->get('employeeId') != "" && $this->input->get('employeeId') != 'All') {
                $salesmanIds = $this->input->get('employeeId');
            } else {
                $salesmanIds = json_decode(get_title('employees', ['Id' => $employeeId, 'IsDeleted' => 1], 'WarehouseSalesman'), true) ?: [];
            }
            if ($this->session->userdata('BranchRole')) {
                $employeeSalesWhere['compBranchId'] = $compBranchId;
                $employeeSalesPartiesWhere['compBranchId'] = $compBranchId;
            }
            
            $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");            
            $data['employeeSalesInfo'] = $this->db->where($employeeSalesWhere)->where_in('employeeId', $salesmanIds)->group_by('date')->order_by('id', 'DESC')->get('employee_sales')->row();
            $data['employeeSalesParties'] = $this->db->where($employeeSalesPartiesWhere)->where_in('employeeId', $salesmanIds)->get('employee_sales_parties')->result();
            return $this->load->view('inventory_sep/Employee/seniorEmployeeSalesInfo', $data);
        }        
        
        public function seniorEmployeeSalesEdit()
        {
            $employeeId = $this->session->userdata('EmployeeId');
            $userId = $this->session->userdata('UserId');
            $compId = $this->session->userdata('CompId');
            $compBranchId = $this->session->userdata('CompBranchId');
            $financialYear = $this->session->userdata('financial_year');
            if ($this->input->get('from') && $this->input->get('to')) {
                $from = $this->input->get('from');
                $to = $this->input->get('to');
            }
            $where = ['Id' => $employeeId, 'UserId' => $userId, 'CompId' => $compId, 'IsDeleted' => 1];
            $employeePermissionWhere = ['EmployeeId' => $employeeId, 'UserId' => $userId, 'CompId' => $compId];
            $employeeSalesWhere = ['date >= ' => $from, 'date <= ' => $to, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $compBranchId;
                $employeePermissionWhere['CompanyBranchId'] = $compBranchId;
                $employeeSalesWhere['compBranchId'] = $compBranchId;
            }
            if ($this->input->get('department') != '') {
                $employeeSalesWhere['department'] = $this->input->get('department');
            }
            if ($this->input->get('band') != '') {
                $employeeSalesWhere['band'] = $this->input->get('band');
            }
            if ($this->input->get('stateId') != '') {
                $employeeSalesWhere['stateId'] = $this->input->get('stateId');
            }
            if ($this->input->get('employeeId') != 'All') {
                $salesmanIds = $this->input->get('employeeId');
            } else {
                $salesmanIds = json_decode(get_title('employees', ['Id' => $employeeId, 'IsDeleted' => 1], 'WarehouseSalesman'), true) ?: [];
            }            

            $data['EmployeeInfo'] = $this->db->where($where)->get('employees')->row();
            $data['EmployeePermission'] = $this->db->where($employeePermissionWhere)->get('employee_permission')->row();
            $employeeSalesQuery = $this->db->where($employeeSalesWhere)->where_in('employeeId', $salesmanIds)->get('employee_sales');
            if ($employeeSalesQuery->num_rows()) {
                $data['employeeSalesInfo'] = $employeeSalesQuery->row();
                $data['from'] = $from;
                $data['to'] = $to;
                $data['employeeId'] = $this->input->get('employeeId');
                $salesmanEmployeeIds = json_decode(get_title('employees', ['Id' => $employeeId, 'IsDeleted' => 1], 'WarehouseSalesman'), true) ?: [];
                $RoleId = get_title('position', ['PostName' => 'Salesman'], 'Id');
                $data['employees'] = $this->db->select('Id, Name')->where('RoleId', $RoleId)->where_in('Id', $salesmanEmployeeIds)->get('employees')->result();
                $data['statesList'] = $this->db->select('id, state, code')->get('state_list')->result();
                $data['page'] = "seniorEmployeeSalesEdit";
                return $this->load->view('inventory_sep/Employee/Employee', $data);
            } else {
                return redirect($_SERVER['HTTP_REFERER']);
            }            
        }
        
        public function getSeniorEmployeeSalesParties()
        {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $employeeId = $this->session->userdata('EmployeeId');
                $userId = $this->session->userdata('UserId');
                $compId = $this->session->userdata('CompId');
                $compBranchId = $this->session->userdata('CompBranchId');
                $financialYear = $this->session->userdata('financial_year');
                $from = $this->input->get('from');
                $to = $this->input->get('to');
                
                $salesPartiesWhere = ['date >= ' => $from, 'date <= ' => $to, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear];
                if ($this->session->userdata('BranchRole')) {
                    $salesPartiesWhere['compBranchId'] = $compBranchId;
                }
                if ($this->input->get('employeeId') != 'All') {
                    $salesmanIds = $this->input->get('employeeId');
                } else {
                    $salesmanIds = json_decode(get_title('employees', ['Id' => $employeeId, 'IsDeleted' => 1], 'WarehouseSalesman'), true) ?: [];
                }

                $employeeSalesPartiesQuery = $this->db->select('id, billingDate, distributorName, retailerName, location, contactPerson, mobileNumber, netSale, employeeId, remark, created, createdBy, updated, updatedBy')->where($salesPartiesWhere)->where_in('employeeId', $salesmanIds)->get('employee_sales_parties');
                if ($employeeSalesPartiesQuery->num_rows()) {
                    echo json_encode(array('status' => true, "data" => $employeeSalesPartiesQuery->result()));
                } else {
                    echo json_encode(array('status' => false, "data" => "No Product found."));
                }
            }
        }        
        
        public function deleteSeniorEmployeeSalesData()
        {
            if ($this->input->is_ajax_request()) {
                if (!empty($this->input->post('rowId'))) {
                    $rowId = $this->input->post('rowId');
                    $employeeId = $this->session->userdata('EmployeeId');
                    $userId = $this->session->userdata('UserId');
                    $compId = $this->session->userdata('CompId');
                    $compBranchId = $this->session->userdata('CompBranchId');
                    
                    $where = ['userId' => $userId, 'compId' => $compId, 'isDeleted' => 1];
                    $salesDataWhere = ['id' => $rowId, 'userId' => $userId, 'compId' => $compId, 'isDeleted' => 1];
                    if ($this->session->userdata('BranchRole')) {
                        $where['compBranchId'] = $compBranchId;
                        $salesDataWhere['compBranchId'] = $compBranchId;
                    }
                    
                    $salesmanIds = json_decode(get_title('employees', ['Id' => $employeeId, 'IsDeleted' => 1], 'WarehouseSalesman'), true) ?: [];
                    $employeeSalesData = $this->db->select('employeeSalesId, netSale')->where($salesDataWhere)->where_in('employeeId', $salesmanIds)->get('employee_sales_parties')->row();
                    $where['id'] = $employeeSalesData->employeeSalesId;
                    $currentGrandTotal = $this->db->select('grandTotal')->where($where)->where_in('employeeId', $salesmanIds)->get('employee_sales')->row('grandTotal');
                    $newGrandTotal = $currentGrandTotal - $employeeSalesData->netSale;
                    $this->db->where($where)->where_in('employeeId', $salesmanIds)->update('employee_sales', ['grandTotal' => $newGrandTotal]);
                    
                    $result = $this->db->where($salesDataWhere)->where_in('employeeId', $salesmanIds)->update('employee_sales_parties', ['isDeleted' => 0]);
                    if ($result) {
                        echo json_encode(array('status' => true, "message" => "Record successfully deleted."));
                        return;
                    } else {
                        echo json_encode(array('status' => false, "message" => "Somethings went wrong, try again."));
                        return;
                    }
                } else {
                    echo json_encode(array('status' => false, "message" => "Id not found."));
                    return;
                }
            }
        }               
        
        public function updateSeniorEmployeeSales()
        {
            if ($this->input->is_ajax_request()) {
                $arraySalesData = [];
                foreach ($this->input->post('parties') as $key => $row) {
                    if ($row['distributorName'] == '' || $row['retailerName'] == '' || $row['location'] == '') {
                        $arraySalesData[] = $row;
                    }
                }

                if (!empty($arraySalesData)) {
                    echo json_encode(['status' => false, 'message' => 'Please enter sales required fields.']);
                    return;
                }
                    
                $this->form_validation->set_rules('date', 'Date', 'trim|required');
                $this->form_validation->set_rules('month', 'Month', 'trim|required');
                $this->form_validation->set_rules('stateId', 'State', 'trim|required');
                if ($this->form_validation->run() == false) {
                    echo json_encode(['status' => false, 'message' => validation_errors()]);
                    return;                    
                } else {
                    $from = $this->input->post('from');
                    $to = $this->input->post('to');
                    $employeeId = $this->session->userdata('EmployeeId');
                    $userId = $this->session->userdata('UserId');
                    $compId = $this->session->userdata('CompId');
                    $compBranchId = $this->session->userdata('CompBranchId');
                    $updated = date('d-m-Y h:i:s A');
                    $updatedBy = get_title('employees', ['Id' => $employeeId], 'Name');
                    
                    $compProfileWhere = ['user_id' => $userId, 'comp_id' => $compId];
                    $updateWhere = ['date >= ' => $from, 'date <= ' => $to, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId];
                    $updateDataWhere = ['date >= ' => $from, 'date <= ' => $to, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId];
                    if ($this->session->userdata('BranchRole')) {
                        $compProfileWhere['CompanyBranchId'] = $compBranchId;
                        $updateWhere['compBranchId'] = $compBranchId;
                        $updateDataWhere['compBranchId'] = $compBranchId;
                    }
                    
                    $financialYear = get_title('comp_profile', $compProfileWhere, 'financial_year');
                    
                    if ($this->input->post('employeeId') != 'All') {
                        $salesmanIds = $this->input->post('employeeId');
                    } else {
                        $salesmanIds = json_decode(get_title('employees', ['Id' => $employeeId, 'IsDeleted' => 1], 'WarehouseSalesman'), true) ?: [];
                    }
                                        
                    $employeeSalesData = [
                        'date' => $this->input->post('date'),
                        'baseLocation' => $this->input->post('baseLocation'),
                        'band' => $this->input->post('band'),
                        'stateId' => $this->input->post('stateId'),
                        'month' => $this->input->post('month'),
                        'department' => $this->input->post('department'),
                        'remark' => $this->input->post('remark'),
                        'updated' => $updated,
                        'updatedBy' => $updatedBy,
                    ];                    
                    
                    $result = $this->db->where($updateWhere)->where_in('employeeId', $salesmanIds)->update('employee_sales', $employeeSalesData);
                    
                    foreach ($this->input->post('parties') as $key => $party) {                        
                        if (!empty($party['rowId']) && !empty($party['distributorName']) && !empty($party['retailerName']) && !empty($party['location'])) {
                            $employeeSalesPartiesData = [
                                'date' => $this->input->post('date'),
                                'billingDate' => $party['billingDate'],
                                'distributorName' => $party['distributorName'],
                                'retailerName' => $party['retailerName'],
                                'location' => $party['location'],
                                'contactPerson' => $party['contactPerson'],
                                'mobileNumber' => $party['mobileNumber'],
                                'netSale' => $party['netSale'],
                                'remark' => $party['remark'],
                                'updated' => $updated,
                                'updatedBy' => $updatedBy
                            ];
                            $updateDataWhere['id'] = $party['rowId'];
                            $this->db->where($updateDataWhere)->where_in('employeeId', $salesmanIds)->update('employee_sales_parties', $employeeSalesPartiesData);
                        }
                    }
                    
                    foreach ($this->input->post('newParties') as $key => $partyData) {
                        if (!empty($partyData['distributorName']) && !empty($partyData['retailerName']) && !empty($partyData['location'])) {
                            $employeeSalesNewPartiesData = [
                                'date' => $this->input->post('date'),
                                'employeeSalesId' => $this->db->select('id')->where(['date >= ' => $from, 'date <= ' => $to, 'employeeId' => $partyData['employeeId']])->order_by('id', 'DESC')->get('employee_sales')->row('id'),
                                'billingDate' => $partyData['billingDate'],
                                'distributorName' => $partyData['distributorName'],
                                'retailerName' => $partyData['retailerName'],
                                'location' => $partyData['location'],
                                'contactPerson' => $partyData['contactPerson'],
                                'mobileNumber' => $partyData['mobileNumber'],
                                'netSale' => $partyData['netSale'],
                                'remark' => $partyData['remark'],
                                'userId' => $userId,
                                'compId' => $compId,
                                'compBranchId' => $compBranchId,
                                'seniorEmployeeId' => $employeeId,
                                'employeeId' => $partyData['employeeId'],
                                'financialYear' => $financialYear,
                                'created' => $updated,
                                'createdBy' => $updatedBy
                            ];
                            $this->user_model->saveAry('employee_sales_parties', $employeeSalesNewPartiesData);
                        }
                    }                    
                    
                    if (!empty($result)) {
                        echo json_encode(['status' => true, 'message' => 'Record update successfully']);
                        return;
                    } else {
                        echo json_encode(['status' => false, 'message' => 'Unable to save record']);
                        return;
                    }                    
                }                
            }
        }
        
        public function seniorEmployeeSalesDelete()
        {
            $employeeId = $this->session->userdata('EmployeeId');
            $userId = $this->session->userdata('UserId');
            $compId = $this->session->userdata('CompId');
            $compBranchId = $this->session->userdata('CompBranchId');
            $financialYear = $this->session->userdata('financial_year');
            
            $where = ['date >= ' => $this->input->get('from'), 'date <= ' => $this->input->get('to'), 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear];
            if ($this->session->userdata('BranchRole')) {
                $where['compBranchId'] = $compBranchId;
            }
            if ($this->input->get('employeeId') != "" && $this->input->get('employeeId') != 'All') {
                $salesmanIds = $this->input->get('employeeId');
            } else {
                $salesmanIds = json_decode(get_title('employees', ['Id' => $employeeId, 'IsDeleted' => 1], 'WarehouseSalesman'), true) ?: [];
            }
            
            if ($this->db->select('id')->where($where)->where_in('employeeId', $salesmanIds)->get('employee_sales')->num_rows()) {
                if ($this->db->where($where)->where_in('employeeId', $salesmanIds)->update('employee_sales', ['isDeleted' => 0])) {
                    $this->db->where($where)->where_in('employeeId', $salesmanIds)->update('employee_sales_parties', ['isDeleted' => 0]);
                    $this->session->set_flashdata(['msg' => 'Record delete successfully.', 'class' => 'success']);
                    return redirect($_SERVER['HTTP_REFERER']);
                } else {
                    $this->session->set_flashdata(['msg' => 'Unable to delete record.', 'class' => 'danger']);
                    return redirect($_SERVER['HTTP_REFERER']);
                }
            } else {
                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }
        }
        
        public function seniorEmployeeSalesReportDownload() 
        {
            ini_set('memory_limit', '-1');
            set_time_limit(500);            
            
            $from = $this->input->get('from');
            $to = $this->input->get('to');
            $employeeId = $this->input->get('employeeId');
            $userId = $this->session->userdata('UserId');
            $compId = $this->session->userdata('CompId');
            $compBranchId = $this->session->userdata('CompBranchId');
            $financialYear = $this->session->userdata('financial_year');
            
            $where = ['date >= ' => $from, 'date <= ' => $to, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear, 'isDeleted' => 1];
            if ($employeeId != "" && $employeeId != 'All') {
                $salesmanIds = $employeeId;
            } else {
                $salesmanIds = json_decode(get_title('employees', ['Id' => $this->session->userdata('EmployeeId'), 'IsDeleted' => 1], 'WarehouseSalesman'), true) ?: [];
            }
            $partiesWhere = ['date >=' => $from, 'date <= ' => $to, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear, 'isDeleted' => 1];
            $compProfileWhere = ['user_id' => $userId, 'comp_id' => $compId];
            if ($this->session->userdata('BranchRole')) {
                $Name = get_title('companybranch', ['Id' => $compBranchId], 'BranchName');
                $where['compBranchId'] = $compBranchId;
                $partiesWhere['compBranchId'] = $compBranchId;
                $compProfileWhere['CompanyBranchId'] = $compBranchId;
            } else {
                $Name = get_title('inventory_billing', ['id' => $compId], 'company_name');
            }
            
            $logoPath = 'uploads/complogo/' . get_title('comp_profile', $compProfileWhere, 'logo');
            if (!file_exists($logoPath)) {
                $logoPath = 'assets/images/avatar.png';
            }
            
            $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");            
            $SaleReportQuery = $this->db->where($where)->where_in('employeeId', $salesmanIds)->group_by('date')->get('employee_sales');
            
            if ($SaleReportQuery->num_rows()) {
                $saleData = $SaleReportQuery->row();
                
                $PHPExcel = new PHPExcel();
                
                $PHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(50);

                // Add the logo image
                $PHPExcelDrawing1 = new PHPExcel_Worksheet_Drawing();
                $PHPExcelDrawing1->setName('Logo');
                $PHPExcelDrawing1->setDescription('Logo');
                $PHPExcelDrawing1->setPath($logoPath);
                $PHPExcelDrawing1->setHeight(65);
                $PHPExcelDrawing1->setCoordinates('A1');
                $PHPExcelDrawing1->setWorksheet($PHPExcel->getActiveSheet());
                
                $PHPExcelDrawing2 = new PHPExcel_Worksheet_Drawing();
                $PHPExcelDrawing2->setName('Logo');
                $PHPExcelDrawing2->setDescription('Logo');
                $PHPExcelDrawing2->setPath($logoPath);
                $PHPExcelDrawing2->setHeight(65);
                $PHPExcelDrawing2->setCoordinates('I1');
                $PHPExcelDrawing2->setWorksheet($PHPExcel->getActiveSheet());
                
                $PHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('B1', $Name . ' SALES REPORT')
                    ->mergeCells('B1:I1')
                    ->setCellValue('A2', 'Base Location')
                    ->setCellValue('C2', $saleData->baseLocation)
                    ->mergeCells('A2:B2')
                    ->mergeCells('C2:D2')
                    ->setCellValue('E2', 'State')
                    ->setCellValue('G2', get_title('state_list', ['id' => $saleData->stateId], 'code') . '-' . get_title('state_list', ['id' => $saleData->stateId], 'state'))
                    ->mergeCells('E2:F2')
                    ->mergeCells('G2:H2')
                    ->setCellValue('A3', 'Band')
                    ->setCellValue('C3', $saleData->band)
                    ->mergeCells('A3:B3')
                    ->mergeCells('C3:D3')
                    ->setCellValue('E3', 'Department')
                    ->setCellValue('G3', $saleData->department)
                    ->mergeCells('E3:F3')
                    ->mergeCells('G3:H3')
                    ->setCellValue('A4', 'Month')
                    ->setCellValue('C4', $saleData->month)
                    ->mergeCells('A4:B4')
                    ->mergeCells('C4:D4')                    
                    ->mergeCells('E4:F4')                    
                    ->mergeCells('G4:H4');

                // Add table headers
                $PHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A5', 'Billing Date')
                    ->setCellValue('B5', 'Salesman')
                    ->setCellValue('C5', 'Distributor Name from whom material supplied')
                    ->setCellValue('D5', 'Retailer / Sub dealer Name')
                    ->setCellValue('E5', 'Location')
                    ->setCellValue('F5', 'Contact Person Name')
                    ->setCellValue('G5', 'Contact Person Mobile No')
                    ->setCellValue('H5', 'Bill value (Net Sale) excluding GST')
                    ->setCellValue('I5', 'Remark');
                
                $PHPExcel->getActiveSheet()->getStyle('A5:I5')->getAlignment()->setWrapText(true);
                
                $styleArray = array(
                    'font' => array(
                        'bold' => true,
                        'size' => 12
                    )
                );

                $PHPExcel->getActiveSheet()->getStyle('A5:I5')->applyFromArray($styleArray);

                $PHPExcel->getActiveSheet()->getStyle('B1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 25
                    ],
                    'alignment' => [
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                    ]
                ]);
                
                $SaleReportPartiesQuery = $this->db->where($partiesWhere)->where_in('employeeId', $salesmanIds)->get('employee_sales_parties');
                if ($SaleReportPartiesQuery->num_rows()) {
                    $rowCount = 6;
                    $totalNetSale = 0;
                    foreach ($SaleReportPartiesQuery->result() as $key => $salesPerties) {
                        $totalNetSale += $salesPerties->netSale;
                        $PHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, date('d-m-Y', strtotime($salesPerties->billingDate)));
                        $PHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, get_title('employees', ['Id' => $salesPerties->employeeId], 'Name'));
                        $PHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $salesPerties->distributorName);
                        $PHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $salesPerties->retailerName);
                        $PHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $salesPerties->location);
                        $PHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $salesPerties->contactPerson);
                        $PHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $salesPerties->mobileNumber);
                        $PHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $salesPerties->netSale);
                        $PHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $salesPerties->remark);
                        $rowCount++;
                    }
                    
                    $rowCount++;
                    $PHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, 'Total');
                    $PHPExcel->getActiveSheet()->setCellValue('H' . $rowCount, $totalNetSale);
                    $PHPExcel->getActiveSheet()->getStyle('A' . $rowCount)->getFont()->setBold(true);
                    $PHPExcel->getActiveSheet()->getStyle('H' . $rowCount)->getFont()->setBold(true);
                }
                
                // Set column widths
                $PHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
                $PHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(30);
                $PHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(30);
                $PHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(30);
                $PHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
                $PHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(25);
                $PHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
                $PHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(30);
                $PHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(25);

                $PHPExcel->getActiveSheet()->getStyle('A1:I' . $rowCount)->applyFromArray(
                    array(
                        'borders' => array(
                            'allborders' => array(
                                'style' => PHPExcel_Style_Border::BORDER_THIN,
                                'color' => array('rgb' => '000435')
                            )
                        )
                    )
                );
                
                $PHPExcel->getActiveSheet()->setTitle('SALES_REPORT');
                
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="' . str_replace(' ', '_', $Name) . '_SALES_REPORT.xlsx"');
                header('Cache-Control: max-age=0');
                PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007')->save('php://output');
            } else {
                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }
        }
        
        public function employeeTravels()
        {
            $employeeId = $this->session->userdata('EmployeeId');
            $userId = $this->session->userdata('UserId');
            $compId = $this->session->userdata('CompId');
            $compBranchId = $this->session->userdata('CompBranchId');
            
            $where = ['Id' => $employeeId, 'UserId' => $userId, 'CompId' => $compId, 'IsDeleted' => 1];
            $employeePermissionWhere = ['EmployeeId' => $employeeId, 'UserId' => $userId, 'CompId' => $compId];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $compBranchId;
                $employeePermissionWhere['CompanyBranchId'] = $compBranchId;                
            }
            
            $data['EmployeeInfo'] = $this->db->where($where)->get('employees')->row();
            $data['EmployeePermission'] = $this->db->where($employeePermissionWhere)->get('employee_permission')->row();
            $data['statesList'] = $this->db->select('id, state, code')->get('state_list')->result();
            $data['page'] = "employeeTravels";
            return $this->load->view('inventory_sep/Employee/Employee', $data);
        }
        
        public function saveEmployeeTravels()
        {
            if ($this->input->is_ajax_request()) {
                $arrayTravelsData = [];
                foreach ($this->input->post('travels') as $key => $row) {
                    if ($row['travelDate'] == '' || $row['placesVisited'] == '') {
                        $arrayTravelsData[] = $row;
                    }
                }

                if (!empty($arrayTravelsData)) {
                    echo json_encode(['status' => false, 'message' => 'Please enter travel required fields.']);
                    return;
                }                
                
                $this->form_validation->set_rules('date', 'Date', 'trim|required');
                $this->form_validation->set_rules('employeeName', 'Employee Name', 'trim|required');
                $this->form_validation->set_rules('designation', 'Designation', 'trim|required');
                $this->form_validation->set_rules('month', 'Month', 'trim|required');
                $this->form_validation->set_rules('stateId', 'State', 'trim|required');
                $this->form_validation->set_rules('grandTotal', 'Total', 'trim|numeric|required');
                if ($this->form_validation->run() == false) {
                    echo json_encode(['status' => false, 'message' => validation_errors()]);
                    return;
                } else {
                    $employeeId = $this->session->userdata('EmployeeId');
                    $userId = $this->session->userdata('UserId');
                    $compId = $this->session->userdata('CompId');
                    $compBranchId = $this->session->userdata('CompBranchId');
                    $created = date('d-m-Y h:i:s A');
                    $createdBy = get_title('employees', ['Id' => $employeeId], 'Name');
                    
                    $compProfileWhere = ['user_id' => $userId, 'comp_id' => $compId];
                    if ($this->session->userdata('BranchRole')) {
                        $compProfileWhere['CompanyBranchId'] = $compBranchId;
                    }
                    $financialYear = get_title('comp_profile', $compProfileWhere, 'financial_year');
                                        
                    $employeeTravels = [
                        'date' => $this->input->post('date'),
                        'employeeId' => $this->input->post('employeeId'),
                        'location' => $this->input->post('location'),
                        'band' => $this->input->post('band'),
                        'stateId' => $this->input->post('stateId'),
                        'month' => $this->input->post('month'),
                        'department' => $this->input->post('department'),
                        'reportingSenior' => $this->input->post('reportingSenior'),
                        'headQuarter' => $this->input->post('headQuarter'),
                        'remark' => $this->input->post('remark'),
                        'grandTotal' => $this->input->post('grandTotal'),
                        'userId' => $userId,
                        'compId' => $compId,
                        'compBranchId' => $compBranchId,
                        'financialYear' => $financialYear,
                        'created' => $created,
                        'createdBy' => $createdBy,
                    ];
                    
                    $result = $this->user_model->saveAry('employee_travels', $employeeTravels);
                    
                    if ($this->session->userdata('BranchRole')) {
                        $sql = "SELECT Id FROM employees WHERE UserId = '" . $userId . "' AND CompId = '" . $compId . "' AND CompanyBranchId = '" . $compBranchId . "' AND IsDeleted = 1 AND Status = 1 AND WarehouseSalesman IS NOT NULL AND JSON_VALID(WarehouseSalesman) AND JSON_CONTAINS(WarehouseSalesman, '[\"$employeeId\"]')";                        
                    } else {
                        $sql = "SELECT Id FROM employees WHERE UserId = '" . $userId . "' AND CompId = '" . $compId . "' AND IsDeleted = 1 AND Status = 1 AND WarehouseSalesman IS NOT NULL AND JSON_VALID(WarehouseSalesman) AND JSON_CONTAINS(WarehouseSalesman, '[\"$employeeId\"]')";
                    }                    
                    $query = $this->db->query($sql);                    
                    
                    foreach ($this->input->post('travels') as $key => $party) {
                        if (!empty($party['travelDate']) && !empty($party['placesVisited'])) {
                            $employeeTravelsData = [
                                'date' => $this->input->post('date'),
                                'employeeTravelId' => $result,
                                'travelDate' => $party['travelDate'],
                                'placesVisited' => $party['placesVisited'],
                                'dailyAllowanceClaimed' => $party['dailyAllowanceClaimed'],
                                'internetFax' => $party['internetFax'],
                                'xeroxPrint' => $party['xeroxPrint'],
                                'postCourier' => $party['postCourier'],
                                'total' => $party['total'],
                                'remark' => $party['remark'],
                                'userId' => $userId,
                                'compId' => $compId,
                                'compBranchId' => $compBranchId,
                                'seniorEmployeeId' => $query->row()->Id,
                                'employeeId' => $this->input->post('employeeId'),                                
                                'financialYear' => $financialYear,
                                'created' => $created,
                                'createdBy' => $createdBy
                            ];
                            $this->user_model->saveAry('employee_travels_data', $employeeTravelsData);
                        }
                    }
                    
                    if (!empty($result)) {
                        echo json_encode(['status' => true, 'message' => 'Record save successfully']);
                        return;
                    } else {
                        echo json_encode(['status' => false, 'message' => 'Unable to save record']);
                        return;
                    }                    
                }                
            }
        }        
        
        public function employeeTravelsReport()
        {
            $employeeId = $this->session->userdata('EmployeeId');
            $userId = $this->session->userdata('UserId');
            $compId = $this->session->userdata('CompId');
            $compBranchId = $this->session->userdata('CompBranchId');
            $financialYear = $this->session->userdata('financial_year');
            if ($this->input->get('from') && $this->input->get('to')) {
                $from = $this->input->get('from');
                $to = $this->input->get('to');
            } else {
                $from = date('Y-m-d');
                $to = date('Y-m-d');
            }
            
            $where = ['Id' => $employeeId, 'UserId' => $userId, 'CompId' => $compId, 'IsDeleted' => 1];
            $employeePermissionWhere = ['EmployeeId' => $employeeId, 'UserId' => $userId, 'CompId' => $compId];
            $employeeTravelsWhere = ['date >= ' => $from, 'date <= ' => $to, 'employeeId' => $employeeId, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $compBranchId;
                $employeePermissionWhere['CompanyBranchId'] = $compBranchId;                
                $employeeTravelsWhere['compBranchId'] = $compBranchId;                
            }
            
            $data['EmployeeInfo'] = $this->db->where($where)->get('employees')->row();
            $data['EmployeePermission'] = $this->db->where($employeePermissionWhere)->get('employee_permission')->row();
            $data['employeeTravels'] = $this->db->where($employeeTravelsWhere)->order_by('id', 'DESC')->get('employee_travels')->result();
            $data['page'] = "employeeTravelsReport";
            return $this->load->view('inventory_sep/Employee/Employee', $data);
        }
        
        public function employeeTravelsInfo($id)
        {
            if (!empty($id)) {
                $employeeId = $this->session->userdata('EmployeeId');
                $userId = $this->session->userdata('UserId');
                $compId = $this->session->userdata('CompId');
                $compBranchId = $this->session->userdata('CompBranchId');
                $financialYear = $this->session->userdata('financial_year');
                
                $employeeTravelsWhere = ['id' => $id, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear];
                $employeeTravelsDataWhere = ['employeeTravelId' => $id, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear];
                if ($this->session->userdata('BranchRole')) {
                    $employeeTravelsWhere['compBranchId'] = $compBranchId;
                    $employeeTravelsDataWhere['compBranchId'] = $compBranchId;
                }

                if ($this->session->userdata('RoleType') == "Salesman") {
                    $employeeTravelsWhere['employeeId'] = $employeeId;
                }
                
                $data['employeeTravelsInfo'] = $this->db->where($employeeTravelsWhere)->get('employee_travels')->row();
                $data['employeeTravelsData'] = $this->db->where($employeeTravelsDataWhere)->get('employee_travels_data')->result();
                return $this->load->view('inventory_sep/Employee/employeeTravelsInfo', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }
        }
        
        public function employeeTravelsEdit($id)
        {
            if (!empty($id)) {
                $employeeId = $this->session->userdata('EmployeeId');
                $userId = $this->session->userdata('UserId');
                $compId = $this->session->userdata('CompId');
                $compBranchId = $this->session->userdata('CompBranchId');
                $financialYear = $this->session->userdata('financial_year');
                
                $where = ['Id' => $employeeId, 'UserId' => $userId, 'CompId' => $compId, 'IsDeleted' => 1];
                $employeePermissionWhere = ['EmployeeId' => $employeeId, 'UserId' => $userId, 'CompId' => $compId];
                $employeeTravelsWhere = ['id' => $id, 'isDeleted' => 1, 'employeeId' => $employeeId, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear];
                if ($this->session->userdata('BranchRole')) {
                    $where['CompanyBranchId'] = $compBranchId;
                    $employeePermissionWhere['CompanyBranchId'] = $compBranchId;
                    $employeeTravelsWhere['compBranchId'] = $compBranchId;
                }

                $data['EmployeeInfo'] = $this->db->where($where)->get('employees')->row();
                $data['EmployeePermission'] = $this->db->where($employeePermissionWhere)->get('employee_permission')->row();
                $data['employeeTravelsInfo'] = $this->db->where($employeeTravelsWhere)->get('employee_travels')->row();
                $data['employeeTravelId'] = $id;
                $data['statesList'] = $this->db->select('id, state, code')->get('state_list')->result();
                $data['page'] = "employeeTravelsEdit";
                return $this->load->view('inventory_sep/Employee/Employee', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }
        }
        
        public function getEmployeeTravelsData($employeeTravelId)
        {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                if (!empty($employeeTravelId)) {
                    $employeeId = $this->session->userdata('EmployeeId');
                    $userId = $this->session->userdata('UserId');
                    $compId = $this->session->userdata('CompId');
                    $compBranchId = $this->session->userdata('CompBranchId');
                    $financialYear = $this->session->userdata('financial_year');
                    
                    $TravelsDataWhere = ['employeeTravelId' => $employeeTravelId, 'employeeId' => $employeeId, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear];
                    if ($this->session->userdata('BranchRole')) {
                        $TravelsDataWhere['compBranchId'] = $compBranchId;
                    }
                    
                    $employeeTravelsDataQuery = $this->db->where($TravelsDataWhere)->get('employee_travels_data');
                    if ($employeeTravelsDataQuery->num_rows()) {
                        echo json_encode(array('status' => true, "data" => $employeeTravelsDataQuery->result()));
                    } else {
                        echo json_encode(array('status' => false, "data" => "No Product found."));
                    }
                } else {
                    echo json_encode(array('status' => false, "data" => "id missing."));
                }
            }
        }
        
        public function deleteEmployeeTravelsData()
        {
            if ($this->input->is_ajax_request()) {
                if (!empty($this->input->post('rowId'))) {
                    $rowId = $this->input->post('rowId');
                    $employeeId = $this->session->userdata('EmployeeId');
                    $userId = $this->session->userdata('UserId');
                    $compId = $this->session->userdata('CompId');
                    $compBranchId = $this->session->userdata('CompBranchId');
                    
                    $where = ['employeeId' => $employeeId, 'userId' => $userId, 'compId' => $compId, 'isDeleted' => 1];
                    $travelDataWhere = ['employeeId' => $employeeId, 'id' => $rowId, 'userId' => $userId, 'compId' => $compId, 'isDeleted' => 1];
                    if ($this->session->userdata('BranchRole')) {
                        $where['compBranchId'] = $compBranchId;
                        $travelDataWhere['compBranchId'] = $compBranchId;
                    }
                    
                    $employeeTravelsData = $this->db->select('employeeTravelId, total')->where($travelDataWhere)->get('employee_travels_data')->row();
                    $where['id'] = $employeeTravelsData->employeeTravelId;
                    $currentGrandTotal = get_title('employee_travels', $where, 'grandTotal');
                    $newGrandTotal = $currentGrandTotal - $employeeTravelsData->total;
                    $this->db->where($where)->update('employee_travels', ['grandTotal' => $newGrandTotal]);
                    
                    $result = $this->db->where($travelDataWhere)->update('employee_travels_data', ['isDeleted' => 0]);
                    if ($result) {
                        echo json_encode(array('status' => true, "message" => "Record successfully deleted."));
                        return;
                    } else {
                        echo json_encode(array('status' => false, "message" => "Somethings went wrong, try again."));
                        return;
                    }
                } else {
                    echo json_encode(array('status' => false, "message" => "Id not found."));
                    return;
                }
            }
        }        
        
        public function updateEmployeeTravels()
        {
            if ($this->input->is_ajax_request()) {
                $arrayTravelData = [];
                foreach ($this->input->post('travels') as $key => $row) {
                    if ($row['travelDate'] == '' || $row['placesVisited']) {
                        $arrayTravelData[] = $row;
                    }
                }

                if (!empty($arrayTravelData)) {
                    echo json_encode(['status' => false, 'message' => 'Please enter travel required fields.']);
                    return;
                }
                
                $this->form_validation->set_rules('date', 'Date', 'trim|required');
                $this->form_validation->set_rules('employeeName', 'Employee Name', 'trim|required');
                $this->form_validation->set_rules('designation', 'Designation', 'trim|required');
                $this->form_validation->set_rules('month', 'Month', 'trim|required');
                $this->form_validation->set_rules('stateId', 'State', 'trim|required');
                $this->form_validation->set_rules('grandTotal', 'Total', 'trim|numeric|required');
                if ($this->form_validation->run() == false) {
                    echo json_encode(['status' => false, 'message' => validation_errors()]);
                    return;
                } else {
                    $employeeTravelId = $this->input->post('employeeTravelId');
                    $employeeId = $this->session->userdata('EmployeeId');
                    $userId = $this->session->userdata('UserId');
                    $compId = $this->session->userdata('CompId');
                    $compBranchId = $this->session->userdata('CompBranchId');
                    $updated = date('d-m-Y h:i:s A');
                    $updatedBy = get_title('employees', ['Id' => $employeeId], 'Name');
                    
                    $updateWhere = ['id' => $employeeTravelId, 'employeeId' => $employeeId, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId];
                    $updateTravelDataWhere = ['employeeTravelId' => $employeeTravelId, 'employeeId' => $employeeId, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId];
                    $compProfileWhere = ['user_id' => $userId, 'comp_id' => $compId];
                    if ($this->session->userdata('BranchRole')) {
                        $compProfileWhere['CompanyBranchId'] = $compBranchId;
                        $updateWhere['compBranchId'] = $compBranchId;
                        $updateTravelDataWhere['compBranchId'] = $compBranchId;
                    }
                    $financialYear = get_title('comp_profile', $compProfileWhere, 'financial_year');
                                        
                    $employeeTravels = [
                        'date' => $this->input->post('date'),
                        'location' => $this->input->post('location'),
                        'band' => $this->input->post('band'),
                        'stateId' => $this->input->post('stateId'),
                        'month' => $this->input->post('month'),
                        'department' => $this->input->post('department'),
                        'reportingSenior' => $this->input->post('reportingSenior'),
                        'headQuarter' => $this->input->post('headQuarter'),
                        'remark' => $this->input->post('remark'),
                        'grandTotal' => $this->input->post('grandTotal'),
                        'updated' => $updated,
                        'updatedBy' => $updatedBy,
                    ];                    
                    
                    $result = $this->user_model->updateAry('employee_travels', $employeeTravels, $updateWhere);                    
                    
                    foreach ($this->input->post('travels') as $key => $travelData) {
                        if (!empty($newData['travelDate']) && !empty($travelData['placesVisited'])) {
                            $employeeTravelsData = [
                                'date' => $this->input->post('date'),
                                'travelDate' => $travelData['travelDate'],
                                'placesVisited' => $travelData['placesVisited'],
                                'dailyAllowanceClaimed' => $travelData['dailyAllowanceClaimed'],
                                'internetFax' => $travelData['internetFax'],
                                'xeroxPrint' => $travelData['xeroxPrint'],
                                'postCourier' => $travelData['postCourier'],
                                'total' => $travelData['total'],
                                'remark' => $travelData['remark'],
                                'updated' => $updated,
                                'updatedBy' => $updatedBy
                            ];
                            
                            $updateTravelDataWhere['id'] = $travelData['rowId'];
                            $this->user_model->updateAry('employee_travels_data', $employeeTravelsData, $updateTravelDataWhere);                            
                        }
                    }
                    
                    if ($this->session->userdata('BranchRole')) {
                        $sql = "SELECT Id FROM employees WHERE UserId = '" . $userId . "' AND CompId = '" . $compId . "' AND CompanyBranchId = '" . $compBranchId . "' AND IsDeleted = 1 AND Status = 1 AND WarehouseSalesman IS NOT NULL AND JSON_VALID(WarehouseSalesman) AND JSON_CONTAINS(WarehouseSalesman, '[\"$employeeId\"]')";                        
                    } else {
                        $sql = "SELECT Id FROM employees WHERE UserId = '" . $userId . "' AND CompId = '" . $compId . "' AND IsDeleted = 1 AND Status = 1 AND WarehouseSalesman IS NOT NULL AND JSON_VALID(WarehouseSalesman) AND JSON_CONTAINS(WarehouseSalesman, '[\"$employeeId\"]')";
                    }                    
                    $query = $this->db->query($sql);

                    foreach ($this->input->post('newTravels') as $key => $newTravelData) {
                        if (!empty($newTravelData['travelDate']) && !empty($newTravelData['placesVisited'])) {
                            $employeeNewTravelsData = [
                                'date' => $this->input->post('date'),
                                'employeeTravelId' => $employeeTravelId,
                                'travelDate' => $newTravelData['travelDate'],
                                'placesVisited' => $newTravelData['placesVisited'],
                                'dailyAllowanceClaimed' => $newTravelData['dailyAllowanceClaimed'],
                                'internetFax' => $newTravelData['internetFax'],
                                'xeroxPrint' => $newTravelData['xeroxPrint'],
                                'postCourier' => $newTravelData['postCourier'],
                                'total' => $newTravelData['total'],
                                'remark' => $newTravelData['remark'],
                                'userId' => $userId,
                                'compId' => $compId,
                                'compBranchId' => $compBranchId,
                                'seniorEmployeeId' => $query->row()->Id,
                                'employeeId' => $employeeId,
                                'financialYear' => $financialYear,
                                'created' => $updated,
                                'createdBy' => $updatedBy
                            ];
                            $this->user_model->saveAry('employee_travels_data', $employeeNewTravelsData);
                        }
                    }
                    
                    if (!empty($result)) {
                        echo json_encode(['status' => true, 'message' => 'Record update successfully']);
                        return;
                    } else {
                        echo json_encode(['status' => false, 'message' => 'Unable to save record']);
                        return;
                    }                    
                }                
            }
        }
        
        public function employeeTravelsDelete($id)
        {
            if (!empty($id)) {
                $where = ['id' => $id, 'isDeleted' => 1];
                if ($this->db->select('id')->where($where)->get('employee_travels')->num_rows()) {
                    if ($this->user_model->updateAry('employee_travels', ['isDeleted' => 0], $where)) {
                        $this->user_model->updateAry('employee_travels_data', ['isDeleted' => 0], ['employeeTravelId' => $id, 'isDeleted' => 1]);
                        $this->session->set_flashdata(['msg' => 'Record delete successfully.', 'class' => 'success']);
                        return redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to delete record.', 'class' => 'danger']);
                        return redirect($_SERVER['HTTP_REFERER']);
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                    return redirect($_SERVER['HTTP_REFERER']);
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Id is missing.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }
        }
        
        public function employeeTravelsReportDownload($id)
        {
            ini_set('memory_limit', '-1');
            set_time_limit(500);
            
            $employeeId = $this->session->userdata('EmployeeId');            
            $userId = $this->session->userdata('UserId');
            $compId = $this->session->userdata('CompId');
            $compBranchId = $this->session->userdata('CompBranchId');
            $financialYear = $this->session->userdata('financial_year');
            
            $where = ['id' => $id, 'employeeId' => $employeeId, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear, 'isDeleted' => 1];
            $travelsDataWhere = ['employeeTravelId' => $id, 'employeeId' => $employeeId, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear, 'isDeleted' => 1];
            $compProfileWhere = ['user_id' => $userId, 'comp_id' => $compId];
            if ($this->session->userdata('BranchRole')) {
                $Name = get_title('companybranch', ['Id' => $compBranchId], 'BranchName');
                $where['compBranchId'] = $compBranchId;
                $travelsDataWhere['compBranchId'] = $compBranchId;
                $compProfileWhere['CompanyBranchId'] = $compBranchId;
            } else {
                $Name = get_title('inventory_billing', ['id' => $compId], 'company_name');
            }
            
            $logoPath = 'uploads/complogo/' . get_title('comp_profile', $compProfileWhere, 'logo');
            if (!file_exists($logoPath)) {
                $logoPath = 'assets/images/avatar.png';
            }
            
            $TravelsReportQuery = $this->db->where($where)->get('employee_travels');
            if ($TravelsReportQuery->num_rows()) {
                $travelData = $TravelsReportQuery->row();
                
                $PHPExcel = new PHPExcel();
                                
                // Define styles
                $topHeaderStyleArray = [
                    'font' => [
                        'size' => 11,
                        'color' => ['rgb' => '000000'],
                    ],
                    'borders' => [
                        'allborders' => [
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ];
                $headerStyleArray = [
                    'font' => [
                        'bold' => true,
                        'size' => 11,
                        'color' => ['rgb' => '000000'],
                    ],
                    'borders' => [
                        'allborders' => [
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ];

                $dataStyleArray = [
                    'font' => [
                        'size' => 10,
                    ],
                    'borders' => [
                        'allborders' => [
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ];                
                
                $PHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
                                
                // Add the logo image
                $PHPExcelDrawing = new PHPExcel_Worksheet_Drawing();
                $PHPExcelDrawing->setName('Logo');
                $PHPExcelDrawing->setDescription('Logo');
                $PHPExcelDrawing->setPath($logoPath);
                $PHPExcelDrawing->setHeight(60);
                $PHPExcelDrawing->setCoordinates('H1');
                $PHPExcelDrawing->setWorksheet($PHPExcel->getActiveSheet());                
                $PHPExcel->getActiveSheet()->getStyle('H1:H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                
                $PHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'Sale:')
                    ->mergeCells('B1:C1')
                    ->setCellValue('A2', 'Total Expenses:')
                    ->mergeCells('B2:C2')
                    ->setCellValue('D1', 'Format effective date: ' . date('jS F, Y'))
                    ->mergeCells('D1:G2')
                    ->setCellValue('A3', 'LOCAL TRAVEL')                    
                    ->mergeCells('A3:H3')
                    ->setCellValue('A4', 'Employee Name')
                    ->setCellValue('B4', get_title('employees', ['Id' => $travelData->employeeId], 'Name'))
                    ->mergeCells('B4:C4')
                    ->setCellValue('A5', 'Designation')
                    ->setCellValue('B5', get_title('position', ['Id' => get_title('employees', ['Id' => $travelData->employeeId], 'RoleId')], 'PostName'))
                    ->mergeCells('B5:C5')
                    ->setCellValue('A6', 'Band')
                    ->setCellValue('B6', $travelData->band)
                    ->mergeCells('B6:C6')    
                    ->setCellValue('A7', 'Location / Place of posting')
                    ->setCellValue('B7', $travelData->location . " - " . get_title('state_list', ['id' => $travelData->stateId], 'state'))
                    ->mergeCells('B7:C7')
                    ->setCellValue('D4', 'Month')
                    ->setCellValue('E4', $travelData->month)
                    ->mergeCells('E4:G4')
                    ->setCellValue('D5', 'Department')
                    ->setCellValue('E5', $travelData->department)
                    ->mergeCells('E5:G5')
                    ->setCellValue('D6', 'Reporting Senior')
                    ->setCellValue('E6', $travelData->reportingSenior)
                    ->mergeCells('E6:G6')
                    ->setCellValue('D7', 'Head Quarter')
                    ->setCellValue('E7', $travelData->headQuarter)
                    ->mergeCells('E7:G7');                                                

                // Set column titles
                $PHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A8', 'DATE (DD/MM/YYYY)')
                    ->mergeCells('A8:A9')
                    ->setCellValue('B8', 'PLACES VISITED')
                    ->mergeCells('B8:B9')
                    ->setCellValue('C8', 'DAILY ALLOWANCE CLAIMED')
                    ->mergeCells('C8:C9')
                    ->setCellValue('D8', 'OTHER MISC. EXPENSES')
                    ->mergeCells('D8:F8')
                    ->setCellValue('D9', 'INTERNET/FAX')
                    ->setCellValue('E9', 'XEROX/PRINT')
                    ->setCellValue('F9', 'POST/COURIER')
                    ->setCellValue('G8', 'TOTAL')
                    ->mergeCells('G8:G9')
                    ->setCellValue('H8', 'REMARK')
                    ->mergeCells('H8:H9');
                
                
                $TravelDataReportQuery = $this->db->where($travelsDataWhere)->get('employee_travels_data');
                if ($TravelDataReportQuery->num_rows()) {
                    $rowCount = 10;
                    $totalDailyAllowanceClaimed = 0;
                    $totalInternetFax = 0;
                    $totalXeroxPrint = 0;
                    $totalPostCourier = 0;
                    $totalAmount = 0;
                    foreach ($TravelDataReportQuery->result() as $key => $travelsData) {
                        $totalDailyAllowanceClaimed += $travelsData->dailyAllowanceClaimed;
                        $totalInternetFax += $travelsData->internetFax;
                        $totalXeroxPrint += $travelsData->xeroxPrint;
                        $totalPostCourier += $travelsData->postCourier;
                        $totalAmount += $travelsData->total;
                        
                        $PHPExcel->getActiveSheet()->getStyle('A' . $rowCount . ':H' . $rowCount)->applyFromArray($dataStyleArray);
                        
                        $PHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, date('d/m/Y', strtotime($travelsData->travelDate)));
                        $PHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $travelsData->placesVisited);
                        $PHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $travelsData->dailyAllowanceClaimed);
                        $PHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $travelsData->internetFax);
                        $PHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $travelsData->xeroxPrint);
                        $PHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $travelsData->postCourier);
                        $PHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $travelsData->total);
                        $PHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $travelsData->remark);
                        $rowCount++;
                    }
                    $PHPExcel->getActiveSheet()->getStyle('A' . $rowCount . ':H' . $rowCount)->applyFromArray($dataStyleArray);
                    
                    $rowCount++;
                    $PHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, 'Total');
                    $PHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $rowCount . ':B' . $rowCount);
                    $PHPExcel->getActiveSheet()->getStyle('A' . $rowCount . ':B' . $rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    
                    $PHPExcel->getActiveSheet()->setCellValue('C' . $rowCount, $totalDailyAllowanceClaimed);
                    $PHPExcel->getActiveSheet()->setCellValue('D' . $rowCount, $totalInternetFax);
                    $PHPExcel->getActiveSheet()->setCellValue('E' . $rowCount, $totalXeroxPrint);
                    $PHPExcel->getActiveSheet()->setCellValue('F' . $rowCount, $totalPostCourier);
                    $PHPExcel->getActiveSheet()->setCellValue('G' . $rowCount, $totalAmount);
                    
                    $PHPExcel->getActiveSheet()->getStyle('A' . $rowCount)->getFont()->setBold(true);
                    $PHPExcel->getActiveSheet()->getStyle('G' . $rowCount)->getFont()->setBold(true);
                    $PHPExcel->getActiveSheet()->getStyle('A' . $rowCount . ':H' . $rowCount)->applyFromArray($headerStyleArray);
                    
                    $rowCount++;
                    $PHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $rowCount . ':H' . $rowCount);
                    $PHPExcel->getActiveSheet()->getStyle('A' . $rowCount . ':H' . $rowCount)->applyFromArray($headerStyleArray);
                    
                    $rowCount++;
                    $PHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, 'Sd/-');
                    $PHPExcel->getActiveSheet()->setCellValue('C' . $rowCount, 'Sd/-');
                    $PHPExcel->getActiveSheet()->setCellValue('F' . $rowCount, 'Sd/-');
                    $PHPExcel->getActiveSheet()->getStyle('A' . $rowCount)->getFont()->setBold(true);
                    $PHPExcel->getActiveSheet()->getStyle('C' . $rowCount)->getFont()->setBold(true);
                    $PHPExcel->getActiveSheet()->getStyle('F' . $rowCount)->getFont()->setBold(true);
                    $PHPExcel->getActiveSheet()->getStyle('A' . $rowCount . ':H' . $rowCount)->applyFromArray($topHeaderStyleArray);
                    
                    $rowCount++;
                    $previousRowNumber = ($rowCount - 1);
                    $PHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, 'Employee');
                    $PHPExcel->getActiveSheet()->setCellValue('C' . $rowCount, 'Reporting Senior');
                    $PHPExcel->getActiveSheet()->setCellValue('F' . $rowCount, 'Director');
                    $PHPExcel->getActiveSheet()->getStyle('A' . $rowCount)->getFont()->setBold(true);
                    $PHPExcel->getActiveSheet()->getStyle('C' . $rowCount)->getFont()->setBold(true);
                    $PHPExcel->getActiveSheet()->getStyle('F' . $rowCount)->getFont()->setBold(true);
                    $PHPExcel->getActiveSheet()->getStyle('A' . $rowCount . ':H' . $rowCount)->applyFromArray($topHeaderStyleArray);
                    $PHPExcel->setActiveSheetIndex(0)->mergeCells('B' . $previousRowNumber . ':B' . $rowCount);
                    $PHPExcel->setActiveSheetIndex(0)->mergeCells('D' . $previousRowNumber . ':E' . $rowCount);
                    $PHPExcel->setActiveSheetIndex(0)->mergeCells('G' . $previousRowNumber . ':H' . $rowCount);
                }
                
                $PHPExcel->getActiveSheet()->getRowDimension('9')->setRowHeight(35);
                $PHPExcel->getActiveSheet()->getStyle('A8:H9')->getAlignment()->setWrapText(true);
                
                // Set column widths
                $PHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
                $PHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(50);
                $PHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(16);
                $PHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(16);
                $PHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
                $PHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
                $PHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
                $PHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
                
                // Apply styles
                $PHPExcel->getActiveSheet()->getStyle('A1:H1')->applyFromArray($headerStyleArray);
                $PHPExcel->getActiveSheet()->getStyle('D1:H2')->applyFromArray($headerStyleArray);
                $PHPExcel->getActiveSheet()->getStyle('D1:H2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);                
                $PHPExcel->getActiveSheet()->getStyle('A2:H2')->applyFromArray($headerStyleArray);
                $PHPExcel->getActiveSheet()->getStyle('A3:H3')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14,
                        'color' => ['rgb' => '000000'],
                    ],
                    'borders' => [
                        'allborders' => [
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);
                $PHPExcel->getActiveSheet()->getStyle('A3:H3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $PHPExcel->getActiveSheet()->getStyle('A3:H3')->getFill()->applyFromArray([
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'startcolor' => ['rgb' => 'FFFF00'],
                ]);
                $PHPExcel->getActiveSheet()->getStyle('D1:H2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $PHPExcel->getActiveSheet()->getStyle('A8:H9')->applyFromArray([
                    'alignment' => [
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                    ]
                ]);
                $PHPExcel->getActiveSheet()->getStyle('A4:H7')->applyFromArray($topHeaderStyleArray);
                $PHPExcel->getActiveSheet()->getStyle('A8:H9')->applyFromArray($headerStyleArray);
                
                // Set background colors
                $PHPExcel->getActiveSheet()->getStyle('A1:A9')->getFill()->applyFromArray([
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'startcolor' => ['rgb' => 'FFFF00'],
                ]);
                $PHPExcel->getActiveSheet()->getStyle('D3:D9')->getFill()->applyFromArray([
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'startcolor' => ['rgb' => 'FFFF00'],
                ]);
                $PHPExcel->getActiveSheet()->getStyle('A8:H9')->getFill()->applyFromArray([
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'startcolor' => ['rgb' => 'FFFF00'],
                ]);

                $PHPExcel->getActiveSheet()->setTitle('LOCAL_TRAVEL');
                
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="' . str_replace(' ', '_', $Name) . '_LOCAL_TRAVEL_REPORT.xlsx"');
                header('Cache-Control: max-age=0');
                PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007')->save('php://output');
            } else {
                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }
        }

        public function seniorEmployeeTravels()
        {
            $employeeId = $this->session->userdata('EmployeeId');
            $userId = $this->session->userdata('UserId');
            $compId = $this->session->userdata('CompId');
            $compBranchId = $this->session->userdata('CompBranchId');
            
            $where = ['Id' => $employeeId, 'UserId' => $userId, 'CompId' => $compId, 'IsDeleted' => 1];
            $employeePermissionWhere = ['EmployeeId' => $employeeId, 'UserId' => $userId, 'CompId' => $compId];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $compBranchId;
                $employeePermissionWhere['CompanyBranchId'] = $compBranchId;                
            }
            
            $data['EmployeeInfo'] = $this->db->where($where)->get('employees')->row();
            $data['EmployeePermission'] = $this->db->where($employeePermissionWhere)->get('employee_permission')->row();
            $data['statesList'] = $this->db->select('id, state, code')->get('state_list')->result();
            $salesmanEmployeeIds = json_decode(get_title('employees', ['Id' => $employeeId, 'IsDeleted' => 1], 'WarehouseSalesman'), true) ?: [];
            $RoleId = get_title('position', ['PostName' => 'Salesman'], 'Id');
            $data['employees'] = $this->db->select('Id, Name')->where('RoleId', $RoleId)->where_in('Id', $salesmanEmployeeIds)->get('employees')->result();               
            $data['page'] = "seniorEmployeeTravels";
            return $this->load->view('inventory_sep/Employee/Employee', $data);
        }
        
        public function saveSeniorEmployeeTravels()
        {
            if ($this->input->is_ajax_request()) {
                $arrayTravelsData = [];
                foreach ($this->input->post('travels') as $key => $row) {
                    if ($row['employeeId'] == '' || $row['travelDate'] == '' || $row['placesVisited'] == '') {
                        $arrayTravelsData[] = $row;
                    }
                }

                if (!empty($arrayTravelsData)) {
                    echo json_encode(['status' => false, 'message' => 'Please enter travel required fields.']);
                    return;
                }                
                
                $this->form_validation->set_rules('date', 'Date', 'trim|required');
                $this->form_validation->set_rules('month', 'Month', 'trim|required');
                $this->form_validation->set_rules('stateId', 'State', 'trim|required');
                $this->form_validation->set_rules('grandTotal', 'Total', 'trim|numeric|required');
                if ($this->form_validation->run() == false) {
                    echo json_encode(['status' => false, 'message' => validation_errors()]);
                    return;
                } else {
                    $employeeId = $this->session->userdata('EmployeeId');
                    $userId = $this->session->userdata('UserId');
                    $compId = $this->session->userdata('CompId');
                    $compBranchId = $this->session->userdata('CompBranchId');
                    $created = date('d-m-Y h:i:s A');
                    $createdBy = get_title('employees', ['Id' => $employeeId], 'Name');
                    
                    $compProfileWhere = ['user_id' => $userId, 'comp_id' => $compId];
                    if ($this->session->userdata('BranchRole')) {
                        $compProfileWhere['CompanyBranchId'] = $compBranchId;
                    }
                    $financialYear = get_title('comp_profile', $compProfileWhere, 'financial_year');                                                            
                    
                    $employeeTravels = [];
                    foreach ($this->input->post('travels') as $key => $travelData) {
                        if (!empty($travelData['employeeId'])) {
                            $SalesmanId = $travelData['employeeId'];
                            if (isset($employeeTravels[$SalesmanId])) {
                                $employeeTravels[$SalesmanId]['grandTotal'] += $travelData['total'];
                            } else {
                                $employeeTravels[$SalesmanId] = [
                                    'date' => $this->input->post('date'),
                                    'employeeId' => $SalesmanId,
                                    'location' => $this->input->post('location'),
                                    'band' => $this->input->post('band'),
                                    'stateId' => $this->input->post('stateId'),
                                    'month' => $this->input->post('month'),
                                    'department' => $this->input->post('department'),
                                    'reportingSenior' => $this->input->post('reportingSenior'),
                                    'headQuarter' => $this->input->post('headQuarter'),
                                    'remark' => $this->input->post('remark'),
                                    'grandTotal' => $travelData['total'],
                                    'userId' => $userId,
                                    'compId' => $compId,
                                    'compBranchId' => $compBranchId,
                                    'financialYear' => $financialYear,
                                    'created' => $created,
                                    'createdBy' => $createdBy,                                    
                                ];
                            }
                        }
                    }
                    
                    $employeeTravelsIds = [];
                    foreach ($employeeTravels as $SalesmanId => $salesmanData) {
                        $result = $this->user_model->saveAry('employee_travels', $salesmanData);
                        $employeeTravelsIds[$SalesmanId] = $result;
                    }
                    
                    foreach ($this->input->post('travels') as $key => $travels) {
                        if (!empty($travels['travelDate']) && !empty($travels['placesVisited'])) {
                            $employeeTravelsData = [
                                'date' => $this->input->post('date'),
                                'employeeTravelId' => $employeeTravelsIds[$travels['employeeId']],
                                'travelDate' => $travels['travelDate'],
                                'placesVisited' => $travels['placesVisited'],
                                'dailyAllowanceClaimed' => $travels['dailyAllowanceClaimed'],
                                'internetFax' => $travels['internetFax'],
                                'xeroxPrint' => $travels['xeroxPrint'],
                                'postCourier' => $travels['postCourier'],
                                'total' => $travels['total'],
                                'remark' => $travels['remark'],
                                'userId' => $userId,
                                'compId' => $compId,
                                'compBranchId' => $compBranchId,
                                'seniorEmployeeId' => $employeeId,
                                'employeeId' => $travels['employeeId'],
                                'financialYear' => $financialYear,
                                'created' => $created,
                                'createdBy' => $createdBy
                            ];
                            $this->user_model->saveAry('employee_travels_data', $employeeTravelsData);
                        }
                    }
                    
                    if (!empty($result)) {
                        echo json_encode(['status' => true, 'message' => 'Record save successfully']);
                        return;
                    } else {
                        echo json_encode(['status' => false, 'message' => 'Unable to save record']);
                        return;
                    }                    
                }                
            }
        }
        
        public function seniorEmployeeTravelsReport()
        {
            $employeeId = $this->session->userdata('EmployeeId');
            $userId = $this->session->userdata('UserId');
            $compId = $this->session->userdata('CompId');
            $compBranchId = $this->session->userdata('CompBranchId');
            $financialYear = $this->session->userdata('financial_year');
            if ($this->input->get('from') && $this->input->get('to')) {
                $from = $this->input->get('from');
                $to = $this->input->get('to');
            } else {
                $from = date('Y-m-d');
                $to = date('Y-m-d');
            }
            
            $where = ['Id' => $employeeId, 'UserId' => $userId, 'CompId' => $compId, 'IsDeleted' => 1];
            $employeePermissionWhere = ['EmployeeId' => $employeeId, 'UserId' => $userId, 'CompId' => $compId];
            $employeeTravelsWhere = ['date >= ' => $from, 'date <= ' => $to, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $compBranchId;
                $employeePermissionWhere['CompanyBranchId'] = $compBranchId;                
                $employeeTravelsWhere['compBranchId'] = $compBranchId;                
            }
            
            if ($this->input->get('employeeId') != "" && $this->input->get('employeeId') != 'All') {
                $salesmanIds = $this->input->get('employeeId');
            } else {
                $salesmanIds = json_decode(get_title('employees', ['Id' => $employeeId, 'IsDeleted' => 1], 'WarehouseSalesman'), true) ?: [];
            }
            
            $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
            $data['EmployeeInfo'] = $this->db->where($where)->get('employees')->row();
            $data['EmployeePermission'] = $this->db->where($employeePermissionWhere)->get('employee_permission')->row();
            $employeeSalesmanIds = json_decode(get_title('employees', ['Id' => $employeeId, 'IsDeleted' => 1], 'WarehouseSalesman'), true) ?: [];
            $RoleId = get_title('position', ['PostName' => 'Salesman'], 'Id');
            $data['salesmans'] = $this->db->select('Id, Name')->where('RoleId', $RoleId)->where_in('Id', $employeeSalesmanIds)->get('employees')->result();
            $data['employeeTravels'] = $this->db->select('id, date, month, SUM(grandTotal) AS grandTotal')->where($employeeTravelsWhere)->where_in('employeeId', $salesmanIds)->group_by('date')->order_by('id', 'DESC')->get('employee_travels')->result();
            $data['page'] = "seniorEmployeeTravelsReport";
            return $this->load->view('inventory_sep/Employee/Employee', $data);
        }         
        
        public function seniorEmployeeTravelsInfo()
        {
            $employeeId = $this->session->userdata('EmployeeId');
            $userId = $this->session->userdata('UserId');
            $compId = $this->session->userdata('CompId');
            $compBranchId = $this->session->userdata('CompBranchId');
            $financialYear = $this->session->userdata('financial_year');
            if ($this->input->get('from') && $this->input->get('to')) {
                $from = $this->input->get('from');
                $to = $this->input->get('to');
            } else {
                $from = date('Y-m-d');
                $to = date('Y-m-d');
            }
            $employeeTravelsWhere = ['date >= ' => $from, 'date <= ' => $to, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear];
            $employeeTravelsDataWhere = ['date >= ' => $from, 'date <= ' => $to, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear];
            if ($this->input->get('employeeId') != "" && $this->input->get('employeeId') != 'All') {
                $salesmanIds = $this->input->get('employeeId');
            } else {
                $salesmanIds = json_decode(get_title('employees', ['Id' => $employeeId, 'IsDeleted' => 1], 'WarehouseSalesman'), true) ?: [];
            }
            if ($this->session->userdata('BranchRole')) {
                $employeeTravelsWhere['compBranchId'] = $compBranchId;
                $employeeTravelsDataWhere['compBranchId'] = $compBranchId;
            }
            
            $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");            
            $data['employeeTravelsInfo'] = $this->db->where($employeeTravelsWhere)->where_in('employeeId', $salesmanIds)->group_by('date')->order_by('id', 'DESC')->get('employee_travels')->row();
            $data['employeeTravelsData'] = $this->db->where($employeeTravelsDataWhere)->where_in('employeeId', $salesmanIds)->get('employee_travels_data')->result();
            return $this->load->view('inventory_sep/Employee/seniorEmployeeTravelsInfo', $data);
        }        
        
        public function seniorEmployeeTravelsEdit()
        {
            $employeeId = $this->session->userdata('EmployeeId');
            $userId = $this->session->userdata('UserId');
            $compId = $this->session->userdata('CompId');
            $compBranchId = $this->session->userdata('CompBranchId');
            $financialYear = $this->session->userdata('financial_year');
            if ($this->input->get('from') && $this->input->get('to')) {
                $from = $this->input->get('from');
                $to = $this->input->get('to');
            }
            $where = ['Id' => $employeeId, 'UserId' => $userId, 'CompId' => $compId, 'IsDeleted' => 1];
            $employeePermissionWhere = ['EmployeeId' => $employeeId, 'UserId' => $userId, 'CompId' => $compId];
            $employeeTravelsWhere = ['date >= ' => $from, 'date <= ' => $to, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $compBranchId;
                $employeePermissionWhere['CompanyBranchId'] = $compBranchId;
                $employeeTravelsWhere['compBranchId'] = $compBranchId;
            }
            if ($this->input->get('department') != '') {
                $employeeTravelsWhere['department'] = $this->input->get('department');
            }
            if ($this->input->get('band') != '') {
                $employeeTravelsWhere['band'] = $this->input->get('band');
            }
            if ($this->input->get('stateId') != '') {
                $employeeTravelsWhere['stateId'] = $this->input->get('stateId');
            }
            if ($this->input->get('employeeId') != 'All') {
                $salesmanIds = $this->input->get('employeeId');
            } else {
                $salesmanIds = json_decode(get_title('employees', ['Id' => $employeeId, 'IsDeleted' => 1], 'WarehouseSalesman'), true) ?: [];
            }            

            $data['EmployeeInfo'] = $this->db->where($where)->get('employees')->row();
            $data['EmployeePermission'] = $this->db->where($employeePermissionWhere)->get('employee_permission')->row();
            $employeeTravelsQuery = $this->db->where($employeeTravelsWhere)->where_in('employeeId', $salesmanIds)->get('employee_travels');
            if ($employeeTravelsQuery->num_rows()) {
                $data['employeeTravelsInfo'] = $employeeTravelsQuery->row();
                $data['from'] = $from;
                $data['to'] = $to;
                $data['employeeId'] = $this->input->get('employeeId');
                $salesmanEmployeeIds = json_decode(get_title('employees', ['Id' => $employeeId, 'IsDeleted' => 1], 'WarehouseSalesman'), true) ?: [];
                $RoleId = get_title('position', ['PostName' => 'Salesman'], 'Id');
                $data['employees'] = $this->db->select('Id, Name')->where('RoleId', $RoleId)->where_in('Id', $salesmanEmployeeIds)->get('employees')->result();
                $data['statesList'] = $this->db->select('id, state, code')->get('state_list')->result();
                $data['page'] = "seniorEmployeeTravelsEdit";
                return $this->load->view('inventory_sep/Employee/Employee', $data);
            } else {
                return redirect($_SERVER['HTTP_REFERER']);
            }
        }
        
        public function getSeniorEmployeeTravelsData()
        {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $employeeId = $this->session->userdata('EmployeeId');
                $userId = $this->session->userdata('UserId');
                $compId = $this->session->userdata('CompId');
                $compBranchId = $this->session->userdata('CompBranchId');
                $financialYear = $this->session->userdata('financial_year');
                $from = $this->input->get('from');
                $to = $this->input->get('to');
                
                $travelDataWhere = ['date >= ' => $from, 'date <= ' => $to, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear];
                if ($this->session->userdata('BranchRole')) {
                    $travelDataWhere['compBranchId'] = $compBranchId;
                }
                if ($this->input->get('employeeId') != 'All') {
                    $salesmanIds = $this->input->get('employeeId');
                } else {
                    $salesmanIds = json_decode(get_title('employees', ['Id' => $employeeId, 'IsDeleted' => 1], 'WarehouseSalesman'), true) ?: [];
                }

                $employeeTravelsDataQuery = $this->db->where($travelDataWhere)->where_in('employeeId', $salesmanIds)->get('employee_travels_data');
                if ($employeeTravelsDataQuery->num_rows()) {
                    echo json_encode(array('status' => true, "data" => $employeeTravelsDataQuery->result()));
                } else {
                    echo json_encode(array('status' => false, "data" => "No Product found."));
                }
            }
        }
        
        public function deleteSeniorEmployeeTravelsData()
        {
            if ($this->input->is_ajax_request()) {
                if (!empty($this->input->post('rowId'))) {
                    $rowId = $this->input->post('rowId');
                    $employeeId = $this->session->userdata('EmployeeId');
                    $userId = $this->session->userdata('UserId');
                    $compId = $this->session->userdata('CompId');
                    $compBranchId = $this->session->userdata('CompBranchId');
                    
                    $where = ['userId' => $userId, 'compId' => $compId, 'isDeleted' => 1];
                    $travelDataWhere = ['id' => $rowId, 'userId' => $userId, 'compId' => $compId, 'isDeleted' => 1];
                    if ($this->session->userdata('BranchRole')) {
                        $where['compBranchId'] = $compBranchId;
                        $travelDataWhere['compBranchId'] = $compBranchId;
                    }
                    
                    $salesmanIds = json_decode(get_title('employees', ['Id' => $employeeId, 'IsDeleted' => 1], 'WarehouseSalesman'), true) ?: [];
                    $employeeTravelsData = $this->db->select('employeeTravelId, total')->where($travelDataWhere)->where_in('employeeId', $salesmanIds)->get('employee_travels_data')->row();
                    $where['id'] = $employeeTravelsData->total;
                    $currentGrandTotal = $this->db->select('grandTotal')->where($where)->where_in('employeeId', $salesmanIds)->get('employee_travels')->row('grandTotal');
                    $newGrandTotal = $currentGrandTotal - $employeeTravelsData->total;
                    $this->db->where($where)->where_in('employeeId', $salesmanIds)->update('employee_travels', ['grandTotal' => $newGrandTotal]);
                    
                    $result = $this->db->where($travelDataWhere)->where_in('employeeId', $salesmanIds)->update('employee_travels_data', ['isDeleted' => 0]);
                    if ($result) {
                        echo json_encode(array('status' => true, "message" => "Record successfully deleted."));
                        return;
                    } else {
                        echo json_encode(array('status' => false, "message" => "Somethings went wrong, try again."));
                        return;
                    }
                } else {
                    echo json_encode(array('status' => false, "message" => "Id not found."));
                    return;
                }
            }
        }
        
        public function updateSeniorEmployeeTravels()
        {
            if ($this->input->is_ajax_request()) {
                $arrayTravelData = [];
                foreach ($this->input->post('travels') as $key => $row) {
                    if ($row['travelDate'] == '' || $row['placesVisited']) {
                        $arrayTravelData[] = $row;
                    }
                }

                if (!empty($arrayTravelData)) {
                    echo json_encode(['status' => false, 'message' => 'Please enter travel required fields.']);
                    return;
                }
                
                $this->form_validation->set_rules('date', 'Date', 'trim|required');
                $this->form_validation->set_rules('month', 'Month', 'trim|required');
                $this->form_validation->set_rules('stateId', 'State', 'trim|required');
                if ($this->form_validation->run() == false) {
                    echo json_encode(['status' => false, 'message' => validation_errors()]);
                    return;
                } else {
                    $from = $this->input->post('from');
                    $to = $this->input->post('to');
                    $employeeId = $this->session->userdata('EmployeeId');
                    $userId = $this->session->userdata('UserId');
                    $compId = $this->session->userdata('CompId');
                    $compBranchId = $this->session->userdata('CompBranchId');
                    $updated = date('d-m-Y h:i:s A');
                    $updatedBy = get_title('employees', ['Id' => $employeeId], 'Name');
                    
                    $updateWhere = ['date >= ' => $from, 'date <= ' => $to, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId];
                    $updateTravelDataWhere = ['date >= ' => $from, 'date <= ' => $to, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId];
                    $compProfileWhere = ['user_id' => $userId, 'comp_id' => $compId];
                    if ($this->session->userdata('BranchRole')) {
                        $compProfileWhere['CompanyBranchId'] = $compBranchId;
                        $updateWhere['compBranchId'] = $compBranchId;
                        $updateTravelDataWhere['compBranchId'] = $compBranchId;
                    }
                    $financialYear = get_title('comp_profile', $compProfileWhere, 'financial_year');
                                        
                    if ($this->input->post('employeeId') != 'All') {
                        $salesmanIds = $this->input->post('employeeId');
                    } else {
                        $salesmanIds = json_decode(get_title('employees', ['Id' => $employeeId, 'IsDeleted' => 1], 'WarehouseSalesman'), true) ?: [];
                    }                    
                    
                    $employeeTravels = [
                        'date' => $this->input->post('date'),
                        'location' => $this->input->post('location'),
                        'band' => $this->input->post('band'),
                        'stateId' => $this->input->post('stateId'),
                        'month' => $this->input->post('month'),
                        'department' => $this->input->post('department'),
                        'headQuarter' => $this->input->post('headQuarter'),
                        'remark' => $this->input->post('remark'),
                        'updated' => $updated,
                        'updatedBy' => $updatedBy,
                    ];                    
                    
                    $result = $this->db->where($updateWhere)->where_in('employeeId', $salesmanIds)->update('employee_travels', $employeeTravels);
                    
                    foreach ($this->input->post('travels') as $key => $travelData) {
                        if (!empty($newData['travelDate']) && !empty($travelData['placesVisited'])) {
                            $employeeTravelsData = [
                                'date' => $this->input->post('date'),
                                'travelDate' => $travelData['travelDate'],
                                'placesVisited' => $travelData['placesVisited'],
                                'dailyAllowanceClaimed' => $travelData['dailyAllowanceClaimed'],
                                'internetFax' => $travelData['internetFax'],
                                'xeroxPrint' => $travelData['xeroxPrint'],
                                'postCourier' => $travelData['postCourier'],
                                'total' => $travelData['total'],
                                'remark' => $travelData['remark'],
                                'updated' => $updated,
                                'updatedBy' => $updatedBy
                            ];
                            
                            $updateTravelDataWhere['id'] = $travelData['rowId'];
                            $this->db->where($updateTravelDataWhere)->where_in('employeeId', $salesmanIds)->update('employee_travels_data', $employeeTravelsData);                            
                        }
                    }                    

                    foreach ($this->input->post('newTravels') as $key => $newTravelData) {
                        if (!empty($newTravelData['travelDate']) && !empty($newTravelData['placesVisited'])) {
                            $employeeNewTravelsData = [
                                'date' => $this->input->post('date'),
                                'employeeTravelId' => $this->db->select('id')->where(['date >= ' => $from, 'date <= ' => $to, 'employeeId' => $newTravelData['employeeId']])->order_by('id', 'DESC')->get('employee_travels')->row('id'),
                                'travelDate' => $newTravelData['travelDate'],
                                'placesVisited' => $newTravelData['placesVisited'],
                                'dailyAllowanceClaimed' => $newTravelData['dailyAllowanceClaimed'],
                                'internetFax' => $newTravelData['internetFax'],
                                'xeroxPrint' => $newTravelData['xeroxPrint'],
                                'postCourier' => $newTravelData['postCourier'],
                                'total' => $newTravelData['total'],
                                'remark' => $newTravelData['remark'],
                                'userId' => $userId,
                                'compId' => $compId,
                                'compBranchId' => $compBranchId,
                                'seniorEmployeeId' => $employeeId,
                                'employeeId' => $newTravelData['employeeId'],
                                'financialYear' => $financialYear,
                                'created' => $updated,
                                'createdBy' => $updatedBy
                            ];
                            $this->user_model->saveAry('employee_travels_data', $employeeNewTravelsData);
                        }
                    }
                    
                    if (!empty($result)) {
                        echo json_encode(['status' => true, 'message' => 'Record update successfully']);
                        return;
                    } else {
                        echo json_encode(['status' => false, 'message' => 'Unable to save record']);
                        return;
                    }                    
                }                
            }
        }        
        
        public function seniorEmployeeTravelsDelete()
        {
            $employeeId = $this->session->userdata('EmployeeId');
            $userId = $this->session->userdata('UserId');
            $compId = $this->session->userdata('CompId');
            $compBranchId = $this->session->userdata('CompBranchId');
            $financialYear = $this->session->userdata('financial_year');
            
            $where = ['date >= ' => $this->input->get('from'), 'date <= ' => $this->input->get('to'), 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear];
            if ($this->session->userdata('BranchRole')) {
                $where['compBranchId'] = $compBranchId;
            }
            if ($this->input->get('employeeId') != "" && $this->input->get('employeeId') != 'All') {
                $salesmanIds = $this->input->get('employeeId');
            } else {
                $salesmanIds = json_decode(get_title('employees', ['Id' => $employeeId, 'IsDeleted' => 1], 'WarehouseSalesman'), true) ?: [];
            }
            
            if ($this->db->select('id')->where($where)->where_in('employeeId', $salesmanIds)->get('employee_travels')->num_rows()) {
                if ($this->db->where($where)->where_in('employeeId', $salesmanIds)->update('employee_travels', ['isDeleted' => 0])) {
                    $this->db->where($where)->where_in('employeeId', $salesmanIds)->update('employee_travels_data', ['isDeleted' => 0]);
                    $this->session->set_flashdata(['msg' => 'Record delete successfully.', 'class' => 'success']);
                    return redirect($_SERVER['HTTP_REFERER']);
                } else {
                    $this->session->set_flashdata(['msg' => 'Unable to delete record.', 'class' => 'danger']);
                    return redirect($_SERVER['HTTP_REFERER']);
                }
            } else {
                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }
        }        
        
        public function seniorEmployeeTravelsReportDownload()
        {
            ini_set('memory_limit', '-1');
            set_time_limit(500);
            
            $from = $this->input->get('from');
            $to = $this->input->get('to');
            $employeeId = $this->input->get('employeeId');
            $userId = $this->session->userdata('UserId');
            $compId = $this->session->userdata('CompId');
            $compBranchId = $this->session->userdata('CompBranchId');
            $financialYear = $this->session->userdata('financial_year');
            
            $where = ['date >= ' => $from, 'date <= ' => $to, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear, 'isDeleted' => 1];
            $travelsDataWhere = ['date >= ' => $from, 'date <= ' => $to, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear, 'isDeleted' => 1];
            if ($employeeId != "" && $employeeId != 'All') {
                $salesmanIds = $employeeId;
            } else {
                $salesmanIds = json_decode(get_title('employees', ['Id' => $this->session->userdata('EmployeeId'), 'IsDeleted' => 1], 'WarehouseSalesman'), true) ?: [];
            }            
            $compProfileWhere = ['user_id' => $userId, 'comp_id' => $compId];
            if ($this->session->userdata('BranchRole')) {
                $Name = get_title('companybranch', ['Id' => $compBranchId], 'BranchName');
                $where['compBranchId'] = $compBranchId;
                $travelsDataWhere['compBranchId'] = $compBranchId;
                $compProfileWhere['CompanyBranchId'] = $compBranchId;
            } else {
                $Name = get_title('inventory_billing', ['id' => $compId], 'company_name');
            }
            
            $logoPath = 'uploads/complogo/' . get_title('comp_profile', $compProfileWhere, 'logo');
            if (!file_exists($logoPath)) {
                $logoPath = 'assets/images/avatar.png';
            }
            
            $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
            $TravelsReportQuery = $this->db->where($where)->where_in('employeeId', $salesmanIds)->group_by('date')->get('employee_travels');
            if ($TravelsReportQuery->num_rows()) {
                $travelData = $TravelsReportQuery->row();
                
                $PHPExcel = new PHPExcel();
                                
                // Define styles
                $topHeaderStyleArray = [
                    'font' => [
                        'size' => 11,
                        'color' => ['rgb' => '000000'],
                    ],
                    'borders' => [
                        'allborders' => [
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ];
                $headerStyleArray = [
                    'font' => [
                        'bold' => true,
                        'size' => 11,
                        'color' => ['rgb' => '000000'],
                    ],
                    'borders' => [
                        'allborders' => [
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ];

                $dataStyleArray = [
                    'font' => [
                        'size' => 10,
                    ],
                    'borders' => [
                        'allborders' => [
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ];                
                
                $PHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
                                
                // Add the logo image
                $PHPExcelDrawing = new PHPExcel_Worksheet_Drawing();
                $PHPExcelDrawing->setName('Logo');
                $PHPExcelDrawing->setDescription('Logo');
                $PHPExcelDrawing->setPath($logoPath);
                $PHPExcelDrawing->setHeight(60);
                $PHPExcelDrawing->setCoordinates('I1');
                $PHPExcelDrawing->setWorksheet($PHPExcel->getActiveSheet());                
                $PHPExcel->getActiveSheet()->getStyle('I1:I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                
                $PHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'Sale:')
                    ->mergeCells('A1:B1')
                    ->mergeCells('C1:D1')
                    ->setCellValue('A2', 'Total Expenses:')
                    ->mergeCells('A2:B2')
                    ->mergeCells('C2:D2')
                    ->setCellValue('E1', 'Format effective date: ' . date('jS F, Y'))
                    ->mergeCells('E1:H2')
                    ->setCellValue('A3', 'LOCAL TRAVEL')                    
                    ->mergeCells('A3:I3')
                    ->setCellValue('A4', 'Band')
                    ->setCellValue('C4', $travelData->band)
                    ->mergeCells('A4:B4')
                    ->mergeCells('C4:D4')
                    ->setCellValue('E4', 'Department')
                    ->setCellValue('G4', $travelData->department)
                    ->mergeCells('E4:F4')                        
                    ->mergeCells('G4:H4')                        
                    ->setCellValue('A5', 'Location / Place of posting')
                    ->setCellValue('B5', $travelData->location . " - " . get_title('state_list', ['id' => $travelData->stateId], 'state'))
                    ->mergeCells('A5:B5')
                    ->mergeCells('C5:D5')
                    ->setCellValue('E5', 'Head Quarter')
                    ->setCellValue('G5', $travelData->headQuarter)
                    ->mergeCells('E5:F5')
                    ->mergeCells('G5:H5')
                    ->setCellValue('A6', 'Month')
                    ->setCellValue('C6', $travelData->month)
                    ->mergeCells('A6:B6')
                    ->mergeCells('C6:D6')
                    ->setCellValue('E6', '')
                    ->setCellValue('G6', '')
                    ->mergeCells('E6:F6')
                    ->mergeCells('G6:H6');

                // Set column titles
                $PHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A7', 'DATE (DD/MM/YYYY)')
                    ->mergeCells('A7:A8')
                    ->setCellValue('B7', 'SALESMAN')
                    ->mergeCells('B7:B8')
                    ->setCellValue('C7', 'PLACES VISITED')
                    ->mergeCells('C7:C8')
                    ->setCellValue('D7', 'DAILY ALLOWANCE CLAIMED')
                    ->mergeCells('D7:D8')
                    ->setCellValue('E7', 'OTHER MISC. EXPENSES')
                    ->mergeCells('E7:G7')
                    ->setCellValue('E8', 'INTERNET/FAX')
                    ->setCellValue('F8', 'XEROX/PRINT')
                    ->setCellValue('G8', 'POST/COURIER')
                    ->setCellValue('H7', 'TOTAL')
                    ->mergeCells('H7:H8')
                    ->setCellValue('I7', 'REMARK')
                    ->mergeCells('I7:I8');
                                
                $TravelDataReportQuery = $this->db->where($travelsDataWhere)->where_in('employeeId', $salesmanIds)->get('employee_travels_data');
                if ($TravelDataReportQuery->num_rows()) {
                    $rowCount = 9;
                    $totalDailyAllowanceClaimed = 0;
                    $totalInternetFax = 0;
                    $totalXeroxPrint = 0;
                    $totalPostCourier = 0;
                    $totalAmount = 0;
                    foreach ($TravelDataReportQuery->result() as $key => $travelsData) {
                        $totalDailyAllowanceClaimed += $travelsData->dailyAllowanceClaimed;
                        $totalInternetFax += $travelsData->internetFax;
                        $totalXeroxPrint += $travelsData->xeroxPrint;
                        $totalPostCourier += $travelsData->postCourier;
                        $totalAmount += $travelsData->total;
                        
                        $PHPExcel->getActiveSheet()->getStyle('A' . $rowCount . ':I' . $rowCount)->applyFromArray($dataStyleArray);
                        
                        $PHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, date('d/m/Y', strtotime($travelsData->travelDate)));
                        $PHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, get_title('employees', ['Id' => $travelsData->employeeId], 'Name'));
                        $PHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $travelsData->placesVisited);
                        $PHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $travelsData->dailyAllowanceClaimed);
                        $PHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $travelsData->internetFax);
                        $PHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $travelsData->xeroxPrint);
                        $PHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $travelsData->postCourier);
                        $PHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $travelsData->total);
                        $PHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $travelsData->remark);
                        $rowCount++;
                    }
                    $PHPExcel->getActiveSheet()->getStyle('A' . $rowCount . ':I' . $rowCount)->applyFromArray($dataStyleArray);
                    
                    $rowCount++;
                    $PHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, 'Total');
                    $PHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $rowCount . ':C' . $rowCount);
                    $PHPExcel->getActiveSheet()->getStyle('A' . $rowCount . ':C' . $rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    
                    $PHPExcel->getActiveSheet()->setCellValue('D' . $rowCount, $totalDailyAllowanceClaimed);
                    $PHPExcel->getActiveSheet()->setCellValue('E' . $rowCount, $totalInternetFax);
                    $PHPExcel->getActiveSheet()->setCellValue('F' . $rowCount, $totalXeroxPrint);
                    $PHPExcel->getActiveSheet()->setCellValue('G' . $rowCount, $totalPostCourier);
                    $PHPExcel->getActiveSheet()->setCellValue('H' . $rowCount, $totalAmount);
                    
                    $PHPExcel->getActiveSheet()->getStyle('A' . $rowCount . ':I' . $rowCount)->applyFromArray($headerStyleArray);
                    
                    $rowCount++;
                    $PHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $rowCount . ':I' . $rowCount);
                    $PHPExcel->getActiveSheet()->getStyle('A' . $rowCount . ':I' . $rowCount)->applyFromArray($headerStyleArray);
                    
                    $rowCount++;
                    $PHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, 'Sd/-');
                    $PHPExcel->getActiveSheet()->setCellValue('D' . $rowCount, 'Sd/-');
                    $PHPExcel->getActiveSheet()->setCellValue('G' . $rowCount, 'Sd/-');
                    $PHPExcel->getActiveSheet()->getStyle('A' . $rowCount)->getFont()->setBold(true);
                    $PHPExcel->getActiveSheet()->getStyle('D' . $rowCount)->getFont()->setBold(true);
                    $PHPExcel->getActiveSheet()->getStyle('G' . $rowCount)->getFont()->setBold(true);
                    $PHPExcel->getActiveSheet()->getStyle('A' . $rowCount . ':I' . $rowCount)->applyFromArray($topHeaderStyleArray);
                    
                    $rowCount++;
                    $previousRowNumber = ($rowCount - 1);
                    $PHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, 'Employee');
                    $PHPExcel->getActiveSheet()->setCellValue('D' . $rowCount, 'Reporting Senior');
                    $PHPExcel->getActiveSheet()->setCellValue('G' . $rowCount, 'Director');
                    $PHPExcel->getActiveSheet()->getStyle('A' . $rowCount)->getFont()->setBold(true);
                    $PHPExcel->getActiveSheet()->getStyle('D' . $rowCount)->getFont()->setBold(true);
                    $PHPExcel->getActiveSheet()->getStyle('G' . $rowCount)->getFont()->setBold(true);
                    $PHPExcel->getActiveSheet()->getStyle('A' . $rowCount . ':I' . $rowCount)->applyFromArray($topHeaderStyleArray);
                    $PHPExcel->setActiveSheetIndex(0)->mergeCells('B' . $previousRowNumber . ':C' . $rowCount);
                    $PHPExcel->setActiveSheetIndex(0)->mergeCells('E' . $previousRowNumber . ':F' . $rowCount);
                    $PHPExcel->setActiveSheetIndex(0)->mergeCells('H' . $previousRowNumber . ':I' . $rowCount);
                }
                
                $PHPExcel->getActiveSheet()->getRowDimension('8')->setRowHeight(35);
                $PHPExcel->getActiveSheet()->getStyle('A7:I8')->getAlignment()->setWrapText(true);
                
                // Set column widths
                $PHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(25);
                $PHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(16);
                $PHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(50);
                $PHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(16);
                $PHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(15);
                $PHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
                $PHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(20);
                $PHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
                $PHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(20);
                
                // Apply styles
                $PHPExcel->getActiveSheet()->getStyle('A1:I1')->applyFromArray($headerStyleArray);
                $PHPExcel->getActiveSheet()->getStyle('E1:I2')->applyFromArray($headerStyleArray);
                $PHPExcel->getActiveSheet()->getStyle('E1:I2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_TOP);                
                $PHPExcel->getActiveSheet()->getStyle('A2:I2')->applyFromArray($headerStyleArray);
                $PHPExcel->getActiveSheet()->getStyle('A3:I3')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14,
                        'color' => ['rgb' => '000000'],
                    ],
                    'borders' => [
                        'allborders' => [
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);
                $PHPExcel->getActiveSheet()->getStyle('A3:I3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $PHPExcel->getActiveSheet()->getStyle('A3:I3')->getFill()->applyFromArray([
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'startcolor' => ['rgb' => 'FFFF00'],
                ]);
                $PHPExcel->getActiveSheet()->getStyle('E1:I2')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $PHPExcel->getActiveSheet()->getStyle('A7:I8')->applyFromArray([
                    'alignment' => [
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                    ]
                ]);
                $PHPExcel->getActiveSheet()->getStyle('A4:I7')->applyFromArray($topHeaderStyleArray);
                $PHPExcel->getActiveSheet()->getStyle('A7:I8')->applyFromArray($headerStyleArray);
                
                // Set background colors
                $PHPExcel->getActiveSheet()->getStyle('A1:B8')->getFill()->applyFromArray([
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'startcolor' => ['rgb' => 'FFFF00'],
                ]);
                $PHPExcel->getActiveSheet()->getStyle('C3:I3')->getFill()->applyFromArray([
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'startcolor' => ['rgb' => 'FFFF00'],
                ]);
                $PHPExcel->getActiveSheet()->getStyle('E3:F6')->getFill()->applyFromArray([
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'startcolor' => ['rgb' => 'FFFF00'],
                ]);
                $PHPExcel->getActiveSheet()->getStyle('A7:I8')->getFill()->applyFromArray([
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'startcolor' => ['rgb' => 'FFFF00'],
                ]);

                $PHPExcel->getActiveSheet()->setTitle('LOCAL_TRAVEL');
                
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="' . str_replace(' ', '_', $Name) . '_LOCAL_TRAVEL_REPORT.xlsx"');
                header('Cache-Control: max-age=0');
                PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007')->save('php://output');
            } else {
                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }
        }
        
        public function employeeOutstationTravels()
        {
            $employeeId = $this->session->userdata('EmployeeId');
            $userId = $this->session->userdata('UserId');
            $compId = $this->session->userdata('CompId');
            $compBranchId = $this->session->userdata('CompBranchId');
            
            $where = ['Id' => $employeeId, 'UserId' => $userId, 'CompId' => $compId, 'IsDeleted' => 1];
            $employeePermissionWhere = ['EmployeeId' => $employeeId, 'UserId' => $userId, 'CompId' => $compId];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $compBranchId;
                $employeePermissionWhere['CompanyBranchId'] = $compBranchId;                
            }
            
            $data['EmployeeInfo'] = $this->db->where($where)->get('employees')->row();
            $data['EmployeePermission'] = $this->db->where($employeePermissionWhere)->get('employee_permission')->row();
            $data['statesList'] = $this->db->select('id, state, code')->get('state_list')->result();
            $data['page'] = "employeeOutstationTravels";
            return $this->load->view('inventory_sep/Employee/Employee', $data);
        }
        
        public function saveEmployeeOutstationTravels()
        {
            if ($this->input->is_ajax_request()) {
                $arrayTravelData = [];
                foreach ($this->input->post('travels') as $key => $row) {
                    if ($row['travelDate'] == '' || $row['from'] == '' || $row['to'] == '') {
                        $arrayTravelData[] = $row;
                    }
                }

                if (!empty($arrayTravelData)) {
                    echo json_encode(['status' => false, 'message' => 'Please enter travel required fields.']);
                    return;
                }
                
                $this->form_validation->set_rules('date', 'Date', 'trim|required');
                $this->form_validation->set_rules('employeeName', 'Employee Name', 'trim|required');
                $this->form_validation->set_rules('designation', 'Designation', 'trim|required');
                $this->form_validation->set_rules('month', 'Month', 'trim|required');
                $this->form_validation->set_rules('stateId', 'State', 'trim|required');
                $this->form_validation->set_rules('grandTotal', 'Total', 'trim|numeric|required');
                if ($this->form_validation->run() == false) {
                    echo json_encode(['status' => false, 'message' => validation_errors()]);
                    return;
                } else {
                    $employeeId = $this->session->userdata('EmployeeId');
                    $userId = $this->session->userdata('UserId');
                    $compId = $this->session->userdata('CompId');
                    $compBranchId = $this->session->userdata('CompBranchId');
                    $created = date('d-m-Y h:i:s A');
                    $createdBy = get_title('employees', ['Id' => $employeeId], 'Name');
                    
                    $compProfileWhere = ['user_id' => $userId, 'comp_id' => $compId];
                    if ($this->session->userdata('BranchRole')) {
                        $compProfileWhere['CompanyBranchId'] = $compBranchId;
                    }
                    $financialYear = get_title('comp_profile', $compProfileWhere, 'financial_year');
                                        
                    $employeeTravels = [
                        'date' => $this->input->post('date'),
                        'employeeId' => $this->input->post('employeeId'),
                        'location' => $this->input->post('location'),
                        'band' => $this->input->post('band'),
                        'stateId' => $this->input->post('stateId'),
                        'month' => $this->input->post('month'),
                        'department' => $this->input->post('department'),
                        'reportingSenior' => $this->input->post('reportingSenior'),
                        'headQuarter' => $this->input->post('headQuarter'),
                        'remark' => $this->input->post('remark'),
                        'grandTotal' => $this->input->post('grandTotal'),
                        'userId' => $userId,
                        'compId' => $compId,
                        'compBranchId' => $compBranchId,
                        'financialYear' => $financialYear,
                        'created' => $created,
                        'createdBy' => $createdBy,
                    ];
                    
                    $result = $this->user_model->saveAry('employee_outstation_travels', $employeeTravels);
                    
                    if ($this->session->userdata('BranchRole')) {
                        $sql = "SELECT Id FROM employees WHERE UserId = '" . $userId . "' AND CompId = '" . $compId . "' AND CompanyBranchId = '" . $compBranchId . "' AND IsDeleted = 1 AND Status = 1 AND WarehouseSalesman IS NOT NULL AND JSON_VALID(WarehouseSalesman) AND JSON_CONTAINS(WarehouseSalesman, '[\"$employeeId\"]')";                        
                    } else {
                        $sql = "SELECT Id FROM employees WHERE UserId = '" . $userId . "' AND CompId = '" . $compId . "' AND IsDeleted = 1 AND Status = 1 AND WarehouseSalesman IS NOT NULL AND JSON_VALID(WarehouseSalesman) AND JSON_CONTAINS(WarehouseSalesman, '[\"$employeeId\"]')";
                    }                    
                    $query = $this->db->query($sql);                    
                    
                    foreach ($this->input->post('travels') as $key => $travalData) {
                        if (!empty($travalData['travelDate']) && !empty($travalData['from']) && !empty($travalData['to'])) {
                            $employeeTravelsData = [
                                'date' => $this->input->post('date'),
                                'employeeTravelId' => $result,
                                'travelDate' => $travalData['travelDate'],
                                'from' => $travalData['from'],
                                'to' => $travalData['to'],
                                'bus' => $travalData['bus'],
                                'startKM' => $travalData['startKM'],
                                'endKM' => $travalData['endKM'],
                                'totalKM' => $travalData['totalKM'],
                                'amount' => $travalData['amount'],
                                'modelType' => $travalData['modelType'],
                                'railway' => $travalData['railway'],
                                'auto' => $travalData['auto'],
                                'hotel' => $travalData['hotel'],
                                'food' => $travalData['food'],
                                'internetFax' => $travalData['internetFax'],
                                'xeroxPrint' => $travalData['xeroxPrint'],
                                'postCourier' => $travalData['postCourier'],
                                'totalAmount' => $travalData['totalAmount'],
                                'remark' => $travalData['remark'],
                                'userId' => $userId,
                                'compId' => $compId,
                                'compBranchId' => $compBranchId,
                                'seniorEmployeeId' => $query->row()->Id,
                                'employeeId' => $this->input->post('employeeId'),
                                'financialYear' => $financialYear,
                                'created' => $created,
                                'createdBy' => $createdBy
                            ];
                            $this->user_model->saveAry('employee_outstation_travels_data', $employeeTravelsData);
                        }
                    }
                    
                    if (!empty($result)) {
                        echo json_encode(['status' => true, 'message' => 'Record save successfully']);
                        return;
                    } else {
                        echo json_encode(['status' => false, 'message' => 'Unable to save record']);
                        return;
                    }                    
                }                
            }
        }
        
        public function employeeOutstationTravelsReport()
        {
            $employeeId = $this->session->userdata('EmployeeId');
            $userId = $this->session->userdata('UserId');
            $compId = $this->session->userdata('CompId');
            $compBranchId = $this->session->userdata('CompBranchId');
            $financialYear = $this->session->userdata('financial_year');
            if ($this->input->get('from') && $this->input->get('to')) {
                $from = $this->input->get('from');
                $to = $this->input->get('to');
            } else {
                $from = date('Y-m-d');
                $to = date('Y-m-d');
            }
            
            $where = ['Id' => $employeeId, 'UserId' => $userId, 'CompId' => $compId, 'IsDeleted' => 1];
            $employeePermissionWhere = ['EmployeeId' => $employeeId, 'UserId' => $userId, 'CompId' => $compId];
            $employeeTravelsWhere = ['date >= ' => $from, 'date <= ' => $to, 'employeeId' => $employeeId, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $compBranchId;
                $employeePermissionWhere['CompanyBranchId'] = $compBranchId;                
                $employeeTravelsWhere['compBranchId'] = $compBranchId;                
            }
            
            $data['EmployeeInfo'] = $this->db->where($where)->get('employees')->row();
            $data['EmployeePermission'] = $this->db->where($employeePermissionWhere)->get('employee_permission')->row();
            $data['employeeTravels'] = $this->db->where($employeeTravelsWhere)->order_by('id', 'DESC')->get('employee_outstation_travels')->result();
            $data['page'] = "employeeOutstationTravelsReport";
            return $this->load->view('inventory_sep/Employee/Employee', $data);
        }
        
        public function employeeOutstationTravelsInfo($id)
        {
            if (!empty($id)) {
                $employeeId = $this->session->userdata('EmployeeId');
                $userId = $this->session->userdata('UserId');
                $compId = $this->session->userdata('CompId');
                $compBranchId = $this->session->userdata('CompBranchId');
                $financialYear = $this->session->userdata('financial_year');
                
                $employeeTravelsWhere = ['id' => $id, 'employeeId' => $employeeId, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear];
                $employeeTravelsDataWhere = ['employeeTravelId' => $id, 'employeeId' => $employeeId, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear];
                if ($this->session->userdata('BranchRole')) {
                    $employeeTravelsWhere['compBranchId'] = $compBranchId;
                    $employeeTravelsDataWhere['compBranchId'] = $compBranchId;
                }

                $data['employeeTravelsInfo'] = $this->db->where($employeeTravelsWhere)->get('employee_outstation_travels')->row();
                $data['employeeTravelsData'] = $this->db->where($employeeTravelsDataWhere)->get('employee_outstation_travels_data')->result();
                return $this->load->view('inventory_sep/Employee/employeeOutstationTravelsInfo', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }
        }
        
        public function employeeOutstationTravelsEdit($id)
        {
            if (!empty($id)) {
                $employeeId = $this->session->userdata('EmployeeId');
                $userId = $this->session->userdata('UserId');
                $compId = $this->session->userdata('CompId');
                $compBranchId = $this->session->userdata('CompBranchId');
                $financialYear = $this->session->userdata('financial_year');
                
                $where = ['Id' => $employeeId, 'UserId' => $userId, 'CompId' => $compId, 'IsDeleted' => 1];
                $employeePermissionWhere = ['EmployeeId' => $employeeId, 'UserId' => $userId, 'CompId' => $compId];
                $employeeTravelsWhere = ['id' => $id, 'employeeId' => $employeeId, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear];
                if ($this->session->userdata('BranchRole')) {
                    $where['CompanyBranchId'] = $compBranchId;
                    $employeePermissionWhere['CompanyBranchId'] = $compBranchId;
                    $employeeTravelsWhere['compBranchId'] = $compBranchId;
                }

                $data['EmployeeInfo'] = $this->db->where($where)->get('employees')->row();
                $data['EmployeePermission'] = $this->db->where($employeePermissionWhere)->get('employee_permission')->row();
                $data['employeeTravelsInfo'] = $this->db->where($employeeTravelsWhere)->get('employee_outstation_travels')->row();
                $data['employeeTravelId'] = $id;
                $data['statesList'] = $this->db->select('id, state, code')->get('state_list')->result();
                $data['page'] = "employeeOutstationTravelsEdit";
                return $this->load->view('inventory_sep/Employee/Employee', $data);
            } else {
                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }
        }
        
        public function getEmployeeOutstationTravelsData($employeeTravelId)
        {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                if (!empty($employeeTravelId)) {
                    $employeeId = $this->session->userdata('EmployeeId');
                    $userId = $this->session->userdata('UserId');
                    $compId = $this->session->userdata('CompId');
                    $compBranchId = $this->session->userdata('CompBranchId');
                    $financialYear = $this->session->userdata('financial_year');
                    
                    $TravelsDataWhere = ['employeeTravelId' => $employeeTravelId, 'employeeId' => $employeeId, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear];
                    if ($this->session->userdata('BranchRole')) {
                        $TravelsDataWhere['compBranchId'] = $compBranchId;
                    }
                    
                    $employeeTravelsDataQuery = $this->db->where($TravelsDataWhere)->get('employee_outstation_travels_data');
                    if ($employeeTravelsDataQuery->num_rows()) {
                        echo json_encode(array('status' => true, "data" => $employeeTravelsDataQuery->result()));
                    } else {
                        echo json_encode(array('status' => false, "data" => "No data found."));
                    }
                } else {
                    echo json_encode(array('status' => false, "data" => "id missing."));
                }
            }
        }
                
        public function deleteEmployeeOutstationTravelsData()
        {
            if ($this->input->is_ajax_request()) {
                if (!empty($this->input->post('rowId'))) {
                    $rowId = $this->input->post('rowId');
                    $employeeId = $this->session->userdata('EmployeeId');
                    $userId = $this->session->userdata('UserId');
                    $compId = $this->session->userdata('CompId');
                    $compBranchId = $this->session->userdata('CompBranchId');
                    
                    $where = ['employeeId' => $employeeId, 'userId' => $userId, 'compId' => $compId, 'isDeleted' => 1];
                    $travelDataWhere = ['employeeId' => $employeeId, 'id' => $rowId, 'userId' => $userId, 'compId' => $compId, 'isDeleted' => 1];
                    if ($this->session->userdata('BranchRole')) {
                        $where['compBranchId'] = $compBranchId;
                        $travelDataWhere['compBranchId'] = $compBranchId;
                    }
                    
                    $employeeTravelsData = $this->db->select('employeeTravelId, totalAmount')->where($travelDataWhere)->get('employee_outstation_travels_data')->row();
                    $where['id'] = $employeeTravelsData->employeeTravelId;
                    $currentGrandTotal = get_title('employee_outstation_travels', $where, 'grandTotal');
                    $newGrandTotal = $currentGrandTotal - $employeeTravelsData->totalAmount;
                    $this->db->where($where)->update('employee_outstation_travels', ['grandTotal' => $newGrandTotal]);
                    
                    $result = $this->db->where($travelDataWhere)->update('employee_outstation_travels_data', ['isDeleted' => 0]);
                    if ($result) {
                        echo json_encode(array('status' => true, "message" => "Record successfully deleted."));
                        return;
                    } else {
                        echo json_encode(array('status' => false, "message" => "Somethings went wrong, try again."));
                        return;
                    }
                } else {
                    echo json_encode(array('status' => false, "message" => "Id not found."));
                    return;
                }
            }
        }         
        
        public function updateEmployeeOutstationTravels()
        {
            if ($this->input->is_ajax_request()) {
                $arrayTravalData = [];
                foreach ($this->input->post('travels') as $key => $row) {
                    if ($row['travelDate'] == '' || $row['from'] == '' || $row['to'] == '') {
                        $arrayTravalData[] = $row;
                    }
                }

                if (!empty($arrayTravalData)) {
                    echo json_encode(['status' => false, 'message' => 'Please enter travel required fields.']);
                    return;
                }                
                
                $this->form_validation->set_rules('date', 'Date', 'trim|required');
                $this->form_validation->set_rules('employeeName', 'Employee Name', 'trim|required');
                $this->form_validation->set_rules('designation', 'Designation', 'trim|required');
                $this->form_validation->set_rules('month', 'Month', 'trim|required');
                $this->form_validation->set_rules('stateId', 'State', 'trim|required');
                $this->form_validation->set_rules('grandTotal', 'Total', 'trim|numeric|required');
                if ($this->form_validation->run() == false) {
                    echo json_encode(['status' => false, 'message' => validation_errors()]);
                    return;
                } else {
                    $employeeTravelId = $this->input->post('employeeTravelId');
                    $employeeId = $this->session->userdata('EmployeeId');
                    $userId = $this->session->userdata('UserId');
                    $compId = $this->session->userdata('CompId');
                    $compBranchId = $this->session->userdata('CompBranchId');
                    $updated = date('d-m-Y h:i:s A');
                    $updatedBy = get_title('employees', ['Id' => $employeeId], 'Name');
                    
                    $updateWhere = ['id' => $employeeTravelId, 'employeeId' => $employeeId, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId];
                    $updateTravelDataWhere = ['employeeTravelId' => $employeeTravelId, 'employeeId' => $employeeId, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId];
                    $compProfileWhere = ['user_id' => $userId, 'comp_id' => $compId];
                    if ($this->session->userdata('BranchRole')) {
                        $compProfileWhere['CompanyBranchId'] = $compBranchId;
                        $updateWhere['compBranchId'] = $compBranchId;
                        $updateTravelDataWhere['compBranchId'] = $compBranchId;
                    }
                    $financialYear = get_title('comp_profile', $compProfileWhere, 'financial_year');
                                        
                    $employeeTravels = [
                        'date' => $this->input->post('date'),
                        'location' => $this->input->post('location'),
                        'band' => $this->input->post('band'),
                        'stateId' => $this->input->post('stateId'),
                        'month' => $this->input->post('month'),
                        'department' => $this->input->post('department'),
                        'reportingSenior' => $this->input->post('reportingSenior'),
                        'headQuarter' => $this->input->post('headQuarter'),
                        'remark' => $this->input->post('remark'),
                        'grandTotal' => $this->input->post('grandTotal'),
                        'updated' => $updated,
                        'updatedBy' => $updatedBy,
                    ];                    
                    
                    $result = $this->user_model->updateAry('employee_outstation_travels', $employeeTravels, $updateWhere);
                    
                    foreach ($this->input->post('travels') as $key => $travelData) {
                        if (!empty($travelData['travelDate']) && !empty($travelData['from']) && !empty($travelData['to'])) {
                            $employeeTravelsData = [
                                'date' => $this->input->post('date'),
                                'travelDate' => $travelData['travelDate'],
                                'from' => $travelData['from'],
                                'to' => $travelData['to'],
                                'bus' => $travelData['bus'],
                                'startKM' => $travelData['startKM'],
                                'endKM' => $travelData['endKM'],
                                'totalKM' => $travelData['totalKM'],
                                'amount' => $travelData['amount'],
                                'modelType' => $travelData['modelType'],
                                'railway' => $travelData['railway'],
                                'auto' => $travelData['auto'],
                                'hotel' => $travelData['hotel'],
                                'food' => $travelData['food'],
                                'internetFax' => $travelData['internetFax'],
                                'xeroxPrint' => $travelData['xeroxPrint'],
                                'postCourier' => $travelData['postCourier'],
                                'totalAmount' => $travelData['totalAmount'],
                                'remark' => $travelData['remark'],
                                'updated' => $updated,
                                'updatedBy' => $updatedBy
                            ];
                            
                            $updateTravelDataWhere['id'] = $travelData['rowId'];
                            $this->user_model->updateAry('employee_outstation_travels_data', $employeeTravelsData, $updateTravelDataWhere);
                        }
                    }
                    
                    if ($this->session->userdata('BranchRole')) {
                        $sql = "SELECT Id FROM employees WHERE UserId = '" . $userId . "' AND CompId = '" . $compId . "' AND CompanyBranchId = '" . $compBranchId . "' AND IsDeleted = 1 AND Status = 1 AND WarehouseSalesman IS NOT NULL AND JSON_VALID(WarehouseSalesman) AND JSON_CONTAINS(WarehouseSalesman, '[\"$employeeId\"]')";                        
                    } else {
                        $sql = "SELECT Id FROM employees WHERE UserId = '" . $userId . "' AND CompId = '" . $compId . "' AND IsDeleted = 1 AND Status = 1 AND WarehouseSalesman IS NOT NULL AND JSON_VALID(WarehouseSalesman) AND JSON_CONTAINS(WarehouseSalesman, '[\"$employeeId\"]')";
                    }                    
                    $query = $this->db->query($sql);

                    foreach ($this->input->post('newTravels') as $key => $newTravelData) {
                        if (!empty($newTravelData['travelDate']) && !empty($newTravelData['from']) && !empty($newTravelData['to'])) {
                            $employeeNewTravelsData = [
                                'date' => $this->input->post('date'),
                                'employeeTravelId' => $employeeTravelId,
                                'travelDate' => $newTravelData['travelDate'],
                                'from' => $newTravelData['from'],
                                'to' => $newTravelData['to'],
                                'bus' => $newTravelData['bus'],
                                'startKM' => $newTravelData['startKM'],
                                'endKM' => $newTravelData['endKM'],
                                'totalKM' => $newTravelData['totalKM'],
                                'amount' => $newTravelData['amount'],
                                'modelType' => $newTravelData['modelType'],
                                'railway' => $newTravelData['railway'],
                                'auto' => $newTravelData['auto'],
                                'hotel' => $newTravelData['hotel'],
                                'food' => $newTravelData['food'],
                                'internetFax' => $newTravelData['internetFax'],
                                'xeroxPrint' => $newTravelData['xeroxPrint'],
                                'postCourier' => $newTravelData['postCourier'],
                                'totalAmount' => $newTravelData['totalAmount'],
                                'remark' => $newTravelData['remark'],
                                'userId' => $userId,
                                'compId' => $compId,
                                'compBranchId' => $compBranchId,
                                'seniorEmployeeId' => $query->row()->Id,
                                'employeeId' => $employeeId,
                                'financialYear' => $financialYear,
                                'created' => $updated,
                                'createdBy' => $updatedBy
                            ];
                            $this->user_model->saveAry('employee_outstation_travels_data', $employeeNewTravelsData);                            
                        }
                    }
                    
                    if (!empty($result)) {
                        echo json_encode(['status' => true, 'message' => 'Record update successfully']);
                        return;
                    } else {
                        echo json_encode(['status' => false, 'message' => 'Unable to save record']);
                        return;
                    }                    
                }                
            }
        }
        
        public function employeeOutstationTravelsDelete($id)
        {
            if (!empty($id)) {
                $where = ['id' => $id, 'isDeleted' => 1];
                if ($this->db->select('id')->where($where)->get('employee_outstation_travels')->num_rows()) {
                    if ($this->user_model->updateAry('employee_outstation_travels', ['isDeleted' => 0], $where)) {
                        $this->user_model->updateAry('employee_outstation_travels_data', ['isDeleted' => 0], ['employeeTravelId' => $id, 'isDeleted' => 1]);
                        $this->session->set_flashdata(['msg' => 'Record delete successfully.', 'class' => 'success']);
                        return redirect($_SERVER['HTTP_REFERER']);
                    } else {
                        $this->session->set_flashdata(['msg' => 'Unable to delete record.', 'class' => 'danger']);
                        return redirect($_SERVER['HTTP_REFERER']);
                    }
                } else {
                    $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                    return redirect($_SERVER['HTTP_REFERER']);
                }
            } else {
                $this->session->set_flashdata(['msg' => 'Id is missing.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }
        }
        
        public function employeeOutstationTravelsReportDownload($id)
        {
            ini_set('memory_limit', '-1');
            set_time_limit(500);
            
            $employeeId = $this->session->userdata('EmployeeId');            
            $userId = $this->session->userdata('UserId');
            $compId = $this->session->userdata('CompId');
            $compBranchId = $this->session->userdata('CompBranchId');
            $financialYear = $this->session->userdata('financial_year');
            
            $where = ['id' => $id, 'employeeId' => $employeeId, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear, 'isDeleted' => 1];
            $travelsDataWhere = ['employeeTravelId' => $id, 'employeeId' => $employeeId, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear, 'isDeleted' => 1];
            $compProfileWhere = ['user_id' => $userId, 'comp_id' => $compId];
            if ($this->session->userdata('BranchRole')) {
                $Name = get_title('companybranch', ['Id' => $compBranchId], 'BranchName');
                $where['compBranchId'] = $compBranchId;
                $travelsDataWhere['compBranchId'] = $compBranchId;
                $compProfileWhere['CompanyBranchId'] = $compBranchId;
            } else {
                $Name = get_title('inventory_billing', ['id' => $compId], 'company_name');
            }
            
            $logoPath = 'uploads/complogo/' . get_title('comp_profile', $compProfileWhere, 'logo');
            if (!file_exists($logoPath)) {
                $logoPath = 'assets/images/avatar.png';
            }
            
            $TravelsReportQuery = $this->db->where($where)->get('employee_outstation_travels');
            if ($TravelsReportQuery->num_rows()) {
                $travelData = $TravelsReportQuery->row();
                
                $PHPExcel = new PHPExcel();
                                
                // Define styles
                $topHeaderStyleArray = [
                    'font' => [
                        'size' => 11,
                        'color' => ['rgb' => '000000'],
                    ],
                    'borders' => [
                        'allborders' => [
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ];
                $headerStyleArray = [
                    'font' => [
                        'bold' => true,
                        'size' => 11,
                        'color' => ['rgb' => '000000'],
                    ],
                    'borders' => [
                        'allborders' => [
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ];

                $dataStyleArray = [
                    'font' => [
                        'size' => 10,
                    ],
                    'borders' => [
                        'allborders' => [
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ];                
                
                $PHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
                                
                // Add the logo image
                $PHPExcelDrawing = new PHPExcel_Worksheet_Drawing();
                $PHPExcelDrawing->setName('Logo');
                $PHPExcelDrawing->setDescription('Logo');
                $PHPExcelDrawing->setPath($logoPath);
                $PHPExcelDrawing->setHeight(60);
                $PHPExcelDrawing->setCoordinates('R1');
                $PHPExcelDrawing->setWorksheet($PHPExcel->getActiveSheet());                
                $PHPExcel->getActiveSheet()->getStyle('R1:R1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                
                $PHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'Format effective date: ' . date('jS F, Y'))
                    ->mergeCells('A1:R3')
                    ->setCellValue('A4', 'OUTSTATION TRAVEL')                    
                    ->mergeCells('A4:R4')
                    ->setCellValue('A5', 'Employee Name')
                    ->mergeCells('A5:B5')
                    ->setCellValue('C5', get_title('employees', ['Id' => $travelData->employeeId], 'Name'))
                    ->mergeCells('C5:G5')
                    ->setCellValue('A6', 'Designation')
                    ->mergeCells('A6:B6')
                    ->setCellValue('C6', get_title('position', ['Id' => get_title('employees', ['Id' => $travelData->employeeId], 'RoleId')], 'PostName'))
                    ->mergeCells('C6:G6')
                    ->setCellValue('A7', 'Band')
                    ->mergeCells('A7:B7')
                    ->setCellValue('C7', $travelData->band)
                    ->mergeCells('C7:G7')
                    ->setCellValue('A8', 'Location / Place of posting')
                    ->mergeCells('A8:B8')
                    ->setCellValue('C8', $travelData->location . " - " . get_title('state_list', ['id' => $travelData->stateId], 'state'))
                    ->mergeCells('C8:G8')
                    ->setCellValue('H5', 'Month')
                    ->mergeCells('H5:I5')
                    ->setCellValue('J5', $travelData->month)
                    ->mergeCells('J5:R5')
                    ->setCellValue('H6', 'Department')
                    ->mergeCells('H6:I6')
                    ->setCellValue('J6', $travelData->department)
                    ->mergeCells('J6:R6')
                    ->setCellValue('H7', 'Reporting Senior')
                    ->mergeCells('H7:I7')
                    ->setCellValue('J7', $travelData->reportingSenior)
                    ->mergeCells('J7:R7')
                    ->setCellValue('H8', 'Head Quarter')
                    ->mergeCells('H8:I8')
                    ->setCellValue('J8', $travelData->headQuarter)
                    ->mergeCells('J8:R8');                                                

                $PHPExcel->getActiveSheet()->getStyle('A5:G8')->applyFromArray($dataStyleArray);
                $PHPExcel->getActiveSheet()->getStyle('J5:R8')->applyFromArray($dataStyleArray);                
                
                // Set column titles
                $PHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A9', 'DATE')
                    ->mergeCells('A9:A11')
                    ->setCellValue('B9', 'PLACES VISITED')
                    ->mergeCells('B9:C9')
                    ->setCellValue('B10', 'From')
                    ->mergeCells('B10:B11')
                    ->setCellValue('C10', 'To')
                    ->mergeCells('C10:C11')
                    ->setCellValue('D9', 'MODE OF TRAVEL')
                    ->mergeCells('D9:K9')
                    ->setCellValue('D10', 'Bus')
                    ->mergeCells('D10:D11')
                    ->setCellValue('E10', 'Private Own Vehicle')
                    ->mergeCells('E10:I10')
                    ->setCellValue('E11', 'Start KM (A)')
                    ->setCellValue('F11', 'End KM (B)')
                    ->setCellValue('G11', 'Total KM (B - A)')
                    ->setCellValue('H11', 'Amount')
                    ->setCellValue('I11', 'Car/Bike Model Type')
                    ->setCellValue('J10', 'Railway')
                    ->mergeCells('J10:J11')
                    ->setCellValue('K10', 'Auto')
                    ->mergeCells('K10:K11')
                    ->setCellValue('L9', 'OUTSTATION')
                    ->mergeCells('L9:M9')
                    ->setCellValue('L10', 'Hotel Accommodation')
                    ->mergeCells('L10:L11')
                    ->setCellValue('M10', 'Food')
                    ->mergeCells('M10:M11')
                    ->setCellValue('N9', 'OTHER MISC. EXPENSES')
                    ->mergeCells('N9:P9')
                    ->setCellValue('N10', 'Internet/Fax')
                    ->mergeCells('N10:N11')
                    ->setCellValue('O10', 'Xerox/Print')
                    ->mergeCells('O10:O11')
                    ->setCellValue('P10', 'Post/Courier')
                    ->mergeCells('P10:P11')
                    ->setCellValue('Q9', 'TOTAL AMOUNT')
                    ->mergeCells('Q9:Q11')
                    ->setCellValue('R9', 'Remark')
                    ->mergeCells('R9:R11');
                                              
                $PHPExcel->getActiveSheet()->getRowDimension('11')->setRowHeight(30);
                $PHPExcel->getActiveSheet()->getStyle('A9:Q11')->getAlignment()->setWrapText(true);
                
                $TravelDataReportQuery = $this->db->where($travelsDataWhere)->get('employee_outstation_travels_data');
                if ($TravelDataReportQuery->num_rows()) {
                    $rowCount = 12;
                    $totalBus = 0;
                    $totalStartKM = 0;
                    $totalEndKM = 0;
                    $totalTotalKM = 0;
                    $totalAmount = 0;
                    $totalRailway = 0;
                    $totalAuto = 0;
                    $totalHotel = 0;
                    $totalFood = 0;
                    $totalInternetFax = 0;
                    $totalXeroxPrint = 0;
                    $totalPostCourier = 0;
                    $grandTotalAmount = 0;
                    foreach ($TravelDataReportQuery->result() as $key => $travelsData) {
                        $totalBus += $travelsData->bus;
                        $totalStartKM += $travelsData->startKM;
                        $totalEndKM += $travelsData->endKM;
                        $totalTotalKM += $travelsData->totalKM;
                        $totalAmount += $travelsData->amount;
                        $totalRailway += $travelsData->railway;
                        $totalAuto += $travelsData->auto;
                        $totalHotel += $travelsData->hotel;
                        $totalFood += $travelsData->food;
                        $totalInternetFax += $travelsData->internetFax;
                        $totalXeroxPrint += $travelsData->xeroxPrint;
                        $totalPostCourier += $travelsData->postCourier;
                        $grandTotalAmount += $travelsData->totalAmount;
                        
                        $PHPExcel->getActiveSheet()->getStyle('A' . $rowCount . ':R' . $rowCount)->applyFromArray($dataStyleArray);
                        
                        $PHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, date('d/m/Y', strtotime($travelsData->travelDate)));
                        $PHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $travelsData->from);
                        $PHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $travelsData->to);
                        $PHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $travelsData->bus);
                        $PHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $travelsData->startKM);
                        $PHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $travelsData->endKM);
                        $PHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $travelsData->totalKM);
                        $PHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $travelsData->amount);
                        $PHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $travelsData->modelType);
                        $PHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, $travelsData->railway);
                        $PHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, $travelsData->auto);
                        $PHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, $travelsData->hotel);
                        $PHPExcel->getActiveSheet()->SetCellValue('M' . $rowCount, $travelsData->food);
                        $PHPExcel->getActiveSheet()->SetCellValue('N' . $rowCount, $travelsData->internetFax);
                        $PHPExcel->getActiveSheet()->SetCellValue('O' . $rowCount, $travelsData->xeroxPrint);
                        $PHPExcel->getActiveSheet()->SetCellValue('P' . $rowCount, $travelsData->postCourier);
                        $PHPExcel->getActiveSheet()->SetCellValue('Q' . $rowCount, $travelsData->totalAmount);
                        $PHPExcel->getActiveSheet()->SetCellValue('R' . $rowCount, $travelsData->remark);
                        $rowCount++;
                    }
                    $PHPExcel->getActiveSheet()->getStyle('A' . $rowCount . ':R' . $rowCount)->applyFromArray($dataStyleArray);
                    
                    $rowCount++;
                    $PHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, 'Total');
                    $PHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $rowCount . ':C' . $rowCount);
                    $PHPExcel->getActiveSheet()->getStyle('A' . $rowCount . ':C' . $rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    
                    $PHPExcel->getActiveSheet()->setCellValue('D' . $rowCount, $totalBus);
                    $PHPExcel->getActiveSheet()->setCellValue('E' . $rowCount, $totalStartKM);
                    $PHPExcel->getActiveSheet()->setCellValue('F' . $rowCount, $totalEndKM);
                    $PHPExcel->getActiveSheet()->setCellValue('G' . $rowCount, $totalTotalKM);
                    $PHPExcel->getActiveSheet()->setCellValue('H' . $rowCount, $totalAmount);
                    $PHPExcel->getActiveSheet()->setCellValue('I' . $rowCount, '');
                    $PHPExcel->getActiveSheet()->setCellValue('J' . $rowCount, $totalRailway);
                    $PHPExcel->getActiveSheet()->setCellValue('K' . $rowCount, $totalAuto);
                    $PHPExcel->getActiveSheet()->setCellValue('L' . $rowCount, $totalHotel);
                    $PHPExcel->getActiveSheet()->setCellValue('M' . $rowCount, $totalFood);
                    $PHPExcel->getActiveSheet()->setCellValue('N' . $rowCount, $totalInternetFax);
                    $PHPExcel->getActiveSheet()->setCellValue('O' . $rowCount, $totalXeroxPrint);
                    $PHPExcel->getActiveSheet()->setCellValue('P' . $rowCount, $totalPostCourier);
                    $PHPExcel->getActiveSheet()->setCellValue('Q' . $rowCount, $grandTotalAmount);
                    
                    $PHPExcel->getActiveSheet()->getStyle('A' . $rowCount)->getFont()->setBold(true);
                    $PHPExcel->getActiveSheet()->getStyle('R' . $rowCount)->getFont()->setBold(true);
                    $PHPExcel->getActiveSheet()->getStyle('A' . $rowCount . ':R' . $rowCount)->applyFromArray($headerStyleArray);
                    
                    $rowCount++;
                    $PHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $rowCount . ':R' . $rowCount);
                    $PHPExcel->getActiveSheet()->getStyle('A' . $rowCount . ':R' . $rowCount)->applyFromArray($headerStyleArray);
                    
                    $rowCount++;
                    $PHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, 'Sd/-');
                    $PHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $rowCount . ':B' . $rowCount);
                    $PHPExcel->getActiveSheet()->setCellValue('E' . $rowCount, 'Sd/-');
                    $PHPExcel->setActiveSheetIndex(0)->mergeCells('E' . $rowCount . ':F' . $rowCount);
                    $PHPExcel->getActiveSheet()->setCellValue('I' . $rowCount, 'Sd/-');
                    $PHPExcel->setActiveSheetIndex(0)->mergeCells('I' . $rowCount . ':J' . $rowCount);
                    $PHPExcel->getActiveSheet()->setCellValue('M' . $rowCount, 'Sd/-');
                    $PHPExcel->setActiveSheetIndex(0)->mergeCells('M' . $rowCount . ':N' . $rowCount);
                    $PHPExcel->getActiveSheet()->getStyle('A' . $rowCount)->getFont()->setBold(true);
                    $PHPExcel->getActiveSheet()->getStyle('E' . $rowCount)->getFont()->setBold(true);
                    $PHPExcel->getActiveSheet()->getStyle('I' . $rowCount)->getFont()->setBold(true);
                    $PHPExcel->getActiveSheet()->getStyle('M' . $rowCount)->getFont()->setBold(true);
                    $PHPExcel->getActiveSheet()->getStyle('A' . $rowCount . ':R' . $rowCount)->applyFromArray($topHeaderStyleArray);
                    
                    $rowCount++;
                    $previousRowNumber = ($rowCount - 1);
                    $PHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, 'Employee');
                    $PHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $rowCount . ':B' . $rowCount);
                    $PHPExcel->getActiveSheet()->setCellValue('E' . $rowCount, 'Reporting Senior');
                    $PHPExcel->setActiveSheetIndex(0)->mergeCells('E' . $rowCount . ':F' . $rowCount);
                    $PHPExcel->getActiveSheet()->setCellValue('I' . $rowCount, 'Accounts Head');
                    $PHPExcel->setActiveSheetIndex(0)->mergeCells('I' . $rowCount . ':J' . $rowCount);
                    $PHPExcel->getActiveSheet()->setCellValue('M' . $rowCount, 'Director');
                    $PHPExcel->setActiveSheetIndex(0)->mergeCells('M' . $rowCount . ':N' . $rowCount);
                    $PHPExcel->getActiveSheet()->getStyle('A' . $rowCount)->getFont()->setBold(true);
                    $PHPExcel->getActiveSheet()->getStyle('E' . $rowCount)->getFont()->setBold(true);
                    $PHPExcel->getActiveSheet()->getStyle('I' . $rowCount)->getFont()->setBold(true);
                    $PHPExcel->getActiveSheet()->getStyle('M' . $rowCount)->getFont()->setBold(true);
                    $PHPExcel->getActiveSheet()->getStyle('A' . $rowCount . ':R' . $rowCount)->applyFromArray($topHeaderStyleArray);
                    $PHPExcel->setActiveSheetIndex(0)->mergeCells('C' . $previousRowNumber . ':D' . $rowCount);
                    $PHPExcel->setActiveSheetIndex(0)->mergeCells('G' . $previousRowNumber . ':H' . $rowCount);
                    $PHPExcel->setActiveSheetIndex(0)->mergeCells('K' . $previousRowNumber . ':L' . $rowCount);
                    $PHPExcel->setActiveSheetIndex(0)->mergeCells('O' . $previousRowNumber . ':R' . $rowCount);
                }                
                
                // Set column widths
                $PHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
                $PHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(20);
                $PHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                $PHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
                $PHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
                $PHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
                $PHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
                $PHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
                $PHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(12);
                $PHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(10);
                $PHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
                $PHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(16);
                $PHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(10);
                $PHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(12);
                $PHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(12);
                $PHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(12);
                $PHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(10);
                $PHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(20);
                
                // Apply styles
                $PHPExcel->getActiveSheet()->getStyle('A1:R3')->applyFromArray($headerStyleArray);
                $PHPExcel->getActiveSheet()->getStyle('A5:B8')->applyFromArray($topHeaderStyleArray);
                $PHPExcel->getActiveSheet()->getStyle('H5:I8')->applyFromArray($topHeaderStyleArray);
                $PHPExcel->getActiveSheet()->getStyle('A9:R11')->applyFromArray($headerStyleArray);
                $PHPExcel->getActiveSheet()->getStyle('A4:R4')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14,
                        'color' => ['rgb' => '000000'],
                    ],
                    'borders' => [
                        'allborders' => [
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);
                $PHPExcel->getActiveSheet()->getStyle('A4:R4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $PHPExcel->getActiveSheet()->getStyle('A5:B11')->getFill()->applyFromArray([
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'startcolor' => ['rgb' => 'FFFF00'],
                ]);                
                $PHPExcel->getActiveSheet()->getStyle('A1:A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $PHPExcel->getActiveSheet()->getStyle('D1:R1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $PHPExcel->getActiveSheet()->getStyle('A9:R9')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $PHPExcel->getActiveSheet()->getStyle('E10:I10')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $PHPExcel->getActiveSheet()->getStyle('A9:R11')->applyFromArray([
                    'alignment' => [
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                    ]
                ]);
                
                // Set background colors
                $PHPExcel->getActiveSheet()->getStyle('H5:I11')->getFill()->applyFromArray([
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'startcolor' => ['rgb' => 'FFFF00'],
                ]);
                $PHPExcel->getActiveSheet()->getStyle('C9:G11')->getFill()->applyFromArray([
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'startcolor' => ['rgb' => 'FFFF00'],
                ]);
                $PHPExcel->getActiveSheet()->getStyle('J9:R11')->getFill()->applyFromArray([
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'startcolor' => ['rgb' => 'FFFF00'],
                ]);

                $PHPExcel->getActiveSheet()->setTitle('OUTSTATION_TRAVEL');
                
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="' . str_replace(' ', '_', $Name) . '_OUTSTATION_TRAVEL_REPORT.xlsx"');
                header('Cache-Control: max-age=0');
                PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007')->save('php://output');
            } else {
                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }
        }

        public function seniorEmployeeOutstationTravels()
        {
            $employeeId = $this->session->userdata('EmployeeId');
            $userId = $this->session->userdata('UserId');
            $compId = $this->session->userdata('CompId');
            $compBranchId = $this->session->userdata('CompBranchId');
            
            $where = ['Id' => $employeeId, 'UserId' => $userId, 'CompId' => $compId, 'IsDeleted' => 1];
            $employeePermissionWhere = ['EmployeeId' => $employeeId, 'UserId' => $userId, 'CompId' => $compId];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $compBranchId;
                $employeePermissionWhere['CompanyBranchId'] = $compBranchId;                
            }
            
            $data['EmployeeInfo'] = $this->db->where($where)->get('employees')->row();
            $data['EmployeePermission'] = $this->db->where($employeePermissionWhere)->get('employee_permission')->row();
            $data['statesList'] = $this->db->select('id, state, code')->get('state_list')->result();
            $salesmanEmployeeIds = json_decode(get_title('employees', ['Id' => $employeeId, 'IsDeleted' => 1], 'WarehouseSalesman'), true) ?: [];
            $RoleId = get_title('position', ['PostName' => 'Salesman'], 'Id');
            $data['employees'] = $this->db->select('Id, Name')->where('RoleId', $RoleId)->where_in('Id', $salesmanEmployeeIds)->get('employees')->result();
            $data['page'] = "seniorEmployeeOutstationTravels";
            return $this->load->view('inventory_sep/Employee/Employee', $data);
        }
        
        public function saveSeniorEmployeeOutstationTravels()
        {
            if ($this->input->is_ajax_request()) {
                $arrayTravelData = [];
                foreach ($this->input->post('travels') as $key => $row) {
                    if ($row['employeeId'] == '' || $row['travelDate'] == '' || $row['from'] == '' || $row['to'] == '') {
                        $arrayTravelData[] = $row;
                    }
                }

                if (!empty($arrayTravelData)) {
                    echo json_encode(['status' => false, 'message' => 'Please enter travel required fields.']);
                    return;
                }
                
                $this->form_validation->set_rules('date', 'Date', 'trim|required');
                $this->form_validation->set_rules('month', 'Month', 'trim|required');
                $this->form_validation->set_rules('stateId', 'State', 'trim|required');
                $this->form_validation->set_rules('grandTotal', 'Total', 'trim|numeric|required');
                if ($this->form_validation->run() == false) {
                    echo json_encode(['status' => false, 'message' => validation_errors()]);
                    return;
                } else {
                    $employeeId = $this->session->userdata('EmployeeId');
                    $userId = $this->session->userdata('UserId');
                    $compId = $this->session->userdata('CompId');
                    $compBranchId = $this->session->userdata('CompBranchId');
                    $created = date('d-m-Y h:i:s A');
                    $createdBy = get_title('employees', ['Id' => $employeeId], 'Name');
                    
                    $compProfileWhere = ['user_id' => $userId, 'comp_id' => $compId];
                    if ($this->session->userdata('BranchRole')) {
                        $compProfileWhere['CompanyBranchId'] = $compBranchId;
                    }
                    $financialYear = get_title('comp_profile', $compProfileWhere, 'financial_year');
                                        
                    $employeeTravels = [];
                    foreach ($this->input->post('travels') as $key => $travelsData) {
                        if (!empty($travelsData['employeeId'])) {
                            $SalesmanId = $travelsData['employeeId'];
                            if (isset($employeeTravels[$SalesmanId])) {
                                $employeeTravels[$SalesmanId]['grandTotal'] += $travelsData['totalAmount'];
                            } else {
                                $employeeTravels[$SalesmanId] = [
                                    'date' => $this->input->post('date'),
                                    'employeeId' => $SalesmanId,
                                    'location' => $this->input->post('location'),
                                    'band' => $this->input->post('band'),
                                    'stateId' => $this->input->post('stateId'),
                                    'month' => $this->input->post('month'),
                                    'department' => $this->input->post('department'),
                                    'remark' => $this->input->post('remark'),                                    
                                    'grandTotal' => $travelsData['totalAmount'],
                                    'userId' => $userId,
                                    'compId' => $compId,
                                    'compBranchId' => $compBranchId,
                                    'financialYear' => $financialYear,
                                    'created' => $created,
                                    'createdBy' => $createdBy
                                ];
                            }
                        }
                    }
                    
                    $employeeTravelsIds = [];
                    foreach ($employeeTravels as $SalesmanId => $salesmanData) {
                        $result = $this->user_model->saveAry('employee_outstation_travels', $salesmanData);
                        $employeeTravelsIds[$SalesmanId] = $result;
                    }
                    
                    foreach ($this->input->post('travels') as $key => $travalData) {
                        if (!empty($travalData['travelDate']) && !empty($travalData['from']) && !empty($travalData['to'])) {
                            $employeeTravelsData = [
                                'date' => $this->input->post('date'),
                                'employeeTravelId' => $employeeTravelsIds[$travalData['employeeId']],
                                'travelDate' => $travalData['travelDate'],
                                'from' => $travalData['from'],
                                'to' => $travalData['to'],
                                'bus' => $travalData['bus'],
                                'startKM' => $travalData['startKM'],
                                'endKM' => $travalData['endKM'],
                                'totalKM' => $travalData['totalKM'],
                                'amount' => $travalData['amount'],
                                'modelType' => $travalData['modelType'],
                                'railway' => $travalData['railway'],
                                'auto' => $travalData['auto'],
                                'hotel' => $travalData['hotel'],
                                'food' => $travalData['food'],
                                'internetFax' => $travalData['internetFax'],
                                'xeroxPrint' => $travalData['xeroxPrint'],
                                'postCourier' => $travalData['postCourier'],
                                'totalAmount' => $travalData['totalAmount'],
                                'remark' => $travalData['remark'],
                                'userId' => $userId,
                                'compId' => $compId,
                                'compBranchId' => $compBranchId,
                                'seniorEmployeeId' => $employeeId,
                                'employeeId' => $travalData['employeeId'],
                                'financialYear' => $financialYear,
                                'created' => $created,
                                'createdBy' => $createdBy
                            ];
                            $this->user_model->saveAry('employee_outstation_travels_data', $employeeTravelsData);
                        }
                    }
                    
                    if (!empty($result)) {
                        echo json_encode(['status' => true, 'message' => 'Record save successfully']);
                        return;
                    } else {
                        echo json_encode(['status' => false, 'message' => 'Unable to save record']);
                        return;
                    }                    
                }                
            }
        }
        
        public function seniorEmployeeOutstationTravelsReport()
        {
            $employeeId = $this->session->userdata('EmployeeId');
            $userId = $this->session->userdata('UserId');
            $compId = $this->session->userdata('CompId');
            $compBranchId = $this->session->userdata('CompBranchId');
            $financialYear = $this->session->userdata('financial_year');
            if ($this->input->get('from') && $this->input->get('to')) {
                $from = $this->input->get('from');
                $to = $this->input->get('to');
            } else {
                $from = date('Y-m-d');
                $to = date('Y-m-d');
            }
            
            $where = ['Id' => $employeeId, 'UserId' => $userId, 'CompId' => $compId, 'IsDeleted' => 1];
            $employeePermissionWhere = ['EmployeeId' => $employeeId, 'UserId' => $userId, 'CompId' => $compId];
            $employeeTravelsWhere = ['date >= ' => $from, 'date <= ' => $to, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $compBranchId;
                $employeePermissionWhere['CompanyBranchId'] = $compBranchId;                
                $employeeTravelsWhere['compBranchId'] = $compBranchId;                
            }
            
            if ($this->input->get('employeeId') != "" && $this->input->get('employeeId') != 'All') {
                $salesmanIds = $this->input->get('employeeId');
            } else {
                $salesmanIds = json_decode(get_title('employees', ['Id' => $employeeId, 'IsDeleted' => 1], 'WarehouseSalesman'), true) ?: [];
            }
            
            $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
            $data['EmployeeInfo'] = $this->db->where($where)->get('employees')->row();
            $data['EmployeePermission'] = $this->db->where($employeePermissionWhere)->get('employee_permission')->row();
            $employeeSalesmanIds = json_decode(get_title('employees', ['Id' => $employeeId, 'IsDeleted' => 1], 'WarehouseSalesman'), true) ?: [];
            $RoleId = get_title('position', ['PostName' => 'Salesman'], 'Id');
            $data['salesmans'] = $this->db->select('Id, Name')->where('RoleId', $RoleId)->where_in('Id', $employeeSalesmanIds)->get('employees')->result();            
            $data['employeeTravels'] = $this->db->select('id, date, month, SUM(grandTotal) AS grandTotal')->where($employeeTravelsWhere)->where_in('employeeId', $salesmanIds)->group_by('date')->order_by('id', 'DESC')->get('employee_outstation_travels')->result();
            $data['page'] = "seniorEmployeeOutstationTravelsReport";
            return $this->load->view('inventory_sep/Employee/Employee', $data);
        }
        
        public function seniorEmployeeOutstationTravelsInfo()
        {
            $employeeId = $this->session->userdata('EmployeeId');
            $userId = $this->session->userdata('UserId');
            $compId = $this->session->userdata('CompId');
            $compBranchId = $this->session->userdata('CompBranchId');
            $financialYear = $this->session->userdata('financial_year');
            if ($this->input->get('from') && $this->input->get('to')) {
                $from = $this->input->get('from');
                $to = $this->input->get('to');
            } else {
                $from = date('Y-m-d');
                $to = date('Y-m-d');
            }
            
            $employeeTravelsWhere = ['date >= ' => $from, 'date <= ' => $to, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear];
            $employeeTravelsDataWhere = ['date >= ' => $from, 'date <= ' => $to, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear];
            if ($this->session->userdata('BranchRole')) {
                $employeeTravelsWhere['compBranchId'] = $compBranchId;
                $employeeTravelsDataWhere['compBranchId'] = $compBranchId;
            }
            if ($this->input->get('employeeId') != "" && $this->input->get('employeeId') != 'All') {
                $salesmanIds = $this->input->get('employeeId');
            } else {
                $salesmanIds = json_decode(get_title('employees', ['Id' => $employeeId, 'IsDeleted' => 1], 'WarehouseSalesman'), true) ?: [];
            }            

            $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
            $data['employeeTravelsInfo'] = $this->db->where($employeeTravelsWhere)->where_in('employeeId', $salesmanIds)->group_by('date')->get('employee_outstation_travels')->row();
            $data['employeeTravelsData'] = $this->db->where($employeeTravelsDataWhere)->where_in('employeeId', $salesmanIds)->get('employee_outstation_travels_data')->result();
            return $this->load->view('inventory_sep/Employee/seniorEmployeeOutstationTravelsInfo', $data);
        }
        
        public function seniorEmployeeOutstationTravelsEdit()
        {
            $employeeId = $this->session->userdata('EmployeeId');
            $userId = $this->session->userdata('UserId');
            $compId = $this->session->userdata('CompId');
            $compBranchId = $this->session->userdata('CompBranchId');
            $financialYear = $this->session->userdata('financial_year');
            if ($this->input->get('from') && $this->input->get('to')) {
                $from = $this->input->get('from');
                $to = $this->input->get('to');
            }

            $where = ['Id' => $employeeId, 'UserId' => $userId, 'CompId' => $compId, 'IsDeleted' => 1];
            $employeePermissionWhere = ['EmployeeId' => $employeeId, 'UserId' => $userId, 'CompId' => $compId];
            $employeeTravelsWhere = ['date >= ' => $from, 'date <= ' => $to, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear];
            if ($this->session->userdata('BranchRole')) {
                $where['CompanyBranchId'] = $compBranchId;
                $employeePermissionWhere['CompanyBranchId'] = $compBranchId;
                $employeeTravelsWhere['compBranchId'] = $compBranchId;
            }
            if ($this->input->get('department') != '') {
                $employeeTravelsWhere['department'] = $this->input->get('department');
            }
            if ($this->input->get('band') != '') {
                $employeeTravelsWhere['band'] = $this->input->get('band');
            }
            if ($this->input->get('stateId') != '') {
                $employeeTravelsWhere['stateId'] = $this->input->get('stateId');
            }
            if ($this->input->get('employeeId') != 'All') {
                $salesmanIds = $this->input->get('employeeId');
            } else {
                $salesmanIds = json_decode(get_title('employees', ['Id' => $employeeId, 'IsDeleted' => 1], 'WarehouseSalesman'), true) ?: [];
            }
            
            $data['EmployeeInfo'] = $this->db->where($where)->get('employees')->row();
            $data['EmployeePermission'] = $this->db->where($employeePermissionWhere)->where_in('employeeId', $salesmanIds)->get('employee_permission')->row();
            $employeeOutstationTravelsQuery = $this->db->where($employeeTravelsWhere)->get('employee_outstation_travels');
            if ($employeeOutstationTravelsQuery->num_rows()) {
                $data['employeeTravelsInfo'] = $employeeOutstationTravelsQuery->row();
                $data['from'] = $from;
                $data['to'] = $to;
                $data['employeeId'] = $this->input->get('employeeId');
                $salesmanEmployeeIds = json_decode(get_title('employees', ['Id' => $employeeId, 'IsDeleted' => 1], 'WarehouseSalesman'), true) ?: [];
                $RoleId = get_title('position', ['PostName' => 'Salesman'], 'Id');
                $data['employees'] = $this->db->select('Id, Name')->where('RoleId', $RoleId)->where_in('Id', $salesmanEmployeeIds)->get('employees')->result();
                $data['statesList'] = $this->db->select('id, state, code')->get('state_list')->result();
                $data['page'] = "seniorEmployeeOutstationTravelsEdit";
                return $this->load->view('inventory_sep/Employee/Employee', $data);
            } else {
                return redirect($_SERVER['HTTP_REFERER']);
            }
        }
        
        public function getSeniorEmployeeOutstationTravelsData()
        {
            if ($_SERVER['REQUEST_METHOD'] === 'GET') {
                $employeeId = $this->session->userdata('EmployeeId');
                $userId = $this->session->userdata('UserId');
                $compId = $this->session->userdata('CompId');
                $compBranchId = $this->session->userdata('CompBranchId');
                $financialYear = $this->session->userdata('financial_year');
                $from = $this->input->get('from');
                $to = $this->input->get('to');
                
                $TravelsDataWhere = ['date >= ' => $from, 'date <= ' => $to, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear];
                if ($this->session->userdata('BranchRole')) {
                    $TravelsDataWhere['compBranchId'] = $compBranchId;
                }
                if ($this->input->get('employeeId') != 'All') {
                    $salesmanIds = $this->input->get('employeeId');
                } else {
                    $salesmanIds = json_decode(get_title('employees', ['Id' => $employeeId, 'IsDeleted' => 1], 'WarehouseSalesman'), true) ?: [];
                }
                
                $employeeTravelsDataQuery = $this->db->where($TravelsDataWhere)->where_in('employeeId', $salesmanIds)->get('employee_outstation_travels_data');
                if ($employeeTravelsDataQuery->num_rows()) {
                    echo json_encode(array('status' => true, "data" => $employeeTravelsDataQuery->result()));
                } else {
                    echo json_encode(array('status' => false, "data" => "No data found."));
                }
            }
        }
        
        public function deleteSeniorEmployeeOutstationTravelsData()
        {
            if ($this->input->is_ajax_request()) {
                if (!empty($this->input->post('rowId'))) {
                    $rowId = $this->input->post('rowId');
                    $employeeId = $this->session->userdata('EmployeeId');
                    $userId = $this->session->userdata('UserId');
                    $compId = $this->session->userdata('CompId');
                    $compBranchId = $this->session->userdata('CompBranchId');
                    
                    $where = ['userId' => $userId, 'compId' => $compId, 'isDeleted' => 1];
                    $travelDataWhere = ['id' => $rowId, 'userId' => $userId, 'compId' => $compId, 'isDeleted' => 1];
                    if ($this->session->userdata('BranchRole')) {
                        $where['compBranchId'] = $compBranchId;
                        $travelDataWhere['compBranchId'] = $compBranchId;
                    }
                    
                    $salesmanIds = json_decode(get_title('employees', ['Id' => $employeeId, 'IsDeleted' => 1], 'WarehouseSalesman'), true) ?: [];
                    $employeeTravelsData = $this->db->select('employeeTravelId, totalAmount')->where($travelDataWhere)->where_in('employeeId', $salesmanIds)->get('employee_outstation_travels_data')->row();
                    $where['id'] = $employeeTravelsData->employeeTravelId;
                    $currentGrandTotal = $this->db->select('grandTotal')->where($where)->where_in('employeeId', $salesmanIds)->get('employee_outstation_travels')->row('grandTotal');
                    $newGrandTotal = $currentGrandTotal - $employeeTravelsData->totalAmount;
                    $this->db->where($where)->where_in('employeeId', $salesmanIds)->update('employee_outstation_travels', ['grandTotal' => $newGrandTotal]);
                    
                    $result = $this->db->where($travelDataWhere)->where_in('employeeId', $salesmanIds)->update('employee_outstation_travels_data', ['isDeleted' => 0]);
                    if ($result) {
                        echo json_encode(array('status' => true, "message" => "Record successfully deleted."));
                        return;
                    } else {
                        echo json_encode(array('status' => false, "message" => "Somethings went wrong, try again."));
                        return;
                    }
                } else {
                    echo json_encode(array('status' => false, "message" => "Id not found."));
                    return;
                }
            }
        }
        
        public function updateSeniorEmployeeOutstationTravels()
        {
            if ($this->input->is_ajax_request()) {
                $arrayTravalData = [];
                foreach ($this->input->post('travels') as $key => $row) {
                    if ($row['travelDate'] == '' || $row['from'] == '' || $row['to'] == '') {
                        $arrayTravalData[] = $row;
                    }
                }

                if (!empty($arrayTravalData)) {
                    echo json_encode(['status' => false, 'message' => 'Please enter travel required fields.']);
                    return;
                }                
                
                $this->form_validation->set_rules('date', 'Date', 'trim|required');
                $this->form_validation->set_rules('month', 'Month', 'trim|required');
                $this->form_validation->set_rules('stateId', 'State', 'trim|required');
                if ($this->form_validation->run() == false) {
                    echo json_encode(['status' => false, 'message' => validation_errors()]);
                    return;
                } else {
                    $from = $this->input->post('from');
                    $to = $this->input->post('to');
                    $employeeId = $this->session->userdata('EmployeeId');
                    $userId = $this->session->userdata('UserId');
                    $compId = $this->session->userdata('CompId');
                    $compBranchId = $this->session->userdata('CompBranchId');
                    $updated = date('d-m-Y h:i:s A');
                    $updatedBy = get_title('employees', ['Id' => $employeeId], 'Name');
                    
                    $updateWhere = ['date >= ' => $from, 'date <= ' => $to, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId];
                    $updateTravelDataWhere = ['date >= ' => $from, 'date <= ' => $to, 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId];
                    $compProfileWhere = ['user_id' => $userId, 'comp_id' => $compId];
                    if ($this->session->userdata('BranchRole')) {
                        $compProfileWhere['CompanyBranchId'] = $compBranchId;
                        $updateWhere['compBranchId'] = $compBranchId;
                        $updateTravelDataWhere['compBranchId'] = $compBranchId;
                    }
                    $financialYear = get_title('comp_profile', $compProfileWhere, 'financial_year');

                    if ($this->input->post('employeeId') != 'All') {
                        $salesmanIds = $this->input->post('employeeId');
                    } else {
                        $salesmanIds = json_decode(get_title('employees', ['Id' => $employeeId, 'IsDeleted' => 1], 'WarehouseSalesman'), true) ?: [];
                    }
                    
                    $employeeTravels = [
                        'date' => $this->input->post('date'),
                        'location' => $this->input->post('location'),
                        'band' => $this->input->post('band'),
                        'stateId' => $this->input->post('stateId'),
                        'month' => $this->input->post('month'),
                        'department' => $this->input->post('department'),
                        'headQuarter' => $this->input->post('headQuarter'),
                        'remark' => $this->input->post('remark'),
                        'updated' => $updated,
                        'updatedBy' => $updatedBy,
                    ];                    
                    
                    $result = $this->db->where($updateWhere)->where_in('employeeId', $salesmanIds)->update('employee_outstation_travels', $employeeTravels);
                    
                    foreach ($this->input->post('travels') as $key => $travelData) {
                        if (!empty($travelData['travelDate']) && !empty($travelData['from']) && !empty($travelData['to'])) {
                            $employeeTravelsData = [
                                'date' => $this->input->post('date'),
                                'travelDate' => $travelData['travelDate'],
                                'from' => $travelData['from'],
                                'to' => $travelData['to'],
                                'bus' => $travelData['bus'],
                                'startKM' => $travelData['startKM'],
                                'endKM' => $travelData['endKM'],
                                'totalKM' => $travelData['totalKM'],
                                'amount' => $travelData['amount'],
                                'modelType' => $travelData['modelType'],
                                'railway' => $travelData['railway'],
                                'auto' => $travelData['auto'],
                                'hotel' => $travelData['hotel'],
                                'food' => $travelData['food'],
                                'internetFax' => $travelData['internetFax'],
                                'xeroxPrint' => $travelData['xeroxPrint'],
                                'postCourier' => $travelData['postCourier'],
                                'totalAmount' => $travelData['totalAmount'],
                                'remark' => $travelData['remark'],
                                'updated' => $updated,
                                'updatedBy' => $updatedBy
                            ];
                            
                            $updateTravelDataWhere['id'] = $travelData['rowId'];
                            $this->db->where($updateTravelDataWhere)->where_in('employeeId', $salesmanIds)->update('employee_outstation_travels_data', $employeeTravelsData);
                        }
                    }                    

                    foreach ($this->input->post('newTravels') as $key => $newTravelData) {
                        if (!empty($newTravelData['travelDate']) && !empty($newTravelData['from']) && !empty($newTravelData['to'])) {
                            $employeeNewTravelsData = [
                                'date' => $this->input->post('date'),
                                'employeeTravelId' => $this->db->select('id')->where(['date >= ' => $from, 'date <= ' => $to, 'employeeId' => $newTravelData['employeeId']])->order_by('id', 'DESC')->get('employee_outstation_travels')->row('id'),
                                'travelDate' => $newTravelData['travelDate'],
                                'from' => $newTravelData['from'],
                                'to' => $newTravelData['to'],
                                'bus' => $newTravelData['bus'],
                                'startKM' => $newTravelData['startKM'],
                                'endKM' => $newTravelData['endKM'],
                                'totalKM' => $newTravelData['totalKM'],
                                'amount' => $newTravelData['amount'],
                                'modelType' => $newTravelData['modelType'],
                                'railway' => $newTravelData['railway'],
                                'auto' => $newTravelData['auto'],
                                'hotel' => $newTravelData['hotel'],
                                'food' => $newTravelData['food'],
                                'internetFax' => $newTravelData['internetFax'],
                                'xeroxPrint' => $newTravelData['xeroxPrint'],
                                'postCourier' => $newTravelData['postCourier'],
                                'totalAmount' => $newTravelData['totalAmount'],
                                'remark' => $newTravelData['remark'],
                                'userId' => $userId,
                                'compId' => $compId,
                                'compBranchId' => $compBranchId,
                                'seniorEmployeeId' => $employeeId,
                                'employeeId' => $newTravelData['employeeId'],
                                'financialYear' => $financialYear,
                                'created' => $updated,
                                'createdBy' => $updatedBy
                            ];
                            $this->user_model->saveAry('employee_outstation_travels_data', $employeeNewTravelsData);                            
                        }
                    }
                    
                    if (!empty($result)) {
                        echo json_encode(['status' => true, 'message' => 'Record update successfully']);
                        return;
                    } else {
                        echo json_encode(['status' => false, 'message' => 'Unable to save record']);
                        return;
                    }                    
                }                
            }
        }
        
        public function seniorEmployeeOutstationTravelsDelete()
        {
            $employeeId = $this->session->userdata('EmployeeId');
            $userId = $this->session->userdata('UserId');
            $compId = $this->session->userdata('CompId');
            $compBranchId = $this->session->userdata('CompBranchId');
            $financialYear = $this->session->userdata('financial_year');
            
            $where = ['date >= ' => $this->input->get('from'), 'date <= ' => $this->input->get('to'), 'isDeleted' => 1, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear];
            if ($this->session->userdata('BranchRole')) {
                $where['compBranchId'] = $compBranchId;
            }
            if ($this->input->get('employeeId') != "" && $this->input->get('employeeId') != 'All') {
                $salesmanIds = $this->input->get('employeeId');
            } else {
                $salesmanIds = json_decode(get_title('employees', ['Id' => $employeeId, 'IsDeleted' => 1], 'WarehouseSalesman'), true) ?: [];
            }
            if ($this->db->select('id')->where($where)->where_in('employeeId', $salesmanIds)->get('employee_outstation_travels')->num_rows()) {
                if ($this->db->where($where)->where_in('employeeId', $salesmanIds)->update('employee_outstation_travels', ['isDeleted' => 0])) {
                    $this->db->where($where)->where_in('employeeId', $salesmanIds)->update('employee_outstation_travels_data', ['isDeleted' => 0]);
                    $this->session->set_flashdata(['msg' => 'Record delete successfully.', 'class' => 'success']);
                    return redirect($_SERVER['HTTP_REFERER']);
                } else {
                    $this->session->set_flashdata(['msg' => 'Unable to delete record.', 'class' => 'danger']);
                    return redirect($_SERVER['HTTP_REFERER']);
                }
            } else {
                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }
        }
        
        public function seniorEmployeeOutstationTravelsReportDownload()
        {
            ini_set('memory_limit', '-1');
            set_time_limit(500);
            
            $from = $this->input->get('from');
            $to = $this->input->get('to');
            $employeeId = $this->input->get('employeeId');
            $userId = $this->session->userdata('UserId');
            $compId = $this->session->userdata('CompId');
            $compBranchId = $this->session->userdata('CompBranchId');
            $financialYear = $this->session->userdata('financial_year');
            
            $where = ['date >= ' => $from, 'date <= ' => $to, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear, 'isDeleted' => 1];
            $travelsDataWhere = ['date >= ' => $from, 'date <= ' => $to, 'userId' => $userId, 'compId' => $compId, 'financialYear' => $financialYear, 'isDeleted' => 1];
            if ($employeeId != "" && $employeeId != 'All') {
                $salesmanIds = $employeeId;
            } else {
                $salesmanIds = json_decode(get_title('employees', ['Id' => $this->session->userdata('EmployeeId'), 'IsDeleted' => 1], 'WarehouseSalesman'), true) ?: [];
            }            
            $compProfileWhere = ['user_id' => $userId, 'comp_id' => $compId];
            if ($this->session->userdata('BranchRole')) {
                $Name = get_title('companybranch', ['Id' => $compBranchId], 'BranchName');
                $where['compBranchId'] = $compBranchId;
                $travelsDataWhere['compBranchId'] = $compBranchId;
                $compProfileWhere['CompanyBranchId'] = $compBranchId;
            } else {
                $Name = get_title('inventory_billing', ['id' => $compId], 'company_name');
            }
            
            $logoPath = 'uploads/complogo/' . get_title('comp_profile', $compProfileWhere, 'logo');
            if (!file_exists($logoPath)) {
                $logoPath = 'assets/images/avatar.png';
            }
            
            $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode, 'ONLY_FULL_GROUP_BY', ''));");
            $TravelsReportQuery = $this->db->where($where)->where_in('employeeId', $salesmanIds)->group_by('date')->get('employee_outstation_travels');
            if ($TravelsReportQuery->num_rows()) {
                $travelData = $TravelsReportQuery->row();
                
                $PHPExcel = new PHPExcel();
                                
                // Define styles
                $topHeaderStyleArray = [
                    'font' => [
                        'size' => 11,
                        'color' => ['rgb' => '000000'],
                    ],
                    'borders' => [
                        'allborders' => [
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ];
                $headerStyleArray = [
                    'font' => [
                        'bold' => true,
                        'size' => 11,
                        'color' => ['rgb' => '000000'],
                    ],
                    'borders' => [
                        'allborders' => [
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ];

                $dataStyleArray = [
                    'font' => [
                        'size' => 10,
                    ],
                    'borders' => [
                        'allborders' => [
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ];                
                
                $PHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
                                
                // Add the logo image
                $PHPExcelDrawing = new PHPExcel_Worksheet_Drawing();
                $PHPExcelDrawing->setName('Logo');
                $PHPExcelDrawing->setDescription('Logo');
                $PHPExcelDrawing->setPath($logoPath);
                $PHPExcelDrawing->setHeight(60);
                $PHPExcelDrawing->setCoordinates('S1');
                $PHPExcelDrawing->setWorksheet($PHPExcel->getActiveSheet());                
                $PHPExcel->getActiveSheet()->getStyle('S1:S1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                
                $PHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1', 'Format effective date: ' . date('jS F, Y'))
                    ->mergeCells('A1:S2')
                    ->setCellValue('A3', 'OUTSTATION TRAVEL')                    
                    ->mergeCells('A3:S3')                   
                    ->setCellValue('A4', 'Band')
                    ->mergeCells('A4:C4')
                    ->setCellValue('D4', $travelData->band)
                    ->mergeCells('D4:H4')
                    ->setCellValue('A5', 'Location / Place of posting')
                    ->mergeCells('A5:C5')
                    ->setCellValue('D5', $travelData->location . " - " . get_title('state_list', ['id' => $travelData->stateId], 'state'))
                    ->mergeCells('D5:H5')
                    ->setCellValue('A6', 'Head Quarter')
                    ->mergeCells('A6:C6')
                    ->setCellValue('D6', $travelData->headQuarter)
                    ->mergeCells('D6:H6')                        
                    ->setCellValue('I4', 'Month')
                    ->mergeCells('I4:K4')
                    ->setCellValue('L4', $travelData->month)
                    ->mergeCells('L4:S4')
                    ->setCellValue('I5', 'Department')
                    ->mergeCells('I5:K5')
                    ->setCellValue('L5', $travelData->department)
                    ->mergeCells('L5:S5')
                    ->setCellValue('I6', '')
                    ->mergeCells('I6:K6')
                    ->setCellValue('L6', '')
                    ->mergeCells('L6:S6');

                $PHPExcel->getActiveSheet()->getStyle('D4:H6')->applyFromArray($dataStyleArray);
                $PHPExcel->getActiveSheet()->getStyle('L4:S6')->applyFromArray($dataStyleArray);                
                
                // Set column titles
                $PHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A7', 'DATE')
                    ->mergeCells('A7:A9')
                    ->setCellValue('B7', 'SALESMAN')
                    ->mergeCells('B7:B9')
                    ->setCellValue('C7', 'PLACES VISITED')
                    ->mergeCells('C7:D7')
                    ->setCellValue('C8', 'From')
                    ->mergeCells('C8:C9')
                    ->setCellValue('D8', 'To')
                    ->mergeCells('D8:D9')
                    ->setCellValue('E7', 'MODE OF TRAVEL')
                    ->mergeCells('E7:L7')
                    ->setCellValue('E8', 'Bus')
                    ->mergeCells('E8:E9')
                    ->setCellValue('F8', 'Private Own Vehicle')
                    ->mergeCells('F8:J8')
                    ->setCellValue('F9', 'Start KM (A)')
                    ->setCellValue('G9', 'End KM (B)')
                    ->setCellValue('H9', 'Total KM (B - A)')
                    ->setCellValue('I9', 'Amount')
                    ->setCellValue('J9', 'Car/Bike Model Type')
                    ->setCellValue('K8', 'Railway')
                    ->mergeCells('K8:K9')
                    ->setCellValue('L8', 'Auto')
                    ->mergeCells('L8:L9')
                    ->setCellValue('M7', 'OUTSTATION')
                    ->mergeCells('M7:N7')
                    ->setCellValue('M8', 'Hotel Accommodation')
                    ->mergeCells('M8:M9')
                    ->setCellValue('N8', 'Food')
                    ->mergeCells('N8:N9')
                    ->setCellValue('O7', 'OTHER MISC. EXPENSES')
                    ->mergeCells('O7:Q7')
                    ->setCellValue('O8', 'Internet/Fax')
                    ->mergeCells('O8:O9')
                    ->setCellValue('P8', 'Xerox/Print')
                    ->mergeCells('P8:P9')
                    ->setCellValue('Q8', 'Post/Courier')
                    ->mergeCells('Q8:Q9')
                    ->setCellValue('R7', 'TOTAL AMOUNT')
                    ->mergeCells('R7:R9')
                    ->setCellValue('S7', 'REMARK')
                    ->mergeCells('S7:S9');
                                              
                $PHPExcel->getActiveSheet()->getRowDimension('9')->setRowHeight(30);
                $PHPExcel->getActiveSheet()->getStyle('A7:S9')->getAlignment()->setWrapText(true);
                
                $TravelDataReportQuery = $this->db->where($travelsDataWhere)->where_in('employeeId', $salesmanIds)->get('employee_outstation_travels_data');
                if ($TravelDataReportQuery->num_rows()) {
                    $rowCount = 10;
                    $totalBus = 0;
                    $totalStartKM = 0;
                    $totalEndKM = 0;
                    $totalTotalKM = 0;
                    $totalAmount = 0;
                    $totalRailway = 0;
                    $totalAuto = 0;
                    $totalHotel = 0;
                    $totalFood = 0;
                    $totalInternetFax = 0;
                    $totalXeroxPrint = 0;
                    $totalPostCourier = 0;
                    $grandTotalAmount = 0;
                    foreach ($TravelDataReportQuery->result() as $key => $travelsData) {
                        $totalBus += $travelsData->bus;
                        $totalStartKM += $travelsData->startKM;
                        $totalEndKM += $travelsData->endKM;
                        $totalTotalKM += $travelsData->totalKM;
                        $totalAmount += $travelsData->amount;
                        $totalRailway += $travelsData->railway;
                        $totalAuto += $travelsData->auto;
                        $totalHotel += $travelsData->hotel;
                        $totalFood += $travelsData->food;
                        $totalInternetFax += $travelsData->internetFax;
                        $totalXeroxPrint += $travelsData->xeroxPrint;
                        $totalPostCourier += $travelsData->postCourier;
                        $grandTotalAmount += $travelsData->totalAmount;
                        
                        $PHPExcel->getActiveSheet()->getStyle('A' . $rowCount . ':S' . $rowCount)->applyFromArray($dataStyleArray);
                        
                        $PHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, date('d/m/Y', strtotime($travelsData->travelDate)));
                        $PHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, get_title('employees', ['Id' => $travelsData->employeeId], 'Name'));
                        $PHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $travelsData->from);
                        $PHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $travelsData->to);
                        $PHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $travelsData->bus);
                        $PHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $travelsData->startKM);
                        $PHPExcel->getActiveSheet()->SetCellValue('G' . $rowCount, $travelsData->endKM);
                        $PHPExcel->getActiveSheet()->SetCellValue('H' . $rowCount, $travelsData->totalKM);
                        $PHPExcel->getActiveSheet()->SetCellValue('I' . $rowCount, $travelsData->amount);
                        $PHPExcel->getActiveSheet()->SetCellValue('J' . $rowCount, $travelsData->modelType);
                        $PHPExcel->getActiveSheet()->SetCellValue('K' . $rowCount, $travelsData->railway);
                        $PHPExcel->getActiveSheet()->SetCellValue('L' . $rowCount, $travelsData->auto);
                        $PHPExcel->getActiveSheet()->SetCellValue('M' . $rowCount, $travelsData->hotel);
                        $PHPExcel->getActiveSheet()->SetCellValue('N' . $rowCount, $travelsData->food);
                        $PHPExcel->getActiveSheet()->SetCellValue('O' . $rowCount, $travelsData->internetFax);
                        $PHPExcel->getActiveSheet()->SetCellValue('P' . $rowCount, $travelsData->xeroxPrint);
                        $PHPExcel->getActiveSheet()->SetCellValue('Q' . $rowCount, $travelsData->postCourier);
                        $PHPExcel->getActiveSheet()->SetCellValue('R' . $rowCount, $travelsData->totalAmount);
                        $PHPExcel->getActiveSheet()->SetCellValue('S' . $rowCount, $travelsData->remark);
                        $rowCount++;
                    }
                    $PHPExcel->getActiveSheet()->getStyle('A' . $rowCount . ':S' . $rowCount)->applyFromArray($dataStyleArray);
                    
                    $rowCount++;
                    $PHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, 'Total');
                    $PHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $rowCount . ':D' . $rowCount);
                    $PHPExcel->getActiveSheet()->getStyle('A' . $rowCount . ':D' . $rowCount)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
                    
                    $PHPExcel->getActiveSheet()->setCellValue('E' . $rowCount, $totalBus);
                    $PHPExcel->getActiveSheet()->setCellValue('F' . $rowCount, $totalStartKM);
                    $PHPExcel->getActiveSheet()->setCellValue('G' . $rowCount, $totalEndKM);
                    $PHPExcel->getActiveSheet()->setCellValue('H' . $rowCount, $totalTotalKM);
                    $PHPExcel->getActiveSheet()->setCellValue('I' . $rowCount, $totalAmount);
                    $PHPExcel->getActiveSheet()->setCellValue('J' . $rowCount, '');
                    $PHPExcel->getActiveSheet()->setCellValue('K' . $rowCount, $totalRailway);
                    $PHPExcel->getActiveSheet()->setCellValue('L' . $rowCount, $totalAuto);
                    $PHPExcel->getActiveSheet()->setCellValue('M' . $rowCount, $totalHotel);
                    $PHPExcel->getActiveSheet()->setCellValue('N' . $rowCount, $totalFood);
                    $PHPExcel->getActiveSheet()->setCellValue('O' . $rowCount, $totalInternetFax);
                    $PHPExcel->getActiveSheet()->setCellValue('P' . $rowCount, $totalXeroxPrint);
                    $PHPExcel->getActiveSheet()->setCellValue('Q' . $rowCount, $totalPostCourier);
                    $PHPExcel->getActiveSheet()->setCellValue('R' . $rowCount, $grandTotalAmount);
                    
                    $PHPExcel->getActiveSheet()->getStyle('A' . $rowCount . ':S' . $rowCount)->applyFromArray($headerStyleArray);
                    
                    $rowCount++;
                    $PHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $rowCount . ':S' . $rowCount);
                    $PHPExcel->getActiveSheet()->getStyle('A' . $rowCount . ':S' . $rowCount)->applyFromArray($headerStyleArray);
                    
                    $rowCount++;
                    $PHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, 'Sd/-');
                    $PHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $rowCount . ':B' . $rowCount);
                    $PHPExcel->getActiveSheet()->setCellValue('F' . $rowCount, 'Sd/-');
                    $PHPExcel->setActiveSheetIndex(0)->mergeCells('F' . $rowCount . ':G' . $rowCount);
                    $PHPExcel->getActiveSheet()->setCellValue('K' . $rowCount, 'Sd/-');
                    $PHPExcel->setActiveSheetIndex(0)->mergeCells('K' . $rowCount . ':L' . $rowCount);
                    $PHPExcel->getActiveSheet()->setCellValue('P' . $rowCount, 'Sd/-');
                    $PHPExcel->setActiveSheetIndex(0)->mergeCells('P' . $rowCount . ':Q' . $rowCount);
                    $PHPExcel->getActiveSheet()->getStyle('A' . $rowCount)->getFont()->setBold(true);
                    $PHPExcel->getActiveSheet()->getStyle('F' . $rowCount)->getFont()->setBold(true);
                    $PHPExcel->getActiveSheet()->getStyle('K' . $rowCount)->getFont()->setBold(true);
                    $PHPExcel->getActiveSheet()->getStyle('P' . $rowCount)->getFont()->setBold(true);
                    $PHPExcel->getActiveSheet()->getStyle('A' . $rowCount . ':S' . $rowCount)->applyFromArray($topHeaderStyleArray);
                    
                    $rowCount++;
                    $previousRowNumber = ($rowCount - 1);
                    $PHPExcel->getActiveSheet()->setCellValue('A' . $rowCount, 'Employee');
                    $PHPExcel->setActiveSheetIndex(0)->mergeCells('A' . $rowCount . ':B' . $rowCount);
                    $PHPExcel->getActiveSheet()->setCellValue('F' . $rowCount, 'Reporting Senior');
                    $PHPExcel->setActiveSheetIndex(0)->mergeCells('F' . $rowCount . ':G' . $rowCount);
                    $PHPExcel->getActiveSheet()->setCellValue('K' . $rowCount, 'Accounts Head');
                    $PHPExcel->setActiveSheetIndex(0)->mergeCells('K' . $rowCount . ':L' . $rowCount);
                    $PHPExcel->getActiveSheet()->setCellValue('P' . $rowCount, 'Director');
                    $PHPExcel->setActiveSheetIndex(0)->mergeCells('P' . $rowCount . ':Q' . $rowCount);
                    $PHPExcel->getActiveSheet()->getStyle('A' . $rowCount)->getFont()->setBold(true);
                    $PHPExcel->getActiveSheet()->getStyle('F' . $rowCount)->getFont()->setBold(true);
                    $PHPExcel->getActiveSheet()->getStyle('K' . $rowCount)->getFont()->setBold(true);
                    $PHPExcel->getActiveSheet()->getStyle('P' . $rowCount)->getFont()->setBold(true);
                    $PHPExcel->getActiveSheet()->getStyle('A' . $rowCount . ':S' . $rowCount)->applyFromArray($topHeaderStyleArray);
                    $PHPExcel->setActiveSheetIndex(0)->mergeCells('C' . $previousRowNumber . ':E' . $rowCount);
                    $PHPExcel->setActiveSheetIndex(0)->mergeCells('H' . $previousRowNumber . ':J' . $rowCount);
                    $PHPExcel->setActiveSheetIndex(0)->mergeCells('M' . $previousRowNumber . ':O' . $rowCount);
                    $PHPExcel->setActiveSheetIndex(0)->mergeCells('R' . $previousRowNumber . ':S' . $rowCount);
                }                
                
                // Set column widths
                $PHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(10);
                $PHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
                $PHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
                $PHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(20);
                $PHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
                $PHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
                $PHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
                $PHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(10);
                $PHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(10);
                $PHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(12);
                $PHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(10);
                $PHPExcel->getActiveSheet()->getColumnDimension('L')->setWidth(10);
                $PHPExcel->getActiveSheet()->getColumnDimension('M')->setWidth(16);
                $PHPExcel->getActiveSheet()->getColumnDimension('N')->setWidth(10);
                $PHPExcel->getActiveSheet()->getColumnDimension('O')->setWidth(12);
                $PHPExcel->getActiveSheet()->getColumnDimension('P')->setWidth(12);
                $PHPExcel->getActiveSheet()->getColumnDimension('Q')->setWidth(12);
                $PHPExcel->getActiveSheet()->getColumnDimension('R')->setWidth(10);
                $PHPExcel->getActiveSheet()->getColumnDimension('S')->setWidth(20);
                
                // Apply styles
                $PHPExcel->getActiveSheet()->getStyle('A1:S2')->applyFromArray($headerStyleArray);
                $PHPExcel->getActiveSheet()->getStyle('A4:C6')->applyFromArray($topHeaderStyleArray);
                $PHPExcel->getActiveSheet()->getStyle('I4:K6')->applyFromArray($topHeaderStyleArray);
                $PHPExcel->getActiveSheet()->getStyle('A7:S9')->applyFromArray($headerStyleArray);
                $PHPExcel->getActiveSheet()->getStyle('A3:S3')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14,
                        'color' => ['rgb' => '000000'],
                    ],
                    'borders' => [
                        'allborders' => [
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => ['rgb' => '000000'],
                        ],
                    ],
                ]);
                $PHPExcel->getActiveSheet()->getStyle('A3:S3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $PHPExcel->getActiveSheet()->getStyle('A1:A1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $PHPExcel->getActiveSheet()->getStyle('A1:S1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $PHPExcel->getActiveSheet()->getStyle('F8:J8')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
                $PHPExcel->getActiveSheet()->getStyle('A7:S9')->applyFromArray([
                    'alignment' => [
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER
                    ]
                ]);
                
                // Set background colors
                $PHPExcel->getActiveSheet()->getStyle('A4:C6')->getFill()->applyFromArray([
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'startcolor' => ['rgb' => 'FFFF00'],
                ]);                
                $PHPExcel->getActiveSheet()->getStyle('I4:K6')->getFill()->applyFromArray([
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'startcolor' => ['rgb' => 'FFFF00'],
                ]);
                $PHPExcel->getActiveSheet()->getStyle('A7:S9')->getFill()->applyFromArray([
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'startcolor' => ['rgb' => 'FFFF00'],
                ]);

                $PHPExcel->getActiveSheet()->setTitle('OUTSTATION_TRAVEL');
                
                header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                header('Content-Disposition: attachment;filename="' . str_replace(' ', '_', $Name) . '_OUTSTATION_TRAVEL_REPORT.xlsx"');
                header('Cache-Control: max-age=0');
                PHPExcel_IOFactory::createWriter($PHPExcel, 'Excel2007')->save('php://output');
            } else {
                $this->session->set_flashdata(['msg' => 'No record found.', 'class' => 'danger']);
                return redirect($_SERVER['HTTP_REFERER']);
            }
        }
    }