<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des Livres - Admin</title>
    <style>
        /* Reset & base */
        * { margin:0; padding:0; box-sizing:border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { display:flex; min-height:100vh; background:#f0f2f5; color:#333; }

        /* Sidebar */
        .sidebar {
            width:250px;
            background:#1e90ff;
            color:white;
            flex-shrink:0;
            display:flex;
            flex-direction:column;
            padding-top:20px;
        }
        .sidebar h2 { text-align:center; margin-bottom:30px; font-size:1.5rem; }
        .sidebar a {
            display:block;
            color:white;
            padding:15px 20px;
            text-decoration:none;
            transition:0.3s;
        }
        .sidebar a:hover { background:#104e8b; }

        /* Main content */
        .main-content {
            flex:1;
            padding:30px;
        }
        header h1 { font-size:2rem; color:#1e90ff; margin-bottom:20px; }

        /* Stats cards */
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px,1fr));
            gap: 20px;
            margin-bottom: 40px;
        }
        .card {
            background:white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
            text-align:center;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .card:hover { transform: translateY(-5px); box-shadow: 0 12px 20px rgba(0,0,0,0.15); }
        .card h2 { font-size: 2rem; color:#1e90ff; margin-bottom: 10px; }
        .card p { font-size: 1rem; color:#555; }

        /* Table styles */
        table {
            width:100%;
            border-collapse: collapse;
            margin-top: 20px;
            background:white;
            border-radius: 10px;
            overflow:hidden;
            box-shadow: 0 8px 16px rgba(0,0,0,0.1);
        }
        th, td { padding: 14px 16px; text-align:left; }
        th { background:#1e90ff; color:white; font-weight:600; }
        tr:nth-child(even) { background:#f9f9f9; }
        tr:hover { background:#e6f0ff; }

        /* Buttons */
        .button {
            display:inline-block;
            background:#1e90ff;
            color:white;
            padding:8px 12px;
            border-radius:6px;
            text-decoration:none;
            font-weight:bold;
            transition: all 0.3s;
        }
        .button:hover { background:#104e8b; transform: scale(1.05); }
        .button-delete { background:#ff4d4f; }
        .button-delete:hover { background:#c41f1f; transform: scale(1.05); }
        .actions { display:flex; gap:8px; }

        h2.section-title { color:#1e90ff; margin-top: 40px; margin-bottom:15px; }

        @media(max-width:768px){
            body { flex-direction:column; }
            .sidebar { width:100%; flex-direction:row; overflow-x:auto; }
            .sidebar a { flex-shrink:0; padding:15px 10px; }
        }
    </style>
</head>
<body>

    {{-- Sidebar --}}
    <div class="sidebar">
        <h2>Admin</h2>
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <a href="{{ route('books.index') }}">Livres</a>
        <a href="{{ route('users.index') }}">Utilisateurs</a>
        <a href="#">Emprunts</a>
        <a href="#">Favoris</a>
        <a href="{{ route('logout') }}">Déconnexion</a>
    </div>

    {{-- Main content --}}
    <div class="main-content">
        <header>
            <h1>Gestion des Livres</h1>
        </header>

        {{-- Stats --}}
        <div class="stats">
            <div class="card">
                <h2>{{ $bookCount }}</h2>
                <p>Total Livres</p>
            </div>
            <div class="card">
                <h2>{{ $availableBooks }}</h2>
                <p>Livres Disponibles</p>
            </div>
            <div class="card">
                <h2>{{ $borrowedBooks }}</h2>
                <p>Livres Empruntés</p>
            </div>
        </div>

        {{-- Ajouter un livre --}}
        <a href="{{ route('books.create') }}" class="button">Ajouter un livre</a>

        {{-- Tous les livres --}}
        <h2 class="section-title">Tous les Livres</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Auteur</th>
                    <th>Prix</th>
                    <th>Disponible</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($books as $book)
                <tr>
                    <td>{{ $book->id }}</td>
                    <td>{{ $book->title }}</td>
                    <td>{{ $book->author }}</td>
                    <td>{{ $book->price }} €</td>
                    <td>{{ $book->available ? 'Oui' : 'Non' }}</td>
                    <td class="actions">
                        <a href="{{ route('books.edit', $book->id) }}" class="button">Éditer</a>
                        <form action="{{ route('books.destroy', $book->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="button button-delete">Supprimer</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; color:#777;">Aucun livre trouvé</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Livres récents --}}
        <h2 class="section-title">Livres récents</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Auteur</th>
                    <th>Prix</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentBooks as $book)
                <tr>
                    <td>{{ $book->id }}</td>
                    <td>{{ $book->title }}</td>
                    <td>{{ $book->author }}</td>
                    <td>{{ $book->price }} €</td>
                </tr>
                @empty
                <tr><td colspan="4" style="text-align:center; color:#777;">Aucun livre récent</td></tr>
                @endforelse
            </tbody>
        </table>

    </div>

</body>
</html>
