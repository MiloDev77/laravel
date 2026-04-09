<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    @if (session()->has('error'))
        <div class="failed" style="background-color: red; color: white;">
            {{ session('error') }}
        </div>
    @endif

    <form method="POST" action="/login">
        @csrf
        <input type="text" placeholder="Passphrase" name="passphrase" />
        {{-- <textarea name="gtin" rows="5" cols="40"></textarea> --}}
        <button type="submit">Send</button>
    </form>
</body>

</html>
