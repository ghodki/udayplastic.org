<!DOCTYPE html>
<html lang="en">
    <head>
        <?php include_once('inventory_sep/Employee/Includes/Library.php'); ?>
    </head>
    <body class="">
        <?php
            $fyYear = get_title('year', ['id' => $this->session->userdata('financial_year')], 'year');
            $fy = !empty($fyYear) ? explode('-', $fyYear) : [date('Y'), substr(date('Y') + 1, -2)];
            if (!isset($fy[1])) {
                $fy[1] = substr(((int) $fy[0]) + 1, -2);
            }

            include_once('inventory_sep/Employee/Includes/Header.php');
            
            include_once($page . '.php');
            
            include_once('inventory_sep/Employee/Includes/Footer.php'); 
        ?>
    </body>
</html>