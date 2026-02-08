<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gradient-to-br from-black to-green-900">
        <div class="min-h-screen flex items-center justify-center p-2 sm:p-4 md:p-6 lg:p-8">
            <!-- Floating Card Container -->
            <div class="w-full max-w-6xl bg-white rounded-2xl sm:rounded-3xl shadow-2xl border-2 border-green-800 border-r-2 border-r-black overflow-hidden flex min-h-[500px] sm:min-h-[600px] md:min-h-[650px]">
                <!-- Left Side - Visual/Branding Panel -->
                <div class="hidden lg:flex lg:w-[45%] xl:w-1/2 relative overflow-hidden bg-black">
                <!-- Content -->
                <div class="relative z-10 flex flex-col justify-center items-center px-12 xl:px-16 text-white w-full">
                    <!-- Slideshow -->
                    <div class="w-full mb-6 relative slideshow-container">
                        <div class="slideshow-wrapper relative w-full h-64 overflow-hidden rounded-lg">
                            <img src="{{ asset('images/lc.png') }}" alt="Car 1" class="slideshow-image absolute inset-0 w-full h-full object-cover opacity-0 transition-opacity duration-1000">
                            <img src="{{ asset('images/fortuner.png') }}" alt="Car 2" class="slideshow-image absolute inset-0 w-full h-full object-cover opacity-0 transition-opacity duration-1000">
                            <img src="{{ asset('images/hilux.png') }}" alt="Car 3" class="slideshow-image absolute inset-0 w-full h-full object-cover opacity-0 transition-opacity duration-1000">
                        </div>
                    </div>
                    
                    <!-- Main Headline -->
                    <div class="space-y-2 text-center mb-8">
                        <h1 class="text-3xl xl:text-4xl font-bold leading-tight tracking-tight text-white">
                            Book Trusted <span class="text-green-700">Auto Service</span>
                        </h1>
                    </div>
                    
                    <!-- Features - Horizontal Layout -->
                    <div class="grid grid-cols-3 gap-6 w-full">
                        <div class="flex flex-col items-center text-center">
                            <div class="flex-shrink-0 w-12 h-12 bg-green-700 rounded-full flex items-center justify-center mb-3">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-white mb-1">Easy Scheduling</h3>
                                <p class="text-xs text-gray-400">Book your service in minutes</p>
                            </div>
                        </div>
                        
                        <div class="flex flex-col items-center text-center">
                            <div class="flex-shrink-0 w-12 h-12 bg-green-700 rounded-full flex items-center justify-center mb-3">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-white mb-1">Certified Technicians</h3>
                                <p class="text-xs text-gray-400">Expert care for your vehicle</p>
                            </div>
                        </div>
                        
                        <div class="flex flex-col items-center text-center">
                            <div class="flex-shrink-0 w-12 h-12 bg-green-700 rounded-full flex items-center justify-center mb-3">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-bold text-white mb-1">Transparent Pricing</h3>
                                <p class="text-xs text-gray-400">No hidden fees, upfront quotes</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

                <!-- Right Side - Login Form -->
                <div class="flex-1 flex items-center justify-center px-4 sm:px-6 py-6 sm:py-8 md:py-12 bg-white">
                    <div class="w-full max-w-md">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Slideshow functionality with persistent state
            let slideshowState = {
                currentIndex: 0,
                interval: null,
                images: null
            };
            
            function updateImageReferences() {
                slideshowState.images = document.querySelectorAll('.slideshow-image');
                if (slideshowState.images.length > 0) {
                    // Show current image without resetting index
                    slideshowState.images.forEach((img, index) => {
                        img.style.opacity = index === slideshowState.currentIndex ? '1' : '0';
                    });
                }
            }
            
            function showNextImage() {
                if (!slideshowState.images || slideshowState.images.length === 0) return;
                
                // Hide current image with smooth transition
                slideshowState.images[slideshowState.currentIndex].style.opacity = '0';
                
                // Move to next image (loop back to start)
                slideshowState.currentIndex = (slideshowState.currentIndex + 1) % slideshowState.images.length;
                
                // Show next image with smooth transition
                slideshowState.images[slideshowState.currentIndex].style.opacity = '1';
            }
            
            function startSlideshow() {
                if (!slideshowState.interval) {
                    slideshowState.interval = setInterval(showNextImage, 3000);
                }
            }
            
            // Initialize on page load
            document.addEventListener('DOMContentLoaded', function() {
                updateImageReferences();
                startSlideshow();
            });
            
            // Update references after navigation but keep slideshow running
            document.addEventListener('livewire:navigated', function() {
                updateImageReferences();
            });
        </script>
    </body>
</html>
