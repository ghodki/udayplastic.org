<?php 
    $role = $this->session->userdata('roles');
    $CompBranchId = $this->session->userdata('CompBranchId');
    $CompId = $this->session->userdata('comp_id');
    if ($this->session->userdata('BranchRole')) {
        if (!isset($CompBranchId) && empty($CompBranchId)) {
            return redirect(base_url() . 'CompanyBranch');
        }
    } else {
        if (!isset($CompId) && empty($CompId)) {
            return redirect(base_url() . 'inventory_billing');
        }
    }

    $fy = explode("-", get_title('year', ['id' => $this->session->userdata('financial_year')], 'year'));
    
    include_once('adminPart/index_inventory.php'); 

    include_once($page . '.php'); 
    
    include_once('adminPart/footer.php'); 