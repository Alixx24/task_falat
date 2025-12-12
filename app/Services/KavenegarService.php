<?php

namespace App\Services;

use Kavenegar;

class KavenegarService
{
    protected $sender;

    public function __construct()
    {
        $this->sender = config('kavenegar.sender', '2000660110'); // شماره اختصاصی از config
    }

    /**
     * ارسال پیامک ساده
     */
    public function sendSms(array|string $receptor, string $message)
    {
        try {
            $receptor = is_array($receptor) ? $receptor : [$receptor];

            $result = Kavenegar::Send($this->sender, $receptor, $message);

            return $result;

        } catch (\Kavenegar\Exceptions\ApiException $e) {
            return ['error' => 'API Error: '.$e->errorMessage()];
        } catch (\Kavenegar\Exceptions\HttpException $e) {
            return ['error' => 'HTTP Error: '.$e->errorMessage()];
        } catch (\Exception $e) {
            return ['error' => 'General Error: '.$e->getMessage()];
        }
    }

    /**
     * ارسال پیامک با قالب VerifyLookup
     */
  public function sendVerify(string $receptor, string $template, array $tokens = [])
{
    try {
        // مطمئن می‌شویم همیشه 3 توکن داریم
        $token1 = $tokens[0] ?? null;
        $token2 = $tokens[1] ?? null;
        $token3 = $tokens[2] ?? null;

        $result = Kavenegar::VerifyLookup($receptor, $token1, $token2, $token3, $template);

        return $result;
    } catch (\Exception $e) {
        return ['error' => $e->getMessage()];
    }
}

}
