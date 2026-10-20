<form id="onProductHsnVerifiedFormSubmitHandler" action="<?= base_url() ?>product/hsnVerified" method="POST" enctype="multipart/form-data" onsubmit="onProductHsnVerifiedFormSubmitHandler(event)">
<div class="row">
     <div class="col-md-4 col-sm-4 col-lg-4">
        <input type="hidden" name="product_id" id="product_id" value="<?= $result->id ?>" />
        <div class="form-group">
            <label for="product_name">Product Name<span class="text-danger">*</span></label>
            <input id="hsn_product_name" name="name" tabindex="1" placeholder="Product Name" type="text" class="form-control" required="true" value="<?= set_value('name',$result->p_name) ?>">
            <span class="control-label">
                <?php echo form_error('name'); ?>
            </span>
        </div>
    </div>
     <div class="col-md-4 col-sm-4 col-lg-4">
        <div class="form-group">
            <label for="hsn_code">Product Hsn <a href="https://ewaybillgst.gov.in/Others/ProductSearch.aspx" target="_blank"> &gt;&gt;Check HSN</a><span class="text-danger">*</span></label>
            <input id="hsn_code" name="hsn_code" tabindex="1" placeholder="Product Hsn" type="text" class="form-control" required="true" value="<?= set_value('hsn_code',$result->hsn) ?>">
            <span class="control-label">
                <?php echo form_error('name'); ?>
            </span>
        </div>
    </div>
    
     <div class="col-md-4 col-sm-4 col-lg-4">
            <?php
            $this->db->select('id,tax_per');
            $this->db->where('status', 1);
            $tax_master = $this->db->get('tax')->result();
            ?>
            <div class="form-group"> 
                <label for="product_tax">Tax<span class="text-danger">*</span></label>
                                <select id="product_tax" name="product_tax" class="form-control" tabindex="25" required>
                                    <option value="">Select Tax</option>
                                    <?php foreach ($tax_master as $value) { ?>
                        <option value="<?= $value->tax_per ?>" <?= $value->tax_per==$result->tax_qty?"selected":""; ?>><?= $value->tax_per ?></option>
                    <?php } ?>
                                </select>

                                <span class="control-label">
                    <?php echo form_error('name'); ?>
                </span>
                                
            </div>
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