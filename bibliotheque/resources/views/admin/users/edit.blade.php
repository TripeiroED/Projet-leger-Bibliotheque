<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier le rôle - Admin</title>
    <style>
        body { font-family: Arial, sans-serif; background:#f0f0f0; padding:40px; }
        .container { background:#fff; padding:20px; border-radius:8px; max-width:400px; margin:auto; box-shadow:0 4px 8px rgba(0,0,0,0.1); }
        h1 { color:#1e90ff; margin-bottom:20px; font-size:1.5rem; text-align:center; }
        label { display:block; margin-bottom:5px; font-weight:bold; }
        select { width:100%; padding:8px; margin-bottom:20px; border-radius:5px; border:1px solid #ccc; }
        button { background:#1e90ff; color:#fff; padding:10px 15px; border:none; border-radius:5px; cursor:pointer; width:100%; }
        button:hover { background:#104e8b; }
        a { display:block; text-align:center; margin-top:15px; color:#333; text-decoration:none; }
        a:hover { text-decoration:underline; }
        .success { background:#d4edda; color:#155724; padding:10px; border-radius:5px; margin-bottom:15px; text-align:center; }
        .error { background:#f8d7da; color:#721c24; padding:10px; border-radius:5px; margin-bottom:15px; text-align:center; }
    </style>
</head>
<body>

<div class="container">
    <h1>Modifier le rôle de {{ $user->name }}</h1>

    @if(session('success'))
        <div class="success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="error">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <label for="role">Rôle :</label>
        <select name="role" id="role" required>
            <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>Utilisateur</option>
            <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Administrateur</option>
        </select>

        <button type="submit">Mettre à jour</button>
    </form>

    <a href="{{ route('users.index') }}">← Retour à la liste des utilisateurs</a>
</div>

</body>
</html>
