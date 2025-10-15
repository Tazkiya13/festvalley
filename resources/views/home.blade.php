<!-- filepath: /home/demigod/Documents/live-chat/resources/views/login.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    @include('layouts.navbar')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <h2 class="text-center">User List</h2>
                <div>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>name</th>
                                <th>customer phone</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($messages as $message)
                            {{-- @if ($message->is_admin == 0) --}}
                                <tr>
                                    <td>{{ $message->name }}</td>

                                    <td> <a href="{{ route('chat', $message->customer_phone) }}">{{ $message->customer_phone }}</a></td>

                                </tr>
                            {{-- @endif --}}
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
