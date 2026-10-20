<?php
    defined('BASEPATH') or exit('No direct script access allowed');
    date_default_timezone_set('Asia/Kolkata');

    require APPPATH . '/libraries/REST_Controller.php';
    
    class API extends REST_Controller
    {
        public function __construct()
        {
            parent::__construct();
            
            header('Access-Control-Allow-Origin: *');
            header("Access-Control-Allow-Methods: GET, OPTIONS, POST, PUT, DELETE");
            header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
            
            $this->load->model('user_model');
        }
        
        public function Auth_POST()
        {
            $EmailId = $this->post('EmailId');
            $Password = $this->post('Password');
            
            if (!empty($EmailId) && !empty($Password)) {
                $Where = ['Email' => $EmailId, 'Password' => $Password, 'Status' => 1, 'IsActive' => 1, 'IsDeleted' => 1];
                $EmployeeQuery = $this->db->select('Id, Name, RoleId AS RoleName')->where($Where)->get('employees');
                if ($EmployeeQuery->num_rows()) {
                    $EmployeeData = $EmployeeQuery->row_array();                    
                    
                    $this->db->where('Id', $EmployeeData['Id'])->update('employees', ['isLoggedIn' => bin2hex(openssl_random_pseudo_bytes(16))]);
                        
                    $EmployeeData['RoleName'] = get_title('position', ['Id' => $EmployeeQuery->row()->RoleName], 'PostName');
                    $EmployeeData['isLoggedIn'] = get_title('employees', ['Id' => $EmployeeQuery->row()->Id], 'isLoggedIn');

                    $this->response([
                        'Status' => TRUE,
                        'Message' => 'Welcome, Back',
                        'Data' => $EmployeeData
                    ], REST_Controller::HTTP_OK);
                } else {
                    $this->response([
                        'Status' => FALSE,
                        'Message' => 'No account found.',
                        'Data' => 0
                    ], REST_Controller::HTTP_UNAUTHORIZED);
                }
            } else {
                $this->response([
                    'Status' => FALSE,
                    'Message' => 'Enter required fields.',
                    'Data' => 0
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        }
        
        public function logOut_POST()
        {
            $employeeId = $this->post('employeeId');
            
            if (!empty($employeeId)) {
                $Where = ['Id' => $employeeId, 'Status' => 1, 'IsActive' => 1, 'IsDeleted' => 1];
                $EmployeeQuery = $this->db->select('Id')->where($Where)->get('employees');
                if ($EmployeeQuery->num_rows()) {
                    $logOutResult = $this->db->where('Id', $EmployeeQuery->row()->Id)->update('employees', ['isLoggedIn' => NULL]);
                    if ($logOutResult) {
                        $this->response([
                            'Status' => TRUE,
                            'Message' => 'Logged Out Successfully',
                            'Data' => ''
                        ], REST_Controller::HTTP_OK);
                    } else {
                        $this->response([
                            'Status' => FALSE,
                            'Message' => 'Enter required fields.',
                            'Data' => 0
                        ], REST_Controller::HTTP_BAD_REQUEST);
                    }
                } else {
                    $this->response([
                        'Status' => FALSE,
                        'Message' => 'No account found.',
                        'Data' => 0
                    ], REST_Controller::HTTP_UNAUTHORIZED);
                }
            } else {
                $this->response([
                    'Status' => FALSE,
                    'Message' => 'Enter required fields.',
                    'Data' => 0
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        }
        
        public function Profile_GET($EmployeeId)
        {
            if (!empty($EmployeeId)) {
                $Where = ['Id' => $EmployeeId, 'Status' => 1, 'IsActive' => 1, 'IsDeleted' => 1];
                $EmployeeQuery = $this->db->select('Id, EmployeeId, Name, Email, Mobile, RoleId AS RoleName, Photograph, SalesTarget, isLoggedIn, UserId, CompId')->where($Where)->get('employees');
                if ($EmployeeQuery->num_rows()) {
                    $EmployeeData = $EmployeeQuery->row_array();
                    $EmployeeData['RoleName'] = get_title('position', ['Id' => $EmployeeQuery->row()->RoleName], 'PostName');
                    $EmployeeData['Photograph'] = !empty($EmployeeQuery->row()->Photograph) ? base_url() . "uploads/Employee/Photograph/" . $EmployeeQuery->row()->Photograph : "";
                    
                    $employeeWhere = ['Date' => date('Y-m-d'), 'Month' => date('F'), 'OutTime' => NULL, 'EmpId' => $EmployeeId, 'UserId' => $EmployeeQuery->row()->UserId, 'CompId' => $EmployeeQuery->row()->CompId];
                    if ($EmployeeQuery->row()->CompanyBranchId) {
                        $employeeWhere['CompanyBranchId'] = $EmployeeQuery->row()->CompanyBranchId;
                    }
                    if ($this->db->select('Id')->where($employeeWhere)->get('employee_attendance')->num_rows()) {
                        $EmployeeData['todayAttendance'] = TRUE;
                    } else {
                        $EmployeeData['todayAttendance'] = FALSE;
                    }
                    
                    $this->response([
                        'Status' => TRUE,
                        'Message' => 'Employee Data',
                        'Data' => $EmployeeData
                    ], REST_Controller::HTTP_OK);
                } else {
                    $this->response([
                        'Status' => FALSE,
                        'Message' => 'No record found.',
                        'Data' => 0
                    ], REST_Controller::HTTP_BAD_REQUEST);                    
                }
            } else {
                $this->response([
                    'Status' => FALSE,
                    'Message' => 'Employee id missing.',
                    'Data' => 0
                ], REST_Controller::HTTP_BAD_REQUEST);
            }            
        }
        
        public function ChangePassword_POST()
        {
            $EmployeeId = $this->post('Id');
            $CurrentPassword = $this->post('CurrentPassword');
            $NewPassword = $this->post('NewPassword');
            
            if (!empty($EmployeeId) && !empty($CurrentPassword) && !empty($NewPassword)) {
                $Where = ['Id' => $EmployeeId, 'Status' => 1, 'IsActive' => 1, 'IsDeleted' => 1];
                $EmployeeQuery = $this->db->select('Id, Password')->where($Where)->get('employees');
                if ($EmployeeQuery->num_rows()) {
                    $EmployeeInfo = $EmployeeQuery->row();
                    if ($EmployeeInfo->Password == $CurrentPassword) {
                        $EmployeeData = [
                            'Password' => $NewPassword,
                            'Updated' => date('d-m-Y h:i:s A'),
                            'UpdatedBy' => get_title('employees', ['Id' => $EmployeeId], 'Name')
                        ];
                        $Result = $this->db->where($Where)->update('employees', $EmployeeData);
                        if ($Result) {
                            $this->response([
                                'Status' => TRUE,
                                'Message' => 'Password change successfully.',
                                'Data' => ""
                            ], REST_Controller::HTTP_OK);
                        } else {
                            $this->response([
                                'Status' => FALSE,
                                'Message' => 'Somethigs went wrong, try again.',
                                'Data' => 0
                            ], REST_Controller::HTTP_BAD_REQUEST);                            
                        }
                    } else {
                        $this->response([
                            'Status' => FALSE,
                            'Message' => 'Current Password does not match, try again.',
                            'Data' => 0
                        ], REST_Controller::HTTP_BAD_REQUEST);
                    }
                } else {
                    $this->response([
                        'Status' => FALSE,
                        'Message' => 'No record found.',
                        'Data' => 0
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
            } else {
                $this->response([
                    'Status' => FALSE,
                    'Message' => 'Enter required fields.',
                    'Data' => 0
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        }
        
        public function MarkAttendance_POST()
        {
            $EmployeeId = $this->post('Id');
            $InTime = $this->post('InTime');
            $StartLatitude = $this->post('StartLatitude');
            $StartLongitude = $this->post('StartLongitude');
            $SelfieImage = $_FILES['SelfieImage'];
            
            //if (!empty($EmployeeId) && !empty($InTime) && !empty($StartLatitude) && !empty($StartLongitude) && !empty($SelfieImage)) {
            if (!empty($EmployeeId) && !empty($InTime) && !empty($StartLatitude) && !empty($StartLongitude)) {
                $Where = ['Id' => $EmployeeId, 'Status' => 1, 'IsActive' => 1, 'IsDeleted' => 1];
                $EmployeeQuery = $this->db->select('Id, UserId, CompId, CompanyBranchId')->where($Where)->get('employees');
                if ($EmployeeQuery->num_rows()) {
                    $EmployeeInfo = $EmployeeQuery->row();
                    $CompOrBranchProfileWhere = ['user_id' => $EmployeeInfo->UserId, 'comp_id' => $EmployeeInfo->CompId];
                    $EmployeeWhere = ['Date' => date('Y-m-d'), 'Month' => date('F'), 'EmpId' => $EmployeeId, 'UserId' => $EmployeeInfo->UserId, 'CompId' => $EmployeeInfo->CompId];
                    if ($EmployeeInfo->CompanyBranchId) {
                        $CompOrBranchProfileWhere['CompanyBranchId'] = $EmployeeInfo->CompanyBranchId;
                        $EmployeeWhere['CompanyBranchId'] = $EmployeeInfo->CompanyBranchId;
                    }

                    if ($this->db->select('Id')->where($EmployeeWhere)->get('employee_attendance')->num_rows()) {
                        $this->response([
                            'Status' => FALSE,
                            'Message' => 'Attendance already marked.',
                            'Data' => 0
                        ], REST_Controller::HTTP_BAD_REQUEST);
                    } else {
                        if ($_FILES['SelfieImage']['name']) {
                            $SelfieImageName = time() . $_FILES['SelfieImage']['name'];
                            move_uploaded_file($_FILES['SelfieImage']['tmp_name'], 'uploads/Employee/Selfie/' . $SelfieImageName);
                            $SelfieImageFileName = $SelfieImageName;
                        } else {
                            $SelfieImageFileName = "";
                        }
                        $EmployeeAttendanceData = [
                            'Date' => date('Y-m-d'),
                            'InTime' => $InTime,
                            'ShiftType' => 'A',
                            'Attendance' => 'Present',
                            'Month' => date('F'),
                            'SelfieImage' => $SelfieImageFileName,
                            'EmpId' => $EmployeeId,
                            'UserId' => $EmployeeInfo->UserId,
                            'CompId' => $EmployeeInfo->CompId,
                            'CompanyBranchId' => $EmployeeInfo->CompanyBranchId,
                            'FinancialYear' => get_title('comp_profile', $CompOrBranchProfileWhere, 'financial_year'),
                            'Created' => date('d-m-Y h:i:s A'),
                            'CreatedBy' => get_title('employees', ['Id' => $EmployeeId], 'Name')
                        ];
                        $Result = $this->db->insert('employee_attendance', $EmployeeAttendanceData);
                        
                        if ($Result) {
                            $EmployeeTrackingData = [
                                'Date' => date('Y-m-d'),
                                'StartLatitude' => $StartLatitude,
                                'StartLongitude' => $StartLongitude,
                                'EndLatitude' => $StartLatitude,
                                'EndLongitude' => $StartLongitude,
                                'EmployeeId' => $EmployeeInfo->Id,
                                'UserId' => $EmployeeInfo->UserId,
                                'CompId' => $EmployeeInfo->CompId,
                                'CompanyBranchId' => $EmployeeInfo->CompanyBranchId,
                                'Created' => date('d-m-Y h:i:s A'),
                                'CreatedBy' => get_title('employees', ['Id' => $EmployeeId], 'Name')
                            ];
                            $this->db->insert('employee_tracking', $EmployeeTrackingData);
                            
                            $this->response([
                                'Status' => TRUE,
                                'Message' => 'Attendance marked successfully.',
                                'Data' => ""
                            ], REST_Controller::HTTP_OK);
                        } else {
                            $this->response([
                                'Status' => FALSE,
                                'Message' => 'Somethigs went wrong, try again.',
                                'Data' => 0
                            ], REST_Controller::HTTP_BAD_REQUEST);
                        }                        
                    }
                } else {
                    $this->response([
                        'Status' => FALSE,
                        'Message' => 'No record found.',
                        'Data' => 0
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
            } else {
                $this->response([
                    'Status' => FALSE,
                    'Message' => 'Enter required fields.',
                    'Data' => 0
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        }
        
        public function SaveTracking_POST()
        {
            $EmployeeId = $this->post('Id');
            $EndLatitude = $this->post('EndLatitude');
            $EndLongitude = $this->post('EndLongitude');
            
            if (!empty($EmployeeId) && !empty($EndLatitude) && !empty($EndLongitude)) {
                $Where = ['Id' => $EmployeeId, 'Status' => 1, 'IsActive' => 1, 'IsDeleted' => 1];
                $EmployeeQuery = $this->db->select('Id, UserId, CompId, CompanyBranchId')->where($Where)->get('employees');
                $EmployeeTrackingQuery = $this->db->select('EndLatitude, EndLongitude')->where('EmployeeId', $EmployeeId)->order_by('Id', 'DESC')->limit(1)->get('employee_tracking');
                if ($EmployeeQuery->num_rows() && $EmployeeTrackingQuery->num_rows()) {
                    $EmployeeInfo = $EmployeeQuery->row();
                    $EmployeeTrackingInfo = $EmployeeTrackingQuery->row();
                    $EmployeeTrackingData = [
                        'Date' => date('Y-m-d'),
                        'StartLatitude' => $EmployeeTrackingInfo->EndLatitude,
                        'StartLongitude' => $EmployeeTrackingInfo->EndLongitude,
                        'EndLatitude' => $EndLatitude,
                        'EndLongitude' => $EndLongitude,
                        'EmployeeId' => $EmployeeInfo->Id,
                        'UserId' => $EmployeeInfo->UserId,
                        'CompId' => $EmployeeInfo->CompId,
                        'CompanyBranchId' => $EmployeeInfo->CompanyBranchId,
                        'Created' => date('d-m-Y h:i:s A'),
                        'CreatedBy' => get_title('employees', ['Id' => $EmployeeId], 'Name')
                    ];
                    
                    if ($this->db->select('Id')->where(['Date' => date('Y-m-d'), 'StartLatitude' => $EmployeeTrackingInfo->EndLatitude, 'StartLongitude' => $EmployeeTrackingInfo->EndLongitude, 'EndLatitude' => $EndLatitude, 'EndLongitude' => $EndLongitude, 'EmployeeId' => $EmployeeInfo->Id])->get('employee_tracking')->num_rows() === 0) {
                        $Result = $this->db->insert('employee_tracking', $EmployeeTrackingData);                        
                    } else {
                        $this->response([
                            'Status' => FALSE,
                            'Message' => 'Duplicate record.',
                            'Data' => 0
                        ], REST_Controller::HTTP_OK);
                    }

                    if ($Result) {
                        $EmployeeTravelStartQuery = $this->db->select('StartLatitude, StartLongitude')->where(['EmployeeId' => $EmployeeId, 'Date >= ' => date('Y-m-d'), 'Date <= ' => date('Y-m-d')])->order_by('Id', 'ASC')->get('employee_tracking');
                        $EmployeeTravelEndQuery = $this->db->select('EndLatitude, EndLongitude')->where(['EmployeeId' => $EmployeeId, 'Date >= ' => date('Y-m-d'), 'Date <= ' => date('Y-m-d')])->order_by('Id', 'DESC')->get('employee_tracking');
                        if ($EmployeeTravelStartQuery->num_rows() && $EmployeeTravelEndQuery->num_rows()) {
                            $TravelStart = $EmployeeTravelStartQuery->row();
                            $TravelEnd = $EmployeeTravelEndQuery->row();
                            $KM = haversineDistance($TravelStart->StartLatitude, $TravelStart->StartLongitude, $TravelEnd->EndLatitude, $TravelEnd->EndLongitude);
                            $EmployeeTraval = number_format($KM, 2) . ' KM';
                        }
                        $employeeAttendanceQuery = $this->db->select('InTime, OutTime, WorkingHours')->where(['EmpId' => $EmployeeId, 'Date >= ' => date('Y-m-d'), 'Date <= ' => date('Y-m-d')])->get('employee_attendance');
                        if ($employeeAttendanceQuery->num_rows()) {
                            $employeeAttendanceData = $employeeAttendanceQuery->row();
                            if (!empty($employeeAttendanceData->InTime && empty($employeeAttendanceData->OutTime))) {
                                $workingHours = differenceInHours($employeeAttendanceData->InTime, date('H:i'));
                            } elseif (!empty($employeeAttendanceData->InTime && !empty($employeeAttendanceData->OutTime))) {
                                $workingHours = $employeeAttendanceData->WorkingHours;
                            }
                        }
                        
                        $this->response([
                            'Status' => TRUE,
                            'Message' => 'Tracking record save successfully.',
                            'Data' => ['date' => date('Y-m-d'), 'workingHours' => $workingHours, 'travalKM' => $EmployeeTraval]
                        ], REST_Controller::HTTP_OK);
                    } else {
                        $this->response([
                            'Status' => FALSE,
                            'Message' => 'Somethigs went wrong, try again.',
                            'Data' => 0
                        ], REST_Controller::HTTP_BAD_REQUEST);
                    }
                } else {
                    $this->response([
                        'Status' => FALSE,
                        'Message' => 'No record found.',
                        'Data' => 0
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
            } else {
                $this->response([
                    'Status' => FALSE,
                    'Message' => 'Enter required fields.',
                    'Data' => 0
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        }
        
        public function UpdateAttendance_POST()
        {
            $EmployeeId = $this->post('Id');
            $OutTime = $this->post('OutTime');
            $EndLatitude = $this->post('EndLatitude');
            $EndLongitude = $this->post('EndLongitude');
            
            if (!empty($EmployeeId) && !empty($OutTime) && !empty($EndLatitude) && !empty($EndLongitude)) {
                $Where = ['Id' => $EmployeeId, 'Status' => 1, 'IsActive' => 1, 'IsDeleted' => 1];
                $EmployeeQuery = $this->db->select('Id, UserId, CompId, CompanyBranchId')->where($Where)->get('employees');
                if ($EmployeeQuery->num_rows()) {
                    $EmployeeInfo = $EmployeeQuery->row();
                    $CompOrBranchProfileWhere = ['user_id' => $EmployeeInfo->UserId, 'comp_id' => $EmployeeInfo->CompId];
                    $EmployeeWhere = ['Date' => date('Y-m-d'), 'OutTime' => NULL, 'Month' => date('F'), 'EmpId' => $EmployeeId, 'UserId' => $EmployeeInfo->UserId, 'CompId' => $EmployeeInfo->CompId];
                    if ($EmployeeInfo->CompanyBranchId) {
                        $CompOrBranchProfileWhere['CompanyBranchId'] = $EmployeeInfo->CompanyBranchId;
                        $EmployeeWhere['CompanyBranchId'] = $EmployeeInfo->CompanyBranchId;
                    }
                    
                    $EmployeeAttendanceQuery = $this->db->select('Id, InTime')->where($EmployeeWhere)->get('employee_attendance');
                    if ($EmployeeAttendanceQuery->num_rows()) {
                        $EmployeeAttendanceData = [
                            'OutTime' => $OutTime,
                            'WorkingHours' => differenceInHours($EmployeeAttendanceQuery->row()->InTime, $OutTime), 
                            'Updated' => date('d-m-Y h:i:s A'),
                            'UpdatedBy' => get_title('employees', ['Id' => $EmployeeId], 'Name')
                        ];
                        $Result = $this->db->where('Id', $EmployeeAttendanceQuery->row()->Id)->where($EmployeeWhere)->update('employee_attendance', $EmployeeAttendanceData);
                        
                        if ($Result) {
                            $EmployeeTrackingQuery = $this->db->select('EndLatitude, EndLongitude')->where('EmployeeId', $EmployeeId)->order_by('Id', 'DESC')->limit(1)->get('employee_tracking')->row();
                            $EmployeeTrackingData = [
                                'Date' => date('Y-m-d'),
                                'StartLatitude' => $EmployeeTrackingQuery->EndLatitude,
                                'StartLongitude' => $EmployeeTrackingQuery->EndLongitude,
                                'EndLatitude' => $EmployeeTrackingQuery->EndLatitude,
                                'EndLatitude' => $EmployeeTrackingQuery->EndLongitude,
                                'EmployeeId' => $EmployeeInfo->Id,
                                'UserId' => $EmployeeInfo->UserId,
                                'CompId' => $EmployeeInfo->CompId,
                                'CompanyBranchId' => $EmployeeInfo->CompanyBranchId,
                                'Created' => date('d-m-Y h:i:s A'),
                                'CreatedBy' => get_title('employees', ['Id' => $EmployeeId], 'Name')
                            ];
                            $this->db->insert('employee_tracking', $EmployeeTrackingData);
                            
                            $this->response([
                                'Status' => TRUE,
                                'Message' => 'Attendance update successfully.',
                                'Data' => differenceInHours($EmployeeAttendanceQuery->row()->InTime, $OutTime)
                            ], REST_Controller::HTTP_OK);
                        } else {
                            $this->response([
                                'Status' => FALSE,
                                'Message' => 'Somethigs went wrong, try again.',
                                'Data' => 0
                            ], REST_Controller::HTTP_BAD_REQUEST);
                        }                        
                    } else {
                        $this->response([
                            'Status' => FALSE,
                            'Message' => 'No record found.',
                            'Data' => 0
                        ], REST_Controller::HTTP_BAD_REQUEST);   
                    }
                } else {
                    $this->response([
                        'Status' => FALSE,
                        'Message' => 'Employee not record found.',
                        'Data' => 0
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
            } else {
                $this->response([
                    'Status' => FALSE,
                    'Message' => 'Enter required fields.',
                    'Data' => 0
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        }
        
        public function Authentication_GET()
        {
            $EmailId = $this->get('email');
            $Password = $this->get('password');
            $Toten = $this->get('token');
            if (!empty($EmailId) && !empty($Password) && !empty($Toten)) {
                if ($Toten == 'UDAYPLASTIC') {
                    $Where = ['Email' => $EmailId, 'Password' => $Password, 'Status' => 1, 'IsActive' => 1, 'IsDeleted' => 1];
                    $EmployeeQuery = $this->db->select('Id, RoleId, UserId, CompId, CompanyBranchId, WarehouseId')->where($Where)->get('employees');
                    if ($EmployeeQuery->num_rows()) {
                        $LoginDataEmployee = $EmployeeQuery->row();
                        $this->session->set_userdata('EmployeeId', $LoginDataEmployee->Id);
                        $this->session->set_userdata('RoleType', get_title('position', ['Id' => $LoginDataEmployee->RoleId], 'PostName'));
                        $this->session->set_userdata('UserId', $LoginDataEmployee->UserId);
                        $this->session->set_userdata('CompId', $LoginDataEmployee->CompId);
                        $this->session->set_userdata('WarehouseId', $LoginDataEmployee->WarehouseId);

                        $Where = ['comp_id' => $LoginDataEmployee->CompId, 'user_id' => $LoginDataEmployee->UserId];
                        $EmployeeConfigWhere = ['CompId' => $LoginDataEmployee->CompId, 'UserId' => $LoginDataEmployee->UserId];

                        if ($LoginDataEmployee->CompanyBranchId) {
                            $Where['CompanyBranchId'] = $LoginDataEmployee->CompanyBranchId;
                            $EmployeeConfigWhere['CompanyBranchId'] = $LoginDataEmployee->CompanyBranchId;
                            $CompanyBranchWhere = ['Id' => $LoginDataEmployee->CompanyBranchId, 'BranchUserId' => $LoginDataEmployee->UserId, 'CompId' => $LoginDataEmployee->CompId];

                            $CompanyBranch = $this->user_model->getRow('companybranch', $CompanyBranchWhere);
                            $this->session->set_userdata('BranchRole', 'CompanyBranch');
                            $this->session->set_userdata('CompBranchId', $LoginDataEmployee->CompanyBranchId);
                            $this->session->set_userdata('state', $CompanyBranch->State);
                            $this->session->set_userdata('gst_nos', $CompanyBranch->GST);
                            $this->session->set_userdata('numbers', $CompanyBranch->Mobile);
                            $this->session->set_userdata('address', $CompanyBranch->Address);
                            $this->session->set_userdata('format_of_bill', $CompanyBranch->BillingFormat);
                        } else {
                            $MainCompany = $this->user_model->getRow('inventory_billing', ['id' => $LoginDataEmployee->CompId, 'user_id' => $LoginDataEmployee->UserId]);
                            $this->session->set_userdata('state', $MainCompany->state);
                            $this->session->set_userdata('gst_nos', $MainCompany->gst_no);
                            $this->session->set_userdata('numbers', $MainCompany->number);
                            $this->session->set_userdata('address', $MainCompany->address);
                            $this->session->set_userdata('format_of_bill', $MainCompany->billing_format_id);                            
                        }

                        $CompProfile = $this->user_model->getRow('comp_profile', $Where);

                        $this->session->set_userdata('financial_year', $CompProfile->financial_year);        
                        $this->session->set_userdata('barcode', $CompProfile->barcode_con);
                        $this->session->set_userdata('lrdate', $CompProfile->lrdate);
                        $this->session->set_userdata('eway', $CompProfile->eway);
                        $this->session->set_userdata('lrnumber', $CompProfile->lrnumber);
                        $this->session->set_userdata('duedate', $CompProfile->duedate);
                        $this->session->set_userdata('ptd', $CompProfile->ptd);
                        $this->session->set_userdata('dpb', $CompProfile->dpb);
                        $this->session->set_userdata('dcdate', $CompProfile->dcdate);
                        $this->session->set_userdata('dcno', $CompProfile->dcno);
                        $this->session->set_userdata('discount', $CompProfile->discount);
                        $this->session->set_userdata('estimate_format', $CompProfile->estimate_format);
                        $this->session->set_userdata('estimate_stock', $CompProfile->estimate_stock);
                        $this->session->set_userdata('salesman_sale', $CompProfile->salesman_sale);
                        $this->session->set_userdata('salesman_estimate', $CompProfile->salesman_estimate);

                        return redirect(base_url() . 'Employee/Dashboard');
                    } else {
                        return redirect(base_url() . 'signin');
                    }
                } else {
                    return redirect(base_url() . 'signin');
                }
            } else {
                return redirect(base_url() . 'signin');
            }
        }
        
        public function Years_POST()
        {
            $YearQuery = $this->db->select('year')->where('status', 1)->order_by('year', 'ASC')->get('year');
            if ($YearQuery->num_rows()) {
                $Years = [];
                foreach ($YearQuery->result() as $key => $Year) {
                    $YearData = explode('-', $Year->year);
                    $Years[] = $YearData[0];
                }
                
                $this->response([
                    'Status' => TRUE,
                    'Message' => 'Success.',
                    'Data' => $Years
                ], REST_Controller::HTTP_OK);
            } else {
                $this->response([
                    'Status' => FALSE,
                    'Message' => 'No record found.',
                    'Data' => 0
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        }
        
        public function PaySlip_POST()
        {
            $EmployeeId = $this->post('EmployeeId');
            $Month = $this->post('Month');
            $Year = $this->post('Year');
            
            if (!empty($EmployeeId) && !empty($Month) && !empty($Year)) {
                $Where = ['EmpId' => $EmployeeId, 'Month' => $Month, 'Year' => $Year, 'IsDeleted' => 1];
                $EmployeeQuery = $this->db->select('Id')->where($Where)->get('employee_salary');
                if ($EmployeeQuery->num_rows()) {
                    $this->response([
                        'Status' => TRUE,
                        'Message' => 'Success.',
                        'Data' => base_url() . 'Employee/PaySlip/' . $EmployeeQuery->row()->Id
                    ], REST_Controller::HTTP_OK);
                } else {
                    $this->response([
                        'Status' => FALSE,
                        'Message' => 'No record found.',
                        'Data' => 0
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
            } else {
                $this->response([
                    'Status' => FALSE,
                    'Message' => 'Enter required fields.',
                    'Data' => 0
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        }
        
        public function EmployeeData_POST()
        {
            $EmployeeId = $this->post('EmployeeId');
            
            if (!empty($EmployeeId)) {
                $Where = ['Id' => $EmployeeId, 'Status' => 1, 'IsActive' => 1, 'IsDeleted' => 1];
                $EmployeeQuery = $this->db->select('SalesTarget')->where($Where)->get('employees');
                if ($EmployeeQuery->num_rows()) {
                    $TotalSales = $this->db->select_sum('total')->where(['invoiceDate >= ' => date('Y-m-01'), 'invoiceDate <= ' => date('Y-m-31'), 'SalesmanId' => $EmployeeId, 'status' => 1])->get('sales')->row('total');
                    $TotalEstimates = $this->db->select_sum('total')->where(['invoiceDate >= ' => date('Y-m-01'), 'invoiceDate <= ' => date('Y-m-31'), 'SalesmanId' => $EmployeeId, 'status' => 1])->get('estimates')->row('total');
                    $TargetAchieved = ($TotalSales + $TotalEstimates);

                    $EmployeeData = [
                        'SalesTarget' => $EmployeeQuery->row()->SalesTarget,
                        'TargetAchieved' => $TargetAchieved,
                        'Incentive' => $TargetAchieved > 500000 ? ((($TargetAchieved - $EmployeeQuery->row()->SalesTarget) * 2) / 100) : 0
                    ];
                    
                    $this->response([
                        'Status' => TRUE,
                        'Message' => 'Success.',
                        'Data' => $EmployeeData
                    ], REST_Controller::HTTP_OK);
                } else {
                    $this->response([
                        'Status' => FALSE,
                        'Message' => 'No record found.',
                        'Data' => 0
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
            } else {
                $this->response([
                    'Status' => FALSE,
                    'Message' => 'Enter required fields.',
                    'Data' => 0
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        }
        
        public function LeaveRequestSave_POST()
        {
            $EmployeeId = $this->post('EmployeeId');
            $StartDate = $this->post('StartDate');
            $EndDate = $this->post('EndDate');
            $Remark = $this->post('Remark');
            
            if (!empty($EmployeeId) && !empty($StartDate) && !empty($EndDate)) {
                $Where = ['Id' => $EmployeeId, 'Status' => 1, 'IsActive' => 1, 'IsDeleted' => 1];
                $EmployeeQuery = $this->db->select('Id, UserId, CompId, CompanyBranchId')->where($Where)->get('employees');
                if ($EmployeeQuery->num_rows()) {
                    $EmployeeInfo = $EmployeeQuery->row();
                    
                    $EmployeeLeaveData = [
                        'Date' => date('Y-m-d'),
                        'StartDate' => $StartDate,
                        'EndDate' => $EndDate,
                        'Remark' => $Remark,
                        'EmployeeId' => $EmployeeId,
                        'UserId' => $EmployeeInfo->UserId,
                        'CompId' => $EmployeeInfo->CompId,
                        'CompanyBranchId' => $EmployeeInfo->CompanyBranchId,
                        'Created' => date('d-m-Y h:i:s A'),
                        'CreatedBy' => get_title('employees', ['Id' => $EmployeeId], 'Name')
                    ];
                    $Result = $this->db->insert('leave_request', $EmployeeLeaveData);

                    if ($Result) {
                        $this->response([
                            'Status' => TRUE,
                            'Message' => 'Leave_requested successfully.',
                            'Data' => ""
                        ], REST_Controller::HTTP_OK);
                    } else {
                        $this->response([
                            'Status' => FALSE,
                            'Message' => 'Somethigs went wrong, try again.',
                            'Data' => 0
                        ], REST_Controller::HTTP_BAD_REQUEST);
                    }
                } else {
                    $this->response([
                        'Status' => FALSE,
                        'Message' => 'No record found.',
                        'Data' => 0
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
            } else {
                $this->response([
                    'Status' => FALSE,
                    'Message' => 'Enter required fields.',
                    'Data' => 0
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        }
        
        public function LeaveRequestData_POST()
        {
            $EmployeeId = $this->post('EmployeeId');
            $FromDate = $this->post('FromDate');
            $ToDate = $this->post('ToDate');
            
            if (!empty($EmployeeId) && !empty($FromDate) && !empty($ToDate)) {
                $Where = ['EmployeeId' => $EmployeeId, 'Date >= ' => $FromDate, 'Date >= ' => $ToDate];
                $LeaveRequestQuery = $this->db->select('Date, StartDate, EndDate, Remark, Status, AdminRemark')->where($Where)->order_by('Id', 'DESC')->get('leave_request');
                if ($LeaveRequestQuery->num_rows()) {
                    $this->response([
                        'Status' => TRUE,
                        'Message' => 'Success.',
                        'Data' => $LeaveRequestQuery->result()
                    ], REST_Controller::HTTP_OK);
                } else {
                    $this->response([
                        'Status' => FALSE,
                        'Message' => 'No record found.',
                        'Data' => 0
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
            } else {
                $this->response([
                    'Status' => FALSE,
                    'Message' => 'Enter required fields.',
                    'Data' => 0
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        }
        
        public function PartiesData_POST()
        {
            $EmployeeId = $this->post('EmployeeId');
            
            if (!empty($EmployeeId)) {
                $Where = ['Id' => $EmployeeId, 'Status' => 1, 'IsActive' => 1, 'IsDeleted' => 1];
                $EmployeeQuery = $this->db->select('Id')->where($Where)->get('employees');
                if ($EmployeeQuery->num_rows()) {
                    $this->response([
                        'Status' => TRUE,
                        'Message' => 'Success.',
                        'Data' => $this->db->select('id, name, gst, contact, area, latitude, longitude')->where('SalesmanId', $EmployeeId)->get('parties')->result()
                    ], REST_Controller::HTTP_OK);
                } else {
                    $this->response([
                        'Status' => FALSE,
                        'Message' => 'No record found.',
                        'Data' => 0
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
            } else {
                $this->response([
                    'Status' => FALSE,
                    'Message' => 'Enter required fields.',
                    'Data' => 0
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        }
        
        public function UpdatePartiesData_POST()
        {
            $PartyId = $this->post('PartyId');
            $EmployeeId = $this->post('EmployeeId');
            $Latitude = $this->post('Latitude');
            $Longitude = $this->post('Longitude');
            
            if (!empty($PartyId) && !empty($EmployeeId) && !empty($Latitude) && !empty($Longitude)) {
                $Where = ['Id' => $EmployeeId, 'Status' => 1, 'IsActive' => 1, 'IsDeleted' => 1];
                $EmployeeQuery = $this->db->select('Id')->where($Where)->get('employees');
                if ($EmployeeQuery->num_rows()) {
                    $PartyData = [
                        'Latitude' => $Latitude,
                        'Longitude' => $Longitude
                    ];
                    $Result = $this->db->where(['id' => $PartyId, 'SalesmanId' => $EmployeeId])->update('parties', $PartyData);
                    if ($Result) {
                        $PartyData['id'] = $PartyId;
                        $this->response([
                            'Status' => TRUE,
                            'Message' => 'Party update successfully.',
                            'Data' => $PartyData
                        ], REST_Controller::HTTP_OK);
                    } else {
                        $this->response([
                            'Status' => FALSE,
                            'Message' => 'Unable to update, try again.',
                            'Data' => ""
                        ], REST_Controller::HTTP_BAD_REQUEST);                        
                    }
                } else {
                    $this->response([
                        'Status' => FALSE,
                        'Message' => 'No record found.',
                        'Data' => ""
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
            } else {
                $this->response([
                    'Status' => FALSE,
                    'Message' => 'Enter required fields.',
                    'Data' => ""
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        }
        
        public function resetPartiesGeoTag_POST()
        {
            $PartyId = $this->post('PartyId');
            $EmployeeId = $this->post('EmployeeId');
            
            if (!empty($PartyId) && !empty($EmployeeId)) {
                $Where = ['Id' => $EmployeeId, 'Status' => 1, 'IsActive' => 1, 'IsDeleted' => 1];
                $EmployeeQuery = $this->db->select('Id')->where($Where)->get('employees');
                if ($EmployeeQuery->num_rows()) {
                    $PartyData = [
                        'Latitude' => '',
                        'Longitude' => ''
                    ];
                    $Result = $this->db->where(['id' => $PartyId, 'SalesmanId' => $EmployeeId])->update('parties', $PartyData);
                    if ($Result) {
                        $this->response([
                            'Status' => TRUE,
                            'Message' => 'Party update successfully.',
                            'Data' => ''
                        ], REST_Controller::HTTP_OK);
                    } else {
                        $this->response([
                            'Status' => FALSE,
                            'Message' => 'Unable to update, try again.',
                            'Data' => ""
                        ], REST_Controller::HTTP_BAD_REQUEST);                        
                    }
                } else {
                    $this->response([
                        'Status' => FALSE,
                        'Message' => 'No record found.',
                        'Data' => ""
                    ], REST_Controller::HTTP_BAD_REQUEST);
                }
            } else {
                $this->response([
                    'Status' => FALSE,
                    'Message' => 'Enter required fields.',
                    'Data' => ""
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        }
    }