<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <div class="header bg-gradient-green p-2">
                        <div class="container-fluid">
                            <div class="header-body">
                                <h2 style="color: white; padding:10px 0px 10px 0px;" class="text-center">Update Tax</h2>
                                <form method="POST" action="<?= base_url(); ?>tax/update">
                                    <input type="hidden" value="<?= set_value('id', $tax->id); ?>" name="id" />
                                    <div class="row">
                                        <div class="col-md-4 col-xs-4 col-sm-4 col-lg-4">
                                            <div class="form-group">
                                                <label for="tax_name" class="text-white">Tax Name</label>
                                                <input type="text" name="name" value="<?= set_value('name', $tax->name); ?>" id="name" class="form-control" placeholder="Tax Name" required="" />
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-xs-4 col-sm-4 col-lg-4">
                                            <div class="form-group">
                                                <label for="tax_per" class="text-white">Tax Percentage</label>
                                                <input type="number" name="tax_per" value="<?= set_value('tax_per', $tax->tax_per); ?>" id="tax_per" class="form-control" onchange="checkper(this.value)" required="" min="0" placeholder="Tax Percentage" />
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-xs-4 col-sm-4 col-lg-4">
                                            <div class="form-group">
                                                <label for="igst" class="text-white">IGST</label>
                                                <input type="number" name="igst" value="<?= set_value('igst', $tax->igst); ?>" id="igst" class="form-control" readonly="" placeholder="IGST" />
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-xs-4 col-sm-4 col-lg-4">
                                            <div class="form-group">
                                                <label for="cgst" class="text-white">CGST</label>
                                                <input type="number" name="cgst" value="<?= set_value('cgst', $tax->cgst); ?>" id="cgst" class="form-control" readonly="" placeholder="CGST" />
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-xs-4 col-sm-4 col-lg-4">
                                            <div class="form-group">
                                                <label for="sgst" class="text-white">SGST</label>
                                                <input type="number" name="sgst" value="<?= set_value('sgst', $tax->sgst); ?>" id="sgst" class="form-control" readonly="" placeholder="SGST" />
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-xs-4 col-sm-4 col-lg-4">
                                            <div class="form-group">
                                                <label for="cess" class="text-white">CESS</label>
                                                <input type="number" name="cess" value="<?= set_value('cess', $tax->cess); ?>" id="cess" class="form-control" required="" placeholder="CESS" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2 pb-2">
                                        <div class="col-md-12 text-center">
                                            <button type="submit" class="btn btn-primary">
                                                Update
                                            </button>
                                            <a href="<?= base_url(); ?>tax" class="btn btn-danger" title="Cancel">
                                                Cancel
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function checkper(value) {
        if (value > 0) {
            var cgst = value / 2;
            var cess = value / 2;
            document.getElementById('igst').value = value;
            document.getElementById('cgst').value = cgst;
            document.getElementById('sgst').value = cess;
        } else {
            document.getElementById('tax_per').value = 0;
            document.getElementById('igst').value = 0;
            document.getElementById('cgst').value = 0;
            document.getElementById('sgst').value = 0;
        }
    }
</script>