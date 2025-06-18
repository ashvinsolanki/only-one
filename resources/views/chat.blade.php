@extends('layouts.app')

@section('content')
<div class="flex justify-center items-center h-screen bg-gray-100">
    <div class="w-full max-w-md bg-white shadow-lg rounded-lg overflow-hidden">
        <!-- Chat Header -->
        <div class="bg-blue-500 text-white py-3 px-5 flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <img src="{{ asset('images/default-avatar.png') }}" class="w-10 h-10 rounded-full" alt="Avatar">
                <span class="text-lg font-semibold" id="chat-username">Username</span>
            </div>
            <button class="text-white" onclick="closeChat()">✖</button>
        </div>

        <!-- Chat Messages -->
        <div id="chat-messages" class="p-4 h-80 overflow-y-auto space-y-3">
            <!-- Messages will be appended here -->
        </div>

        <!-- Chat Input -->
        <div class="border-t p-3 bg-gray-100 flex items-center space-x-2">
            <input type="text" id="message" placeholder="Type a message..." class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            <input type="file" id="file" class="hidden" onchange="previewFile()">
            <label for="file" class="cursor-pointer">
                📎
            </label>
            <button onclick="sendMessage()" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">Send</button>
        </div>
    </div>
</div>

<script>
    let receiver_id = 1; // Replace with dynamic user ID

    function sendMessage() {
        let message = document.getElementById("message").value;
        let fileInput = document.getElementById("file");
        let formData = new FormData();
        
        formData.append("receiver_id", receiver_id);
        formData.append("message", message);
        
        if (fileInput.files.length > 0) {
            formData.append("file", fileInput.files[0]);
        }
        formData.append("_token", document.querySelector('meta[name="csrf-token"]').content);
        fetch("/send-message", {
            method: "POST",
            headers: { "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content },
            body: formData
        }).then(res => res.json()).then(data => {
            document.getElementById("message").value = "";
            fileInput.value = "";
        });
    }

    function previewFile() {
        let file = document.getElementById("file").files[0];
        if (file) {
            alert("File ready to send: " + file.name);
        }
    }
</script>
@endsection
