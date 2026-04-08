<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Emprunts - Bibliothèque</title>
<style>
* { margin:0; padding:0; box-sizing:border-box; font-family:'Segoe UI', sans-serif; }
body { display:flex; min-height:100vh; background:#f0f2f5; color:#333; }

/* Sidebar */
.sidebar { width:250px; background:#1e90ff; color:white; flex-shrink:0; display:flex; flex-direction:column; padding-top:20px; }
.sidebar h2 { text-align:center; margin-bottom:30px; font-size:1.5rem; }
.sidebar a { display:block; color:white; padding:15px 20px; text-decoration:none; transition:0.3s; }
.sidebar a:hover { background:#104e8b; }

/* Main content */
.main-content { flex:1; padding:30px; }
header { display:flex; justify-content:space-between; align-items:center; margin-bottom:30px; }
header h1 { font-size:2rem; color:#1e90ff; }

/* Table */
table { width:100%; border-collapse:collapse; background:white; border-radius:10px; overflow:hidden; box-shadow:0 4px 8px rgba(0,0,0,0.1); }
th, td { padding:12px 16px; text-align:left; }
th { background:#1e90ff; color:white; }
tr:nth-child(even){background:#f9f9f9;}
tr:hover{background:#e6f0ff;}
.button { display:inline-block; background:#1e90ff; color:white; padding:6px 12px; border-radius:5px; text-decoration:none; font-weight:bold; transition:0.3s; cursor:pointer; }
.button:hover { background:#104e8b; }
</style>
</head>
<body>

<div class="sidebar">
    <h2>Admin</h2>
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <a href="{{ route('users.index') }}">Utilisateurs</a>
    <a href="{{ route('books.index') }}">Livres</a>
    <a href="{{ route('admin.borrows') }}">Emprunts</a>
    <a href="{{ route('logout') }}">Déconnexion</a>
</div>

<div class="main-content">
<header>
    <h1>Liste des emprunts</h1>
</header>

<table>
<thead>
<tr>
    <th>ID</th>
    <th>Utilisateur</th>
    <th>Email</th>
    <th>Livre</th>
    <th>Date emprunt</th>
    <th>Date retour</th>
    <th>Statut</th>
</tr>
</thead>
<tbody>
@forelse($borrows as $borrow)
<tr>
    <td>{{ $borrow->id }}</td>
    <td>{{ $borrow->user->name }}</td>
    <td>{{ $borrow->user->email }}</td>
    <td>{{ $borrow->book->title }}</td>
    <td>
        {{ $borrow->borrowed_at ? $borrow->borrowed_at->format('d/m/Y H:i') : '-' }}
    </td>
    <td>
        {{ $borrow->returned_at ? $borrow->returned_at->format('d/m/Y H:i') : '-' }}
    </td>

    <td>
        @if($borrow->returned_at)
            <span style="color:green;font-weight:bold;">Retourné</span>
        @else
            <span style="color:red;font-weight:bold;">En cours</span>
        @endif
    </td>
</tr>
@empty
<tr><td colspan="6" style="text-align:center;">Aucun emprunt</td></tr>
@endforelse
</tbody>
</table>

</div>

</body>
</html>
