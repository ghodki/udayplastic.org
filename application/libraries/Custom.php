<?php
defined('BASEPATH') or exit('No direct script access allowed');

#[AllowDynamicProperties]
class Custom
{
	public function getService()
	{
		$CI = &get_instance();
		$CI->load->model('user_model');
		$service = $CI->user_model->getResults('service');
		return $service;
	}
	public function _permisssion($role_id)
	{
		$CI = &get_instance();
		$CI->load->model('user_model');
		$where = ['role_id' => $role_id];
		$permission = $CI->user_model->getAry('permisssion', '', '', $where);
		return $permission;
	}
}
