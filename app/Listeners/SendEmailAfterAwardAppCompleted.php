<?php

namespace App\Listeners;

use App\Events\AwardAppCompleted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;
use Illuminate\Support\Facades\Crypt;

class SendEmailAfterAwardAppCompleted
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
     * @param  AwardAppCompleted  $event
     * @return void
     */
    public function handle(AwardAppCompleted $event)
    {
        $user = $event->request->user();
        $input =  $event->request->all();
        $data = array(
  				'user' => $user,
  			 	'input' => $input
  			 );
        Mail::send('mails.awardAppCompletionEmail', $data, function ($m) use ($user) {
            $m->from('josei@noguchi.or.jp ', '公益財団法人 野口研究所');
            $m->to($user->email, $user->name)->subject('野口遵賞へのご応募ありがとうございます。');
        });

    }
}
