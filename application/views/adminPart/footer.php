            <footer class="main-footer pl-3 pr-3 pt-0 pb-0">
                <div class="row">
                    <div class="col-md-2">
                        <a href="<?= base_url(); ?>" title="Uday Plastic" class="brand-link" target="_blank">
                            <?php $logo = $this->user_model->getResult('logo', ['user_id' => 1]); if ($logo) { ?>
                            <img src="<?= base_url(); ?>uploads/logo/<?= $logo[0]->logo; ?>" alt="logo" style="width: 50px;" />
                            <?php } ?>
                        </a>
                    </div>
                    <div class="col-md-8">
                        <p class="text-center pt-3">
                            <b>
                                ©2021 Uday Plastic All Rights Reserved
                            </b>
                        </p>
                    </div>
                    <div class="col-md-2">
                        <div class="float-right d-none d-sm-inline-block pt-3">
                            <b>Version</b> 3.0
                        </div>
                    </div>
                </div>
            </footer>
        </div>

        <style type="text/css">
            .bg-gradient-green, .header {
                background: #2C4668 !important;
            }
        </style>

        <script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.min.js"></script>
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/popper.min.js"></script>
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/jquery-ui.min.js"></script>
        <script type="text/javascript" src="<?= base_url(); ?>crm/assets/js/selectize.min.js"></script>
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/toastr.min.js"></script>
        <script type="text/javascript" src="<?= base_url(); ?>plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
        <!-- AdminLTE App -->
        <script type="text/javascript" src="<?= base_url(); ?>dist/js/adminlte.js"></script>
        <!-- OPTIONAL SCRIPTS -->
        <script type="text/javascript" src="<?= base_url(); ?>dist/js/demo.js"></script>
        <!-- jQuery Mapael -->
        <script type="text/javascript" src="<?= base_url(); ?>plugins/jquery-mousewheel/jquery.mousewheel.js"></script>
        <script type="text/javascript" src="<?= base_url(); ?>plugins/raphael/raphael.min.js"></script>
        <script type="text/javascript" src="<?= base_url(); ?>plugins/jquery-mapael/jquery.mapael.min.js"></script>
        <script type="text/javascript" src="<?= base_url(); ?>plugins/jquery-mapael/maps/usa_states.min.js"></script>
        <script type="text/javascript" src="<?= base_url(); ?>plugins/chart.js/Chart.min.js"></script>
        <script type="text/javascript" src="<?= base_url(); ?>dist/js/sweetalert2.all.min.js"></script>
        <script type="text/javascript" src="<?= base_url(); ?>dist/js/select2.min.js"></script>
        <script type="text/javascript" src="<?= base_url(); ?>assets/dt/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/dataTables.scroller.min.js"></script>
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/buttons.print.min.js"></script>
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/dataTables.buttons.min.js"></script>
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/jszip.min.js"></script>
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/pdfmake.min.js"></script>
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/vfs_fonts.js"></script>
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/buttons.html5.min.js"></script>
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/buttons.print.min.js"></script>
        <script type="text/javascript" src="<?= base_url(); ?>assets/js/buttons.colVis.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function(){
                $('[data-toggle="tooltip"]').tooltip();
            });
        
            $(document).ready(function() {
                <?php unset($_SESSION['msg']); ?>
                $('#loading-image').hide();
                jQuery.noConflict();
                setTimeout(function() {
                    $('.alert').hide(1000, function() {
                        $(this).remove();
                    });
                }, 1000);
            });

            $('.floatNumber').keypress(function(eve) {
                if ((eve.which != 46 || $(this).val().indexOf('.') != -1) && (eve.which < 48 || eve.which > 57) || (eve.which == 46 && $(this).caret().start == 0)) {
                    eve.preventDefault();
                }
                // this part is when left part of number is deleted and leaves a . in the leftmost position. For example, 33.25, then 33 is deleted
                $('.floatNumber').keyup(function(eve) {
                    if ($(this).val().indexOf('.') == 0) {
                        $(this).val($(this).val().substring(1));
                    }
                });
            });

            function setNotification(table_id, status) {
                $.ajax({
                    url     :   "<?= base_url(); ?>offer/setNotification",
                    type    :   "POST",
                    data    :   {table_id: table_id, notification_status: status},
                    success :   function(response) {
                                    response = JSON.parse(response);
                                    console.log(response, "responce");
                                    if (response.status === true) {
                                        toastr.success(response.message);
                                        setTimeout(function() {
                                            window.location.reload();
                                        }, 1000);
                                    } else {
                                            toastr.error(response.message);
                                    }
                                }
                });
            }

            function IsNumeric(evt) {
                evt = (evt) ? evt : window.event;
                var charCode = (evt.which) ? evt.which : evt.keyCode;
                if (charCode > 31 && (charCode < 48 || charCode > 57)) {
                    return false;
                }
                return true;
            }

            $(document).ready(function() {
                $(".addnew").click(function() {
                    $(".addnewrole").toggle();
                });
            });

            $('.module').change(function(event) {
                var flag = true;
                $('.module').each(function() {
                    if (!$(this).is(":checked")) {
                        flag = false;
                    }
                });
                $('#selectAll').prop('checked', flag);
            });

            $('.childModule').change(function(event) {
                var id = $(this).attr('id');
                var flag = false;
                $('.childModule_' + id).each(function() {
                    if ($(this).is(":checked")) {
                        flag = true;
                    }
                });
                $('.parentModule_' + id).prop('checked', flag);
            });

            $('.parentModule').change(function(event) {
                var id = $(this).attr('id');
                $('.childModule_' + id).prop('checked', this.checked);
                //childModule_1
            });

            $('#selectAll').change(function(event) {
                $('input:checkbox').not(this).prop('checked', this.checked);
            });

            $("#wrapper").toggleClass("toggled");
            $("#menu-toggle").click(function(e) {
                e.preventDefault();
                console.log("hrllo")
                $("#wrapper").toggleClass("toggled");
            });

            function checkuser() {
                var select_role_id = document.getElementById("roles").value;
                console.log(select_role_id);
                $(".poultryNamediv").hide();
                if (select_role_id == 2) {
                    $(".poultryNamediv").show();
                }
            }
            
            function loginInfo(id) {
                $("#loginmodal .modal-dialog .content").load("<?= base_url('reports/content'); ?>/" + id);
                $("#loginmodal").modal("show");
            }
        </script>
    </body>
</html>