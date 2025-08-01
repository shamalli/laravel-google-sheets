<x-layouts.app :title="__('Dashboard')">
    <div class="container">
        <h1 class="text-2xl font-bold mb-4">Items Management</h1>

        @if (session('message'))
            <div class="p-4 mb-4 text-sm text-green-800 border border-green-300 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                <span class="font-medium">{{ session('message') }}</span>
            </div>
        @endif
        
        <div class="mb-4">
            <div class="mb-4">
                <a href="{{ route('items.create') }}" class="mb-4 px-4 py-2 bg-blue-500 text-white font-bold rounded cursor-pointer">Create New</a>
            </div>
            <form action="{{ route('items.generate') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="mb-4 px-4 py-2 bg-blue-500 text-white font-bold rounded cursor-pointer">Generate 1000 Items</button>
            </form>
            <form action="{{ route('items.clear') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="mb-4 px-4 py-2 bg-blue-500 text-white font-bold rounded cursor-pointer">Clear All Items</button>
            </form>
        </div>
        
        <div class="card mb-4">
            <div class="card-header">Google Sheet Integration</div>
            <div class="card-body">
                <form action="{{ route('items.set-google-sheet') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="google_sheet_url">Google Sheet URL</label>
                        <input type="url" class="border p-2 rounded w-1/2" id="google_sheet_url" name="google_sheet_url" 
                            value="{{ $google_sheet_url }}" required>
                    </div>
                    <button type="submit" class="mb-4 px-4 py-2 bg-blue-500 text-white font-bold rounded cursor-pointer">Save URL</button>
                </form>
            </div>
        </div>
        
        <div>
            <div class="flex gap-2">
                <div class="w-1/4 pb-2">ID</div>
                <div class="w-1/4 pb-2">Name</div>
                <div class="w-1/4 pb-2">Description</div>
                <div class="w-1/4 pb-2">Status</div>
                <div class="w-1/4 pb-2">Created At</div>
                <div class="w-1/4 pb-2">Updated At</div>
                <div class="w-1/4 pb-2">Actions</div>
            </div>
            @foreach($items as $item)
            <div class="flex gap-2 mb-3 odd:bg-white even:bg-gray-50 dark:odd:bg-gray-900/50 dark:even:bg-gray-950">
                <div class="p-2 w-1/4">{{ $item->id }}</div>
                <div class="p-2 w-1/4">{{ $item->name }}</div>
                <div class="p-2 w-1/4">{{ $item->description }}</div>
                <div class="p-2 w-1/4">{{ $item->status->value }}</div>
                <div class="p-2 w-1/4">{{ $item->created_at }}</div>
                <div class="p-2 w-1/4">{{ $item->updated_at }}</div>
                <div class="p-2 w-1/4">
                    <a href="{{ route('items.edit', $item->id) }}" class="cursor-pointer">Edit</a>
                    <form action="{{ route('items.destroy', $item->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="cursor-pointer text-red-500">Delete</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
        </div>
        
        {{ $items->links() }}
    </div>
</x-layouts.app>