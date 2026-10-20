<!DOCTYPE html>
<html lang="en-in" dir="ltr">
    <head>
        <?php include APPPATH . 'views/layout/common/library.php'; ?>
    </head>
    <body>
        <?php
            include APPPATH . 'views/layout/common/header.php';
            include APPPATH . 'views/' . $page . '.php';
            include APPPATH . 'views/layout/common/footer.php';
        ?>
    </body>
</html>
