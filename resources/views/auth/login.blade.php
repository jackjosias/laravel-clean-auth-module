@extends('auth.layout')

@section('title', 'Connexion')

@section('content')
<h1>Connexion</h1>

@error('credentials') <p class="error">{{ $message }}</p> @enderror

<form method="POST" action="{{ route('login') }}">
    @csrf

    <label for="email">Adresse email</label>
    <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required>
    @error('email') <p class="error">{{ $message }}</p> @enderror

    <label for="password">Mot de passe</label>
    <input id="password" name="password" type="password" autocomplete="current-password" required>
    @error('password') <p class="error">{{ $message }}</p> @enderror

    <label style="display:flex; align-items:center; gap:.5rem; margin-top:1rem; font-weight:normal">
        <input name="remember" type="checkbox" value="1" style="width:auto">
        Rester connecté
    </label>

    <button type="submit">Se connecter</button>
</form>

<p style="margin-top:1rem">Pas encore de compte ? <a href="{{ route('register') }}">S'inscrire</a></p>
@endsection
