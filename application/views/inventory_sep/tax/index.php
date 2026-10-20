<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <?php if ($this->session->flashdata('msg')) { ?>
                    <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                        <?= $this->session->flashdata('msg'); ?>
                    </div>
                    <?php } ?>
                </div>
                
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <div class="header bg-gradient-green p-2">
                        <div class="header-body">
                            <h4 style="color: white; padding: 10px 0px 10px 0px;" class="text-center">Add Tax</h4>
                            <form method="POST" action="<?= base_url(); ?>tax/save">
                                <div class="row">
                                    <div class="col-md-4 col-xs-4 col-sm-4 col-lg-4">
                                        <div class="form-group">
                                            <label for="tax_name" class="text-white">Tax Name</label>
                                            <input type="text" name="name" value="<?= set_value('name'); ?>" id="name" class="form-control" placeholder="Tax Name" required="" />
                                        </div>
                                        <span class="col-sm-4 control-label"><?= form_error('name'); ?></span>
                                    </div>
                                    <div class="col-md-4 col-xs-4 col-sm-4 col-lg-4">
                                        <div class="form-group">
                                            <label for="tax_per" class="text-white">Tax Percentage</label>
                                            <input type="number" name="tax_per" value="<?= set_value('tax_per'); ?>" id="tax_per" class="form-control" onchange="checkper(this.value);" required="" min="0" placeholder="Tax Percentage" />
                                        </div>
                                        <span class="col-sm-4 control-label"><?= form_error('tax_per'); ?></span>
                                    </div>
                                    <div class="col-md-4 col-xs-4 col-sm-4 col-lg-4">
                                        <div class="form-group">
                                            <label for="igst" class="text-white">IGST</label>
                                            <input type="number" name="igst" value="<?= set_value('igst'); ?>" id="igst" class="form-control" readonly="" placeholder="IGST" />
                                        </div>
                                        <span class="col-sm-4 control-label"><?= form_error('igst'); ?></span>
                                    </div>
                                    <div class="col-md-4 col-xs-4 col-sm-4 col-lg-4">
                                        <div class="form-group">
                                            <label for="cgst" class="text-white">CGST</label>
                                            <input type="number" name="cgst" value="<?= set_value('cgst'); ?>" id="cgst" class="form-control" readonly="" placeholder="CGST" />
                                        </div>
                                        <span class="col-sm-4 control-label"><?= form_error('cgst'); ?></span>
                                    </div>
                                    <div class="col-md-4 col-xs-4 col-sm-4 col-lg-4">
                                        <div class="form-group">
                                            <label for="sgst" class="text-white">SGST</label>
                                            <input type="number" name="sgst" value="<?= set_value('sgst'); ?>" id="sgst" class="form-control" readonly="" placeholder="SGST" />
                                        </div>
                                        <span class="col-sm-4 control-label"><?= form_error('sgst'); ?></span>
                                    </div>
                                    <div class="col-md-4 col-xs-4 col-sm-4 col-lg-4">
                                        <div class="form-group">
                                            <label for="cess" class="text-white">CESS</label>
                                            <input type="number" name="cess" value="<?= set_value('cess', 0); ?>" id="cess" class="form-control" required="" placeholder="CESS" />
                                        </div>
                                        <span class="col-sm-4 control-label"><?= form_error('cess'); ?></span>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn btn-success">
                                            Save
                                        </button>
                                        <input type="reset" class="btn btn-danger" value="Reset" />
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-12 col-xl-12 col-lg-12">
                    <div class="card card-outline card-maroon mt-2">
                        <div class="card-header">
                            <div class="card-title">
                                <h3 class="text-maroon">Tax List</h3>
                            </div>
                        </div>
                        <div class="card-body">    
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="bg-maroon">
                                        <tr>
                                            <th>Sr</th>
                                            <th>Date</th>
                                            <th>Tax Name</th>
                                            <th>Tax %</th>
                                            <th>IGST</th>
                                            <th>CGST</th>
                                            <th>SGST</th>
                                            <th>CESS</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if (isset($tax) && $tax != '') {
                                                $Sr = 0;
                                                foreach ($tax as $key => $cat) {
                                                    $Sr++;
                                        ?>
                                        <tr>
                                            <td><?= $Sr; ?></td>
                                            <td><?= date("d-m-Y", strtotime($cat->created_at)); ?></td>
                                            <td><?= $cat->name; ?></td>
                                            <td><?= $cat->tax_per; ?>%</td>
                                            <td><?= $cat->igst; ?></td>
                                            <td><?= $cat->cgst; ?></td>
                                            <td><?= $cat->sgst; ?></td>
                                            <td><?= $cat->cess; ?></td>
                                            <td>
                                                <a href="<?= base_url(); ?>tax/edit/<?= $cat->id; ?>" class="btn btn-success btn-sm" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a> 
                                                <a href="<?= base_url(); ?>tax/delete/<?= $cat->id ?>" class="btn btn-danger btn-sm" title="Delete" onclick="return confirm('Are you sure want to delete this record!');">
                                                    <i class="fa fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php } } ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
        $('.table').DataTable();
    });
    
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