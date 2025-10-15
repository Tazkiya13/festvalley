<div class="app-body" style="padding-top: 0;">
    <!-- Modern Hero Section with Parallax Effect -->
    <div class="hero-image-container" style="
        width: 100vw; 
        height: 100vh; 
        position: relative; 
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, rgba(0,0,0,0.4), rgba(0,0,0,0.6));
    ">
        <img src="{{ asset('pulse-template/assets/images/b0.jpg') }}" 
             alt="Hero Background" 
             style="
                width: 100%; 
                height: 100%; 
                object-fit: cover; 
                position: absolute; 
                top: 0; 
                left: 0; 
                z-index: -1;
                filter: brightness(0.7) saturate(1.2);
             ">
        
        <!-- Hero Content -->
        <div class="hero-content-modern" style="
            position: relative;
            z-index: 2;
            color: white;
            text-align: center;
            max-width: 800px;
            padding: 0 2rem;
            animation: fadeInUp 1s ease-out;
        ">
            <div class="hero-badge-modern" style="
                display: inline-block;
                background: rgba(255,255,255,0.1);
                backdrop-filter: blur(20px);
                border: 1px solid rgba(255,255,255,0.2);
                padding: 0.5rem 1.5rem;
                border-radius: 50px;
                font-size: 0.9rem;
                font-weight: 500;
                margin-bottom: 2rem;
                color: rgba(255,255,255,0.9);
            ">
                🎵 Indonesia's #1 Artist Booking Platform
            </div>
            
            <h1 class="hero-title-modern" style="
                font-size: clamp(2.5rem, 5vw, 4rem);
                font-weight: 700;
                line-height: 1.2;
                margin-bottom: 1.5rem;
                text-shadow: 0 4px 20px rgba(0,0,0,0.3);
            ">
                Book World-Class Artists
                <br><span style="
                    background: linear-gradient(135deg, #667eea, #764ba2);
                    -webkit-background-clip: text;
                    -webkit-text-fill-color: transparent;
                    background-clip: text;
                ">for Your Events</span>
            </h1>
            
            <p style="
                font-size: clamp(1rem, 2vw, 1.3rem);
                margin-bottom: 3rem;
                opacity: 0.9;
                max-width: 600px;
                margin-left: auto;
                margin-right: auto;
                line-height: 1.6;
            ">
                Connect with 500+ verified professional artists across Indonesia. 
                From intimate acoustic sessions to grand concert performances.
            </p>
            
            <!-- CTA Buttons -->
            <div class="hero-actions-modern" style="
                display: flex;
                gap: 1rem;
                justify-content: center;
                flex-wrap: wrap;
                margin-bottom: 3rem;
            ">
                <button onclick="openRegisterModal()" style="
                    background: linear-gradient(135deg, #667eea, #764ba2);
                    color: white;
                    border: none;
                    padding: 1rem 2rem;
                    border-radius: 12px;
                    font-size: 1.1rem;
                    font-weight: 600;
                    cursor: pointer;
                    transition: all 0.3s ease;
                    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
                    min-width: 180px;
                " onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 12px 35px rgba(102, 126, 234, 0.4)'" 
                   onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 8px 25px rgba(102, 126, 234, 0.3)'">
                    <i class="fa fa-rocket" style="margin-right: 0.5rem;"></i>
                    Start Booking Now
                </button>
                
                <a href="{{ route('home') }}" style="
                    background: rgba(255,255,255,0.1);
                    backdrop-filter: blur(20px);
                    color: white;
                    border: 2px solid rgba(255,255,255,0.3);
                    padding: 1rem 2rem;
                    border-radius: 12px;
                    font-size: 1.1rem;
                    font-weight: 600;
                    text-decoration: none;
                    transition: all 0.3s ease;
                    min-width: 180px;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                " onmouseover="this.style.background='rgba(255,255,255,0.2)'; this.style.borderColor='rgba(255,255,255,0.5)'" 
                   onmouseout="this.style.background='rgba(255,255,255,0.1)'; this.style.borderColor='rgba(255,255,255,0.3)'">
                    <i class="fa fa-search" style="margin-right: 0.5rem;"></i>
                    Browse Artists
                </a>
            </div>
            
            
        </div>
        
        <!-- Scroll Indicator -->
        <div class="scroll-indicator" style="
            position: absolute;
            bottom: 2rem;
            left: 50%;
            transform: translateX(-50%);
            color: white;
            text-align: center;
            opacity: 0.7;
            animation: bounce 2s infinite;
        ">
            <div style="margin-bottom: 0.5rem; font-size: 0.9rem;">Scroll to explore</div>
            <i class="fa fa-chevron-down" style="font-size: 1.2rem;"></i>
        </div>
    </div>

    <!-- Enhanced Features Section -->
    <section class="features-section" style="background: white; padding: 6rem 0;">
        <div class="container">
            <!-- Section Header -->
            <div class="text-center" style="margin-bottom: 4rem;">
                <div style="
                    display: inline-block;
                    background: linear-gradient(135deg, #667eea, #764ba2);
                    color: white;
                    padding: 0.5rem 1.5rem;
                    border-radius: 50px;
                    font-size: 0.9rem;
                    font-weight: 600;
                    margin-bottom: 1.5rem;
                ">
                    ⭐ Why Choose Festvalley
                </div>
                <h2 style="
                    font-size: clamp(2rem, 4vw, 3rem);
                    font-weight: 700;
                    color: #2d3436;
                    margin-bottom: 1rem;
                    line-height: 1.2;
                ">
                    The Most Advanced Artist Booking Platform
                </h2>
                <p style="
                    font-size: 1.2rem;
                    color: #636e72;
                    max-width: 700px;
                    margin: 0 auto;
                    line-height: 1.6;
                ">
                    Powered by cutting-edge technology and trusted by industry professionals. 
                    Experience the future of event management today.
                </p>
            </div>

            <!-- Features Grid -->
            <div class="row" style="gap: 2rem 0;">
                <!-- Feature 1 -->
                <div class="col-lg-4 col-md-6">
                    <div style="
                        background: white;
                        border-radius: 20px;
                        padding: 2.5rem;
                        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
                        border: 1px solid rgba(102, 126, 234, 0.1);
                        text-align: center;
                        transition: all 0.3s ease;
                        height: 100%;
                    " onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 20px 60px rgba(0,0,0,0.12)'" 
                       onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 40px rgba(0,0,0,0.08)'">
                        
                        <div style="
                            background: linear-gradient(135deg, #667eea, #764ba2);
                            color: white;
                            width: 70px;
                            height: 70px;
                            border-radius: 20px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            margin: 0 auto 1.5rem;
                            font-size: 1.8rem;
                        ">
                            🎼
                        </div>
                        
                        <h3 style="
                            font-size: 1.4rem;
                            font-weight: 700;
                            color: #2d3436;
                            margin-bottom: 1rem;
                        ">Diverse Music Genres</h3>
                        
                        <p style="
                            color: #636e72;
                            line-height: 1.6;
                            margin-bottom: 1.5rem;
                        ">
                            From pop and jazz to traditional and electronic music. 
                            Our curated collection features verified artists across every genre imaginable.
                        </p>
                        
                        <div style="
                            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
                            border-radius: 12px;
                            padding: 1rem;
                            font-size: 0.9rem;
                            color: #495057;
                        ">
                            <strong>500+</strong> verified artists across <strong>20+</strong> music genres
                        </div>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="col-lg-4 col-md-6">
                    <div style="
                        background: white;
                        border-radius: 20px;
                        padding: 2.5rem;
                        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
                        border: 1px solid rgba(102, 126, 234, 0.1);
                        text-align: center;
                        transition: all 0.3s ease;
                        height: 100%;
                    " onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 20px 60px rgba(0,0,0,0.12)'" 
                       onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 40px rgba(0,0,0,0.08)'">
                        
                        <div style="
                            background: linear-gradient(135deg, #00b894, #00cec9);
                            color: white;
                            width: 70px;
                            height: 70px;
                            border-radius: 20px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            margin: 0 auto 1.5rem;
                            font-size: 1.8rem;
                        ">
                            🛡️
                        </div>
                        
                        <h3 style="
                            font-size: 1.4rem;
                            font-weight: 700;
                            color: #2d3436;
                            margin-bottom: 1rem;
                        ">Enterprise Security</h3>
                        
                        <p style="
                            color: #636e72;
                            line-height: 1.6;
                            margin-bottom: 1.5rem;
                        ">
                            Bank-grade security with SSL encryption, escrow payment protection, 
                            and comprehensive identity verification for all transactions.
                        </p>
                        
                        <div style="
                            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
                            border-radius: 12px;
                            padding: 1rem;
                            font-size: 0.9rem;
                            color: #495057;
                        ">
                            <strong>100%</strong> secure payments with <strong>money-back</strong> guarantee
                        </div>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div class="col-lg-4 col-md-6">
                    <div style="
                        background: white;
                        border-radius: 20px;
                        padding: 2.5rem;
                        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
                        border: 1px solid rgba(102, 126, 234, 0.1);
                        text-align: center;
                        transition: all 0.3s ease;
                        height: 100%;
                    " onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 20px 60px rgba(0,0,0,0.12)'" 
                       onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 40px rgba(0,0,0,0.08)'">
                        
                        <div style="
                            background: linear-gradient(135deg, #fd79a8, #fdcb6e);
                            color: white;
                            width: 70px;
                            height: 70px;
                            border-radius: 20px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            margin: 0 auto 1.5rem;
                            font-size: 1.8rem;
                        ">
                            💬
                        </div>
                        
                        <h3 style="
                            font-size: 1.4rem;
                            font-weight: 700;
                            color: #2d3436;
                            margin-bottom: 1rem;
                        ">24/7 Expert Support</h3>
                        
                        <p style="
                            color: #636e72;
                            line-height: 1.6;
                            margin-bottom: 1.5rem;
                        ">
                            Dedicated support team available around the clock via live chat, 
                            WhatsApp, and phone. Get instant solutions to any questions.
                        </p>
                        
                        <div style="
                            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
                            border-radius: 12px;
                            padding: 1rem;
                            font-size: 0.9rem;
                            color: #495057;
                        ">
                            Average response time: <strong>< 5 minutes</strong>
                        </div>
                    </div>
                </div>

                <!-- Feature 4 -->
                <div class="col-lg-4 col-md-6">
                    <div style="
                        background: white;
                        border-radius: 20px;
                        padding: 2.5rem;
                        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
                        border: 1px solid rgba(102, 126, 234, 0.1);
                        text-align: center;
                        transition: all 0.3s ease;
                        height: 100%;
                    " onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 20px 60px rgba(0,0,0,0.12)'" 
                       onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 40px rgba(0,0,0,0.08)'">
                        
                        <div style="
                            background: linear-gradient(135deg, #a29bfe, #6c5ce7);
                            color: white;
                            width: 70px;
                            height: 70px;
                            border-radius: 20px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            margin: 0 auto 1.5rem;
                            font-size: 1.8rem;
                        ">
                            ⭐
                        </div>
                        
                        <h3 style="
                            font-size: 1.4rem;
                            font-weight: 700;
                            color: #2d3436;
                            margin-bottom: 1rem;
                        ">Premium Artist Quality</h3>
                        
                        <p style="
                            color: #636e72;
                            line-height: 1.6;
                            margin-bottom: 1.5rem;
                        ">
                            Every artist undergoes rigorous auditions and professional verification. 
                            Complete portfolios, certifications, and proven track records.
                        </p>
                        
                        <div style="
                            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
                            border-radius: 12px;
                            padding: 1rem;
                            font-size: 0.9rem;
                            color: #495057;
                        ">
                            <strong>4.9/5</strong> average rating from <strong>10,000+</strong> reviews
                        </div>
                    </div>
                </div>

                <!-- Feature 5 -->
                <div class="col-lg-4 col-md-6">
                    <div style="
                        background: white;
                        border-radius: 20px;
                        padding: 2.5rem;
                        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
                        border: 1px solid rgba(102, 126, 234, 0.1);
                        text-align: center;
                        transition: all 0.3s ease;
                        height: 100%;
                    " onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 20px 60px rgba(0,0,0,0.12)'" 
                       onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 40px rgba(0,0,0,0.08)'">
                        
                        <div style="
                            background: linear-gradient(135deg, #e17055, #d63031);
                            color: white;
                            width: 70px;
                            height: 70px;
                            border-radius: 20px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            margin: 0 auto 1.5rem;
                            font-size: 1.8rem;
                        ">
                            🚀
                        </div>
                        
                        <h3 style="
                            font-size: 1.4rem;
                            font-weight: 700;
                            color: #2d3436;
                            margin-bottom: 1rem;
                        ">AI-Powered Matching</h3>
                        
                        <p style="
                            color: #636e72;
                            line-height: 1.6;
                            margin-bottom: 1.5rem;
                        ">
                            Advanced algorithm matches your event requirements with perfect artists. 
                            Smart scheduling with automated confirmation systems.
                        </p>
                        
                        <div style="
                            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
                            border-radius: 12px;
                            padding: 1rem;
                            font-size: 0.9rem;
                            color: #495057;
                        ">
                            <strong>95%</strong> match accuracy with instant booking
                        </div>
                    </div>
                </div>

                <!-- Feature 6 -->
                <div class="col-lg-4 col-md-6">
                    <div style="
                        background: white;
                        border-radius: 20px;
                        padding: 2.5rem;
                        box-shadow: 0 10px 40px rgba(0,0,0,0.08);
                        border: 1px solid rgba(102, 126, 234, 0.1);
                        text-align: center;
                        transition: all 0.3s ease;
                        height: 100%;
                    " onmouseover="this.style.transform='translateY(-10px)'; this.style.boxShadow='0 20px 60px rgba(0,0,0,0.12)'" 
                       onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 10px 40px rgba(0,0,0,0.08)'">
                        
                        <div style="
                            background: linear-gradient(135deg, #00b894, #55a3ff);
                            color: white;
                            width: 70px;
                            height: 70px;
                            border-radius: 20px;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            margin: 0 auto 1.5rem;
                            font-size: 1.8rem;
                        ">
                            🏆
                        </div>
                        
                        <h3 style="
                            font-size: 1.4rem;
                            font-weight: 700;
                            color: #2d3436;
                            margin-bottom: 1rem;
                        ">100% Satisfaction Guarantee</h3>
                        
                        <p style="
                            color: #636e72;
                            line-height: 1.6;
                            margin-bottom: 1.5rem;
                        ">
                            Not satisfied? Get your money back. We maintain a 98% customer satisfaction rate 
                            with thousands of successful events.
                        </p>
                        
                        <div style="
                            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
                            border-radius: 12px;
                            padding: 1rem;
                            font-size: 0.9rem;
                            color: #495057;
                        ">
                            <strong>98%</strong> satisfaction rate with full refund policy
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Simple Process Section -->
    <section id="booking" class="simple-process-section">
        <div class="container">
            <!-- Section Header -->
            <div class="section-header">
                <div class="section-badge">
                    <span class="badge-icon">🚀</span>
                    <span>Cara Booking</span>
                </div>
                <div class="section-title-how-to-booking">
                    <h2 class="section-title">Booking Artis dalam <span class="highlight">3 Langkah Mudah</span></h2>
                    <p class="section-description">
                        Proses booking yang simpel dan cepat. Tidak perlu ribet, dalam hitungan menit event Anda sudah
                        ready!
                    </p>
                </div>
            </div>

            <!-- Process Steps -->
            <div class="process-grid">
                <div class="process-card" data-step="1">
                    <div class="card-header">
                        <div class="step-number">
                            <span>01</span>
                        </div>
                        <div class="step-icon">
                            <i class="fa fa-search"></i>
                        </div>
                    </div>
                    <div class="card-content">
                        <h3 class="step-title">Cari & Pilih Artis</h3>
                        <p class="step-description">
                            Browse katalog artis berdasarkan genre, budget, dan lokasi.
                            Filter sesuai kebutuhan event Anda.
                        </p>
                        <div class="step-features">
                            <div class="feature-tag">🎵 500+ Artis</div>
                            <div class="feature-tag">🎯 Smart Filter</div>
                            <div class="feature-tag">⭐ Rating & Review</div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a href="{{ route('home') }}" class="step-action">
                            <span>Start Discovering</span>
                            <i class="fa fa-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <div class="process-card" data-step="2">
                    <div class="card-header">
                        <div class="step-number">
                            <span>02</span>
                        </div>
                        <div class="step-icon">
                            <i class="fa fa-calendar-check"></i>
                        </div>
                    </div>
                    <div class="card-content">
                        <h3 class="step-title">Pilih Tanggal & Booking</h3>
                        <p class="step-description">
                            Cek jadwal real-time artis dan lakukan booking instant.
                            Konfirmasi otomatis dalam hitungan detik.
                        </p>
                        <div class="step-features">
                            <div class="feature-tag">📅 Real-time Schedule</div>
                            <div class="feature-tag">⚡ Instant Booking</div>
                            <div class="feature-tag">🔒 Secure Payment</div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="step-action disabled">
                            <span>Booking Langsung</span>
                            <i class="fa fa-check-circle"></i>
                        </div>
                    </div>
                </div>

                <div class="process-card" data-step="3">
                    <div class="card-header">
                        <div class="step-number">
                            <span>03</span>
                        </div>
                        <div class="step-icon">
                            <i class="fa fa-party-horn"></i>
                        </div>
                    </div>
                    <div class="card-content">
                        <h3 class="step-title">Event Berjalan Sukses</h3>
                        <p class="step-description">
                            Artis datang tepat waktu, perform sesuai ekspektasi.
                            Tim support kami memastikan semuanya perfect!
                        </p>
                        <div class="step-features">
                            <div class="feature-tag">🎤 Professional Performance</div>
                            <div class="feature-tag">⏰ On-time Arrival</div>
                            <div class="feature-tag">🛟 24/7 Support</div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="step-action success">
                            <span>Event Sukses!</span>
                            <i class="fa fa-star"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CTA Section -->
            {{-- <div class="process-cta">
                <h3>Siap Memulai Event Impian Anda?</h3>
                <p>Bergabunglah dengan ribuan organizer yang sudah merasakan kemudahan booking artis bersama kami</p>
                <div class="cta-buttons">
                    <a href="{{ route('home') }}" class="btn-primary">
                        <i class="fa fa-compass"></i>
                        <span>Discover Artists</span>
                        <small>AI-powered recommendations</small>
                    </a>
                    <a href="{{ route('home') }}" class="btn-secondary">
                        <i class="fa fa-search"></i>
                        <span>Search Artists</span>
                        <small>Find specific artists</small>
                    </a>
                </div>
            </div> --}}
        </div>
    </section>

    <!-- Professional Authentication Section -->
    <section id="auth-section" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); padding: 6rem 0;">
        <div class="container">
            <!-- Section Header -->
            <div class="text-center" style="margin-bottom: 4rem;">
                <div style="
                    display: inline-block;
                    background: linear-gradient(135deg, #667eea, #764ba2);
                    color: white;
                    padding: 0.5rem 1.5rem;
                    border-radius: 50px;
                    font-size: 0.9rem;
                    font-weight: 600;
                    margin-bottom: 1.5rem;
                ">
                    🚀 Get Started Today
                </div>
                <h2 style="
                    font-size: clamp(2rem, 4vw, 3rem);
                    font-weight: 700;
                    color: #2d3436;
                    margin-bottom: 1rem;
                    line-height: 1.2;
                ">
                    Join the Revolution in Event Management
                </h2>
                <p style="
                    font-size: 1.2rem;
                    color: #636e72;
                    max-width: 600px;
                    margin: 0 auto;
                    line-height: 1.6;
                ">
                    Whether you're organizing events or performing at them, we've got the perfect solution for you.
                </p>
            </div>

            <div class="row align-items-center" style="gap: 3rem 0; display: flex !important; justify-content: center;">
                <!-- Sign Up Section -->
                <div class="col-lg-6 col-md-8 col-sm-10">
                    <div style="
                        background: white;
                        border-radius: 20px;
                        padding: 3rem;
                        box-shadow: 0 20px 60px rgba(0,0,0,0.1);
                        border: 1px solid rgba(102, 126, 234, 0.1);
                        transition: all 0.3s ease;
                    " onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 30px 80px rgba(0,0,0,0.15)'" 
                       onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 20px 60px rgba(0,0,0,0.1)'">
                        
                        <div style="text-align: center; margin-bottom: 2rem;">
                            <div style="
                                background: linear-gradient(135deg, #667eea, #764ba2);
                                color: white;
                                width: 60px;
                                height: 60px;
                                border-radius: 50%;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                margin: 0 auto 1rem;
                                font-size: 1.5rem;
                            ">
                                <i class="fa fa-user-plus"></i>
                            </div>
                            <h3 style="
                                font-size: 1.8rem;
                                font-weight: 700;
                                color: #2d3436;
                                margin-bottom: 0.5rem;
                            ">Start Your Journey</h3>
                            <p style="color: #636e72; margin-bottom: 0;">For Event Organizers & Artists</p>
                        </div>

                        <div style="margin-bottom: 2rem;">
                            <div style="
                                background: linear-gradient(135deg, #00b894, #00cec9);
                                color: white;
                                padding: 1rem 1.5rem;
                                border-radius: 12px;
                                margin-bottom: 1.5rem;
                                text-align: center;
                            ">
                                <div style="font-weight: 700; font-size: 1.1rem;">🎁 Limited Time Offer</div>
                                <div style="font-size: 0.9rem; opacity: 0.9;">FREE consultation + 20% off your first booking</div>
                            </div>

                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                                <div style="text-align: center; padding: 1rem; background: #f8f9fa; border-radius: 12px;">
                                    <div style="font-size: 1.5rem; font-weight: 700; color: #667eea;">10,000+</div>
                                    <div style="font-size: 0.9rem; color: #636e72;">Happy Organizers</div>
                                </div>
                                <div style="text-align: center; padding: 1rem; background: #f8f9fa; border-radius: 12px;">
                                    <div style="font-size: 1.5rem; font-weight: 700; color: #667eea;">500+</div>
                                    <div style="font-size: 0.9rem; color: #636e72;">Verified Artists</div>
                                </div>
                            </div>
                        </div>

                        <!-- Benefits List -->
                        <div style="margin-bottom: 2rem;">
                            <div style="display: flex; align-items: center; margin-bottom: 0.75rem;">
                                <div style="
                                    background: #00b894;
                                    color: white;
                                    width: 20px;
                                    height: 20px;
                                    border-radius: 50%;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    margin-right: 0.75rem;
                                    font-size: 0.7rem;
                                ">✓</div>
                                <span style="color: #2d3436; font-weight: 500;">Instant artist matching & booking</span>
                            </div>
                            <div style="display: flex; align-items: center; margin-bottom: 0.75rem;">
                                <div style="
                                    background: #00b894;
                                    color: white;
                                    width: 20px;
                                    height: 20px;
                                    border-radius: 50%;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    margin-right: 0.75rem;
                                    font-size: 0.7rem;
                                ">✓</div>
                                <span style="color: #2d3436; font-weight: 500;">Secure payment with money-back guarantee</span>
                            </div>
                            <div style="display: flex; align-items: center; margin-bottom: 0.75rem;">
                                <div style="
                                    background: #00b894;
                                    color: white;
                                    width: 20px;
                                    height: 20px;
                                    border-radius: 50%;
                                    display: flex;
                                    align-items: center;
                                    justify-content: center;
                                    margin-right: 0.75rem;
                                    font-size: 0.7rem;
                                ">✓</div>
                                <span style="color: #2d3436; font-weight: 500;">24/7 dedicated support team</span>
                            </div>
                        </div>

                        <button onclick="openRegisterModal()" style="
                            width: 100%;
                            background: linear-gradient(135deg, #667eea, #764ba2);
                            color: white;
                            border: none;
                            padding: 1rem;
                            border-radius: 12px;
                            font-size: 1.1rem;
                            font-weight: 600;
                            cursor: pointer;
                            transition: all 0.3s ease;
                            margin-bottom: 1rem;
                        " onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 10px 25px rgba(102, 126, 234, 0.4)'" 
                           onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                            <i class="fa fa-rocket" style="margin-right: 0.5rem;"></i>
                            Create Free Account
                        </button>

                        <div style="text-align: center; color: #636e72; font-size: 0.9rem;">
                            Already have an account? 
                            <a href="#" onclick="openLoginModal()" style="
                                color: #667eea; 
                                text-decoration: none; 
                                font-weight: 600;
                            ">Sign in here</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
