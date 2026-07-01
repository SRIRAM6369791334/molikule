@extends('layouts.app')


@section('title', 'Careers | Join Our Team of Chemical Innovators | Molikule')
@section('meta_description', "Molikule Green Care � India's trusted provider of eco-conscious hygiene, laundry care, auto care, and specialty cleaning solutions for homes, institutions, and industries.")
@section('meta_keywords', 'sustainable hygiene solutions India, eco-friendly cleaning products, institutional hygiene products, healthcare disinfectants India, laundry care solutions for hotels, auto care chemicals India, green cleaning company India, specialty chemical company India, commercial cleaning products, facility management hygiene solutions')
@section('content')

    <style>
        /* Custom Styling for High-End Design Taste */
        .mgc-career-card {
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            border: 1px solid #f1f5f9;
            background: #ffffff;
            position: relative;
            overflow: hidden;
        }

        .mgc-career-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #bbd700, #1a9fd4);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .mgc-career-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 30px 60px rgba(15, 23, 42, 0.08), 0 10px 20px rgba(15, 23, 42, 0.03);
            border-color: rgba(187, 215, 0, 0.25);
        }

        .mgc-career-card:hover::before {
            opacity: 1;
        }

        .mgc-career-card .icon-container {
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .mgc-career-card:hover .icon-container {
            transform: scale(1.15) rotate(4deg);
        }

        .mgc-input-group {
            position: relative;
            margin-bottom: 24px;
        }

        .mgc-input-label {
            font-size: 12px;
            font-weight: 800;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 8px;
            display: block;
            transition: color 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .mgc-input-group:focus-within .mgc-input-label {
            color: #1a9fd4;
        }

        .mgc-input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .mgc-input-icon {
            position: absolute;
            left: 20px;
            color: #94a3b8;
            font-size: 16px;
            transition: color 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            pointer-events: none;
        }

        .mgc-input-wrapper:focus-within .mgc-input-icon {
            color: #1a9fd4;
        }

        .mgc-career-form-input {
            width: 100%;
            padding: 16px 20px 16px 52px;
            /* Pad left to clear prefix icon */
            border-radius: 14px;
            border: 1px solid #e2e8f0;
            background: rgba(248, 250, 252, 0.6);
            transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            color: #0f172a;
            font-weight: 500;
            font-size: 15px;
        }

        .mgc-career-form-input::placeholder {
            color: #94a3b8;
            font-weight: 400;
        }

        .mgc-career-form-input:hover {
            border-color: #cbd5e1;
            background: rgba(248, 250, 252, 0.9);
        }

        .mgc-career-form-input:focus {
            background: #ffffff;
            outline: none;
            border-color: #1a9fd4;
            box-shadow: 0 0 0 4px rgba(26, 159, 212, 0.12);
            transform: translateY(-1px);
        }

        /* Textarea doesn't need icon padding */
        textarea.mgc-career-form-input {
            padding-left: 20px;
        }

        .mgc-dropzone {
            border: 2px dashed #cbd5e1;
            border-radius: 14px;
            padding: 40px 20px;
            text-align: center;
            background: rgba(248, 250, 252, 0.6);
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
        }

        .mgc-dropzone:hover {
            border-color: #1a9fd4;
            background: rgba(26, 159, 212, 0.03);
            transform: translateY(-1px);
        }

        .mgc-dropzone.dragover {
            border-color: #bbd700;
            background: rgba(187, 215, 0, 0.06);
            box-shadow: 0 0 0 4px rgba(187, 215, 0, 0.1);
        }

        .mgc-dropzone:hover .upload-icon {
            transform: translateY(-4px) scale(1.1);
            color: #1a9fd4 !important;
        }

        .upload-icon {
            transition: all 0.3s ease;
        }

        /* Premium Button Style with Sweep Light Effect */
        .mgc-btn-submit {
            width: 100%;
            background: #0f172a;
            color: #ffffff;
            padding: 18px;
            border-radius: 14px;
            font-weight: 900;
            font-size: 16px;
            border: none;
            cursor: pointer;
            transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.12);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-top: 30px;
            position: relative;
            overflow: hidden;
        }

        .mgc-btn-submit::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.15), transparent);
            transition: left 0.6s ease;
        }

        .mgc-btn-submit:hover {
            background: #bbd700;
            color: #0f172a;
            box-shadow: 0 12px 30px rgba(187, 215, 0, 0.3);
            transform: translateY(-2px);
        }

        .mgc-btn-submit:hover::after {
            left: 100%;
        }

        .mgc-btn-submit:active {
            transform: translateY(0);
            box-shadow: 0 8px 20px rgba(187, 215, 0, 0.2);
        }

        /* Selection Roadmap Timeline (referencing about.blade.php styles) */
        .hw-roadmap {
            position: relative;
            max-width: 1000px;
            margin: 0 auto;
            padding: 40px 0;
            display: flex;
            flex-direction: column;
        }

        .hw-roadmap__line {
            position: absolute;
            left: 50%;
            top: 0;
            bottom: 0;
            width: 3px;
            background: linear-gradient(to bottom, #bbd700 0%, #1a9fd4 50%, #059669 85%, #ea580c 100%);
            transform: translateX(-50%);
            z-index: 1;
        }

        .hw-roadmap__item {
            position: relative;
            display: flex;
            width: 50%;
            padding: 20px 45px;
            box-sizing: border-box;
            z-index: 2;
        }

        .hw-roadmap__item--left {
            align-self: flex-start;
            justify-content: flex-end;
            text-align: right;
        }

        .hw-roadmap__item--right {
            align-self: flex-end;
            justify-content: flex-start;
            margin-left: 50%;
            text-align: left;
        }

        .hw-roadmap__content {
            background: #ffffff;
            border-radius: 24px;
            padding: 32px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.02), 0 1px 3px rgba(0, 0, 0, 0.01);
            width: 100%;
            max-width: 440px;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
        }

        .hw-roadmap__content:hover {
            transform: translateY(-6px);
            box-shadow: 0 20px 45px rgba(15, 23, 42, 0.06), 0 1px 3px rgba(15, 23, 42, 0.02);
            border-color: rgba(187, 215, 0, 0.35);
        }

        .hw-roadmap__header {
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 16px;
        }

        .hw-roadmap__item--left .hw-roadmap__header {
            flex-direction: row-reverse;
        }

        .hw-roadmap__step-num {
            font-size: 32px;
            font-weight: 900;
            line-height: 1;
            font-family: 'Outfit', sans-serif;
        }

        .hw-roadmap__icon {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .hw-roadmap__dot {
            position: absolute;
            top: 36px;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #ffffff;
            z-index: 3;
            transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            box-shadow: 0 0 0 4px #ffffff;
        }

        .hw-roadmap__item--left .hw-roadmap__dot {
            right: -9px;
        }

        .hw-roadmap__item--right .hw-roadmap__dot {
            left: -9px;
        }

        .hw-roadmap__item:hover .hw-roadmap__dot {
            transform: scale(1.35);
        }

        .hw-roadmap__body h4 {
            font-size: 18px;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 8px;
        }

        .hw-roadmap__body p {
            font-size: 14px;
            color: #64748b;
            line-height: 1.6;
            margin: 0;
        }

        /* Colors by timeline node */
        .hw-roadmap__item--1 .hw-roadmap__step-num {
            color: #bbd700;
        }

        .hw-roadmap__item--1 .hw-roadmap__icon {
            background: rgba(187, 215, 0, 0.1);
            color: #9aaf00;
        }

        .hw-roadmap__item--1 .hw-roadmap__content:hover .hw-roadmap__icon {
            background: #bbd700;
            color: #0f172a;
        }

        .hw-roadmap__item--1 .hw-roadmap__dot {
            border: 4px solid #bbd700;
        }

        .hw-roadmap__item--1:hover .hw-roadmap__dot {
            background: #bbd700;
        }

        .hw-roadmap__item--2 .hw-roadmap__step-num {
            color: #1a9fd4;
        }

        .hw-roadmap__item--2 .hw-roadmap__icon {
            background: rgba(26, 159, 212, 0.1);
            color: #1a9fd4;
        }

        .hw-roadmap__item--2 .hw-roadmap__content:hover .hw-roadmap__icon {
            background: #1a9fd4;
            color: #ffffff;
        }

        .hw-roadmap__item--2 .hw-roadmap__dot {
            border: 4px solid #1a9fd4;
        }

        .hw-roadmap__item--2:hover .hw-roadmap__dot {
            background: #1a9fd4;
        }

        .hw-roadmap__item--3 .hw-roadmap__step-num {
            color: #059669;
        }

        .hw-roadmap__item--3 .hw-roadmap__icon {
            background: rgba(5, 150, 105, 0.1);
            color: #059669;
        }

        .hw-roadmap__item--3 .hw-roadmap__content:hover .hw-roadmap__icon {
            background: #059669;
            color: #ffffff;
        }

        .hw-roadmap__item--3 .hw-roadmap__dot {
            border: 4px solid #059669;
        }

        .hw-roadmap__item--3:hover .hw-roadmap__dot {
            background: #059669;
        }

        .hw-roadmap__item--4 .hw-roadmap__step-num {
            color: #ea580c;
        }

        .hw-roadmap__item--4 .hw-roadmap__icon {
            background: rgba(234, 88, 12, 0.1);
            color: #ea580c;
        }

        .hw-roadmap__item--4 .hw-roadmap__content:hover .hw-roadmap__icon {
            background: #ea580c;
            color: #ffffff;
        }

        .hw-roadmap__item--4 .hw-roadmap__dot {
            border: 4px solid #ea580c;
        }

        .hw-roadmap__item--4:hover .hw-roadmap__dot {
            background: #ea580c;
        }

        .hw-roadmap__item--5 .hw-roadmap__step-num {
            color: #bbd700;
        }

        .hw-roadmap__item--5 .hw-roadmap__icon {
            background: rgba(187, 215, 0, 0.1);
            color: #9aaf00;
        }

        .hw-roadmap__item--5 .hw-roadmap__content:hover .hw-roadmap__icon {
            background: #bbd700;
            color: #0f172a;
        }

        .hw-roadmap__item--5 .hw-roadmap__dot {
            border: 4px solid #bbd700;
        }

        .hw-roadmap__item--5:hover .hw-roadmap__dot {
            background: #bbd700;
        }

        @media (max-width: 767px) {
            .hw-roadmap__line {
                left: 20px;
            }

            .hw-roadmap__item {
                width: 100%;
                padding-left: 55px;
                padding-right: 0;
                text-align: left !important;
            }

            .hw-roadmap__item--right {
                margin-left: 0;
            }

            .hw-roadmap__header {
                flex-direction: row !important;
            }

            .hw-roadmap__item--left .hw-roadmap__dot,
            .hw-roadmap__item--right .hw-roadmap__dot {
                left: 11px;
            }
        }

        /* Career Form Nice Select Overrides */
        .mgc-input-wrapper .nice-select.mgc-career-form-input {
            height: auto;
            line-height: 1.5;
            float: none;
            display: flex;
            align-items: center;
        }
        .mgc-input-wrapper .nice-select.mgc-career-form-input::after {
            right: 20px;
            height: 8px;
            width: 8px;
            margin-top: -6px;
        }
        .mgc-input-wrapper .nice-select.mgc-career-form-input .list {
            width: 100%;
            border-radius: 14px;
            box-shadow: 0 15px 35px rgba(15, 23, 42, 0.1);
            border: 1px solid #e2e8f0;
            margin-top: 8px;
            padding: 8px 0;
            max-height: 280px;
            overflow-y: auto;
        }
        .mgc-input-wrapper .nice-select.mgc-career-form-input .option {
            padding: 12px 24px;
            font-size: 15px;
            font-weight: 500;
            color: #475569;
            line-height: 1.5;
            min-height: auto;
            transition: all 0.2s ease;
        }
        .mgc-input-wrapper .nice-select.mgc-career-form-input .option:hover, 
        .mgc-input-wrapper .nice-select.mgc-career-form-input .option.focus {
            background: #f8fafc;
            color: #1a9fd4;
        }
        .mgc-input-wrapper .nice-select.mgc-career-form-input .option.selected {
            font-weight: 700;
            color: #0f172a;
            background: rgba(26, 159, 212, 0.1);
        }
        .mgc-input-wrapper .nice-select.mgc-career-form-input .current {
            display: block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            margin-right: 20px;
        }
    </style>

    {{-- =====================================================
    PREMIUM HERO SECTION
    ====================================================== --}}
    <section class="page-title"
        style="padding: 140px 0 220px; background: linear-gradient(135deg, #090d16 0%, #0f172a 100%); position: relative; overflow: hidden;">
        <!-- Decorative Glow Accents -->
        <div
            style="position: absolute; top: -120px; left: 30%; width: 600px; height: 350px; background: radial-gradient(circle, rgba(187,215,0,0.15) 0%, transparent 70%); filter: blur(60px); pointer-events: none;">
        </div>
        <div
            style="position: absolute; bottom: -50px; right: 20%; width: 500px; height: 300px; background: radial-gradient(circle, rgba(26,159,212,0.12) 0%, transparent 70%); filter: blur(50px); pointer-events: none;">
        </div>

        <div class="auto-container">
            <div class="content-box"
                style="text-align: center; max-width: 800px; margin: 0 auto; position: relative; z-index: 10;">

                <div
                    style="display: inline-flex; align-items: center; gap: 8px; padding: 6px 18px; border-radius: 50px; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.08); color: #bbd700; font-size: 12px; font-weight: 800; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 28px; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
                    <span
                        style="width: 8px; height: 8px; border-radius: 50%; background: #bbd700; display: inline-block; box-shadow: 0 0 10px #bbd700;"></span>
                    We Are Hiring
                </div>

                <h1
                    style="font-size: 64px; font-weight: 900; color: #ffffff; margin-bottom: 24px; letter-spacing: -2px; line-height: 1.1; font-family: 'Outfit', sans-serif;">
                    Drive Your Career <br>
                    <span
                        style="background: linear-gradient(90deg, #bbd700, #1a9fd4); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">Forward
                        With Us</span>
                </h1>
                <p
                    style="color: #94a3b8; font-size: 19px; line-height: 1.8; margin-bottom: 0; font-weight: 400; max-width: 720px; margin-left: auto; margin-right: auto;">
                    Join the Molikule Green Care team. We are looking for passionate minds ready to revolutionize the
                    industrial chemistry and preventive care industry.
                </p>
            </div>
        </div>
    </section>

    {{-- =====================================================
    OVERLAPPING APPLICATION FORM CARD
    ====================================================== --}}
    <section style="position: relative; z-index: 20; margin-top: -140px; padding-bottom: 100px;">
        <div class="auto-container">
            <div
                style="background: #ffffff; border-radius: 35px; box-shadow: 0 30px 80px rgba(15,23,42,0.1), 0 10px 30px rgba(15,23,42,0.04); overflow: hidden; border: 1px solid #f1f5f9; display: flex; flex-wrap: wrap;">

                <!-- Left Side - Brand Info Panel (Agency Dark Theme) -->
                <div
                    style="background: linear-gradient(150deg, #0f172a 0%, #1e293b 100%); padding: 60px 50px; flex: 1; min-width: 320px; display: flex; flex-direction: column; justify-content: space-between; position: relative; overflow: hidden;">
                    <!-- Ambient Glow Inside -->
                    <div
                        style="position: absolute; bottom: -80px; left: -80px; width: 260px; height: 260px; background: radial-gradient(circle, rgba(187,215,0,0.06) 0%, transparent 70%); border-radius: 50%;">
                    </div>
                    <div
                        style="position: absolute; top: -60px; right: -60px; width: 220px; height: 220px; background: radial-gradient(circle, rgba(26,159,212,0.06) 0%, transparent 70%); border-radius: 50%;">
                    </div>

                    <div style="position: relative; z-index: 2;">
                        <span
                            style="display: inline-block; padding: 6px 14px; background: rgba(187,215,0,0.1); border: 1px solid rgba(187,215,0,0.20); border-radius: 50px; color: #bbd700 !important; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 30px;">Direct
                            Path</span>
                        <h3
                            style="font-size: 32px; font-weight: 900; color: #ffffff !important; margin-bottom: 18px; letter-spacing: -0.5px; line-height: 1.2; font-family: 'Outfit', sans-serif;">
                            Join the Green Revolution</h3>
                        <p style="color: #94a3b8 !important; font-size: 15.5px; line-height: 1.8; margin-bottom: 48px;">
                            We are building high-performance, eco-safe industrial solutions. Tell us about your journey and
                            showcase how you can contribute to sustainable chemistry.
                        </p>

                        <!-- Feature 1 -->
                        <div style="margin-bottom: 32px; display: flex; align-items: flex-start; gap: 18px;">
                            <div
                                style="width: 48px; height: 48px; border-radius: 14px; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.07); display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 8px 20px rgba(0,0,0,0.15);">
                                <i class="fas fa-bolt" style="color: #bbd700 !important; font-size: 18px;"></i>
                            </div>
                            <div>
                                <h4
                                    style="font-weight: 800; color: #ffffff !important; font-size: 16px; margin-bottom: 6px;">
                                    Swift Process</h4>
                                <p style="color: #94a3b8 !important; font-size: 13.5px; line-height: 1.6; margin: 0;">We
                                    respect your time. Direct applications are reviewed by hiring managers within 2 days.
                                </p>
                            </div>
                        </div>

                        <!-- Feature 2 -->
                        <div style="margin-bottom: 32px; display: flex; align-items: flex-start; gap: 18px;">
                            <div
                                style="width: 48px; height: 48px; border-radius: 14px; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.07); display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 8px 20px rgba(0,0,0,0.15);">
                                <i class="fas fa-seedling" style="color: #1a9fd4 !important; font-size: 18px;"></i>
                            </div>
                            <div>
                                <h4
                                    style="font-weight: 800; color: #ffffff !important; font-size: 16px; margin-bottom: 6px;">
                                    Ecological Impact</h4>
                                <p style="color: #94a3b8 !important; font-size: 13.5px; line-height: 1.6; margin: 0;">Work
                                    on solutions that directly reduce industrial pollution and toxic chemical footprint.</p>
                            </div>
                        </div>

                        <!-- Feature 3 -->
                        <div style="display: flex; align-items: flex-start; gap: 18px;">
                            <div
                                style="width: 48px; height: 48px; border-radius: 14px; background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.07); display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: 0 8px 20px rgba(0,0,0,0.15);">
                                <i class="fas fa-users" style="color: #bbd700 !important; font-size: 18px;"></i>
                            </div>
                            <div>
                                <h4
                                    style="font-weight: 800; color: #ffffff !important; font-size: 16px; margin-bottom: 6px;">
                                    Collaborative Vibe</h4>
                                <p style="color: #94a3b8 !important; font-size: 13.5px; line-height: 1.6; margin: 0;">Flat
                                    hierarchy, transparent communication, and continuous learning opportunities.</p>
                            </div>
                        </div>
                    </div>

                    <div
                        style="margin-top: 60px; padding-top: 30px; border-top: 1px solid rgba(255,255,255,0.08); position: relative; z-index: 2;">
                        <p
                            style="color: #ffffff !important;font-size: 13px;margin-bottom: 6px;font-weight: 600;text-transform: uppercase;letter-spacing: 0.5px;">
                            Direct Recruitment Email</p>
                        <a href="mailto:hr@molikule.com"
                            style="color: #bbd700 !important; font-weight: 800; font-size: 17px; text-decoration: none; transition: color 0.3s;"
                            onmouseover="this.style.color='#ffffff'"
                            onmouseout="this.style.color='#bbd700'">hr@molikule.com</a>
                    </div>
                </div>

                <div style="padding: 60px 50px; flex: 1.5; min-width: 320px; background: #ffffff;">
                    @if(session('success'))
                        <div style="background: #ecfdf5; border: 1px solid #10b981; color: #065f46; padding: 15px 20px; border-radius: 12px; margin-bottom: 25px; font-weight: 600; font-size: 14px;">
                            <i class="fas fa-check-circle" style="margin-right: 8px;"></i> {{ session('success') }}
                        </div>
                    @endif
                    @if($errors->any())
                        <div style="background: #fef2f2; border: 1px solid #ef4444; color: #991b1b; padding: 15px 20px; border-radius: 12px; margin-bottom: 25px; font-weight: 600; font-size: 14px;">
                            <ul style="margin: 0; padding-left: 20px;">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('careers.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="mgc-input-group">
                                    <label class="mgc-input-label" for="first_name_input">First Name <span
                                            style="color: #ef4444;">*</span></label>
                                    <div class="mgc-input-wrapper">
                                        <i class="far fa-user mgc-input-icon"></i>
                                        <input type="text" id="first_name_input" name="first_name" placeholder="John"
                                            class="mgc-career-form-input" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="mgc-input-group">
                                    <label class="mgc-input-label" for="last_name_input">Last Name <span
                                            style="color: #ef4444;">*</span></label>
                                    <div class="mgc-input-wrapper">
                                        <i class="far fa-user mgc-input-icon"></i>
                                        <input type="text" id="last_name_input" name="last_name" placeholder="Doe"
                                            class="mgc-career-form-input" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <div class="mgc-input-group">
                                    <label class="mgc-input-label" for="email_input">Email Address <span
                                            style="color: #ef4444;">*</span></label>
                                    <div class="mgc-input-wrapper">
                                        <i class="far fa-envelope mgc-input-icon"></i>
                                        <input type="email" id="email_input" name="email" placeholder="john@example.com"
                                            class="mgc-career-form-input" required>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-4">
                                <div class="mgc-input-group">
                                    <label class="mgc-input-label" for="phone_input">Phone Number</label>
                                    <div class="mgc-input-wrapper">
                                        <i class="fas fa-mobile-alt mgc-input-icon"></i>
                                        <input type="tel" id="phone_input" name="phone" placeholder="+1 (555) 000-0000"
                                            class="mgc-career-form-input">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4" style="position: relative; z-index: 50;">
                            <div class="mgc-input-group">
                                <label class="mgc-input-label" for="position_select">Position Applied For <span
                                        style="color: #ef4444;">*</span></label>
                                <div class="mgc-input-wrapper">
                                    <i class="fas fa-briefcase mgc-input-icon"></i>
                                    <select id="position_select" name="position" class="mgc-career-form-input" required
                                        style="appearance: none; background-image: url('data:image/svg+xml;charset=utf-8,%3Csvg xmlns=%27http://www.w3.org/2000/svg%27 width=%2724%27 height=%2724%27 viewBox=%270 0 24 24%27 fill=%27none%27 stroke=%27%23475569%27 stroke-width=%272%27 stroke-linecap=%27round%27 stroke-linejoin=%27round%27%3E%3Cpolyline points=%276 9 12 15 18 9%27/%3E%3C/svg%3E'); background-repeat: no-repeat; background-position: right 18px center; background-size: 16px;">
                                        <option value="" disabled selected>Select a position...</option>
                                        <option value="General Application">General Application</option>
                                        @if(isset($positions))
                                            @foreach($positions as $pos)
                                                <option value="{{ $pos->title }}">{{ $pos->title }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="mgc-input-group">
                                <label class="mgc-input-label" for="resume-input">Resume / CV <span
                                        style="color: #ef4444;">*</span></label>
                                <div class="mgc-dropzone" id="resume-dropzone">
                                    <input type="file" name="resume" id="resume-input" accept=".pdf,.doc,.docx,.txt"
                                        style="position: absolute; width: 100%; height: 100%; top: 0; left: 0; opacity: 0; cursor: pointer; z-index: 10;"
                                        required>

                                    <div id="dropzone-default">
                                        <div
                                            style="width: 54px; height: 54px; margin: 0 auto 16px; background: #ffffff; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 20px rgba(15,23,42,0.04); border: 1px solid #f1f5f9;">
                                            <i class="fas fa-cloud-upload-alt upload-icon"
                                                style="font-size: 22px; color: #1a9fd4;"></i>
                                        </div>
                                        <p style="font-size: 15px; font-weight: 700; color: #0f172a; margin-bottom: 4px;">
                                            Click to upload or drag & drop
                                        </p>
                                        <p style="font-size: 12px; color: #64748b; margin: 0;">PDF, DOCX, or TXT up to 5MB
                                        </p>
                                    </div>

                                    <div id="dropzone-preview" style="display: none; position: relative; z-index: 15;">
                                        <div
                                            style="width: 54px; height: 54px; margin: 0 auto 16px; background: rgba(26,159,212,0.06); border-radius: 50%; display: flex; align-items: center; justify-content: center; border: 1px solid rgba(26,159,212,0.12);">
                                            <i class="fas fa-file-pdf" id="file-icon"
                                                style="font-size: 22px; color: #1a9fd4;"></i>
                                        </div>
                                        <p style="font-size: 15px; font-weight: 700; color: #0f172a; margin-bottom: 4px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 90%; margin-left: auto; margin-right: auto;"
                                            id="file-name">
                                            filename.pdf
                                        </p>
                                        <p style="font-size: 12px; color: #64748b; margin-bottom: 12px;" id="file-size">1.2
                                            MB</p>
                                        <button type="button" id="remove-file-btn" class="btn"
                                            style="background: #ef4444; color: #ffffff; border: none; padding: 8px 18px; border-radius: 30px; font-size: 11px; font-weight: 800; cursor: pointer; position: relative; z-index: 25; transition: all 0.2s;">
                                            REMOVE FILE
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="mgc-input-group">
                                <label class="mgc-input-label" for="cover_letter_input">Cover Letter / Note
                                    (Optional)</label>
                                <textarea id="cover_letter_input" name="cover_letter" rows="4"
                                    placeholder="Tell us about yourself and why you want to join Molikule..."
                                    class="mgc-career-form-input" style="resize: none;"></textarea>
                            </div>
                        </div>

                        <button type="submit" class="mgc-btn-submit">
                            Submit Application
                            <i class="fas fa-paper-plane" style="font-size: 14px;"></i>
                        </button>

                        <p
                            style="text-align: center; font-size: 12.5px; color: #94a3b8; margin-top: 18px; font-weight: 600;">
                            By submitting, you agree to our Privacy Policy regarding candidate data.
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </section>

    {{-- =====================================================
    "HOW WE HIRE" SELECTION JOURNEY TIMELINE
    ====================================================== --}}
    <section class="pt_120 pb_120"
        style="background: #f8fafc; border-top: 1px solid #e2e8f0; border-bottom: 1px solid #e2e8f0; position: relative; overflow: hidden;">
        <!-- Background Ambient Blurs -->
        <div
            style="position: absolute; top: -100px; left: -100px; width: 400px; height: 400px; background: radial-gradient(circle, rgba(187,215,0,0.03) 0%, transparent 70%); border-radius: 50%;">
        </div>
        <div
            style="position: absolute; bottom: -100px; right: -100px; width: 450px; height: 450px; background: radial-gradient(circle, rgba(26,159,212,0.03) 0%, transparent 70%); border-radius: 50%;">
        </div>

        <div class="auto-container" style="position: relative; z-index: 2;">
            <div style="text-align: center; max-width: 700px; margin: 0 auto 70px;">
                <span
                    style="display: inline-block; padding: 8px 22px; background: rgba(187,215,0,0.08); color: #9aaf00; border-radius: 50px; font-weight: 800; font-size: 12px; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 18px; border: 1px solid rgba(187,215,0,0.18);">Hiring
                    Roadmap</span>
                <h2
                    style="font-size: 44px; font-weight: 900; color: #0f172a; margin-bottom: 18px; letter-spacing: -0.5px; font-family: 'Outfit', sans-serif;">
                    Our Selection <span style="color: #bbd700;">Journey</span></h2>
                <p style="color: #64748b; font-size: 17px; line-height: 1.7;">A structured, transparent roadmap designed to
                    let you showcase your skills and get to know our team.</p>
            </div>

            <div class="hw-roadmap">
                <div class="hw-roadmap__line"></div>

                {{-- Step 1 --}}
                <div class="hw-roadmap__item hw-roadmap__item--left hw-roadmap__item--1" data-aos="fade-right">
                    <div class="hw-roadmap__content">
                        <div class="hw-roadmap__header">
                            <span class="hw-roadmap__step-num">01</span>
                            <div class="hw-roadmap__icon">
                                <i class="fas fa-file-signature"></i>
                            </div>
                        </div>
                        <div class="hw-roadmap__body">
                            <h4>Online Application</h4>
                            <p>Fill out the application form above and upload your resume/CV. We'll verify your
                                qualifications and fit within 48 hours.</p>
                        </div>
                    </div>
                    <div class="hw-roadmap__dot"></div>
                </div>

                {{-- Step 2 --}}
                <div class="hw-roadmap__item hw-roadmap__item--right hw-roadmap__item--2" data-aos="fade-left">
                    <div class="hw-roadmap__content">
                        <div class="hw-roadmap__header">
                            <span class="hw-roadmap__step-num">02</span>
                            <div class="hw-roadmap__icon">
                                <i class="fas fa-comments"></i>
                            </div>
                        </div>
                        <div class="hw-roadmap__body">
                            <h4>Discovery Chat</h4>
                            <p>A quick 15-30 minute introductory call with our talent team to discuss your background,
                                expectations, and answer your initial questions.</p>
                        </div>
                    </div>
                    <div class="hw-roadmap__dot"></div>
                </div>

                {{-- Step 3 --}}
                <div class="hw-roadmap__item hw-roadmap__item--left hw-roadmap__item--3" data-aos="fade-right">
                    <div class="hw-roadmap__content">
                        <div class="hw-roadmap__header">
                            <span class="hw-roadmap__step-num">03</span>
                            <div class="hw-roadmap__icon">
                                <i class="fas fa-flask"></i>
                            </div>
                        </div>
                        <div class="hw-roadmap__body">
                            <h4>Practical Assessment</h4>
                            <p>For technical and engineering roles, a short, practical task related to actual challenges we
                                face. No generic brain teasers.</p>
                        </div>
                    </div>
                    <div class="hw-roadmap__dot"></div>
                </div>

                {{-- Step 4 --}}
                <div class="hw-roadmap__item hw-roadmap__item--right hw-roadmap__item--4" data-aos="fade-left">
                    <div class="hw-roadmap__content">
                        <div class="hw-roadmap__header">
                            <span class="hw-roadmap__step-num">04</span>
                            <div class="hw-roadmap__icon">
                                <i class="fas fa-users-cog"></i>
                            </div>
                        </div>
                        <div class="hw-roadmap__body">
                            <h4>Panel Interview</h4>
                            <p>Meet our technical leaders and your potential teammates. We'll dive deeper into your
                                assessment and discuss our culture and alignment.</p>
                        </div>
                    </div>
                    <div class="hw-roadmap__dot"></div>
                </div>

                {{-- Step 5 --}}
                <div class="hw-roadmap__item hw-roadmap__item--left hw-roadmap__item--5" data-aos="fade-right">
                    <div class="hw-roadmap__content">
                        <div class="hw-roadmap__header">
                            <span class="hw-roadmap__step-num">05</span>
                            <div class="hw-roadmap__icon">
                                <i class="fas fa-handshake"></i>
                            </div>
                        </div>
                        <div class="hw-roadmap__body">
                            <h4>Offer & Onboarding</h4>
                            <p>We present a competitive offer. Once signed, our customized onboarding plan kicks off to get
                                you fully supported from day one.</p>
                        </div>
                    </div>
                    <div class="hw-roadmap__dot"></div>
                </div>

            </div>
        </div>
    </section>

    {{-- =====================================================
    PERKS & BENEFITS SECTION (LIGHT THEME)
    ====================================================== --}}
    <section class="pt_120 pb_120" style="background: #ffffff;">
        <div class="auto-container">
            <div style="text-align: center; max-width: 600px; margin: 0 auto 70px;">
                <span
                    style="display: inline-block; padding: 8px 22px; background: rgba(26,159,212,0.08); color: #1a9fd4; border-radius: 50px; font-weight: 800; font-size: 12px; text-transform: uppercase; letter-spacing: 2px; margin-bottom: 18px; border: 1px solid rgba(26,159,212,0.18);">Working
                    Here</span>
                <h2
                    style="font-size: 40px; font-weight: 900; color: #0f172a; margin-bottom: 18px; letter-spacing: -0.5px; font-family: 'Outfit', sans-serif;">
                    Perks of Joining Us</h2>
                <p style="color: #64748b; font-size: 17px; line-height: 1.7;">We provide a supportive, high-growth
                    environment so you can focus on doing your best work.</p>
            </div>

            <div class="row g-4">
                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="mgc-career-card" style="padding: 44px 32px; border-radius: 28px; height: 100%;">
                        <div class="icon-container"
                            style="width: 56px; height: 56px; background: rgba(187,215,0,0.10); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin-bottom: 28px; box-shadow: 0 10px 20px rgba(187,215,0,0.08);">
                            <i class="fas fa-heartbeat" style="font-size: 24px; color: #9aaf00;"></i>
                        </div>
                        <h3
                            style="font-size: 19px; font-weight: 900; color: #0f172a; margin-bottom: 12px; font-family: 'Outfit', sans-serif;">
                            Health & Wellness</h3>
                        <p style="font-size: 14px; color: #64748b; line-height: 1.7; margin: 0; font-weight: 500;">
                            Comprehensive medical insurance for you and your family.</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="mgc-career-card" style="padding: 44px 32px; border-radius: 28px; height: 100%;">
                        <div class="icon-container"
                            style="width: 56px; height: 56px; background: rgba(26,159,212,0.10); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin-bottom: 28px; box-shadow: 0 10px 20px rgba(26,159,212,0.08);">
                            <i class="fas fa-chart-line" style="font-size: 24px; color: #1a9fd4;"></i>
                        </div>
                        <h3
                            style="font-size: 19px; font-weight: 900; color: #0f172a; margin-bottom: 12px; font-family: 'Outfit', sans-serif;">
                            Career Growth</h3>
                        <p style="font-size: 14px; color: #64748b; line-height: 1.7; margin: 0; font-weight: 500;">Clear
                            progression paths and regular skill development budgets.</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="mgc-career-card" style="padding: 44px 32px; border-radius: 28px; height: 100%;">
                        <div class="icon-container"
                            style="width: 56px; height: 56px; background: rgba(5,150,105,0.10); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin-bottom: 28px; box-shadow: 0 10px 20px rgba(5,150,105,0.08);">
                            <i class="far fa-clock" style="font-size: 24px; color: #059669;"></i>
                        </div>
                        <h3
                            style="font-size: 19px; font-weight: 900; color: #0f172a; margin-bottom: 12px; font-family: 'Outfit', sans-serif;">
                            Flexible Hours</h3>
                        <p style="font-size: 14px; color: #64748b; line-height: 1.7; margin: 0; font-weight: 500;">Work-life
                            balance is our priority with flexible timings and remote work options.</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="mgc-career-card" style="padding: 44px 32px; border-radius: 28px; height: 100%;">
                        <div class="icon-container"
                            style="width: 56px; height: 56px; background: rgba(234,88,12,0.10); border-radius: 16px; display: flex; align-items: center; justify-content: center; margin-bottom: 28px; box-shadow: 0 10px 20px rgba(234,88,12,0.08);">
                            <i class="fas fa-mug-hot" style="font-size: 24px; color: #ea580c;"></i>
                        </div>
                        <h3
                            style="font-size: 19px; font-weight: 900; color: #0f172a; margin-bottom: 12px; font-family: 'Outfit', sans-serif;">
                            Great Culture</h3>
                        <p style="font-size: 14px; color: #64748b; line-height: 1.7; margin: 0; font-weight: 500;">
                            Collaborative environment with regular team outings, lunches, and events.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dropzone = document.getElementById('resume-dropzone');
            const fileInput = document.getElementById('resume-input');
            const defaultView = document.getElementById('dropzone-default');
            const previewView = document.getElementById('dropzone-preview');
            const fileNameEl = document.getElementById('file-name');
            const fileSizeEl = document.getElementById('file-size');
            const fileIconEl = document.getElementById('file-icon');
            const removeBtn = document.getElementById('remove-file-btn');

            if (!dropzone || !fileInput) return;

            // Handle Drag & Drop events
            ['dragenter', 'dragover'].forEach(eventName => {
                dropzone.addEventListener(eventName, function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropzone.classList.add('dragover');
                }, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropzone.addEventListener(eventName, function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    dropzone.classList.remove('dragover');
                }, false);
            });

            dropzone.addEventListener('drop', function (e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                if (files.length > 0) {
                    fileInput.files = files;
                    updateFilePreview(files[0]);
                }
            }, false);

            fileInput.addEventListener('change', function (e) {
                if (this.files.length > 0) {
                    updateFilePreview(this.files[0]);
                }
            });

            removeBtn.addEventListener('click', function (e) {
                e.preventDefault();
                e.stopPropagation();

                // Reset input value
                fileInput.value = '';
                // Restore input pointer events
                fileInput.style.pointerEvents = 'auto';

                // Toggle view states
                previewView.style.display = 'none';
                defaultView.style.display = 'block';
            });

            function updateFilePreview(file) {
                const fileName = file.name;
                const fileSize = formatBytes(file.size);

                fileNameEl.textContent = fileName;
                fileSizeEl.textContent = fileSize;

                // Determine icon based on file extension
                const extension = fileName.split('.').pop().toLowerCase();
                fileIconEl.className = 'fas';

                if (extension === 'pdf') {
                    fileIconEl.className += ' fa-file-pdf';
                    fileIconEl.style.color = '#ef4444'; // Red for PDF
                } else if (['doc', 'docx'].includes(extension)) {
                    fileIconEl.className += ' fa-file-word';
                    fileIconEl.style.color = '#2563eb'; // Blue for Word
                } else {
                    fileIconEl.className += ' fa-file-alt';
                    fileIconEl.style.color = '#64748b'; // Slate for others
                }

                // Toggle view states
                defaultView.style.display = 'none';
                previewView.style.display = 'block';

                // Prevent input from intercepting click events on the remove button
                fileInput.style.pointerEvents = 'none';
            }

            function formatBytes(bytes, decimals = 2) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const dm = decimals < 0 ? 0 : decimals;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
            }
        });
    </script>

@endsection
