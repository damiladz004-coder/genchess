<footer class="bg-purple-900 text-purple-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-10 grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
        <div>
            <h3 class="text-2xl font-display mb-2 text-white">genchess.ng </h3>
            <p class="text-sm text-purple-200">
                Structured chess education for schools, homes, and communities.
            </p>
            <div class="flex items-center gap-3 mt-4">
                <a href="https://web.facebook.com/genchesssacademy" class="inline-flex items-center justify-center w-8 h-8 rounded-full border border-purple-300 text-purple-200 hover:text-white hover:border-white" aria-label="Facebook">
                    <span class="text-xs font-semibold">f</span>
                </a>
                <a href="https://www.instagram.com/genchesseducation/" class="inline-flex items-center justify-center w-8 h-8 rounded-full border border-purple-300 text-purple-200 hover:text-white hover:border-white" aria-label="Instagram">
                    <span class="text-xs font-semibold">ig</span>
                </a>
                <a href="https://x.com/genchess1234" class="inline-flex items-center justify-center w-8 h-8 rounded-full border border-purple-300 text-purple-200 hover:text-white hover:border-white" aria-label="X">
                    <span class="text-xs font-semibold">x</span>
                </a>
                <a href="https://www.youtube.com/@GenchessAcademy-c2z" class="inline-flex items-center justify-center w-8 h-8 rounded-full border border-purple-300 text-purple-200 hover:text-white hover:border-white" aria-label="YouTube">
                    <span class="text-xs font-semibold">yt</span>
                </a>
            </div>
        </div>

        <div>
            <h4 class="font-semibold mb-2 text-white">Quick Links</h4>
            <ul class="space-y-1 text-sm">
                <li><a href="{{ route('about') }}" class="hover:text-white">About</a></li>
                <li><a href="{{ route('chess.in.schools') }}" class="hover:text-white">Chess in Schools</a></li>
                <li><a href="{{ route('chess.communities.homes') }}" class="hover:text-white">Chess in Communities & Homes</a></li>
                <li><a href="{{ route('store.index') }}" class="hover:text-white">Store</a></li>
                <li><a href="{{ route('tournaments') }}" class="hover:text-white">Tournaments</a></li>
                <li><a href="{{ route('instructor.training') }}" class="hover:text-white">Instructor Training</a></li>
                <li><a href="{{ route('careers') }}" class="hover:text-white">Careers</a></li>
                <li><a href="{{ route('register.school') }}" class="hover:text-white">Register Your School</a></li>
                <li><a href="{{ route('training.preview') }}" class="hover:text-white">Instructor Training</a></li>
            </ul>
        </div>

        <div>
            <h4 class="font-semibold mb-2 text-white">Contact</h4>
            <ul class="space-y-1 text-sm text-purple-200">
                <li>Email: info@genchess.ng</li>
                <li>Phone: <a href="https://wa.me/2348078462223" class="hover:text-white">+234-807-846-2223 (WhatsApp)</a></li>
                <li>Address: Lagos, Nigeria</li>
                <li><a href="{{ route('contact') }}" class="hover:text-white">Contact Us</a></li>
            </ul>
        </div>
    </div>
    <div class="border-t border-purple-700 px-4 sm:px-6 py-4 text-center text-xs text-purple-300">
        &copy; {{ date('Y') }} genchess.ng. All rights reserved.
    </div>
</footer>

