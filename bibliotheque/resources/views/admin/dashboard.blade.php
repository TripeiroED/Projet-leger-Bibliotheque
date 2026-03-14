<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Dashboard Admin - Bibliothèque</title>
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

/* Cards */
.cards { display:grid; grid-template-columns: repeat(auto-fit,minmax(200px,1fr)); gap:20px; margin-bottom:40px; }
.card { background:white; padding:20px; border-radius:10px; box-shadow:0 4px 8px rgba(0,0,0,0.1); text-align:center; transition:0.3s; }
.card:hover { transform: translateY(-5px); box-shadow:0 8px 16px rgba(0,0,0,0.2); }
.card h3 { font-size:1.3rem; color:#1e90ff; margin-bottom:10px; }
.card p { font-size:1.5rem; font-weight:bold; }

/* Table */
table { width:100%; border-collapse:collapse; background:white; border-radius:10px; overflow:hidden; box-shadow:0 4px 8px rgba(0,0,0,0.1); margin-bottom:30px; }
th, td { padding:12px 16px; text-align:left; }
th { background:#1e90ff; color:white; }
tr:nth-child(even){background:#f9f9f9;}
tr:hover{background:#e6f0ff;}
.button { display:inline-block; background:#1e90ff; color:white; padding:6px 12px; border-radius:5px; text-decoration:none; font-weight:bold; transition:0.3s; }
.button:hover { background:#104e8b; }
.button-delete { background:#ff4d4f; }
.button-delete:hover { background:#c41f1f; }

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
    <a href="#">Emprunts</a>
    <a href="#">Favoris</a>
    <a href="{{ route('logout') }}">Déconnexion</a>
</div>

<div class="main-content">
<header>
    <h1>Dashboard Admin</h1>
</header>

{{-- Stats --}}
<div class="cards">
    <div class="card">
        <h3>Livres</h3>
        <p>{{ $bookCount }}</p>
    </div>
    <div class="card">
        <h3>Utilisateurs</h3>
        <p>{{ $userCount }}</p>
    </div>
    <div class="card">
        <h3>Livres Disponibles</h3>
        <p>{{ $availableBooks }}</p>
    </div>
    <div class="card">
        <h3>Livres Empruntés</h3>
        <p>{{ $borrowedBooks }}</p>
    </div>
</div>

{{-- Livres récents --}}
<h2>Livres récents</h2>
<a href="{{ route('books.create') }}" class="button">Ajouter un livre</a>
<table>
<thead>
<tr>
    <th>ID</th>
    <th>Titre</th>
    <th>Auteur</th>
    <th>Prix</th>
    <th>Actions</th>
</tr>
</thead>
<tbody>
@forelse($recentBooks as $book)
<tr>
    <td>{{ $book->id }}</td>
    <td>{{ $book->title }}</td>
    <td>{{ $book->author }}</td>
    <td>{{ $book->price }} €</td>
    <td>
        <a href="{{ route('books.edit', $book->id) }}" class="button">Éditer</a>
        <form action="{{ route('books.destroy', $book->id) }}" method="POST" style="display:inline;">
            @csrf
            @method('DELETE')
            <button type="submit" class="button button-delete">Supprimer</button>
        </form>
    </td>
</tr>
@empty
<tr><td colspan="5" style="text-align:center;">Aucun livre récent</td></tr>
@endforelse
</tbody>
</table>

{{-- Utilisateurs récents --}}
<h2>Utilisateurs récents</h2>
<table>
<thead>
<tr>
    <th>ID</th>
    <th>Nom</th>
    <th>Email</th>
    <th>Rôle</th>
</tr>
</thead>
<tbody>
@forelse($recentUsers as $user)
<tr>
    <td>{{ $user->id }}</td>
    <td>{{ $user->name }}</td>
    <td>{{ $user->email }}</td>
    <td>{{ ucfirst($user->role) }}</td>
</tr>
@empty
<tr><td colspan="4" style="text-align:center;">Aucun utilisateur récent</td></tr>
@endforelse
</tbody>
</table>

</div>

</body>
</html>
