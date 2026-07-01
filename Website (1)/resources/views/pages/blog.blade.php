@extends('layouts.app')

@section('content')
    <!-- page-title -->
    <section class="page-title" style="padding: 80px 0 50px; background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);">
        <div class="auto-container">
            <div class="content-box" style="text-align: center;">
                <ul class="bread-crumb" style="display: inline-flex; gap: 8px; font-weight: 800; font-size: 12px; text-transform: uppercase; letter-spacing: 1.5px; background: #ffffff; padding: 8px 20px; border-radius: 50px; box-shadow: 0 10px 25px rgba(218, 165, 32, 0.2);">
                    <li><a href="{{ route('home') }}" style="color: #0f172a; text-shadow: 1px 1px 2px rgba(255, 215, 0, 0.3);">Home</a></li>
                    <li style="color: #000000; text-shadow: 1px 1px 2px rgba(255, 215, 0, 0.4);">Blog</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- Blog Header Section -->
    <section class="pt_80 pb_60" style="background: #f5f9d9c9;">
        <div class="auto-container">
            <div style="text-align: center; max-width: 800px; margin: 0 auto;">
                <span style="color: #0f172a; font-weight: 800; text-transform: uppercase; letter-spacing: 2px; font-size: 14px; background: #ffffff; padding: 5px 15px; border-radius: 50px; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">Articles & News</span>
                {{-- <h2 style="font-size: 56px; font-weight: 900; color: #0f172a; letter-spacing: -2px; line-height: 1.1; margin-top: 20px;">The Molikule <span style="color: #ffffff; text-shadow: 2px 2px 4px rgba(0,0,0,0.1);">Journal.</span></h2> --}}
                <p style="color: #0f172a; font-size: 19px; margin-top: 20px; line-height: 1.7; font-weight: 600;">Fresh ideas and simple tips for a greener, safer, and cleaner lifestyle at home and work.</p>
            </div>
        </div>
    </section>

    <!-- sidebar-page-container -->
    <section class="sidebar-page-container pb_100" style="background: #f5f9d9c9;">
        <div class="auto-container">
            <div class="row clearfix">
                <!-- Content Side -->
                <div class="col-lg-8 col-md-12 col-sm-12 content-side">
                    <div class="blog-grid-content">
                        <div class="row clearfix">
                            @forelse($blogs as $blog)
                            <div class="col-lg-12 mb_40">
                                <div style="background: #ffffff; border-radius: 40px; overflow: hidden; box-shadow: 0 30px 60px rgba(0,0,0,0.08); border: 1px solid rgba(0,0,0,0.03); display: flex; align-items: center; transition: all 0.4s ease;">
                                    <!-- Blog Image Side -->
                                    <div style="width: 40%; height: 320px; overflow: hidden;">
                                        <a href="{{ route('blog-details', $blog->slug) }}">
                                            <img src="{{ $blog->image_full_url }}" alt="{{ $blog->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                                        </a>
                                    </div>
                                    <!-- Blog Content Side -->
                                    <div style="width: 60%; padding: 40px;">
                                        <div style="margin-bottom: 20px; display: flex; align-items: center; gap: 15px;">
                                            <span style="padding: 5px 15px; background: #f5f9d9; color: #0f172a; border-radius: 50px; font-weight: 800; font-size: 11px; text-transform: uppercase; border: 1px solid rgba(0,0,0,0.05);">{{ $blog->category->name ?? 'News' }}</span>
                                            <span style="color: #94a3b8; font-size: 13px; font-weight: 700;"><i class="far fa-calendar-alt" style="margin-right: 5px; color: #bbd700;"></i> {{ $blog->published_at ? $blog->published_at->format('M d, Y') : $blog->created_at->format('M d, Y') }}</span>
                                        </div>
                                        <h3 style="font-size: 28px; font-weight: 900; color: #0f172a; margin-bottom: 15px; line-height: 1.2;">
                                            <a href="{{ route('blog-details', $blog->slug) }}" style="color: inherit; transition: color 0.3s;">{{ $blog->title }}</a>
                                        </h3>
                                        <p style="color: #64748b; font-size: 15px; line-height: 1.6; margin-bottom: 25px;">{{ Str::limit($blog->excerpt, 110) }}</p>
                                        <a href="{{ route('blog-details', $blog->slug) }}" style="font-weight: 800; color: #0f172a; text-transform: uppercase; font-size: 12px; letter-spacing: 1px; display: flex; align-items: center; gap: 10px;">
                                            Read More <i class="fas fa-arrow-right" style="color: #bbd700;"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="col-12 text-center py-5">
                                <div style="background: #f8fafc; padding: 60px; border-radius: 40px; border: 2px dashed #e2e8f0;">
                                    <h3 style="color: #64748b; font-weight: 800;">No articles found yet.</h3>
                                    <p style="color: #94a3b8;">Check back soon for new molikule updates.</p>
                                </div>
                            </div>
                            @endforelse
                        </div>
                        
                        <!-- Simple Pagination -->
                        <div class="pagination-wrapper pt_20">
                            {{ $blogs->appends(request()->query())->links('vendor.pagination.custom') }}
                        </div>
                    </div>
                </div>

                <!-- Sidebar Side -->
                <div class="col-lg-4 col-md-12 col-sm-12 sidebar-side">
                    <div style="background: #f8fafc; border-radius: 40px; padding: 40px; border: 1px solid #f1f5f9; position: sticky; top: 100px;">
                        <!-- Search -->
                        <div style="margin-bottom: 45px;">
                            <h4 style="font-weight: 900; color: #0f172a; margin-bottom: 20px;">Search Journal</h4>
                            <form method="get" action="{{ route('blog') }}">
                                <div style="position: relative;">
                                    <input type="search" name="search" placeholder="Look for a topic..." value="{{ request('search') }}" required style="width: 100%; padding: 18px 25px; border-radius: 20px; border: 1px solid #e2e8f0; background: #ffffff; font-weight: 600;">
                                    <button type="submit" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); background: none; border: none; color: #bbd700; font-size: 18px;"><i class="fas fa-search"></i></button>
                                </div>
                            </form>
                        </div>

                        <!-- Recent Posts -->
                        <div style="margin-bottom: 45px;">
                            <h4 style="font-weight: 900; color: #0f172a; margin-bottom: 25px;">Recent Articles</h4>
                            <div class="post-inner">
                                @foreach($recent_posts as $recent)
                                <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 20px;">
                                    <a href="{{ route('blog-details', $recent->slug) }}" style="flex-shrink: 0; width: 80px; height: 80px; border-radius: 20px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
                                        <img src="{{ $recent->image_full_url }}" alt="{{ $recent->title }}" style="width: 100%; height: 100%; object-fit: cover;">
                                    </a>
                                    <div>
                                        <h5 style="font-weight: 800; line-height: 1.3; font-size: 15px; margin-bottom: 5px;">
                                            <a href="{{ route('blog-details', $recent->slug) }}" style="color: #0f172a;">{{ Str::limit($recent->title, 45) }}</a>
                                        </h5>
                                        <span style="color: #94a3b8; font-size: 13px; font-weight: 700;">{{ $recent->published_at ? $recent->published_at->format('M d') : $recent->created_at->format('M d') }}</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Categories -->
                        <div>
                            <h4 style="font-weight: 900; color: #0f172a; margin-bottom: 25px;">Explore Topics</h4>
                            <ul style="list-style: none; padding: 0;">
                                @foreach($categories as $category)
                                <li style="margin-bottom: 12px;">
                                    <a href="{{ route('blog', ['category' => $category->slug]) }}" style="display: flex; justify-content: space-between; align-items: center; padding: 12px 20px; background: {{ request('category') == $category->slug ? '#0f172a' : '#ffffff' }}; color: {{ request('category') == $category->slug ? '#ffffff' : '#475569' }}; border-radius: 15px; font-weight: 800; font-size: 14px; transition: all 0.3s; box-shadow: 0 5px 10px rgba(0,0,0,0.02);">
                                        <span>{{ $category->name }}</span>
                                        <span style="background: rgba(187, 215, 0, 0.2); color: #bbd700; padding: 2px 10px; border-radius: 50px; font-size: 11px;">{{ $category->blogs_count }}</span>
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
