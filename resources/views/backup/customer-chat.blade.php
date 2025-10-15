<!-- filepath: /home/demigod/Documents/live-chat/resources/views/customer-chat.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Chat</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <h2 class="text-center">Customer Chat</h2>
                <form id="chatForm" action="{{ route('send.message') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="number">Number</label>
                        <input type="number" class="form-control" id="number" name="customer_phone" placeholder="Enter your number" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" required>
                    </div>
                    <div class="form-group">
                        <label for="message">Message</label>
                        <input type="text" class="form-control" id="message" name="message" placeholder="Enter your message" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Send</button>
                </form>
                <div id="chat" class="mt-4">
                    <!-- Messages will be displayed here -->
                </div>
            </div>
        </div>
    </div>
    {{-- <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> --}}
    {{-- <script>
        document.getElementById('chatForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission

            const numberInput = document.getElementById('number');
            const nameInput = document.getElementById('name');
            const messageInput = document.getElementById('message');
            const chat = document.getElementById('chat');

            const number = numberInput.value;
            const name = nameInput.value;
            const message = messageInput.value;

            // Append the new message to the chat
            const newMessage = document.createElement('div');
            newMessage.classList.add('alert', 'alert-secondary');
            newMessage.innerHTML = `<strong>${name} (${number}):</strong> ${message}`;
            chat.appendChild(newMessage);

            // Clear the input fields
            numberInput.value = '';
            nameInput.value = '';
            messageInput.value = '';
        }); --}}
    </script>
</body>
</html>