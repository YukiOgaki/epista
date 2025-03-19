<x-app-layout>
    <div class="container lg:w-3/4 md:w-4/5 w-11/12 mx-auto my-8 px-8 py-4 bg-white shadow-md">

        <x-flash-message :message="session('notice')" />
        <x-validation-errors :errors="$errors" />
        
        <article class="mb-2">
            <h2 class="font-bold font-sans break-normal text-gray-900 pt-6 pb-1 text-3xl md:text-4xl break-words">
                {{ $post->title }}</h2>
            <p class="text-sm md:text-base font-normal text-gray-600">
                <span class="text-red-400 font-bold">
                    {{ date('Y-m-d H:i:s', strtotime('-1 day')) < $post->created_at ? 'NEW' : '' }}
                </span>
                {{ $post->created_at }}
                作成者:{{ $post->user->name }}
            </p>
            <img src="{{ $post->image_url }}" alt="" class="mb-4">
            <p class="text-gray-700 text-base break-all">{!! nl2br(e($post->body)) !!}</p>
            <p class="text-gray-700 text-base break-all text-red-400 font-bold">投票期限:{{ ($post->deadline) }}</p>
        </article>

        <div class="flex flex-row text-center my-4">
            @can('update', $post)
                <a href="{{ route('posts.edit', $post) }}" 
                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-20 mr-2">編集</a>
            @endcan
            @can('delete', $post)
                <form action="{{ route('posts.destroy', $post) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <input type="submit" value="削除" onclick="if(!confirm('削除しますか？')){return false};" 
                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-20">
                </form>
            @endcan
        </div>

        @auth
            <hr class="my-4">

            <div class="flex justify-end">
                <a href="{{ route('posts.comments.create', $post) }}" 
                    class="bg-indigo-400 hover:bg-indigo-600 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline block">ボケる</a>
            </div>
        @endauth

        <section class="font-sans break-normal text-gray-900 ">
            @foreach ($comments as $comment)
                <div class="my-2">
                    <span class="font-bold mr-3">{{ $comment->user->name }}</span>
                    <span class="text-sm">{{ $comment->created_at }}</span>
                    <p class="break-all">{!! nl2br(e($comment->body)) !!}</p>

                    @php
                        $deadline = $post->deadline ?? now()->subDay(); // NULL の場合は昨日の日付
                    @endphp

                    <!-- イイネボタン追加 -->
                    <div class="flex items-center justify-start mt-2">
                        <button class="like-button flex items-center text-blue-500"
                            data-comment-id="{{ $comment->id }}"
                            @if(now()->greaterThan($deadline)) disabled @endif>
                            👍 <span class="like-count ml-1">{{ $comment->likes->count() }}</span>
                        </button>
                    </div>
                    <div class="flex justify-end text-center">
                        @can('update', $comment)
                            <a href="{{ route('posts.comments.edit', [$post, $comment]) }}"
                                class="text-sm bg-green-400 hover:bg-green-600 text-white font-bold py-1 px-2 rounded focus:outline-none focus:shadow-outline w-20 mr-2">
                                編集
                            </a>
                        @endcan
                        @can('delete', $comment)
                            <form action="{{ route('posts.comments.destroy', [$post, $comment]) }}" method="post">
                                @csrf
                                @method('DELETE')
                                <input type="submit" value="削除" onclick="if(!confirm('削除しますか？')){return false};"
                                    class="text-sm bg-red-400 hover:bg-red-600 text-white font-bold py-1 px-2 rounded focus:outline-none focus:shadow-outline w-20">
                            </form>
                        @endcan
                    </div>
                </div>
                <hr>
            @endforeach
        </section>
        
        <!-- JavaScript (イイネ機能) -->
        <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".like-button").forEach(button => {
                button.addEventListener("click", function(event) {
                    // 期限切れの場合は処理を中断
                    if (this.hasAttribute("disabled")) {
                        alert("この投稿の期限が過ぎているため、イイネはできません。");
                        return;
                    }

                    let commentId = this.getAttribute("data-comment-id");
                    let likeCount = this.querySelector(".like-count");

                    fetch(`/comments/${commentId}/like`, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                            "Accept": "application/json",
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({})
                    })
                    .then(response => response.json())
                    .then(data => {
                        likeCount.textContent = data.like_count;
                        this.classList.toggle("liked", data.liked);
                    })
                    .catch(error => console.error("Error:", error));
                });
            });
        });
        </script>
    </div>
</x-app-layout>
