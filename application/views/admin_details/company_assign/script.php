<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/popper.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/jquery-ui.min.js"></script>
<script>
    function assignValue(id) {
        jQuery.noConflict();
        $("#adding_party_ok .modal-dialog .modal-body").load("<?= base_url() ?>company_assign/assign_to_user/" + id);
        $("#addparty").html("Assign Company");
        $("#adding_party_ok").modal("show");
    }
    function blockSms(id) {
        jQuery.noConflict();
        $("#sms_block .modal-dialog .modal-body").load("<?= base_url() ?>company_assign/blocked_by_company/" + id);
        $("#sms_block_title").html("Block Sms for The Company");
        $("#sms_block").modal("show");
    }

    function productStock(productId) {
        jQuery.noConflict();
        $("#productStock .modal-dialog .modal-body").load(baseurl + "index.php/company_assign/stock/" + productId);
        $("#productStockLabels").html("COMPANY REPORT");
        $("#productStock").modal("show");

    }

    function viewCompanyUsers(compid) {
        jQuery.noConflict();
        $("#companyusers .modal-dialog .modal-body").load(baseurl + "company_assign/viewCompanyUsers/" + compid);
        $("#companyuserslabel").html("Company Users Report");
        $("#companyusers").modal("show");
    }


    $(document).ready(function() {
    $(".Datatable").DataTable({
        dom: 'lBfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5',
            'pageLength',
        ],
    });
})
</script>