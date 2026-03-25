<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Gestion des utilisateurs - Bibliothèque</title>
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

/* Table */
table { width:100%; border-collapse:collapse; background:white; border-radius:10px; overflow:hidden; box-shadow:0 4px 8px rgba(0,0,0,0.1); margin-bottom:30px; }
th, td { padding:12px 16px; text-align:left; }
th { background:#1e90ff; color:white; }
tr:nth-child(even){background:#f9f9f9;}
tr:hover{background:#e6f0ff;}

/* Buttons */
.button { display:inline-block; background:#1e90ff; color:white; padding:6px 12px; border-radius:5px; text-decoration:none; font-weight:bold; transition:0.3s; cursor:pointer; }
.button:hover { background:#104e8b; }
.button-delete { background:#ff4d4f; }
.button-delete:hover { background:#c41f1f; }

.select-role { padding:5px; border-radius:5px; border:1px solid #ccc; }
form.inline { display:inline; }
</style>
</head>
<body>

<div class="sidebar">
    <h2>Admin</h2>
    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
    <a href="{{ route('users.index') }}">Utilisateurs</a>
    <a href="{{ route('admin.borrows') }}">Emprunts</a>
    <a href="{{ route('logout') }}">Déconnexion</a>
</div>

<div class="main-content">
<header>
    <h1>Gestion des utilisateurs</h1>
</header>

<table>
<thead>
<tr>
    <th>ID</th>
    <th>Nom</th>
    <th>Email</th>
    <th>Rôle</th>
    <th>Actions</th>
</tr>
</thead>
<tbody>
@forelse($users as $user)
<tr>
    <td>{{ $user->id }}</td>
    <td>{{ $user->name }}</td>
    <td>{{ $user->email }}</td>
    <td>
        <!-- Formulaire pour changer le rôle -->
        <form action="{{ route('users.update', $user->id) }}" method="POST" class="inline">
            @csrf
            @method('PUT')
            <select name="role" class="select-role">
                <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>Utilisateur</option>
                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
            </select>
            <button type="submit" class="button">Modifier</button>
        </form>
    </td>
    <td>
        <!-- Formulaire pour supprimer l'utilisateur -->
        <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="button button-delete">Supprimer</button>
        </form>
    </td>
</tr>
@empty
<tr><td colspan="5" style="text-align:center;">Aucun utilisateur</td></tr>
@endforelse
</tbody>
</table>

</div>

</body>
</html>
