<!DOCTYPE html>
<html lang="en-in" dir="ltr"> 
    <head>
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

            // Defaults for footer alerts (dashboard may override via controller $data)
            $NewFinancialYear = $NewFinancialYear ?? null;
            $NewFinancialYearFlag = $NewFinancialYearFlag ?? null;
            $ChequeData = $ChequeData ?? null;
        ?>
        <?php include APPPATH . 'views/inventory_sep/common/library.php'; ?>
    </head>
    <body>
        <?php
            include APPPATH . 'views/inventory_sep/common/header.php';
            include APPPATH . 'views/inventory_sep/' . $page . '.php';
            include APPPATH . 'views/inventory_sep/common/footer.php';
        ?>
    </body>
</html>
