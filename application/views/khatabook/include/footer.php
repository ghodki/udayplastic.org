  <!-- ======= Footer ======= -->
  <footer id="footer" class="footer">
      <div class="copyright">
          <p class="text-center">&copy; Copyright <strong>©2021 Umatic India Pvt Ltd All Rights Reserved</strong></p>
          <div class="float-right d-none d-sm-inline-block">
          </div>
      </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <style>
      .pagination a {
          background: #ffffff;
          padding: 5px 10px 5px 10px;
          border-radius: 5px;
          color: #000000;
          border: 0.5px solid #cfcdcd;
      }

      .pagination b {
          background: #7c7171;
          padding: 5px 10px 5px 10px;
          border-radius: 5px;
          color: #ffffff;
      }
  </style>
  <!-- Vendor JS Files -->
  <script src="<?php echo base_url(); ?>assets/js/toastr.min.js"></script>

  <script src="<?= base_url() ?>crm/assets/vendor/apexcharts/apexcharts.min.js"></script>
  <script src="<?= base_url() ?>crm/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="<?= base_url() ?>crm/assets/vendor/chart.js/chart.min.js"></script>
  <script src="<?= base_url() ?>crm/assets/vendor/echarts/echarts.min.js"></script>
  <script src="<?= base_url() ?>crm/assets/vendor/quill/quill.min.js"></script>
  <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.colVis.min.js"></script>
  <script src="https://datatables.net/extensions/buttons/examples/initialisation/export.html"></script>
  <script src="<?= base_url() ?>crm/assets/vendor/tinymce/tinymce.min.js"></script>
  <script src="<?= base_url() ?>crm/assets/vendor/php-email-form/validate.js"></script>
  <!-- Template Main JS File -->
  <script src="<?= base_url() ?>crm/assets/js/main.js"></script>
  <script src="<?= base_url() ?>crm/assets/js/selectize.min.js"></script>
  <!-- <script type="text/javascript" src="https://cdn.datatables.net/r/dt/jq-2.1.4,jszip-2.5.0,pdfmake-0.1.18,dt-1.10.9,af-2.0.0,b-1.0.3,b-colvis-1.0.3,b-html5-1.0.3,b-print-1.0.3,se-1.0.1/datatables.min.js"></script> -->
  <!-- <script src="<?= base_url() ?>crm/assets/js/custom.js"></script> -->
  <script>
      $(document).ready(function() {
          $('select').selectize({
              sortField: 'text',
          });
          //   $('#datatable').DataTable({
          //       "autoWidth": false,
          //       dom: 'Blfrtip',
          //       buttons: [
          //           'copy', 'csv', 'excel', 'pdf', 'print'
          //       ],
          //       columnDefs: [{
          //           bSortable: false,
          //           targets: [0]
          //       }]

          //   });
      });
  </script>

  </body>

  </html>