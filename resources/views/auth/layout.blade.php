<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Auth Module')</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: system-ui, sans-serif; padding: 2rem; max-width: 420px; margin: auto; }
        h1 { font-size: 1.5rem; line-height: 1.2; margin-bottom: 1rem; }
        label { display: block; margin-top: 1rem; font-size: .9rem; font-weight: 600; }
        input { width: 100%; padding: .6rem; margin-top: .3rem; border: 1px solid #ccc; border-radius: 4px; }
        button[type=submit] { margin-top: 1.5rem; width: 100%; padding: .75rem; cursor: pointer; }
        .error { color: #c0392b; font-size: .85rem; margin-top: .25rem; }
        .success { color: #1f7a3a; font-size: .85rem; margin: 0 0 1rem; }
        #strength-bar { height: 6px; width: 0; border-radius: 3px; transition: width .3s, background .3s; margin-top: .4rem; }
        #strength-label { font-size: .8rem; margin-top: .2rem; min-height: 1rem; }
    </style>
</head>
<body>
    @if(session('success'))
        <p class="success">{{ session('success') }}</p>
    @endif

    @error('session')
        <p class="error">{{ $message }}</p>
    @enderror

    @yield('content')
</body>
</html>
