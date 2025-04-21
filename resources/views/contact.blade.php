<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Ielts</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="antialiased">
    <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center">
        <div class="max-w-7xl mx-auto p-6 lg:p-8">
            <h2 class="text-center text-black font-bold" style="font-size: 30px;">お問い合わせ</h2>

            <!-- success message -->
            @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mt-4"
                role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            @endif

            <form action="{{ route('contact.submit') }}" method="POST" class="mt-8 space-y-4 max-w-3xl">
                @csrf
                <input type='text' name="name" placeholder='名前'
                    value="{{ old('name') }}"
                    class="w-full rounded-md py-3 px-4 text-gray-800 bg-gray-100 focus:bg-transparent text-sm outline-blue-500" />

                @if($errors->has('name'))
                <span class="text-red-500 text-sm">{{ $errors->first('name') }}</span>
                @endif

                <input type='email' name="email" placeholder='メールアドレス'
                    value="{{ old('email') }}"
                    class="w-full rounded-md py-3 px-4 text-gray-800 bg-gray-100 focus:bg-transparent text-sm outline-blue-500" />

                @if($errors->has('email'))
                <span class="text-red-500 text-sm">{{ $errors->first('email') }}</span>
                @endif

                <input type='text' name="phone" placeholder='電話'
                    value="{{ old('phone') }}"
                    class="w-full rounded-md py-3 px-4 text-gray-800 bg-gray-100 focus:bg-transparent text-sm outline-blue-500" />

                @if($errors->has('phone'))
                <span class="text-red-500 text-sm">{{ $errors->first('phone') }}</span>
                @endif

                <textarea placeholder='メッセージ' rows="6" name="message"
                    class="w-full rounded-md px-4 text-gray-800 bg-gray-100 focus:bg-transparent text-sm pt-3 outline-blue-500">{{ old('message') }}</textarea>

                @if($errors->has('message'))
                <span class="text-red-500 text-sm">{{ $errors->first('message') }}</span>
                @endif

                <button type='submit'
                    class="text-white bg-blue-500 hover:bg-blue-600 tracking-wide rounded-md text-sm px-4 py-3 w-full">送信</button>
            </form>

            <div class="flex justify-center mt-10 px-0 sm:items-center sm:justify-between">
                <div class="text-center text-sm text-gray-500 sm:text-right">
                    Ielts Version 1.0.0
                </div>
            </div>
        </div>
    </div>
</body>

</html>
