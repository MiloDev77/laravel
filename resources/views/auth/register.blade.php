<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <form action="{{ route('register.post') }}" enctype="multipart/form-data" method="POST">
        @csrf
        <input type="text" name="name" placeholder="Nama" />
        <input type="text" name="username" placeholder="Username" />
        <input type="password" name="password" placeholder="Password" />
        <input type="file" name="avatar_path" placeholder="Gambar" />

        <button type="submit">Send</button>
    </form>
</body>

</html>
