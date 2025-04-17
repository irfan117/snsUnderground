<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
 
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const likeButtons = document.querySelectorAll('.like-button');

                likeButtons.forEach(button => {
                    button.addEventListener('click', function () {
                        const postId = this.getAttribute('data-post-id');
                        const url = `/posts/${postId}/like`;

                        fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                            },
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.liked) {
                                this.textContent = `${data.likeCount} Unlike`;
                            } else {
                                this.textContent = `${data.likeCount} Like`;
                            }
                        })
                        .catch(error => console.error('Error:', error));
                    });
                });

                const seeMoreButtons = document.querySelectorAll('.see-more');

                seeMoreButtons.forEach(button => {
                    button.addEventListener('click', function () {
                        const fullContent = this.getAttribute('data-full-content');
                        const postContent = this.previousElementSibling;

                        postContent.innerHTML = fullContent;
                        this.style.display = 'none'; // Sembunyikan tombol "Lihat lebih banyak"
                    });
                });
            });
        </script>
    </body>
</html>
