<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Restaurant Sales Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.datatables.net/2.0.7/css/dataTables.dataTables.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css" rel="stylesheet">
    <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td,
        th {
            border: 1px solid #dddddd;
            text-align: right;
            padding: 8px;
        }

        @media(max-width:767px) {
            .table>:not(caption)>*>* {
                /* overflow-x: scroll; */
                font-size: 10px;
                padding: 0.2rem;
            }

            .table12 tr th {
                font-size: 10px !important;
            }

            label {
                font-size: 12px;
            }

            a {
                font-size: 12px !important;
            }
        }
    </style>
</head>

<body>
    <div class="container table-responsive">

        <h1 class="text-center mt-1">Sales Report</h1>
        <div class="row mb-1">
            <div class="col-sm-2"></div>
            <div class="col-sm-8 text-center">
                <form method="post">
<!-- date('Y-m-d') -->
                    <div class="row">
                        <div class="col-4">
                            <label>From Date</label>
                            <input type="date" class="form-control" id="fromDate" name="from_date" value="<?php 
                            if(isset($arr['from_date']))
                                {
                                 echo $arr['from_date'];
                             }
                            else{ echo date('Y-m-d');}?>" style="font-size:11px">
                        </div>
                        <div class="col-4">
                            <label>To Date</label>
                            <input type="date" class="form-control" id="toDate" name="to_date" value="<?php 
                            if(isset($arr['to_date']))
                                {
                                 echo $arr['to_date'];
                             }
                            else{ echo date('Y-m-d');}?>" style="font-size:11px">
                        </div>
                        <div class="col-4">
                            <br />
                            <input type="submit" class="btn" value="Search" style="font-size:11px;background-color:#F44336;color:white">
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-sm-2"></div>
        </div>
        <?php
        if (!empty($msg)) {
        ?>
            
                <h6 class="text-center text-danger pt-5"><?= $msg ?></h6>

            
        <?php
        }
        ?>
        <?php
        if (!empty($arr)) {
        ?>

            <a class="btn btn-sm" href="<?= base_url() ?>index.php/pdfReport_pdf_download/<?= $arr['admin_id'] ?>/<?= $arr['from_date'] ?>/<?= $arr['to_date'] ?>" download="FileName" style="background-color:#F44336;color:white">PDF</a>
            <a class="btn btn-primary btn-sm" href="<?= base_url() ?>index.php/csvRepost/<?= $arr['admin_id'] ?>/<?= $arr['from_date'] ?>/<?= $arr['to_date'] ?>" download="FileName">CSV</a>

        <?php
        }
        if (!empty($final_res)) {

        ?>


            <table class="table table-bordered table-striped table12 mt-1" id="example">
                <thead>
                    <tr>
                        <th scope="col">Date</th>
                        <th scope="col">Total Amount</th>
                        <th scope="col">Discount Amount</th>
                        <th scope="col">Total GST</th>
                        <th scope="col">Net Sales</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $t1 = 0;
                    $t2 = 0;
                    $t3 = 0;
                    $t4 = 0;

                    foreach ($final_res as $res) {
                        $t1 = $t1 + $res['total_amount'];
                        $t2 = $t2 + $res['discount_amount'];
                        $t3 = $t3 + $res['total_gst'];
                        $t4 = $t4 + $res['net_sales'];
                    ?>
                        <tr>
                            <td><?= $res['date'] ?></td>
                            <td><?= number_format($res['total_amount'], 2) ?></td>
                            <td><?= number_format($res['discount_amount'], 2) ?></td>
                            <td><?= number_format($res['total_gst'], 2) ?></td>
                            <td><?= number_format($res['net_sales'], 2) ?></td>

                        </tr>
                <?php
                    }
                    echo '<tr class="item" style="border-bottom: 1px solid #eee;background-color:#eee;">
                    <td style="text-align: center"><b>Total</b></td>
                    <td style="text-align: right">' . number_format($t1, 2) . '</td>
                    <td style="text-align: right">' . number_format($t2, 2) . '</td>
                    <td style="text-align: right">' . number_format($t3, 2) . '</td>
                    <td style="text-align: right">' . number_format($t4, 2) . '</td>
                    
                   
                  </tr>';
                }

                ?>



                </tbody>
            </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/2.0.7/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.dataTables.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.print.min.js"></script>
    <script>
        // new DataTable('#example', {
        //     layout: {
        //         topStart: {
        //             buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
        //         }
        //     }
        // });

document.getElementById("fromDate").addEventListener("change", function() {
    var fromDate = new Date(this.value);
    var toDateInput = document.getElementById("toDate");
    var today = new Date();

    // Disable dates greater than today in the From date picker
    if (fromDate > today) {
        alert("From date cannot be greater than today's date!");
        this.value = formatDate(today); // Set value to today's date
        fromDate = today;
    }

    // Set the minimum date for the To date picker
    toDateInput.min = formatDate(fromDate);

    // Reset To date value if it's invalid
    if (new Date(toDateInput.value) < fromDate) {
        toDateInput.value = formatDate(fromDate);
    }
});




function formatDate(date) {
    var day = date.getDate();
    var month = date.getMonth() + 1; // Month starts from 0
    var year = date.getFullYear();

    if (day < 10) day = '0' + day;
    if (month < 10) month = '0' + month;

    return year + '-' + month + '-' + day;
}
    </script>


</body>

</html>