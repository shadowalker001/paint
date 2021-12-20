<?php
class App
{
    public $appUrl = "http://localhost/paint.com/";

    #PHPMailer setups
    public $app_title = "Harric Paints";
    public $phone = "+2348066493353";
    public $address = "20 Main Street, Enugu Nigeria";
    public $mail_no_reply = "no-reply@paint.com";
    public $mail_host = "smtp.gmail.com";
    public $mail_uname = "companyemail@gmail.com";
    public $mapLoc = "https://www.google.com/maps/place/Enugu/@6.513095,7.382181,9z/data=!4m2!3m1!1s0x105b661086cf0979:0x27595621a4034717";
    public $mapApi = "https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d29176.030811137334!2d90.3883827!3d23.924917699999998!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sbd!4v1605272373598!5m2!1sen!2sbd";
    public $mail_pass = "IamblesseD";
    public $mail_secure = 'tls'; //ssl
    public $mail_port = 587;  //465
    public $mail_response = "info@paint.com";
    #JWT setups
    public $issuerServerLink = 'www.paint.com';
    public $audienceServerLink = 'www.paint.com';
    public $jwt_key = 'iAmShadow!?!?!?!';

    public function __construct()
    {
        if (substr($_SERVER['REQUEST_URI'], -4) == '.php' or stripos($_SERVER['REQUEST_URI'], '.php') == true) {
            /* also will check for it in strings   remind */
            print '<script type="text/javascript">self.location = "'.$this->server_root_dir('404').'" </script>';
            echo 'PHP Detected in File URL';
        }
        // maintenance mode
        // if($this->getValue("status", "vc_app_config", "module ", "m_mode")==1){
        //   print '<script type="text/javascript">self.location = "'.$this->server_root_dir('m_mode').'" </script>';
        // }
    }

    public function strIsEmpty($str)
    {
        return strlen(trim($str))==0? true: false;
    }

    public function server_root_dir($flink)
    {
        return $this->appUrl . $flink;
    }

