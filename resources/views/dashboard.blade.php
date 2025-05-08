<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
</head>

<body>
    <div class="container">
        <h2>Welcome! {{ $user->name }}</h2>

        <form id="update-form">
            @csrf
            <label>Name</label>
            <input type="text" name="name" value="{{ $user->name }}" readonly>

            <label>Username</label>
            <input type="text" name="username" value="{{ $user->username }}" readonly>

            <label>Password</label>
            <input type="password" name="password" placeholder="Leave blank to keep current" readonly>

            <button type="button" onclick="enableEdit()">Edit</button>
            <button type="submit" id="saveBtn" disabled>Save</button>
        </form>
        <form id="delete-form">
            @csrf
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <button type="submit">Delete Account</button>
        </form>

        <form id="logout-form" style="margin-top: 15px;">
            @csrf
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <button type="submit">Logout</button>
        </form>
    </div>

    <script>
        function enableEdit() {
            document.querySelectorAll('#update-form input').forEach(input => input.removeAttribute('readonly'));
            document.getElementById('saveBtn').disabled = false;
        }

        document.getElementById('update-form').addEventListener('submit', function(e) {
            e.preventDefault();
            if (!confirm("Are you sure you want to save changes?")) return;

            const formData = new FormData(this);

            fetch('/apiUpdate', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.user) {
                        swal("Success!", data.message, "success");
                        document.getElementById('saveBtn').disabled = true;
                        document.querySelector('h2').textContent = `Welcome! ${data.user.name}`;
                    } else {
                        swal("Error!", "Update failed", "error");
                    }
                });
        });

        document.getElementById('delete-form').addEventListener('submit', function(e) {
            e.preventDefault();
            if (!confirm("Are you sure you want to delete your account?")) return;

            fetch('/apiDeleteUser', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                })
                .then(res => res.json())
                .then(data => {
                    swal("Deleted!", data.message, "success")
                        .then(() => window.location.href = '/');
                });
        });

        document.getElementById('logout-form').addEventListener('submit', function(e) {
            e.preventDefault();
            fetch('/apiLogout', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                })
                .then(res => res.json())
                .then(data => {
                    swal("Logged out", data.message, "success")
                        .then(() => window.location.href = '/login');
                });
        });
    </script>
</body>

</html>
