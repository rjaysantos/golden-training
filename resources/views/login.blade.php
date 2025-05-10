<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f9f9f9;
            font-family: Arial, sans-serif;
            background-color: #27548A;
        }

        .form-container {
            border: 2px solid;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            text-align: center;
        }

        input {
            display: block;
            width: 100%;
            margin: 5px 0;
            margin-bottom: 10px;
            padding: 4px;
            font-size: 16px;
            border-radius: 10px;
        }

        button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #333;
            color: #fff;
            border: none;
            cursor: pointer;
            margin-top: 10px;
            border-radius: 4px;
        }

        button:hover {
            background-color: #27548A;
        }

        h2 {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <div class="form-container">
        <h2>Login</h2>
        <form id="login-form">
            @csrf
            <input name="username" type="text" placeholder="Username" required>
            <input name="password" type="password" placeholder="Password" required>
            <button type="submit">Log In</button>
        </form>
        
    </div>

    <script>
        document.getElementById('login-form').addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('/apiLogin', {
                method: 'POST',
                headers: { 'Accept': 'application/json' },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.user) {
                    swal("Success!", data.message, "success")
                        .then(() => window.location.href = '/dashboard');
                } else {
                    swal("Error!", data.message || "Login failed", "error");
                }
            })
            .catch(() => {
                swal("Error!", "Something went wrong", "error");
            });
        });
    </script>
</body>
</html>