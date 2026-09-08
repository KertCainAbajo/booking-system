<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/shop.png') }}">
    <title>Cookie Policy - Dexter Auto Services</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .cookie-policy-page,
        .cookie-policy-page * {
            color: #ffffff !important;
        }
    </style>
</head>
<body class="cookie-policy-page min-h-screen bg-gradient-to-br from-black to-green-900">
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
                <h1 class="text-3xl font-bold sm:text-4xl">Cookie Policy</h1>
                <p class="mt-3 text-sm">Effective date: {{ now()->format('F j, Y') }}</p>
            </header>

            <div class="space-y-8 leading-7">
                <section>
                    <h2 class="mb-2 text-xl font-bold">1. What cookies are</h2>
                    <p>
                        Cookies are small data files stored by your browser when you visit a website. They can help a
                        website remember a session and protect forms and account actions.
                    </p>
                </section>

                <section>
                    <h2 class="mb-2 text-xl font-bold">2. Cookies used by this system</h2>
                    <p>
                        Dexter Auto Services currently uses cookies required for the website and booking system to work:
                    </p>
                    <ul class="mt-3 list-disc space-y-2 pl-6">
                        <li><strong>Session cookies:</strong> help maintain login state, booking state, and server-side session information.</li>
                        <li><strong>Security cookies:</strong> help protect forms and requests against cross-site request forgery.</li>
                        <li><strong>Navigation cookies:</strong> may support normal page navigation and interactive Livewire features.</li>
                    </ul>
                    <p class="mt-3">
                        The current application does not add a separate analytics or advertising cookie system.
                    </p>
                </section>

                <section>
                    <h2 class="mb-2 text-xl font-bold">3. Third-party services</h2>
                    <p>
                        Some pages may connect to third-party services. The login page may provide Google sign-in, and
                        the contact page may load Google Maps. Some pages may also load fonts or interface scripts from
                        external content-delivery services. Those services may use their own cookies or similar
                        technologies under their own policies. Dexter Auto Services does not control those third-party
                        technologies.
                    </p>
                </section>

                <section>
                    <h2 class="mb-2 text-xl font-bold">4. Managing cookies</h2>
                    <p>
                        You can manage or delete cookies through your browser settings. Blocking essential cookies may
                        prevent login, booking forms, tracking, or other parts of the system from working correctly.
                    </p>
                </section>

                <section>
                    <h2 class="mb-2 text-xl font-bold">5. Updates</h2>
                    <p>
                        This Cookie Policy may be updated when the system's cookie or third-party service configuration
                        changes. The updated version will be published on this page with a revised effective date.
                    </p>
                </section>

                <section>
                    <h2 class="mb-2 text-xl font-bold">6. Related policies and contact</h2>
                    <p>
                        For information about personal and vehicle information, read our
                        <a href="{{ route('privacy.policy') }}" class="underline">Privacy Policy</a>. For payment questions,
                        read our <a href="{{ route('refund.policy') }}" class="underline">Refund Policy</a>. Otherwise,
                        contact Dexter Auto Services at
                        <a href="tel:+639270071538" class="underline">0927-007153-8</a>.
                    </p>
                </section>
            </div>
        </article>

        <div class="mt-8 flex flex-wrap justify-center gap-4 text-center text-sm">
            <a href="{{ route('privacy.policy') }}" class="underline hover:no-underline">Privacy Policy</a>
            <span aria-hidden="true">|</span>
            <a href="{{ route('terms.conditions') }}" class="underline hover:no-underline">Terms and Conditions</a>
            <span aria-hidden="true">|</span>
            <a href="{{ route('refund.policy') }}" class="underline hover:no-underline">Refund Policy</a>
            <span aria-hidden="true">|</span>
            <a href="{{ route('guest.home') }}" class="underline hover:no-underline">Home</a>
        </div>
    </main>
</body>
</html>
