public function pt_return()
{
$user_id = $this->session->userdata('user_id');
$crm_id = $this->session->userdata('crm_id');
$service_id = get_title('crm_servicesubgroup', ['sub_name' => "pt_return"], 'id');
$where = ['user_id' => $user_id, 'crm_id' => $crm_id];
$data['pt_returnData'] = $this->user_model->getAry('crm_pt_return', '', '', '', $where);
$this->session->set_userdata('sub_service_id', $service_id);
$data['page'] = 'professionaltax/pt_return/index';
$this->load->view('crm/main', $data);
}


protected function pt_return_rules()
{
$rules = array(
array(
'field' => 'party',
'label' => 'Party',
'rules' => 'trim|required|xss_clean|callback_unique_party_for_pt_return'
),
array(
'field' => 'date',
'label' => 'Date',
'rules' => 'trim|required|xss_clean'
),

);
return $rules;
}

protected function pt_return_rules_update()
{
$rules = array(
array(
'field' => 'party',
'label' => 'Party',
'rules' => 'trim|required|xss_clean|callback_unique_party_for_pt_return_update'
),
array(
'field' => 'date',
'label' => 'Date',
'rules' => 'trim|required|xss_clean'
),

);
return $rules;
}



public function pt_return_save()
{

if ($_POST) {
$rules = $this->pt_return_rules();
$this->form_validation->set_rules($rules);
if ($this->form_validation->run() == false) {
$response = array(
'status' => 'error',
'message' => validation_errors()
);
} else {
$data['party_id'] = $this->input->post('party');
$data['remark'] = $this->input->post('remark');
$data['date'] = date('Y-m-d h:i:s', strtotime($this->input->post('date')));
$data['timestamp'] = time();
$data['subservice_id'] = $this->session->userdata('sub_service_id');
$data['user_id'] = $this->session->userdata('user_id');
$data['status'] = $this->input->post('status');

$data['crm_id'] = $this->session->userdata('crm_id');
$data['month'] = date('m');
$data['year'] = date('Y');
$result = $this->user_model->saveAry('crm_pt_return', $data);
if ($result) {
$response = array(
'status' => 'success',
'message' => "<h3>PT Annual Return Added</h3>"
);
} else {
$response = array(
'status' => 'error',
'message' => "<h3>Something Went Wrong</h3>"
);
}
}
} else {
$response = array(
'status' => 'error',
'message' => "<h3>Required Field can not Be Empty</h3>"
);
}
$this->output
->set_content_type('application/json')
->set_output(json_encode($response));
}

public function pt_returnReport($pagename, $cateId)
{

$data['category_id'] = $cateId;
$this->load->view('crm/pt_return/' . $pagename, $data);
}

public function delete_pt_return($id)
{
if (!empty($id)) {
$result = $this->user_model->deleteRow('crm_pt_return', ['id' => $id]);
if ($result) {
$this->session->set_flashdata(['msg' => 'PT Annual Return Deleted Successfully.', 'class' => 'success']);
return redirect('crm/master/pt_return');
} else {
$this->session->set_flashdata(['msg' => 'Failed To Delete.', 'class' => 'danger']);
return redirect('crm/master/pt_return');
}
}
}

public function edit_pt_return($id)
{
if (!empty($id)) {
$data['result'] = $this->user_model->getRow('crm_pt_return', ['id' => $id]);
$this->load->view('crm/professionaltax/pt_return/edit', $data);
}
}

public function view_pt_return($id)
{
if (!empty($id)) {
$data['result'] = $this->user_model->getRow('crm_pt_return', ['id' => $id]);
$this->load->view('crm/professionaltax/pt_return/view', $data);
}
}

public function update_pt_return()
{

if ($_POST) {
$rules = $this->pt_return_rules_update();
$this->form_validation->set_rules($rules);
if ($this->form_validation->run() == false) {
$response = array(
'status' => 'error',
'message' => validation_errors()
);
} else {
$id = $this->input->post('id');
$data['party_id'] = $this->input->post('party');
$data['remark'] = $this->input->post('remark');
$data['status'] = $this->input->post('status');


$data['date'] = date('Y-m-d h:i:s', strtotime($this->input->post('date')));
$data['timestamp'] = time();
$result = $this->user_model->updateAry('crm_pt_return', $data, ['id' => $id]);
if ($result) {
$response = array(
'status' => 'success',
'message' => "<h3>PT ANNUAL RETURN Updated</h3>"
);
} else {
$response = array(
'status' => 'error',
'message' => "<h3>Something Went Wrong</h3>"
);
}
}
} else {
$response = array(
'status' => 'error',
'message' => "<h3>Required Field can not Be Empty</h3>"
);
}
$this->output
->set_content_type('application/json')
->set_output(json_encode($response));
}

