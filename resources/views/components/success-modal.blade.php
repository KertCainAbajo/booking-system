@props(['message' => ''])

@if(session('success') || $message)
<div x-data="{ show: true }" 
     x-show="show"
     x-transition:enter="transition ease-out duration-400"
     x-transition:enter-start="opacity-0 transform scale-75 -translate-y-10"
     x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="opacity-100 transform scale-100 translate-y-0"
     x-transition:leave-end="opacity-0 transform scale-75 translate-y-10"
     class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50"
     style="display: none;">
    <div x-transition:enter="transition ease-out duration-400 delay-100"
         x-transition:enter-start="opacity-0 transform scale-90"
         x-transition:enter-end="opacity-100 transform scale-100"
         class="p-6 border-2 border-garage-neon shadow-2xl rounded-lg bg-gradient-to-br from-garage-charcoal to-garage-darkgreen w-full max-w-md">
        <div class="text-center">
            <!-- Success Icon -->
            <div x-transition:enter="transition ease-out duration-500 delay-200"
                 x-transition:enter-start="opacity-0 transform scale-50 rotate-180"
                 x-transition:enter-end="opacity-100 transform scale-100 rotate-0"
                 class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-garage-neon/20 border-2 border-garage-neon mb-3 animate-pulse">
                <svg class="h-7 w-7 text-garage-neon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            
            <!-- Success Message -->
            <h3 x-transition:enter="transition ease-out duration-400 delay-300"
                x-transition:enter-start="opacity-0 transform translate-y-4"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                class="text-lg font-bold text-garage-neon mb-2 service-tag">SUCCESS!</h3>
            <p x-transition:enter="transition ease-out duration-400 delay-400"
               x-transition:enter-start="opacity-0 transform translate-y-4"
               x-transition:enter-end="opacity-100 transform translate-y-0"
               class="text-garage-offwhite mb-4 font-medium text-sm">{{ session('success') ?? $message }}</p>
            
            <!-- OK Button -->
            <button x-transition:enter="transition ease-out duration-400 delay-500"
                    x-transition:enter-start="opacity-0 transform scale-90"
                    x-transition:enter-end="opacity-100 transform scale-100"
                    @click="show = false" 
                    class="w-full px-4 py-2 bg-garage-neon text-garage-black font-bold rounded-lg hover:bg-garage-emerald transition-all duration-200 service-tag shadow-neon-green text-sm">
                OK
            </button>
        </div>
    </div>
</div>
@endif
