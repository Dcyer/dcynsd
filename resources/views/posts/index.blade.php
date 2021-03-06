@extends('layouts.app')

@section('page-header')
    <header class="masthead" style="background-image: url('{{ page_image($page_image) }}')">
        <div class="overlay"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-10 mx-auto">
                    <div class="site-heading">
                        <h1>{{ $title }}</h1>
                        <span class="subheading">{{ $subtitle }}</span>
                    </div>
                </div>
            </div>
        </div>
    </header>
@stop

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-10 mx-auto">
                {{-- 文章列表 --}}
                @foreach ($posts as $post)
                    <div class="post-preview">
                        <a href="{{ $post->url($tag) }}">
                            <h2 class="post-title">{{ $post->title }}</h2>
                            @if ($post->subtitle)
                                <h3 class="post-subtitle">{{ $post->subtitle }}</h3>
                            @endif
                        </a>
                        <p class="post-meta">
                            发布于&nbsp; {{ $post->published_at->diffForHumans() }}
                            @if ($post->tags->count())
                                @foreach($post->tagLinks() as $link)
                                    <span class="badge badge-pill badge-info">{!! $link !!}</span>
                                @endforeach
                            @endif
                        </p>
                    </div>
                    <hr>
                @endforeach

                {{-- 分页 --}}
                <div class="clearfix">
                    {{-- Reverse direction --}}
                    @if ($posts->currentPage() > 1)
                        <a class="btn btn-primary float-left" href="{!! $posts->url($posts->currentPage() - 1) !!}">
                            ←
                            上一页 {{ $tag ? $tag->tag : '' }} 文章
                        </a>
                    @endif
                    @if ($posts->hasMorePages())
                        <a class="btn btn-primary float-right" href="{!! $posts->nextPageUrl() !!}">
                            更多 {{ $tag ? $tag->tag : '' }} 文章
                            →
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop