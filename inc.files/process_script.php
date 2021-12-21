<?php
require('../classes/PdoDB.php');
require('../classes/App.php');
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../vendors/PHPMailer/src/Exception.php';
require '../vendors/PHPMailer/src/PHPMailer.php';
require '../vendors/PHPMailer/src/SMTP.php';

// Encryption files
require '../classes/Aes.php';     // AES PHP implementation
require '../classes/AesCtr.php';  // AES Counter Mode implementation

$app = new App();
$app->safesession();
extract($_POST);

if (isset($_GET['mode'])) {
    $mode = $_GET['mode'];
    if ($mode == "contactForm") {
        if ($app->strIsEmpty($name) or $app->strIsEmpty($message) or $app->strIsEmpty($email)) {
            $app->sweetAlert('warning', 'Fields cannot be Empty!');
            $app->buttonController('#smtBtn', 'enable');
            print '<script type="text/javascript">$(\'#smtBtn\').html(\'send message <i class="far fa-arrow-right"></i>\');</script>';
            die;
        }

        try {
            $db_handle = $dbh->prepare("INSERT INTO pt_contact SET name=:name, email=:email, message=:message, date=Now()");
            if ($db_handle->execute(array(':name' => $name, ':email' => $email, ':message' => $message))) {
                $app->sweetAlert('success', 'Message delivered successfully!');
                $app->buttonController('#smtBtn', 'enable');
                print '<script type="text/javascript">$(\'#contactForm\').trigger("reset"); $(\'#smtBtn\').html(\'send message <i class="far fa-arrow-right"></i>\');</script>';
                exit;
            }
        } catch (PDOException $error) {
            $app->buttonController('#smtBtn', 'enable');
            print '<script type="text/javascript">$(\'#smtBtn\').html(\'send message <i class="far fa-arrow-right"></i>\');</script>';
            die($error->getMessage());
        }
    } else if ($mode == "adinlogForm") {
        $isAuthenticated = false;

        if ($app->strIsEmpty($email) or $app->strIsEmpty($password)) {
            $app->sweetAlert('warning', 'Fields cannot be Empty!');
            $app->buttonController('#smtBtn', 'enable');
            print '<script type="text/javascript">$(\'#smtBtn\').html(\'Sign In <i class="fas fa-sign-in-alt ml-1"></i>\');</script>';
            die;
        }

        $admin = (array)$app->getAdminByEmail($email);
        if (count($admin) > 1) {
            if (password_verify($password, $admin["password"])) {
                $isAuthenticated = true;
            }
        }

        if ($isAuthenticated) {
            if ($admin["status"] != '1') {
                $app->sweetAlert('warning', 'This Account is not Active or has been Blocked.');
                $app->buttonController('#smtBtn', 'enable');
                print '<script type="text/javascript">$(\'#smtBtn\').html(\'Sign In <i class="fas fa-sign-in-alt ml-1"></i>\');</script>';
                die;
            } else {
                $admin = (object) $admin;
                $dynamicimage = $app->imageDynamic($admin->picture, $app->server_root_dir('assets/images/'));
                $app->setCookie('admin_hold_fullname', "$admin->fname");
                $app->setCookie('admin_hold_email', $admin->email);
                $app->setCookie('admin_hold_phone', @$admin->phone);
                $app->setCookie('admin_hold_picture', $dynamicimage);
                $_SESSION['tappAdminId'] = $admin->id;
                sleep(1);
                print '<script type="text/javascript"> self.location = "' . $app->server_root_dir("admin/dashboard/home") . '" </script>';
            }
        } else {
            //display a normal login error message
            $app->sweetAlert('warning', 'Incorrect Login Details!');
            $app->buttonController('#smtBtn', 'enable');
            print '<script type="text/javascript">$(\'#smtBtn\').html(\'Sign In <i class="fas fa-sign-in-alt ml-1"></i>\');</script>';
            die;
        }
    } else if ($mode == "profilePhotoA") {
        /* Getting file name */
        $filename = $_FILES['file']['name'];
        $final_name = "img_pt_" . $app->generateRandomString() . ".jpg";

        /* Location */
        // $location = $app->server_root_dir("pictures/$final_name");
        $location = "../assets/images/$final_name";
        //update to DB
        if ($app->updatePersonalPicture($final_name, "pt_admin", $_SESSION['tappAdminId'])) {
            if (move_uploaded_file($_FILES['file']['tmp_name'], $location)) {
                $location = $app->server_root_dir("assets/images/$final_name");
                $app->setCookie('admin_hold_picture', $location);
                print "<script type='text/javascript'>$('.userdpsrc').attr('src','$location');$('.userdpbg').css('background-image','url($location)');</script>";
                $app->sweetAlert('success', 'Display Picture Updataed!');
            } else {
                $app->sweetAlert('warning', 'Unable to move display picture!');
            }
        } else {
            $app->sweetAlert('warning', 'Unable to update display picture, try again later!');
        }
    } else if ($mode == "activateProduct") {
        if (!empty($btnId) && is_numeric($btnId) && strrev($byepass) == "shadowalker") {
            $db_handle = $dbh->prepare("UPDATE pt_products SET status=1 WHERE id='$btnId'");
            if ($db_handle->execute()) {
                print "<script type=\"text/javascript\">
                        //$(':button').removeAttr('disabled');
                    </script>";
                $app->toaster('success', 'Product Activated Successfully!');
                print "<script type=\"text/javascript\">
                            $('#user$btnId').addClass('deactivateProduct');
                            $('#user$btnId').removeClass('activateProduct');
                            $('#user$btnId').html('<i class=\"fas fa-key\"></i> Deactivate');
                            $('#user$btnId').addClass('btn-warning');
                            $('#user$btnId').removeClass('btn-success');
                            $('#userSpan$btnId').addClass('btn-success');
                            $('#userSpan$btnId').removeClass('btn-warning');
                            $('#userSpan$btnId').html('Active');
                        </script>";
                exit();
            } else {
                print "<script type=\"text/javascript\">
                        $('#user$btnId').html('<i class=\"fas fa-key\"></i> Activate');
                    </script>";
                $app->sweetAlert('warning', 'Unable to activate, try again later!');
                exit();
            }
        } else {
            $app->sweetAlert('warning', 'Something went wrong, try again later!');
            print "<script type=\"text/javascript\">
                    $('#user$btnId').html('<i class=\"fas fa-key\"></i> Activate');
                </script>";
            exit();
        }
    } else if ($mode == "deactivateProduct") {
        if (!empty($btnId) && is_numeric($btnId) && strrev($byepass) == "shadowalker") {
            $db_handle = $dbh->prepare("UPDATE pt_products SET status=0 WHERE id='$btnId'");
            if ($db_handle->execute()) {
                print "<script type=\"text/javascript\">
                        //$(':button').removeAttr('disabled');
                    </script>";
                $app->toaster('success', 'Product Dectivated Successfully!');
                print "<script type=\"text/javascript\">
                            $('#user$btnId').addClass('activateProduct');
                            $('#user$btnId').removeClass('deactivateProduct');
                            $('#user$btnId').html('<i class=\"fas fa-key\"></i> Activate');
                            $('#user$btnId').addClass('btn-success');
                            $('#user$btnId').removeClass('btn-warning');
                            $('#userSpan$btnId').addClass('btn-warning');
                            $('#userSpan$btnId').removeClass('btn-success');
                            $('#userSpan$btnId').html('Inactive');
                        </script>";
                exit();
            } else {
                print "<script type=\"text/javascript\">
                        $('#user$btnId').html('<i class=\"fas fa-key\"></i> Deactivate');
                    </script>";
                $app->sweetAlert('warning', 'Unable to deactivate, try again later!');
                exit();
            }
        } else {
            $app->sweetAlert('warning', 'Something went wrong, try again later!');
            print "<script type=\"text/javascript\">
                    $('#user$btnId').html('<i class=\"fas fa-key\"></i> Deactivate');
                </script>";
            exit();
        }
    } else if ($mode == "logOutAdmin") {
        unset($_SESSION['tappAdminId']);
        $app->setCookie('admin_hold_fullname', '');
        $app->setCookie('admin_hold_email', '');
        $app->setCookie('admin_hold_phone', '');
        $app->setCookie('admin_hold_picture', '');
        print '<script> self.location = "' . $app->server_root_dir('admin') . '" </script>';
    } else if ($mode == "adminprofileForm") {
        $qstr = " WHERE id='" . $_SESSION['tappAdminId'] . "'";

        if ($app->strIsEmpty($fname)) {
            $app->sweetAlert('warning', 'Fields cannot be Empty!');
            $app->buttonController('#smtBtn', 'enable');
            print '<script type="text/javascript">$(\'#smtBtn\').html(\'Update Profile <i class="fas fa-sign-in-alt">\');</script>';
            exit;
        }

        if ($app->updateProfileAdmin($fname, $qstr) === true) {
            $app->setCookie('admin_hold_fullname', "$fname");
            print "<script type='text/javascript'>$('.admin_hold_fullname').html('$fname');</script>";
            $app->sweetAlert('success', 'Profile Updated successfully!');
            $app->buttonController('#smtBtn', 'enable');
            print '<script type="text/javascript">$("#password").val("");$("#repeatPassword").val("");$(\'#smtBtn\').html(\'Update Profile <i class="fas fa-sign-in-alt">\');</script>';
        } else {
            $app->sweetAlert('warning', 'Unable to update profile, try again latar!');
            $app->buttonController('#smtBtn', 'enable');
            print '<script type="text/javascript">$(\'#smtBtn\').html(\'Update Profile <i class="fas fa-sign-in-alt">\');</script>';
            exit;
        }
    } else if ($mode == "adminupdatePassword") {
        if ($app->strIsEmpty($password) or $app->strIsEmpty($repeatPassword) or $app->strIsEmpty($old_password)) {
            $app->sweetAlert('warning', 'Fields cannot be Empty!');
            $app->buttonController('#smtBtnPass', 'enable');
            print '<script type="text/javascript">$(\'#smtBtnPass\').html(\'Update Password<i class="fas fa-sign ml-1"></i>\');</script>';
            exit;
        }

        if ($password != $repeatPassword) {
            $app->sweetAlert('warning', 'Passwords not the same!');
            $app->buttonController('#smtBtnPass', 'enable');
            print '<script type="text/javascript">$(\'#smtBtnPass\').html(\'Update Password<i class="fas fa-sign ml-1"></i>\');</script>';
            exit;
        }

        $userPassword = $app->getValue("password", "pt_admin", "id", $_SESSION['tappAdminId']);
        if (!password_verify($old_password, $userPassword)) {
            $app->sweetAlert('warning', 'Incorrect User Password!');
            $app->buttonController('#smtBtnPass', 'enable');
            print '<script type="text/javascript">$(\'#smtBtnPass\').html(\'Update Password<i class="fas fa-sign ml-1"></i>\');</script>';
            exit;
        }
        $password_hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 11]);
        $userId = $_SESSION['tappAdminId'];

        if ($app->updatePassword($password_hash, "pt_admin", "WHERE id='$userId'") === true) {
            $app->sweetAlert('success', 'Password Updated successfully!');
            $app->buttonController('#smtBtnPass', 'enable');
            print '<script type="text/javascript">$("#old_password").val("");$("#password").val("");$("#repeatPassword").val("");$(\'#smtBtnPass\').html(\'Update Password<i class="fas fa-sign ml-1"></i>\');</script>';
            exit;
        } else {
            $app->sweetAlert('warning', 'Unable to update profile, try again latar!');
            $app->buttonController('#smtBtnPass', 'enable');
            print '<script type="text/javascript">$(\'#smtBtnPass\').html(\'Update Password<i class="fas fa-sign ml-1"></i>\');</script>';
            exit;
        }
    } else if ($mode == "uploadForm") {
        $title = ucwords($itle);
        $grade = $grade;
        $term = $app->getValue("term", "vc_system", "id", "1");
        $video_description = $video_description;
        $schedule = $schedule;

        if ($app->strIsEmpty($title) or $app->strIsEmpty($grade) or $app->strIsEmpty($video_description) or $app->strIsEmpty($schedule)) {
            $app->sweetAlert('warning', 'Fields cannot be Empty!');
            $app->buttonController('#smtBtn', 'enable');
            print '<script type="text/javascript">$(\'#smtBtn\').html(\'Upload <i class="fas fa-upload"></i>\');</script>';
            die;
        }

        $errors = 0;
        $exists = 0;
        $success = 0;

        for ($i = 0; $i < count($_FILES); $i++) {
            # code...
            $pos = 'file_' . $i;
            $final_name = str_replace(" ", "", strtolower(trim($_FILES[$pos]['name'])));
            $final_name = "file_vc_" . $app->generateRandomString(10) . "_" . time() . $final_name;

            $location = "../vendor/files/$final_name";
            $loc = $app->server_root_dir("vendor/files/$final_name");
            $explode = explode(".", $_FILES[$pos]['name']);
            $type = end($explode);

            if (!file_exists($location)) {
                $fileData = file_get_contents($_FILES[$pos]['tmp_name']);
                if (file_put_contents($location, $fileData)) {
                    unlink($_FILES[$pos]['tmp_name']);
                    // if(move_uploaded_file($_FILES[$pos]['tmp_name'],$location)){
                    $querySQL = "INSERT INTO vc_uploads SET term=:term, title=:title, grade=:grade, video=:video, description=:description, schedule=:schedule, date=NOW()";
                    $db_handle = $dbh->prepare($querySQL);
                    if ($db_handle->execute(array(':term' => $term, 'title' => $title, 'grade' => $grade, 'video' => $final_name, 'description' => $video_description, 'schedule' => $schedule))) {
                        $success += 1;
                    } else {
                        $errors += 1;
                    }
                } else {
                    $errors += 1;
                }
            } else {
                $exists += 1;
            }
        }

        if ($errors == 0 && $exists == 0) {
            $app->sweetAlert('success', 'Record created successfully!');
            $app->buttonController('#smtBtn', 'enable');
            print '<script type="text/javascript">$(\'#uploadForm\').trigger("reset"); $(\'#smtBtn\').html(\'Upload <i class="fas fa-upload"></i>\');</script>';
        } else {
            $app->sweetAlert('warning', 'Server error, try again later!');
            $app->buttonController('#smtBtn', 'enable');
            print '<script type="text/javascript">$(\'#uploadForm\').trigger("reset"); $(\'#smtBtn\').html(\'Upload <i class="fas fa-upload"></i>\');</script>';
        }
    } else if ($mode == "productForm") {
        if (empty($title) || !is_numeric($title)) {
            $app->sweetAlert('warning', 'Fields cannot be Empty!');
            $app->buttonController('#smtBtn', 'enable');
            print '<script type="text/javascript"> $(\'#smtBtn\').html(\'Submit Product <i class="fas fa-sign-in-alt"></i>\');</script>';
            die;
        }

        if ($app->valueExists("id", "pt_products", $title, "AND status=1") == false) {
            $app->sweetAlert('warning', 'Product is not registered!');
            $app->buttonController('#smtBtn', 'enable');
            print '<script type="text/javascript"> $(\'#smtBtn\').html(\'Submit Product <i class="fas fa-sign-in-alt"></i>\');</script>';
            die;
        }

        if ($app->valueExists("Product_id", "pt_students", $title) == true) {
            $app->sweetAlert('warning', 'Product already chosen by another student!');
            $app->buttonController('#smtBtn', 'enable');
            print '<script type="text/javascript"> $(\'#smtBtn\').html(\'Submit Product <i class="fas fa-sign-in-alt"></i>\');</script>';
            die;
        }

        try {
            $db_handle = $dbh->prepare("UPDATE pt_students SET Product_id=:Product_id WHERE id=:id");
            if ($db_handle->execute(array(':Product_id' => $title, ':id' => $_SESSION['tappUserId']))) {
                $app->sweetAlert('success', 'Product Assigned to student successfully!');
                $app->buttonController('#smtBtn', 'enable');
                print '<script type="text/javascript">$(\'#ProductForm\').trigger("reset"); $(\'#smtBtn\').html(\'Submit Product <i class="fa sign-in-alt"></i>\');</script>';
                print '<script type="text/javascript"> setTimeout(() => { self.location = "' . $app->server_root_dir("user/dashboard/my_Product") . '"; }, 1000); </script>';
            }
        } catch (PDOException $error) {
            $app->buttonController('#smtBtn', 'enable');
            print '<script type="text/javascript"> $(\'#smtBtn\').html(\'Submit Product <i class="fas fa-sign-in-alt"></i>\');</script>';
            die($error->getMessage());
        }
    } else if ($mode == "processProduct") {
        $titles = explode(" ", $title);
        $join = implode("%' OR title LIKE '%", $titles);
        $querySQL = "SELECT * FROM pt_products WHERE title LIKE '%$join%'";
        $db_handle = $dbh->prepare($querySQL);
        $db_handle->execute();
        $count = 1;
        print "<script>$('.processor').css('display', 'block');</script>";
        if ($db_handle->rowCount() > 0) {
            while ($paramGetFields = $db_handle->fetch(PDO::FETCH_OBJ)) {
                print "<p>#$count. $paramGetFields->title</p>";
                $count++;
            }
        } else {
            print '<p>No match results</p>';
        }
    } else if ($mode == "addproductForm") {
        $title = ucwords($title);
        $desc = ucwords($desc);
        if (empty($title) || empty($desc) || empty($price) || empty($rating)) {
            $app->sweetAlert('warning', 'Fields cannot be Empty!');
            $app->buttonController('#smtBtn', 'enable');
            print '<script type="text/javascript"> $(\'#smtBtn\').html(\'Add Product <i class="fas fa-sign-in-alt"></i>\');</script>';
            die;
        }

        if (!is_numeric($price)) {
            $app->sweetAlert('warning', 'Wrong value for price field!');
            $app->buttonController('#smtBtn', 'enable');
            print '<script type="text/javascript"> $(\'#smtBtn\').html(\'Add Product <i class="fas fa-sign-in-alt"></i>\');</script>';
            die;
        }

        $errors = 0;
        $exists = 0;
        $success = 0;
        for ($i = 0; $i < count($_FILES); $i++) {
            # code...
            $pos = 'file_' . $i;
            $final_name = str_replace(" ", "", strtolower(trim($_FILES[$pos]['name'])));
            $final_name = "file_pt_" . time() . "_" . $final_name;

            $location = "../assets/files/$final_name";
            $loc = $app->server_root_dir("assets/files/$final_name");
            $explode = explode(".", $_FILES[$pos]['name']);
            $type = end($explode);

            if (!file_exists($location)) {
                $fileData = file_get_contents($_FILES[$pos]['tmp_name']);
                if (file_put_contents($location, $fileData)) {
                    unlink($_FILES[$pos]['tmp_name']);
                    try {
                        $db_handle = $dbh->prepare("INSERT INTO pt_products SET title=:title, description=:description, price=:price, rating=:rating, img_name=:img_name, date=NOW()");
                        if ($db_handle->execute(array(':title' => $title, ':description' => $desc, ':price' => $price, ':rating' => $rating, ':img_name' => $final_name))) {
                            $app->sweetAlert('success', 'Product added successfully!');
                            $app->buttonController('#smtBtn', 'enable');
                            print '<script type="text/javascript">$(\'#addproductForm\').trigger("reset"); $(\'#smtBtn\').html(\'Add Product <i class="fa sign-in-alt"></i>\');</script>';
                            // print "<script>$('.processor').css('display', 'none');</script>";
                        }
                    } catch (PDOException $error) {
                        $app->buttonController('#smtBtn', 'enable');
                        print '<script type="text/javascript"> $(\'#smtBtn\').html(\'Add Product <i class="fas fa-sign-in-alt"></i>\');</script>';
                        die($error->getMessage());
                    }
                } else {
                    // print_r($_FILES[$pos]['tmp_name']);
                    $errors += 1;
                }
            } else {
                $exists += 1;
            }
        }

        if ($errors > 0 || $exists > 0) {
            $app->sweetAlert('warning', 'Server error, try again later!');
            $app->buttonController('#smtBtn', 'enable');
            print '<script type="text/javascript">$(\'#createListingForm\').trigger("reset"); $(\'#smtBtn\').html(\'Create Listing <i class="fa fa-sign-in ml-1"></i>\');</script>';
        }
    } else if ($mode == "updateProductForm") {
        $title = ucwords($title);
        $desc = ucwords($desc);
        if (empty($title) || empty($desc) || empty($price) || empty($rating)) {
            $app->sweetAlert('warning', 'Fields cannot be Empty!');
            $app->buttonController('#smtBtn', 'enable');
            print '<script type="text/javascript"> $(\'#smtBtn\').html(\'Update Product <i class="fas fa-sign-in-alt"></i>\');</script>';
            die;
        }

        if (!is_numeric($price)) {
            $app->sweetAlert('warning', 'Wrong value for price field!');
            $app->buttonController('#smtBtn', 'enable');
            print '<script type="text/javascript"> $(\'#smtBtn\').html(\'Update Product <i class="fas fa-sign-in-alt"></i>\');</script>';
            die;
        }

        $errors = 0;
        $exists = 0;
        $success = 0;
        if (!isset($files)) {
            for ($i = 0; $i < count($_FILES); $i++) {
                # code...
                $pos = 'file_' . $i;
                $final_name = str_replace(" ", "", strtolower(trim($_FILES[$pos]['name'])));
                $final_name = "file_pt_" . time() . "_" . $final_name;

                $location = "../assets/files/$final_name";
                $loc = $app->server_root_dir("assets/files/$final_name");
                $explode = explode(".", $_FILES[$pos]['name']);
                $type = end($explode);

                if (!file_exists($location)) {
                    $fileData = file_get_contents($_FILES[$pos]['tmp_name']);
                    if (file_put_contents($location, $fileData)) {
                        $old_img_name = $app->getValue("img_name", "pt_products", "id", $btnId);
                        unlink($_FILES[$pos]['tmp_name']);
                        try {
                            $db_handle = $dbh->prepare("UPDATE pt_products SET title=:title, description=:description, price=:price, rating=:rating, img_name=:img_name WHERE id='$btnId'");
                            if ($db_handle->execute(array(':title' => $title, ':description' => $desc, ':price' => $price, ':rating' => $rating, ':img_name' => $final_name))) {
                                unlink("../assets/files/".$old_img_name);
                                $app->sweetAlert('success', 'Product updated successfully!');
                                $app->buttonController('#smtBtn', 'enable');
                                print '<script type="text/javascript">$(\'#file\').val(""); $(\'#smtBtn\').html(\'Update Product <i class="fa sign-in-alt"></i>\');</script>';
                                // print "<script>$('.processor').css('display', 'none');</script>";
                            }
                        } catch (PDOException $error) {
                            $app->buttonController('#smtBtn', 'enable');
                            print '<script type="text/javascript"> $(\'#smtBtn\').html(\'Update Product <i class="fas fa-sign-in-alt"></i>\');</script>';
                            die($error->getMessage());
                        }
                    } else {
                        // print_r($_FILES[$pos]['tmp_name']);
                        $errors += 1;
                    }
                } else {
                    $exists += 1;
                }
            }
        }else{
            try {
                $db_handle = $dbh->prepare("UPDATE pt_products SET title=:title, description=:description, price=:price, rating=:rating");
                if ($db_handle->execute(array(':title' => $title, ':description' => $desc, ':price' => $price, ':rating' => $rating))) {
                    $app->sweetAlert('success', 'Product updated successfully!');
                    $app->buttonController('#smtBtn', 'enable');
                    print '<script type="text/javascript">$(\'#smtBtn\').html(\'Update Product <i class="fa sign-in-alt"></i>\');</script>';
                    // print "<script>$('.processor').css('display', 'none');</script>";
                }
            } catch (PDOException $error) {
                $app->buttonController('#smtBtn', 'enable');
                print '<script type="text/javascript"> $(\'#smtBtn\').html(\'Update Product <i class="fas fa-sign-in-alt"></i>\');</script>';
                die($error->getMessage());
            }
        }

        if ($errors > 0 || $exists > 0) {
            $app->sweetAlert('warning', 'Server error, try again later!');
            $app->buttonController('#smtBtn', 'enable');
            print '<script type="text/javascript">$(\'#createListingForm\').trigger("reset"); $(\'#smtBtn\').html(\'Create Listing <i class="fa fa-sign-in ml-1"></i>\');</script>';
        }
    }
}
