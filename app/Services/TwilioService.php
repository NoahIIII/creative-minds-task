<?php

namespace App\Services;

use Twilio\Rest\Client;

class TwilioService
{
    protected $twilioSID;
    protected $twilioToken;
    protected $twilio;
    public function __construct()
    {
        $this->twilioSID = config('services.twilio.sid');
        $this->twilioToken = config('services.twilio.token');
        $this->twilio = new Client($this->twilioSID, $this->twilioToken);
    }
    /**
     * sends verification otp for the user phone
     * @param mixed $to
     * @param mixed $code
     */
    public function sendOTP($to, $code)
    {
        $message = $this->twilio->messages
            ->create(
                $to,
                array(
                    "from" => config('services.twilio.from'),
                    "body" => "Your OTP Code is: " . $code . " Valid for 5 minutes"
                )
            );

        if ($message->status == 'queued' && $message->errorMessage == null) {
            return true;
        } else {
            throw new \Exception($message->errorMessage ?? 'Unknown error occurred');
        }
    }
}
