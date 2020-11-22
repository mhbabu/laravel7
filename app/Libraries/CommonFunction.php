<?php

namespace App\Libraries;

use App\Configuration;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Session;

class CommonFunction {

    /**
     * @param Carbon|string $updated_at
     * @param string $updated_by
     * @return string
     * @internal param $Users->id /string $updated_by
     */
    public static function showAuditLog($updated_at = '', $updated_by = '') {
        $update_was = 'Unknown';
        if ($updated_at && $updated_at > '0') {
            $update_was = Carbon::createFromFormat('Y-m-d H:i:s', $updated_at)->diffForHumans();
        }

        $user_name = 'Unknown';
        if ($updated_by) {
            $name = User::where('id', $updated_by)->first();
            if ($name) {
                $user_name = $name->user_full_name;
            }
        }
        return '<span class="help-block">Last updated : <i>' . $update_was . '</i> by <b>' . $user_name . '</b></span>';
    }

    public static function getUserId() {

        if (Auth::user()) {
            return Auth::user()->id;
        } else {
            return 0;
        }
    }

    public static function getUserType() {

        if (Auth::user()) {
            return Auth::user()->user_type;
        } else {
            dd('Invalid User Type');
        }
    }

//    public static function GlobalSettings(){
//        $logoInfo=Logo::orderBy('id','DESC')->first();
//        if($logoInfo!="") {
//            Session::set('logo', $logoInfo->logo);
//            Session::set('title', $logoInfo->title);
//            Session::set('manage_by', $logoInfo->manage_by);
//            Session::set('help_link', $logoInfo->help_link);
//        }else{
//            Session::set('logo', 'assets/images/company_logo.png');
//        }
//        //return $logoInfo;
//    }



    public static function convertUTF8($string) {
//        $string = 'u0986u09a8u09c7u09beu09dfu09beu09b0 u09b9u09c7u09beu09b8u09beu0987u09a8';
        $string = preg_replace('/u([0-9a-fA-F]+)/', '&#x$1;', $string);
        return html_entity_decode($string, ENT_COMPAT, 'UTF-8');
    }

    public static function isAdmin() {
        $user_type = Auth::user()->user_type;
        /*
         * 1x101 for System Admin
         */
        if ($user_type == '1x101')
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public static function convert2Bangla($eng_number) {
        $eng = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
        $ban = ['à§¦', 'à§§', 'à§¨', 'à§©', 'à§ª', 'à§«', 'à§¬', 'à§­', 'à§®', 'à§¯'];
        return str_replace($eng, $ban, $eng_number);
    }

    public static function convert2English($ban_number) {
        $eng = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
        $ban = ['à§¦', 'à§§', 'à§¨', 'à§©', 'à§ª', 'à§«', 'à§¬', 'à§­', 'à§®', 'à§¯'];
        return str_replace($ban, $eng, $ban_number);
    }

    public static function generateTrackingID($prefix, $id) {
        $prefix = strtoupper($prefix);
        $str = $id . date('Y') . mt_rand(0, 9);
        if ($prefix == 'M' || $prefix == 'N') {
            if (strlen($str) > 12) {
                $str = substr($str, strlen($str) - 12);
            }
        } elseif ($prefix == 'G') {
            if (strlen($str) > 10) {
                $str = substr($str, strlen($str) - 10);
            }
        } elseif ($prefix == 'T') {
            if (strlen($str) > 12) {
                $str = substr($str, strlen($str) - 12);
            }
        } else {
            if (strlen($str) > 14) {
                $str = substr($str, strlen($str) - 14);
            }
        }
        return $prefix . dechex($str);
    }



//    public static function getTeamNameById($id) {
//        if ($id) {
//            $name = TeamInfo::where('id', $id)->pluck('name');
//            return $name;
//        } else {
//            return 'N/A';
//        }
//    }

    public static function sendMessageFromSystem($param=array()) {

        $mobileNo = (empty($param[0]['mobileNo']) ? '0' : $param[0]['mobileNo']);
        $smsYes = (empty($param[0]['smsYes']) ? '0' : $param[0]['smsYes']);
        $smsBody = (empty($param[0]['smsBody']) ? 'No SMS Body' : $param[0]['smsBody']);
        $emailYes = (empty($param[0]['emailYes']) ? '1' : $param[0]['emailYes']);
        $emailBody = (empty($param[0]['emailBody']) ? 'No Email Body' : $param[0]['emailBody']);
        $emailHeader = (empty($param[0]['emailHeader']) ? '0' : $param[0]['emailHeader']);
        $emailAdd= (empty($param[0]['emailAdd']) ? 'base@gmail.com' : $param[0]['emailAdd']);
        $template= (empty($param[0]['emailTemplate']) ? '' : $param[0]['emailTemplate']);
        $emailSubject= (empty($param[0]['emailSubject']) ? 'No Subject' : $param[0]['emailSubject']);

        if ($emailYes == 1) {

            $email_content_html = <<<HERE
          <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Inventory Management System</title>
    <link href='https://fonts.googleapis.com/css?family=Vollkorn' rel='stylesheet' type='text/css'>
    <style type="text/css">
        *{
            font-family: Vollkorn;
        }
    </style>
</head>


<body>
<table width="80%" style="background-color:#D2E0E8;margin:0 auto; height:50px; border-radius: 4px;">
    <thead>
    <tr>
        <td style="padding: 10px; border-bottom: 1px solid rgba(0, 102, 255, 0.21);">
            
            <h4 style="text-align:center">
               Inventory Management System
            </h4>
        </td>
    </tr>
    </thead>
    <tbody>
    <tr>
        <td style="margin-top: 20px; padding: 15px;">
            <!--Dear Applicant,-->
            Dear User,
            <br/><br/>
          $emailBody

            <br/><br/>
        </td>
    </tr>
    <tr style="margin-top: 15px;">
        <td style="padding: 1px; border-top: 1px solid rgba(0, 102, 255, 0.21);">
            <h5 style="text-align:center">All right reserved by Inventory Management System 2017.</h5>
        </td>
    </tr>
    </tbody>
</table>
</body>
</html>
HERE;

            $emailQueue = new EmailQueue();
            $emailQueue->service_id = 0; // there is no service id
            $emailQueue->app_id = 0; // there is no app id
            $emailQueue->email_content = $email_content_html;
            $emailQueue->email_to = $emailAdd;
            $emailQueue->email_subject = $emailSubject;
            $emailQueue->email_cc = 'write2hkc@gmail.com';
            $emailQueue->attachment = '';
            $emailQueue->secret_key = '';
            $emailQueue->pdf_type = '';
            $emailQueue->save();
        }
        if ($smsYes == 1) {
            $emailQueue = new EmailQueue();
            $emailQueue->service_id = 0; // there is no service id
            $emailQueue->app_id = 0; // there is no app id
            $emailQueue->sms_content = $smsBody;
            $emailQueue->sms_to = $mobileNo;
            $emailQueue->attachment = '';
            $emailQueue->secret_key = '';
            $emailQueue->pdf_type = '';
            $emailQueue->save();
        }
    }

    public static function auditEmail(){
        return Configuration::where('caption','AUDIT_EMAIL')->pluck('value');
    }

    /*     * ****************************End of Class***************************** */
}