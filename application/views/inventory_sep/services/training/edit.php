<script type="text/javascript" src="<?= base_url() ?>assets/js/jquery.min.js"></script>
<script type="text/javascript" src="<?= base_url(); ?>dist/js/select2.min.js"></script>

<form onsubmit="onTrainingUpdateFormHendler(event);" id="updateForm" autocomplete="off">
    <div class="modal-header">
        <h5 class="modal-title">Training Edit</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label for="categoryId">Select Category <span class="text-danger">*</span></label>
                    <select name="categoryId" id="categoryIdEdit" class="form-control form-control-sm" title="Category Name" required="">
                        <?php
                            $trainingDataArray = [];
                            foreach ($trainingData as $key => $data) {
                                $trainingDataArray[] = $data->categoryId;
                            }
                        ?>
                        <option value="" disabled="" <?= in_array("", $trainingDataArray) ? 'selected' : ''; ?>>Select Category</option>
                        <?php if (!empty($categories)) { foreach($categories as $key => $category) { ?>
                        <option value="<?= $category->id; ?>" <?= in_array($category->id, $trainingDataArray) ? 'selected' : ''; ?>><?= $category->name; ?></option>
                        <?php } } ?>
                    </select>
                </div>
            </div>
            <div class="col-md-12 mt-5">
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Sr</th>
                            <th>Training Name</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="dynamicField">
                        <?php
                            if (!empty($trainingData)) {
                                $sr = 0;
                                foreach ($trainingData as $key => $dataValue) {
                                    $trainingNames = explode(',', $dataValue->trainingName);
                                    
                                    foreach($trainingNames as $key => $trainingName) {                                    
                                        $sr++;
                        ?>
                        <tr id="row<?= $sr; ?>">
                            <td><?= $sr; ?></td>
                            <td>
                                <input type="text" name="trainingName[]" value="<?= $trainingName; ?>" id="trainingName<?= $sr; ?>" class="form-control form-control-sm" placeholder="Training Name" required="" autocomplete="off" />
                            </td>
                            <td>
                                <button type="button" id="<?= $sr; ?>" class="btn btn-danger btn-sm btn_remove">
                                    <i class="fa fa-minus-circle text-white"></i>
                                </button>
                            </td>
                        </tr>
                        <?php } } } ?>
                        <tr>
                            <td>1</td>
                            <td>
                                <input type="text" name="trainingName[]" id="trainingName1" class="form-control form-control-sm" placeholder="Training Name" autocomplete="off" />
                            </td>
                            <td>
                                <button type="button" id="add" class="btn btn-success btn-sm">
                                    <i class="fa fa-plus-circle text-white"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>            
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" id="updateButton" class="btn btn-success btn-sm">Make Changes</button>
        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
    </div>
</form>