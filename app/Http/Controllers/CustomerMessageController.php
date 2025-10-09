<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Events\SendMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CustomerMessageController extends Controller
{
    public function chatCustomer()
    {
        return view('customer-chat');
    }

    public function sendMessageCustomer(Request $request)
    {
        $message = $request->validate([
            'customer_phone' => 'required',
            'name' => 'required',
            'message' => 'required'
        ]);

        $message = Message::create([
            'is_admin' => 0,
            'customer_phone' => $message['customer_phone'],
            'name' => $message['name'],
            'message' => $message['message']
        ]);

        session([
            'customer_phone' => $message['customer_phone'],
            'customer_name' => $message['name']
        ]);

        SendMessage::dispatch($message);

        return response()->json([
            'status' => 'success',
            'message' => $message
        ]);

        // return redirect()->route('customer.to.admin', ['customer_phone' => $request->customer_phone, 'name' => $request->name]);
    }

    public function passingCustomerToAdmin(Request $request, $name)
    {

        // $messages = Message::where(function ($query) use ($request) {
        //     $query->where('customer_phone', $request->customer_phone);
        // })
        //     ->get();

        // return view('message-customer', compact('messages', 'name'));

        $messages = Message::where('customer_phone', $request->customer_phone)
        ->orderBy('created_at', 'asc')
        ->get()
        ->map(function ($msg) {
            return [
                'id' => $msg->id,  // Added ID field for polling
                'is_admin'=>$msg->is_admin,
                'name' => $msg->name,  // Pastikan 'name' ada
                'message' => $msg->message,
                'created_at' => $msg->created_at
            ];
        });

    return response()->json(['messages' => $messages]);
    }

    public function chatCustomerToAdmin(Request $request)
    {
        // $message = $request->validate([
        //     'customer_phone' => 'required',
        //     'name' => 'required',
        //     'message' => 'required'
        // ]);

        $message = Message::create([
            'is_admin' => 0,
            'customer_phone' => $request->customer_phone,
            'name' => $request->name,
            'message' => $request->message
        ]);

        // broadcast(new SendMessage($message));
        SendMessage::dispatch($message);

        return response()->json([
            'status' => 'Message sent!', 
            'message' => $message
        ], 200);
    }
}
