<div class="container p-1">
    <table class="table table-bordered table-hover shadow-sm">
        <thead class="table-light">
            <tr>
                <th>Name</th>
                <th>Price</th>
                <th>Image</th>
                <th>Description</th>
                <th>Type</th>
                <th>Discount</th>
                <th>Special</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($menuItems as $item)
                <tr class="{{ $item->is_special ? 'table-warning' : '' }}">
                    <td>{{ $item->name }}</td>
                    <td>${{ number_format($item->price, 2) }}</td>
                    <td>
                        <img src="{{ asset('storage/' . $item->image_path) }}" alt="image" class="img-thumbnail" style="width: 64px; height: 64px; object-fit: cover;">
                    </td>
                    <td class="text-truncate" style="max-width: 150px;">{{ $item->description }}</td>
                    <td>{{ $item->type }}</td>
                    <td>{{ $item->discount }}%</td>
                    <td class="fw-bold">{{ $item->is_special ? 'Yes' : 'No' }}</td>
                    <td>
                        <button wire:click="edit({{ $item->id }})" class="btn btn-primary btn-sm">Edit</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if($itemId)
        <div class="mt-4 p-4 border rounded shadow bg-white">
            <h2 class="h5 mb-3">Edit Menu Item</h2>
            <form wire:submit.prevent="update">
                <div class="mb-2">
                    <label class="form-label">Name</label>
                    <input type="text" wire:model="name" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label class="form-label">Price</label>
                    <input type="number" wire:model="price" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label class="form-label">Description</label>
                    <textarea wire:model="description" class="form-control"></textarea>
                </div>
                <div class="mb-2">
                    <label class="form-label">Type</label>
                    <input type="text" wire:model="type" class="form-control" required>
                </div>
                <div class="mb-2">
                    <label class="form-label">Discount</label>
                    <input type="number" wire:model="discount" class="form-control">
                </div>
                <div class="form-check mb-3">
                    <input type="checkbox" wire:model="is_special" class="form-check-input" id="specialCheck">
                    <label class="form-check-label" for="specialCheck">Is Special?</label>
                </div>
                <button type="submit" class="btn btn-success">Update Item</button>
            </form>
        </div>
    @endif
</div>
