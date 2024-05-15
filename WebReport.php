<?php

defined('BASEPATH') or exit('No direct script access allowed');

class WebReport extends CI_Controller
{
    public function index($resID)
    {
        ini_set('display_errors', '0');
        ini_set('display_startup_errors', '0');
        // error_reporting(E_ALL);
        $msg = "";
        $this->load->model('Supervisor');
        if ($this->input->method('post') == 'POST') {
            if (isset($resID) && !empty($resID)) {
                $admin_id = $resID;
                $from_date = $this->input->post('from_date');
                $to_date = $this->input->post('to_date');

                if (!empty($from_date) && !empty($to_date)) {
                    if ($from_date <= $to_date) {
                        $result = $this->Supervisor->getsalesReport($admin_id, $from_date, $to_date);
                        if (!empty($result)) {
                            $final_array = array();
                            $count = count($result);
                            $i = 1;
                            foreach ($result as $value) {
                                $create_date = date('Y-m-d', strtotime($value['creation_date']));
                                $final_array[$create_date]['date'] = $create_date;
                                $final_array[$create_date]['total_amount'] += round($value['total_order_amount'], 2);
                                // Discount%=(Original Price - Sale price)/Original price*100
                                $final_array[$create_date]['discount'] += $value['discount'];
                                $final_array[$create_date]['discount_amount'] += round($value['discount_amount'], 2);
                                $final_array[$create_date]['total_gst'] += round($value['total_gst'], 2);
                                $final_array[$create_date]['net_sales'] += round(round(($value['total_order_amount'] - $value['discount_amount']), 2) + round($value['total_gst'], 2), 2);
                                if (isset($value['get_payment']) && $value['get_payment'] != NULL && $value['get_payment'] != '') {
                                    if (isset($final_array[$create_date]['channel'])) {
                                        $final_array[$create_date]['channel'] .= ',' . $value['get_payment'];
                                    } else {
                                        $final_array[$create_date]['channel'] .= $value['get_payment'];
                                    }
                                }
                                if (isset($value['payment_mode']) && $value['payment_mode'] != NULL && $value['payment_mode'] != '') {
                                    if (isset($final_array[$create_date]['channel'])) {
                                        $final_array[$create_date]['channel'] .= ',' . $value['payment_mode'];
                                    } else {
                                        $final_array[$create_date]['channel'] .= $value['payment_mode'];
                                    }
                                }
                                if ($i == $count) {
                                    $final_array[$create_date]['discount'] = round(($final_array[$create_date]['discount_amount'] / $final_array[$create_date]['total_amount']) * 100, 2);
                                    $final_array[$create_date]['channel'] = implode(',', array_unique(explode(',', $final_array[$create_date]['channel'])));
                                }
                                $i++;
                            }
                            $final_res = array();
                            foreach ($final_array as $val) {
                                $val['channel'] = isset($val['channel']) ? $val['channel'] : '';
                                array_push($final_res, $val);
                            }
                            $data['final_res'] = $final_res;
                            $arr = array('admin_id' => $admin_id, 'from_date' => $from_date, 'to_date' => $to_date);
                            $data['arr'] = $arr;
                            // print_r($data);
                            // die();
                            $this->load->view('webReport', $data);
                        } else {
                            $msg = 'Data not found.';
                            $data['msg'] = $msg;
                            $this->load->view('webReport', $data);
                        }
                    } else {
                        $msg = 'Please enter To Date should be greater than From Date.';
                        $data['msg'] = $msg;
                        $this->load->view('webReport', $data);
                    }
                } else {
                    $msg =  'From and To Date are required.';
                    $data['msg'] = $msg;
                    $this->load->view('webReport', $data);
                }
            } else {
                $msg =  "Restorent not found.";
                $data['msg'] = $msg;
                $this->load->view('webReport', $data);
            }
        } else {
            $data['msg'] = $msg;
            $this->load->view('webReport', $data);
        }
    }

