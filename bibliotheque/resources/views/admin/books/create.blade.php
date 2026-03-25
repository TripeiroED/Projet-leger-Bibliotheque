<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Ajouter un livre - Bibliothèque</title>
<style>
    body { font-family:'Segoe UI', sans-serif; background:#f0f2f5; margin:0; padding:0; }
    .container { max-width:600px; margin:50px auto; background:#fff; padding:30px; border-radius:10px; box-shadow:0 4px 8px rgba(0,0,0,0.1); }
    h2 { text-align:center; color:#1e90ff; margin-bottom:20px; }
    label { display:block; margin-top:15px; font-weight:bold; }
    input, textarea, select { width:100%; padding:10px; margin-top:5px; border:1px solid #ccc; border-radius:5px; }
    button { margin-top:20px; width:100%; background:#1e90ff; color:#fff; padding:10px; border:none; border-radius:5px; font-size:1rem; cursor:pointer; transition:0.3s; }
    button:hover { background:#104e8b; }
    .errors { background:#ffdddd; border-left:5px solid #ff4d4f; padding:10px; margin-bottom:20px; }
    .errors ul { margin:0; padding-left:20px; }
</style>
</head>
<body>

<div class="container">
    <h2>Ajouter un nouveau livre</h2>

    @if ($errors->any())
    <div class="errors">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('books.store') }}" method="POST">
        @csrf

        <label>Titre</label>
        <input type="text" name="title" value="{{ old('title') }}" required>

        <label>Auteur</label>
        <input type="text" name="author" value="{{ old('author') }}" required>

        <label>Description</label>
        <textarea name="description" rows="4">{{ old('description') }}</textarea>

        <label>Prix (€)</label>
        <input type="number" step="0.01" name="price" value="{{ old('price') }}" required>

        <label>Disponible ?</label>
        <select name="available">
            <option value="1" {{ old('available') == 1 ? 'selected' : '' }}>Oui</option>
            <option value="0" {{ old('available') == 0 ? 'selected' : '' }}>Non</option>
        </select>

        <button type="submit">Ajouter le livre</button>
    </form>
</div>

</body>
</html>
