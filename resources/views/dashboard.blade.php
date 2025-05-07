<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #27548A;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background: white;
            padding: 35px;
            border-radius: 10px;
            width: 500px;
            box-shadow: 0 0 10px
        }
        input {
            width: 95%;
            padding: 8px;
            margin-bottom: 10px;
            margin-top: 4px;
            border-radius: 10px;
        }
        button {
            padding: 10px;
            margin-top: 10px;
            border: none;
            background: #333;
            color: white;
            cursor: pointer;
            border-radius: 5px;
        }

        button:hover {
            background-color: #27548A;
        }

        .success {
            color: green;
            margin-bottom: 10px;
        }
    </style>
    <script>
        function enableEdit() {
            document.querySelectorAll('input').forEach(input => input.removeAttribute('readonly'));
            document.getElementById('saveBtn').disabled = false;
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Welcome, {{ $user->name }}</h2>
        
        @if (Session::has('user_loggedin'))
            <script>
                swal("Success!", "{{ Session::get('user_loggedin') }}", "success", {
                    button: "OK",
                });
            </script>
        @endif

        <form method="POST" action="{{ route('dashboard.update') }}" onsubmit="return confirm('Are you sure you want to save changes?');">
            @csrf
            <label>Name</label>
            <input type="text" name="name" value="{{ $user->name }}" readonly>

            <label>Username</label>
            <input type="text" name="username" value="{{ $user->username }}" readonly>

            <label>Password</label>
            <input type="password" name="password" placeholder="Leave blank to keep current" readonly>

            <button type="button" onclick="enableEdit()" >Edit</button>
            <button type="submit" id="saveBtn" disabled>Save</button>
        </form>

        @if (Session::has('updateSuccess'))
            <script>
                swal("Success!", "{{ Session::get('user_loggedin') }}", "success", {
                    button: "OK",
                });
            </script>
        @endif

        <form method="POST" action="{{ route('user.delete') }}" onsubmit="return confirm('Are you sure you want to delete your account? This action cannot be undone.');">
            @csrf
            <button type="submit">Delete Account</button>
        </form>

        <form method="POST" action="/logout" style="margin-top: 15px;">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </div>
</body>
</html>
