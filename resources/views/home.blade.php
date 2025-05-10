<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>CRUD sample -raven</title>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #27548A;
            font-family: Arial, sans-serif;
        }

        .form-container {
            border: 2px solid;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            text-align: center;
            box-shadow: inset;
        }

        input {
            display: block;
            width: 100%;
            margin: 5px px;
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

<body style="display: flex; flex-direction: column; ">

    <div class="form-container">
        <h2>Register</h2>
        <form id="register-form">
            @csrf
            <input name="name" type="text" placeholder="Name" required>
            <input name="username" type="text" placeholder="Username" required>
            <input name="password" type="password" placeholder="Password" required>
            <button type="submit">Register</button>
        </form>
        <button onclick="window.location.href = '/login'"> Login </button>
    </div>

    <script>
        document.getElementById('register-form').addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);
    
            fetch('/apiRegister', {
                method: 'POST',
                headers: { 'Accept': 'application/json' },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.user) {
                    swal("Success!", data.message, "success")
                    .then(() => window.location.href = '/login');
                } else {
                    swal("Error!", data.message || "Registration failed", "error");
                }
            })
            .catch(() => {
                swal("Error!", "Something went wrong", "error");
            });
        });
    </script>

</body>

</html> 