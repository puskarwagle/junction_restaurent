<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <!-- Header -->
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Users</h2>
        </div>

        <!-- Alert Message -->
        <div id="alert" class="{{ $msgst ? 'alert alert-' . $msgst : 'hidden' }} mb-6 p-4 rounded-lg {{ $msgst == 'success' ? 'bg-green-100 text-green-800' : ($msgst == 'error' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800') }}">
            {{ $msg ?? null }}
        </div>

        <!-- Search Filter -->
        <div class="mb-6">
            <input type="text" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Search by name, email, or type..." wire:model.live="search">
        </div>

        <!-- Users Table -->
        <div class="overflow-x-auto">
            <table class="w-full py-2 bg-white border border-gray-200 rounded-lg shadow-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="#" wire:click="sortBy('id')" class="hover:text-blue-600">Id</a>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Image</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="#" wire:click.prevent="sortBy('name')" class="hover:text-blue-600">Name</a>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="#" wire:click.prevent="sortBy('email')" class="hover:text-blue-600">Email</a>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <a href="#" wire:click.prevent="sortBy('type')" class="hover:text-blue-600">Type</a>
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($users as $item)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $item->id }}</td>
                            <td class="px-6 py-4">
                                <img class="rounded-full h-8 w-8 object-cover" src="{{ !empty($item->Data->image) ? asset('/storage/userdata/' . $item->Data->image) : asset('stockUser.png') }}" alt="{{ $item->name }}'s profile image">
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $item->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">{{ $item->email }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700">
                                <select class="px-2 py-1 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent" wire:change="updateUserType({{ $item->id }}, $event.target.value)" data-name="{{ $item->name }}">
                                    <option value="A" {{ $item->type == 'A' ? 'selected' : '' }}>Admin</option>
                                    <option value="U" {{ $item->type == 'U' ? 'selected' : '' }}>User</option>
                                </select>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>