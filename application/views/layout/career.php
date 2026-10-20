<style type="text/css">
    .topheadingsection {
        height: calc(45vh - 90px);
    }
    .inner-container {
        min-height: 200px;
    }
</style>

<div class="topheadingsection">
    <div class="container text-center">
        <h1>Career</h1>
        <p class="px-md-5 font-22">
            Become a member of the Uday Plastic Family
        </p>
    </div>
</div>

<section class="inner-container">
    <div class="container">
        <div class="row">
                <div class="col-lg-12">
                    <?php if ($this->session->flashdata('msg')) { ?>
                    <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                        <?= $this->session->flashdata('msg'); ?>
                    </div>
                    <?php } ?>
                </div>
            <?php if (isset($Vacancies) && !empty($Vacancies)) { foreach ($Vacancies as $key => $Vacancy) { ?>
            <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12 col-xl-12 col-xxl-12 order-md-last">
                <div class="card mb-5">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12 col-xl-12 col-xxl-12">
                                <h5><?= $Vacancy->Designation; ?></h5>
                                <?php if (empty($Vacancy->CompanyBranchId)) { ?>
                                <small><?= get_title('inventory_billing', ['id' => $Vacancy->CompId], 'company_name'); ?></small>
                                <?php } else { ?>
                                <small><?= get_title('companybranch', ['Id' => $Vacancy->CompanyBranchId], 'BranchName'); ?></small>    
                                <?php } ?>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-4 col-sm-4 col-lg-4 col-xs-4 col-xl-4 col-xxl-4">
                                <i class="fa fa-briefcase"></i> Experience : <?= $Vacancy->Experience; ?>
                            </div>
                            <div class="col-md-4 col-sm-4 col-lg-4 col-xs-4 col-xl-4 col-xxl-4">
                                <i class="fa fa-map-marker"></i> Location : <?= $Vacancy->Location; ?>
                            </div>
                            <div class="col-md-4 col-sm-4 col-lg-4 col-xs-4 col-xl-4 col-xxl-4">
                                <i class="fa fa-user-plus"></i> Openings : <?= $Vacancy->Openings; ?>
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-md-12 col-sm-12 col-lg-12 col-xs-12 col-xl-12 col-xxl-12">
                                Job Description : <?= word_limiter($Vacancy->JobDescription, 15); ?>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <label class="pull-left">
                            <?php
                                $inputDateTime = new DateTime($Vacancy->Created);
                                $currentDateTime = new DateTime();
                                $interval = $currentDateTime->diff($inputDateTime);
                                if ($interval->invert == 1) {
                                    echo $interval->days . ' Days Ago';
                                }
                            ?>
                        </label>
                        <a href="<?= base_url(); ?>Index/Vacancy/<?= $Vacancy->Id; ?>" class="btn btn-primary pull-right">
                            Apply Now
                        </a>
                    </div>
                </div>
            </div>
            <?php } } ?>
        </div>
    </div>
</section>