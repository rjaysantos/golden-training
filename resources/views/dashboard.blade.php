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
        <h2 class="text-2xl">Welcome! <span id="userDisplayName"></span></h2>

        <form id="update-form" class="mt-4">
            @csrf
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <label class="block">Name</label>
            <input type="text" name="name" id="form-name" readonly
                class="w-[95%] py-2 px-2 mb-2.5 mt-1 rounded-[10px] border border-gray-300 bg-gray-100">
            <div class="error-message text-red-500 text-xs"></div>

            <label class="block">Username</label>
            <input type="text" name="username" id="form-username" readonly
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
        document.addEventListener('DOMContentLoaded', () => {
            const editBtn = document.getElementById('editBtn');
            const saveBtn = document.getElementById('saveBtn');
            const inputs = document.querySelectorAll('input[readonly]');

            editBtn.addEventListener('click', function() {
                inputs.forEach(input => {
                    input.removeAttribute('readonly');
                    input.classList.remove('bg-gray-100');
                    input.classList.add('bg-white');
                });

                saveBtn.classList.remove('hidden');
                editBtn.classList.add('hidden');
            });
        });

        document.addEventListener('DOMContentLoaded', () => {
            const api_token = localStorage.getItem('api_token');
            const currentUser = localStorage.getItem('name');
            const currentUsername = localStorage.getItem('username');

            if (!api_token) {
                window.location.href = '/login';
                return;
            }

            document.getElementById('userDisplayName').textContent = currentUser || '';
            document.getElementById('form-name').value = currentUser || '';
            document.getElementById('form-username').value = currentUsername || '';

            fetch('/apiGetAuthenticatedUser', {
                    method: 'GET',
                    headers: {
                        'Authorization': 'Bearer ' + api_token,
                        'Accept': 'application/json',
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.username) {
                        document.getElementById('userDisplayName').textContent = data.name || '';
                        document.getElementById('form-name').value = data.name || '';
                        document.getElementById('form-username').value = data.username || '';

                        localStorage.setItem('name', data.name || '');
                        localStorage.setItem('username', data.username || '');
                    }
                })
                .catch(() => {
                    localStorage.clear();
                    swal("Error!", "Failed to fetch user data.", "error")
                        .then(() => {
                            window.location.href = '/login';
                        });
                });
        });

        document.getElementById('update-form').addEventListener('submit', function(e) {
            e.preventDefault();

            const api_token = localStorage.getItem('api_token');
            const formData = new FormData(this);
            const saveBtn = document.getElementById('saveBtn');
            const editBtn = document.getElementById('editBtn');

            saveBtn.classList.remove('hidden');
            editBtn.classList.add('hidden');

            const jsonData = {};
            formData.forEach((value, key) => {
                jsonData[key] = value;
            });

            fetch('/api/apiUpdate', {
                    method: 'PATCH',
                    headers: {
                        'Authorization': 'Bearer ' + api_token,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(jsonData)
                })
                .then(function(response) {
                    return response.json();
                })
                .then(function(data) {
                    if (data.user) {
                        const name = document.getElementById('form-name').value;
                        const username = document.getElementById('form-username').value;
                        localStorage.setItem('name', name);
                        localStorage.setItem('username', username);
                        document.getElementById('userDisplayName').textContent = name;

                        const passwordField = document.getElementById('form-password');
                        if (passwordField) {
                            passwordField.value = '';
                        }

                        const inputs = document.querySelectorAll('input[type="text"], input[type="password"]');
                        for (let i = 0; i < inputs.length; i++) {
                            inputs[i].readOnly = true;
                            inputs[i].classList.add('bg-gray-100');
                        }

                        saveBtn.classList.add('hidden');
                        editBtn.classList.remove('hidden');

                        swal("Success!", data.message, "success");
                    } else {
                        swal("Error!", data.message || "Something went wrong. Please try again.", "error");
                        saveBtn.disabled = false;
                        saveBtn.textContent = 'Save Changes';
                    }
                })
                .catch(function(error) {
                    console.error('Error:', error);
                    swal("Error!", "Failed to update user data.", "error");
                    saveBtn.disabled = hidden;
                    saveBtn.textContent = 'Save Changes';
                });
        });

        document.getElementById('delete-form').addEventListener('submit', function(e) {
            e.preventDefault();
            if (!confirm("Are you sure you want to delete your account?")) return;

            const username = localStorage.getItem('username');

            fetch('/api/apiDeleteUser', {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        username: username,
                    }),
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        swal("Deleted!", data.message, "success")
                            .then(() => {
                                localStorage.clear();
                                window.location.href = '/';
                            });
                    }
                })
        });

        document.getElementById('logout-form').addEventListener('submit', function(e) {
            e.preventDefault();

            const api_token = localStorage.getItem('api_token');

            fetch('/api/apiLogout', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': 'Bearer ' + api_token,
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(res => res.json())
                .then(data => {
                    localStorage.clear();

                    swal("Logged out", data.message, "success")
                        .then(() => {
                            window.location.replace('/login');
                        });
                })
                .catch(error => {
                    console.error('Logout error:', error);
                    swal("Error", "Failed to log out.", "error");
                });
        });
    </script>
</body>

</html>
