<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - One Movies</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white flex items-center justify-center h-screen">
    <div class="bg-gray-800 p-8 rounded-lg shadow-lg w-96">
        <h2 class="text-2xl font-bold mb-6 text-center text-orange-500">DAFTAR AKUN</h2>
        
        @if($errors->any())
            <div class="bg-red-500 text-white p-3 rounded mb-4 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('register.post') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm mb-2">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full p-2 rounded bg-gray-700 border border-gray-600 focus:outline-none focus:border-orange-500">
            </div>
            <div class="mb-4">
                <label class="block text-sm mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full p-2 rounded bg-gray-700 border border-gray-600 focus:outline-none focus:border-orange-500">
            </div>
            <div class="mb-4">
                <label class="block text-sm mb-2">Password</label>
                <input type="password" name="password" required class="w-full p-2 rounded bg-gray-700 border border-gray-600 focus:outline-none focus:border-orange-500">
            </div>
            <div class="mb-6">
                <label class="block text-sm mb-2">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required class="w-full p-2 rounded bg-gray-700 border border-gray-600 focus:outline-none focus:border-orange-500">
            </div>
            <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white p-2 rounded font-bold transition">Daftar</button>
        </form>
        <p class="mt-4 text-center text-sm text-gray-400">Sudah punya akun? <a href="{{ route('login') }}" class="text-orange-400 hover:underline">Login di sini</a></p>
    </div>
</body>
</html>