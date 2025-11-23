{{-- Comments --}}
<div id="comments-section">
    <h2 class="text-lg font-semibold mb-4">Comments ({{ $manhwa->approvedComments->count() }})</h2>

    @auth
        {{-- Comment Form --}}
        <div class="mb-6">
            <form id="comment-form" action="{{ route('comments.store', $manhwa) }}" method="POST">
                @csrf
                <div class="flex gap-3">
                    <x-img-profile :user="auth()->user()" />
                    <div class="flex-1">
                        <textarea name="content" id="comment-content" placeholder="Write your comment..." rows="3"
                            class="w-full p-3 border border-gray-300 rounded-lg resize-none focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white text-gray-900"
                            required></textarea>
                        <div class="flex justify-between items-center mt-2">
                            <span class="text-xs text-gray-500">Max 1000 characters</span>
                            <button type="submit"
                                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors">
                                Post Comment
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @else
        <div class="mb-6 p-4 bg-gray-50 rounded-lg text-center">
            <p class="text-gray-600 mb-2">Please login to leave a comment</p>
            <a href="{{ route('login') }}" class="text-blue-600 hover:text-blue-700 font-medium">Login here</a>
        </div>
    @endauth

    {{-- Comments List --}}
    <div id="comments-list" class="space-y-6">
        @forelse($manhwa->approvedComments as $comment)
            <div class="comment-item bg-gray-50 p-4 rounded-lg" data-comment-id="{{ $comment->id }}">
                <div class="flex gap-3">
                    <x-img-profile :user="$comment->user" />
                    <div class="flex-1">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="font-medium text-gray-900">
                                    {{ $comment->user->name }}</h4>
                                <p class="text-xs text-gray-500">
                                    {{ $comment->created_at->format('M d, Y \a\t H:i') }}</p>
                            </div>
                            @auth
                                @if (Auth::id() === $comment->user_id || Auth::user()->is_admin)
                                    <div class="flex gap-2">
                                        <button class="edit-comment-btn text-blue-600 hover:text-blue-700 text-sm"
                                            data-comment-id="{{ $comment->id }}">
                                            Edit
                                        </button>
                                        <button class="delete-comment-btn text-red-600 hover:text-red-700 text-sm"
                                            data-comment-id="{{ $comment->id }}">
                                            Delete
                                        </button>
                                    </div>
                                @endif
                            @endauth
                        </div>
                        <div class="comment-content mt-2 text-gray-700">
                            {{ $comment->content }}
                        </div>

                        <div class="flex items-center gap-3 mt-2">
                            @auth
                                <button class="reply-btn text-sm text-gray-600 hover:text-blue-600 font-medium"
                                    data-comment-id="{{ $comment->id }}" data-user-name="{{ $comment->user->name }}">
                                    <i class="bi bi-reply"></i> Reply
                                </button>
                            @endauth

                            @if ($comment->replies && $comment->replies->count() > 0)
                                <button class="toggle-replies-btn text-sm text-blue-600 hover:text-blue-700 font-medium"
                                    data-comment-id="{{ $comment->id }}">
                                    <i class="bi bi-chevron-down"></i>
                                    <span class="replies-text">Show {{ $comment->replies->count() }}
                                        {{ Str::plural('reply', $comment->replies->count()) }}</span>
                                </button>
                            @endif
                        </div>

                        {{-- Replies --}}
                        @if ($comment->replies && $comment->replies->count() > 0)
                            <div class="replies-list mt-4 ml-8 space-y-3 border-l-2 border-gray-300 pl-4 hidden"
                                data-comment-id="{{ $comment->id }}">
                                @foreach ($comment->replies->sortBy('created_at') as $reply)
                                    <div class="reply-item bg-white p-3 rounded-lg"
                                        data-comment-id="{{ $reply->id }}">
                                        <div class="flex gap-3">
                                            <x-img-profile :user="$reply->user" />
                                            <div class="flex-1">
                                                <div class="flex items-center justify-between">
                                                    <div>
                                                        <h5 class="font-medium text-sm text-gray-900">
                                                            {{ $reply->user->name }}</h5>
                                                        <p class="text-xs text-gray-500">
                                                            {{ $reply->created_at->format('M d, Y \a\t H:i') }}
                                                        </p>
                                                    </div>
                                                    @auth
                                                        @if (Auth::id() === $reply->user_id || Auth::user()->is_admin)
                                                            <div class="flex gap-2">
                                                                <button
                                                                    class="edit-comment-btn text-blue-600 hover:text-blue-700 text-xs"
                                                                    data-comment-id="{{ $reply->id }}">
                                                                    Edit
                                                                </button>
                                                                <button
                                                                    class="delete-comment-btn text-red-600 hover:text-red-700 text-xs"
                                                                    data-comment-id="{{ $reply->id }}">
                                                                    Delete
                                                                </button>
                                                            </div>
                                                        @endif
                                                    @endauth
                                                </div>
                                                <div class="comment-content mt-1 text-sm text-gray-700">
                                                    @php
                                                        // Parse mentions in content (escape content first, then add mention styling)
                                                        $content = e($reply->content);
                                                        $content = preg_replace(
                                                            '/@(\w+)/',
                                                            '<span class="text-blue-600 font-medium">@$1</span>',
                                                            $content,
                                                        );
                                                    @endphp
                                                    {!! nl2br($content) !!}
                                                </div>
                                                @auth
                                                    <button
                                                        class="reply-btn text-sm text-gray-600 hover:text-blue-600 mt-2 font-medium"
                                                        data-comment-id="{{ $comment->id }}"
                                                        data-user-name="{{ $reply->user->name }}"
                                                        data-reply-to="{{ $reply->user->name }}">
                                                        <i class="bi bi-reply"></i> Reply
                                                    </button>
                                                @endauth
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center text-gray-500 py-8">
                <p>No comments yet. Be the first to comment!</p>
            </div>
        @endforelse
    </div>
</div>
@push('scripts')
    <script src="{{ asset('js/component/commentField.js') }}" :manhwa="{{ json_encode($manhwa) }}"></script>
@endpush
