@extends('layouts.app')

@section('content')
    <!-- page-title -->
    <section class="page-title" style="padding: 60px 0 40px; background: #fdfdfd;">
        <div class="auto-container">
            <div class="content-box" style="text-align: left;">
                <ul class="bread-crumb" style="display: inline-flex; gap: 8px; font-weight: 800; font-size: 12px; text-transform: uppercase; letter-spacing: 1.5px; background: #ffffff; padding: 8px 20px; border-radius: 50px; box-shadow: 0 10px 25px rgba(218, 165, 32, 0.15);">
                    <li><a href="{{ route('home') }}" style="color: #0f172a;">Home</a></li>
                    {{-- <li style="color: #0f172a;">/</li> --}}
                    <li><a href="{{ route('blog') }}" style="color: #0f172a;">Blog</a></li>
                    {{-- <li style="color: #bbd700;">/</li> --}}
                    <li style="color: #000000; text-shadow: 1px 1px 2px rgba(255, 215, 0, 0.2);">Reading Article</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- sidebar-page-container -->
    <section class="sidebar-page-container pb_100 pt_50" style="background: #f5f9d9c9;">
        <div class="auto-container">
            <div style="background: #ffffff; border-radius: 60px; padding: 60px; box-shadow: 0 40px 80px rgba(0,0,0,0.06); border: 1px solid rgba(0,0,0,0.02);">
                <div class="row clearfix">
                <div class="col-lg-8 col-md-12 col-sm-12 content-side">
                    <div class="blog-details-content">
                        <!-- Article Header -->
                        <div style="margin-bottom: 40px;">
                            <span style="display: inline-block; padding: 5px 15px; background: #f5f9d9; color: #0f172a; border-radius: 50px; font-weight: 800; font-size: 11px; text-transform: uppercase; margin-bottom: 20px; border: 1px solid rgba(0,0,0,0.05);">{{ $blog->category->name ?? 'Article' }}</span>
                            <h1 style="font-size: 48px; font-weight: 900; color: #0f172a; line-height: 1.1; letter-spacing: -1.5px; margin-bottom: 25px;">{{ $blog->title }}</h1>
                            <div style="display: flex; align-items: center; gap: 25px; padding-bottom: 30px; border-bottom: 1px solid #edf2f7;">
                                <div style="display: flex; align-items: center; gap: 12px;">
                                    {{-- <div style="width: 45px; height: 45px; border-radius: 50%; overflow: hidden; background: #f1f5f9; border: 2px solid #ffffff; box-shadow: 0 5px 10px rgba(0,0,0,0.05);">
                                        <img src="{{ $blog->author->image_full_url ?? 'https://via.placeholder.com/150' }}" alt="Author" style="width: 100%; height: 100%; object-fit: cover;">
                                    </div> --}}
                                    <div>
                                        <span style="display: block; font-size: 14px; font-weight: 800; color: #0f172a;">{{ $blog->author->name ?? 'Molikule Admin' }}</span>
                                        <span style="display: block; font-size: 12px; color: #94a3b8; font-weight: 600;">Author</span>
                                    </div>
                                </div>
                                <div style="height: 30px; width: 1px; background: #edf2f7;"></div>
                                <div>
                                    <span style="display: block; font-size: 14px; font-weight: 800; color: #0f172a;">{{ $blog->published_at ? $blog->published_at->format('F d, Y') : $blog->created_at->format('F d, Y') }}</span>
                                    <span style="display: block; font-size: 12px; color: #94a3b8; font-weight: 600;">Published Date</span>
                                </div>
                            </div>
                        </div>

                        <!-- Featured Image -->
                        <div style="margin-bottom: 50px; border-radius: 40px; overflow: hidden; box-shadow: 0 30px 60px rgba(0,0,0,0.1); border: 8px solid #ffffff;">
                            <img src="{{ $blog->image_full_url }}" alt="{{ $blog->title }}" style="width: 100%; height: auto; display: block;">
                        </div>

                        <!-- Main Content -->
                        <style>
                            .blog-rich-content { color: #475569; line-height: 1.8; font-size: 18px; }
                            .blog-rich-content h2 { font-size: 32px; font-weight: 900; color: #0f172a; margin: 40px 0 20px; }
                            .blog-rich-content h3 { font-size: 24px; font-weight: 800; color: #0f172a; margin: 30px 0 15px; }
                            .blog-rich-content p { margin-bottom: 25px; }
                            .blog-rich-content ul, .blog-rich-content ol { margin-bottom: 30px; padding-left: 20px; }
                            .blog-rich-content li { margin-bottom: 12px; position: relative; list-style: none; padding-left: 30px; }
                            .blog-rich-content ul li::before { content: "\f058"; font-family: "Font Awesome 5 Free"; font-weight: 900; position: absolute; left: 0; color: #bbd700; font-size: 18px; }
                            .blog-rich-content strong { color: #0f172a; font-weight: 800; }
                            .blog-rich-content blockquote { background: #f8fafc; border-left: 5px solid #bbd700; padding: 30px 40px; margin: 40px 0; border-radius: 0 20px 20px 0; font-style: italic; font-size: 20px; color: #0f172a; }
                        </style>

                        <div class="blog-rich-content">
                            {!! $blog->content !!}
                        </div>

                        <!-- Tags & Share -->
                        <div style="margin-top: 60px; padding: 30px; background: #fdfdfd; border-radius: 30px; border: 1px solid #edf2f7; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.02);">
                            <div style="display: flex; align-items: center; gap: 15px;">
                                <span style="font-weight: 800; color: #0f172a; font-size: 13px; text-transform: uppercase; letter-spacing: 1px;">Tags:</span>
                                @foreach($blog->tags as $tag)
                                <a href="{{ route('blog', ['tag' => $tag->slug]) }}" style="padding: 8px 20px; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 50px; font-size: 12px; font-weight: 700; color: #64748b; transition: all 0.3s ease; box-shadow: 0 4px 10px rgba(0,0,0,0.03);">#{{ $tag->name }}</a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4 col-md-12 col-sm-12 sidebar-side">
                    <div style="position: sticky; top: 100px; margin-left: 30px;">
                        <!-- Search Box -->
                        <div style="background: #ffffff; border-radius: 35px; padding: 35px; box-shadow: 0 20px 40px rgba(0,0,0,0.03); border: 1px solid rgba(0,0,0,0.02); margin-bottom: 40px;">
                            <h4 style="font-weight: 900; color: #0f172a; margin-bottom: 20px; font-size: 20px;">Quick Search</h4>
                            <form method="get" action="{{ route('blog') }}">
                                <div style="position: relative;">
                                    <input type="search" name="search" placeholder="Search articles..." required style="width: 100%; padding: 15px 25px; border-radius: 20px; border: 1px solid #f1f5f9; background: #f8fafc; font-weight: 600; font-size: 14px;">
                                    <button type="submit" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #bbd700;"><i class="fas fa-search"></i></button>
                                </div>
                            </form>
                        </div>

                        <!-- Categories -->
                        <div style="background: #bbd700; border-radius: 35px; padding: 35px; box-shadow: 0 20px 40px rgba(15,23,42,0.1); margin-bottom: 40px;">
                            <h4 style="font-weight: 900; color: #ffffff; margin-bottom: 20px; font-size: 20px;">Categories</h4>
                            <ul style="list-style: none; padding: 0;">
                                @foreach($categories as $category)
                                <li style="margin-bottom: 12px;">
                                    <a href="{{ route('blog', ['category' => $category->slug]) }}" style="display: flex; justify-content: space-between; align-items: center; color: #94a3b8; font-weight: 700; font-size: 14px; transition: color 0.3s ease;">
                                        <span>{{ $category->name }}</span>
                                        <span style="background: rgba(187, 215, 0, 0.2); color: #bbd700; padding: 2px 10px; border-radius: 50px; font-size: 11px;">{{ $category->blogs_count }}</span>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>

                        <!-- Recent Articles -->
                        <div style="background: #f5f9d9c9; border-radius: 35px; padding: 35px; border: 1px solid rgba(187, 215, 0, 0.1);">
                            <h4 style="font-weight: 900; color: #0f172a; margin-bottom: 25px; font-size: 20px;">Popular Now</h4>
                            @foreach($recent_posts as $recent)
                            <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 20px;">
                                <a href="{{ route('blog-details', $recent->slug) }}" style="flex-shrink: 0; width: 65px; height: 65px; border-radius: 15px; overflow: hidden; border: 2px solid #ffffff;">
                                    <img src="{{ $recent->image_full_url }}" alt="Post" style="width: 100%; height: 100%; object-fit: cover;">
                                </a>
                                <div>
                                    <h5 style="font-weight: 800; font-size: 14px; line-height: 1.3; margin-bottom: 3px;">
                                        <a href="{{ route('blog-details', $recent->slug) }}" style="color: #0f172a;">{{ Str::limit($recent->title, 40) }}</a>
                                    </h5>
                                    <span style="color: #94a3b8; font-size: 11px; font-weight: 700;">{{ $recent->published_at ? $recent->published_at->format('M d') : $recent->created_at->format('M d') }}</span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
