@extends('layouts.app')

@section('content')
    <thread-view :thread="{{ $thread }}" inline-template>
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="card mb-3">
                        <div class="card-header d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <img class="rounded-circle" src="{{ $thread->creator->avatar_path }}" alt="" width="50" height="50">
                                <a href="/profiles/{{ $thread->creator->name }}">{{ $thread->creator->name }}</a> posted:
                                {{ $thread->title }}
                            </div>
                            @can ('update', $thread)
                                <div>
                                    <form action="{{ $thread->path() }}" method="post">
                                        @method('DELETE')
                                        @csrf

                                        <button class="btn btn-sm btn-danger">Delete Thread</button>
                                    </form>
                                </div>
                            @endcan
                        </div>
                        <div class="card-body">{{ $thread->body }}</div>
                    </div>

                    <replies @created="count++" @removed="count--" :is-locked="locked"></replies>
                </div>

                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            This thread was published {{ $thread->created_at->diffForHumans() }}
                            by <a href="#">{{ $thread->creator->name }}</a>, and currently
                            has <span>@{{ count }}</span> {{ Str::plural('comment', $thread->replies_count) }}.

                            <div>
                                @auth
                                    <span>
                                        <subscribe-button :active="{{ json_encode($thread->isSubscribedTo) }}"></subscribe-button>
                                    </span>
                                @endauth

                                @if (auth()->check() && auth()->user()->isAdmin())
                                    <span>
                                        <button class="btn btn-light" @click="toggleLock">
                                            @{{ btnLabel }}
                                        </button>
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </thread-view>
@endsection
