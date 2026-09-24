<nav class="bg-white border-b border-gray-100 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            <!-- Logo et Liens Gauche -->
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('profile') }}" class="font-bold text-xl text-indigo-600">
                        Mon Profil
                    </a>
                </div>

                

                <!-- Liens de navigation principaux -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex sm:items-center">
                    <a href="{{ route('dashboard') }}" class="text-gray-900 hover:text-indigo-600 px-1 pt-1 text-sm font-medium">
                        Tableau de bord
                    </a>
                </div>
            </div>

            <!-- Boutons d'authentification (Droite) -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                @guest
                    <!-- Mode Déconnecté -->
                    <div class="space-x-4">
                        @if (!request()->routeIs('login'))
                            <a href="{{ route('login') }}" class="text-gray-500 hover:text-gray-700 text-sm font-medium">
                                Connexion
                            </a>
                        @endif
                            
                        @if (!request()->routeIs('register'))
                            <a href="{{ route('register') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium shadow-sm transition">
                                S'inscrire
                            </a>
                        @endif

                    </div>
                @endguest

                @auth
                    <!-- Mode Connecté -->
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('listes') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium shadow-sm transition">
                            Mes listes
                        </a>

                        <span class="text-gray-700 text-sm font-medium">
                            Bonjour {{ Auth::user()->pseudo }}
                        </span>
                        
                        <!-- Formulaire Logout intégré -->
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-red-500 hover:text-red-700 text-sm font-medium">
                                Déconnexion
                            </button>
                        </form>
                    </div>
                @endauth
            </div>

        </div>
    </div>
</nav>
