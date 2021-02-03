<?php

namespace App\Listeners;

use App\Events\UserPasswordChanged;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;
use Illuminate\Support\Facades\Crypt;

class SendEmailAfterPasswordChanged
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
     * @param  UserPasswordChanged  $event
     * @return void
     */
    public function handle(UserPasswordChanged $event)
    {
        
        $user = $event->request->user();
        Mail::send('mails.changedPsswordMail', ['user' => $user], function ($m) use ($user) {
            $m->from('info@noguchi.org', 'Noguchi Support');
            $m->to($user->email, $user->name)->subject('Your password on Noguchi was changed');
        }); 

    }
}
