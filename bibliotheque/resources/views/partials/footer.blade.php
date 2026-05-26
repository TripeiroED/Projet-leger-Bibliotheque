<style>
.app-footer {
    margin-top: 48px;
    background: linear-gradient(135deg, #0f3d73, #1e90ff);
    color: #ffffff;
}

.app-footer__inner {
    width: 90%;
    max-width: 1200px;
    margin: 0 auto;
    padding: 36px 0 18px;
}

.app-footer__grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 24px;
}

.app-footer__title {
    font-size: 1.35rem;
    margin-bottom: 12px;
}

.app-footer__text {
    color: rgba(255, 255, 255, 0.88);
    line-height: 1.6;
}

.app-footer__heading {
    font-size: 1rem;
    margin-bottom: 12px;
    color: #ffeb3b;
}

.app-footer__links {
    list-style: none;
    margin: 0;
    padding: 0;
}

.app-footer__links li + li {
    margin-top: 10px;
}

.app-footer__links a {
    color: #ffffff;
    text-decoration: none;
    opacity: 0.9;
    transition: opacity 0.3s ease;
}

.app-footer__links a:hover {
    opacity: 1;
}

.app-footer__bottom {
    margin-top: 24px;
    padding-top: 16px;
    border-top: 1px solid rgba(255, 255, 255, 0.24);
    display: flex;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
    color: rgba(255, 255, 255, 0.86);
    font-size: 0.95rem;
}

@media (max-width: 640px) {
    .app-footer__bottom {
        flex-direction: column;
    }
}
</style>

@php
    $footerUser = auth()->user();
    $isAdmin = $footerUser && $footerUser->role === 'admin';
    $isVerifiedUser = $footerUser && ! $isAdmin && $footerUser->hasVerifiedEmail();
@endphp

<footer class="app-footer">
    <div class="app-footer__inner">
        <div class="app-footer__grid">
            <div>
                <h3 class="app-footer__title">Bibliotheque en ligne</h3>
                <p class="app-footer__text">
                    Decouvrez, empruntez et gerez vos livres dans une interface simple,
                    claire et agreable a utiliser.
                </p>
            </div>

            <div>
                <h4 class="app-footer__heading">Navigation</h4>
                <ul class="app-footer__links">
                    @if($isAdmin)
                        <li><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li><a href="{{ route('users.index') }}">Utilisateurs</a></li>
                        <li><a href="{{ route('books.index') }}">Livres</a></li>
                        <li><a href="{{ route('admin.borrows') }}">Emprunts</a></li>
                    @elseif($isVerifiedUser)
                        <li><a href="{{ url('/') }}">Accueil</a></li>
                        <li><a href="{{ url('/profile') }}">Profil</a></li>
                        <li><a href="{{ url('/favorites') }}">Favoris</a></li>
                        <li><a href="{{ route('cart') }}">Panier</a></li>
                    @elseif($footerUser)
                        <li><a href="{{ url('/') }}">Accueil</a></li>
                        <li><a href="{{ route('verification.notice') }}">Verification email</a></li>
                        <li><a href="{{ route('logout') }}">Deconnexion</a></li>
                    @else
                        <li><a href="{{ url('/') }}">Accueil</a></li>
                        <li><a href="{{ route('login') }}">Connexion</a></li>
                        <li><a href="{{ route('register') }}">Inscription</a></li>
                    @endif
                </ul>
            </div>

            <div>
                <h4 class="app-footer__heading">Infos utiles</h4>
                <ul class="app-footer__links">
                    <li><a href="mailto:testdevprojets@gmail.com">Support email</a></li>
                    <li><a href="{{ url('/') }}?sort=title">Explorer les titres</a></li>
                    <li><a href="{{ url('/') }}?sort=price">Voir les prix</a></li>
                </ul>
            </div>
        </div>

        <div class="app-footer__bottom">
            <span>&copy; {{ now()->year }} Bibliotheque en ligne</span>
            <span>Lecture, decouverte et partage.</span>
        </div>
    </div>
</footer>
