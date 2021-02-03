<?php

namespace App\Listeners;

use App\Events\SubsidyAppCompleted;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;
use Illuminate\Support\Facades\Crypt;

class SendEmailAfterSubsidyAppCompleted
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
     * @param  SubsidyAppCompleted  $event
     * @return void
     */
    public function handle(SubsidyAppCompleted $event)
    {
        $user = $event->request->user();
        $input =  $event->request->all();
        $data = array(
  				'user' => $user,
  			 	'input' => $input
  			 );
        Mail::send('mails.subsidyAppCompletionEmail', $data, function ($m) use ($user) {
            $m->from('josei@noguchi.or.jp ', '公益財団法人 野口研究所');
            $m->to($user->email, $user->name)->subject('野口遵研究助成金へのご応募ありがとうございます。');
        });

    }
}
