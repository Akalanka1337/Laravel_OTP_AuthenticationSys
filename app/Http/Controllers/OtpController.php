<?php

namespace App\Http\Controllers;

use Illuminate\Support\Carbon;
use DateInterval;
use App\Models\otp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

define('apiuser', '#'); //API USER
define('apipsword', '#'); // API Token

class OtpController extends Controller
{
    public function make_otp(Request $request)
    {
        $otp_num = rand(100001, 999999);
        $time = Carbon::now();
        $endTime = $time->addMinutes(3);
        $endTime->format('Y-m-d h:i:s');

        $msg_transfer = Http::post(
            'http://smsm.lankabell.com:4040/Sms.svc/PostSendSms', //Place Your API Provider URL
            [
                'CompanyId' => apiuser,
                'Pword' => apipsword,
                'SmsMessage' => $otp_num,
                'PhoneNumber' => $request->tel_no,
            ]
        );

        $send_id = $msg_transfer['ID'];

        if ($send_id == 0) {
            return false;
        } else {
            $otp_info = otp::create([
                'exp_time' => $endTime,
                'phone_number' => $request->tel_no,
                'sms_id' => $send_id,
                'project' => $request->project,
                'desc' => $request->desc,
                'auth_status' => '',
                'otp' => $otp_num
            ]);
            return array('send_id' => $send_id);
        }
    }

    public function auth_otp($send_id, $otp)
    {
        $otp_data = otp::where('sms_id', '=', $send_id)
            ->orderBy('created_at', 'desc')
            ->first();
        if ($otp_data != null) {
            $url = "http://smsm.lankabell.com:4040/Sms.svc/SmsStatus?id=$send_id&companyId={COMPANY_ID}&pword={TOKEN}"; //Place Your API Token

            $msg_response_code = Http::get($url);
            $status = $msg_response_code['Status'];
            $exp_time = Carbon::parse($otp_data->exp_time);
            $now = Carbon::now();

            // dd($exp_time);
            // dd($now);

            // Carbon::parse($request->ShootDateTime)->timezone('America/Los_Angeles');
            if ($otp_data->otp == $otp) {
                if ($exp_time->greaterThanOrEqualTo($now)) {
                    $otp_response_update = otp::where('sms_id', '=', $send_id)
                        ->update([
                            'sms_response' => $status,
                            'auth_status' => Carbon::now(),
                        ]);
                    return array('status' => 'true');
                } else {
                    return array('status' => 'false');
                }
            } else {
                return array('status' => 'false');
            }
        } else {
            return array('status' => 'false');
        }
    }
}
