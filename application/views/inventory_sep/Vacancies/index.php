<style type="text/css">
    th, td {
        white-space: nowrap;
    }
    .form-group {
        margin-bottom: 0rem;
    }
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12 col-sm-12 col-md-12">
                    <?php if ($this->session->flashdata('msg')) { ?>
                    <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                        <?= $this->session->flashdata('msg'); ?>
                    </div>
                    <?php } ?>
                </div>
                <div class="col-lg-12 col-sm-12 col-md-12">
                    <div class="card card-outline card-navy">
                        <div class="card-header">
                            <div class="card-title">
                                <h4 class="text-navy">
                                    Vacancies Report
                                </h4>
                            </div>
                            <div class="pull-right">
                                <a href="<?= base_url(); ?>Vacancies/Create" class="btn btn-sm bg-navy" title="Create">
                                    <i class="fa fa-plus-circle"></i> Create
                                </a>
                                <a href="<?= base_url(); ?>Vacancies/JobApplicationReport" class="btn btn-sm bg-navy" title="Job Application Report">
                                    <i class="fa fa-table"></i> Job Applicants
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form action="<?= base_url(); ?>Vacancies/index">
                                <div class="row mb-2">
                                    <div class="col-md-2 col-lg-2 col-sm-2 col-xl-2 col-xs-2 col-xxl-2">
                                        <div class="form-group">
                                            <input type="date" name="from" value="<?= set_value('from', $this->input->get('from') ? $this->input->get('from') : date('Y-m-d')); ?>" id="from" class="form-control form-control-sm" title="From Date" />
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-lg-2 col-sm-2 col-xl-2 col-xs-2 col-xxl-2">
                                        <div class="form-group">
                                            <input type="date" name="to" value="<?= set_value('to', $this->input->get('to') ? $this->input->get('to') : date('Y-m-d')); ?>" id="to" class="form-control form-control-sm" title="To Date" />
                                        </div>
                                    </div>
                                    <div class="col-md-1 col-lg-1 col-sm-1 col-xl-1 col-xs-1 col-xxl-1">
                                        <div class="form-group">
                                            <select name="status" id="status" class="form-control form-control-sm" title="Status">
                                                <option value="All" <?= $this->input->get('status') == 'All' ? 'selected' : ''; ?>>All</option>
                                                <option value="1" <?= $this->input->get('status') == '1' ? 'selected' : ''; ?>>Published</option>
                                                <option value="2" <?= $this->input->get('status') == '2' ? 'selected' : ''; ?>>Unpublished</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-lg-2 col-sm-2 col-xl-2 col-xs-2 col-xxl-2">
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-sm bg-navy">
                                                Search
                                            </button>
                                            <a href="<?= base_url(); ?>Vacancies" class="btn btn-danger btn-sm" title="Refresh">
                                                <i class="fa fa-refresh"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead class="bg-navy">
                                        <tr>
                                            <th>Sr</th>
                                            <th>Date</th>
                                            <th>Designation</th>
                                            <th>Experience</th>
                                            <th>Location</th>
                                            <th>Openings</th>
                                            <th>Status</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                            if (isset($Vacancies) && !empty($Vacancies)) {
                                                $Sr = 0;
                                                foreach ($Vacancies as $key => $Data) {
                                                    $Sr++;
                                        ?>
                                        <tr>
                                            <td><?= $Sr; ?></td>
                                            <td><?= date('d-m-Y', strtotime($Data->Date)); ?></td>
                                            <td><?= $Data->Designation; ?></td>
                                            <td><?= $Data->Experience; ?></td>
                                            <td><?= $Data->Location; ?></td>
                                            <td><?= $Data->Openings; ?></td>
                                            <td>
                                                <?php if ($Data->Status == 1) { ?>
                                                <a href="<?= base_url(); ?>Vacancies/Status/<?= $Data->Id; ?>" class="btn btn-success btn-xs">
                                                    <i class="fa fa-flag-checkered"></i> Published
                                                </a>
                                                <?php } elseif ($Data->Status == 2) { ?>
                                                <a href="<?= base_url(); ?>Vacancies/Status/<?= $Data->Id; ?>" class="btn btn-danger btn-xs">
                                                    <i class="fa fa-ban"></i> Unpublished
                                                </a>                                                
                                                <?php } ?>
                                            </td>
                                            <td>
                                                <a href="javascript:void(0);" class="btn btn-primary btn-xs" onclick="getVacanciesInfo(<?= $Data->Id; ?>);" title="More Info">
                                                    <i class="fa fa-info-circle text-white"></i>
                                                </a>
                                                <a href="<?= base_url(); ?>Vacancies/Edit/<?= $Data->Id; ?>" class="btn btn-success btn-xs" title="Edit">
                                                    <i class="fa fa-edit text-white"></i>
                                                </a>
                                                <a href="<?= base_url(); ?>Vacancies/Delete/<?= $Data->Id; ?>" class="btn btn-danger btn-xs" title="Delete" onclick="return confirm('Are you sure want to delete this record!');">
                                                    <i class="fa fa-trash text-white"></i>
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

<div class="modal fade" id="Info" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content"></div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        $("table").DataTable({
            dom: "Bfrtip",
            pageLength: 20,
            lengthMenu: [[20, 50, 100, -1], [20, 50, 100, "All"]],
            buttons: ["copyHtml5", "excelHtml5", "pdfHtml5", "print", "pageLength"]
        });
    });
    
    const getVacanciesInfo = (Id) => {
        $("#Info .modal-dialog .modal-content").load("<?= base_url(); ?>Vacancies/Info/" + Id);
        $('#Info').modal('show');
    };
</script>