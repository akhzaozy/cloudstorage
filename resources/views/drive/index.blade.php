<x-app-layout>
    <style>
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }
        ::-webkit-scrollbar-track {
            background: transparent;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 5px;
        }
        .dark ::-webkit-scrollbar-thumb {
            background: #475569;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        .dark ::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }

        /* Smooth transitions */
        * {
            transition-property: background-color, border-color, color, fill, stroke;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.3s ease-out;
        }

        /* Glassmorphism effect */
        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }
        .dark .glass {
            background: rgba(31, 41, 55, 0.7);
        }

        /* Gradient text */
        .gradient-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* File upload drag animation */
        @keyframes pulse-border {
            0%, 100% { border-color: #3b82f6; }
            50% { border-color: #8b5cf6; }
        }
        .drag-active {
            animation: pulse-border 1s ease-in-out infinite;
        }
    </style>

    <div x-data="{ 
        view: localStorage.getItem('view') || 'grid',
        preview: null, 
        previewType: null,
        darkMode: localStorage.getItem('darkMode') === 'true',
        showActions: {},
        searchQuery: '',
        init() {
            // Apply dark mode on load
            if (this.darkMode) {
                document.documentElement.classList.add('dark');
            }
        },
        toggleDark() {
            this.darkMode = !this.darkMode;
            localStorage.setItem('darkMode', this.darkMode);
            if (this.darkMode) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        },
        changeView(newView) {
            this.view = newView;
            localStorage.setItem('view', newView);
        },
        toggleActions(id) {
            this.showActions[id] = !this.showActions[id];
        }
    }" 
    x-init="init()"
    class="min-h-screen bg-gradient-to-br from-gray-50 via-blue-50 to-indigo-50 dark:from-gray-900 dark:via-gray-900 dark:to-gray-800">

        {{-- NAVBAR --}}
        <nav class="glass border-b border-gray-200 dark:border-gray-700 sticky top-0 z-40">
            <div class="max-w-7xl mx-auto px-6 py-4">

                {{-- HEADER --}}
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="relative">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg transform hover:scale-110 transition-transform duration-300">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                </svg>
                            </div>
                            <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 rounded-full border-2 border-white dark:border-gray-800"></div>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold gradient-text">My Drive</h2>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Manage your files & folders</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2">
                        {{-- Search Bar --}}
                        <div class="hidden md:flex items-center gap-2 bg-white dark:bg-gray-800 rounded-full px-4 py-2 shadow-sm border border-gray-200 dark:border-gray-700 min-w-[300px]">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <input type="text" 
                                   x-model="searchQuery"
                                   placeholder="Search files..." 
                                   class="bg-transparent border-0 focus:ring-0 text-sm w-full text-gray-900 dark:text-white placeholder-gray-400">
                        </div>

                        {{-- Dark Mode Toggle --}}
                        <button @click="toggleDark()" 
                                class="relative p-3 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-300 group">
                            <svg x-show="!darkMode" class="w-5 h-5 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                            </svg>
                            <svg x-show="darkMode" class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                        </button>

                        {{-- Trash --}}
                        <a href="/drive/trash" 
                           class="flex items-center gap-2 px-4 py-2 rounded-full text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            <span class="hidden lg:inline font-medium">Trash</span>
                        </a>

                        {{-- Sort --}}
                        <form action="" method="GET">
                            <select name="sort" 
                                    class="border-0 bg-white dark:bg-gray-800 text-gray-900 dark:text-white px-4 py-2 rounded-full shadow-sm hover:shadow-md transition-all cursor-pointer focus:ring-2 focus:ring-blue-500"
                                    onchange="this.form.submit()">
                                <option value="date_desc" {{ request('sort')=='date_desc'?'selected':'' }}>📅 Terbaru</option>
                                <option value="date_asc" {{ request('sort')=='date_asc'?'selected':'' }}>📅 Terlama</option>
                                <option value="name_asc" {{ request('sort')=='name_asc'?'selected':'' }}>🔤 Nama A-Z</option>
                                <option value="name_desc" {{ request('sort')=='name_desc'?'selected':'' }}>🔤 Nama Z-A</option>
                                <option value="size_desc" {{ request('sort')=='size_desc'?'selected':'' }}>📊 Terbesar</option>
                                <option value="size_asc" {{ request('sort')=='size_asc'?'selected':'' }}>📊 Terkecil</option>
                            </select>
                        </form>

                        {{-- View Toggle --}}
                        <div class="flex gap-1 bg-white dark:bg-gray-800 p-1 rounded-full shadow-sm">
                            <button @click="changeView('grid')" 
                                    :class="view=='grid' ? 'bg-gradient-to-r from-blue-500 to-purple-500 text-white shadow-lg' : 'text-gray-600 dark:text-gray-400'"
                                    class="p-2 rounded-full transition-all duration-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                </svg>
                            </button>
                            <button @click="changeView('list')" 
                                    :class="view=='list' ? 'bg-gradient-to-r from-blue-500 to-purple-500 text-white shadow-lg' : 'text-gray-600 dark:text-gray-400'"
                                    class="p-2 rounded-full transition-all duration-300">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                </svg>
                            </button>
                        </div>

                        {{-- Logout --}}
                        <form method="POST" action="/logout">
                            @csrf
                            <button class="flex items-center gap-2 px-4 py-2 rounded-full bg-gradient-to-r from-red-500 to-pink-500 text-white shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 font-medium">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                <span class="hidden lg:inline">Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        {{-- MAIN CONTENT --}}
        <div class="max-w-7xl mx-auto px-6 py-8">

            {{-- BREADCRUMB --}}
            <div class="flex items-center gap-2 mb-8 animate-fade-in">
                <div class="flex items-center gap-2 text-sm bg-white dark:bg-gray-800 rounded-full px-4 py-2 shadow-sm border border-gray-200 dark:border-gray-700">
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <a href="/drive" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium transition-colors">My Drive</a>
                    @if(isset($breadcrumbs))
                        @foreach($breadcrumbs as $b)
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                            <a href="/drive/folder/{{ $b['id'] }}" 
                               class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium transition-colors">
                                {{ $b['name'] }}
                            </a>
                        @endforeach
                    @endif
                </div>
            </div>

            {{-- UPLOAD & CREATE SECTION --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8 animate-fade-in">
                
                {{-- UPLOAD BOX --}}
                <div class="lg:col-span-2">
                    <div class="glass border-2 border-dashed border-blue-400 dark:border-purple-500 rounded-2xl p-10 text-center shadow-xl hover:shadow-2xl transition-all duration-300"
                         x-data="{drag:false}"
                         @dragover.prevent="drag=true"
                         @dragleave.prevent="drag=false"
                         @drop.prevent="drag=false;$refs.file.files=$event.dataTransfer.files;$refs.f.submit()"
                         :class="drag ? 'border-blue-600 dark:border-purple-400 scale-105 drag-active' : ''">

                        <form action="/drive/upload" method="POST" enctype="multipart/form-data" x-ref="f">
                            @csrf
                            <input type="file" name="files[]" multiple class="hidden" x-ref="file" @change="$refs.f.submit()">
                            @if(isset($folder))
                                <input type="hidden" name="parent_id" value="{{ $folder }}">
                            @endif

                            <div class="mb-6">
                                <div class="w-20 h-20 mx-auto bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500 rounded-full flex items-center justify-center mb-4 shadow-lg transform hover:scale-110 transition-transform">
                                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                </div>
                            </div>

                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Upload Files</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-6">
                                <span x-show="!drag">Drag & drop your files here</span>
                                <span x-show="drag" class="text-blue-600 dark:text-purple-400 font-semibold">Drop files to upload!</span>
                            </p>

                            <div class="flex flex-col sm:flex-row gap-3 justify-center items-center">
                                <button type="button"
                                        class="px-8 py-4 bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 hover:from-blue-700 hover:via-purple-700 hover:to-pink-700 text-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:scale-105 font-semibold flex items-center gap-3"
                                        @click="$refs.file.click()">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Choose Files
                                </button>
                                <p class="text-sm text-gray-500 dark:text-gray-400">or drag and drop</p>
                            </div>

                            <div class="mt-6 flex items-center justify-center gap-6 text-xs text-gray-500 dark:text-gray-400">
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Any file type
                                </span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                    Fast upload
                                </span>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- CREATE FOLDER --}}
                <div class="glass border border-gray-200 dark:border-gray-700 rounded-2xl p-6 shadow-xl hover:shadow-2xl transition-all duration-300">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-xl flex items-center justify-center shadow-md">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">New Folder</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Organize your files</p>
                        </div>
                    </div>

                    <form action="/drive/folder" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Folder Name</label>
                            <input type="text" 
                                   class="w-full border-0 bg-white dark:bg-gray-700 text-gray-900 dark:text-white px-4 py-3 rounded-xl shadow-sm focus:ring-2 focus:ring-blue-500 transition-all" 
                                   name="name" 
                                   placeholder="Enter folder name..." 
                                   required>
                        </div>
                        @if(isset($folder))
                            <input type="hidden" name="parent_id" value="{{ $folder }}">
                        @endif
                        <button class="w-full bg-gradient-to-r from-yellow-500 to-orange-500 hover:from-yellow-600 hover:to-orange-600 text-white px-6 py-3 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl font-semibold flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Create Folder
                        </button>
                    </form>

                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Quick Actions</h4>
                        <div class="space-y-2">
                            <button class="w-full text-left px-4 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors text-sm text-gray-700 dark:text-gray-300 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                </svg>
                                Recent files
                            </button>
                            <button class="w-full text-left px-4 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors text-sm text-gray-700 dark:text-gray-300 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Shared with me
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- LIST VIEW --}}
            <div x-show="view=='list'" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 class="glass border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden shadow-xl animate-fade-in">
                @if(empty($items))
                    <div class="p-16 text-center">
                        <div class="w-32 h-32 mx-auto bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-700 rounded-full flex items-center justify-center mb-6 shadow-inner">
                            <svg class="w-16 h-16 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">No files yet</h3>
                        <p class="text-gray-500 dark:text-gray-400">Upload your first file to get started</p>
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900 border-b border-gray-200 dark:border-gray-700 sticky top-0 z-10">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                            </svg>
                                            Name
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                            </svg>
                                            Size
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            Modified
                                        </div>
                                    </th>
                                    <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($items as $item)
                                    @php
                                        $isImg = !empty($item['mime']) && \Illuminate\Support\Str::startsWith($item['mime'], 'image/');
                                    @endphp
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all duration-200 group"
                                        x-data="{ showMenu: false }">
                                        <td class="px-6 py-4">
                                            @if($item['type']=='folder')
                                                <a href="/drive/folder/{{ $item['id'] }}" 
                                                   class="flex items-center gap-3 group/item">
                                                    <div class="w-10 h-10 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-lg flex items-center justify-center shadow-sm group-hover/item:scale-110 transition-transform">
                                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                                        </svg>
                                                    </div>
                                                    <span class="font-medium text-gray-900 dark:text-white group-hover/item:text-blue-600 dark:group-hover/item:text-blue-400 transition-colors">
                                                        {{ $item['name'] }}
                                                    </span>
                                                </a>
                                            @else
                                                @if($isImg)
                                                    <a href="javascript:void(0)" 
                                                       @click="preview='{{ asset($item['path']) }}';previewType='image'"
                                                       class="flex items-center gap-3 group/item">
                                                        <div class="w-10 h-10 rounded-lg overflow-hidden shadow-sm ring-2 ring-gray-200 dark:ring-gray-700 group-hover/item:ring-blue-400 transition-all">
                                                            <img src="{{ asset($item['path']) }}" class="w-full h-full object-cover group-hover/item:scale-110 transition-transform">
                                                        </div>
                                                        <span class="text-gray-900 dark:text-white group-hover/item:text-blue-600 dark:group-hover/item:text-blue-400 transition-colors">
                                                            {{ $item['name'] }}
                                                        </span>
                                                    </a>
                                                @else
                                                    <a href="javascript:void(0)"
                                                       @click="preview='{{ asset($item['path']) }}';previewType='file'"
                                                       class="flex items-center gap-3 group/item">
                                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-purple-500 rounded-lg flex items-center justify-center shadow-sm group-hover/item:scale-110 transition-transform">
                                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                                            </svg>
                                                        </div>
                                                        <span class="text-gray-900 dark:text-white group-hover/item:text-blue-600 dark:group-hover/item:text-blue-400 transition-colors">
                                                            {{ $item['name'] }}
                                                        </span>
                                                    </a>
                                                @endif
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400 font-medium">
                                            <div class="flex items-center gap-2">
                                                @if($item['size'])
                                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                                    </svg>
                                                    {{ number_format($item['size']/1024,2) }} KB
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                {{ date('d M Y', strtotime($item['created_at'])) }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex justify-end items-center gap-2">
                                                <div class="relative" @click.away="showMenu = false">
                                                    <button @click="showMenu = !showMenu"
                                                            class="p-2 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-lg transition-colors">
                                                        <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                                        </svg>
                                                    </button>

                                                    <div x-show="showMenu"
                                                         x-transition
                                                         class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-xl shadow-2xl border border-gray-200 dark:border-gray-700 z-20 overflow-hidden">
                                                        <form action="/drive/rename/{{ $item['id'] }}" method="POST" class="p-3 border-b border-gray-200 dark:border-gray-700">
                                                            @csrf
                                                            <div class="flex gap-2">
                                                                <input type="text" name="name" placeholder="New name" 
                                                                       class="flex-1 px-3 py-2 text-sm border-0 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500">
                                                                <button class="px-3 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                        </form>

                                                        <form action="/drive/move/{{ $item['id'] }}" method="POST" class="p-3 border-b border-gray-200 dark:border-gray-700">
                                                            @csrf
                                                            <div class="flex gap-2">
                                                                <input type="text" name="target_parent_id" placeholder="Folder ID" 
                                                                       class="flex-1 px-3 py-2 text-sm border-0 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-purple-500">
                                                                <button class="px-3 py-2 bg-purple-500 hover:bg-purple-600 text-white rounded-lg transition-colors">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                                                    </svg>
                                                                </button>
                                                            </div>
                                                        </form>

                                                        <form action="/drive/delete/{{ $item['id'] }}" method="POST" class="p-2">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button class="w-full px-3 py-2 text-left text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors flex items-center gap-2">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                                </svg>
                                                                Move to Trash
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            {{-- GRID VIEW --}}
            <div x-show="view=='grid'" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-4 animate-fade-in">
                @forelse($items as $item)
                    @php
                        $isImg = !empty($item['mime']) && \Illuminate\Support\Str::startsWith($item['mime'], 'image/');
                    @endphp
                    <div class="glass border border-gray-200 dark:border-gray-700 rounded-2xl p-4 shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 group"
                         x-data="{ showMenu: false }">
                        
                        {{-- Item Content --}}
                        <div class="relative">
                            @if($item['type']=='folder')
                                <a href="/drive/folder/{{ $item['id'] }}" class="block">
                                    <div class="relative mb-4">
                                        <div class="w-full h-32 bg-gradient-to-br from-yellow-400 via-orange-500 to-red-500 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform duration-300">
                                            <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                            </svg>
                                        </div>
                                        <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-white dark:bg-gray-800 rounded-full flex items-center justify-center shadow-md border-2 border-gray-200 dark:border-gray-700">
                                            <svg class="w-4 h-4 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <h3 class="font-semibold text-gray-900 dark:text-white truncate group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                        {{ $item['name'] }}
                                    </h3>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Folder</p>
                                </a>
                            @else
                                @if($isImg)
                                    <a href="javascript:void(0)" 
                                       @click="preview='{{ asset($item['path']) }}';previewType='image'" 
                                       class="block">
                                        <div class="relative mb-4">
                                            <div class="w-full h-32 rounded-xl overflow-hidden shadow-lg ring-2 ring-gray-200 dark:ring-gray-700 group-hover:ring-blue-400 transition-all">
                                                <img src="{{ asset($item['path']) }}" 
                                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                                            </div>
                                            <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-gradient-to-br from-blue-500 to-purple-500 rounded-full flex items-center justify-center shadow-md">
                                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <h3 class="font-semibold text-sm text-gray-900 dark:text-white truncate group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                            {{ $item['name'] }}
                                        </h3>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            {{ $item['size'] ? number_format($item['size']/1024,2).' KB' : '-' }}
                                        </p>
                                    </a>
                                @else
                                    <a href="javascript:void(0)" 
                                       @click="preview='{{ asset($item['path']) }}';previewType='file'" 
                                       class="block">
                                        <div class="relative mb-4">
                                            <div class="w-full h-32 bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-105 transition-transform duration-300">
                                                <svg class="w-16 h-16 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                            <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-white dark:bg-gray-800 rounded-full flex items-center justify-center shadow-md border-2 border-gray-200 dark:border-gray-700">
                                                <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <h3 class="font-semibold text-sm text-gray-900 dark:text-white truncate group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                                            {{ $item['name'] }}
                                        </h3>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            {{ $item['size'] ? number_format($item['size']/1024,2).' KB' : 'File' }}
                                        </p>
                                    </a>
                                @endif
                            @endif
                        </div>

                        {{-- Actions Dropdown --}}
                        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700 relative">
                            <button @click="showMenu = !showMenu"
                                    class="w-full px-3 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 rounded-lg transition-colors text-sm font-medium text-gray-700 dark:text-gray-300 flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                </svg>
                                Actions
                            </button>

                            <div x-show="showMenu"
                                 @click.away="showMenu = false"
                                 x-transition
                                 class="absolute bottom-full left-0 right-0 mb-2 bg-white dark:bg-gray-800 rounded-xl shadow-2xl border border-gray-200 dark:border-gray-700 z-30 overflow-hidden">
                                
                                <form action="/drive/rename/{{ $item['id'] }}" method="POST" class="p-3 border-b border-gray-200 dark:border-gray-700">
                                    @csrf
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Rename</label>
                                    <div class="flex gap-2">
                                        <input type="text" name="name" placeholder="New name" 
                                               class="flex-1 px-3 py-2 text-sm border-0 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-blue-500">
                                        <button class="px-3 py-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors flex items-center justify-center">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </button>
                                    </div>
                                </form>

                                <form action="/drive/move/{{ $item['id'] }}" method="POST" class="p-3 border-b border-gray-200 dark:border-gray-700">
                                    @csrf
                                    <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Move to</label>
                                    <div class="flex gap-2">
                                        <input type="text" name="target_parent_id" placeholder="Folder ID" 
                                               class="flex-1 px-3 py-2 text-sm border-0 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg focus:ring-2 focus:ring-purple-500">
                                        <button class="px-3 py-2 bg-purple-500 hover:bg-purple-600 text-white rounded-lg transition-colors flex items-center justify-center">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                            </svg>
                                        </button>
                                    </div>
                                </form>

                                <form action="/drive/delete/{{ $item['id'] }}" method="POST" class="p-2">
                                    @csrf
                                    @method('DELETE')
                                    <button class="w-full px-3 py-2 text-left text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors flex items-center gap-2 font-medium">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full flex flex-col items-center justify-center py-20">
                        <div class="w-32 h-32 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-700 rounded-full flex items-center justify-center mb-6 shadow-inner">
                            <svg class="w-16 h-16 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-300 mb-2">No files yet</h3>
                        <p class="text-gray-500 dark:text-gray-400">Upload your first file to get started</p>
                    </div>
                @endforelse
            </div>

            {{-- PREVIEW MODAL --}}
            <div x-show="preview" 
                 @click.self="preview=null"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 class="fixed inset-0 bg-black/90 backdrop-blur-md flex items-center justify-center p-4 sm:p-6 z-50">
                
                <div class="relative w-full max-w-6xl"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform scale-90"
                     x-transition:enter-end="opacity-100 transform scale-100">
                    
                    {{-- Header --}}
                    <div class="flex items-center justify-between mb-4 px-2">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-500 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </div>
                            <span class="text-white font-semibold text-lg">File Preview</span>
                        </div>
                        
                        <button @click="preview=null" 
                                class="w-12 h-12 bg-white/10 hover:bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center transition-all duration-300 hover:rotate-90">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Content --}}
                    <div class="glass border border-white/20 rounded-2xl p-6 shadow-2xl">
                        <template x-if="previewType=='image'">
                            <div class="flex items-center justify-center">
                                <img :src="preview" 
                                     class="max-w-full max-h-[75vh] object-contain rounded-lg shadow-2xl">
                            </div>
                        </template>

                        <template x-if="previewType=='file'">
                            <iframe :src="preview" 
                                    class="w-full h-[75vh] rounded-lg border-0 bg-white dark:bg-gray-900">
                            </iframe>
                        </template>
                    </div>

                    {{-- Footer Actions --}}
                    <div class="flex items-center justify-center gap-3 mt-4">
                        <button class="px-6 py-3 bg-white/10 hover:bg-white/20 backdrop-blur-sm text-white rounded-full transition-all duration-300 flex items-center gap-2 font-medium">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                            </svg>
                            Download
                        </button>
                        <button class="px-6 py-3 bg-white/10 hover:bg-white/20 backdrop-blur-sm text-white rounded-full transition-all duration-300 flex items-center gap-2 font-medium">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                            </svg>
                            Share
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <footer class="mt-12 pb-8">
            <div class="max-w-7xl mx-auto px-6">
                <div class="glass border border-gray-200 dark:border-gray-700 rounded-2xl p-6">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 via-purple-500 to-pink-500 rounded-lg flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900 dark:text-white">My Drive</h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Secure cloud storage</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-6 text-sm text-gray-600 dark:text-gray-400">
                            <a href="#" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Help Center
                            </a>
                            <a href="#" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                                </svg>
                                Settings
                            </a>
                            <a href="#" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                                Privacy
                            </a>
                        </div>

                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            © 2024 My Drive. All rights reserved.
                        </div>
                    </div>
                </div>
            </div>
        </footer>

    </div>
</x-app-layout>