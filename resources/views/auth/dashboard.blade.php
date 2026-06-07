@extends('auth.layout')

@section('title', 'Dashboard')

@section('content')
<h1>Connecté</h1>
<p>Vous êtes authentifié avec succès.</p>

<form method="POST" action="{{ route('logout') }}" style="margin-top:1rem">
    @csrf
    <button type="submit">Se déconnecter</button>
</form>
@endsection
