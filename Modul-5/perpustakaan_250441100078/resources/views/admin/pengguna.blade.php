<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Kelola Pengguna & Role</h2></x-slot>
    <div class="py-12"><div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <x-flash />
            <table class="w-full text-left border-collapse">
                <thead><tr class="bg-gray-100">
                    <th class="border p-2">Nama</th><th class="border p-2">Email</th>
                    <th class="border p-2">Role</th>
                    @can('kelola role pengguna')<th class="border p-2">Ubah Role</th>@endcan
                </tr></thead>
                <tbody>
                @foreach ($users as $u)
                    <tr>
                        <td class="border p-2">{{ $u->name }}</td>
                        <td class="border p-2">{{ $u->email }}</td>
                        <td class="border p-2">{{ $u->getRoleNames()->implode(', ') ?: '-' }}</td>
                        @can('kelola role pengguna')
                        <td class="border p-2">
                            @if ($u->id !== auth()->id())
                            <form method="POST" action="{{ route('admin.pengguna.role', $u) }}" class="flex gap-2">
                                @csrf @method('PATCH')
                                <select name="role" class="border rounded p-1">
                                    @foreach ($roles as $r)
                                        <option value="{{ $r }}" @selected($u->hasRole($r))>{{ ucfirst($r) }}</option>
                                    @endforeach
                                </select>
                                <button class="px-3 py-1 bg-blue-600 text-white rounded">Simpan</button>
                            </form>
                            @else <span class="text-gray-400">akun Anda</span> @endif
                        </td>
                        @endcan
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div></div>
</x-app-layout>
