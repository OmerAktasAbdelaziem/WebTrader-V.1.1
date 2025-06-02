<?php

namespace App\Http\Controllers;

use App\Models\Chat_ah;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        $chats = Chat_ah::where('client_id',auth()->guard('client')->user()->id)->where('read',0)->update([
            'read' => 1
        ]);
        $chat  = Chat_ah::where('client_id',auth()->guard('client')->user()->id)->get();
        return view('clientarea.chat',compact(
            'chat',
        ));
    }

    public function store(Request $request)
    {
        $inputs = $request->only('message');
        $inputs['client_id'] = auth()->guard('client')->user()->id;

        Chat_ah::create($inputs);
        return redirect()->back();
    }
}
