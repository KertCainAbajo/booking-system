<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/shop.png') }}">
    <title>Privacy Policy - Dexter Auto Services</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .privacy-policy-page,
        .privacy-policy-page * {
            color: #ffffff !important;
        }
    </style>
</head>
<body class="privacy-policy-page min-h-screen bg-gradient-to-br from-black to-green-900">
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
                <p class="mb-2 text-sm font-semibold uppercase tracking-widest text-white">Legal Information</p>
                <h1 class="text-3xl font-bold sm:text-4xl">Privacy Policy</h1>
                <p class="mt-3 text-sm">Effective date: {{ now()->format('F j, Y') }}</p>
            </header>

            <div class="space-y-8 leading-7">
                <section>
                    <h2 class="mb-2 text-xl font-bold">1. About this policy</h2>
                    <p>
                        Dexter Auto Services (“we,” “us,” or “our”) respects your privacy. This Privacy Policy explains
                        how we collect, use, store, and protect information when you use our website and auto-service
                        booking system.
                    </p>
                </section>

                <section>
                    <h2 class="mb-2 text-xl font-bold">2. Information we collect</h2>
                    <p>Depending on how you use the system, we may collect:</p>
                    <ul class="mt-3 list-disc space-y-2 pl-6">
                        <li><strong>Contact information:</strong> your name, email address, and phone number.</li>
                        <li><strong>Vehicle information:</strong> vehicle make, model, year, and plate number.</li>
                        <li><strong>Booking information:</strong> selected services, preferred date and time, booking reference, and booking status.</li>
                        <li><strong>Account information:</strong> login details, role, and profile information when you create an account.</li>
                        <li><strong>Security information:</strong> information needed for two-factor authentication if you enable it.</li>
                        <li><strong>Technical information:</strong> essential session, authentication, and security data required to operate the system.</li>
                    </ul>
                </section>

                <section>
                    <h2 class="mb-2 text-xl font-bold">3. How we use your information</h2>
                    <ul class="list-disc space-y-2 pl-6">
                        <li>Create and manage service appointments.</li>
                        <li>Generate booking references and display booking-status information.</li>
                        <li>Provide dashboards and tools to authorized staff, owners, and administrators.</li>
                        <li>Authenticate users and support available security features such as two-factor authentication.</li>
                        <li>Maintain the application's operation and investigate technical problems.</li>
                    </ul>
                </section>

                <section>
                    <h2 class="mb-2 text-xl font-bold">4. Guest bookings</h2>
                    <p>
                        You may submit a booking without creating a customer account. Guest-booking information is used
                        to create the booking record and let you track the booking using its reference number.
                    </p>
                </section>

                <section>
                    <h2 class="mb-2 text-xl font-bold">5. Sharing of information</h2>
                    <p>
                        Booking and account information is available to authorized users according to their application
                        role. If you choose Google sign-in, Google processes information under Google's own policies. The
                        contact page may load Google Maps, and some pages may load fonts or interface scripts from
                        external content-delivery services. No other external sharing feature is provided by the current
                        application.
                    </p>
                </section>

                <section>
                    <h2 class="mb-2 text-xl font-bold">6. Cookies and similar technologies</h2>
                    <p>
                        The system uses essential cookies and session technologies for login, security, navigation, and
                        maintaining your booking session. The contact page may load Google Maps, and the login page may
                        provide Google sign-in. See our
                        <a href="{{ route('cookie.policy') }}" class="underline">Cookie Policy</a> for more details.
                    </p>
                </section>

                <section>
                    <h2 class="mb-2 text-xl font-bold">7. Data retention and security</h2>
                    <p>
                        Booking and account information is stored in the application's database. Completed and cancelled
                        booking records may be archived by an authorized system process. The current application does not
                        provide a public self-service data deletion feature.
                    </p>
                </section>

                <section>
                    <h2 class="mb-2 text-xl font-bold">8. Your choices and rights</h2>
                    <p>
                        You may choose whether to submit a guest booking or create a customer account. Information marked
                        as required by a form is needed for that feature to work. Depending on the applicable law and
                        lawful basis for processing, you may ask about access to, correction of, objection to, or deletion
                        of your personal information. Where processing is based on consent, you may also ask to withdraw
                        that consent. The current application does not provide a dedicated privacy-request form; contact
                        Dexter Auto Services directly for questions about your information.
                    </p>
                    <p class="mt-3">
                        If you believe your privacy rights have not been addressed, you may seek guidance or lodge a
                        complaint with the Philippine National Privacy Commission through its official website:
                        <a href="https://privacy.gov.ph/data-subject-rights/" target="_blank" rel="noopener" class="underline">Data Subject Rights</a>.
                    </p>
                </section>

                <section>
                    <h2 class="mb-2 text-xl font-bold">9. Changes to this policy</h2>
                    <p>
                        We may update this policy when the system, services, or legal requirements change. The updated
                        version will be published on this page with a revised effective date.
                    </p>
                </section>

                <section>
                    <h2 class="mb-2 text-xl font-bold">10. Contact us</h2>
                    <p>
                        For privacy questions or requests, contact Dexter Auto Services at
                        <a href="tel:+639270071538" class="underline">0927-007153-8</a> or visit us at
                        Don lis Village, Adelfa Street, City of Mati, Davao Oriental, Philippines.
                    </p>
                </section>
            </div>
        </article>

        <div class="mt-8 flex justify-center gap-4 text-center text-sm">
            <a href="{{ route('terms.conditions') }}" class="underline hover:no-underline">Terms and Conditions</a>
            <span aria-hidden="true">|</span>
            <a href="{{ route('cookie.policy') }}" class="underline hover:no-underline">Cookie Policy</a>
            <span aria-hidden="true">|</span>
            <a href="{{ route('refund.policy') }}" class="underline hover:no-underline">Refund Policy</a>
            <span aria-hidden="true">|</span>
            <span>&copy; {{ date('Y') }} Dexter Auto Services.</span>
        </div>
    </main>
</body>
</html>
