<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Éditer Livre - Bibliothèque</title>
<style>
/* Reset & base */
* { margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI', sans-serif; }
body { display:flex; min-height:100vh; background:#f0f2f5; color:#333; }

/* Sidebar */
.sidebar {
    width:250px; background:#1e90ff; color:white; flex-shrink:0; display:flex; flex-direction:column; padding-top:20px;
}
.sidebar h2 { text-align:center; margin-bottom:30px; font-size:1.5rem; }
.sidebar a { display:block; color:white; padding:15px 20px; text-decoration:none; transition:0.3s; }
.sidebar a:hover { background:#104e8b; }

/* Main content */
.main-content { flex:1; padding:30px; }
header { display:flex; justify-content:space-between; align-items:center; margin-bottom:30px; }
header h1 { font-size:2rem; color:#1e90ff; }

/* Form */
form { background:white; padding:20px; border-radius:10px; box-shadow:0 4px 8px rgba(0,0,0,0.1); max-width:600px; }
form label { display:block; margin-top:15px; font-weight:bold; }
form input, form textarea, form select { width:100%; padding:10px; margin-top:5px; border:1px solid #ccc; border-radius:5px; }
form textarea { resize: vertical; min-height:100px; }
form button { margin-top:20px; background:#1e90ff; color:white; border:none; padding:10px 15px; border-radius:5px; cursor:pointer; transition:0.3s; }
form button:hover { background:#104e8b; }

/* Messages */
.success { color:green; margin-bottom:10px; }
.error { color:red; margin-bottom:5px; }

/* Responsive */
@media(max-width:768px){
    body { flex-direction:column; }
    .sidebar { width:100%; flex-direction:row; overflow-x:auto; }
    .sidebar a { flex-shrink:0; padding:15px 10px; }
}
</style>
</head>
<body>

<div class="sidebar">
    <h2>Admin</h2>
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <a href="{{ route('books.index') }}">Livres</a>
    <a href="{{ route('users.index') }}">Utilisateurs</a>
    <a href="{{ route('logout') }}">Déconnexion</a>
</div>

<div class="main-content">
<header>
    <h1>Éditer Livre</h1>
</header>

{{-- Messages --}}
@if(session('success'))
    <p class="success">{{ session('success') }}</p>
@endif

@if($errors->any())
    @foreach($errors->all() as $error)
        <p class="error">{{ $error }}</p>
    @endforeach
@endif

<form action="{{ route('books.update', $book->id) }}" method="POST">
    @csrf
    @method('PUT')

    <label>Titre :</label>
    <input type="text" name="title" value="{{ old('title', $book->title) }}" required>

    <label>Auteur :</label>
    <input type="text" name="author" value="{{ old('author', $book->author) }}" required>

    <label>Description :</label>
    <textarea name="description">{{ old('description', $book->description) }}</textarea>

    <label>Prix (€) :</label>
    <input type="number" step="0.01" name="price" value="{{ old('price', $book->price) }}" required>

    <label>Disponible :</label>
    <select name="available">
        <option value="1" {{ $book->available ? 'selected' : '' }}>Oui</option>
        <option value="0" {{ !$book->available ? 'selected' : '' }}>Non</option>
    </select>

    <button type="submit">Mettre à jour</button>
</form>

<a href="{{ route('books.index') }}" style="display:inline-block;margin-top:20px;color:#1e90ff;">← Retour à la liste</a>
</div>

</body>
</html>
