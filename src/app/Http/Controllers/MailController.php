<?php

namespace App\Http\Controllers;

use App\Mail\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

class MailController extends Controller
{
    public function sendNotification(Request $request)
    {
        $destination = $request->input('destination');
        $messageContent = $request->input('message');

        if ($destination === 'all') {
            $users = User::all();
        } elseif ($destination === 'user') {
            $users = User::doesntHave('roles')->get();
        } else {
            $role = Role::findByName($destination);
            $users = $role ? $role->users : collect();
        }

        foreach ($users as $user) {
            Mail::to($user->email)->send(new Notification($user, $messageContent));
        }

        return back()->with('success', '送信完了しました。');
    }
}