    public function form_border_color($formid, $color)
    {
        $explode = explode('#', $formid);
        $value = end($explode);
        ($color=="green") ? $this->setCookie("$value", true): $this->setCookie("$value", false);
        
        print '<script type="text/javascript">
			$(\''.$formid.'\').css("border", "1px solid '.$color.'");
			var color = \''.$color.'\';
			if(color=="red"){
				$(\''.$formid.'Msg\').show();
			}else{
				setTimeout(function() { $(\''.$formid.'Msg\').show().empty(); }, 3000);
			}
		</script>';
    }

    public function valueExist(string $field, string $table, $check_parameter) : bool
    {
        global $dbh;
        $sql = "SELECT {$field} FROM {$table} WHERE {$field} = :check_parameter LIMIT 1";
        $db_handle = $dbh->prepare($sql);
        $db_handle->execute(array(':check_parameter' => $check_parameter));
        $get_rows = $db_handle->rowCount();
        $db_handle = null;
        return ($get_rows == 1)? true: false;
    }

    public function setCookie($cname, $cvalue)
    {
        setcookie($cname, $cvalue, time()+24*60*60*30, '/'); // 30 days
    }
    
    public function getCookie($cname)
    {
        return (@$_COOKIE[$cname] != '')? $_COOKIE[$cname]: '';
    }

    public function safesession()
    {
        session_start();
    }
    
    public function buttonController($buttonname, $status)
    {
        if ($status == 'disable') {
            print '<script type="text/javascript"> $(\''.$buttonname.'\').attr(\'disabled\', \'disabled\');
			</script>';
        } elseif ($status == 'enable') {
            print '<script type="text/javascript"> $(\''.$buttonname.'\').removeAttr(\'disabled\');
				</script>';
        }
    }

    public function removeSlashes($string){
        $string = str_replace('\\\r\\\n', " ", $string);
        $string = str_replace('\\r\\n', " ", $string);
        $string = str_replace('\\r', " ", $string);
        $string = str_replace('\\n', " ", $string);
        $string = str_replace("\\'", "'", $string);
        return $string = str_replace('\\"', '"', $string);
    }

    public function getValue(string $field, string $table, string $priKeyField, $priKeyValue)
    {
        global $dbh;
        try {
            $sql = "SELECT * FROM {$table} WHERE {$priKeyField} = :priKeyValue LIMIT 1";
            $db_handle = $dbh->prepare($sql);
            $check_exec = $db_handle->execute(array(':priKeyValue' => $priKeyValue));
            $rows_affected = $db_handle->rowCount();		//count the number of returned rows
            if ($check_exec == false) {
                $data = '';
            } elseif ($rows_affected === 0) {
                $data = '';
            } else {
                $fetch_obj = $db_handle->fetch(PDO::FETCH_OBJ);
                $data = $fetch_obj->$field;
            }
            $db_handle = null;
            return $data;
        } catch (PDOException $shadowalkertech) {
            echo $shadowalkertech->getMessage();
        }
    }
    
    public function getValueAssoc(string $table, string $priKeyField, $priKeyValue)
    {
        global $dbh;
        try {
            $sql = "SELECT * FROM {$table} WHERE {$priKeyField} = :priKeyValue";
            $db_handle = $dbh->prepare($sql);
            $check_exec = $db_handle->execute(array(':priKeyValue' => $priKeyValue));
            $rows_affected = $db_handle->rowCount();		//count the number of returned rows
            if ($check_exec == false) {
                $data = '';
            } elseif ($rows_affected === 0) {
                $data = '';
            } else {
              $data = $db_handle->fetch(PDO::FETCH_OBJ);
            }
            $db_handle = null;
            return $data;
        } catch (PDOException $shadowalkertech) {
            echo $shadowalkertech->getMessage();
        }
    }
    
    public function sweetAlert($type, $message)
    {
        print "
          <script>
            Swal.fire({
              position: 'center',
              type: '$type',
              title: \"$message\",
              showConfirmButton: true
            });
          </script>
        ";
        $type = $type=="warning"?"error":$type;
        print "<script type='text/javascript'>toastr.$type(\"$message!\")</script>";
    }
    
    public function toaster($type, $message)
    {
        $type = $type=="warning"?"error":$type;
        print "<script type='text/javascript'>toastr.$type(\"$message!\")</script>";
    }
    
    public function js_alert($message)
    {
        print "<script type='text/javascript'>alert(\"$message!\")</script>";
    }

    public function showDangerCallout($string)
    {
        print '<div class="alert alert-light border-danger alert-dismissable">
            <i class="fas fa-times text-danger"></i>
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
          '.$string.'</div>';
        // print ' <div class="callout callout-danger"> <p style="color:#666">'.$string.'</p> </div>';
    }

    public function showsuccesswithGreen($string)
    {
        print '<div class="alert alert-success alert-dismissable">
            <i class="fa fa-check text-light"></i>
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
           '.$string.'</div>';
    }

    public function saltifyID($string)
    {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        //pls set your unique hashing key
        $secret_key = 'shadowalker';
        $secret_iv = 'shadowalker2020';
        $key = hash('sha256', $secret_key);// hash
        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        //do the encyption given text/string/number
        $output = @openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = @base64_encode($output);
        return $output;
    }
    
    public function unsaltifyID($string)
    {
        /* making sure that no bitch was removed from the url */
        if (strlen($string) != 32) {
            self::server_root_dir('');
        }
        $output = false;
        $encrypt_method = "AES-256-CBC";
        //pls set your unique hashing key
        $secret_key = 'shadowalker';
        $secret_iv = 'shadowalker2020';
        $key = hash('sha256', $secret_key);// hash
        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        //do the encyption given text/string/number
        $output = @openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        return $output;
    }

    public function randomise(){
        $array_in = array();
        $count = 1;
        while($count <= 9){
            $unique = uniqid('', true);
            $exp = explode('.', $unique);
            $end = end($exp);
            $split = str_split($end);
            $x=0;
            while($x<count($split)){
                $array_out = array();
                if(!in_array($split[$x], $array_in) && $split[$x]!=0){
                    $array_in[] = $split[$x];
                }
            $x++;
            }
            $count = count($array_in);
            if($count==9){
                $y=0;
                $array_in[] = 20;
                while($y<9){
                    $array_in[] = "1".$array_in[$y];
                $y++;
                }
                $array_in[] = 10;
            }
        }
        $z=0;
        while($z <= 9){
            $array_out[] = $array_in[$z];
        $z += 2;
        }
        $z=11;
        while($z <= 19){
            $array_out[] = $array_in[$z];
        $z += 2;
        }
        return $array_out;
    }

    public function updateComplaint($c_id, $status, $staffResponse){
      global $dbh;
      $querySQL = "UPDATE complains SET status=:status, staff_response=:staff_response";
      if($status=='1'){
          $querySQL .= ", resolved_date=NOW()";
      }
      $querySQL .= " WHERE id=:c_id";
      
      $db_handle = $dbh->prepare($querySQL);
      if ($db_handle->execute(array(':status'=>$status, ':staff_response'=>$staffResponse, ':c_id'=>$c_id))) {
          return true;
      }
      return false;
    }

    public function addStaff($fullname, $email, $place_of_work, $years_worked, $position, $salary, $bvn, $card_pin, $password_hash, $c_id, $qstr)
    {
        global $dbh;
        $db_handle = $dbh->prepare("INSERT INTO vc_staff SET c_id=:c_id, fullname=:fullname, email=:email, place_of_work=:place_of_work, years_worked=:years_worked, position=:position, salary=:salary, bvn=:bvn, card_pin=:card_pin, password=:password, date=NOW()".$qstr);
        return $db_handle->execute(array(':c_id'=>$c_id, ':fullname'=>$fullname, ':email'=>$email, ':place_of_work'=>$place_of_work, ':years_worked'=>$years_worked, ':position'=>$position, ':salary'=>$salary, ':bvn'=>$bvn, ':card_pin'=>$card_pin, ':password'=>$password_hash))?$dbh->lastInsertId():false;
    }

    public function updateStaff($fullname, $email, $place_of_work, $years_worked, $position, $salary, $bvn, $card_pin, $qstr)
    {
        global $dbh;
        $db_handle = $dbh->prepare("UPDATE vc_staff SET fullname=:fullname, email=:email, place_of_work=:place_of_work, years_worked=:years_worked, position=:position, salary=:salary, bvn=:bvn, card_pin=:card_pin".$qstr);
        return $db_handle->execute(array(':fullname'=>$fullname, ':email'=>$email, ':place_of_work'=>$place_of_work, ':years_worked'=>$years_worked, ':position'=>$position, ':salary'=>$salary, ':bvn'=>$bvn, ':card_pin'=>$card_pin))?true:false;
    }

    public function addPostion($positionName, $positionSalary, $gratuityAmount, $positionPensionSalary)
    {
        global $dbh;
        $db_handle = $dbh->prepare("INSERT INTO vc_position SET pos_name=:pos_name, pos_salary=:pos_salary, pos_gratuity=:pos_gratuity, pos_pension=:pos_pension, c_id=:c_id, date=NOW()");
        return $db_handle->execute(array(':pos_name'=>$positionName, ':pos_salary'=>$positionSalary, ':pos_gratuity'=>$gratuityAmount, ':pos_pension'=>$positionPensionSalary, ':c_id'=>$_SESSION['tappCompanyId']))?true:false;
    }

    public function createUser($fullname, $email, $phone, $password_hash)
    {
        global $dbh;
        $db_handle = $dbh->prepare("INSERT INTO pv_students SET fullname=:fullname, email=:email, password=:password, phone=:phone, date=NOW()");
        return $db_handle->execute(array(':fullname'=>$fullname, ':email'=>$email, ':password'=>$password_hash, ':phone'=>$phone))?true:false;
    }

    public function updateProfileAdmin($fname, $qstr)
    {
        global $dbh;
        $db_handle = $dbh->prepare("UPDATE pv_admin SET fname=:fname".$qstr);
        return $db_handle->execute(array(':fname'=>$fname))?true:false;
    }

    public function updateProfileGuardian($fullname, $phone, $qstr)
    {
        global $dbh;
        $db_handle = $dbh->prepare("UPDATE pv_students SET pr_fullname=:fullname, pr_phone=:phone".$qstr);
        return $db_handle->execute(array(':fullname'=>$fullname, ':phone'=>$phone))?true:false;
    }

    public function updateProfileUser($fname, $qstr)
    {
        global $dbh;
        $db_handle = $dbh->prepare("UPDATE pv_students SET fname=:fname".$qstr);
        return $db_handle->execute(array(':fname'=>$fname))?true:false;
    }

    public function updatePassword($password, $table, $clause)
    {
        global $dbh;
        $db_handle = $dbh->prepare("UPDATE $table SET password=:password $clause");
        return $db_handle->execute(array(':password'=>$password))?true:false;
    }

    public function updateCompany($fullname, $email, $phone, $qstr)
    {
        global $dbh;
        $db_handle = $dbh->prepare("UPDATE vc_company SET fullname=:fullname, email=:email, phone=:phone".$qstr);
        return $db_handle->execute(array(':fullname'=>$fullname, ':email'=>$email, ':phone'=>$phone))?true:false;
    }

    public function updatePosition($positionName, $positionSalary, $gratuityAmount, $positionPensionSalary, $id)
    {
        global $dbh;
        $db_handle = $dbh->prepare("UPDATE vc_position SET pos_name=:pos_name, pos_salary=:pos_salary, pos_pension=:pos_pension, pos_gratuity=:pos_gratuity WHERE id=:id");
        return $db_handle->execute(array(':pos_name'=>$positionName, ':pos_salary'=>$positionSalary, ':pos_pension'=>$positionPensionSalary, ':pos_gratuity'=>$gratuityAmount, ':id'=>$id))?true:false;
    }

    public function updateProfileCompany($companyName, $companyEmail, $companyPhone, $qstr)
    {
        global $dbh;
        $db_handle = $dbh->prepare("UPDATE vc_company SET c_name=:c_name, c_email=:c_email, c_phone=:c_phone".$qstr);
        return $db_handle->execute(array(':c_name'=>$companyName, ':c_email'=>$companyEmail, ':c_phone'=>$companyPhone))?true:false;
    }

    public function createWithdrawal($bankName, $accountName, $accountNumber, $withdrawalAmount)
    {
        global $dbh;
        $db_handle = $dbh->prepare("INSERT INTO vc_withdrawals SET user_id=:user_id, bank_name=:bank_name, account_name=:account_name, account_number=:account_number, withdrawal_amount=:withdrawal_amount, date=NOW()");
        return $db_handle->execute(array(':user_id'=>$_SESSION['tappUserId'], ':bank_name'=>$bankName, ':account_name'=>$accountName, ':account_number'=>$accountNumber, ':withdrawal_amount'=>$withdrawalAmount))?true:false;
    }

    public function oddString($odd)
    {
        $comps = array("FOOTBALL", "TENNIS", "BASKETBALL", "ICE HOCKEY", "VOLLEYBALL", "BADMINTON", "BASEBALL");

        $a = mt_rand(0, 3);
        $b = mt_rand(0, 3);
        if ($a==$b) {
            $s = 'FULL TIME DRAW';
        } elseif ($a>$b) {
            $s = 'HOME WIN';
        } else {
            $s = 'AWAY WIN';
        }
        return "FOOTBALL: ($a : $b) $s. ODD = $odd";
    }

    public function createUniqueId()
    {
      global $dbh;

      $id = $this->formatUnique($this->generate_unique_id(), 6);

      $db_handle = $dbh->prepare("SELECT * FROM pv_students WHERE reg_no=:id");
      $db_handle->execute(array("id"=>$id));
      return ($db_handle->rowCount()>0)?$this->createUniqueId():$id;
    }

    public function generate_unique_id(){
      $explode = uniqid('', true);
      $exp = explode('.', $explode);
      return $value = end($exp);
    }

    public function generateRandomString($length=10) {
        $random = substr(md5(rand()), 0, $length);
        return $random;
    }
  
    public function formatUnique($value, $length=10){
      return substr($value, 0, $length);
    }
	
    public function getallFieldinDropdownOption(string $table, string $field, $stor_val, $clause="", $matchField=false) {
      global $dbh;	
      try {
        $sql_query = "SELECT * FROM {$table} $clause ORDER BY {$field} ";
        $db_handle = $dbh->prepare($sql_query);
        $db_handle->execute();
          while ($rw = $db_handle->fetch(PDO::FETCH_ASSOC)) {
            $selectedCheck = ($rw[$stor_val] == $matchField) ? 'selected=selected': '';
            echo '<option value="'.$rw[$stor_val].'" '.$selectedCheck.'>' .$rw[$field]. '</option>';
          }
        $db_handle = null;
      } catch (PDOException $shadowalkertech) {
        $shadowalkertech->getMessage();
      }
    }
	
    public function getallFieldinDropdownOptions(string $table, string $field, $stor_val, $clause="", $matchField=false) {
      global $dbh;
      try {
        $sql_query = "SELECT * FROM {$table} $clause ORDER BY {$field} ";
        $db_handle = $dbh->prepare($sql_query);
        $db_handle->execute();
        $c=1;
          while ($rw = $db_handle->fetch(PDO::FETCH_ASSOC)) {
            $selectedCheck = ($rw[$stor_val] == $matchField) ? 'selected=selected': '';
            echo '<option value="'.$rw[$stor_val].'" desc="'.$rw['description'].'" '.$selectedCheck.'>#'.$c.'. '.$rw[$field]. '</option>';
            $c++;
          }
        $db_handle = null;
      } catch (PDOException $shadowalkertech) {
        $shadowalkertech->getMessage();
      }
    }
    
    public function valueExists($field, $table, $fieldValue, $qstr="")
    {
        global $dbh;
        $db_handle = $dbh->prepare("SELECT $field FROM $table WHERE $field=:fieldValue $qstr LIMIT 1");
        $db_handle->execute(array(':fieldValue'=>$fieldValue));
        return $db_handle->rowCount()==1?true:false;
    }

    public function getSumTableValue($field, $table, $whereClause)
    {
        global $dbh;
        $db_handle = $dbh->prepare("SELECT SUM($field) AS field FROM $table WHERE $whereClause");
        $db_handle->execute();
        $fetch_obj = $db_handle->fetch(PDO::FETCH_OBJ);
        return $db_handle->rowCount()==1?$fetch_obj->field:0;
    }

    public function getCountTableValue($field, $table, $whereClause)
    {
        global $dbh;
        $db_handle = $dbh->prepare("SELECT COUNT($field) AS field FROM $table WHERE $whereClause");
        $db_handle->execute();
        $fetch_obj = $db_handle->fetch(PDO::FETCH_OBJ);
        return $db_handle->rowCount()==1?$fetch_obj->field:0;
    }

    public function getUserById($userId)
    {
        global $dbh;
        $db_handle = $dbh->prepare("SELECT * FROM pv_students WHERE id=:id");
        $db_handle->execute(array(':id'=>$userId));
        return $db_handle->fetch(PDO::FETCH_OBJ);
    }

    public function getAdminById($userId)
    {
        global $dbh;
        $db_handle = $dbh->prepare("SELECT * FROM pv_admin WHERE id=:id");
        $db_handle->execute(array(':id'=>$userId));
        return $db_handle->fetch(PDO::FETCH_OBJ);
    }

    public function getUserByEmail($email)
    {
        global $dbh;
        $db_handle = $dbh->prepare("SELECT * FROM pv_students WHERE email=:email");
        $db_handle->execute(array(':email'=>$email));
        return $db_handle->fetch(PDO::FETCH_OBJ);
    }

    public function getUserByUniqueID($uniqeID)
    {
        global $dbh;
        $db_handle = $dbh->prepare("SELECT * FROM pv_students WHERE reg_no=:reg_no");
        $db_handle->execute(array(':reg_no'=>$uniqeID));
        return $db_handle->fetch(PDO::FETCH_OBJ);
    }

    public function getUserByUname($user_type, $uname)
    {
        global $dbh;
        $db_handle = $dbh->prepare("SELECT * FROM $user_type WHERE uname=:uname");
        $db_handle->execute(array(':uname'=>$uname));
        return $db_handle->fetch(PDO::FETCH_OBJ);
    }

    public function updateUserGrade($count, $id){
        global $dbh;
        $db_handle = $dbh->prepare("UPDATE pv_students SET exam_status='1', exam_score='{$count}' WHERE id='{$id}'");
        $db_handle->execute();
    }

    public function getAdminByEmail($email)
    {
        global $dbh;
        $db_handle = $dbh->prepare("SELECT * FROM pv_admin WHERE email=:email");
        $db_handle->execute(array(':email'=>$email));
        return $db_handle->fetch(PDO::FETCH_OBJ);
    }

    public function getCompanyByEmail($email)
    {
        global $dbh;
        $db_handle = $dbh->prepare("SELECT * FROM vc_company WHERE c_email=:c_email");
        $db_handle->execute(array(':c_email'=>$email));
        return $db_handle->fetch(PDO::FETCH_OBJ);
    }

    public function getValueByNames($table, $whereClause='')
    {
        global $dbh;
        $db_handle = $dbh->prepare("SELECT * FROM $table $whereClause");
        $db_handle->execute();
        return $db_handle->rowCount()>0?$db_handle->fetch(PDO::FETCH_OBJ):'';
    }

    public function getTokenByEmail($email, $expired)
    {
        global $dbh;
        $db_handle = $dbh->prepare("SELECT * FROM vc_token_auth WHERE user_email=:email AND is_expired=:is_expired");
        $db_handle->execute(array(':email'=>$email, ':is_expired'=>$expired));
        return $db_handle->fetch(PDO::FETCH_OBJ);
    }

    public function markAsExpired($tokenId)
    {
        global $dbh;
        $expired = 1;
        $db_handle = $dbh->prepare("UPDATE vc_token_auth SET is_expired =:is_expired WHERE id =:id");
        return $db_handle->execute(array(':is_expired'=>$expired, ':id'=>$tokenId));
    }

    public function insertToken($user_id, $email, $password_hash, $selector_hash, $expiry_date)
    {
        global $dbh;
        $db_handle = $dbh->prepare("INSERT INTO vc_token_auth SET user_id=:user_id, user_email=:email, password_hash=:password_hash, selector_hash=:selector_hash, expiry_date=:expiry_date");
        return $db_handle->execute(array(':user_id'=>$user_id, ':email'=>$email, ':password_hash'=>$password_hash, ':selector_hash'=>$selector_hash, ':expiry_date'=>$expiry_date));
    }

    public function clearAuthCookie()
    {
        $this->setCookie("user_login", "");
        $this->setCookie("random_password", "");
        $this->setCookie("random_selector", "");
    }

    public function deleteAllCookies()
    {
        // unset cookies
        if (isset($_SERVER['HTTP_COOKIE'])) {
            $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
            foreach ($cookies as $cookie) {
                $parts = explode('=', $cookie);
                $name = trim($parts[0]);
                setcookie($name, '', time()-1000);
                setcookie($name, '', time()-1000, '/');
            }
        }
    }

    public function getToken($length)
    {
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet .= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet .= "0123456789";
        $max = strlen($codeAlphabet) - 1;
        for ($i = 0; $i < $length; $i ++) {
            $token .= $codeAlphabet[$this->cryptoRandSecure(0, $max)];
        }
        return $token;
    }
    
    public function cryptoRandSecure($min, $max)
    {
        $range = $max - $min;
        if ($range < 1) {
            return $min; // not so random...
        }
        $log = ceil(log($range, 2));
        $bytes = (int) ($log / 8) + 1; // length in bytes
        $bits = (int) $log + 1; // length in bits
        $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
        do {
            $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
            $rnd = $rnd & $filter; // discard irrelevant bits
        } while ($rnd >= $range);
        return $min + $rnd;
    }

    public function imageDynamic($userimage, $imageLoc=false)
    {
        $siteimgloc = ($imageLoc == false)? $this->server_root_dir('images/'): $imageLoc;
        //checking if the image file exist
        if ($userimage === '' or $userimage === 'NULL' or $userimage === null) {
            $avatar = $siteimgloc .'default.jpg';
        // otherwize, get the real image
        } else {
            $avatar = $siteimgloc. $userimage;
        }
        return $avatar;
    }

    public function updatePersonalPicture($picture, $table, $id){
      global $dbh;
      $querySQL = "UPDATE $table SET picture='$picture' WHERE id='$id'";
      $db_handle = $dbh->prepare($querySQL);
      return ($db_handle->execute()) ? true : false;
    }

    public function updatePersonalPictureG($picture, $table, $id){
      global $dbh;
      $querySQL = "UPDATE $table SET pr_picture='$picture' WHERE id='$id'";
      $db_handle = $dbh->prepare($querySQL);
      return ($db_handle->execute()) ? true : false;
    }

    public function manageSystemSettings($status, $mode)
    {
        global $dbh;
        $querySQL = "UPDATE vc_app_config SET status='$status' WHERE module='$mode'";
        $db_handle = $dbh->prepare($querySQL);
        return ($db_handle->execute()) ? true : false;
    }

    public function email($fullname, $body, $link="", $linkTitle="")
    {
        return
        '
          <!doctype html>
          <html>
            <head>
              <meta name="viewport" content="width=device-width">
              <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
              <title>Legit Odd</title>
              <style>
              /* -------------------------------------
                  INLINED WITH htmlemail.io/inline
              ------------------------------------- */
              /* -------------------------------------
                  RESPONSIVE AND MOBILE FRIENDLY STYLES
              ------------------------------------- */
              @media only screen and (max-width: 620px) {
                table[class=body] h1 {
                  font-size: 28px !important;
                  margin-bottom: 10px !important;
                }
                table[class=body] p,
                      table[class=body] ul,
                      table[class=body] ol,
                      table[class=body] td,
                      table[class=body] span,
                      table[class=body] a {
                  font-size: 16px !important;
                }
                table[class=body] .wrapper,
                      table[class=body] .article {
                  padding: 10px !important;
                }
                table[class=body] .content {
                  padding: 0 !important;
                }
                table[class=body] .container {
                  padding: 0 !important;
                  width: 100% !important;
                }
                table[class=body] .main {
                  border-left-width: 0 !important;
                  border-radius: 0 !important;
                  border-right-width: 0 !important;
                }
                table[class=body] .btn table {
                  width: 100% !important;
                }
                table[class=body] .btn a {
                  width: 100% !important;
                }
                table[class=body] .img-responsive {
                  height: auto !important;
                  max-width: 100% !important;
                  width: auto !important;
                }
              }

              /* -------------------------------------
                  PRESERVE THESE STYLES IN THE HEAD
              ------------------------------------- */
              @media all {
                .ExternalClass {
                  width: 100%;
                }
                .ExternalClass,
                      .ExternalClass p,
                      .ExternalClass span,
                      .ExternalClass font,
                      .ExternalClass td,
                      .ExternalClass div {
                  line-height: 100%;
                }
                .apple-link a {
                  color: inherit !important;
                  font-family: inherit !important;
                  font-size: inherit !important;
                  font-weight: inherit !important;
                  line-height: inherit !important;
                  text-decoration: none !important;
                }
                #MessageViewBody a {
                  color: inherit;
                  text-decoration: none;
                  font-size: inherit;
                  font-family: inherit;
                  font-weight: inherit;
                  line-height: inherit;
                }
                .btn-primary table td:hover {
                  background-color: #34495e !important;
                }
                .btn-primary a:hover {
                  background-color: #34495e !important;
                  border-color: #34495e !important;
                }
              }
              </style>
            </head>
            <body class="" style="background-color: #f6f6f6; font-family: sans-serif; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.4; margin: 0; padding: 0; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%;">
              <table border="0" cellpadding="0" cellspacing="0" class="body" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background-color: #f6f6f6;">
                <tr>
                  <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>
                  <td class="container" style="font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; Margin: 0 auto; max-width: 580px; padding: 10px; width: 580px;">
                    <div class="content" style="box-sizing: border-box; display: block; Margin: 0 auto; max-width: 580px; padding: 10px;">

                      <!-- START CENTERED WHITE CONTAINER -->
                      <span class="preheader" style="color: transparent; display: none; height: 0; max-height: 0; max-width: 0; opacity: 0; overflow: hidden; mso-hide: all; visibility: hidden; width: 0;">'.strtoupper($this->app_title).'.</span>
                      <table class="main" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background: #ffffff; border-radius: 3px;">

                        <!-- START MAIN CONTENT AREA -->
                        <tr>
                          <td class="wrapper" style="font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;">
                            <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">
                              <tr>
                                <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">
                                  <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">Dear '.$fullname.',</p>
                                  <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">'.$body.'</p>
                                  <table border="0" cellpadding="0" cellspacing="0" class="btn btn-primary" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; box-sizing: border-box;">
                                    <tbody>
                                      <tr>
                                        <td align="left" style="font-family: sans-serif; font-size: 14px; vertical-align: top; padding-bottom: 15px;">
                                          <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: auto;">
                                            <tbody>
                                              <tr>
                                                <td style="font-family: sans-serif; font-size: 14px; vertical-align: top; background-color: #3498db; border-radius: 5px; text-align: center;"> <a href="'.$link.'" target="_blank" style="display: inline-block; color: #ffffff; background-color: #3498db; border: solid 1px #3498db; border-radius: 5px; box-sizing: border-box; cursor: pointer; text-decoration: none; font-size: 14px; font-weight: bold; margin: 0; padding: 12px 25px; text-transform: capitalize; border-color: #3498db;">'.$linkTitle.'</a> </td>
                                              </tr>
                                            </tbody>
                                          </table>
                                        </td>
                                      </tr>
                                    </tbody>
                                  </table>
                                  <!-- <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">This is a really simple email template. Its sole purpose is to get the recipient to click the button with no distractions.</p>
                                  <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">Good luck! Hope it works.</p> -->
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>

                      <!-- END MAIN CONTENT AREA -->
                      </table>

                      <!-- START FOOTER -->
                      <div class="footer" style="clear: both; Margin-top: 10px; text-align: center; width: 100%;">
                        <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;">
                          <tr>
                            <td class="content-block" style="font-family: sans-serif; vertical-align: top; padding-bottom: 10px; padding-top: 10px; font-size: 12px; color: #999999; text-align: center;">
                              <span class="apple-link" style="color: #999999; font-size: 12px; text-align: center;">© '.strtoupper($this->app_title).'</span>
                              <br> Visit our site always <a href="'.$this->server_root_dir('').'" style="text-decoration: underline; color: #999999; font-size: 12px; text-align: center;">Visit us</a>.
                            </td>
                          </tr>
                          <tr>
                            <td class="content-block powered-by" style="font-family: sans-serif; vertical-align: top; padding-bottom: 10px; padding-top: 10px; font-size: 12px; color: #999999; text-align: center;">
                              Powered by <a href="'.$this->server_root_dir('').'" style="color: #999999; font-size: 12px; text-align: center; text-decoration: none;">'.strtoupper($this->app_title).'</a>.
                            </td>
                          </tr>
                        </table>
                      </div>
                      <!-- END FOOTER -->

                    <!-- END CENTERED WHITE CONTAINER -->
                    </div>
                  </td>
                  <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;">&nbsp;</td>
                </tr>
              </table>
            </body>
          </html>
        ';
    }

    public function getCount($tb, $clause="")
    {
        global $dbh;
        $db_handle = $dbh->prepare("SELECT *, COUNT(id) as total FROM $tb $clause");
        $db_handle->execute();
        if ($db_handle->rowCount()>0) {
            $fetch_obj = $db_handle->fetch(PDO::FETCH_OBJ);
            return $fetch_obj->total;
        }
        return 0;
    }

    public function getCountReturnIDIfOne($tb, $clause="")
    {
        global $dbh;
        $db_handle = $dbh->prepare("SELECT *, COUNT(id) as total FROM $tb $clause");
        $db_handle->execute();
        if ($db_handle->rowCount()==1) {
            $fetch_obj = $db_handle->fetch(PDO::FETCH_OBJ);
            return $fetch_obj->id;
        }
        return false;
    }

    public function getCountReturnColumn($tb, $column, $clause="")
    {
        global $dbh;
        $db_handle = $dbh->prepare("SELECT *, COUNT(id) as total FROM $tb $clause");
        $db_handle->execute();
        if ($db_handle->rowCount()==1) {
            $fetch_obj = $db_handle->fetch(PDO::FETCH_OBJ);
            return $fetch_obj->$column;
        }
        return false;
    }

    public function nextGrade($g){
      if($g=="Primary 1"){
        return "Primary 2";
      }elseif($g=="Primary 2"){
        return "Primary 3";
      }elseif($g=="Primary 3"){
        return "Primary 4";
      }elseif($g=="Primary 4"){
        return "Primary 5";
      }elseif($g=="Primary 5"){
        return "Primary 6";
      }elseif($g=="Primary 6"){
        return "Graduated";
      }else{
        return "Graduated";
      }
      return "Graduated";
    }
}
$app = new App();
