<div class="container mt-4">
    <h1 class="mb-4">User Management</h1>
    
    <!-- Search Input -->
    <div class="mb-3">
        <input type="text" wire:model.live="search" placeholder="Search..." class="form-control">
    </div>

    <!-- Create and Delete Buttons -->
    <div class="mb-3">
        <button wire:click="$toggle('showCreateForm')" class="btn btn-sm {{$showCreateForm ? 'btn-warning' : 'btn-primary' }}">
            {{$showCreateForm ? 'Cancel' : 'Create New' }}
        </button>
        <button wire:click="delete" class="btn btn-danger btn-sm">Delete</button>
        @if ($showCreateForm)
        <button wire:click="create" class="btn btn-success btn-sm">Save</button>
        @endif
    </div>
    
    <!-- Table -->
    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th><input type="checkbox" wire:model="selectAll" aria-label="Select all rows"></th> <!-- Checkbox column -->
                <th wire:click="sortBy('id')">ID</th>
                @foreach ($tabledata[0] ?? [] as $field => $value)
                    @if (!in_array($field, ['id', 'created_at', 'updated_at'])) <!-- Exclude these fields -->
                        <th wire:click="sortBy('{{ $field }}')">{{ ucfirst($field) }}</th>
                    @endif
                @endforeach
                <th>Created At</th> <!-- Move created_at to the far right -->
                <th>Updated At</th> <!-- Move updated_at to the far right -->
            </tr>
        </thead>
        <tbody>
            <!-- Hidden Create Form -->
            @if ($showCreateForm)
                <tr>
                    <td></td> <!-- Empty checkbox column -->
                    <td>{{ $nextId }}</td> <!-- Display the next ID (max ID + 1) -->
                    @foreach ($tabledata[0] ?? [] as $field => $value)
                        @if (!in_array($field, ['id', 'created_at', 'updated_at'])) <!-- Exclude these fields -->
                            <td>
                                <input type="text" wire:model="{{ $field }}" class="form-control blendInputs" placeholder="Enter {{ ucfirst($field) }}">
                            </td>
                        @endif
                    @endforeach
                    <td></td> <!-- Empty created_at column -->
                    <td></td> <!-- Empty updated_at column -->
                </tr>
            @endif

            <!-- Table Data Rows -->
            @foreach ($tabledata as $record)
                <tr>
                    <td>
                        <input type="checkbox" wire:model="selectedIds" value="{{ $record['id'] }}" aria-label="Select row">
                    </td>
                    <td>{{ $record['id'] }}</td>
                    @foreach ($record as $field => $value)
                        @if (!in_array($field, ['id', 'created_at', 'updated_at'])) <!-- Exclude these fields -->
                            <td>
                                @if ($editingField === $field . '-' . $record['id'])
                                    <input type="text" wire:model="editingValue" class="form-control" wire:keydown.enter="saveModifiedField('{{ $field }}', {{ $record['id'] }})">
                                @else
                                    <span wire:click="incrementClick('{{ $field }}', {{ $record['id'] }}, '{{ $value }}')">{{ $value }}</span>
                                @endif
                            </td>
                        @endif
                    @endforeach
                    <td>{{ $record['created_at'] }}</td> <!-- Display created_at -->
                    <td>{{ $record['updated_at'] }}</td> <!-- Display updated_at -->
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <!-- Pagination Controls -->
    <div class="d-flex justify-content-between align-items-center mt-3">
        <select wire:model.live="perPage" class="form-select w-auto">
            <option value="10">10 per page</option>
            <option value="25">25 per page</option>
            <option value="50">50 per page</option>
            <option value="100">100 per page</option>
        </select>
        <span>Page {{ $pagination['current_page'] }} of {{ $pagination['last_page'] }}</span>
        <span>{{ $pagination['total'] }} Total Records</span>
    </div>
</div>