public function unique_party_for_pt_return()
{
$party = $this->input->post('party');
$user_id = $this->session->userdata('user_id');
$crm_id = $this->session->userdata('crm_id');

$where = ['user_id' => $user_id, 'crm_id' => $crm_id, 'party_id' => $party, 'month' => date('m'), 'year' => date('Y')];
$return_value = $this->user_model->getRow('crm_pt_return', $where);
// print_r($return_value);
if ($return_value) {
$this->form_validation->set_message('unique_party_for_pt_return', 'Party should be Unique');
return false;
}
return true;
}

public function unique_party_for_pt_return_update()
{
$party = $this->input->post('party');
$id = $this->input->post('id');
$user_id = $this->session->userdata('user_id');
$crm_id = $this->session->userdata('crm_id');
$where = ['user_id' => $user_id, 'crm_id' => $crm_id, 'party_id' => $party, 'id!=' => $id, 'month' => date('m'), 'year' => date('Y')];
$return_value = $this->user_model->getRow('crm_pt_return', $where);
// print_r($return_value);
if ($return_value) {
$this->form_validation->set_message('unique_party_for_pt_return_update', 'Party should be Unique');
return false;
}
return true;
}


public function brand()
{
$data['page'] = 'admin/homepage/brand';
$order = ['col' => 'added_at', 'order' => 'DESC'];
$parties = $this->model->getAry('our_brand', $order);
$data['parties'] = $parties;
$this->load->view('index', $data);
}

public function addbrand()
{
$data['page'] = 'admin/homepage/add';
$this->load->view('index', $data);
}

public function savebrand()
{

if ($_FILES['brand_image']['name']) {
$config['upload_path'] = './uploads/';
$config['allowed_types'] = 'gif|jpg|png';
$config['file_name'] = $_FILES['brand_image']['name'];
$this->load->library('upload', $config);
$this->upload->initialize($config);

if (!$this->upload->do_upload('brand_image')) {
$error = array('error' => $this->upload->display_errors());
} else {

$uploadData = $this->upload->data();
$image = $uploadData['file_name'];
$formData1['image'] = $image;
}
}
$data['page'] = 'admin/homepage/add';
$formData = $this->input->post();
$res = $this->model->getRow('our_brand', ['status' => 1]);
$text = json_encode($formData);

$formData1['pageDetails'] = $text;
if ($res == '') {
$result = $this->model->saveAry('our_brand', $formData1);
} else {

$result = $this->model->updateAry('our_brand', $formData1, ['status' => 1]);
}
if ($result) {
return redirect('our-brand');
} else {
$this->load->view('index', $data);
}
}

public function editbrand()

{
$data['page'] = 'admin/homepage/edit';
$res = $this->model->getRow('our_brand', ['status' => 1]);
$data['result'] = $res;
$this->load->view('index', $data);
}


protected function office_rules()
{
$rules = array(
array(
'field' => 'applicant_name',
'label' => 'Applicant',
'rules' => 'trim|required|xss_clean'
),
array(
'field' => 'amount',
'label' => 'Amount',
'rules' => 'trim|required|xss_clean'
),
array(
'field' => 'type',
'label' => 'Type',
'rules' => 'trim|required|xss_clean'
),
array(
'field' => 'status',
'label' => 'Status',
'rules' => 'trim|required|xss_clean'
),

);
return $rules;
}

public function office_expence_save()
{

if ($_POST) {
$rules = $this->office_rules();
$this->form_validation->set_rules($rules);
if ($this->form_validation->run() == false) {
$response = array(
'status' => 'error',
'message' => validation_errors()
);
} else {
$id= $this->input->post('id');
$data['remark'] = $this->input->post('remark');
$data['amount'] = $this->input->post('amount');
$data['type'] = $this->input->post('type');
$data['status'] = $this->input->post('status');
$data['applicant_name'] = $this->input->post('applicant_name');
$result = $this->user_model->updateAry('crm_office_expence', $data,['id'=>$id]);
if ($result) {
$response = array(
'status' => 'success',
'message' => "<h3>Office Expence Updated</h3>"
);
} else {
$response = array(
'status' => 'error',
'message' => "<h3>Something Went Wrong</h3>"
);
}
}
} else {
$response = array(
'status' => 'error',
'message' => "<h3>Required Field can not Be Empty</h3>"
);
}
$this->output
->set_content_type('application/json')
->set_output(json_encode($response));
}