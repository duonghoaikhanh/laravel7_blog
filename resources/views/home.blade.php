@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if (count($post_categories) > 0)
                        @foreach($post_categories as $category)
                                <h3 class="title_border">
                                    <a href="{{ url(route('post.categories', ['slug' => $category->term_slug])) }}">{!! $category->term_name !!}</a>
                                </h3>
                        @endforeach
                    @else
                    @endif

                    <!-- List post -->
                    @if (count($posts) > 0)
                        @foreach($posts as $post)
                            <h3 class="title_border">
                                <a href="{{ url(route('post.detail', ['slug' => $post->post_name])) }}">
                                    <h5>{!! $post->post_title !!}</h5>
                                    <h5>{!! $post->post_excerpt !!}</h5>
                                </a>
                            </h3>
                        @endforeach
                    @else
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
