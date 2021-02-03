<?php

namespace App\Listeners;

use App\Events\AwardRejectionNoticed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class SendEmailAwardRejectionNoticed
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
     * @param  AwardRejectionNoticed  $event
     * @return void
     */
    public function handle(AwardRejectionNoticed $event)
    {
      $award = $event->award;
      $user = $event->award->user;

       $data = array(
        'award' => $award,
        'user' => $user,
       );

       Mail::send('mails.awardRejectionNoticedEmail', $data, function ($m) use ($award) {
        $m->from('josei@noguchi.or.jp ', '公益財団法人 野口研究所');
        $m->to($award->user->email, $award->name)->subject('野口遵賞採択結果');
    });

   }
}
