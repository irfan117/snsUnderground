import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

document.addEventListener('DOMContentLoaded', function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Tampilkan form komentar saat tombol "Tambah Komentar" diklik
    document.querySelectorAll('.comment-toggle').forEach(toggle => {
        toggle.addEventListener('click', function () {
            const postId = this.dataset.postId;
            const commentForm = document.querySelector(`.comment-form[data-post-id="${postId}"]`);
            commentForm.classList.toggle('hidden');
        });
    });

    // Tampilkan form balasan saat tombol "Balas" diklik
    document.querySelectorAll('.reply-toggle').forEach(toggle => {
        toggle.addEventListener('click', function () {
            const commentId = this.dataset.commentId;
            const replyForm = document.querySelector(`.reply-form[data-parent-id="${commentId}"]`);
            replyForm.classList.toggle('hidden');
        });
    });

    // Like/unlike postingan
    document.querySelectorAll('.like-button').forEach(button => {
        button.addEventListener('click', function () {
            const postId = this.dataset.postId;
            fetch(`/posts/${postId}/like`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Content-Type': 'application/json',
                },
            })
            .then(res => res.json())
            .then(data => {
                this.textContent = `${data.likeCount} ${data.liked ? 'Unlike' : 'Like'}`;
            })
            .catch(console.error);
        });
    });

    // Lihat lebih banyak konten
    document.querySelectorAll('.see-more').forEach(button => {
        button.addEventListener('click', function () {
            const fullContent = this.dataset.fullContent;
            const postContent = this.previousElementSibling;
            postContent.innerHTML = fullContent;
            this.style.display = 'none';
        });
    });

    // Submit komentar
    document.querySelectorAll('.comment-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const postId = this.dataset.postId;
            const commentsContainer = document.getElementById(`comments-${postId}`);
            const formData = new FormData(this);

            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: formData,
            })
            .then(res => res.json())
            .then(data => {
                const profileImg = data.user.profile_photo_path
                    ? `/storage/${data.user.profile_photo_path}`
                    : '/images/default-profile.png';

                const newComment = `
                    <div class="flex items-start text-sm text-gray-700 mt-2">
                        <img src="${profileImg}" alt="Profile Photo" class="w-8 h-8 rounded-full object-cover mr-3">
                        <div>
                            <span class="font-bold">${data.user.name}</span>
                            <p>${data.comment.content}</p>
                        </div>
                    </div>
                `;
                commentsContainer.insertAdjacentHTML('beforeend', newComment);
                this.querySelector('textarea').value = '';
            })
            .catch(console.error);
        });
    });

    // Submit balasan
    document.querySelectorAll('.reply-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const parentId = this.dataset.parentId;
            const repliesContainer = document.getElementById(`replies-${parentId}`);
            const formData = new FormData(this);

            fetch(this.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: formData,
            })
            .then(res => res.json())
            .then(data => {
                const profileImg = data.user.profile_photo_path
                    ? `/storage/${data.user.profile_photo_path}`
                    : '/images/default-profile.png';

                const newReply = `
                    <div class="flex items-start text-sm text-gray-700 mt-2">
                        <img src="${profileImg}" alt="Profile Photo" class="w-8 h-8 rounded-full object-cover mr-3">
                        <div>
                            <span class="font-bold">${data.user.name}</span>
                            <p>${data.comment.content}</p>
                        </div>
                    </div>
                `;
                repliesContainer.insertAdjacentHTML('beforeend', newReply);
                this.querySelector('textarea').value = '';
            })
            .catch(console.error);
        });
    });
});
