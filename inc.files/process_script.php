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
    if ($mode == "regForm") {
        if ($app->strIsEmpty($fname) or $app->strIsEmpty($reg_no) or $app->strIsEmpty($email) or $app->strIsEmpty($password) or $app->strIsEmpty($repass)) {
            $app->sweetAlert('warning', 'Fields cannot be Empty!');
            $app->buttonController('#smtBtn', 'enable');
            print '<script type="text/javascript">$(\'#smtBtn\').html(\'Sign Up <i class="fas fa-sign-in-alt"></i>\');</script>';
            die;
        }

        if ($password != $repass) {
            $app->sweetAlert('warning', 'Password do not match!');
            $app->buttonController('#smtBtn', 'enable');
            print '<script type="text/javascript">$(\'#smtBtn\').html(\'Sign Up <i class="fas fa-sign-in-alt"></i>\');</script>';
            die;
        }

        if ($app->valueExists("email", "pv_students", $email) === true) {
            $app->sweetAlert('warning', 'Student\'s Email already exists!');
            $app->buttonController('#smtBtn', 'enable');
            print '<script type="text/javascript">$(\'#smtBtn\').html(\'Sign Up <i class="fas fa-sign-in-alt"></i>\');</script>';
            die;
        }

        if ($app->valueExists("reg_no", "pv_students", $reg_no) === true) {
            $app->sweetAlert('warning', 'Student\'s Reg No already exists!');
            $app->buttonController('#smtBtn', 'enable');
            print '<script type="text/javascript">$(\'#smtBtn\').html(\'Sign Up <i class="fas fa-sign-in-alt"></i>\');</script>';
            die;
        }

        $split = explode("/", $reg_no);
        if (!(strlen($reg_no) == 11) || !(count(@$split) == 2) || !(strlen((int)@$split[0]) == 4) || !(strlen(@$split[1]) == 6)) {
            $app->sweetAlert('warning', 'Reg number is invalid!');
            $app->buttonController('#smtBtn', 'enable');
            print '<script type="text/javascript">$(\'#smtBtn\').html(\'Sign Up <i class="fas fa-sign-in-alt"></i>\');</script>';
            die;
        }

        try {
            $password_hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 11]);
            $db_handle = $dbh->prepare("INSERT INTO pv_students SET fname=:fname, reg_no=:reg_no, email=:email, password=:password, date=NOW()");
            if ($db_handle->execute(array(':fname' => $fname, ':reg_no' => $reg_no, ':email' => $email, ':password' => $password_hash))) {
                $user = (object)$app->getUserByEmail($email);
                $dynamicimage = $app->imageDynamic($user->picture, $app->server_root_dir('assets/images/'));
                $app->setCookie('user_hold_fullname', "$user->fname");
                $app->setCookie('user_hold_email', $user->email);
                $app->setCookie('user_hold_reg_no', $user->reg_no);
                $app->setCookie('user_hold_picture', $dynamicimage);
                $_SESSION['tappUserId'] = $user->id;
                $app->sweetAlert('success', 'Account created successfully!');
                $app->buttonController('#smtBtn', 'enable');
                print '<script type="text/javascript">$(\'#regForm\').trigger("reset"); $(\'#smtBtn\').html(\'Sign Up <i class="fas fa-sign-in-alt"></i>\');</script>';
                sleep(1);
                print '<script type="text/javascript"> self.location = "' . $app->server_root_dir('user/dashboard/home') . '" </script>';
            }
        } catch (PDOException $error) {
            $app->buttonController('#smtBtn', 'enable');
            print '<script type="text/javascript">$(\'#smtBtn\').html(\'Sign Up <i class="fas fa-sign-in-alt"></i>\');</script>';
            die($error->getMessage());
        }
    } else if ($mode == "contactForm") {
        if ($app->strIsEmpty($name) or $app->strIsEmpty($subject) or $app->strIsEmpty($message) or $app->strIsEmpty($email)) {
            $app->sweetAlert('warning', 'Fields cannot be Empty!');
            $app->buttonController('#smtBtn', 'enable');
            print '<script type="text/javascript">$(\'#smtBtn\').html(\'Send <i class="fas fa-sign-in-alt ml-1"></i>\');</script>';
            die;
        }

        try {
            $db_handle = $dbh->prepare("INSERT INTO pv_contact SET name=:name, email=:email, subject=:subject, message=:message, date=Now()");
            if ($db_handle->execute(array(':name' => $name, ':email' => $email, ':subject' => $subject, ':message' => $message))) {
                $app->sweetAlert('success', 'Message delivered successfully!');
                $app->buttonController('#smtBtn', 'enable');
                print '<script type="text/javascript">$(\'#contactForm\').trigger("reset"); $(\'#smtBtn\').html(\'Send <i class="fa sign-in"></i>\');</script>';
                exit;
            }
        } catch (PDOException $error) {
            $app->buttonController('#smtBtn', 'enable');
            print '<script type="text/javascript">$(\'#smtBtn\').html(\'Send <i class="fas fa-sign-in-alt ml-1"></i>\');</script>';
            die($error->getMessage());
        }
    } else if ($mode == "logForm") {
        $isAuthenticated = false;

        if ($app->strIsEmpty($email) or $app->strIsEmpty($password)) {
            $app->sweetAlert('warning', 'Fields cannot be Empty!');
            $app->buttonController('#smtBtn', 'enable');
            print '<script type="text/javascript">$(\'#smtBtn\').html(\'Sign In <i class="fas fa-sign-in-alt ml-1"></i>\');</script>';
            die;
        }

        $user = (array)$app->getUserByEmail($email);
        if (count($user) > 1) {
            if (password_verify($password, $user["password"])) {
                $isAuthenticated = true;
            }
        }

        if ($isAuthenticated) {
            if ($user["status"] != '1') {
                $app->sweetAlert('warning', 'This Account is not Active or has been Blocked.');
                $app->buttonController('#smtBtn', 'enable');
                print '<script type="text/javascript">$(\'#smtBtn\').html(\'Sign In <i class="fas fa-sign-in-alt ml-1"></i>\');</script>';
                die;
            } else {
                $user = (object) $user;
                $dynamicimage = $app->imageDynamic($user->picture, $app->server_root_dir('assets/images/'));
                $app->setCookie('user_hold_fullname', "$user->fname");
                $app->setCookie('user_hold_email', $user->email);
                $app->setCookie('user_hold_reg_no', $user->reg_no);
                $app->setCookie('user_hold_picture', $dynamicimage);
                $_SESSION['tappUserId'] = $user->id;
                sleep(1);
                print '<script type="text/javascript"> self.location = "' . $app->server_root_dir("user/dashboard/home") . '" </script>';
            }
        } else {
            //login attempt for the button
            $login_attempt = @$_SESSION['login_atempt'] + 1; //gettting the total login attempts
            $_SESSION['login_atempt'] = $login_attempt;

            if ($_SESSION['login_atempt'] >= 3) {
                $app->sweetAlert('warning', 'Check in Temporarily Suspended Reason for too many login attempts!');
                $app->buttonController('#smtBtn', 'disable');
                print '<script type="text/javascript">$(\'#smtBtn\').html(\'Sign In <i class="fas fa-sign-in-alt ml-1"></i>\');</script>';
                $_SESSION['login_atempt'] = 0;  //resetting the total login attempt
                die;
            } else {
                //display a normal login error message
                $app->sweetAlert('warning', 'Incorrect Login Details!');
                $app->buttonController('#smtBtn', 'enable');
                print '<script type="text/javascript">$(\'#smtBtn\').html(\'Sign In <i class="fas fa-sign-in-alt ml-1"></i>\');</script>';
                die;
            }
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
                $app->setCookie('admin_hold_reg_no', @$admin->reg_no);
                $app->setCookie('admin_hold_picture', $dynamicimage);
                $_SESSION['tappAdminId'] = $admin->id;
                sleep(1);
                print '<script type="text/javascript"> self.location = "' . $app->server_root_dir("admin/dashboard/home") . '" </script>';
            }
        } else {
            //login attempt for the button
            $login_attempt = @$_SESSION['login_atempt'] + 1; //gettting the total login attempts
            $_SESSION['login_atempt'] = $login_attempt;

            if ($_SESSION['login_atempt'] >= 3) {
                $app->sweetAlert('warning', 'Check in Temporarily Suspended Reason for too many login attempts!');
                $app->buttonController('#smtBtn', 'disable');
                print '<script type="text/javascript">$(\'#smtBtn\').html(\'Sign In <i class="fas fa-sign-in-alt ml-1"></i>\');</script>';
                $_SESSION['login_atempt'] = 0;  //resetting the total login attempt
                die;
            } else {
                //display a normal login error message
                $app->sweetAlert('warning', 'Incorrect Login Details!');
                $app->buttonController('#smtBtn', 'enable');
                print '<script type="text/javascript">$(\'#smtBtn\').html(\'Sign In <i class="fas fa-sign-in-alt ml-1"></i>\');</script>';
                die;
            }
        }
    } else if ($mode == "logOutUser") {
        unset($_SESSION['tappUserId']);
        $app->setCookie('user_hold_fullname', '');
        $app->setCookie('user_hold_email', '');
        $app->setCookie('user_hold_reg_no', '');
        $app->setCookie('user_hold_picture', '');
        print '<script> self.location = "' . $app->server_root_dir('sign_in') . '" </script>';
    } else if ($mode == "profilePhoto") {
        /* Getting file name */
        $filename = $_FILES['file']['name'];
        $final_name = "img_pv_" . $app->generateRandomString() . ".jpg";

        /* Location */
        // $location = $app->server_root_dir("pictures/$final_name");
        $location = "../assets/images/$final_name";
        //update to DB
        if ($app->updatePersonalPicture($final_name, "pv_students", $_SESSION['tappUserId'])) {
            if (move_uploaded_file($_FILES['file']['tmp_name'], $location)) {
                $location = $app->server_root_dir("assets/images/$final_name");
                $app->setCookie('user_hold_picture', $location);
                print "<script type='text/javascript'>$('.userdpsrc').attr('src','$location');$('.userdpbg').css('background-image','url($location)');</script>";
                $app->sweetAlert('success', 'Display Picture Updataed!');
            } else {
                $app->sweetAlert('warning', 'Unable to move display picture!');
            }
        } else {
            $app->sweetAlert('warning', 'Unable to update display picture, try again later!');
        }
    } else if ($mode == "profilePhotoA") {
        /* Getting file name */
        $filename = $_FILES['file']['name'];
        $final_name = "img_pv_" . $app->generateRandomString() . ".jpg";

        /* Location */
        // $location = $app->server_root_dir("pictures/$final_name");
        $location = "../assets/images/$final_name";
        //update to DB
        if ($app->updatePersonalPicture($final_name, "pv_admin", $_SESSION['tappAdminId'])) {
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
    } else if ($mode == "resetForm") {
        $user_email = $emiil = $email;

        if ($app->strIsEmpty($email)) {
            $app->sweetAlert('warning', 'Fields cannot be Empty!');
            $app->buttonController('#smtBtn', 'enable');
            print '<script type="text/javascript"> $(\'#smtBtn\').html(\'Reset Password  <i class="fas fa-lock ml-1"></i>\');</script>';
            die;
        }

        $db_handle = $dbh->prepare("SELECT * FROM pv_students WHERE email=:email");
        $db_handle->execute(array(':email' => $emiil));
        if ($db_handle->rowCount() == 1) {
            $paramGetFields = $db_handle->fetch(PDO::FETCH_OBJ);
            $fullname = $paramGetFields->fname;
            $new_pass = $app->generateRandomString(6);
            $password_hash = password_hash($new_pass, PASSWORD_BCRYPT, ['cost' => 11]);

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
                $mail->setFrom($app->mail_no_reply, $app->app_title);
                $mail->addAddress($email, $fullname);
                $mail->Subject = 'Account Recovery';
                $mail->Body    = $app->email($fullname, "A new password has been created for your student, the new password is $new_pass", $app->server_root_dir('sign_in'), "Go To Login");
                $mail->AltBody = "Dear $fullname, A new password has been created for your student, the new password is $new_pass";

                // Content
                $mail->isHTML(true);

                $mail->send();

                $db_handle = $dbh->prepare("UPDATE pv_students SET password=:password WHERE email=:email AND id='$paramGetFields->id'");
                if ($db_handle->execute(array(':email' => $email, ':password' => $password_hash))) {
                    $app->sweetAlert('success', 'Email sent to you with new password!');
                    $app->buttonController('#smtBtn', 'enable');
                    print '<script type="text/javascript">$(\'#resetForm\').trigger("reset"); $(\'#smtBtn\').html(\'Reset Password  <i class="fas fa-lock ml-1"></i>\');</script>';
                    die;
                }
            } catch (Exception $e) {
                print_r($mail->ErrorInfo);
                $app->sweetAlert('warning', 'Email could not be sent, try again later!');
                $app->buttonController('#smtBtn', 'enable');
                print '<script type="text/javascript"> $(\'#smtBtn\').html(\'Reset Password  <i class="fas fa-lock ml-1"></i>\');</script>';
                die;
            }
        } else {
            $app->sweetAlert('warning', 'Account not found!');
            $app->buttonController('#smtBtn', 'enable');
            print '<script type="text/javascript">$(\'#smtBtn\').html(\'Reset Password  <i class="fas fa-lock ml-1"></i>\');</script>';
            die;
        }
    } else if ($mode == "activateUser") {
        if (!empty($btnId) && is_numeric($btnId) && strrev($byepass) == "shadowalker") {
            $db_handle = $dbh->prepare("UPDATE pv_students SET status=1 WHERE id='$btnId'");
            if ($db_handle->execute()) {
                print "<script type=\"text/javascript\">
                        //$(':button').removeAttr('disabled');
                    </script>";
                $app->toaster('success', 'User Activated Successfully!');
                print "<script type=\"text/javascript\">
                            $('#user$btnId').addClass('deactivateUser');
                            $('#user$btnId').removeClass('activateUser');
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
                        // $(':button').removeAttr('disabled');
                        $('#user$btnId').html('<i class=\"fas fa-key\"></i> Activate');
                    </script>";
                $app->sweetAlert('warning', 'Unable to activate user, try again later!');
                exit();
            }
        } else {
            $app->sweetAlert('warning', 'Something went wrong, try again later!');
            print "<script type=\"text/javascript\">
                    // $(':button').removeAttr('disabled');
                    $('#user$btnId').html('<i class=\"fas fa-key\"></i> Activate');
                </script>";
            exit();
        }
    } else if ($mode == "deactivateUser") {
        if (!empty($btnId) && is_numeric($btnId) && strrev($byepass) == "shadowalker") {
            $db_handle = $dbh->prepare("UPDATE pv_students SET status=0 WHERE id='$btnId'");
            if ($db_handle->execute()) {
                print "<script type=\"text/javascript\">
                        //$(':button').removeAttr('disabled');
                    </script>";
                $app->toaster('success', 'User Dectivated Successfully!');
                print "<script type=\"text/javascript\">
                            $('#user$btnId').addClass('activateUser');
                            $('#user$btnId').removeClass('deactivateUser');
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
                        // $(':button').removeAttr('disabled');
                        $('#user$btnId').html('<i class=\"fas fa-key\"></i> Deactivate');
                    </script>";
                $app->sweetAlert('warning', 'Unable to deactivate user, try again later!');
                exit();
            }
        } else {
            $app->sweetAlert('warning', 'Something went wrong, try again later!');
            print "<script type=\"text/javascript\">
                    // $(':button').removeAttr('disabled');
                    $('#user$btnId').html('<i class=\"fas fa-key\"></i> Deactivate');
                </script>";
            exit();
        }
    } else if ($mode == "activateProject") {
        if (!empty($btnId) && is_numeric($btnId) && strrev($byepass) == "shadowalker") {
            $db_handle = $dbh->prepare("UPDATE pv_projects SET status=1 WHERE id='$btnId'");
            if ($db_handle->execute()) {
                print "<script type=\"text/javascript\">
                        //$(':button').removeAttr('disabled');
                    </script>";
                $app->toaster('success', 'Project Activated Successfully!');
                print "<script type=\"text/javascript\">
                            $('#user$btnId').addClass('deactivateProject');
                            $('#user$btnId').removeClass('activateProject');
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
    } else if ($mode == "deactivateProject") {
        if (!empty($btnId) && is_numeric($btnId) && strrev($byepass) == "shadowalker") {
            $db_handle = $dbh->prepare("UPDATE pv_projects SET status=0 WHERE id='$btnId'");
            if ($db_handle->execute()) {
                print "<script type=\"text/javascript\">
                        //$(':button').removeAttr('disabled');
                    </script>";
                $app->toaster('success', 'Project Dectivated Successfully!');
                print "<script type=\"text/javascript\">
                            $('#user$btnId').addClass('activateProject');
                            $('#user$btnId').removeClass('deactivateProject');
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
        $app->setCookie('admin_hold_reg_no', '');
        $app->setCookie('admin_hold_picture', '');
        print '<script> self.location = "' . $app->server_root_dir('sign_in') . '" </script>';
    } else if ($mode == "profileForm") {
        $qstr = " WHERE id='" . $_SESSION['tappUserId'] . "'";

        if ($app->strIsEmpty($fname)) {
            $app->sweetAlert('warning', 'Fields cannot be Empty!');
            $app->buttonController('#smtBtn', 'enable');
            print '<script type="text/javascript">$(\'#smtBtn\').html(\'Update Profile <i class="fas fa-sign-in-alt">\');</script>';
            exit;
        }

        if ($app->updateProfileUser($fname, $qstr) === true) {
            $app->setCookie('user_hold_fullname', "$fname");
            print "<script type='text/javascript'>$('.user_hold_fullname').html('$fname');</script>";
            $app->sweetAlert('success', 'Profile Updated successfully!');
            $app->buttonController('#smtBtn', 'enable');
            print '<script type="text/javascript">$("#password").val("");$("#repeatPassword").val("");$(\'#smtBtn\').html(\'Update Profile <i class="fas fa-sign-in-alt">\');</script>';
        } else {
            $app->sweetAlert('warning', 'Unable to update profile, try again latar!');
            $app->buttonController('#smtBtn', 'enable');
            print '<script type="text/javascript">$(\'#smtBtn\').html(\'Update Profile <i class="fas fa-sign-in-alt">\');</script>';
            exit;
        }
    } else if ($mode == "updatePassword") {

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

        $userPassword = $app->getValue("password", "pv_students", "id", $_SESSION['tappUserId']);
        if (!password_verify($old_password, $userPassword)) {
            $app->sweetAlert('warning', 'Incorrect User Password!');
            $app->buttonController('#smtBtnPass', 'enable');
            print '<script type="text/javascript">$(\'#smtBtnPass\').html(\'Update Password<i class="fas fa-sign ml-1"></i>\');</script>';
            exit;
        }
        $password_hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 11]);
        $userId = $_SESSION['tappUserId'];

        if ($app->updatePassword($password_hash, "pv_students", "WHERE id='$userId'") === true) {
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

        $userPassword = $app->getValue("password", "pv_admin", "id", $_SESSION['tappAdminId']);
        if (!password_verify($old_password, $userPassword)) {
            $app->sweetAlert('warning', 'Incorrect User Password!');
            $app->buttonController('#smtBtnPass', 'enable');
            print '<script type="text/javascript">$(\'#smtBtnPass\').html(\'Update Password<i class="fas fa-sign ml-1"></i>\');</script>';
            exit;
        }
        $password_hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 11]);
        $userId = $_SESSION['tappAdminId'];

        if ($app->updatePassword($password_hash, "pv_admin", "WHERE id='$userId'") === true) {
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
    } else if ($mode == "manageSystem") {
        if ($model == 'userLogin') {
            print ($app->manageSystemSettings($status, 'user_login')) ? "<script type='text/javascript'>toastr.success('Updated Successfully!')</script>" : "<script type='text/javascript'>toastr.error('Unable to Update!')</script>";
        } elseif ($model == 'userRegistration') {
            print ($app->manageSystemSettings($status, 'user_registration')) ? "<script type='text/javascript'>toastr.success('Updated Successfully!')</script>" : "<script type='text/javascript'>toastr.error('Unable to Update!')</script>";;
        } elseif ($model == 'checkSecurity') {
            print ($app->manageSystemSettings($status, 'check_security')) ? "<script type='text/javascript'>toastr.success('Updated Successfully!')</script>" : "<script type='text/javascript'>toastr.error('Unable to Update!')</script>";;
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
    } else if ($mode == "manageSystemForm") {
        $user_registration = $user_registration;
        print $user_login = $user_login;
        $term = $term;
        $exam_status = $exam_status;
        $exam_questions = $exam_questions;
        $exam_duration = $exam_duration;
        $test_status = $test_status;
        $test_questions = $test_questions;
        $test_duration = $test_duration;

        if ($user_registration == "" || $user_login == "" || empty($term) || $exam_status == "" || $exam_questions == "" || $exam_duration == "" || $test_status == "" || $test_questions == "" || $test_duration == "") {
            $app->sweetAlert('warning', 'Fields cannot be Empty!');
            $app->buttonController('#smtBtn', 'enable');
            print '<script type="text/javascript"> $(\'#smtBtn\').html(\'Submit <i class="fas fa-sign-in-alt"></i>\');</script>';
            die;
        }

        $db_handle = $dbh->prepare("UPDATE students SET term=:term");
        if ($db_handle->execute(array(':term' => $term))) {
            $db_handle_inner = $dbh->prepare("UPDATE vc_system SET user_login=:user_login, user_registration=:user_registration, term=:term, exam_duration=:exam_duration, test_duration=:test_duration, exam_questions=:exam_questions, test_questions=:test_questions, test_status=:test_status, exam_status=:exam_status");
            if ($db_handle_inner->execute(array(':user_login' => $user_login, ':user_registration' => $user_registration, ':term' => $term, ':exam_duration' => $exam_duration, ':test_duration' => $test_duration, ':exam_questions' => $exam_questions, ':test_questions' => $test_questions, ':test_status' => $test_status, ':exam_status' => $exam_status))) {
                $app->sweetAlert('success', 'Settings updated successfully!');
                $app->buttonController('#smtBtn', 'enable');
                print '<script type="text/javascript"> $(\'#smtBtn\').html(\'Submit <i class="fas fa-sign-in-alt"></i>\');</script>';
                die;
            }
        }
    } else if ($mode == "projectForm") {
        if(empty($title) || !is_numeric($title)){
            $app->sweetAlert('warning', 'Fields cannot be Empty!');
            $app->buttonController('#smtBtn', 'enable');
            print '<script type="text/javascript"> $(\'#smtBtn\').html(\'Submit Project <i class="fas fa-sign-in-alt"></i>\');</script>';
            die;
        }

        if($app->valueExists("id", "pv_projects", $title, "AND status=1")==false){
            $app->sweetAlert('warning', 'Project is not registered!');
            $app->buttonController('#smtBtn', 'enable');
            print '<script type="text/javascript"> $(\'#smtBtn\').html(\'Submit Project <i class="fas fa-sign-in-alt"></i>\');</script>';
            die;
        }

        if($app->valueExists("project_id", "pv_students", $title)==true){
            $app->sweetAlert('warning', 'Project already chosen by another student!');
            $app->buttonController('#smtBtn', 'enable');
            print '<script type="text/javascript"> $(\'#smtBtn\').html(\'Submit Project <i class="fas fa-sign-in-alt"></i>\');</script>';
            die;
        }

        try {
            $db_handle = $dbh->prepare("UPDATE pv_students SET project_id=:project_id WHERE id=:id");
            if ($db_handle->execute(array(':project_id' => $title, ':id' => $_SESSION['tappUserId']))) {
                $app->sweetAlert('success', 'Project Assigned to student successfully!');
                $app->buttonController('#smtBtn', 'enable');
                print '<script type="text/javascript">$(\'#projectForm\').trigger("reset"); $(\'#smtBtn\').html(\'Submit Project <i class="fa sign-in-alt"></i>\');</script>';
                print '<script type="text/javascript"> setTimeout(() => { self.location = "' . $app->server_root_dir("user/dashboard/my_project") . '"; }, 1000); </script>';
            }
        } catch (PDOException $error) {
            $app->buttonController('#smtBtn', 'enable');
            print '<script type="text/javascript"> $(\'#smtBtn\').html(\'Submit Project <i class="fas fa-sign-in-alt"></i>\');</script>';
            die($error->getMessage());
        }
    } else if ($mode == "processProject") {
        $titles = explode(" ", $title);
        $join = implode("%' OR title LIKE '%", $titles);
        $querySQL = "SELECT * FROM pv_projects WHERE title LIKE '%$join%'";
        $db_handle = $dbh->prepare($querySQL);
        $db_handle->execute();
        $count = 1;
        print "<script>$('.processor').css('display', 'block');</script>";
        if ($db_handle->rowCount() > 0) {
            while($paramGetFields = $db_handle->fetch(PDO::FETCH_OBJ)){
                print "<p>#$count. $paramGetFields->title</p>";
                $count++;
            }
        }else{
            print '<p>No match results</p>';
        }
    } else if ($mode == "addprojectForm") {
        $title = ucwords($title);
        $desc = ucwords($desc);
        if(empty($title) || empty($desc)){
            $app->sweetAlert('warning', 'Fields cannot be Empty!');
            $app->buttonController('#smtBtn', 'enable');
            print '<script type="text/javascript"> $(\'#smtBtn\').html(\'Add Project <i class="fas fa-sign-in-alt"></i>\');</script>';
            die;
        }

        try {
            $db_handle = $dbh->prepare("INSERT INTO pv_projects SET title=:title, description=:description, date=NOW()");
            if ($db_handle->execute(array(':title' => $title, ':description' => $desc))) {
                $app->sweetAlert('success', 'Project added successfully!');
                $app->buttonController('#smtBtn', 'enable');
                print '<script type="text/javascript">$(\'#addprojectForm\').trigger("reset"); $(\'#smtBtn\').html(\'Add Project <i class="fa sign-in-alt"></i>\');</script>';
                print "<script>$('.processor').css('display', 'none');</script>";
            }
        } catch (PDOException $error) {
            $app->buttonController('#smtBtn', 'enable');
            print '<script type="text/javascript"> $(\'#smtBtn\').html(\'Add Project <i class="fas fa-sign-in-alt"></i>\');</script>';
            die($error->getMessage());
        }

    }
}
