<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])


    <title>Login</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

</head>

<body class="flex justify-center items-center h-screen m-0 font-sans bg-[#27548A]">

    <div class="border-2 p-8 bg-white rounded-xl text-center w-full max-w-sm">
        <h2 class="mb-5 text-4xl font-bold">LOGIN</h2>
        <form id="login-form">
            @csrf
            <input name="username" type="text" placeholder="Username" required
                class="w-[95%] py-2 px-2 mb-2.5 mt-1 rounded-[10px] border border-gray-300 bg-gray-100" />
            <input name="password" type="password" placeholder="Password" required
                class="w-[95%] py-2 px-2 mb-2.5 mt-1 rounded-[10px] border border-gray-300 bg-gray-100" />
            <button type="submit"
                class="px-5 py-2 w-[100px] text-base bg-gray-800 text-white rounded-xl hover:bg-[#27548A] mt-2 cursor-pointer">
                Log In
            </button>
        </form>
    </div>

    <script>
        document.getElementById('login-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('/apiLogin', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json'
                    },
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
