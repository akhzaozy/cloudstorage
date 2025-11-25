<x-app-layout>

    <div class="max-w-7xl mx-auto p-6">

        <div class="flex items-center justify-between mb-6">
            <h2 class="text-2xl font-semibold">Trash</h2>

            <div class="flex items-center gap-4">
                <a href="/drive" class="hover:underline text-gray-700">Kembali</a>

                <form action="/logout" method="POST">
                    @csrf
                    <button class="text-red-600 hover:underline">Logout</button>
                </form>
            </div>
        </div>

        @if(empty($items))
            <p class="text-gray-500">Trash kosong</p>
        @else

            <div class="bg-white shadow rounded p-4">

                <table class="w-full">
                    <thead>
                        <tr class="border-b">
                            <th class="p-2">Nama</th>
                            <th class="p-2">Tipe</th>
                            <th class="p-2">Dihapus</th>
                            <th class="p-2 text-right">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($items as $item)
                            <tr class="border-b">
                                <td class="p-2">{{ $item['name'] }}</td>
                                <td class="p-2">{{ $item['type'] }}</td>
                                <td class="p-2">
                                    {{ date('d M Y H:i', strtotime($item['deleted_at'])) }}
                                </td>
                                <td class="p-2 text-right">

                                    {{-- restore --}}
                                    <form action="/drive/restore/{{ $item['id'] }}"
                                          method="POST" class="inline">
                                        @csrf
                                        <button class="text-green-700 text-xs hover:underline">
                                            Restore
                                        </button>
                                    </form>

                                    {{-- permanen --}}
                                    <form action="/drive/force-delete/{{ $item['id'] }}"
                                          method="POST"
                                          class="inline ml-2"
                                          onsubmit="return confirm('Hapus permanen?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-red-700 text-xs hover:underline">
                                            Delete Permanen
                                        </button>
                                    </form>

                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>

            </div>

        @endif

    </div>

</x-app-layout>
