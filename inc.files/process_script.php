<?php
// Prevent direct access to this class
// define("BASEPATH", 1);

require('../classes/PdoDB.php');
require('../classes/App.php');
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\SMTP;
// use PHPMailer\PHPMailer\Exception;

// require '../vendors/PHPMailer/src/Exception.php';
// require '../vendors/PHPMailer/src/PHPMailer.php';
// require '../vendors/PHPMailer/src/SMTP.php';

// Encryption files
require '../classes/Aes.php';     // AES PHP implementation
require '../classes/AesCtr.php';  // AES Counter Mode implementation

// include('../vendors/flutterwave/library/rave.php');
// include('../vendors/flutterwave/library/raveEventHandlerInterface.php');

// use Flutterwave\Rave;
// use Flutterwave\EventHandlerInterface;

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
    } else if ($mode == "activateSlider") {
        if (!empty($btnId) && is_numeric($btnId) && strrev($byepass) == "shadowalker") {
            $db_handle = $dbh->prepare("UPDATE pt_sliders SET status=1 WHERE id='$btnId'");
            if ($db_handle->execute()) {
                print "<script type=\"text/javascript\">
                        //$(':button').removeAttr('disabled');
                    </script>";
                $app->toaster('success', 'Slider Activated Successfully!');
                print "<script type=\"text/javascript\">
                            $('#user$btnId').addClass('deactivateSlider');
                            $('#user$btnId').removeClass('activateSlider');
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
    } else if ($mode == "deactivateSlider") {
        if (!empty($btnId) && is_numeric($btnId) && strrev($byepass) == "shadowalker") {
            $db_handle = $dbh->prepare("UPDATE pt_sliders SET status=0 WHERE id='$btnId'");
            if ($db_handle->execute()) {
                print "<script type=\"text/javascript\">
                        //$(':button').removeAttr('disabled');
                    </script>";
                $app->toaster('success', 'Slider Dectivated Successfully!');
                print "<script type=\"text/javascript\">
                            $('#user$btnId').addClass('activateSlider');
                            $('#user$btnId').removeClass('deactivateSlider');
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
    } else if ($mode == "activateBtn") {
        if (!empty($btnId) && !empty($btnType) && is_numeric($btnId) && strrev($byepass) == "shadowalker") {
            $db_handle = $dbh->prepare("UPDATE pt_$btnType SET status=1 WHERE id='$btnId'");
            if ($db_handle->execute()) {
                print "<script type=\"text/javascript\">
                        //$(':button').removeAttr('disabled');
                    </script>";
                $app->toaster('success', 'Activated Successfully!');
                print "<script type=\"text/javascript\">
                            $('#user$btnId').addClass('deactivateBtn');
                            $('#user$btnId').removeClass('activateBtn');
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
    } else if ($mode == "deactivateBtn") {
        if (!empty($btnId) && !empty($btnType) && is_numeric($btnId) && strrev($byepass) == "shadowalker") {
            $db_handle = $dbh->prepare("UPDATE pt_$btnType SET status=0 WHERE id='$btnId'");
            if ($db_handle->execute()) {
                print "<script type=\"text/javascript\">
                        //$(':button').removeAttr('disabled');
                    </script>";
                $app->toaster('success', 'Dectivated Successfully!');
                print "<script type=\"text/javascript\">
                            $('#user$btnId').addClass('activateBtn');
                            $('#user$btnId').removeClass('deactivateBtn');
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
    } else if ($mode == "deleteBtn") {
        $old_img_name = $app->getValue("img_name", "pt_$btnType", "id", $btnId);
        // print('../assets/files/'.$old_img_name);die;
        if (!empty($btnId) && !empty($btnType) && is_numeric($btnId) && strrev($byepass) == "shadowalker") {
            $db_handle = $dbh->prepare("DELETE FROM pt_$btnType WHERE id='$btnId'");
            if(unlink('../assets/files/'.$old_img_name)){
                $old_img_name = $app->getValue("img_name", "pt_$btnType", "id", $btnId);
                if ($db_handle->execute()) {
                    $app->toaster('success', 'Deleted Successfully!');
                    print '<script type="text/javascript"> setTimeout(() => { self.location = "' . $app->server_root_dir("admin/dashboard/manage_categories") . '" ; }, 3000); </script>';
                }
                // print "<script type=\"text/javascript\">
                //         //$(':button').removeAttr('disabled');
                //     </script>";
                // $app->toaster('success', 'Dectivated Successfully!');
                // print "<script type=\"text/javascript\">
                //             $('#user$btnId').addClass('activateBtn');
                //             $('#user$btnId').removeClass('deactivateBtn');
                //             $('#user$btnId').html('<i class=\"fas fa-key\"></i> Activate');
                //             $('#user$btnId').addClass('btn-success');
                //             $('#user$btnId').removeClass('btn-warning');
                //             $('#userSpan$btnId').addClass('btn-warning');
                //             $('#userSpan$btnId').removeClass('btn-success');
                //             $('#userSpan$btnId').html('Inactive');
                //         </script>";
                exit();
            } else {
                // print "<script type=\"text/javascript\">
                //         $('#user$btnId').html('<i class=\"fas fa-key\"></i> Deactivate');
                //     </script>";
                $app->sweetAlert('warning', 'Unable to deactivate, try again later!');
                exit();
            }
        } else {
            $app->sweetAlert('warning', 'Something went wrong, try again later!');
            // print "<script type=\"text/javascript\">
            //         $('#user$btnId').html('<i class=\"fas fa-key\"></i> Deactivate');
            //     </script>";
            exit();
        }
    } else if ($mode == "activateOrder") {
        if (!empty($btnId) && is_numeric($btnId) && strrev($byepass) == "shadowalker") {
            $db_handle = $dbh->prepare("UPDATE pt_transactions SET delivered=1 WHERE id='$btnId'");
            if ($db_handle->execute()) {
                print "<script type=\"text/javascript\">
                        //$(':button').removeAttr('disabled');
                    </script>";
                $app->toaster('success', 'Order Delivered Successfully!');
                print "<script type=\"text/javascript\">
                            $('#user$btnId').addClass('deactivateOrder');
                            $('#user$btnId').removeClass('activateOrder');
                            $('#user$btnId').html('<i class=\"fas fa-key\"></i> Not Delivered');
                            $('#user$btnId').addClass('btn-warning');
                            $('#user$btnId').removeClass('btn-success');
                            $('#userSpan$btnId').addClass('btn-success');
                            $('#userSpan$btnId').removeClass('btn-warning');
                            $('#userSpan$btnId').html('Delivered');
                        </script>";
                exit();
            } else {
                print "<script type=\"text/javascript\">
                        $('#user$btnId').html('<i class=\"fas fa-key\"></i> Delivered');
                    </script>";
                $app->sweetAlert('warning', 'Unable to process, try again later!');
                exit();
            }
        } else {
            $app->sweetAlert('warning', 'Something went wrong, try again later!');
            print "<script type=\"text/javascript\">
                    $('#user$btnId').html('<i class=\"fas fa-key\"></i> Delivered');
                </script>";
            exit();
        }
    } else if ($mode == "deactivateOrder") {
        if (!empty($btnId) && is_numeric($btnId) && strrev($byepass) == "shadowalker") {
            $db_handle = $dbh->prepare("UPDATE pt_transactions SET delivered=0 WHERE id='$btnId'");
            if ($db_handle->execute()) {
                print "<script type=\"text/javascript\">
                        //$(':button').removeAttr('disabled');
                    </script>";
                $app->toaster('success', 'Order Not Delivered!');
                print "<script type=\"text/javascript\">
                            $('#user$btnId').addClass('activateOrder');
                            $('#user$btnId').removeClass('deactivateOrder');
                            $('#user$btnId').html('<i class=\"fas fa-key\"></i> Delivered');
                            $('#user$btnId').addClass('btn-success');
                            $('#user$btnId').removeClass('btn-warning');
                            $('#userSpan$btnId').addClass('btn-warning');
                            $('#userSpan$btnId').removeClass('btn-success');
                            $('#userSpan$btnId').html('Not Delivered');
                        </script>";
                exit();
            } else {
                print "<script type=\"text/javascript\">
                        $('#user$btnId').html('<i class=\"fas fa-key\"></i> Not Delivered');
                    </script>";
                $app->sweetAlert('warning', 'Unable to process, try again later!');
                exit();
            }
        } else {
            $app->sweetAlert('warning', 'Something went wrong, try again later!');
            print "<script type=\"text/javascript\">
                    $('#user$btnId').html('<i class=\"fas fa-key\"></i> Not Delivered');
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
    } else if ($mode == "addSliderForm") {
        $title = ucwords($title);
        $subtitle = ucwords($subtitle);
        if (empty($title) || empty($subtitle)) {
            $app->sweetAlert('warning', 'Fields cannot be Empty!');
            $app->buttonController('#smtBtn', 'enable');
            print '<script type="text/javascript"> $(\'#smtBtn\').html(\'Add Slider <i class="fas fa-sign-in-alt"></i>\');</script>';
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
                        $db_handle = $dbh->prepare("INSERT INTO pt_sliders SET title=:title, subtitle=:subtitle, img_name=:img_name, date=NOW()");
                        if ($db_handle->execute(array(':title' => $title, ':subtitle' => $subtitle, ':img_name' => $final_name))) {
                            $app->sweetAlert('success', 'Slider added successfully!');
                            $app->buttonController('#smtBtn', 'enable');
                            print '<script type="text/javascript">$(\'#addSliderForm\').trigger("reset"); $(\'#smtBtn\').html(\'Add Slider <i class="fa sign-in-alt"></i>\');</script>';
                            // print "<script>$('.processor').css('display', 'none');</script>";
                        }
                    } catch (PDOException $error) {
                        $app->buttonController('#smtBtn', 'enable');
                        print '<script type="text/javascript"> $(\'#smtBtn\').html(\'Add Slider <i class="fas fa-sign-in-alt"></i>\');</script>';
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
            print '<script type="text/javascript">$(\'#addSliderForm\').trigger("reset"); $(\'#smtBtn\').html(\'Add Slider <i class="fa fa-sign-in ml-1"></i>\');</script>';
        }
    } else if ($mode == "addCategoryForm") {
        // $category = ucwords($category);
        if (empty($category)) {
            $app->sweetAlert('warning', 'Fields cannot be Empty!');
            $app->buttonController('#smtBtn', 'enable');
            print '<script type="text/javascript"> $(\'#smtBtn\').html(\'Add <i class="fas fa-sign-in-alt"></i>\');</script>';
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
                        $db_handle = $dbh->prepare("INSERT INTO pt_categories SET category=:category, img_name=:img_name, date=NOW()");
                        if ($db_handle->execute(array(':category' => $category, ':img_name' => $final_name))) {
                            $success++;
                        }
                    } catch (PDOException $error) {
                        $app->buttonController('#smtBtn', 'enable');
                        print '<script type="text/javascript"> $(\'#smtBtn\').html(\'Add <i class="fas fa-sign-in-alt"></i>\');</script>';
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
            print '<script type="text/javascript">$(\'#addSliderForm\').trigger("reset"); $(\'#smtBtn\').html(\'Add Slider <i class="fa fa-sign-in ml-1"></i>\');</script>';
            exit;
        }

        if ($success == count($_FILES)) {
            $app->sweetAlert('success', 'Picture(s) added successfully!');
            $app->buttonController('#smtBtn', 'enable');
            print '<script type="text/javascript">$(\'#addCategoryForm\').trigger("reset"); $(\'#smtBtn\').html(\'Add <i class="fa sign-in-alt"></i>\');</script>';
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
                                unlink("../assets/files/" . $old_img_name);
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
        } else {
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
    } else if ($mode == "updateSliderForm") {
        $title = ucwords($title);
        $subtitle = ucwords($subtitle);
        if (empty($title) || empty($subtitle)) {
            $app->sweetAlert('warning', 'Fields cannot be Empty!');
            $app->buttonController('#smtBtn', 'enable');
            print '<script type="text/javascript"> $(\'#smtBtn\').html(\'Update Slider <i class="fas fa-sign-in-alt"></i>\');</script>';
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
                        $old_img_name = $app->getValue("img_name", "pt_sliders", "id", $btnId);
                        unlink($_FILES[$pos]['tmp_name']);
                        try {
                            $db_handle = $dbh->prepare("UPDATE pt_sliders SET title=:title, subtitle=:subtitle, img_name=:img_name WHERE id='$btnId'");
                            if ($db_handle->execute(array(':title' => $title, ':subtitle' => $subtitle, ':img_name' => $final_name))) {
                                unlink("../assets/files/" . $old_img_name);
                                $app->sweetAlert('success', 'Slider updated successfully!');
                                $app->buttonController('#smtBtn', 'enable');
                                print '<script type="text/javascript">$(\'#file\').val(""); $(\'#smtBtn\').html(\'Update Slider <i class="fa sign-in-alt"></i>\');</script>';
                                // print "<script>$('.processor').css('display', 'none');</script>";
                            }
                        } catch (PDOException $error) {
                            $app->buttonController('#smtBtn', 'enable');
                            print '<script type="text/javascript"> $(\'#smtBtn\').html(\'Update Slider <i class="fas fa-sign-in-alt"></i>\');</script>';
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
        } else {
            try {
                $db_handle = $dbh->prepare("UPDATE pt_sliders SET title=:title, subtitle=:subtitle");
                if ($db_handle->execute(array(':title' => $title, ':subtitle' => $subtitle))) {
                    $app->sweetAlert('success', 'Slider updated successfully!');
                    $app->buttonController('#smtBtn', 'enable');
                    print '<script type="text/javascript">$(\'#smtBtn\').html(\'Update Slider <i class="fa sign-in-alt"></i>\');</script>';
                    // print "<script>$('.processor').css('display', 'none');</script>";
                }
            } catch (PDOException $error) {
                $app->buttonController('#smtBtn', 'enable');
                print '<script type="text/javascript"> $(\'#smtBtn\').html(\'Update Slider <i class="fas fa-sign-in-alt"></i>\');</script>';
                die($error->getMessage());
            }
        }

        if ($errors > 0 || $exists > 0) {
            $app->sweetAlert('warning', 'Server error, try again later!');
            $app->buttonController('#smtBtn', 'enable');
            print '<script type="text/javascript">$(\'#createListingForm\').trigger("reset"); $(\'#smtBtn\').html(\'Create Listing <i class="fa fa-sign-in ml-1"></i>\');</script>';
        }
    } else if ($mode == "addToCart") {
        if (isset($_SESSION["cart"])) {
            $cart = $_SESSION["cart"];
            if (!in_array($btnId, $cart["btnId"])) {
                array_push($cart["btnId"], $btnId);
                array_push($cart["btnPrice"], $btnPrice);
                $_SESSION["cart"] = $cart;

                print '<script type="text/javascript"> 
                    $(\'.cartItems\').html(\'' . count($cart["btnId"]) . '\');
                    $(\'.cartSumPrice\').html(\'₦' . number_format(array_sum($cart["btnPrice"])) . '\');
                    $("#cartIds").val("' . str_replace('"', "'", json_encode($cart['btnId'])) . '");
                </script>';
                print "<script type='text/javascript'>toastr.success(\"Added to cart!\")</script>";
            } else {
                print "<script type='text/javascript'>toastr.success(\"Already in cart!\")</script>";
            }
        } else {
            $_SESSION["cart"] = ['btnId' => [$btnId], 'btnPrice' => [$btnPrice]];
            $cart = $_SESSION["cart"];
            print '<script type="text/javascript"> 
                $(\'.cartItems\').html(\'1\');
                $(\'.cartSumPrice\').html(\'₦' . number_format($btnPrice) . '\');
                $("#cartIds").val("' . str_replace('"', "'", json_encode($cart['btnId'])) . '");
            </script>';
            print "<script type='text/javascript'>toastr.success(\"Added to cart!\")</script>";
        }
    } else if ($mode == "addColorForm") {
        if (empty($ccolor) || empty($cname)) {
            $app->sweetAlert('warning', 'Fields cannot be Empty!');
            $app->buttonController('#smtBtnColor', 'enable');
            print '<script type="text/javascript"> $(\'#smtBtnColor\').html(\'Add <i class="fas fa-plus"></i>\');</script>';
            die;
        }
        $color = ['name' => ucfirst(strtolower($cname)), "color" => strtolower($ccolor)];
        $old_color = $app->getValue("color", "pt_products", "id", $btnId);
        if ($old_color == "") {
            $old_color = [];
        } else {
            $old_color = json_decode($old_color);
        }
        array_push($old_color, $color);
        $new_color = json_encode($old_color);
        $db_handle = $dbh->prepare("UPDATE pt_products SET color=:color WHERE id='$btnId'");
        if ($db_handle->execute(array(':color' => $new_color))) {
            $btnIdEnc = AesCtr::encrypt($btnId, 'aes256', 256);
            $app->sweetAlert('success', 'Color added successfully!');
            $app->buttonController('#smtBtnColor', 'enable');
            print '<script type="text/javascript">$(\'#addColorForm\').trigger("reset"); $(\'#smtBtnColor\').html(\'Add <i class="fas fa-plus"></i>\');</script>';
            print '<script type="text/javascript"> setTimeout(() => { self.location = "' . $app->server_root_dir("admin/dashboard/edit_product?btnId=$btnIdEnc") . '"; }, 1000); </script>';
        }
    } else if ($mode == "removeColorForm") {
        if (empty($colorId)) {
            $app->sweetAlert('warning', 'Fields cannot be Empty!');
            $app->buttonController('#smtBtnColorRemove', 'enable');
            print '<script type="text/javascript"> $(\'#smtBtnColorRemove\').html(\'Remove <i class="fas fa-minus"></i>\');</script>';
            die;
        }
        $colorId -= $colorId;

        $old_color = $app->getValue("color", "pt_products", "id", $btnId);
        if ($old_color != "") {
            $old_color = json_decode($old_color);
            unset($old_color[$colorId]);
            $new_color = json_encode(array_values($old_color));
            $db_handle = $dbh->prepare("UPDATE pt_products SET color=:color WHERE id='$btnId'");
            if ($db_handle->execute(array(':color' => $new_color))) {
                $btnIdEnc = AesCtr::encrypt($btnId, 'aes256', 256);
                $app->sweetAlert('success', 'Color removed successfully!');
                $app->buttonController('#smtBtnColorRemove', 'enable');
                print '<script type="text/javascript">$(\'#addColorForm\').trigger("reset"); $(\'#smtBtnColorRemove\').html(\'Remove <i class="fas fa-minus"></i>\');</script>';
                print '<script type="text/javascript"> setTimeout(() => { self.location = "' . $app->server_root_dir("admin/dashboard/edit_product?btnId=$btnIdEnc") . '"; }, 1000); </script>';
            }
        }
    } else if ($mode == "removeQty") {
        if (isset($_SESSION["cart"])) {
            $cart = $_SESSION["cart"];
            for ($i = 0; $i < count($cart['btnId']); $i++) {
                # code...
                if ($cart['btnId'][$i] == $btnId) {
                    unset($cart['btnId'][$i]);
                    unset($cart['btnPrice'][$i]);
                }
            }
            if (count($cart['btnId']) == 0) {
                unset($_SESSION["cart"]);
            } else {
                $cart['btnId'] = array_values($cart['btnId']);
                $cart['btnPrice'] = array_values($cart['btnPrice']);
                $_SESSION["cart"] = $cart;
            }
            print '<script type="text/javascript">
            $(\'.cartSumPrice\').html(\'₦' . number_format(array_sum($cart["btnPrice"])) . '\');
            setTimeout(() => { self.location = "' . $app->server_root_dir("store/cart") . '"; }, 1000); </script>';
        }
    } else if ($mode == "clearAll") {
        unset($_SESSION["cart"]);
        print '<script type="text/javascript">
        setTimeout(() => { self.location = "' . $app->server_root_dir("store/home") . '"; }, 1000); </script>';
    } else if ($mode == "checkoutForm") {
        // print_r($_POST);die;
        //Array ( 
        //     [qtyVal5] => 1 
        //     [qtyVal4] => 1 
        //     [colorId4] => 1 
        //     [name] => Akubue Augustus 
        //     [email] => akubueaugustuskc@gmail.com 
        //     [phone] => 08081301064 [state] => Enugu 
        //     [address] => NO 1, AMOKE LANE, NSUKKA, ENUGU STATE, NIGERIA 
        //     [totalCartSum] => 95000 
        // );
        // $_SESSION['last_post'] = $_POST;
        // if (isset($_SESSION["cart"])) {
        //     $cart = $_SESSION["cart"];
        //     $cartId = time();
        //     for ($i=0; $i < count($cart['btnId']); $i++) { 
        //         # code...
        //         // $cart['btnId'][$i]

        //     }
        // }

        $amount = $_POST['totalCartSum'];
        $name = $_POST['name'];

        //* Prepare our rave request
        $request = [
            'tx_ref' => time(),
            'amount' => $amount,
            'currency' => 'NGN',
            'payment_options' => 'card',
            'redirect_url' => 'http://localhost/store/inc.files/process',
            'customer' => [
                'email' => $email,
                'phonenumber' => $phone,
                'name' => $name
            ],
            'meta' => [
                'price' => $amount
            ],
            'customizations' => [
                'title' => "$app->app_title Product Checkout",
                'description' => 'secure payments for selected products'
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
                'Authorization: Bearer FLWSECK_TEST-aa24859371a164f13b3383779b951819-X',
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
            $app->sweetAlert('warning', 'We can not process your payment!');
            $app->buttonController('#smtBtn', 'enable');
            print '<script type="text/javascript">$(\'#smtBtn\').html(\'<span>Checkout</span>\');</script>';
            exit;
        }
        // $URL = (isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        // $getData = $_GET;
        // $postData = $_POST;
        // $publicKey = $_SERVER['PUBLIC_KEY'];
        // $secretKey = $_SERVER['SECRET_KEY'];
        // $success_url = $postData['successurl'];
        // $failure_url = $postData['failureurl'];
        // $env = $_SERVER['ENV'];

        // if ($postData['amount']) {
        //     $_SESSION['publicKey'] = $publicKey;
        //     $_SESSION['secretKey'] = $secretKey;
        //     $_SESSION['env'] = $env;
        //     $_SESSION['successurl'] = $success_url;
        //     $_SESSION['failureurl'] = $failure_url;
        //     $_SESSION['currency'] = $postData['currency'];
        //     $_SESSION['amount'] = $postData['amount'];
        // }

        // $prefix = 'RV'; // Change this to the name of your business or app
        // $overrideRef = false;

        // // Uncomment here to enforce the useage of your own ref else a ref will be generated for you automatically
        // if ($postData['ref']) {
        //     $prefix = $postData['ref'];
        //     $overrideRef = true;
        // }

        // $payment = new Rave($_SESSION['secretKey'], $prefix, $overrideRef);

        // function getURL($url, $data = array())
        // {
        //     $urlArr = explode('?', $url);
        //     $params = array_merge($_GET, $data);
        //     $new_query_string = http_build_query($params) . '&' . $urlArr[1];
        //     $newUrl = $urlArr[0] . '?' . $new_query_string;
        //     return $newUrl;
        // };
    } else if ($mode == "doneModal"){
        $arr = array("$name" => "$value");
        if(!isset($_SESSION["last_post"])){
            $_SESSION["last_post"] = array();
        }
        $_SESSION["last_post"] = array_merge($_SESSION["last_post"], $arr);
        // print_r($_SESSION["last_post"]);
        echo '<script> 
        cartIds = $("#cartIds").val();
        if(cartIds.includes("'.$btnId.'")){
            toastr.success("Already in cart!")
        }
        if(!cartIds.includes("'.$btnId.'")){
            $.post("'.$app->server_root_dir('inc.files/process_script?mode=addToCart').'", { btnId: "'.$btnId.'", btnPrice: "'.$btnPrice.'" }, function (data) {
                $("#formSpan").html(data);
            });
        }
        </script>
        ';
    }
}
