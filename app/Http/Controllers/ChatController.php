<?php

namespace App\Http\Controllers;

use App\Models\Chat_ah;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        $user = auth()->guard('client')->user();
        if (!$user) {
            return redirect()->route('client.login')->with('error', 'Please login first');
        }
        
        $chats = Chat_ah::where('client_id', $user->id)->where('read',0)->update([
            'read' => 1
        ]);
        $chat  = Chat_ah::where('client_id', $user->id)->get();
        return view('clientarea.chat',compact(
            'chat',
        ));
    }

    public function store(Request $request)
    {
        $user = auth()->guard('client')->user();
        if (!$user) {
            return redirect()->route('client.login')->with('error', 'Please login first');
        }
        
        $inputs = $request->only('message');
        $inputs['client_id'] = $user->id;

        Chat_ah::create($inputs);
        return redirect()->back();
    }
}
