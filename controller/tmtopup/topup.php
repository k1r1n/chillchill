<?php
require_once('./common/AES.php');
// กำหนด API Passkey
define('API_PASSKEY', 's52MMDNAK149');

if($_SERVER['REMOTE_ADDR'] == '203.146.127.115' && isset($_GET['request']))
{
  $aes = new Crypt_AES();
  $aes->setKey(API_PASSKEY);
  $_GET['request'] = base64_decode(strtr($_GET['request'], '-_,', '+/='));
  $_GET['request'] = $aes->decrypt($_GET['request']);
  if($_GET['request'] != false)
  {
    parse_str($_GET['request'],$request);
    $request['Ref1'] = base64_decode($request['Ref1']);

    // เริ่มต้นการทำงานของระบบของท่าน
    // mysql_connect('localhost','mysql_username','mysql_password');
    //     mysql_select_db('website');
    //     mysql_query('UPDATE users SET balance=balance+' . $request['cashcard_amount'] . ' WHERE username=\' . $request['Ref1'] . '\')

    //mail($request['Ref1'], 'ขอบพระคุณที่เลือกใช้บริการของเรา', 'ท่านได้ทำการเติมเงินด้วยบัตรเงินสดทรูมันนี่จำนวน ' . $request['cashcard_amount'] . ' บาท ขอบพระคุณที่ท่านเลือกใช้บริการของเรา');
    // สิ้นสุดการทำงานของระบบของท่าน

    echo 'SUCCEED';
  }
  else
  {
      echo 'ERROR|INVALID_PASSKEY';
  }
}
else
{
    echo 'ERROR|ACCESS_DENIED';
}
?>
