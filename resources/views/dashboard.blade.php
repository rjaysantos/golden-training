<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <title>Dashboard</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

</head>

<body class="font-sans bg-[#27548A] flex justify-center items-center h-screen">
    <div class="container bg-white p-[35px] rounded-[10px] w-[500px] shadow-md">
        <h2 class="text-2xl">Welcome! {{ $user->name }}</h2>

        <form id="update-form" class="mt-4">
            @csrf
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="_method" value="PATCH">

            <label class="block">Name</label>
            <input type="text" name="name" value="{{ $user->name }}" readonly
                class="w-[95%] py-2 px-2 mb-2.5 mt-1 rounded-[10px] border border-gray-300 bg-gray-100">
            <div class="error-message text-red-500 text-xs"></div>

            <label class="block">Username</label>
            <input type="text" name="username" value="{{ $user->username }}" readonly
                class="w-[95%] py-2 px-2 mb-2.5 mt-1 rounded-[10px] border border-gray-300 bg-gray-100">
            <div class="error-message text-red-500 text-xs"></div>

            <label class="block">Password</label>
            <input type="password" name="password" placeholder="Leave blank to keep current" readonly
                class="w-[95%] py-2 px-2 mb-2.5 mt-1 rounded-[10px] border border-gray-300 bg-gray-100">
            <div class="error-message text-red-500 text-xs"></div>

            <button type="button" id="editBtn"
                class="py-2.5 px-4 mt-2.5 border-none bg-gray-800 text-white cursor-pointer rounded-[5px] hover:bg-[#27548A]">Edit</button>
            <button type="submit" id="saveBtn"
                class="hidden py-2.5 px-4 mt-2.5 border-none bg-gray-800 text-white cursor-pointer rounded-[5px] hover:bg-[#27548A]">Save</button>
        </form>
        <form id="delete-form" class="mt-4">
            @csrf
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="hidden" name="_method" value="DELETE">
            <button type="submit"
                class="py-2.5 px-4 mt-2.5 border-none bg-gray-800 text-white cursor-pointer rounded-[5px] hover:bg-[#27548A]">Delete
                Account</button>
        </form>

        <form id="logout-form" class="mt-4">
            @csrf
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <button type="submit"
                class="py-2.5 px-4 mt-2.5 border-none bg-gray-800 text-white cursor-pointer rounded-[5px] hover:bg-[#27548A]">Logout</button>
        </form>
    </div>

    <script>
        document.getElementById('editBtn').addEventListener('click', function() {
            const inputs = document.querySelectorAll(
                '#update-form input[type="text"], #update-form input[type="password"]');

            inputs.forEach(input => {
                input.removeAttribute('readonly');
            });

            document.getElementById('saveBtn').style.display = 'inline-block';
            document.getElementById('editBtn').style.display = 'none';
        });

        document.getElementById('update-form').addEventListener('submit', function(e) {
            e.preventDefault();
            if (!confirm("Are you sure you want to save changes?")) return;

            const formData = new FormData(this);

            fetch('/apiUpdate', {
                    method: 'post',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: formData
                })
                .then(res => {
                    if (!res.ok) {
                        return res.json().then(err => Promise.reject(err));
                    }
                    return res.json();
                })
                .then(data => {
                    if (data.user) {
                        swal("Success!", data.message, "success");
                        const inputs = document.querySelectorAll(
                            '#update-form input[type="text"], #update-form input[type="password"]');
                        inputs.forEach(input => {
                            input.setAttribute('readonly', true);
                        });
                        document.querySelector('h2').textContent = `Welcome! ${data.user.name}`;

                        document.getElementById('editBtn').style.display = 'inline-block';
                        document.getElementById('saveBtn').style.display = 'none';
                    } else {
                        swal("Error!", data.message || "Update failed", "error");
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    swal("Error!", error.message || "Update failed", "error");
                });
        });

        document.getElementById('delete-form').addEventListener('submit', function(e) {
            e.preventDefault();
            if (!confirm("Are you sure you want to delete your account?")) return;

            fetch('/apiDeleteUser', {
                    method: 'DELETE',
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
