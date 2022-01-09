<?php
require('../../classes/PdoDB.php');
require('../../classes/App.php');

$app = new App();
$app->safesession();
extract($_POST);

if (isset($_GET['checkoutForm'])) {
    $_SESSION['last_post'] = $_POST;
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
            $post = $_SESSION['last_post'];
            if ($res->status == 'success' && $res->data->tx_ref == $tx_ref  && $res->data->amount >= $post['totalCartSum']) {
                // echo $response;die;
                $res_data = $res->data;
                if (isset($_SESSION["cart"])) {
                    $cart = $_SESSION["cart"];
                    $cartId = time();
                    $success = 0;
                    for ($i = 0; $i < count($cart['btnId']); $i++) {
                        # code...
                        $id = $cart['btnId'][$i];
                        if(isset($post["colorId$id"])){
                            $colorId = $post["colorId$id"];
                            $old_color = $app->getValue("color", "pt_products", "id", $id);
                            if ($old_color != "") {
                                $old_color = json_decode($old_color);
                                $old_color = $old_color[$colorId];
                                $old_color = json_encode($old_color);
                            }
                        }
                        // die;

                        $db_handle = $dbh->prepare("INSERT INTO pt_transactions SET cart_id=:cart_id,prod_id=:prod_id,color=:color,qty=:qty,unit_price=:unit_price,qty_price=:qty_price,total_price=:total_price,name=:name,email=:email,address=:address,state=:state,phone=:phone, date=NOW()");
                        if ($db_handle->execute(array(':cart_id' => $cartId, ':prod_id' => $id, ':color' => isset($post["colorId$id"]) ? $old_color : '', ':qty' => $post["qtyVal$id"], ':unit_price' => $cart['btnPrice'][$i], ':qty_price' => $cart['btnPrice'][$i] * $post["qtyVal$id"], ':total_price' => $post["totalCartSum"], ':name' => $post["name"], ':email' => $post["email"], ':address' => $post["address"], ':state' => $post["state"], ':phone' => $post["phone"]))) {
                            $success++;
                        }
                    }
                    if($success==count($cart['btnId'])){
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
