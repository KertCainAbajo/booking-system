<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/shop.png') }}">
    <title>Contact Us - Dexter Auto Services</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-br from-black to-green-900 min-h-screen">
    <div class="container mx-auto px-4 py-6 sm:py-8 md:py-12">
        <!-- Header with Logo -->
        <div class="text-center mb-6 sm:mb-8">
            <div class="flex flex-col sm:flex-row items-center justify-center mb-4 gap-3 sm:gap-4">
                <img src="{{ asset('images/shop.png') }}" alt="Dexter Auto Services" class="h-12 w-12 sm:h-16 sm:w-16 md:h-20 md:w-20 brightness-0 invert">
                <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-black text-white tracking-wide">DEXTER AUTO SERVICES</h1>
            </div>
            <a href="{{ route('guest.home') }}" class="inline-flex items-center px-4 py-2 sm:px-6 sm:py-3 bg-white text-green-900 font-bold text-sm sm:text-base rounded-lg hover:bg-green-50 transition-all shadow-lg hover:shadow-xl transform hover:scale-105 mt-4">
                <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                Back to Home
            </a>
        </div>

        <!-- Page Title -->
        <div class="max-w-6xl mx-auto text-center mb-8 sm:mb-12">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-white mb-3 sm:mb-4">Contact Us</h2>
            <p class="text-base sm:text-lg md:text-xl text-green-100">Get in touch with us for any inquiries or assistance</p>
        </div>

        <!-- Contact Content -->
        <div class="max-w-6xl mx-auto grid md:grid-cols-2 gap-8 mb-12">
            <!-- Contact Information Card -->
            <div class="bg-white rounded-2xl shadow-2xl p-6 sm:p-8">
                <h3 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-6">Contact Information</h3>
                
                <!-- Admin Details -->
                <div class="space-y-4 mb-8">
                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 font-semibold">Contact Person</p>
                            <p class="text-lg font-bold text-gray-900">Dexter Abajo</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 font-semibold">Phone</p>
                            <p class="text-lg font-bold text-gray-900">0927-007153-8</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 font-semibold">Location</p>
                            <p class="text-lg font-bold text-gray-900">Don lis Village, Adelfa St</p>
                            <p class="text-gray-600">City of Mati</p>
                            <p class="text-gray-600">Davao Oriental, Philippines</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mr-4">
                            <svg class="w-6 h-6 text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 font-semibold">Business Hours</p>
                            <p class="text-lg font-bold text-gray-900">Monday - Saturday</p>
                            <p class="text-gray-600">8:00 AM - 6:00 PM</p>
                            <p class="text-gray-900 mt-2">Sunday: Closed</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Map Card -->
            <div class="bg-white rounded-2xl shadow-2xl p-6 sm:p-8">
                <h3 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-6">Our Location</h3>
                
                <!-- Google Maps Embed -->
                <div class="w-full h-96 rounded-lg overflow-hidden shadow-lg mb-4">
                    <iframe 
                        src="https://www.google.com/maps/embed/v1/place?key=AIzaSyBFw0Qbyq9zTFTd-tUY6dZWTgaQzuU17R8&q=Adelfa+Street,Mati+City,Davao+Oriental,Philippines&zoom=18&maptype=satellite"
                        width="100%" 
                        height="100%" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade"
                        class="rounded-lg">
                    </iframe>
                </div>
                
                <div class="bg-green-50 p-4 rounded-lg">
                    <p class="text-gray-700 text-sm">
                        <span class="font-bold">Exact Location:</span> Adelfa Street, Don lis Village, City of Mati, Davao Oriental. Easy to find and ample parking available for customers.
                    </p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="max-w-4xl mx-auto text-center mb-8">
            <div class="bg-white rounded-2xl shadow-2xl p-6 sm:p-8">
                <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3 sm:mb-4">Ready to Book Your Service?</h3>
                <p class="text-sm sm:text-base text-gray-600 mb-4 sm:mb-6">Schedule your auto service appointment in just a few clicks</p>
                <a href="{{ route('guest.booking') }}" 
                   class="inline-flex items-center px-6 py-3 sm:px-8 sm:py-4 bg-green-700 text-white text-base sm:text-lg font-bold rounded-lg hover:bg-green-800 transition-all shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Book Now
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-12 text-center text-green-200 text-sm">
            <p>&copy; {{ date('Y') }} Dexter Auto Services. Professional auto service at your convenience.</p>
        </div>
    </div>
</body>
</html>