    public function csvRepost($admin_id, $from_date, $to_date)
    {
        ini_set('display_errors', '0');
        ini_set('display_startup_errors', '0');
        // error_reporting(E_ALL);
        try {

            if (!empty($from_date) && !empty($to_date)) {
                if ($from_date < $to_date) {
                    $result = $this->Supervisor->getsalesReport($admin_id, $from_date, $to_date);
                    if (!empty($result)) {
                        $final_array = array();
                        $count = count($result);
                        $i = 1;
                        foreach ($result as $value) {
                            $create_date = date('Y-m-d', strtotime($value['creation_date']));
                            // $final_array[$create_date]['total_amount'] = 0;
                            // $final_array[$create_date]['discount'] = 0;
                            // $final_array[$create_date]['discount_amount'] = 0;
                            // $final_array[$create_date]['total_gst'] = 0;
                            // $final_array[$create_date]['net_sales'] = 0;
                            $final_array[$create_date]['date'] = $create_date;
                            $final_array[$create_date]['total_amount'] += round($value['total_order_amount'], 2);
                            // Discount%=(Original Price - Sale price)/Original price*100
                            $final_array[$create_date]['discount'] += $value['discount'];
                            $final_array[$create_date]['discount_amount'] += round($value['discount_amount'], 2);
                            $final_array[$create_date]['total_gst'] += round($value['total_gst'], 2);
                            $final_array[$create_date]['net_sales'] += round(round(($value['total_order_amount'] - $value['discount_amount']), 2) + round($value['total_gst'], 2), 2);
                            if (isset($value['get_payment']) && $value['get_payment'] != NULL && $value['get_payment'] != '') {
                                if (isset($final_array[$create_date]['channel'])) {
                                    $final_array[$create_date]['channel'] .= ',' . $value['get_payment'];
                                } else {
                                    $final_array[$create_date]['channel'] .= $value['get_payment'];
                                }
                            }
                            if (isset($value['payment_mode']) && $value['payment_mode'] != NULL && $value['payment_mode'] != '') {
                                if (isset($final_array[$create_date]['channel'])) {
                                    $final_array[$create_date]['channel'] .= ',' . $value['payment_mode'];
                                } else {
                                    $final_array[$create_date]['channel'] .= $value['payment_mode'];
                                }
                            }
                            if ($i == $count) {
                                $final_array[$create_date]['discount'] = round(($final_array[$create_date]['discount_amount'] / $final_array[$create_date]['total_amount']) * 100, 2);
                                $final_array[$create_date]['channel'] = implode(',', array_unique(explode(',', $final_array[$create_date]['channel'])));
                            }
                            $i++;
                        }
                        $final_res = array();
                        foreach ($final_array as $val) {
                            $val['channel'] = isset($val['channel']) ? $val['channel'] : '';
                            array_push($final_res, $val);
                        }
                        $delimiter = ",";
                        $filename = "csvSalesRepost.csv";

                        // Create a file pointer 
                        $f = fopen('php://memory', 'w');

                        // Set column headers 
                        $fields = array('Date', 'Total Amount', 'Discount Amount', 'Total GST', 'Net Sales');
                        fputcsv($f, $fields, $delimiter);

                        $t1 = 0;
                        $t2 = 0;
                        $t3 = 0;
                        $t4 = 0;

                        // Output each row of the data, format line as csv and write to file pointer 
                        foreach ($final_res as $row8) {
                            $t1 = $t1 + $row8['total_amount'];
                            $t2 = $t2 + $row8['discount_amount'];
                            $t3 = $t3 + $row8['total_gst'];
                            $t4 = $t4 + $row8['net_sales'];
                            $lineData = array($row8['date'], number_format($row8['total_amount'], 2), number_format($row8['discount_amount'], 2), number_format($row8['total_gst'], 2), number_format($row8['net_sales'], 2));
                            fputcsv($f, $lineData, $delimiter);
                        }
                        $fields = array('Total', number_format($t1, 2), number_format($t2, 2), number_format($t3, 2), number_format($t4, 2));
                        fputcsv($f, $fields, $delimiter);
                        // Move back to beginning of file 
                        fseek($f, 0);

                        // Set headers to download file rather than displayed
                        header('Content-Description: File Transfer');
                        header('Content-Type: application/force-download');
                        header('Content-Type: text/csv');
                        header('Content-Disposition: attachment; filename="' . $filename . '";');

                        //output all remaining data on a file pointer 
                        fpassthru($f);
                    } else {
                        echo "Data not found";
                        // $aray = array('status' => '0', 'message' => 'Data not found..');
                        // $this->response($aray, 200);
                    }
                } else {
                    echo "Please enter to date should be greater than from date..";
                    // $aray = array('status' => '0', 'message' => 'Please enter to date should be greater than from date..');
                    // $this->response($aray, 200);
                }
            } else {
                echo "From and To Date are required..";
                // $aray = array('status' => '0', 'message' => 'From and To Date are required..');
                // $this->response($aray, 200);
            }
        } catch (Ececption $e) {
            echo $e->getMessage();
            echo "Internal Server Error - Please try Later.";
            // $error = array('status' => '0', "data" => "Internal Server Error - Please try Later.", "StatusCode" => "HTTP405");
            // $this->response($error, 200);
        }
    }
    public function pdfReport_pdf_download($admin_id, $from_date, $to_date)
    {
        ini_set('display_errors', '0');
        ini_set('display_startup_errors', '0');
        // error_reporting(E_ALL);
        try {
            if (!empty($from_date) && !empty($to_date)) {
                if ($from_date < $to_date) {
                    $result = $this->Supervisor->getsalesReport($admin_id, $from_date, $to_date);
                    if (!empty($result)) {
                        $final_array = array();
                        $count = count($result);
                        $i = 1;
                        foreach ($result as $value) {
                            $create_date = date('Y-m-d', strtotime($value['creation_date']));
                            // $final_array[$create_date]['total_amount'] = 0;
                            // $final_array[$create_date]['discount'] = 0;
                            // $final_array[$create_date]['discount_amount'] = 0;
                            // $final_array[$create_date]['total_gst'] = 0;
                            // $final_array[$create_date]['net_sales'] = 0;
                            $final_array[$create_date]['date'] = $create_date;
                            $final_array[$create_date]['total_amount'] += round($value['total_order_amount'], 2);
                            // Discount%=(Original Price - Sale price)/Original price*100
                            $final_array[$create_date]['discount'] += $value['discount'];
                            $final_array[$create_date]['discount_amount'] += round($value['discount_amount'], 2);
                            $final_array[$create_date]['total_gst'] += round($value['total_gst'], 2);
                            $final_array[$create_date]['net_sales'] += round(round(($value['total_order_amount'] - $value['discount_amount']), 2) + round($value['total_gst'], 2), 2);
                            if (isset($value['get_payment']) && $value['get_payment'] != NULL && $value['get_payment'] != '') {
                                if (isset($final_array[$create_date]['channel'])) {
                                    $final_array[$create_date]['channel'] .= ',' . $value['get_payment'];
                                } else {
                                    $final_array[$create_date]['channel'] .= $value['get_payment'];
                                }
                            }
                            if (isset($value['payment_mode']) && $value['payment_mode'] != NULL && $value['payment_mode'] != '') {
                                if (isset($final_array[$create_date]['channel'])) {
                                    $final_array[$create_date]['channel'] .= ',' . $value['payment_mode'];
                                } else {
                                    $final_array[$create_date]['channel'] .= $value['payment_mode'];
                                }
                            }
                            if ($i == $count) {
                                $final_array[$create_date]['discount'] = round(($final_array[$create_date]['discount_amount'] / $final_array[$create_date]['total_amount']) * 100, 2);
                                $final_array[$create_date]['channel'] = implode(',', array_unique(explode(',', $final_array[$create_date]['channel'])));
                            }
                            $i++;
                        }
                        $final_res = array();
                        foreach ($final_array as $val) {
                            $val['channel'] = isset($val['channel']) ? $val['channel'] : '';
                            array_push($final_res, $val);
                        }
                        $query1 = $this->db->get_where('spots', array('admin_id' => $admin_id), 1);
                        $row1 = $query1->row_array();

                        require FCPATH . 'vendor/autoload.php';

                        $mpdf = new \Mpdf\Mpdf();
                        $pdfhtml = '';
                        $pdfhtml .= '<div class="invoice-box" style="max-width: 800px;margin: auto;padding: 30px;
            font-size: 16px;
            line-height: 24px;font-family: "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif;
            color: #555;">
            <h2 style="text-align:center">Sales Report</h2>
            <table  style="border: none;border-right: 0px solid;">
            <tr style="border: none;border-right: 0px solid;">
            <td style="text-align:left;border:none">
            <p><b>Name</b> :- ' . $row1['name'] . '<br/> <b> Address</b> :- ' . $row1['address'] . '<br/><b> Mobile No.</b> :- ' . $row1['phone'] . '</p>
            </td>
            <td style="border:none">
            <p style="text-align:left"><b>GST No.</b> :- ' . $row1['gst_no'] . '<br/><b>From</b> :- ' . $from_date . '<br/> <b> To</b> :- ' . $to_date . '</p>
            </td>
            </tr>
            </table>
            <style>
            table {
              font-family: arial, sans-serif;
              border-collapse: collapse;
              width: 100%;
            }
            
            td, th {
              border: 1px solid #dddddd;
              text-align: right;
              padding: 8px;
            }
            
            
            
            </style>
          <table cellpadding="0" cellspacing="0"  style="width: 100%;line-height: inherit;text-align: left;margin-top:10px">
            
        
            
        
            <tr class="heading" style="background: #eee;border-bottom: 1px solid #ddd;font-weight: bold;">
              <th style="padding: 5px;vertical-align: top;text-align: center">Date</th>
              <th style="padding: 5px;vertical-align: top;">Total Amt.</th>
              
              <th style="padding: 5px;vertical-align: top;">Discount Amt.</th>
              <th style="padding: 5px;vertical-align: top;">Total GST</th>
              <th style="padding: 5px;vertical-align: top;">Net Sales</th>
              
            </tr>';
                        $t1 = 0;
                        $t2 = 0;
                        $t3 = 0;
                        $t4 = 0;
                        foreach ($final_res as $res) {
                            $t1 = $t1 + $res['total_amount'];
                            $t2 = $t2 + $res['discount_amount'];
                            $t3 = $t3 + $res['total_gst'];
                            $t4 = $t4 + $res['net_sales'];
                            $pdfhtml .= '<tr class="item" style="border-bottom: 1px solid #eee;">
                  <td style="text-align: center">' . $res['date'] . '</td>
                  <td style="text-align: right">' . number_format($res['total_amount'], 2) . '</td>
                  <td style="text-align: right">' . number_format($res['discount_amount'], 2) . '</td>
                  <td style="text-align: right">' . number_format($res['total_gst'], 2) . '</td>
                  <td style="text-align: right">' . number_format($res['net_sales'], 2) . '</td>
                  
                 
                </tr>';
                        }
                        $pdfhtml .= '<tr class="item" style="border-bottom: 1px solid #eee;background-color:#eee;">
                  <td style="text-align: center"><b>Total</b></td>
                  <td style="text-align: right">' . number_format($t1, 2) . '</td>
                  <td style="text-align: right">' . number_format($t2, 2) . '</td>
                  <td style="text-align: right">' . number_format($t3, 2) . '</td>
                  <td style="text-align: right">' . number_format($t4, 2) . '</td>
                  
                 
                </tr>';

                        $pdfhtml .= '</table>
        </div>';

                        $mpdf = new \Mpdf\Mpdf([
                            'format' => 'A4',
                            'margin_top' => 0,
                            'margin_right' => 0,
                            'margin_left' => 0,
                            'margin_bottom' => 0,
                        ]);
                        $mpdf->WriteHTML($pdfhtml);
                        // Other code
                        $pdfFilePath = "salesReport" . rand() . ".pdf";
                        $mpdf->Output($pdfFilePath, 'D');
                        // $pdfFilePath = "salesReport.pdf";
                        // $res = $mpdf->Output($_SERVER["DOCUMENT_ROOT"] . "/oyly/salesReport/" . $pdfFilePath, "D");
                        // $attchment = base_url() . "salesReport/" . $pdfFilePath;
                        // $aray = array('status' => '1', 'data' => $attchment, 'message' => 'success');
                        // $this->response($aray, 200);
                    } else {
                        echo "Data not found";
                        // $aray = array('status' => '0', 'message' => 'Data not found..');
                        // $this->response($aray, 200);
                    }
                } else {
                    echo "Please enter to date should be greater than from date..";
                    // $aray = array('status' => '0', 'message' => 'Please enter to date should be greater than from date..');
                    // $this->response($aray, 200);
                }
            } else {
                echo "From and To Date are required..";
                // $aray = array('status' => '0', 'message' => 'From and To Date are required..');
                // $this->response($aray, 200);
            }
        } catch (Ececption $e) {
            echo $e->getMessage();
            echo "Internal Server Error - Please try Later.";
            // $error = array('status' => '0', "data" => "Internal Server Error - Please try Later.", "StatusCode" => "HTTP405");
            // $this->response($error, 200);
        }
    }
}
