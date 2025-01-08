<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\QuestMessageRequest;
use App\Mail\Message as MailMessage;
use App\Mail\QuestMessage;
use App\Models\Api\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $messages = Message::latest()->paginate();

        if (!$messages) {
            return $this->sendError([], 'unable to load messages', 500);
        }

        return $this->sendSuccess($messages, 'successful', 200); 

    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(QuestMessageRequest $request)
    {
        $data = $request->validated();

        try {
            //code...
            $message = Message::create($data);

            // Mail::to('info@sdssn.com')->queue(new QuestMessage($message));
            // Mail::to('info.sdssn@gmail.com')
            //     ->send(new MailMessage($message));
                Mail::to('info@sdssn.org')
                ->send(new MailMessage($message));


            if (!$message) {
                return $this->sendError([], 'unable to send message', 500);
            }

        } catch (\Throwable $th) {
            //throw $th;
            return $this->sendError([], 'unable to send message, try again later', 500);

        }


        return $this->sendSuccess($message, 'message sent successfully', 200); 
    }

    /**
     * Display the specified resource.
     */
    public function show(Message $message)
    {
        if (!$message) {
            return $this->sendError([], 'unable to load message', 500);
        }

        return $this->sendSuccess($message, 'successful', 200); 
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message)
    {
        $user = request()->user();
        if($user->role == 'user'){
            return $this->sendError([], 'you are unauthorize!', 401);
        }

        $message->delete();

        return $this->sendSuccess($message, 'message deleted', 200); 
    }
}
