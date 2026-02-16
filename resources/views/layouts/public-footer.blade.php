<footer class="bg-purple-900 text-purple-100">
    <div class="max-w-7xl mx-auto px-6 py-10 grid md:grid-cols-3 gap-8">
        <div>
            <h3 class="text-2xl font-display mb-2 text-white">Genchess Academy</h3>
            <p class="text-sm text-purple-200">
                Structured chess education for schools, homes, and communities.
            </p>
            <div class="flex items-center gap-3 mt-4">
                <a href="#" class="inline-flex items-center justify-center w-8 h-8 rounded-full border border-purple-300 text-purple-200 hover:text-white hover:border-white" aria-label="Facebook">
                    <span class="text-xs font-semibold">f</span>
                </a>
                <a href="#" class="inline-flex items-center justify-center w-8 h-8 rounded-full border border-purple-300 text-purple-200 hover:text-white hover:border-white" aria-label="Instagram">
                    <span class="text-xs font-semibold">ig</span>
                </a>
                <a href="#" class="inline-flex items-center justify-center w-8 h-8 rounded-full border border-purple-300 text-purple-200 hover:text-white hover:border-white" aria-label="X">
                    <span class="text-xs font-semibold">x</span>
                </a>
            </div>
        </div>

        <div>
            <h4 class="font-semibold mb-2 text-white">Quick Links</h4>
            <ul class="space-y-1 text-sm">
                <li><a href="{{ route('about') }}" class="hover:text-white">About</a></li>
                <li><a href="{{ route('chess.in.schools') }}" class="hover:text-white">Chess in Schools</a></li>
                <li><a href="{{ route('chess.communities.homes') }}" class="hover:text-white">Chess in Communities & Homes</a></li>
                <li><a href="{{ route('products') }}" class="hover:text-white">Products</a></li>
                <li><a href="{{ route('tournaments') }}" class="hover:text-white">Tournaments</a></li>
                <li><a href="{{ route('instructor.training') }}" class="hover:text-white">Instructor Training</a></li>
                <li><a href="{{ route('careers') }}" class="hover:text-white">Careers</a></li>
                <li><a href="{{ route('register.school') }}" class="hover:text-white">Register Your School</a></li>
                <li><a href="{{ route('instructor.screening.create') }}" class="hover:text-white">Become an Instructor</a></li>
            </ul>
        </div>

        <div>
            <h4 class="font-semibold mb-2 text-white">Contact</h4>
            <ul class="space-y-1 text-sm text-purple-200">
                <li>Email: info@genchessacademy.com</li>
                <li>Phone: +234-000-000-0000</li>
                <li>Address: Lagos, Nigeria</li>
                <li><a href="{{ route('contact') }}" class="hover:text-white">Contact Form</a></li>
            </ul>
        </div>
    </div>
    <div class="border-t border-purple-700 py-4 text-center text-xs text-purple-300">
        &copy; {{ date('Y') }} Genchess Academy. All rights reserved.
    </div>
</footer>
