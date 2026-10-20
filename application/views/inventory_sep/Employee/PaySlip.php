<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Uday Plastic - PaySlip</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="x-ua-compatible" content="ie=edge" />
        <link type="image/png" rel="shortcut icon" href="<?= base_url(); ?>assets/images/favicon.png" />
        <link type="text/css" rel="stylesheet" href="<?= base_url(); ?>inv-assets/css/bootstrap.min.css" />
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" />
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/jquery.min.js"></script>
        <style type="text/css">
            @media print{
                @page {
                    size: auto;
                }
            }
            body {
                font-size: 15px; 
                font-family: sans-serif;
            }
            .bg-dark {
                background-color: #1f1f1f !important;
            }
            th, td {
                border: 1px solid #000;
            }
        </style>
        <script type="text/javascript">
            $(document).ready(function() {
                window.print();
                window.onafterprint = function() {
                    window.close();
                };
            });
        </script>
    </head>
    <body>
        <table class="table table-sm">
            <thead class="bg-dark">
                <tr>
                    <th style="width: 50%; border: none;" class="p-2">
                        <img src="<?= base_url(); ?>uploads/complogo/<?= $CompBranchInfo->logo; ?>" alt="<?= $CompBranchInfo->name; ?>" style="width: 150px; margin-bottom: 15px;" />
                    </th>
                    <th style="width: 50%; border: none;" class="p-2">
                        <p class="text-white mb-1" style="font-weight: 800; font-size: 25px;">
                            <?= $CompBranchInfo->name; ?>
                        </p>
                        <p class="text-white">
                            <?php
                                if ($this->session->userdata('BranchRole')) {
                                    echo get_title('companybranch', ['Id' => $CompBranchInfo->CompBranchId], 'Address');
                                } else {
                                    echo get_title('inventory_billing', ['id' => $CompBranchInfo->comp_id], 'address');
                                }
                                echo ", " . get_title('state_list', ['id' => $this->session->userdata('state')], 'state'); 
                            ?>, India 
                        </p>
                    </th>
                </tr>
            </thead>
            <thead>
                <tr>
                    <th colspan="2" class="text-center pt-3" style="border: none;">
                        <strong>
                            Pay slip for the month of <?= $SalaryInfo->Month; ?> - <?= $SalaryInfo->Year; ?> 
                        </strong>
                        <hr class="bg-navy" />
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="2" style="padding: 0px; border: none;">
                        <table class="table table-sm">
                            <tr>
                                <th>Name</th>
                                <td><?= $EmployeeInfo->Name; ?></td>
                                <th>Bank A/c Number</th>
                                <td><?= $EmployeeInfo->AccountNumber; ?></td>
                            </tr>
                            <tr>
                                <th>Designation</th>
                                <td><?= get_title('position', ['Id' => $EmployeeInfo->RoleId], 'PostName'); ?></td>
                                <th>Bank Name</th>
                                <td><?= $EmployeeInfo->BankName; ?></td>
                            </tr>
                            <tr>
                                <th>Employee Code</th>
                                <td><?= $EmployeeInfo->EmployeeId; ?></td>
                                <th>PAN Number</th>
                                <td><?= $EmployeeInfo->PANNumber; ?></td>
                            </tr>
                            <tr>
                                <th>Total Days</th>
                                <td><?= $SalaryInfo->TotalDays; ?></td>
                                <td colspan="2"></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="padding: 0px; border: none;">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th class="pt-2" colspan="3" style="font-size: 20px;">Earnings</th>
                                    <th class="pt-2" colspan="2" style="font-size: 20px;">Deduction</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th></th>
                                    <th>Gross Earning</th>
                                    <th>Actual Earning</th>
                                    <th></th>
                                    <th>Deduction</th>
                                </tr>
                                <tr>
                                    <th>BASIC</th>
                                    <td><?= number_format($EmployeeInfo->Basic, 2); ?></td>
                                    <td><?= number_format($SalaryInfo->Basic, 2); ?></td>
                                    <th>Advance</th>
                                    <th>0.00</th>
                                </tr>
                                <tr>
                                    <th>HRA</th>
                                    <td><?= number_format($EmployeeInfo->HRA, 2); ?></td>
                                    <td><?= number_format($SalaryInfo->HRA, 2); ?></td>
                                    <th>Income Tax</th>
                                    <th><?= number_format($SalaryInfo->IncomeTax, 2); ?></th>
                                </tr>
                                <tr>
                                    <th>CONVEYANCE ALLOWANCE</th>
                                    <td><?= number_format($EmployeeInfo->ConveyanceAllowance, 2); ?></td>
                                    <td><?= number_format($SalaryInfo->ConveyanceAllowance, 2); ?></td>
                                    <th>ESI</th>
                                    <th><?= number_format($SalaryInfo->EmployeeESI, 2); ?></th>
                                </tr>
                                <tr>
                                    <th>MEDICAL ALLOWANCE</th>
                                    <td>0.00</td>
                                    <td>0.00</td>
                                    <th>PF</th>
                                    <th><?= number_format($SalaryInfo->EmployeePF, 2); ?></th>
                                </tr>
                                <tr>
                                    <th>SPECIAL ALLOWANCE</th>
                                    <td><?= number_format($EmployeeInfo->StaffSpecialAllowance, 2); ?></td>
                                    <td><?= number_format($SalaryInfo->StaffSpecialAllowance, 2); ?></td>
                                    <th>Profession Tax</th>
                                    <th><?= number_format($SalaryInfo->ProfessionTax, 2); ?></th>
                                </tr>
                                <tr>
                                    <th class="p-3"></th>
                                    <td></td>
                                    <td></td>
                                    <th></th>
                                    <th></th>
                                </tr>
                                <tr>
                                <th>Reward</th>
                                <td>-</td>
                                <td>-</td>
                                <th></th>
                                <th></th>
                            </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>Total</th>
                                    <th><?= number_format($EmployeeInfo->CostToCompany, 2); ?></th>
                                    <th><?= number_format($SalaryInfo->CTC, 2); ?></th>
                                    <th></th>
                                    <th><?= number_format($SalaryInfo->MonthlyDeduction, 2); ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th style="border-top: none; border-left: none; border-right: none;" class="pt-4">NET SALARY</th>
                    <th style="border-top: none; border-left: none; border-right: none;" class="pt-4"><?= number_format($SalaryInfo->NetTakeHomeSalary, 2); ?></th>
                </tr>
                <tr>
                    <th style="border-top: none; border-left: none; border-right: none;" class="pt-1">GROSS SALARY</th>
                    <th style="border-top: none; border-left: none; border-right: none;" class="pt-1"><?= number_format($SalaryInfo->CTC, 2); ?></th>
                </tr>
            </tfoot>
        </table>
        <img src="<?= base_url(); ?>uploads/signature/<?= $CompBranchInfo->scan_signature; ?>" alt="<?= $CompBranchInfo->name; ?>" style="width: 150px;" class="pt-5" />
        <p class="text-center pt-5">
            This is computer generated copy by <?= $CompBranchInfo->name; ?>
        </p>
    </body>
</html>