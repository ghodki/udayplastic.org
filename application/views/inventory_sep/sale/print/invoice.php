<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Uday Plastic - Invoice</title>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="description" content="Uday Plastic - Invoice" />
        <meta name="keywords" content="Uday Plastic - Invoice" />
        <!-- Favicons -->
        <link type="text/png" rel="icon" href="<?= base_url(); ?>assets/images/favicon.png" />
        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Noto+Serif&family=PT+Serif&family=Playfair+Display&family=Roboto+Slab&display=swap" rel="stylesheet">
        <!-- Vendor CSS Files -->
        <link type="text/css" rel="stylesheet" href="<?= base_url(); ?>crm/assets/vendor/bootstrap/css/bootstrap.min.css" />
        <link type="text/css" rel="stylesheet" href="<?= base_url(); ?>crm/assets/css/style.css" />
        <style type="text/css">
            @media print {
                @page {
                    size: auto;
                }
            }
            body {
                background: #ffffff;
                font-family: 'Noto Serif', serif;
                font-family: 'Playfair Display', serif;
                font-family: 'PT Serif', serif;
                font-family: 'Roboto Slab', serif;
            }
            .table-bordered, .table-bordered tr, .table-bordered th, .table-bordered td {
                border: 0.5px solid #000000;
            }
            .table td, .table th {
                padding: 0.5rem;
            }
            .rs {
                padding-right: 20px;
                padding-top: 10px;
                padding-bottom: 10px;
                border-right: 0.5px solid #000000;
                border-top: 0.5px solid #000000;
                border-bottom: 0.5px solid #000000;
                margin-left: -8px;
            }
            .table {
                margin-bottom: 0rem;
            }
            p {
                margin-bottom: 0rem;
            }
        </style>
    </head>
    <body>
        <section class="m-4">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <table class="table table-bordered">
                            <tr>
                                <td style="border: none;">
                                    <h5 style="text-align: center; margin-bottom: 0rem;">Invoice</h5>
                                    <small style="position: relative; top: 8px; float: right;">Original For Buyer</small>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-12">
                        <table class="table table-bordered">
                            <tr>
                                <td class="col-md-4 text-center pt-5">
                                    <img src="<?= base_url() ?>/assets/images/invoice_img.png" alt="invoice_img">
                                    <p class="mt-4"><b>GST NO: 29AAJFE5367R1ZG</b></p>
                                </td>
                                <td class="col-md-8 text-center p-5">
                                    <h3><b>E SIGN INDIA VENTURES</b></h3>
                                    <p>No. G - 2 , Ground Floor , Swastik Manandi Arcade,<br>
                                        Sheshadripuram, Bangalore - 560020</p>
                                    <p><b>Mob: 9731170076 Ph: 080 49906738</b></p>
                                    <small><b>Email ID: sales@esignindiaventures.com</b></small>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-12">
                        <table class="table table-bordered">
                            <tr>
                                <td class="col-md-7 p-4">
                                    <div class="row">
                                        <div class="col-md-8 mb-3">
                                            <b>Invoice No :</b>
                                            <span style="padding-left: 30px;">ESIV/22-23/231</span>
                                        </div>
                                        <div class="col-md-8 mb-3">
                                            <span>Invoice Date :</span>
                                            <span style="padding-left: 30px;">02-11-2022</span>
                                        </div>
                                        <div class="col-md-8">
                                            <span>State :</span>
                                            <span style="padding-left: 30px;">Karnataka</span>
                                        </div>
                                        <div class="col-md-4">
                                            <span>State Code :</span>
                                            <span style="padding-left: 30px;">29</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="col-md-5 pt-4">
                                    <div class="col-md-8 mb-1">
                                        <span>Transportation Mode :</span>
                                        <span style="padding-left: 30px;"></span>
                                    </div>
                                    <div class="col-md-8 mb-1">
                                        <span>Vehicle Number :</span>
                                        <span style="padding-left: 30px;"></span>
                                    </div>
                                    <div class="col-md-8 mb-1">
                                        <span>Date of Supply :</span>
                                        <span style="padding-left: 30px;"></span>
                                    </div>
                                    <div class="col-md-8">
                                        <span>Place of Supply :</span>
                                        <span style="padding-left: 30px;">Karnataka</span>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-12">
                        <table class="table table-bordered">
                            <tr class="text-center">
                                <th class="col-md-7">Details of Receiver | Billed to:</th>
                                <th class="col-md-5"> Details of Consignee | Shipped to:</th>
                            </tr>
                            <tr>
                                <td class="px-4">
                                    <div class="col-md-8 mb-1">
                                        <b>UMATIC INDIA PVT LTD</b>
                                        <p>NO 113/A NEW NO 1/1 39TH CROSS 2ND BLOCK 10TH <br> MAIN <br> BANGLORE</p>
                                    </div>
                                    <div class="col-md-8 mb-1">
                                        <span>Contact No :</span>
                                        <span style="padding-left: 30px;"></span>
                                    </div>
                                    <div class="col-md-8 mb-1">
                                        <span>GST NO :</span>
                                        <span style="padding-left: 30px;">29AABCU7671H1ZW</span>
                                    </div>
                                    <div class="col-md-8">
                                        <span>STATE :</span>
                                        <span style="padding-left: 30px;">Karnataka</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="col-md-8 mb-1">
                                        <b>UMATIC INDIA PVT LTD</b>
                                        <p>NO 113/A NEW NO 1/1 39TH CROSS 2ND BLOCK 10TH <br> MAIN <br> BANGLORE</p>
                                    </div>
                                    <div class="col-md-8 mb-1">
                                        <span>Contact No :</span>
                                        <span style="padding-left: 30px;"></span>
                                    </div>
                                    <div class="col-md-8 mb-1">
                                        <span>GST NO :</span>
                                        <span style="padding-left: 30px;">29AABCU7671H1ZW</span>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-12">
                        <table class="table table-bordered text-center">
                            <tr>
                                <th class="col-md-1">S.N.</th>
                                <th class="col-md-4">DESCRIPTION</th>
                                <th class="col-md-2">HSN CODE</th>
                                <th>QTY</th>
                                <th>RATE</th>
                                <th>GST%</th>
                                <th>TOTAL</th>
                            </tr>
                            <tr>
                                <td style="height: 300px;">1</td>
                                <td>EPASS 2003 AUTO</td>
                                <td>84733099</td>
                                <td>100</td>
                                <td>144.07</td>
                                <td>18</td>
                                <td>14407.00</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-12">
                        <table class="table table-bordered" style="border: none;">
                            <tr style="border-top: none; border-bottom: none;">
                                <td style="padding: 0.75rem; border-top: none; border-bottom: none;"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-12">
                        <table class="table table-bordered">
                            <td class="col-md-8 px-4 pt-3">
                                <div class="row">
                                    <div class="col-md-10 mb-3 text-center">
                                        <h5 style="text-decoration: underline;"><b>Bank Detail</b></h5>
                                        <p style=" text-align: left; padding-left: 100px"><b>
                                                E SIGN INDIA VENTURES <br>
                                                HDFC BANK <br>
                                                A/C NO: 50200067690181 <br>
                                                IFSC CODE: HDFC0000367 <br>
                                                BRANCH : SHESHADRIPURAM, BANGALORE
                                            </b></p>
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 0rem;">
                                <table class="table" style="line-height: 10px;">
                                    <tr style="border-top: none; border-left: none; border-right: none;">
                                        <th style="border-top: none; border-left: none;">Total Taxable Value</th>
                                        <td style="border-top: none; border-left: none; border-right: none;" class="text-center">14,407.00</td>
                                    </tr>
                                    <tr style="border-top: none; border-left: none; border-right: none;">
                                        <th style="border-top: none; border-left: none;">Add: SGST@ %</th>
                                        <td style="border-top: none; border-left: none; border-right: none;" class="text-center">1296.63</td>
                                    </tr>
                                    <tr style="border-top: none; border-left: none; border-right: none;">
                                        <th style="border-top: none; border-left: none;">Add: CGST@ %</th>
                                        <td style="border-top: none; border-left: none; border-right: none;" class="text-center">1296.63</td>
                                    </tr>
                                    <tr style="border-top: none; border-left: none; border-right: none;">
                                        <th style="border-top: none; border-left: none;">Add: IGST@ %</th>
                                        <td style="border-top: none; border-left: none; border-right: none;" class="text-center">14,407.00</td>
                                    </tr>
                                    <tr style="border-top: none; border-left: none; border-right: none;">
                                        <th style="border-top: none; border-left: none;">Total Tax Amount : GST</th>
                                        <td style="border-top: none; border-left: none; border-right: none;" class="text-center">0.00</td>
                                    </tr>
                                    <tr style="border-top: none; border-left: none; border-right: none; line-height: 40px;">
                                        <th style="border-top: none; border-left: none;">Total Amount After Tax</th>
                                        <td style="border-top: none; border-left: none; border-right: none;" class="text-center">2,593.26</td>
                                    </tr>
                                </table>
                            </td>
                        </table>
                    </div>
                    <div class="col-md-12">
                        <table class="table table-bordered text-center">
                            <tr>
                                <td style="width: 66.65%; padding:20px;">
                                    <p>
                                        <small>
                                            <b>
                                                Bill Amount In Words : Seventeen Thousand Only
                                            </b>
                                        </small>
                                    dd</p>
                                </td>
                                <th style="width: 19.3%;">TOTAL INVOICE VALUE</th>
                                <td>
                                    <b>17000.00</b>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-12">
                        <table class="table table-bordered">
                            <tr>
                                <td class="col-md-8 px-3 py-5">
                                    <h5 style="text-decoration: underline;">
                                        <b>Declaration:</b>
                                    </h5>
                                    <p>
                                        We declare that this invoice shows the actual price of the goods described and that
                                        all particulars are true and correct.
                                    </p>
                                </td>
                                <td class="col-md-4 text-center p-3">
                                    <h5>
                                        <b>
                                            <pre>For E SIGN INDIA VENTURES</pre>
                                        </b>
                                    </h5>
                                    <br /><br /><br />
                                    <p>
                                        <b>Auth. Signatory</b>
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="col-md-12 text-center mt-3">
                    This Is a Computer Generated Invoice
                </div>
            </div>
        </section>

        <script type="text/javascript" src="<?= base_url(); ?>crm/assets/js/jquery.min.js"></script>
        <script type="text/javascript" src="<?= base_url(); ?>crm/assets/js/bootstrap.min.js"></script>
        <script type="text/javascript">
            function Print(divName) {
                var printContents = document.getElementById(divName).innerHTML;
                var originalContents = document.body.innerHTML;
                document.body.innerHTML = printContents;
                window.print();
                document.body.innerHTML = originalContents;
            }
        </script>
    </body>
</html>