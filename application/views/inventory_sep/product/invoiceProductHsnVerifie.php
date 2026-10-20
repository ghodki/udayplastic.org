<form id="oninvoiceProductHsnVerifiedFormSubmitHandler" action="<?= base_url() ?>product/invoiceProducthsnVerified" method="POST" enctype="multipart/form-data" onsubmit="oninvoiceProductHsnVerifiedFormSubmitHandler(event)">
    <div class="row">
        <?php 
        $product_ids = explode(',', $invoiceDetails->product_ids);

        $this->db->select('id,p_name,hsn,hsn_verified,hsn_verified_date');
        $this->db->where_in('id', $product_ids);
        $this->db->where('hsn_verified', 'N');
        $invoice_product = $this->db->get('product')->result(); 
        ?>
        <div class="col-md-12 col-sm-12 col-lg-12">
            <table class="table table-bordered inner_table table-responsive-sm mb-0" id="dataTable">
                <thead class="bg-dark" style="position: sticky; top: 0;">
                    <tr>
                        <th class="text-white">Sr</th>
                        <th class="text-white" width="120px;">Product Name</th>
                        <th class="text-white" width="150px">
                            Product HSN 
                        </th>  
                    </tr>
                </thead>
                <tbody>
                    <input type="hidden" name="invoice_id" id="invoice_id" value="<?= $invoiceDetails->id ?>" />
                    <input type="hidden" name="hsninvoiceType" id="hsninvoiceType" value="<?= $InvoiceType ?>" />
                    <?php foreach($invoice_product as $key=>$product){ ?>
                    <tr id="tr1">
                        <td style="width: 20px; padding: 4px;"><?= ++$key ?></td>
                        <td style="width: 120px; padding: 4px;">
                        <input  type="hidden" value="<?= $product->id ?>" name="product[<?= $key ?>][product_id]" id="product_id<?= $key ?>" >
                        <input style="width: 100%;" type="text" value="<?= $product->p_name ?>" name="product[<?= $key ?>][p_name]" id="p_name<?= $key ?>"  class="form-control">
                        </td>
                        <td style="width: 120px; padding: 4px;">
                        <input style="width: 100%;" type="text" value="<?= $product->hsn ?>" name="product[<?= $key ?>][p_hsn]" id="p_hsn<?= $key ?>"  class="form-control">
                        </td> 
                     </tr>
                     <?php } if(count($invoice_product)==0){ ?>
                     <tr><td colspan="3" style="text-align:center;color:green;" >All product is verified</td></tr>
                     <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-md-12 text-center">
            <button type="submit" class="btn btn-primary">
                Verified
            </button>

        </div>
    </div>
</form>
<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/buttons.print.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/jszip.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/pdfmake.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/vfs_fonts.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>assets/js/buttons.print.min.js"></script>
<script type="text/javascript">
    $(function() {
        $("#ProductStockReport").DataTable({
            dom: 'Bfrtip',
            lengthMenu: [
                [20, 50, 100, 500, -1],
                [20, 50, 100, 500, "All"]
            ],
            buttons: ['copyHtml5', 'excelHtml5', 'pdfHtml5', 'print', 'pageLength']
        });
    });
</script>