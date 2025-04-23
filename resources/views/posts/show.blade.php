{{-- filepath: resources/views/posts/show.blade.php --}}
<x-app-layout>
    <div class="max-w-4xl mx-auto p-4 space-y-6">
        <div class="bg-white shadow-sm rounded p-4 border">
            <h1 class="text-xl font-bold">{{ $post->user->name }}</h1>
            <p class="text-gray-600 text-sm">{{ $post->created_at->diffForHumans() }}</p>
            <p class="mt-4 text-gray-900">{{ $post->content }}</p>

            @if ($post->image_path)
                <img src="{{ asset('storage/' . $post->image_path) }}" class="mt-4 rounded-lg max-w-full">
            @endif
        </div>
    </div>
</x-app-layout>