<div class="modal-header">
    <h5 class="modal-title" id="title">Description</h5>
    <button type="button" class="close" onclick="CloseModal();">
        <span aria-hidden="true">×</span>
    </button>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <p>Description : </p>
            <p style="text-align: justify;" class="border-bottom">
                <?= $proudctInfo->description; ?>
            </p>
        </div>
        
        <div class="col-md-12">
            <p>Large Image : </p>
            <img src="<?= base_url(); ?>uploads/webproductsLarge/<?= $proudctInfo->largeImage; ?>" style="width: 25%;" alt="alt" />
            <p class="border-bottom"></p>
        </div>

        <div class="col-md-12">
            <p>Gallery Images : </p>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Sr.</th>
                            <th>Image.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if ($proudctImages) {
                                $sr = 0;
                                foreach ($proudctImages as $key => $image) {
                                    $sr++;
                        ?>
                        <tr>
                            <td><?= $sr; ?></td>
                            <td>
                                <img src="<?= base_url(); ?>uploads/webProductsGallery/<?= $image->productImage; ?>" style="width: 20%;" alt="alt" />
                            </td>
                        </tr>
                        <?php } } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>