<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data"">
        @csrf
        @method('PUT')
        @if ($user->avatar_path)
            <img src="/storage/{{ $user->avatar_path }}">
            <button type="submit" onclick="removeAvatar()">remove avatar</button>
        @endif
        <input type="text" name="name">
        <input type="text" name="username">
        <input type="file" name="avatar_path">
        <input type="hidden" name="remove_avatar" id="remove_avatar" value="0">
        <button type="submit">Send</button>
    </form>
    <script>
        function removeAvatar() {
            document.getElementById('remove_avatar').value = '1';

            document.querySelector('img').style.display = 'hidden';
        }
    </script>
</body>

</html>
