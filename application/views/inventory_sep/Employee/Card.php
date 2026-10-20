<!DOCTYPE html>
<html>
    <head>
        <title>Uday Plastic - Employee Card</title>
        <meta charset="utf-8" />
    	<meta name="viewport" content="width=device-width, initial-scale=1" />
    	<meta http-equiv="x-ua-compatible" content="ie=edge" />
        <link type="image/png" rel="shortcut icon" href="<?= base_url(); ?>assets/images/favicon.png" />
        <link rel="stylesheet" href="<?= base_url(); ?>dist/css/adminlte.min.css" />
        <link rel="stylesheet" href="<?= base_url(); ?>assets/css/bootstrap.min.css" />
        <link rel="stylesheet" href="<?= base_url(); ?>assets/css/font-awesome.min.css" />
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/jquery.min.js"></script>
        <style type="text/css">
            body {
                line-height: 1.1;
            }
            @media print{
                @page {
                    size: auto;
                }
            }
            .card-title {
                float: none;
                margin-bottom: .25rem
            }
            .card-title img {
                width: 100px;
            }
            .card-header, .card-footer {
                padding: 0.25rem 0.25rem;
            }
            .card-body {
                padding: 0.50rem 0.50rem;
            }
            p {
                margin-bottom: 0rem;
            }
        </style>
    </head>
    <body>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 col-sm-6 col-lg-6 col-xs-6 col-xl-6">
                    <div class="card card-outline card-maroon">
                        <div class="card-header bg-navy">
                            <div class="card-title text-center">
                                <?php
                                    $compBranchLogo = $CompBranchInfo->logo;
                                    $compBranchLogoFile = "uploads/complogo/" . $compBranchLogo;
                                    if (!empty($compBranchLogo) && file_exists($compBranchLogoFile)) {
                                ?>
                                <img src="<?= base_url() . $compBranchLogoFile; ?>" alt="<?= $CompBranchInfo->name; ?>" style="width: 100px;" />
                                <?php } else { ?>
                                <h3 class="text-white font-weight-bold">
                                    <?= $CompBranchInfo->name; ?>
                                </h3>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 col-sm-4 col-lg-4 col-xs-4 col-xl-4">
                                    <?php
                                        $Photograph = $Employee->Photograph;
                                        $PhotograpFile = "uploads/Employee/Photograph/" . $Photograph;
                                        if (!empty($Photograph) && file_exists($PhotograpFile)) {
                                    ?>
                                    <img src="<?= base_url() . $PhotograpFile; ?>" class="img-circle" alt="photograph" style="width: 60px;" />
                                    <?php } else { ?>
                                    <img src="<?= base_url(); ?>assets/images/avatar.png" class="img-circle" alt="avatar" style="width: 100px;" />
                                    <?php } ?>
                                    <br />
                                    <br />
                                    <br />
                                    <small class="text-center">--------------</small>
                                    <br />
                                    <small style="font-size: 12px; white-space: nowrap;">Authorized Signatory</small>
                                </div>
                                <div class="col-md-8 col-sm-8 col-lg-8 col-xs-8 col-xl-8">
                                    <h5 class="text-maroon" style="white-space: nowrap;"><?= $Employee->Name; ?></h5>
                                    <h6 class="text-navy"><?= get_title('position', ['Id' => $Employee->RoleId], 'PostName'); ?></h6>
                                    <small class="text-navy">
                                        Employee Id : <?= $Employee->EmployeeId; ?>
                                        <br />
                                        Mobile : +91-<?= $Employee->Mobile; ?>
                                        <br />
                                        Email : <?= $Employee->Email; ?>
                                        <br />
                                        Gender : <?= $Employee->Gender; ?>
                                        <br />
                                        Blood Group : <?= $Employee-> BloodGroup; ?>
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-maroon text-center">
                            <small style="font-size: 12px;">
                                <i class="fa fa-globe"></i> www.udayplastic.co.in
                                <?php if ($this->session->userdata('BranchRole')) { ?>
                                <i class="fa fa-mobile"></i> +91-<?= get_title('companybranch', ['Id' => $CompBranchInfo->CompBranchId], 'Mobile'); ?>
                                <i class="fa fa-envelope"></i> <?= get_title('companybranch', ['Id' => $CompBranchInfo->CompBranchId], 'Email'); ?>
                                <?php } else { ?>
                                <i class="fa fa-mobile"></i> +91-<?= get_title('inventory_billing', ['id' => $CompBranchInfo->comp_id], 'number'); ?>
                                <i class="fa fa-envelope"></i> <?= get_title('inventory_billing', ['id' => $CompBranchInfo->comp_id], 'email'); ?>
                                <?php } ?>
                            </small>
                            <br />
                            <small>
                                <i class="fa fa-map-marker"></i> 
                                <?php
                                    if ($this->session->userdata('BranchRole')) {
                                        echo get_title('companybranch', ['Id' => $CompBranchInfo->CompBranchId], 'Address');
                                    } else {
                                        echo get_title('inventory_billing', ['id' => $CompBranchInfo->comp_id], 'address');
                                    }
                                    echo ", " . get_title('state_list', ['id' => $this->session->userdata('state')], 'state'); 
                                ?>, India 
                            </small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-lg-6 col-xs-6 col-xl-6">
                    <div class="card card-outline card-maroon">
                        <div class="card-header bg-navy">
                            <div class="card-title text-center">
                                <h3 class="text-white font-weight-bold">
                                    <?= $CompBranchInfo->name; ?>
                                </h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12 col-xl-12">
                                    <small>
                                        <h6 class="text-danger">
                                            Terms & Conditions
                                        </h6>
                                        <p>
                                            <?php
                                                $Where = ['UserId' => $this->session->userdata('user_id'), 'CompId' => $this->session->userdata('comp_id')];
                                                if ($this->session->userdata('BranchRole')) {
                                                    $Where['CompanyBranchId'] = $this->session->userdata('CompBranchId');
                                                }
                                                echo get_title('employee_config', $Where, 'TermsAndConditions');
                                            ?>
                                        </p>
                                    </small>
                                </div>
                                <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12 col-xl-12">
                                    <br />
                                    <small class="text-navy">
                                        Joining : <?= !empty($Employee->JoiningDate) ? date('d-m-Y', strtotime($Employee->JoiningDate)) : ""; ?>
                                        <br />
                                        Expiry : <?= !empty($Employee->ExpiryDate) ? date('d-m-Y', strtotime($Employee->ExpiryDate)) : ""; ?>
                                        <br />
                                        Emergency No. : <?= !empty($Employee->EmergencyNumber) ? "+91-" . $Employee->EmergencyNumber: ""; ?>
                                    </small>
                                    <br />
                                    <small class="pull-right">----------------</small>
                                    <br />
                                    <small class="pull-right" style="font-size: 12px;">Employee Signature</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer bg-maroon text-center">
                            <small style="font-size: 12px;">
                                <i class="fa fa-globe"></i> www.udayplastic.co.in
                                <?php if ($this->session->userdata('BranchRole')) { ?>
                                <i class="fa fa-mobile"></i> +91-<?= get_title('companybranch', ['Id' => $CompBranchInfo->CompBranchId], 'Mobile'); ?>
                                <i class="fa fa-envelope"></i> <?= get_title('companybranch', ['Id' => $CompBranchInfo->CompBranchId], 'Email'); ?>
                                <?php } else { ?>
                                <i class="fa fa-mobile"></i> +91-<?= get_title('inventory_billing', ['id' => $CompBranchInfo->comp_id], 'number'); ?>
                                <i class="fa fa-envelope"></i> <?= get_title('inventory_billing', ['id' => $CompBranchInfo->comp_id], 'email'); ?>
                                <?php } ?>
                            </small>
                            <br />
                            <small>
                                <i class="fa fa-map-marker"></i> 
                                <?php
                                    if ($this->session->userdata('BranchRole')) {
                                        echo get_title('companybranch', ['Id' => $CompBranchInfo->CompBranchId], 'Address');
                                    } else {
                                        echo get_title('inventory_billing', ['id' => $CompBranchInfo->comp_id], 'address');
                                    }
                                    echo ", " . get_title('state_list', ['id' => $this->session->userdata('state')], 'state'); 
                                ?>, India 
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            $(document).ready(function() {
                window.print();
                window.onafterprint = function() {
                    //window.close();
                };
            });
        </script>
    </body>
</html>