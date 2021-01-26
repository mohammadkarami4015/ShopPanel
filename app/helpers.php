<?php


use App\Code;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

function flash($title = null, $message = null)
{
    $flash = app('App\Http\Flash');
    if (func_num_args() == 0) {
        return $flash;
    }
    return $flash->info($title, $message);
}

function getTypeOfUser($str)
{

    switch ($str) {
        case "1":
            return "سوپر ادمین";
        case "2":
            return "ادمین";
        case "3":
            return "استاد اولیه";
        case "4":
            return "استاد";
        case "5":
            return "کاربر";
        default:
            return "نامعلوم";
    }
}

function getLevelOfUser($str)
{

    switch ($str) {
        case "1":
            return "مربی";
        case "2":
            return "استادیار";
        case "3":
            return "دانشیار";
        case "4":
            return "استاد";
        case "5":
            return "دانشجو";
        case "6":
            return "کاربر عادی";
        default:
            return "نامعلوم";
    }
}

function getCourseType($str)
{

    switch ($str) {
        case "1":
            return "خودآگاهی";
        case "2":
            return "خودسازی";
        case "3":
            return "راهبری";
        case "4":
            return "خودشناسی ";
        default:
            return "نامعلوم";
    }
}


function makePhotoTypeFile(UploadedFile $file, $type)
{
    /* set the path */
    $path = photoPath($type);
    /* set the name of file */
    $name = namedFile($file->getClientOriginalName());
    /* save file*/
    $file->move($path, $name);

    $file_path = $path . '' . $name;

    /* return file path */
    return $file_path;
}

/* set photo path */
function photoPath($type)
{
    $date = \Carbon\Carbon::now()->format('Y-m-d');
    return 'photos/' . $type . '/' . $date . '/';
}

/* set the name of file */
function namedFile($name)
{
    return time() . '_' . $name;
}

function jalaliFormat($date)
{
    return \Morilog\Jalali\Jalalian::forge($date)->format('Y-m-d H:i:s');
}

function deletePhoto($object, $request)
{
    $photos = explode(";", $object->photos);
    foreach ($photos as $key => $photo) {
        if ($request->key == $key && $request->url == $photo) {
            unset($photos[$request->key]);
            File::delete($request->url);
        }
    }
    $object->photos = implode(";", array_values(array_filter($photos)));
    $object->save();
}

function deleteSendPrice($object, $request)
{
    $sendPrices = explode(";", $object->send_prices);

    foreach ($sendPrices as $key => $sendPrice) {
        if ($request->sendPrice == $sendPrice) {
            unset($sendPrices[$request->key]);
        }
    }
    $object->send_prices = implode(";", array_values(array_filter($sendPrices)));
    $object->save();;
}

function orderStatus($id)
{
    switch ($id) {
        case 1:
            return 'ثبت شد';
        case 2:
            return 'تایید شد';

        case 3:
            return 'ارسال شد';

        case 4:
            return 'تحویل داده شد';

        case 5:
            return 'لغو شد';
        case 6:
            return 'مرجوعی';
        case 7:
            return 'لغو توسط کاربر';
    }
}

function generateRandomNumber($length = 6)
{
    $characters = '0123456789';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function checkVerificationCode($code, $phone_number)
{
    $code = Code::query()->where('code', $code)
        ->where('phone_number', $phone_number)
        ->where('status', "Active")
        ->get()->last();
    if ($code) {
        $code->status = "Expired";
        $code->save();
        $result['status'] = "0";
        return $result;
    } else {
        $result['status'] = -1;
        return $result;
    }

}
function SendIRVerificationCode($Code, $MobileNumber)
{
    $token = _getToken();
    if ($token != false) {
        $postData = array(
            'Code' => $Code,
            'MobileNumber' => $MobileNumber,
        );
        $url = "https://ws.sms.ir/api/VerificationCode";
        $VerificationCode = _execute($postData, $url, $token);
        $object = json_decode($VerificationCode);

        $result = false;
        if (is_object($object)) {
            $result = $object->Message;
        } else {
            $result = false;
        }

    } else {
        $result = false;
    }
    return $result;
}

function _getToken()

{
    $postData = array(
        'UserApiKey' => "eb38e3decb52842dac24f947",
        'SecretKey' => "Reza1376@",
        'System' => 'php_rest_v_2_0'
    );
    $postString = json_encode($postData);

    $ch = curl_init("https://ws.sms.ir/api/Token");
    curl_setopt(
        $ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json'
        )
    );
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);

    $result = curl_exec($ch);
    curl_close($ch);

    $response = json_decode($result);

    $resp = false;
    $IsSuccessful = '';
    $TokenKey = '';
    if (is_object($response)) {
        $IsSuccessful = $response->IsSuccessful;
        if ($IsSuccessful == true) {
            $TokenKey = $response->TokenKey;
            $resp = $TokenKey;
        } else {
            $resp = false;
        }
    }
    return $resp;
}

function _execute($postData, $url, $token)
{
    $postString = json_encode($postData);

    $ch = curl_init($url);

    curl_setopt(
        $ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'x-sms-ir-secure-token: ' . $token
        )
    );
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);

    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
}

function sendSms($phone_number, $Code)
{
    try {
        date_default_timezone_set("Asia/Tehran");
        SendIRVerificationCode($Code, $phone_number);

    } catch (Exception $e) {
        echo 'Error VerificationCode : ' . $e->getMessage();
    }
}

function sendFCM($title,$keys_values = array(),$tokens = array()) {


    //var_dump($description);
//        print_r($keys_values);
//        print_r($tokens);
    //return;

    $url = 'https://fcm.googleapis.com/fcm/send';


    $fields = array (
        'priority' => 'high',
        'registration_ids' => $tokens,
        'data' =>$keys_values,
        'notification' =>$keys_values
    );
    $fields = json_encode ( $fields );

    $headers = array (
        'Authorization: key=' . "AAAAfHWJ6P0:APA91bEAMCYVHeqI499YSXP5_7P9lddpQUfGW4u4BwDVIBdELTgMn4WTqfXHCQ6iKSCyFG9E9tnfK7BsywNOrdOB6Lzgyq3IPtMqgvmW4LaCEm9VoNpfBvVSLC0DmLq-neS5O0xVOdH1",
        'Content-Type: application/json'
    );

    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, $url );
    curl_setopt ( $ch, CURLOPT_POST, true );
    curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );

    $result = curl_exec ( $ch );

//        echo $result;
    //$this->db->where('id',$id)
    // ->update('notifications',array('response_msg'=>$result));

    curl_close ( $ch );

}

function getNotificationBody($order_status){

    switch ($order_status) {
        case "1":
            return " سفارش شما با موفقیت ثبت شد.";
        case "2":
            return "سفارش شما با موفقیت تایید شد.";
        case "3":
            return "سفارش شما با موفقیت ارسال شد.";
        case "4":
            return "سفارش شما با موفقیت تحویل داده شد.";
        case "5":
            return "سفارش شما لغو شد.";
        case "6":
            return "سفارش شما ارجاع داده شد.";
        case "7":
            return "سفارش توسط کاربر لغو شد.";
        default:
            return "سفارش شما با موفقیت ثبت شد.";
    }

}
