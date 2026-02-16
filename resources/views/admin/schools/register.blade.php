<x-guest-layout>
    <form method="POST" action="/register-school">
        @csrf

        <h2>Register Your School</h2>

        <input name="school_name" placeholder="School Name" required>
        <select name="school_type" required>
            <option value="">School Type</option>
            <option value="private">Private</option>
            <option value="public">Public</option>
        </select>

        <select name="class_system" required>
            <option value="">Class System</option>
            <option value="primary_jss_ss">Primary / JSS / SS</option>
            <option value="grade_1_12">Grade 1-12</option>
            <option value="year_1_12">Year 1-12</option>
        </select>

        <input name="address_line" placeholder="Address (optional)">
        <input name="city" placeholder="City" required>
        <x-nigeria-state-select name="state" required />

        <input name="contact_person" placeholder="Contact Person" required>
        <input name="phone" placeholder="Phone Number" required>
        <input type="email" name="email" placeholder="Email" required>

        <button type="submit">Submit</button>

        @if(session('success'))
            <p>{{ session('success') }}</p>
        @endif
    </form>
</x-guest-layout>
