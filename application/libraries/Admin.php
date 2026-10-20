<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller
{


	public function __construct()
	{
		$CI = &get_instance();
		$CI->load->model('user_model');
		// $module            = $this->uri->segment(1);
		// $action            = $this->uri->segment(2);
		// $siteInfo          = $this->site_m->get_site();
		// $frontendManager   = $this->_frontendManager($siteInfo);
		$permissionManager = $this->_permissionManager();
		// print_r($permissionManager);
		// die;
		if (!empty($permissionManager)) {
			redirect($permissionManager);
		}
		$userTypeID = $this->session->userdata('roles');
	}




	private function _permissionManager()
	{
		$url             = '';
		$permissionArray = [];
		$userdata        = $this->session->userdata;
		if ($this->session->userdata('user_id') && $this->session->userdata('roles')) {
			if (isset($userdata['roles']) && !isset($userdata['get_permission'])) {
				// $features = $this->permission_m->get_permission();
				$features = $this->user_model->get_modules_with_permission($userdata['roles']);
				// echo '<pre>';
				// print_r($features);
				// die;

				if ($features) {
					foreach ($features as $featureKey => $feature) {
						$permissionArray['master_permission_set'][trim($feature->name)] = $feature->active;
					}
					//change take exam yes

					// $permissionArray['master_permission_set']['take_exam'] = 'yes';
					$this->session->set_userdata(['get_permission' => true]);
					$this->session->set_userdata($permissionArray);
				}
				$sessionPermission     = $this->session->userdata('master_permission_set');
			}
		}
	}
}
