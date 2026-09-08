<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/shop.png') }}">
    <title>Terms and Conditions - Dexter Auto Services</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .terms-page,
        .terms-page * {
            color: #ffffff !important;
        }
    </style>
</head>
<body class="terms-page min-h-screen bg-gradient-to-br from-black to-green-900">
    <main class="container mx-auto max-w-5xl px-4 py-8 sm:px-6 sm:py-12">
        <div class="mb-8 flex flex-col items-center justify-between gap-4 sm:flex-row">
            <a href="{{ route('guest.home') }}" class="flex items-center gap-3 text-xl font-bold">
                <img src="{{ asset('images/shop.png') }}" alt="Dexter Auto Services" class="h-12 w-12 brightness-0 invert">
                <span>Dexter Auto Services</span>
            </a>
            <a href="{{ route('guest.home') }}" class="rounded-lg border border-white/30 px-4 py-2 text-sm font-semibold hover:bg-white/10">
                Back to Home
            </a>
        </div>

        <article class="rounded-2xl border border-green-500/30 bg-black/70 p-6 shadow-2xl sm:p-10">
            <header class="mb-8 border-b border-white/20 pb-6">
                <p class="mb-2 text-sm font-semibold uppercase tracking-widest">Legal Information</p>
                <h1 class="text-3xl font-bold sm:text-4xl">Terms and Conditions</h1>
                <p class="mt-3 text-sm">Effective date: {{ now()->format('F j, Y') }}</p>
            </header>

            <div class="space-y-8 leading-7">
                <section>
                    <h2 class="mb-2 text-xl font-bold">1. Acceptance of these terms</h2>
                    <p>
                        These Terms and Conditions govern your use of the Dexter Auto Services website and booking
                        system. By accessing the system or submitting a booking, you agree to follow these terms. If you
                        do not agree, please do not use the booking system.
                    </p>
                </section>

                <section>
                    <h2 class="mb-2 text-xl font-bold">2. Booking requests</h2>
                    <p>
                        The system allows you to submit an appointment request and receive a booking reference. You can
                        use that reference to view the booking status. The status shown in the system may change as the
                        booking is managed by authorized staff.
                    </p>
                </section>

                <section>
                    <h2 class="mb-2 text-xl font-bold">3. Customer responsibilities</h2>
                    <ul class="list-disc space-y-2 pl-6">
                        <li>Provide accurate and complete contact, vehicle, and booking information.</li>
                        <li>Review your booking reference, service selection, date, and time after submitting a request.</li>
                        <li>Bring or make the vehicle available at the confirmed appointment time.</li>
                        <li>Tell us promptly if your contact information, vehicle details, or appointment needs change.</li>
                        <li>Do not submit bookings using another person’s information without permission.</li>
                    </ul>
                </section>

                <section>
                    <h2 class="mb-2 text-xl font-bold">4. Services, availability, and estimates</h2>
                    <p>
                        The system displays available services, service descriptions, base prices, estimated durations,
                        and estimated totals. These values are information displayed by the application and may be
                        updated by authorized staff. The system does not determine the final outcome or cost of vehicle
                        work.
                    </p>
                </section>

                <section>
                    <h2 class="mb-2 text-xl font-bold">5. Changes, cancellations, and missed appointments</h2>
                    <p>
                        Where available, customers may cancel eligible bookings through the booking tracker. A booking
                        may be changed or cancelled by authorized staff through the system. For a request that cannot be
                        handled in the system, contact Dexter Auto Services directly.
                    </p>
                </section>

                <section>
                    <h2 class="mb-2 text-xl font-bold">6. Payments and charges</h2>
                    <p>
                        The current booking form does not collect online payment. Service prices and any payment
                        arrangements are handled separately by Dexter Auto Services. See the
                        <a href="{{ route('refund.policy') }}" class="underline">Refund Policy</a> for information
                        about payment and refund requests.
                    </p>
                </section>

                <section>
                    <h2 class="mb-2 text-xl font-bold">7. Accounts and access</h2>
                    <p>
                        Registered users are responsible for keeping their login information confidential. The application
                        uses separate roles for customers, staff, business owners, and administrators. Access to pages and
                        actions depends on the role assigned to an account.
                    </p>
                </section>

                <section>
                    <h2 class="mb-2 text-xl font-bold">8. Acceptable use</h2>
                    <p>You must not:</p>
                    <ul class="mt-3 list-disc space-y-2 pl-6">
                        <li>Use the system for fraud, abuse, harassment, or unlawful activity.</li>
                        <li>Submit false, misleading, malicious, or unauthorized information.</li>
                        <li>Attempt to bypass authentication, rate limits, or other security controls.</li>
                        <li>Access, change, or delete another person’s information without authorization.</li>
                        <li>Interfere with the availability, security, or normal operation of the system.</li>
                    </ul>
                </section>

                <section>
                    <h2 class="mb-2 text-xl font-bold">9. Booking information and privacy</h2>
                    <p>
                        Information submitted through the system is handled according to our
                        <a href="{{ route('privacy.policy') }}" class="underline">Privacy Policy</a>. Please review it
                        before submitting personal or vehicle information.
                    </p>
                </section>

                <section>
                    <h2 class="mb-2 text-xl font-bold">10. System availability and responsibility</h2>
                    <p>
                        The application may be unavailable or display errors during development, maintenance, updates, or
                        network interruptions. The system provides booking and management features but does not replace
                        direct communication with Dexter Auto Services when clarification is needed.
                    </p>
                </section>

                <section>
                    <h2 class="mb-2 text-xl font-bold">11. Changes to these terms</h2>
                    <p>
                        We may update these Terms and Conditions when the system, services, or legal requirements change.
                        The updated version will be published on this page with a revised effective date. Continued use of
                        the system after an update means you accept the updated terms.
                    </p>
                </section>

                <section>
                    <h2 class="mb-2 text-xl font-bold">12. Contact us</h2>
                    <p>
                        For questions about a booking or these terms, contact Dexter Auto Services at
                        <a href="tel:+639270071538" class="underline">0927-007153-8</a> or visit us at Don lis Village,
                        Adelfa Street, City of Mati, Davao Oriental, Philippines.
                    </p>
                </section>
            </div>
        </article>

        <div class="mt-8 flex justify-center gap-4 text-center text-sm">
            <a href="{{ route('privacy.policy') }}" class="underline hover:no-underline">Privacy Policy</a>
            <span aria-hidden="true">|</span>
            <a href="{{ route('cookie.policy') }}" class="underline hover:no-underline">Cookie Policy</a>
            <span aria-hidden="true">|</span>
            <a href="{{ route('refund.policy') }}" class="underline hover:no-underline">Refund Policy</a>
            <span aria-hidden="true">|</span>
            <a href="{{ route('guest.home') }}" class="underline hover:no-underline">Home</a>
        </div>
    </main>
</body>
</html>
