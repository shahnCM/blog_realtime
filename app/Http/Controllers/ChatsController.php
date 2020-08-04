<?php

namespace App\Http\Controllers; 

use Illuminate\Http\Request;
use Auth;
use App\Message;
use App\Events\MessageSent;


class ChatsController extends Controller
{
    public function _construct() {
        $this->middleware('auth');
    }

    public function index() {
        return view('chats');
    }

    public function fetchMessages() {
        return Message::with('user')->get();
    }

    public function sendMessage(Request $request) {
        // return response()->json(['ans' => $request])->header('ans', $request['message']);
        $message = Auth::user()->messages()->create([
                    'message' => $request->message
                ]);
        
        broadcast(new MessageSent($message->load('user')))->toOthers();
        // broadcast(new MessageSent(2));

        return ['status' => 'success'];
    }

}
