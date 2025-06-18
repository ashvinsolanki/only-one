const pusher = new Pusher("f1d2e2efb8216e240ea9", { cluster: "ap2" });
const channel = pusher.subscribe("chat-channel");

channel.bind("new-message", function(data) {
    let messageContainer = document.getElementById("chat-messages");
    let messageDiv = document.createElement("div");
    console.log(data);
    if (data.message) {
        let decryptedMessage = atob(data.message);
        messageDiv.innerHTML += `<p>${decryptedMessage}</p>`;
    }

    if (data.file_url) {
        if (["jpg", "jpeg", "png", "gif"].includes(data.file_type)) {
            messageDiv.innerHTML += `<img src="${data.file_url}" width="200" />`;
        } else if (["mp4", "avi", "mov"].includes(data.file_type)) {
            messageDiv.innerHTML += `<video controls width="200"><source src="${data.file_url}" type="video/${data.file_type}"></video>`;
        } else {
            messageDiv.innerHTML += `<a href="${data.file_url}" target="_blank">Download File</a>`;
        }
    }

    messageContainer.appendChild(messageDiv);
});


function sendMessage(receiver_id) {
    let message = document.getElementById("message").value;
    let fileInput = document.getElementById("file");

    let formData = new FormData();
    formData.append("receiver_id", receiver_id);
    formData.append("message", message ? btoa(message) : ""); // Encrypt message
    if (fileInput.files.length > 0) {
        formData.append("file", fileInput.files[0]);
    }

    fetch("/send-message", {
        method: "POST",
        headers: { "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content },
        body: formData
    }).then(res => res.json()).then(console.log);
}

