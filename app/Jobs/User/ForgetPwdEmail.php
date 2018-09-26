<?php

namespace App\Jobs\User;

use App\Jobs\Job;

use App\Modules\Users\Domain\Manager;

use Illuminate\Support\Facades\Mail;

class ForgetPwdEmail extends Job
{
    protected $email;
    protected $url;

    // add dash after number of characters in string
    const VERIFICATION_TYPE_EMAIL = 'email';

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $email, string $requestUrl)
    {
        $this->email = $email;
        $this->url = $requestUrl;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // find user by email
        $user = (new Manager)->findUserByAttr ('email', $this->email);

        // generate unique token
        $token = md5 (microtime (true));

        // add token information in respect of user
        $verification = $user->verification()->create([
            'type' => self::VERIFICATION_TYPE_EMAIL, 
            'token' => $token
        ]);

        // send Email to user
        Mail::send('emails.forget-password', [
            'resetPasswordLink' => $this->url."/{$verification->token}", 
            'name' => $user->profile()->first()->name
        ], function ($message){
            $message->to($user->email);
        });

    }
}
