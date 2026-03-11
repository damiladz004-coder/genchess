@extends('layouts.public')

@section('content')

<!-- HERO SECTION -->
<section class="bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-16 grid md:grid-cols-2 gap-10 items-center">
        <div>
            <h1 class="text-4xl font-bold text-gray-900 mb-4">
                Chess in Communities & Homes
            </h1>
            <p class="text-lg text-gray-700">
                Genchess Educational Services brings structured chess education directly
                to communities and homes, making learning accessible,
                engaging, and impactful beyond the classroom.
            </p>
        </div>

        <img 
            src="{{ asset('images/hero/genchess-hero.jpg') }}"
            alt="Community chess learning"
            class="rounded-xl shadow-lg"
        >
    </div>
</section>

<!-- WHY COMMUNITY & HOME CHESS -->
<section class="bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-16">
        <h2 class="text-3xl font-semibold text-center mb-10">
            Why Chess in Communities & Homes?
        </h2>

        <div class="grid md:grid-cols-3 gap-8 text-center">
            <div class="bg-white p-6 rounded-xl shadow">
                <h3 class="text-xl font-semibold mb-2">Accessible Learning</h3>
                <p class="text-gray-600">
                    Children can learn chess without needing to enroll
                    in a school-based program.
                </p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow">
                <h3 class="text-xl font-semibold mb-2">Safe & Structured</h3>
                <p class="text-gray-600">
                    Lessons are conducted in familiar environments
                    under the guidance of trained instructors.
                </p>
            </div>

            <div class="bg-white p-6 rounded-xl shadow">
                <h3 class="text-xl font-semibold mb-2">Character Building</h3>
                <p class="text-gray-600">
                    Chess nurtures discipline, confidence,
                    patience, and healthy competition.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- COMMUNITY PROGRAM -->
<section class="bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-16 grid md:grid-cols-2 gap-10 items-center">
        <img 
            src="{{ asset('images/programs/community-chess.jpg') }}"
            alt="Community chess program"
            class="rounded-xl shadow-md"
        >

        <div>
            <h2 class="text-3xl font-semibold mb-4">
                Chess in Communities
            </h2>
            <p class="text-gray-700 leading-relaxed">
                We partner with community centers, estates, religious organizations,
                NGOs, and youth groups to run chess programs that are fun,
                educational, and inclusive.
            </p>

            <ul class="mt-4 space-y-2 text-gray-700">
                <li>Group chess classes</li>
                <li>Weekend and holiday programs</li>
                <li>Community tournaments</li>
                <li>Talent discovery and mentorship</li>
            </ul>
        </div>
    </div>
</section>

<!-- HOME PROGRAM -->
<section class="bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-16 grid md:grid-cols-2 gap-10 items-center">
        <div>
            <h2 class="text-3xl font-semibold mb-4">
                Chess at Home
            </h2>
            <p class="text-gray-700 leading-relaxed">
                Our home chess program offers personalized, one-on-one
                or small-group lessons tailored to each child's age,
                skill level, and learning pace.
            </p>

            <ul class="mt-4 space-y-2 text-gray-700">
                <li>Personalized learning plans</li>
                <li>Flexible scheduling</li>
                <li>In-home or online sessions</li>
                <li>Progress tracking and feedback</li>
            </ul>
        </div>

        <img 
            src="{{ asset('images/programs/home-chess.jpg') }}"
            alt="Home chess lessons"
            class="rounded-xl shadow-md"
        >
    </div>
</section>

<!-- WHO CAN REGISTER -->
<section class="bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-16">
        <h2 class="text-3xl font-semibold text-center mb-10">
            Who Can Register?
        </h2>

        <div class="grid md:grid-cols-4 gap-6 text-center">
            <div class="bg-gray-50 p-5 rounded-xl shadow">Parents</div>
            <div class="bg-gray-50 p-5 rounded-xl shadow">Communities & Estates</div>
            <div class="bg-gray-50 p-5 rounded-xl shadow">Churches & NGOs</div>
            <div class="bg-gray-50 p-5 rounded-xl shadow">Youth Organizations</div>
        </div>
    </div>
