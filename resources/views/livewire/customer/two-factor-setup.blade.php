<div class="py-12">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 sm:p-8">
                @if (!$showRecoveryCodes)
                    <!-- Setup 2FA -->
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Enable Two-Factor Authentication</h2>
                        <p class="text-gray-600 mb-6">Secure your account with Google Authenticator</p>

                        <div class="space-y-6">
                            <!-- Step 1 -->
                            <div class="border-l-4 border-green-500 pl-4">
                                <h3 class="font-semibold text-gray-900 mb-2">Step 1: Install Google Authenticator</h3>
                                <p class="text-sm text-gray-600 mb-3">Download and install Google Authenticator on your mobile device:</p>
                                <div class="flex gap-4">
                                    <a href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2" 
                                       target="_blank"
                                       class="inline-flex items-center px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-md hover:bg-gray-700">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M3,20.5V3.5C3,2.91 3.34,2.39 3.84,2.15L13.69,12L3.84,21.85C3.34,21.6 3,21.09 3,20.5M16.81,15.12L6.05,21.34L14.54,12.85L16.81,15.12M20.16,10.81C20.5,11.08 20.75,11.5 20.75,12C20.75,12.5 20.5,12.92 20.16,13.19L17.89,14.5L15.39,12L17.89,9.5L20.16,10.81M6.05,2.66L16.81,8.88L14.54,11.15L6.05,2.66Z" />
                                        </svg>
                                        Google Play
                                    </a>
                                    <a href="https://apps.apple.com/app/google-authenticator/id388497605" 
                                       target="_blank"
                                       class="inline-flex items-center px-4 py-2 bg-gray-800 text-white text-sm font-medium rounded-md hover:bg-gray-700">
                                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M18.71,19.5C17.88,20.74 17,21.95 15.66,21.97C14.32,22 13.89,21.18 12.37,21.18C10.84,21.18 10.37,21.95 9.1,22C7.79,22.05 6.8,20.68 5.96,19.47C4.25,17 2.94,12.45 4.7,9.39C5.57,7.87 7.13,6.91 8.82,6.88C10.1,6.86 11.32,7.75 12.11,7.75C12.89,7.75 14.37,6.68 15.92,6.84C16.57,6.87 18.39,7.1 19.56,8.82C19.47,8.88 17.39,10.1 17.41,12.63C17.44,15.65 20.06,16.66 20.09,16.67C20.06,16.74 19.67,18.11 18.71,19.5M13,3.5C13.73,2.67 14.94,2.04 15.94,2C16.07,3.17 15.6,4.35 14.9,5.19C14.21,6.04 13.07,6.7 11.95,6.61C11.8,5.46 12.36,4.26 13,3.5Z" />
                                        </svg>
                                        App Store
                                    </a>
                                </div>
                            </div>

                            <!-- Step 2 -->
                            <div class="border-l-4 border-green-500 pl-4">
                                <h3 class="font-semibold text-gray-900 mb-2">Step 2: Scan QR Code</h3>
                                <p class="text-sm text-gray-600 mb-3">Open Google Authenticator and scan this QR code:</p>
                                
                                <div class="bg-white p-4 border-2 border-gray-200 rounded-lg inline-block">
                                    {!! \SimpleSoftwareIO\QrCode\Facades\QrCode::size(200)->generate($qrCodeUrl) !!}
                                </div>

                                <div class="mt-4 p-3 bg-gray-50 rounded-md">
                                    <p class="text-xs text-gray-600 mb-1">Or enter this code manually:</p>
                                    <code class="text-sm font-mono bg-gray-100 px-2 py-1 rounded">{{ $secret }}</code>
                                </div>
                            </div>

                            <!-- Step 3 -->
                            <div class="border-l-4 border-green-500 pl-4">
                                <h3 class="font-semibold text-gray-900 mb-2">Step 3: Verify Code</h3>
                                <p class="text-sm text-gray-600 mb-3">Enter the 6-digit code from Google Authenticator:</p>
                                
                                <div class="max-w-xs">
                                    <input 
                                        type="text" 
                                        wire:model="verificationCode"
                                        maxlength="6"
                                        pattern="[0-9]{6}"
                                        placeholder="000000"
                                        class="w-full px-4 py-3 text-center text-2xl tracking-widest font-mono border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                    >
                                    @error('verificationCode')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <button 
                                    wire:click="enable2FA"
                                    wire:loading.attr="disabled"
                                    class="mt-4 px-6 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                                >
                                    <span wire:loading.remove>Enable Two-Factor Authentication</span>
                                    <span wire:loading>Verifying...</span>
                                </button>
                            </div>
                        </div>
                    </div>
                @else
                    <!-- Recovery Codes -->
                    <div>
                        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                            <div class="flex items-start">
                                <svg class="w-6 h-6 text-green-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <div>
                                    <h3 class="text-lg font-semibold text-green-900">Two-Factor Authentication Enabled!</h3>
                                    <p class="text-sm text-green-700 mt-1">Your account is now more secure.</p>
                                </div>
                            </div>
                        </div>

                        <h2 class="text-2xl font-bold text-gray-900 mb-2">Save Your Recovery Codes</h2>
                        <p class="text-gray-600 mb-6">Store these codes in a safe place. You can use them to access your account if you lose your device.</p>

                        <div class="bg-gray-50 border-2 border-gray-300 rounded-lg p-6 mb-6">
                            <div class="grid grid-cols-2 gap-3">
                                @foreach($recoveryCodes as $code)
                                    <div class="bg-white px-4 py-2 rounded border border-gray-200">
                                        <code class="text-sm font-mono">{{ $code }}</code>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-yellow-600 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                                <div>
                                    <h4 class="font-semibold text-yellow-900">Important</h4>
                                    <p class="text-sm text-yellow-700 mt-1">Each recovery code can only be used once. Print or download these codes and keep them somewhere safe.</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex gap-3">
                            <button 
                                onclick="window.print()"
                                class="px-4 py-2 bg-gray-600 text-white font-medium rounded-lg hover:bg-gray-700 transition-colors"
                            >
                                Print Codes
                            </button>
                            
                            <button 
                                wire:click="finishSetup"
                                class="px-6 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 transition-colors"
                            >
                                Continue to Dashboard
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
