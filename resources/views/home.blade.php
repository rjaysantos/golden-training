<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

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

    @if (Session::has('accountDeleted'))
        <script>
            swal("Deleted!", "{{ Session::get('accountDeleted') }}", "success", {
                button: "OK",
            });
        </script>
    @endif

    <div class="form-container">
        <h2>Register</h2>
        <form action="/register" method="POST">
            @csrf
            <input name="name" type="text" placeholder="Name">
            <input name="username" type="text" placeholder="Username">
            <input name="password" type="password" placeholder="Password">
            <button type="submit">Register</button>
        </form>
    </div>

</body>

</html> 