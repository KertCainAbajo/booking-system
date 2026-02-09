<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/shop.png') }}">
    <title>Dexter Auto Services - Book Your Service</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes bounceSlow {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-15px);
            }
        }

        @keyframes pulseSlow {
            0%, 100% {
                opacity: 1;
                transform: scale(1);
            }
            50% {
                opacity: 0.7;
                transform: scale(1.05);
            }
        }

        .animate-fade-in {
            animation: fadeIn 1s ease-out;
        }

        .animate-bounce-slow {
            animation: bounceSlow 2.5s ease-in-out infinite;
        }

        .animate-pulse-slow {
            animation: pulseSlow 3s ease-in-out infinite;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-black to-green-900 min-h-screen">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-12">
        <!-- Header Title -->
        <div class="text-center mb-6 sm:mb-8">
            <div class="flex flex-col sm:flex-row items-center justify-center mb-4 gap-3 sm:gap-4">
                <img src="{{ asset('images/shop.png') }}" alt="Dexter Auto Services" class="h-12 w-12 sm:h-16 sm:w-16 md:h-20 md:w-20 brightness-0 invert">
                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-black text-white tracking-wide text-center">DEXTER AUTO SERVICES</h1>
            </div>
        </div>

        <!-- Hero Section -->
        <div class="max-w-4xl mx-auto text-center mb-8 sm:mb-12">
            <h2 class="text-xl sm:text-2xl font-semibold text-white mb-3 sm:mb-4">
                Book Your Auto Service in Minutes
            </h2>
            <p class="text-base sm:text-xl text-green-100 mb-6 sm:mb-10">
                No account required. Quick and hassle-free booking.
            </p>
            
            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-3 sm:gap-4">
                <a href="{{ route('guest.booking') }}" 
                   class="w-full sm:w-auto inline-flex items-center justify-center px-8 sm:px-10 py-4 sm:py-5 bg-white text-green-900 text-base sm:text-lg font-bold rounded-xl hover:bg-green-50 transform hover:scale-105 transition-all shadow-2xl">
                    <svg class="w-5 h-5 sm:w-7 sm:h-7 mr-2 sm:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Book Now
                </a>
                <a href="#track-booking-form" 
                   class="w-full sm:w-auto inline-flex items-center justify-center px-8 sm:px-10 py-4 sm:py-5 bg-green-700 text-white text-base sm:text-lg font-bold rounded-xl hover:bg-green-600 transform hover:scale-105 transition-all shadow-2xl">
                    <svg class="w-5 h-5 sm:w-7 sm:h-7 mr-2 sm:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    Track Your Booking
                </a>
                <a href="{{ route('guest.contact') }}" 
                   class="w-full sm:w-auto inline-flex items-center justify-center px-8 sm:px-10 py-4 sm:py-5 bg-green-900 text-white text-base sm:text-lg font-bold rounded-xl hover:bg-green-800 transform hover:scale-105 transition-all shadow-2xl">
                    <svg class="w-5 h-5 sm:w-7 sm:h-7 mr-2 sm:mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Contact Us
                </a>
            </div>
        </div>

        <!-- Feature Cards -->
        <div class="max-w-6xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6 mb-12 sm:mb-16">
            <!-- Fast & Easy Card -->
            <div class="bg-white rounded-xl shadow-xl p-6 sm:p-8 text-center transform hover:scale-105 transition-all duration-300">
                <div class="w-16 h-16 sm:w-20 sm:h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 sm:mb-5">
                    <svg class="w-8 h-8 sm:w-10 sm:h-10 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">Fast & Easy</h3>
                <p class="text-gray-600 leading-relaxed">
                    Book in just a few clicks without creating an account. Simple and straightforward process designed for your convenience.
                </p>
            </div>

            <!-- Instant Confirmation Card -->
            <div class="bg-white rounded-xl shadow-xl p-8 text-center transform hover:scale-105 transition-all duration-300">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-5">
                    <svg class="w-10 h-10 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">Instant Confirmation</h3>
                <p class="text-gray-600 leading-relaxed">
                    Receive immediate booking confirmation with a unique tracking reference. Know your booking is secured right away.
                </p>
            </div>

            <!-- Track Booking Card -->
            <div class="bg-white rounded-xl shadow-xl p-8 text-center transform hover:scale-105 transition-all duration-300">
                <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-5">
                    <svg class="w-10 h-10 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-3">Track Your Booking</h3>
                <p class="text-gray-600 leading-relaxed">
                    Access your booking status anytime using your unique reference number. Stay informed every step of the way.
                </p>
            </div>
        </div>

        <!-- Professional Arrow Section -->
        <div class="hidden max-w-5xl mx-auto mb-8">
            <div class="relative">
                <!-- Animated Decorative Lines -->
                <div class="absolute left-0 right-0 top-1/2 transform -translate-y-1/2 overflow-hidden">
                    <div class="h-px bg-gradient-to-r from-transparent via-green-500/40 to-transparent animate-pulse"></div>
                </div>
                
                <!-- Center Content with Animations - Unified Clickable Area -->
                <a href="#track-booking-form" class="relative flex flex-col items-center justify-center animate-fade-in animate-bounce-slow group cursor-pointer focus:outline-none">
                    <!-- Modern Glass-morphism Style Button -->
                    <div class="relative">
                        <!-- Glow Effect -->
                        <div class="absolute -inset-1 bg-gradient-to-r from-green-700 to-green-800 rounded-2xl blur opacity-30 group-hover:opacity-50 transition-opacity duration-300"></div>
                        
                        <!-- Main Button -->
                        <div class="relative bg-gradient-to-r from-green-700 via-green-800 to-green-900 px-6 py-3 rounded-2xl shadow-lg transform transition-all duration-300 group-hover:scale-105 group-active:scale-105 group-hover:shadow-green-700/50 border border-green-600/40 group-hover:border-green-500 group-active:border-green-600/40">
                            <div class="flex items-center gap-3">
                                <!-- Animated Icon -->
                                <div class="relative flex items-center justify-center">
                                    <div class="absolute inset-0 bg-white/20 rounded-full blur animate-pulse-slow"></div>
                                    <div class="relative w-7 h-7 rounded-full bg-white/10 backdrop-blur-sm flex items-center justify-center border border-white/30 group-hover:bg-white/20 group-active:bg-white/10 transition-all">
                                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                    </div>
                                </div>
                                
                                <!-- Text -->
                                <span class="text-base font-bold text-white tracking-wide drop-shadow-lg">Track Your Booking</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Animated Arrow - Part of Same Link -->
                    <div class="mt-2 text-green-400 transform transition-all duration-300 group-hover:translate-y-2 group-active:translate-y-2 group-hover:text-green-300 group-active:text-green-400">
                        <svg class="w-5 h-5 drop-shadow-lg filter" fill="currentColor" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                        </svg>
                    </div>
                </a>
            </div>
        </div>

        <!-- Car Slideshow Section -->
        <div class="max-w-5xl mx-auto mb-16" x-data="carSlideshow()">
            <div class="relative bg-gradient-to-br from-black to-green-900 rounded-2xl shadow-2xl overflow-hidden p-4">
                <!-- Slideshow Container -->
                <div class="relative h-96 rounded-xl overflow-hidden bg-black/30">
                    <!-- Images -->
                    <template x-for="(image, index) in images" :key="index">
                        <div x-show="currentSlide === index" 
                             x-transition:enter="transition ease-in-out duration-700"
                             x-transition:enter-start="opacity-0 transform translate-x-full"
                             x-transition:enter-end="opacity-100 transform translate-x-0"
                             x-transition:leave="transition ease-in-out duration-700"
                             x-transition:leave-start="opacity-100 transform translate-x-0"
                             x-transition:leave-end="opacity-0 transform -translate-x-full"
                             class="absolute inset-0 flex items-center justify-center">
                            <img :src="'/images/' + image" 
                                 :alt="'Car ' + (index + 1)"
                                 class="w-full h-full object-cover">
                        </div>
                    </template>

                    <!-- Left Arrow -->
                    <button @click="prevSlide()" 
                            class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white text-green-900 rounded-full p-3 shadow-lg transition-all hover:scale-110 z-10">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </button>

                    <!-- Right Arrow -->
                    <button @click="nextSlide(); restartAutoplay()" 
                            class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/90 hover:bg-white text-green-900 rounded-full p-3 shadow-lg transition-all hover:scale-110 z-10">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                </div>

                <!-- Dot Indicators -->
                <div class="flex justify-center items-center gap-2 mt-6">
                    <template x-for="dotIndex in visibleDots" :key="dotIndex">
                        <button @click="goToSlide(dotIndex)" 
                                :class="currentSlide === dotIndex ? 'bg-white w-3 h-3' : 'bg-white/40 w-2.5 h-2.5 hover:bg-white/60'"
                                class="rounded-full transition-all duration-300"></button>
                    </template>
                </div>
            </div>
        </div>

        <!-- Track Existing Booking Section -->
        <div id="track-booking-form" class="max-w-2xl mx-auto bg-white rounded-2xl shadow-2xl p-10 scroll-mt-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-3 text-center">Track Your Booking</h2>
            <p class="text-gray-600 mb-8 text-center">
                Enter your reference number below to check your booking status
            </p>
            <form action="{{ route('guest.booking.track') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
                <input type="text" 
                       name="reference" 
                       placeholder="Enter booking reference (e.g., BK12345678)" 
                       class="flex-1 px-5 py-4 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-600 focus:border-green-600 outline-none transition text-lg"
                       required>
                <button type="submit" 
                        class="px-8 py-4 bg-green-700 text-white text-lg font-bold rounded-lg hover:bg-green-800 transition-all shadow-lg hover:shadow-xl">
                    Track
                </button>
            </form>
        </div>

        <!-- Footer Space -->
        <div class="mt-16 text-center text-green-200 text-sm">
            <p>&copy; {{ date('Y') }} Dexter Auto Services. Professional auto service at your convenience.</p>
        </div>
    </div>

    <script>
        function carSlideshow() {
            return {
                currentSlide: 0,
                images: ['1.jpg', '2.jpg', '3.jpg', '4.jpg', '5.jpg', '6.jpg', '7.jpg', '8.jpg', '9.jpg', '10.jpg', '11.jpg', '12.jpg', '13.jpg'],
                autoplayInterval: null,
                
                init() {
                    // Start autoplay when component initializes
                    this.startAutoplay();
                },
                
                startAutoplay() {
                    // Auto-advance every 4 seconds (slow loop)
                    this.autoplayInterval = setInterval(() => {
                        this.nextSlide();
                    }, 4000);
                },
                
                stopAutoplay() {
                    if (this.autoplayInterval) {
                        clearInterval(this.autoplayInterval);
                    }
                },
                
                restartAutoplay() {
                    this.stopAutoplay();
                    this.startAutoplay();
                },
                
                get visibleDots() {
                    // Always show only 3 dots with sliding window effect
                    const totalImages = this.images.length;
                    let dots = [];
                    
                    if (totalImages <= 3) {
                        // If 3 or fewer images, show all dots
                        for (let i = 0; i < totalImages; i++) {
                            dots.push(i);
                        }
                    } else {
                        // Show 3 dots in a sliding window that moves with the slide
                        const windowStart = Math.floor(this.currentSlide / 3) * 3;
                        const windowEnd = Math.min(windowStart + 3, totalImages);
                        
                        for (let i = windowStart; i < windowEnd; i++) {
                            dots.push(i);
                        }
                        
                        // If we're at the end and have less than 3 dots, show last 3
                        if (dots.length < 3) {
                            dots = [totalImages - 3, totalImages - 2, totalImages - 1];
                        }
                    }
                    
                    return dots;
                },
                
                nextSlide() {
                    this.currentSlide = (this.currentSlide + 1) % this.images.length;
                },
                
                prevSlide() {
                    this.currentSlide = (this.currentSlide - 1 + this.images.length) % this.images.length;
                    this.restartAutoplay(); // Reset timer when manually navigating
                },
                
                goToSlide(index) {
                    this.currentSlide = index;
                    this.restartAutoplay(); // Reset timer when manually navigating
                }
            }
        }
    </script>
</body>
</html>
