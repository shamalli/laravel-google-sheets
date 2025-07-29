<x-layouts.app :title="__('Dashboard')">
<div class="max-w-2xl mx-auto bg-white rounded-lg p-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Create New Item</h1>
    
    <form action="{{ route('items.store') }}" method="POST" class="space-y-6">
        @csrf
        
        <div>
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}"
                class="mt-1 px-4 py-2 block w-full rounded-md border focus:border-indigo-500 focus:ring-indigo-500 @error('name') border-red-500 @enderror">
            @error('name')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        
        <div>
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea id="description" name="description" rows="3"
                class="mt-1 px-4 py-2 block w-full rounded-md border focus:border-indigo-500 focus:ring-indigo-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
            @error('description')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
            <select id="status" name="status"
                class="border p-2 rounded block w-full  @error('status') border-red-500 @enderror">
                <option value="Allowed" {{ old('status') == 'Allowed' ? 'selected' : '' }}>Allowed</option>
                <option value="Prohibited" {{ old('status') == 'Prohibited' ? 'selected' : '' }}>Prohibited</option>
            </select>
            @error('status')
                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="flex items-center justify-end space-x-4">
            <a href="{{ route('items.index') }}" class="mr-5 px-4  py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                Cancel
            </a>
            <button type="submit" class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                Create Item
            </button>
        </div>
    </form>
</div>
</x-layouts.app>