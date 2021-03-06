@extends('layouts.app', [
  'title' => $post->title,
  'meta_description' => $post->meta_description ?? config('blog.description'),
])

@section('page-header')
    <header class="masthead" style="background-image: url('{{ page_image($post->page_image) }}')">
        <div class="overlay"></div>
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-md-10 mx-auto">
                    <div class="post-heading">
                        <h1>{{ $post->title }}</h1>
                        <h2 class="subheading">{{ $post->subtitle }}</h2>
                        <span class="meta">
                            发布于&nbsp; {{ $post->published_at->diffForHumans() }}
                            @if ($post->tags->count())
                                @foreach($post->tagLinks() as $link)
                                    <span class="badge badge-pill badge-info">{!! $link !!}</span>
                                @endforeach
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </header>
@stop

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-lg-10 col-md-10 mx-auto">
                {{-- 文章详情 --}}
                <article class="markdown">
                    {!! $post->content_html !!}
                </article>

                <br>

                <!-- 分享 -->
                <div class="social-share" style="text-align: center"></div>

                <!-- 署名 -->
                <div class="article-license mb10 mt20 alert alert-info" style="border-radius:10px">
                    @if($post->licenses_name)
                        <div class="license-item license-sa" style="text-align: center; font-weight: bold;">
                            本文转载于 <a target="_blank" href="{{ $post->licenses_link }}">{{ $post->licenses_name }}</a>
                            <br>
                            转载和引用遵循
                            <a href="http://creativecommons.org/licenses/by-nc/2.5/cn/" target="_blank"
                               rel="noreferrer noopener" class="alert-link">
                                署名-非商业性使用 2.5 中国大陆</a> 进行许可。<br>
                        </div>
                    @else
                        <div class="license-item license-sa" style="text-align: center; font-weight: bold;">
                            本文由
                            <a target="_blank" href="http://www.dcy1997.cn"
                               class="alert-link">{{ config('blog.author') }}</a>
                            创作
                            <br>
                            转载和引用遵循
                            <a href="http://creativecommons.org/licenses/by-nc/2.5/cn/" target="_blank"
                               rel="noreferrer noopener" class="alert-link">
                                署名-非商业性使用 2.5 中国大陆</a> 进行许可。<br>
                        </div>
                    @endif
                </div>

                {{-- 上一篇、下一篇导航 --}}
                <div class="clearfix">
                    {{-- Reverse direction --}}
                    @if ($post->newerPost($tag))
                        <a class="btn btn-primary float-left" href="{!! $post->newerPost($tag)->url($tag) !!}">
                            ←
                            上一篇 {{ $tag ? $tag->tag : '' }} 文章
                        </a>
                    @endif
                    @if ($post->olderPost($tag))
                        <a class="btn btn-primary float-right" href="{!! $post->olderPost($tag)->url($tag) !!}">
                            下一篇 {{ $tag ? $tag->tag : '' }} 文章
                            →
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script>
        // 加载 语法高亮
        hljs.initHighlightingOnLoad();
    </script>
@stop
