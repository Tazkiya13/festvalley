<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Chat</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                Admin
            </div>
            <div class="card-body">
                <div id="chat" class="mb-4">
                    @foreach ($messages as $message)
                        <div class="d-flex {{ $message->is_admin ? 'justify-content-end' : 'justify-content-start' }} mb-2">
                            <div class="p-3 {{ $message->is_admin ? 'bg-primary text-white' : 'bg-light' }} rounded">
                                <p class="mb-0"><strong>{{ $message->name }}</strong></p>
                                <p class="mb-0">{{ $message->message }}</p>
                                <p class="mb-0 text-dark"><small>{{ \Carbon\Carbon::parse($message->created_at)->setTimezone('Asia/Jakarta')->format('d M, H:i') }}</small></p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <form action="#" method="POST" id="messageForm">
                    @csrf
                    <input type="hidden" id="customer_phone" value="{{ request()->route('customer_phone') }}">
                    {{-- <input type="hidden" id="name" value="{{ $messages->first()->name }}"> --}}
                    <div class="form-group">
                        <input type="text" class="form-control mb-2" id="message" name="message" placeholder="Enter your message">
                        <button type="submit" class="btn btn-primary">Send</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script type="module">
        document.getElementById('messageForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent the default form submission

            const messageInput = document.getElementById('message');
            const customerPhoneInput = document.getElementById('customer_phone');

            const message = messageInput.value;
            const customer_phone = customerPhoneInput.value;
            // console.log(message, customer_phone);
            fetch('{{ route('send.message.admin') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ message: message, customer_phone: customer_phone })
            }).then(response => response.json())
              .then(data => {
                  console.log(data);
                  messageInput.value = '';
              }).catch(error => {
                  console.error('Error:', error);
              });
        });

        window.Echo.channel('live-chat').listen('SendMessage', (e) => {
            console.log(e);
            const chatDiv = document.getElementById('chat');
            const newMessage = document.createElement('div');
            newMessage.classList.add('d-flex', e.message.is_admin ? 'justify-content-end' : 'justify-content-start', 'mb-2');
            newMessage.innerHTML = `
                <div class="p-3 ${e.message.is_admin ? 'bg-primary text-white' : 'bg-light'} rounded">
                    <p class="mb-0"><strong>${e.message.name}</strong></p>
                    <p class="mb-0">${e.message.message}</p>
                    <p class="mb-0 text-dark"><small>${new Date(e.message.created_at).toLocaleTimeString('en-ID', { timeZone: 'Asia/Jakarta', month: 'short', day: '2-digit', hour: '2-digit',  minute: '2-digit' })}</small></p>
                    </div>`;
                    chatDiv.appendChild(newMessage);
                });
                </script>

    {{-- <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> --}}
</body>
</html>
