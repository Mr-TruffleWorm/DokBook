<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
            </style>
        @endif
    </head>
        <body>
            <header>
                @if (Route::has('login'))
                    <nav>
                        @auth
                            <a href="{{ url('/dashboard') }}">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}">
                                Log in
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}">
                                    Register
                                </a>
                            @endif
                        @endauth
                    </nav>
                @endif
            </header>
            <main>        
                <div class="booking">
                        <a href="{{ route('booking-page') }}">Book an Appointment</a>
                </div>
                <div class="choice-container">
                    <div class="specialties">
                        <h1 class="table-title">Top Specialties</h1>
                        <p class="table-description">We can help you find Doctors that have a Specialty at:</p>
                        <ul>
                            <li><a href="{{ route('doctors.specialty', 'primary-care') }}">Primary Care & General Medicine</a></li>
                            <li><a href="{{ route('doctors.specialty', 'ophthalmology') }}">Eye & Vision Doctor</a></li>
                            <li><a href="{{ route('doctors.specialty', 'cardiology') }}">Heart & Cardiology</a></li>
                            <li><a href="{{ route('doctors.specialty', 'dermatology') }}">Skin & Dermatology</a></li>
                            <li><a href="{{ route('doctors.specialty', 'pulmonology') }}">Lung, Chest & Pulmonology</a></li>
                            <li><a href="{{ route('doctors.specialty', 'ent') }}">Ears, Nose & Throat</a></li>
                            <li><a href="{{ route('doctors.specialty', 'nephrology') }}">Kidney & Urine</a></li>
                            <li><a href="{{ route('doctors.specialty', 'neurology') }}">Brain & Nerves</a></li>
                            <li><a href="{{ route('doctors.specialty', 'gastroenterology') }}">Stomach, Digestion & Gastrology</a></li>
                            <li><a href="{{ route('doctors.specialty', 'endocrinology') }}">Diabetes & Endocrinology</a></li>
                            <li><a href="{{ route('doctors.specialty', 'pediatrics') }}">Pediatrics</a></li>
                            <li><a href="{{ route('doctors.specialty', 'obgyn') }}">OB-GYN's & Women's Health</a></li>
                        </ul>
                    </div>

                    <div class="conditions">
                        <h1 class="table-title">Common Conditions</h1>
                        <p class="table-description">Please Choose what are your current Condition.</p>
                        <ul>
                            <li><a href="{{ route('doctors.condition', 'hypertension') }}">High Blood Pressure</a></li>
                            <li><a href="{{ route('doctors.condition', 'diabetes') }}">Diabetes</a></li>
                            <li><a href="{{ route('doctors.condition', 'asthma') }}">Asthma</a></li>
                            <li><a href="{{ route('doctors.condition', 'allergies') }}">Allergies</a></li>
                            <li><a href="{{ route('doctors.condition', 'arthritis') }}">Arthritis</a></li>
                            <li><a href="{{ route('doctors.condition', 'headaches') }}">Headaches & Migraines</a></li>
                            <li><a href="{{ route('doctors.condition', 'anxiety') }}">Anxiety & Depression</a></li>
                            <li><a href="{{ route('doctors.condition', 'back-pain') }}">Back Pain</a></li>
                        </ul>
                    </div>

                    <div class="services">
                        <h1 class="table-title">Available Services</h1>
                        <p class="table-description">Doctors offering these services</p>
                        <ul>
                            <li><a href="{{ route('services.show', 'online-consultation') }}">Online Consultation</a></li>
                            <li><a href="{{ route('services.show', 'clinic-visit') }}">Clinic Visit</a></li>
                            <li><a href="{{ route('services.show', 'health-checkup') }}">Health Check-up</a></li>
                            <li><a href="{{ route('services.show', 'vaccination') }}">Vaccination</a></li>
                            <li><a href="{{ route('services.show', 'laboratory') }}">Laboratory Tests</a></li>
                            <li><a href="{{ route('services.show', 'xray') }}">X-Ray & Imaging</a></li>
                            <li><a href="{{ route('services.show', 'physical-therapy') }}">Physical Therapy</a></li>
                            <li><a href="{{ route('services.show', 'emergency') }}">Emergency Care</a></li>
                        </ul>
                    </div>
                </div>
            </main>
            @if (Route::has('login'))
                <div></div>
            @endif
        </body>
</html>
