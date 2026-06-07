@extends('auth.layout')

@section('title', 'Inscription')

@section('content')
<h1>Créer un compte</h1>

<form method="POST" action="{{ route('register') }}">
    @csrf

    <label for="name">Nom complet</label>
    <input id="name" name="name" type="text" value="{{ old('name') }}" autocomplete="name" required>
    @error('name') <p class="error">{{ $message }}</p> @enderror

    <label for="email">Adresse email</label>
    <input id="email" name="email" type="email" value="{{ old('email') }}" autocomplete="email" required>
    @error('email') <p class="error">{{ $message }}</p> @enderror

    <label for="password">Mot de passe</label>
    <input id="password" name="password" type="password" autocomplete="new-password" required aria-describedby="strength-label">
    <div id="strength-bar"></div>
    <p id="strength-label" aria-live="polite"></p>
    @error('password') <p class="error">{{ $message }}</p> @enderror

    <label for="password_confirmation">Confirmer le mot de passe</label>
    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required>

    <button type="submit">S'inscrire</button>
</form>

<p style="margin-top:1rem">Déjà un compte ? <a href="{{ route('login') }}">Connexion</a></p>

<script src="{{ asset('js/password-strength.js') }}"></script>
@endsection
