<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Profil</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background:#f0f2f5; margin:0; padding:0; }
        .container { max-width:500px; margin:50px auto; background:white; padding:30px; border-radius:10px; box-shadow:0 4px 12px rgba(0,0,0,0.1); }
        h2 { color:#1e90ff; margin-bottom:20px; text-align:center; }
        form input { width:100%; padding:10px; margin-bottom:15px; border-radius:5px; border:1px solid #ccc; }
        form button { width:100%; padding:10px; background:#1e90ff; color:white; border:none; border-radius:5px; font-weight:bold; cursor:pointer; transition:0.3s; }
        form button:hover { background:#104e8b; }
        .success { color:green; margin-bottom:10px; text-align:center; font-weight:bold; }
        .error { color:red; margin-bottom:10px; text-align:center; font-weight:bold; }
        a { text-decoration:none; color:#1e90ff; display:block; text-align:center; margin-top:15px; }
    </style>
</head>
<body>

<div class="container">
    <h2>Modifier Profil</h2>

    @if(session('success')) <p class="success">{{ session('success') }}</p> @endif
    @if(session('error')) <p class="error">{{ session('error') }}</p> @endif

    <form method="POST" action="{{ url('/profile/update') }}">
        @csrf
        <label>Nom :</label>
        <input type="text" name="name" value="{{ old('name', $user->name) }}" required>
        
        <label>Mot de passe (laisser vide pour ne pas changer) :</label>
        <input type="password" name="password">
        
        <label>Confirmer mot de passe :</label>
        <input type="password" name="password_confirmation">
        
        <button type="submit">Mettre à jour</button>
    </form>

    <a href="{{ url('/profile') }}">Retour au profil</a>
</div>

@include('partials.footer')

</body>
</html>
