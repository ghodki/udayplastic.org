<style type="text/css">
    .widget-user .widget-user-username {
        font-size: 15px;
        font-weight: 400;
    }
    .widget-user-desc {
        font-size: 12px;
        font-weight: 300;
        margin-bottom: 0.25rem;
    }
    .widget-user .widget-user-header {
        height: auto;
        text-align: left;
        padding: 0.25rem;
    }
    .widget-user .card-footer {
        padding-top: 5px;
    } 
   .loader-bg {
        background: #161616 url("<?= base_url(); ?>assets/images/loader.gif") no-repeat center center;
        opacity: 0.5;
        background-size: 5%;
        top: 220px;
    }
    .loader-bg {
        width: 100%;
        height: 100%;
        position: fixed;
        z-index: 10000;
    }
    #swal2-content {
        display: flex !important;
    }
    @media (min-width: 1024px) {
        .downloadMobile {
            display: none;
        }
        .downloadWeb {
            display: inline;
        }
    }
    @media (max-width: 420px) {
        .downloadWeb {
            display: none;
        }
        .downloadMobile {
            display: inline;
        }
    }
    @media (max-width: 480px) {
        .downloadWeb {
            display: none;
        }
        .downloadMobile {
            display: inline;
        }
    }
    @media (max-width: 380px) {
        .downloadWeb {
            display: none;
        }
        .downloadMobile {
            display: inline;
        }
    }
    @media (max-width: 320px) {
        .downloadWeb {
            display: none;
        }
        .downloadMobile {
            display: inline;
        }
    }
</style>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <?php if ($this->session->flashdata('msg')) { ?>
                    <div class="alert alert-<?= $this->session->flashdata('class'); ?>">
                        <?= $this->session->flashdata('msg'); ?>
                    </div>
                    <?php } ?>
                </div>
                <div class="col-lg-12">
                    <div class="card card-navy card-outline">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="text-navy">
                                    Parties Balance Report
                                </h5>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 col-lg-4 col-sm-4 col-xl-4 col-xxl-4 col-xs-4">
                                    <input type="search" name="Search" class="form-control mb-2" id="Search" placeholder="Search" autocomplete="off" />
                                </div>
                                <div class="col-md-12 col-lg-12 col-sm-12 col-xl-12 col-xxl-12 col-xs-12 loader-bg"></div>
                            </div>
                            <div class="row" id="PartiesBalanceData"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        var PartyName = $('#Search').val();
        var Limit = 10;
        var Start = 0;
        var Action = 'Inactive';
        
        const PartiesBalanceReport = (Limit, Start, PartyName) => {
            $(".loader-bg").css("display", "block");
            $.ajax({
                method  :   "POST",
                url     :   "<?= base_url(); ?>Employee/PartiesBalanceReportAjax",
                data    :   {Limit: Limit, Start: Start, PartyName: PartyName},
                cache   :   false,
                success :   function(Response) {
                                $(".loader-bg").css("display", "none");
                                if (Start === 0) {
                                    $('#PartiesBalanceData').html(Response);
                                } else {
                                    $('#PartiesBalanceData').append(Response);
                                }
                                if (Response == '') {
                                    Action = 'Active';
                                } else {
                                    Action = 'Inactive';
                                }
                            }
            });
        };
        
        if (Action == 'Inactive') {
            Action = 'Active';
            PartiesBalanceReport(Limit, Start, PartyName);
        }
        
        $(window).scroll(function() {
            if ($(window).scrollTop() + $(window).height() > $("#PartiesBalanceData").height() && Action == 'Inactive') {
                Action = 'Active';
                Start = Start + Limit;
                setTimeout(function(){
                    PartiesBalanceReport(Limit, Start, PartyName);
                }, 500);
            }
        });
        
        $('#Search').on('input', function () {
            let PartyName = $(this).val().trim();
            Start = 0;
            PartiesBalanceReport(Limit, Start, PartyName);
        });
    });
    
    const DownloadFile = async (FileName, FileUrl) => {
        try {
            const response = await fetch(FileUrl);
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
        
            const blob = await response.blob();
            const objectUrl = window.URL.createObjectURL(blob);
            const anchor = document.createElement('a');
            anchor.style.display = 'none';
            anchor.href = objectUrl;
            anchor.download = FileName;
            document.body.appendChild(anchor);
            anchor.click();
            document.body.removeChild(anchor);
            window.URL.revokeObjectURL(objectUrl);
        } catch (error) {
            console.error('Error downloading the file:', error);
        }
    };
    
    const DownloadStatementOrSendWhatsApp = (event, PartyId) => {
        event.preventDefault();
        let Button = '<button class="btn btn-dark btn-sm downloadWeb mr-2" onclick="DownloadPaymentStatement('+ PartyId + ');">Download Statement</button>';
        Button += '<a href="<?= base_url(); ?>Employee/DownloadStatementViaPartyId/'+ PartyId + '" class="btn btn-dark btn-xs mr-2 downloadMobile" target="_blank">Download Statement</a>';
        Button += '<button class="btn btn-primary btn-sm" onclick="SendPaymentStatement(' + PartyId + ');">Send Statement</button>';
        
        Swal.fire({
            title: 'Do you want payment Statement Download or Send ?',
            text: 'This will create payment Statement ?',
            html: Button,
            icon: 'question',
            showConfirmButton: false,
            showCancelButton: false
        });
    };
    
    function DownloadPaymentStatement(PartyId) {
        $.ajax({
            type    :   "POST",
            url     :   "<?= base_url(); ?>Employee/DownloadPaymentStatement",
            data    :   {PartyId: PartyId},
            success :   function(Response) {
                            var ResponseData = JSON.parse(Response);
                            if (ResponseData.Status === true) {
                                DownloadFile(ResponseData.FileName, ResponseData.URL);
                                window.setTimeout(function() {
                                    window.location.reload();
                                }, 1000);
                            }
                        }
        });
    }
    
    function SendPaymentStatement(PartyId) {
        $.ajax({
            type    :   "POST",
            url     :   "<?= base_url(); ?>Employee/PartyPaymentStatement",
            data    :   {PartyId: PartyId},
            success :   function(Response) {
                            var ResponseData = JSON.parse(Response);
                            if (ResponseData.Status === true) {
                                window.setTimeout(function() {
                                    window.open(ResponseData.URL, '_blank');
                                    window.location.reload();
                                }, 1000);
                            }
                        }
        });
    }
</script>