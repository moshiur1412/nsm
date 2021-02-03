<?php

namespace App\Listeners;

use App\Events\UserVerified;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class SendEmailForUserVerified
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  UserVerified  $event
     * @return void
     */
    public function handle(UserVerified $event)
    {
       $user = $event->request;
       Mail::send('mails.userVerifiedMail', ['user' => $user], function ($m) use ($user) {
        $m->from('josei@noguchi.or.jp ', '公益財団法人 野口研究所');
        $m->to($user['user-email'], $user['user-name'])->subject('仮登録完了のお知らせ');
    });
   }
}
