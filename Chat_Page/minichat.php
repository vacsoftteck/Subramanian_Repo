<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WhatsApp Chat UI</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
       body {
    font-family: Arial, sans-serif;
    background: #f0f0f0;
    margin: 0;
    padding: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}

.chat-container {
    width: 400px;
    height: 500px;
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2), 0 4px 6px rgba(0, 0, 0, 0.15);
    display: flex;
    flex-direction: column;
}

/* Chat Header */
.chat-header {
    background: #25d366;
    color: white;
    padding: 15px;
    text-align: center;
    font-weight: bold;
}

/* Chat Messages */
.chat-messages {
    flex-grow: 1;
    overflow-y: auto;
    padding: 10px;
    background: #ffffff;
    display: flex;
    flex-direction: column;
}

.msgs {
    margin: 5px 0;
    padding: 10px;
    border-radius: 8px;
    max-width: 75%;
    word-wrap: break-word;
    line-height: 1.4;
    font-size: 14px;
}

.received {
    background: white;
    align-self: flex-start;
    text-align: left;
}

.sent {
    background: #dcf8c6;
    align-self: flex-end;
    text-align: right;
}

.time {
    font-size: 12px;
    color: gray;
    display: block;
    margin-top: 5px;
}

/* Chat Footer */
.chat-footer {
    display: flex;
    padding: 10px;
    background: white;
    border-top: 1px solid #ddd;
}

.chat-footer input {
    flex-grow: 1;
    padding: 10px;
    border: none;
    outline: none;
    font-size: 16px;
    border-radius: 20px;
    background: white;
    margin-right: 10px;
    color:white;
}

.chat-footer button {
    background: #25d366;
    color: white;
    border: none;
    padding: 12px;
    cursor: pointer;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.3s;
}

.chat-footer button i {
    font-size: 16px;
    transition: transform 0.2s ease, color 0.3s ease;
}

.chat-footer button:hover i {
    transform: scale(1.2);
    color: #075e54;
}

</style>
</head>
<body>

    <div class="chat-container">
        <!-- Chat Header -->
        <div class="chat-header" id="headername">Chat</div>

        <!-- Chat Messages -->
        <div class="chat-messages" id="chats">
            <p>Loading messages...</p>
        </div>

        <!-- Chat Footer -->
        <div class="chat-footer">
            <input type="text" id="messageInput" placeholder="Type a message...">
            <button onclick="sendMessage()">
            <i class="fas fa-paper-plane"></i>
</button>

        </div>
    </div>

    <script>
        var contactnumber = getQueryVariable("phonenumber");
        var chatBox = document.getElementById('chats');

        if (!contactnumber) {
            chatBox.innerHTML = "<p style='text-align:center; color:red;'>Error: No phone number provided.</p>";
        } else {
            document.getElementById('headername').innerText = "Chat with " + contactnumber;
            fetchMessages();
            setInterval(fetchMessages, 5000); // Polling every 5 seconds
        }

        function fetchMessages() {
    $.ajax({
        url: 'API/VACWA_api.php',
        type: 'POST',
        data: { number: contactnumber, operation: 'getChatOfContact' },
        success: function(response) {
            try {
                var jsonObject = JSON.parse(response);
                if (jsonObject.success !== "true") return;

                var msgs = jsonObject.messages;
                var chatHTML = '';

                msgs.forEach(msgObj => {
                    let msgText = msgObj.msg
                        .replace(/\*(.*?)\*/g, '<b>$1</b>')  // *bold* → <b>bold</b>
                        .replace(/_(.*?)_/g, '<i>$1</i>')   // _italic_ → <i>italic</i>
                        .replace(/\n/g, '<br>');            // Preserve line breaks

                    let msgType = msgObj.type === "IN" ? "received" : "sent";
                    let time = formatTime(msgObj.msgtime);

                    chatHTML += `
                        <div class="msgs ${msgType}">
                            ${msgText}
                            <span class="time">${time}</span>
                        </div>`;
                });

                chatBox.innerHTML = chatHTML;
                chatBox.scrollTop = chatBox.scrollHeight; // Auto-scroll to latest message
            } catch (error) {
                // console.error("Error parsing API response", error);
            }
        }
    });
}

// Function to format date and time as DD-MM-YYYY HH:MM:SS
function formatTime(dateTime) {
    let dateObj = new Date(dateTime);
    let day = String(dateObj.getDate()).padStart(2, '0');
    let month = String(dateObj.getMonth() + 1).padStart(2, '0'); // Months are 0-based
    let year = dateObj.getFullYear();
    let hours = String(dateObj.getHours()).padStart(2, '0');
    let minutes = String(dateObj.getMinutes()).padStart(2, '0');
    let seconds = String(dateObj.getSeconds()).padStart(2, '0');

    return `${day}-${month}-${year} ${hours}:${minutes}:${seconds}`;
}


function handleKeyPress(event) {
    if (event.key === "Enter") {
        sendMessage();
    }
}

function sendMessage() {
    var msgElement = document.getElementById('messageInput');
    var msg = msgElement.value.trim();

    if (msg !== '') {
        // Replace *bold* with **bold** and _italic_ with WhatsApp formatting
        msg = msg.replace(/\*(.*?)\*/g, '**$1**').replace(/_(.*?)_/g, '_$1_');

        $.ajax({
            url: 'API/VACWA_api.php',
            type: 'POST',
            data: {
                operation: 'sendMsg',
                number: contactnumber,
                msg: msg
            },
            success: function(response) {
                fetchMessages();
            }
        });

        msgElement.value = ''; // Clear input field
    }
}

        // Function to get query variable from URL
        function getQueryVariable(variable) {
            var query = window.location.search.substring(1);
            var vars = query.split("&");
            for (var i = 0; i < vars.length; i++) {
                var pair = vars[i].split("=");
                if (pair[0] === variable) {
                    return decodeURIComponent(pair[1]);
                }
            }
            return null;
        }
    </script>

</body>
</html>