<?php 
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;
use App\Models\User;
use App\Helpers\EncryptionHelper;
use Illuminate\Support\Facades\Auth;
use Pusher\Pusher;
use Illuminate\Support\Facades\Validator;

class ChatController extends Controller
{

    public function index()
    {
        if(!Auth::user()->receiver_id){
            return redirect('connect');
        }
        return view('chat');
    }

    public function connect() {
        return view('connect');
    }
    public function sendRequest(Request $request) {
        $receiver = User::where('username', $request->username)->first();
        dd($receiver);
        $validator = Validator::make($request->all(), [
            'receiver_id' => 'required|exists:users,id',
            'message' => 'nullable|string',
            'file' => 'nullable|file|max:10240' // Max 10MB
        ]);

        // Check validation manually
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422); // 422 Unprocessable Entity
        }
        if (!$receiver) {
            return response()->json(['error' => 'User not found'], 404);
        }

        $chat = Chat::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $receiver->id,
            'encrypted_message' => '',
            'accepted' => false
        ]);

        return response()->json(['message' => 'Chat request sent']);
    }

    public function acceptRequest($id) {
        $chat = Chat::where('receiver_id', Auth::id())->where('id', $id)->first();
        if ($chat) {
            $chat->update(['accepted' => true]);
            return response()->json(['message' => 'Chat request accepted']);
        }
        return response()->json(['error' => 'Chat request not found'], 404);
    }

    public function sendMessage(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'receiver_id' => 'required|exists:users,id',
            'message' => 'nullable|string',
            'file' => 'nullable|file|max:10240' // Max 10MB
        ]);

        // Check validation manually
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422); // 422 Unprocessable Entity
        }
        
        $key = env('APP_KEY');

        $encryptedMessage = $request->message ? EncryptionHelper::encryptMessage($request->message, $key) : null;

        $filePath = null;
        $fileType = null;

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileType = $file->getClientOriginalExtension();
            $filePath = $file->store('chat_files', 'public'); // Store file in `storage/app/public/chat_files`
        }



        $chat = Chat::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'encrypted_message' => $encryptedMessage,
            'file_path' => $filePath,
            'file_type' => $fileType,
            'accepted' => true
        ]);

        $pusher = new Pusher(env('PUSHER_APP_KEY'), env('PUSHER_APP_SECRET'), env('PUSHER_APP_ID'), [
            'cluster' => env('PUSHER_APP_CLUSTER'),
            'useTLS' => true
        ]);

        $pusher->trigger('chat-channel', 'new-message', [
            'message' => $encryptedMessage,
            'file_url' => $filePath ? asset('storage/' . $filePath) : null,
            'file_type' => $fileType,
            'sender_id' => Auth::id()
        ]);

        return response()->json(['message' => 'Message sent']);
    }


    public function getMessages($receiver_id)
    {
        $key = env('APP_KEY');
        $messages = Chat::where(function ($query) use ($receiver_id) {
            $query->where('sender_id', Auth::id())->where('receiver_id', $receiver_id);
        })->orWhere(function ($query) use ($receiver_id) {
            $query->where('sender_id', $receiver_id)->where('receiver_id', Auth::id());
        })->get();

        foreach ($messages as $message) {
            $message->decrypted_message = $message->encrypted_message ? EncryptionHelper::decryptMessage($message->encrypted_message, $key) : null;
            $message->file_url = $message->file_path ? asset('storage/' . $message->file_path) : null;
        }

        return response()->json($messages);
    }

}
?>