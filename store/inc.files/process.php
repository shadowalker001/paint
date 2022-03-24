<?php
require('../../classes/PdoDB.php');
require('../../classes/App.php');

// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../../vendors/PHPMailer/src/Exception.php';
require '../../vendors/PHPMailer/src/PHPMailer.php';
require '../../vendors/PHPMailer/src/SMTP.php';
$app = new App();
$app->safesession();
extract($_POST);

if (isset($_GET['checkoutForm'])) {
    // $_SESSION['last_post'] = $_POST;
    if (!isset($_SESSION["last_post"])) {
        $_SESSION["last_post"] = array();
    }
    $_SESSION["last_post"] = array_merge($_SESSION["last_post"], $_POST);
    // print_r($_POST);die;
    $amount = $_POST['totalCartSum'];
    $name = $_POST['name'];

    //* Prepare our rave request
    $request = [
        'tx_ref' => time(),
        'amount' => $amount,
        'currency' => 'NGN',
        'payment_options' => 'card',
        'redirect_url' => $app->appUrl . 'store/inc.files/process',
        'customer' => [
            'email' => $email,
            'phone_number' => $phone,
            'name' => $name
        ],
        'meta' => [
            'price' => $amount
        ],
        'customizations' => [
            'title' => $app->app_title,
            'description' => 'secure payments for selected products',
            // "logo" => $app->appUrl . "assets/images/logo-light.png"
        ]
    ];

    //* Ca;; f;iterwave emdpoint
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.flutterwave.com/v3/payments',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($request),
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer ' . $app->fw_test_sKey,
            'Content-Type: application/json'
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    $res = json_decode($response);
    if ($res->status == 'success') {
        $link = $res->data->link;
        header('Location: ' . $link);
    } else {
        $_SESSION['PAY_ERROR'] = true;
        print '<script type="text/javascript"> self.location = "' . $app->server_root_dir("store/cart") . '" </script>';
        // $app->sweetAlert('warning', 'We can not process your payment!');
        // $app->buttonController('#smtBtn', 'enable');
        // print '<script type="text/javascript">$(\'#smtBtn\').html(\'<span>Checkout</span>\');</script>';
        exit;
    }
} else {
    if (isset($_GET['status'])) {
        if ($_GET['status'] == "successful") {
            $tx_ref = $_GET['tx_ref'];
            $transaction_id = $_GET['transaction_id'];
            $curl = curl_init();

            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.flutterwave.com/v3/transactions/$transaction_id/verify",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "Content-Type: application/json",
                    "Authorization: Bearer $app->fw_test_sKey"
                ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            $res = json_decode($response);
            // print_r($res);exit;
            $post = $_SESSION['last_post'];
            if ($res->status == 'success' && $res->data->tx_ref == $tx_ref  && $res->data->amount >= $post['totalCartSum']) {
                // echo $response;die;
                $res_data = $res->data;
                if (isset($_SESSION["cart"])) {
                    $cart = $_SESSION["cart"];
                    $cartId = time();
                    $success = 0;$title1 = array();$desc1 = array();$color1 = array();$qty = array();$unitprice = array();$qtyprice = array();
                    for ($i = 0; $i < count($cart['btnId']); $i++) {
                        # code...
                        $id = $cart['btnId'][$i];
                        if (isset($post["colorId$id"])) {
                            $colorId = $post["colorId$id"];
                            $old_color = $app->getValue("color", "pt_products", "id", $id);
                            if ($old_color != "") {
                                // $t = false;
                                // try{
                                $old_color = json_decode($old_color);
                                $old_color = $old_color[$colorId-1];
                                $old_color = json_encode($old_color);
                                // } catch (\Throwable $th) {
                                //     //throw $th;
                                //     die($th);
                                //     $t = true;
                                // }
                                // if($t==true){
                                //     die();
                                // }
                            }
                        }
                        // die;
                        //1234_Ozie

                        $db_handle = $dbh->prepare("INSERT INTO pt_transactions SET cart_id=:cart_id,prod_id=:prod_id,color=:color,qty=:qty,unit_price=:unit_price,qty_price=:qty_price,total_price=:total_price,name=:name,email=:email,address=:address,state=:state,phone=:phone, date=NOW()");
                        if ($db_handle->execute(array(':cart_id' => $cartId, ':prod_id' => $id, ':color' => isset($post["colorId$id"]) ? $old_color : '', ':qty' => $post["qtyVal$id"], ':unit_price' => $cart['btnPrice'][$i], ':qty_price' => $cart['btnPrice'][$i] * $post["qtyVal$id"], ':total_price' => $post["totalCartSum"], ':name' => $post["name"], ':email' => $post["email"], ':address' => $post["address"], ':state' => $post["state"], ':phone' => $post["phone"]))) {
                            $success++;
                            array_push($color1, isset($post["colorId$id"]) ? $old_color : '');
                            array_push($title1, $app->getValue("title","pt_products", "id", $id));
                            array_push($desc1, $app->getValue("description","pt_products", "id", $id));
                            array_push($qty, $post["qtyVal$id"]);
                            array_push($unitprice, $cart['btnPrice'][$i]);
                            array_push($qtyprice, $cart['btnPrice'][$i] * $post["qtyVal$id"]);
                        }
                    }
                    if ($success == count($cart['btnId'])) {
                        // Instantiation and passing `true` enables exceptions
                        $mail = new PHPMailer(true);
                        try {
                            // $mail->SMTPDebug = SMTP::DEBUG_SERVER;
                            $mail->isSMTP();
                            $mail->Host = $app->mail_host;
                            $mail->SMTPAuth   = true;
                            $mail->Username   = $app->mail_uname;
                            $mail->Password   = $app->mail_pass;
                            $mail->SMTPSecure = $app->mail_secure;
                            $mail->Port       = $app->mail_port;
                        
                            //Recipients
                            $mail->setFrom($app->mail_uname, $app->app_title);
                            $mail->addAddress($post["email"], $post["name"]);
                            $mail->Subject = 'ORDER DETAILS';
                            $mail ->AddEmbeddedImage('../../static/img/logo/logo.png', 'logoimg');
                            $m = '';
                            for ($i=0; $i < count($title1); $i++) { 
                                # code...
                                $m .= '<strong>Title: </strong> '.$title1[$i].' <br>';
                                $m .= '<strong>Description: </strong> '.$desc1[$i].' <br>';
                                if($color1[$i]!=''){
                                    $m .= '<strong>Color: </strong> '.json_decode($color1[$i])->name.' <br>';
                                }
                                $m .= '<strong>Quantity: </strong> '.$qty[$i].' <br>';
                                $m .= '<strong>Unit Price: </strong> ₦ '.number_format($unitprice[$i]).' <br>';
                                $m .= '<strong>Quantity Price: </strong> ₦ '.number_format($qtyprice[$i]).' <br>';
                                $m .= '<br>';
                            }
                            $msg = '<h5>ORDER DETAILS</h5>
                            <strong>Name: </strong> '.$post["name"].' <br>
                            <strong>Email: </strong> '.$post["email"].' <br>
                            <strong>Address: </strong> '.$post["address"].' <br>
                            <strong>Phone: </strong> '.$post["phone"].' <br>
                            <strong>Total Amount: </strong> ₦ '.number_format($post["totalCartSum"]).' <br><hr>
                            '.$m;
                            $mail->Body    = $app->email($post["name"], "Transaction successful, your product is ready for shipment!<br> $msg", $app->server_root_dir(''), "Visit Us");
                            $mail->AltBody = "Dear ".$post['name'].", Transaction successful, your product is ready for shipment!<br> $msg";
                        
                            // Content
                            $mail->isHTML(true);
                        
                            $mail->send();
                        } catch (Exception $e) {
                            print_r($mail->ErrorInfo);
                            // $app->sweetAlert('warning', 'Email could not be sent, try again later!');
                            // $app->sweetAlert('warning', 'Unable to deliver message, try again later!');
                            die;
                        }
                        $_SESSION['PAY_SUCCESS'] = true;
                        unset($_SESSION["cart"]);
                        unset($_SESSION["last_post"]);
                        print '<script type="text/javascript"> self.location = "' . $app->server_root_dir("store") . '" </script>';
                    }
                }
            } else {
                echo $response;
            }
        } else {
            $_SESSION['PAY_ERROR'] = true;
            print '<script type="text/javascript"> self.location = "' . $app->server_root_dir("store/cart") . '" </script>';
        }
    }
}
