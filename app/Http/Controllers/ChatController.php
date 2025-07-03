<?php

namespace App\Http\Controllers;

use App\Models\Chat_ah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
            if ($request->ajax()) {
                return response()->json(['error' => 'Please login first'], 401);
            }
            return redirect()->route('client.login')->with('error', 'Please login first');
        }
        
        // Validate the request
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);
        
        $inputs = $request->only('message');
        $inputs['client_id'] = $user->id;

        try {
            $chat = Chat_ah::create($inputs);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Message sent successfully',
                    'chat' => $chat
                ]);
            }
            
            return redirect()->back()->with('success', 'Message sent successfully');
        } catch (\Exception $e) {
            Log::error('Chat message creation failed: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json(['error' => 'Failed to send message'], 500);
            }
            
            return redirect()->back()->with('error', 'Failed to send message');
        }
    }
}
