<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Events\SendMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AdminMessageController extends Controller
{
    public function viewChat()
    {
        Gate::authorize('view', Message::class);
        return view('admin.chat.chat-box');
    }

    public function getCustomers()
    {
     // $messages = Message::where('is_admin', 0)
     //   ->groupBy('customer_phone')
     //   ->orderBy('created_at', 'asc')
     //   ->get();
         // Ambil id pesan terakhir untuk setiap customer_phone
    $lastMessageIds = Message::where('is_admin', 0)
        ->selectRaw('MAX(id) as id')
        ->groupBy('customer_phone')
        ->pluck('id');

    // Ambil data lengkap pesan-pesan tersebut
    $messages = Message::whereIn('id', $lastMessageIds)
        ->orderBy('created_at', 'asc')
        ->get();

    return response()->json($messages);

    }
    public function index($customer_phone)
    {
        $messages = Message::where('customer_phone', $customer_phone)
            ->orderBy('created_at', 'asc')
            ->get();

         return response()->json($messages);
    }

    public function sendMessageAdmin(Request $request)
    {
       //      Log::info('chat', $request->all());
        Gate::authorize('create', Message::class);
        $validated = $request->validate([
         'customer_phone' => 'required',
      //   'name' => 'required',
             'message' => 'required|string'
         ]);

        $message = Message::create([
            'is_admin' => 1,
            'customer_phone' => $validated['customer_phone'],
            'name' => auth()->user()->name,
            'message' => $validated['message']
        ]);


        // broadcast(new SendMessage($message));
        SendMessage::dispatch($message);

        return response()->json(['status' => 'Message sent!'], 200);
        // return back()->with('message', 'Message sent!');
    }
}
