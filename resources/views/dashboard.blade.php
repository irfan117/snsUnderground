<x-app-layout>
    <head>
        <!-- Tambahkan Font Awesome -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    </head>
    <div class="max-w-xl mx-auto p-4 space-y-6">
        {{-- Form Post --}}
        <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow rounded p-4 space-y-3">
            @csrf
            <textarea name="content" class="w-full border p-2 rounded" placeholder="Apa yang sedang kamu pikirkan?" required></textarea>
            <input type="file" name="image" class="block mt-2 text-sm">
            <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700" type="submit">Kirim</button>
            <p class="text-xs text-gray-500 italic mt-1">⚠️ Tidak disarankan membagikan identitas asli di platform ini.</p>
        </form>

        {{-- Daftar Post --}}
        @foreach($posts as $post)
        <div class="bg-white shadow-sm rounded p-4 border">
            <div class="flex items-center justify-between text-sm text-gray-600">
                <div class="flex items-center">
                    {{-- Foto Profil --}}
                    @if ($post->user->profile_photo_path)
                        <img src="{{ asset('storage/' . $post->user->profile_photo_path) }}" alt="Profile Photo" class="w-10 h-10 rounded-full object-cover mr-3">
                    @else
                        {{-- Default Foto Profil --}}
                        <img src="{{ asset('images/default-profile.png') }}" alt="Default Profile Photo" class="w-10 h-10 rounded-full object-cover mr-3">
                    @endif

                    {{-- Nama Pengguna --}}
                    <span class="font-bold text-gray-800">{{ $post->user->name }}</span>
                </div>
                <span>{{ $post->created_at->diffForHumans() }}</span>
            </div>

            {{-- Konten Postingan --}}
            <p class="mt-2 text-gray-900">
                <span class="post-content">
                    {!! nl2br(e(Str::limit($post->content, 200))) !!}
                </span>
                @if (strlen($post->content) > 200)
                    <span class="text-blue-600 cursor-pointer see-more" data-full-content="{{ nl2br(e($post->content)) }}">Lihat lebih banyak</span>
                @endif
            </p>

            {{-- Gambar Postingan --}}
            @if ($post->image_path)
                <img src="{{ asset('storage/' . $post->image_path) }}" class="mt-3 rounded-lg max-w-full">
            @endif

            {{-- Tombol Like --}}
            <div>
                <button class="like-button flex items-center text-blue-600" data-post-id="{{ $post->id }}">
                    <i class="fa{{ $post->likes->where('user_id', auth()->id())->count() ? 's' : 'r' }} fa-heart mr-1"></i>
                    <span class="like-count">{{ $post->likes->count() }}</span> Like
                </button>
            </div>

            {{-- Komentar --}}
            <div class="mt-4" id="comments-{{ $post->id }}">
                <h4 class="text-sm font-bold">Komentar</h4>
                @foreach($post->comments as $comment)
                    <div class="flex items-start text-sm text-gray-700 mt-2">
                        {{-- Foto Profil Komentar --}}
                        @if ($comment->user->profile_photo_path)
                            <img src="{{ asset('storage/' . $comment->user->profile_photo_path) }}" alt="Profile Photo" class="w-8 h-8 rounded-full object-cover mr-3">
                        @else
                            <img src="{{ asset('images/default-profile.png') }}" alt="Default Profile Photo" class="w-8 h-8 rounded-full object-cover mr-3">
                        @endif

                        {{-- Konten Komentar --}}
                        <div>
                            <span class="font-bold">{{ $comment->user->name }}</span>
                            <p>{{ $comment->content }}</p>

                            {{-- Tombol Balas --}}
                            <button class="reply-toggle text-blue-600 text-sm" data-comment-id="{{ $comment->id }}">Balas</button>

                            {{-- Form Balas Komentar (Disembunyikan Secara Default) --}}
                            <form action="{{ route('posts.comments.store', $post) }}" method="POST" class="reply-form mt-2 hidden" data-post-id="{{ $post->id }}" data-parent-id="{{ $comment->id }}">
                                @csrf
                                <textarea name="content" class="w-full border p-2 rounded text-sm" placeholder="Balas komentar..." required></textarea>
                                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 mt-2">Kirim</button>
                            </form>

                            {{-- Tampilkan Balasan --}}
                            <div class="ml-8 mt-2" id="replies-{{ $comment->id }}">
                                @foreach($comment->replies as $reply)
                                    <div class="flex items-start text-sm text-gray-700 mt-2">
                                        @if ($reply->user->profile_photo_path)
                                            <img src="{{ asset('storage/' . $reply->user->profile_photo_path) }}" alt="Profile Photo" class="w-8 h-8 rounded-full object-cover mr-3">
                                        @else
                                            <img src="{{ asset('images/default-profile.png') }}" alt="Default Profile Photo" class="w-8 h-8 rounded-full object-cover mr-3">
                                        @endif
                                        <div>
                                            <span class="font-bold">{{ $reply->user->name }}</span>
                                            <p>{{ $reply->content }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach

                {{-- Tombol Tambah Komentar --}}
                <button class="comment-toggle text-blue-600 text-sm mt-4" data-post-id="{{ $post->id }}">Tambah Komentar</button>

                {{-- Form Komentar (Disembunyikan Secara Default) --}}
                <form action="{{ route('posts.comments.store', $post) }}" method="POST" class="comment-form mt-2 hidden" data-post-id="{{ $post->id }}">
                    @csrf
                    <textarea name="content" class="w-full border p-2 rounded text-sm" placeholder="Tulis komentar..." required></textarea>
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 mt-2">Kirim</button>
                </form>
            </div>
        </div>
        @endforeach

        @if ($posts->isEmpty())
            <p class="text-center text-gray-500 italic">Belum ada postingan. Jadilah yang pertama.</p>
        @endif
    </div>
</x-app-layout>
