<style type="text/css">
    .alert {
        margin-top: 50px;
    }
    input[type="file"] {
        line-height: 1;
    }
</style>

<div class="content-wrapper">
    <div class="content-header">
        <div class="col-md-12 col-lg-12 col-sm-12">
            <?php if ($this->session->flashdata('msg')) { ?>
            <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                <?= $this->session->flashdata('msg'); ?>
            </div>
            <?php } ?>
        </div>
        <div class="col-md-12 col-lg-12 col-sm-12">
            <div class="card mt-5">
                <div class="card-header">
                    <h3 class="card-title">
                        Products Information
                    </h3>
                    <a href="<?= base_url(); ?>webProducts" class="btn btn-info btn-xs pull-right" title="Product Report">
                        <i class="fa fa-file-alt"></i> Report
                    </a>
                </div>
                <div class="card-body">
                    <div class="col-md-12">
                        <?php if (empty($id)) { ?>
                        <form action="<?= base_url(); ?>webProducts/create" method="POST" enctype="multipart/form-data" autocomplete="off">
                            <div class="row">
                                <div class="col-md-3 form-group">
                                    <label for="name">Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" value="<?= set_value('name'); ?>" id="name" class="form-control form-control-sm" placeholder="Name" required="" />
                                    <span class="text-danger"><?= form_error('name'); ?></span>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label for="url_name">URL Name <span class="text-danger">*</span></label>
                                    <input type="text" name="url_name" value="<?= set_value('url_name'); ?>" id="url_name" class="form-control form-control-sm" placeholder="URL Name" required="" />
                                    <span class="text-danger"><?= form_error('url_name'); ?></span>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label for="image">Image <span class="text-danger">*</span></label>
                                    <input type="file" name="image" id="image" class="form-control form-control-sm" title="Image" accept="image/*" required="" />
                                </div>
                                <div class="col-md-3 form-group">
                                    <label for="largeImage">Large Image <span class="text-danger">*</span></label>
                                    <input type="file" name="largeImage" id="largeImage" class="form-control form-control-sm" title="Large Image" accept="image/*" />
                                </div>
                                <div class="col-md-3 form-group">
                                    <label for="IsUpcoming">Is Upcoming</label>
                                    <br />
                                    <input type="checkbox" name="IsUpcoming" value="IsUpcoming" <?= set_checkbox('IsUpcoming', 'IsUpcoming'); ?> id="IsUpcoming" title="Is Upcoming" />
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="productImage">Gallery Images <small class="text-danger">(Select multiple images, if you want upload multiple)</small></label>
                                    <input type="file" name="productImage[]" id="productImage" class="form-control form-control-sm" title="Product Image" accept="image/*" multiple="multiple" />
                                </div>                                
                                <div class="col-md-12 form-group">
                                    <label for="description">Description <span class="text-danger">*</span></label>
                                    <textarea name="description" id="description" class="form-control form-control-sm" placeholder="Description" rows="4"><?= set_value('description'); ?></textarea>
                                    <span class="text-danger"><?= form_error('description'); ?></span>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 form-group">
                                    <button type="submit" class="btn btn-primary btn-sm">
                                        <i class="fa fa-save"></i> Save
                                    </button>
                                    <button type="reset" class="btn btn-danger btn-sm">
                                        <i class="fa fa-refresh"></i> Reset
                                    </button>
                                </div>
                            </div>
                        </form>
                        <?php } else { ?>
                        <form action="<?= base_url(); ?>webProducts/update/<?= $productdata->id; ?>" method="POST" enctype="multipart/form-data" autocomplete="off">
                            <input type="hidden" name="id" value="<?= $productdata->id; ?>" />
                            <div class="row">
                                <div class="col-md-3 form-group">
                                    <label for="name">Name <span class="text-danger">*</span></label>
                                    <input type="text" name="name" value="<?= set_value('name', $productdata->name); ?>" id="name" class="form-control form-control-sm" placeholder="Name" required="" />
                                    <span class="text-danger"><?= form_error('name'); ?></span>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label for="url_name">URL Name <span class="text-danger">*</span></label>
                                    <input type="text" name="url_name" value="<?= set_value('url_name', $productdata->url_name); ?>" id="url_name" class="form-control form-control-sm" placeholder="URL Name" required="" />
                                    <span class="text-danger"><?= form_error('url_name'); ?></span>
                                </div>
                                <div class="col-md-3 form-group">
                                    <label for="image">Image</label>
                                    <input type="file" name="image" id="image" class="form-control form-control-sm" title="Image" accept="image/*" />
                                    <input type="hidden" name="oldimage" value="<?= $productdata->image; ?>" />
                                </div>
                                <div class="col-md-1 form-group mt-4">
                                    <img src="<?= base_url(); ?>uploads/webproducts/<?= $productdata->image; ?>" alt="product" style="width: 50px; height: 50px;" />
                                </div>
                                <div class="col-md-3 form-group">
                                    <label for="largeImage">Large Image <span class="text-danger">*</span></label>
                                    <input type="file" name="largeImage" id="largeImage" class="form-control form-control-sm" title="Large Image" accept="image/*" />
                                    <input type="hidden" name="oldImageLarge" value="<?= $productdata->largeImage; ?>" />
                                </div>
                                <div class="col-md-1 form-group mt-4">
                                    <img src="<?= base_url(); ?>uploads/webproductsLarge/<?= $productdata->largeImage; ?>" alt="large-product" style="width: 50px; height: 50px;" />
                                </div>
                                <div class="col-md-2 form-group">
                                    <label for="IsUpcoming">Is Upcoming</label>
                                    <br />
                                    <input type="checkbox" name="IsUpcoming" value="IsUpcoming" <?= set_checkbox('IsUpcoming', 'IsUpcoming'); ?> id="IsUpcoming" <?= $productdata->IsUpcoming == "IsUpcoming" ? "checked" : ""; ?> title="Is Upcoming" />
                                </div>
                                <div class="col-md-12 form-group">
                                    <label for="description">Description <span class="text-danger">*</span></label>
                                    <textarea name="description" id="description" class="form-control form-control-sm" placeholder="Description" rows="4" required=""><?= set_value('description', $productdata->description); ?></textarea>
                                    <span class="text-danger"><?= form_error('description'); ?></span>
                                </div>
                            </div>
                            <hr />
                            <div class="row">
                                <div class="col-md-6">
                                    <label>Gallery Images</label>
                                </div>
                                <div class="col-md-6">
                                    <a href="javascript:void(0);" class="btn btn-info btn-xs pull-right mb-2" title="Add More Images" onclick="AddMoreImages();">
                                        <i class="fa fa-plus"></i> Add More
                                    </a>
                                </div>
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm">
                                            <thead>
                                                <tr>
                                                    <th>Sr.</th>
                                                    <th>Image</th>
                                                    <th>New Image</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php if ($productImages) { $m = 0; foreach ($productImages as $key => $ImgData) { $m++; ?>
                                                <input type="hidden" name="productImageId[]" value="<?= set_value('productImageId[]', $ImgData->id); ?>" />
                                                <input type="hidden" name="oldFileName[]" value="<?= set_value('oldFileName[]', $ImgData->productImage); ?>" />
                                                <tr>
                                                    <td><label id="sr"><?= $m; ?></label></td>
                                                    <td>
                                                        <img src="<?= base_url(); ?>uploads/webProductsGallery/<?= $ImgData->productImage; ?>" alt="<?= $ImgData->productImage; ?>" style="width: 50px; border-radius: 25px;" />
                                                    </td>
                                                    <td>
                                                        <input type="file" name="productImage[]" id="productImage" class="form-control" title="Image" accept="image/*" style="width: 300px;" />
                                                    </td>
                                                    <td>
                                                        <a href="<?= base_url(); ?>webproducts/imageDelete/<?= $ImgData->id; ?>" class="btn btn-danger btn-xs" title="Delete Image" onclick="return confirm('Are you sure want to delete this image!');">
                                                            <i class="fa fa-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php } } ?>
                                            </tbody>
                                            <tfoot id="MoreImagesField"></tfoot>
                                        </table>
                                        <input type="hidden" name="TotalImagesFields" id="TotalImagesFields" />
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3 form-group">
                                    <button type="submit" class="btn btn-warning btn-sm">
                                        <i class="fa fa-save"></i> Save Changes
                                    </button>
                                    <a href="<?= base_url(); ?>webProducts" class="btn btn-danger btn-sm">
                                        <i class="fa fa-times"></i> Cancel
                                    </a>
                                </div>
                            </div>
                        </form>                            
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    const AddMoreImages = () => {
        var TotalField  = $("#TotalImagesFields").val();
        var NewCount = Number(TotalField) + 1;
        var Field = '<tr id="NewImageRow'+NewCount+'">'+
            '<td>'+
            '<label id="ImageSr'+NewCount+'">'+NewCount+'</label>'+
            '</td>'+
            '<td></td>'+
            '<td>'+
            '<input type="file" name="newFileName[]" id="newFileName'+NewCount+'" class="form-control" title="Image" required="" accept="image/*" style="width: 300px;" />'+
            '</td>'+
            '<td>'+
            '<button type="button" class="btn btn-danger btn-xs" onclick="RemoveImagesField('+NewCount+');">'+
            '<i class="fa fa-minus"></i>'+
            '</button>'+
            '</td>'+
            '</tr>';
        $("#MoreImagesField").append(Field);
        $("#TotalImagesFields").val(NewCount);
    };
    
    const RemoveImagesField = (Id) => {
        $("#NewImageRow"+Id).remove();
        var TotalField  = $("#TotalImagesFields").val();
        var NewCount = Number(TotalField) - 1;
        $("#TotalImagesFields").val(NewCount);
    };
</script>