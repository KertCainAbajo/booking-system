<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/shop.png') }}">
    <title>Refund Policy - Dexter Auto Services</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .refund-page,
        .refund-page * {
            color: #ffffff !important;
        }
    </style>
</head>
<body class="refund-page min-h-screen bg-gradient-to-br from-black to-green-900">
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
                <p class="mb-2 text-sm font-semibold uppercase tracking-widest">Payment Information</p>
                <h1 class="text-3xl font-bold sm:text-4xl">Refund Policy</h1>
                <p class="mt-3 text-sm">Effective date: {{ now()->format('F j, Y') }}</p>
            </header>

            <div class="space-y-8 leading-7">
                <section>
                    <h2 class="mb-2 text-xl font-bold">1. Scope of this policy</h2>
                    <p>
                        This Refund Policy explains how payment and refund requests are handled for services booked
                        through Dexter Auto Services. It applies to payments recorded by authorized staff in the
                        booking system.
                    </p>
                </section>

                <section>
                    <h2 class="mb-2 text-xl font-bold">2. No online refund feature</h2>
                    <p>
                        The current booking form does not process online payments and the system does not provide an
                        automatic refund request or refund-processing feature. A booking request by itself does not
                        create a payment or a refund entitlement.
                    </p>
                </section>

                <section>
                    <h2 class="mb-2 text-xl font-bold">3. Refund requests</h2>
                    <p>
                        If you believe you are owed a refund for a payment made to Dexter Auto Services, contact us
                        directly and provide your booking reference, payment details, and the reason for your request.
                        Refund requests are reviewed by Dexter Auto Services based on the relevant service and payment
                        circumstances.
                    </p>
                </section>

                <section>
                    <h2 class="mb-2 text-xl font-bold">4. Payment records</h2>
                    <p>
                        The system may record payments as cash, card, or bank transfer, with a pending or paid status.
                        The payment record shown in the system is for booking administration and does not by itself
                        confirm that a refund has been approved or completed.
                    </p>
                </section>

                <section>
                    <h2 class="mb-2 text-xl font-bold">5. Refund method and timing</h2>
                    <p>
                        If a refund is approved, Dexter Auto Services will confirm the available refund method and
                        expected timing directly with the customer. The method may depend on how the original payment
                        was made. The system does not currently display refund status or provide automatic refund
                        notifications.
                    </p>
                </section>

                <section>
                    <h2 class="mb-2 text-xl font-bold">6. Policy changes</h2>
                    <p>
                        This policy may be updated when the payment or booking process changes. The revised version will
                        be published on this page with a new effective date.
                    </p>
                </section>

                <section>
                    <h2 class="mb-2 text-xl font-bold">7. Contact us</h2>
                    <p>
                        For refund questions, contact Dexter Auto Services at
                        <a href="tel:+639270071538" class="underline">0927-007153-8</a> or visit us at Don lis Village,
                        Adelfa Street, City of Mati, Davao Oriental, Philippines.
                    </p>
                </section>
            </div>
        </article>

        <div class="mt-8 flex flex-wrap justify-center gap-4 text-center text-sm">
            <a href="{{ route('privacy.policy') }}" class="underline hover:no-underline">Privacy Policy</a>
            <span aria-hidden="true">|</span>
            <a href="{{ route('cookie.policy') }}" class="underline hover:no-underline">Cookie Policy</a>
            <span aria-hidden="true">|</span>
            <a href="{{ route('terms.conditions') }}" class="underline hover:no-underline">Terms and Conditions</a>
            <span aria-hidden="true">|</span>
            <a href="{{ route('guest.home') }}" class="underline hover:no-underline">Home</a>
        </div>
    </main>
</body>
</html>
