@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Detail Post</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if (is_object($post))
                        <div class="col-10">
                            <h1 class="h5">{{ $post->post_title }}</h1>
                            <div class="desc-block mb-3">
                                <div class="title-block-desc mb-3">Nội dung chi tiết</div>
                                <p>{!! $post->post_content !!}</p>
                            </div>
                        </div>
                    @else
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
