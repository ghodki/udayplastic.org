<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Uday Plastic - Invoice</title>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta name="description" content="Uday Plastic - Invoice" />
        <meta name="keywords" content="Uday Plastic - Invoice" />
        <!-- Favicon -->
        <link type="text/png" rel="icon" href="<?= base_url(); ?>assets/images/favicon.png" />
        <style type="text/css">
            @media print {
                @page {
                    size: auto;
                }
            }
            .for {
                float: right;
            }
            .title {
                text-align: center;
                font-weight: bolder;
            }
            .comp_name {
                font-size: 25px;
                font-weight: bolder;
            }
            table {
                width: 100%;
            }
            table tr td {
                line-height: 1.5;
            }
            .top_head {
                padding: 5px 0px 5px 5px;
            }
            .top_position {
                vertical-align: top;
                padding: 0px;
                border: 0px;
            }
            .party_name {
                font-size: 20px;
                font-weight: bolder;
            }
            .bank_info {
                font-size: 18px;
                font-weight: bolder;
            }
            .tandc_info {
                font-size: 16px;
                font-weight: bolder;
                text-decoration: underline;
                font-style: italic;
            }
            .customer_info, .comp_info {
                font-size: 18px;
            }
            .comp_bottom {
                vertical-align: top;
                text-align: center;
            }
            .disclaimer {
                font-style: italic;
                padding-top: 10px;
                text-align: center;
            }
        </style>
    </head>
    <body>
        <div class="main">
            <div class="for">
                <small>Original for Buyer</small>
            </div>
            <div class="title">
                TAX INVOICE
            </div>
            <table cellspacing="0" border="1">
                <tr>
                    <td>
                        <span class="comp_name">PADMAVATHI MARKETING</span>
                        <br />
                        98/1F, NEAR POLICE STATION,
                        <br />
                        HALASAHALLI MAIN ROAD, VARTHUR,
                        <br />
                        BENGALURU - 560087
                        <br />
                        Phone : 6360717306, 7760372268
                        <br />
                        E-Mail : padmavathimarketing87@gmail.com
                        <br />
                        GST No. : 29AQMPH1928C1ZW
                    </td>
                    <td class="top_position">
                        <table cellspacing="0" border="1">
                            <tr>
                                <td class="top_head">Invoice No. : PM-23/24-324</td>
                            </tr>
                            <tr>
                                <td class="top_head">Delivery : </td>
                            </tr>
                            <tr>
                                <td class="top_head">Dispatch Document No. : </td>
                            </tr>
                            <tr>
                                <td class="top_head">Dispatch Through : </td>
                            </tr>
                        </table>
                    </td>
                    <td class="top_position">
                        <table cellspacing="0" border="1">
                            <tr>
                                <td class="top_head">Date : 29-06-2023</td>
                            </tr>
                            <tr>
                                <td class="top_head">Term Of Payment : CREDIT</td>
                            </tr>
                            <tr>
                                <td class="top_head">Dated : 29-06-2023</td>
                            </tr>
                            <tr>
                                <td class="top_head">Destination : PANTHUR</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        Buyer details :-
                        <br />
                        <span class="party_name">KRISHNA ELECTRICALS & HARDWARE</span>
                        <br />
                        No. 82/2 A.M.P COMPLEX,
                        <br />
                        VILLAGE POST PANTHUR, BENGALUR
                        <br />
                        Phone No. : 9036507342, 9916291316
                        <br />
                        GST No. : 29DGHPS9790B1ZQ
                    </td>
                    <td colspan="2">
                        Terms of Payment
                        <br />
                        cheque to be issued Immediately at
                        <br />
                        Dilivery Time No Credit.
                        <br />
                        Cheque Bounce Rs. 1000 Will be Charged.
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <table cellspacing="0" border="1">
                            <tr>
                                <th>S No.</th>
                                <th>Description Of Goods</th>
                                <th>HSN</th>
                                <th>QTY</th>
                                <th>MRP</th>
                                <th>Rate</th>
                                <th>Disc %</th>
                                <th>GST %</th>
                                <th>Amount</th>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>DURA SINGLE BOWL SQ WITH D/B 36*19*8</td>
                                <td>73241000</td>
                                <td>1</td>
                                <td>9315.00</td>
                                <td>5868.45</td>
                                <td>0</td>
                                <td>18 %</td>
                                <td>4973.26</td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>NE-025A WALL FLANGE FLAT</td>
                                <td>73072100</td>
                                <td>100</td>
                                <td>18.00</td>
                                <td>11.34</td>
                                <td>0</td>
                                <td>18 %</td>
                                <td>961.02</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>SGST 9 %</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>534.08</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>CGST 9 %</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>534.08</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td>Round Off</td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>0.45</td>
                            </tr>
                            <tr>
                                <td colspan="3"></td>
                                <td>101</td>
                                <td colspan="4">BILL TOTAL</td>
                                <td>7002.00</td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        Amount Chargeable (in words)
                        <br />
                        Rs. Seven Thousand Two Only
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <table>
                            <tr>
                                <th>CLASS</th>
                                <th>TAXABLE</th>
                                <th>SGST</th>
                                <th>CGST</th>
                                <th>TOTAL GST</th>
                            </tr>
                            <tr>
                                <td>GST 5 %</td>
                                <td>0.00</td>
                                <td>0.00</td>
                                <td>0.00</td>
                                <td>0.00</td>
                            </tr>
                            <tr>
                                <td>GST 12 %</td>
                                <td>0.00</td>
                                <td>0.00</td>
                                <td>0.00</td>
                                <td>0.00</td>
                            </tr>
                            <tr>
                                <td>GST 18 %</td>
                                <td>5934.29</td>
                                <td>534.08</td>
                                <td>534.08</td>
                                <td>1068.16</td>
                            </tr>
                            <tr>
                                <td>GST 28 %</td>
                                <td>0.00</td>
                                <td>0.00</td>
                                <td>0.00</td>
                                <td>0.00</td>
                            </tr>
                            <tr>
                                <td>SUB TOTAL</td>
                                <td>5934.29</td>
                                <td>534.08</td>
                                <td>534.08</td>
                                <td>1068.16</td>
                            </tr>
                        </table>
                    </td>
                    <td>
                        <span class="bank_info">Company's Bank Details</span>
                        <br />
                        Bank Name : AXIS BANK
                        <br />
                        A/c No. : 922020012351849
                        <br />
                        Branch : GUNJUR
                        <br />
                        IFSC Code : UTIB0004291
                    </td>
                </tr>
                <tr>
                    <td colspan="3">
                        <span class="tandc_info">Terms & Conditions</span>
                        <br />
                        <ul>
                            <li>Goods once sold will not be taken back or exchanged.</li>
                            <li>Bills not paid due date will attract 24% interest.</li>
                            <li>Prescribed Sales Tax declaration will be given.</li>
                            <li>Certified that the particulars given above are true and correct Checked By ...............</li>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td class="comp_bottom">
                        <span class="customer_info">
                            Customers seal and Signature
                        </span>
                    </td>
                    <td colspan="2" class="comp_bottom">
                        <span class="comp_info">For PADMAVATHI MARKETING</span>
                        <br /><br /><br /><br />
                        Authorized signatory
                    </td>
                </tr>
            </table>
            <div class="disclaimer">
                All Disputes Subject to Bangalore Jurisdiction only.
            </div>
        </div> 
    </body>
</html>