</section>

<!-- CALL TO ACTION -->
<section id="booking-form" class="bg-white">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-16">
        <h2 class="text-3xl font-semibold text-center mb-8">
            Book a Chess Program (Communities & Homes)
        </h2>

        @if(session('success'))
            <div class="bg-green-100 text-green-800 p-4 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 text-red-800 p-4 rounded mb-6">
                <p class="font-semibold mb-2">Please fix the following:</p>
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('school.enroll') }}" class="space-y-8 bg-gray-50 p-5 sm:p-8 rounded-xl shadow">
            @csrf

            <!-- Compatibility fields for existing enrollment endpoint -->
            <input type="hidden" name="school_name" id="school_name_hidden" value="{{ old('school_name') }}">
            <input type="hidden" name="program_type" id="program_type_hidden" value="{{ old('program_type', 'community') }}">
            <input type="hidden" name="school_type" value="{{ old('school_type', 'private') }}">
            <input type="hidden" name="class_system" value="{{ old('class_system', 'primary_jss_ss') }}">
            <input type="hidden" name="address_line" id="address_line_hidden" value="{{ old('address_line') }}">
            <input type="hidden" name="student_count" id="student_count_hidden" value="{{ old('student_count') }}">

            <div class="border rounded-lg p-5 bg-white">
                <h3 class="text-xl font-semibold mb-4">Section 1: Applicant Type</h3>
                <p class="text-sm text-gray-600 mb-4">I am registering as:</p>
                <div class="grid md:grid-cols-2 gap-3">
                    <label class="flex items-center gap-2"><input type="radio" name="applicant_type" value="parent_home" @checked(old('applicant_type') === 'parent_home') required> Parent / Home</label>
                    <label class="flex items-center gap-2"><input type="radio" name="applicant_type" value="community_estate" @checked(old('applicant_type') === 'community_estate')> Community / Estate</label>
                    <label class="flex items-center gap-2"><input type="radio" name="applicant_type" value="church" @checked(old('applicant_type') === 'church')> Church / Religious Organization</label>
                    <label class="flex items-center gap-2"><input type="radio" name="applicant_type" value="ngo" @checked(old('applicant_type') === 'ngo')> NGO</label>
                    <label class="flex items-center gap-2"><input type="radio" name="applicant_type" value="youth_org" @checked(old('applicant_type') === 'youth_org')> Youth Organization</label>
                    <label class="flex items-center gap-2"><input type="radio" name="applicant_type" value="school_non_formal" @checked(old('applicant_type') === 'school_non_formal')> School (Non-formal program)</label>
                </div>
            </div>

            <div class="border rounded-lg p-5 bg-white">
                <h3 class="text-xl font-semibold mb-4">Section 2: Contact Information</h3>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-medium mb-1">Full Name</label>
                        <input type="text" id="full_name" name="contact_person" value="{{ old('contact_person') }}" class="w-full border rounded-lg px-4 py-2" required>
                    </div>
                    <div>
                        <label class="block font-medium mb-1">Phone Number (WhatsApp preferred)</label>
                        <input type="text" name="phone" value="{{ old('phone') }}" class="w-full border rounded-lg px-4 py-2" required>
                    </div>
                    <div>
                        <label class="block font-medium mb-1">Email Address</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="w-full border rounded-lg px-4 py-2" required>
                    </div>
                    <div>
                        <label class="block font-medium mb-1">State</label>
                        <x-nigeria-state-select name="state" :value="old('state')" required />
                    </div>
                    <div class="md:col-span-2">
                        <label class="block font-medium mb-1">City / Area</label>
                        <input type="text" name="city" value="{{ old('city') }}" class="w-full border rounded-lg px-4 py-2" required>
                    </div>
                </div>
            </div>

            <div class="border rounded-lg p-5 bg-white">
                <h3 class="text-xl font-semibold mb-4">Section 3: Program Type</h3>
                <p class="text-sm text-gray-600 mb-3">Preferred Session Type:</p>
                <div class="grid md:grid-cols-3 gap-3 mb-4">
                    <label class="flex items-center gap-2"><input type="radio" name="session_type" value="offline" @checked(old('session_type') === 'offline') required> Offline (Physical)</label>
                    <label class="flex items-center gap-2"><input type="radio" name="session_type" value="online" @checked(old('session_type') === 'online')> Online</label>
                    <label class="flex items-center gap-2"><input type="radio" name="session_type" value="hybrid" @checked(old('session_type') === 'hybrid')> Hybrid</label>
                </div>
                <div id="physical_location_wrap">
                    <label class="block font-medium mb-1">Location for Physical Sessions (if offline)</label>
                    <input type="text" id="physical_location" name="physical_location" value="{{ old('physical_location', old('address_line')) }}" class="w-full border rounded-lg px-4 py-2">
                </div>
            </div>

            <div id="parent_section" class="border rounded-lg p-5 bg-white hidden">
                <h3 class="text-xl font-semibold mb-4">Section 4: For Parents / Guardians (Home Lessons Only)</h3>
                <div class="grid md:grid-cols-2 gap-4">
                    <div>
                        <label class="block font-medium mb-1">Number of Children</label>
                        <select id="children_count" name="children_count" class="w-full border rounded-lg px-4 py-2">
                            <option value="">Select</option>
                            <option value="1" @selected(old('children_count') === '1')>1</option>
                            <option value="2" @selected(old('children_count') === '2')>2</option>
                            <option value="3" @selected(old('children_count') === '3')>3</option>
                            <option value="4" @selected(old('children_count') === '4')>4</option>
                            <option value="5+" @selected(old('children_count') === '5+')>5+</option>
                        </select>
                    </div>
                    <div>
                        <label class="block font-medium mb-1">Age(s) of Child(ren)</label>
                        <input type="text" name="children_ages" value="{{ old('children_ages') }}" class="w-full border rounded-lg px-4 py-2" placeholder="e.g. 6, 9, 11">
                    </div>
                </div>

                <p class="text-sm text-gray-600 mt-4 mb-3">Current Chess Level:</p>
                <div class="grid md:grid-cols-2 gap-3 mb-4">
                    <label class="flex items-center gap-2"><input type="radio" name="chess_level" value="beginner" @checked(old('chess_level') === 'beginner')> Beginner</label>
                    <label class="flex items-center gap-2"><input type="radio" name="chess_level" value="intermediate" @checked(old('chess_level') === 'intermediate')> Intermediate</label>
                    <label class="flex items-center gap-2"><input type="radio" name="chess_level" value="advanced" @checked(old('chess_level') === 'advanced')> Advanced</label>
                    <label class="flex items-center gap-2"><input type="radio" name="chess_level" value="no_experience" @checked(old('chess_level') === 'no_experience')> No Experience</label>
                </div>

                <p class="text-sm text-gray-600 mb-3">Preferred Schedule:</p>
                <div class="grid md:grid-cols-3 gap-3 mb-4">
                    <label class="flex items-center gap-2"><input type="radio" name="preferred_schedule" value="weekdays" @checked(old('preferred_schedule') === 'weekdays')> Weekdays</label>
                    <label class="flex items-center gap-2"><input type="radio" name="preferred_schedule" value="weekends" @checked(old('preferred_schedule') === 'weekends')> Weekends</label>
                    <label class="flex items-center gap-2"><input type="radio" name="preferred_schedule" value="flexible" @checked(old('preferred_schedule') === 'flexible')> Flexible</label>
                </div>

                <div>
                    <label class="block font-medium mb-1">Preferred Time</label>
                    <input type="time" name="parent_preferred_time" value="{{ old('parent_preferred_time') }}" class="w-full md:w-72 border rounded-lg px-4 py-2">
                </div>
            </div>

            <div id="org_section" class="border rounded-lg p-5 bg-white hidden">
                <h3 id="org_section_title" class="text-xl font-semibold mb-1">Section 5: For Organizations / Communities</h3>
                <p id="org_section_description" class="text-sm text-gray-600 mb-4">Fill this section if you selected a non-parent applicant type.</p>
                <div class="grid md:grid-cols-2 gap-4">
                    <div class="md:col-span-2">
                        <label class="block font-medium mb-1">Name of Organization / Estate / Church</label>
                        <input type="text" id="organization_name" name="organization_name" value="{{ old('organization_name', old('school_name')) }}" class="w-full border rounded-lg px-4 py-2">
                    </div>
                    <div>
                        <label class="block font-medium mb-1">Estimated Number of Participants</label>
                        <select id="participants_estimate" name="participants_estimate" class="w-full border rounded-lg px-4 py-2">
                            <option value="">Select</option>
                            <option value="5-10" @selected(old('participants_estimate') === '5-10')>5-10</option>
                            <option value="10-20" @selected(old('participants_estimate') === '10-20')>10-20</option>
                            <option value="20-50" @selected(old('participants_estimate') === '20-50')>20-50</option>
                            <option value="50+" @selected(old('participants_estimate') === '50+')>50+</option>
                        </select>
                    </div>
                </div>

                <p class="text-sm text-gray-600 mt-4 mb-3">Age Group:</p>
                <div class="grid md:grid-cols-2 gap-3 mb-4">
                    <label class="flex items-center gap-2"><input type="radio" name="age_group" value="children" @checked(old('age_group') === 'children')> Children</label>
                    <label class="flex items-center gap-2"><input type="radio" name="age_group" value="teenagers" @checked(old('age_group') === 'teenagers')> Teenagers</label>
                    <label class="flex items-center gap-2"><input type="radio" name="age_group" value="adults" @checked(old('age_group') === 'adults')> Adults</label>
                    <label class="flex items-center gap-2"><input type="radio" name="age_group" value="mixed" @checked(old('age_group') === 'mixed')> Mixed</label>
                </div>

                <p class="text-sm text-gray-600 mb-3">Program Type:</p>
                <div class="grid md:grid-cols-2 gap-3">
                    <label class="flex items-center gap-2"><input type="radio" name="org_program_type" value="weekly_classes" @checked(old('org_program_type') === 'weekly_classes')> Weekly Classes</label>
                    <label class="flex items-center gap-2"><input type="radio" name="org_program_type" value="weekend_program" @checked(old('org_program_type') === 'weekend_program')> Weekend Program</label>
                    <label class="flex items-center gap-2"><input type="radio" name="org_program_type" value="holiday_bootcamp" @checked(old('org_program_type') === 'holiday_bootcamp')> Holiday Bootcamp</label>
                    <label class="flex items-center gap-2"><input type="radio" name="org_program_type" value="tournament_only" @checked(old('org_program_type') === 'tournament_only')> Tournament Only</label>
                    <label class="flex items-center gap-2 md:col-span-2"><input type="radio" name="org_program_type" value="long_term_partnership" @checked(old('org_program_type') === 'long_term_partnership')> Long-Term Partnership</label>
                </div>
            </div>

            <div class="border rounded-lg p-5 bg-white">
                <h3 class="text-xl font-semibold mb-4">Section 6: Meeting & Consultation Booking</h3>
                <p class="text-sm text-gray-600 mb-3">Would you like to schedule a consultation meeting?</p>
                <div class="flex flex-wrap gap-6 mb-4">
                    <label class="flex items-center gap-2"><input type="radio" name="consultation_needed" value="yes" @checked(old('consultation_needed') === 'yes')> Yes</label>
                    <label class="flex items-center gap-2"><input type="radio" name="consultation_needed" value="no" @checked(old('consultation_needed') === 'no')> No</label>
                </div>

                <div id="consultation_details" class="hidden">
                    <p class="text-sm text-gray-600 mb-3">Preferred Meeting Type:</p>
                    <div class="grid md:grid-cols-2 gap-3 mb-4">
                        <label class="flex items-center gap-2"><input type="radio" name="meeting_type" value="physical" @checked(old('meeting_type') === 'physical')> Physical Meeting</label>
                        <label class="flex items-center gap-2"><input type="radio" name="meeting_type" value="virtual" @checked(old('meeting_type') === 'virtual')> Zoom / Google Meet</label>
                    </div>

                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-medium mb-1">Preferred Date</label>
                            <input type="date" name="meeting_date" value="{{ old('meeting_date') }}" class="w-full border rounded-lg px-4 py-2">
                        </div>
                        <div>
                            <label class="block font-medium mb-1">Preferred Time</label>
                            <input type="time" name="meeting_time" value="{{ old('meeting_time') }}" class="w-full border rounded-lg px-4 py-2">
                        </div>
                    </div>
                </div>
            </div>

            <div class="border rounded-lg p-5 bg-white">
                <h3 class="text-xl font-semibold mb-4">Section 7: Additional Information</h3>
                <label class="block font-medium mb-1">Tell us more about your interest or goals</label>
                <textarea id="goals_interest" name="message" rows="4" class="w-full border rounded-lg px-4 py-2">{{ old('message') }}</textarea>
            </div>

            <div class="border rounded-lg p-5 bg-white">
                <h3 class="text-xl font-semibold mb-4">Section 8: Agreement</h3>
                <label class="flex items-start gap-2">
                    <input type="checkbox" name="consent" value="1" required class="mt-1">
                    <span>I agree to be contacted by Genchess Educational Services regarding this program.</span>
                </label>
            </div>

            <button type="submit" class="w-full bg-green-600 text-white py-3 rounded-lg font-semibold hover:bg-green-700 transition">
                Book Session / Request Consultation
            </button>
        </form>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const applicantRadios = document.querySelectorAll('input[name="applicant_type"]');
    const sessionTypeRadios = document.querySelectorAll('input[name="session_type"]');
    const consultationRadios = document.querySelectorAll('input[name="consultation_needed"]');

    const parentSection = document.getElementById('parent_section');
    const orgSection = document.getElementById('org_section');
    const orgSectionTitle = document.getElementById('org_section_title');
    const orgSectionDescription = document.getElementById('org_section_description');
    const physicalLocationWrap = document.getElementById('physical_location_wrap');
    const consultationDetails = document.getElementById('consultation_details');

    const fullName = document.getElementById('full_name');
    const organizationName = document.getElementById('organization_name');
    const physicalLocation = document.getElementById('physical_location');
    const childrenCount = document.getElementById('children_count');
    const participantsEstimate = document.getElementById('participants_estimate');
    const goalsInterest = document.getElementById('goals_interest');
    const childrenAges = document.querySelector('input[name="children_ages"]');
    const parentPreferredTime = document.querySelector('input[name="parent_preferred_time"]');
    const meetingDate = document.querySelector('input[name="meeting_date"]');
    const meetingTime = document.querySelector('input[name="meeting_time"]');

    const schoolNameHidden = document.getElementById('school_name_hidden');
    const programTypeHidden = document.getElementById('program_type_hidden');
    const addressLineHidden = document.getElementById('address_line_hidden');
    const studentCountHidden = document.getElementById('student_count_hidden');

    if (!parentSection || !orgSection || !physicalLocationWrap || !consultationDetails || !schoolNameHidden || !programTypeHidden || !addressLineHidden || !studentCountHidden) {
        return;
    }

    const parentFields = Array.from(parentSection.querySelectorAll('input, select, textarea'));
    const orgFields = Array.from(orgSection.querySelectorAll('input, select, textarea'));
    const consultationFields = Array.from(consultationDetails.querySelectorAll('input, select, textarea'));
    const physicalLocationFields = Array.from(physicalLocationWrap.querySelectorAll('input, select, textarea'));

    function setFieldState(fields, enabled) {
        fields.forEach((field) => {
            field.disabled = !enabled;
            if (!enabled) {
                field.required = false;
            }
        });
    }

    function selectedValue(name) {
        const checked = document.querySelector('input[name="' + name + '"]:checked');
        return checked ? checked.value : '';
    }

    function toggleApplicantSections() {
        const applicantType = selectedValue('applicant_type');
        const isParent = applicantType === 'parent_home';

        parentSection.classList.toggle('hidden', !isParent);
        orgSection.classList.toggle('hidden', isParent);

        setFieldState(parentFields, isParent);
        setFieldState(orgFields, !isParent);

        const childrenCountField = document.getElementById('children_count');
        const organizationNameField = document.getElementById('organization_name');
        const participantsEstimateField = document.getElementById('participants_estimate');

        if (childrenCountField) {
            childrenCountField.required = isParent;
        }
        if (organizationNameField) {
            organizationNameField.required = !isParent;
        }
        if (participantsEstimateField) {
            participantsEstimateField.required = !isParent;
        }

        const orgLabels = {
            community_estate: ['Section 5: For Communities / Estates', 'Tell us about your estate or community setup.'],
            church: ['Section 5: For Churches / Religious Organizations', 'Tell us about your church or faith-based learning plan.'],
            ngo: ['Section 5: For NGOs', 'Tell us about your NGO program and the target participants.'],
            youth_org: ['Section 5: For Youth Organizations', 'Tell us about your youth development program.'],
            school_non_formal: ['Section 5: For Schools (Non-formal Program)', 'Tell us about the non-formal chess setup for your learners.'],
        };

        if (!isParent && orgSectionTitle && orgSectionDescription) {
            const [title, description] = orgLabels[applicantType] || ['Section 5: For Organizations / Communities', 'Fill this section if you selected a non-parent applicant type.'];
            orgSectionTitle.textContent = title;
            orgSectionDescription.textContent = description;
        }
    }

    function togglePhysicalLocation() {
        const sessionType = selectedValue('session_type');
        const show = sessionType === 'offline' || sessionType === 'hybrid';
        physicalLocationWrap.classList.toggle('hidden', !show);
        setFieldState(physicalLocationFields, show);
        physicalLocationFields.forEach((field) => field.required = show);
    }

    function toggleConsultationDetails() {
        const needed = selectedValue('consultation_needed');
        const show = needed === 'yes';
        consultationDetails.classList.toggle('hidden', !show);
        setFieldState(consultationFields, show);
        consultationFields.forEach((field) => field.required = show);
    }

    function syncCompatibilityFields() {
        const applicantType = selectedValue('applicant_type');
        const isParent = applicantType === 'parent_home';

        schoolNameHidden.value = isParent ? (fullName.value || 'Home Lesson Request') : (organizationName.value || 'Community Program Request');
        programTypeHidden.value = isParent ? 'home' : (applicantType === 'school_non_formal' ? 'school' : 'community');
        addressLineHidden.value = physicalLocation.value || '';

        if (isParent) {
            studentCountHidden.value = childrenCount.value && childrenCount.value !== '5+' ? childrenCount.value : '';
        } else {
            const map = { '5-10': '10', '10-20': '20', '20-50': '50', '50+': '' };
            studentCountHidden.value = map[participantsEstimate.value] || '';
        }
    }

    applicantRadios.forEach((radio) => radio.addEventListener('change', function () {
        toggleApplicantSections();
        syncCompatibilityFields();
    }));
    sessionTypeRadios.forEach((radio) => radio.addEventListener('change', function () {
        togglePhysicalLocation();
        syncCompatibilityFields();
    }));
    consultationRadios.forEach((radio) => radio.addEventListener('change', toggleConsultationDetails));

    [fullName, organizationName, physicalLocation, childrenCount, participantsEstimate, goalsInterest, childrenAges, parentPreferredTime, meetingDate, meetingTime].forEach((el) => {
        if (el) {
            el.addEventListener('input', syncCompatibilityFields);
            el.addEventListener('change', syncCompatibilityFields);
        }
    });

    toggleApplicantSections();
    togglePhysicalLocation();
    toggleConsultationDetails();
    syncCompatibilityFields();
});
</script>

@endsection
