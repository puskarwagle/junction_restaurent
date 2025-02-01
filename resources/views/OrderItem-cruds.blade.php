<div class="container mt-4">
    <h1 class="mb-4">OrderItem Management</h1>
    
    <!-- Search Input -->
    <div class="mb-3">
        <input type="text" wire:model.live="search" placeholder="Search..." class="form-control">
    </div>
    
    <!-- Table -->
    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th wire:click="sortBy('id')">ID</th>
                @foreach ($tabledata[0] ?? [] as $field => $value)
                    @if ($field !== 'id') <!-- Exclude the 'id' field from the header loop -->
                        <th wire:click="sortBy('{{ $field }}')">{{ ucfirst($field) }}</th>
                    @endif
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($tabledata as $record)
                <tr>
                    <td>{{ $record['id'] }}</td>
                    @foreach ($record as $field => $value)
                        @if ($field !== 'id') <!-- Exclude the 'id' field from the data loop -->
                            <td>{{ $value }}</td>
                        @endif
                    @endforeach
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