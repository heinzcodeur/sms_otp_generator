<?php

namespace App\Security;

use Twilio\Rest\Client;
use Twilio\Rest\Verify\V2\ServiceContext;

class OTPService
{
    private $twilio;

    public function __construct(
        string $twilioSID = 'ACfd1603db2c7135fce3baa7e49cb24628',
        string $twilioToken = '588968f8f1edd1cdb1048784b80e5e79',
        string $twilioVerificationSID = 'VAa4750bdfb807c9c0b366de219088aa57'
    ) {
        $client = new Client($twilioSID, $twilioToken);
        $this->twilio = $client->verify->v2->services($twilioVerificationSID);
    }

    public function generateOTP(string $phoneNumber): void
    {
        $this->twilio->verifications->create($phoneNumber, 'sms');
    }

    public function isValidOTP(string $otp, string $phoneNumber): bool
    {
        $verificationResponse = $this->twilio->verificationChecks->create($otp, [
            'to' => $phoneNumber
        ]);

        return $verificationResponse->status === 'approved';
    }
}
