<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-outline card-success">
                        <div class="card-header">
                            <div class="card-title">
                                <h3 class="text-success">Update Series Title</h3>
                            </div>
                            <a href="<?= base_url(); ?>seriestitle" class="btn btn-success pull-right">
                                <i class="fa fa-angle-double-left"></i> Back
                            </a>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="<?= base_url(); ?>seriestitle/update">
                                <input type="hidden" name="id" value="<?= set_value('id', $result->id); ?>">
                                <div class="row">
                                    <div class="col-md-6 col-xs-6 col-sm-6 col-lg-6">
                                        <div class="form-group">
                                            <input name="name" type="text" value="<?= set_value('name', $result->series); ?>" id="name" class="form-control" required="" placeholder="Series Title" />
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-12 text-center">
                                        <button type="submit" class="btn btn-primary">Update</button>
                                        <a href="<?= base_url(); ?>seriestitle" class="btn btn-danger" title="Cancel">
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