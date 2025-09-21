<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script src="https://cdn.tailwindcss.com"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
        @vite(['resources/css/app.css', 'resources/js/homepage-scripts.js'])

        <title>{{ config('app.name', 'DokBook') }}</title>
    </head>
    
    @extends('layouts.main')<!-- Reuse the main blade fie for header and footer layout -->

    @section('maincontent')<!-- Main content section -->

    @include('layouts.homepage.home-menu')

    <!-- Automatic Image Carousel -->
    <x-home-carousel></x-home-carousel><!--Located at views/components folder-->
    
    <!-- What is DokBook Section -->
<section class="py-12 bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">What is DokBook?</h2>
            <div class="w-20 h-1 bg-blue-500 mx-auto mb-6"></div>
            <p class="text-lg text-gray-600 max-w-3xl mx-auto leading-relaxed">
                DokBook is your trusted digital healthcare companion that connects you with qualified medical professionals. 
                We provide seamless online consultations, easy appointment booking, and comprehensive healthcare services 
                designed to make quality medical care accessible anytime, anywhere.
            </p>
        </div>
        
        <div class="grid md:grid-cols-3 gap-8 mt-12">
            <div class="text-center p-6 rounded-lg border border-gray-100 hover:shadow-lg transition-shadow">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Trusted Professionals</h3>
                <p class="text-gray-600">Connect with licensed and verified healthcare providers</p>
            </div>
            
            <div class="text-center p-6 rounded-lg border border-gray-100 hover:shadow-lg transition-shadow">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">24/7 Availability</h3>
                <p class="text-gray-600">Access healthcare services anytime, day or night</p>
            </div>
            
            <div class="text-center p-6 rounded-lg border border-gray-100 hover:shadow-lg transition-shadow">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">Secure & Private</h3>
                <p class="text-gray-600">Your health information is protected with advanced security</p>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="py-12 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Our Services</h2>
            <div class="w-20 h-1 bg-blue-500 mx-auto mb-6"></div>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Comprehensive healthcare services tailored to meet your medical needs
            </p>
        </div>
        
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow">
                <div class="w-12 h-12 bg-blue-500 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">General Consultation</h3>
                <p class="text-gray-600 text-sm">Get expert medical advice for common health concerns and general wellness</p>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow">
                <div class="w-12 h-12 bg-red-500 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Cardiology</h3>
                <p class="text-gray-600 text-sm">Heart health assessments and cardiovascular disease management</p>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow">
                <div class="w-12 h-12 bg-green-500 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Dermatology</h3>
                <p class="text-gray-600 text-sm">Skin condition diagnosis and treatment from certified dermatologists</p>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow">
                <div class="w-12 h-12 bg-purple-500 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Laboratory Tests</h3>
                <p class="text-gray-600 text-sm">Complete diagnostic testing and health screening packages</p>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow">
                <div class="w-12 h-12 bg-yellow-500 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Mental Health</h3>
                <p class="text-gray-600 text-sm">Professional psychological counseling and mental wellness support</p>
            </div>
            
            <div class="bg-white rounded-lg shadow-sm p-6 hover:shadow-md transition-shadow">
                <div class="w-12 h-12 bg-indigo-500 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Pediatrics</h3>
                <p class="text-gray-600 text-sm">Specialized healthcare services for infants, children, and adolescents</p>
            </div>
        </div>
    </div>
</section>

<!-- Doctors Grid -->
<section class="py-12 bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Our Doctors</h2>
            <div class="w-20 h-1 bg-blue-500 mx-auto mb-6"></div>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Meet our experienced and qualified healthcare professionals
            </p>
        </div>
        
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <!-- Doctor 1 -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                <img src="https://images.unsplash.com/photo-1612349317150-e413f6a5b16d?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" 
                     alt="Dr. Michael Smith" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">Dr. Michael Smith</h3>
                    <p class="text-sm text-blue-600 mb-2">Cardiologist</p>
                    <p class="text-xs text-gray-600 mb-3">15+ years experience in cardiovascular medicine</p>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <span class="text-yellow-400">★★★★★</span>
                            <span class="text-sm text-gray-600 ml-1">4.9</span>
                        </div>
                        <button class="text-blue-500 text-sm font-medium hover:text-blue-600">Book Now</button>
                    </div>
                </div>
            </div>
            
            <!-- Doctor 2 -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                <img src="https://images.unsplash.com/photo-1594824797147-5cd12de8a5cd?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" 
                     alt="Dr. Sarah Lee" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">Dr. Sarah Lee</h3>
                    <p class="text-sm text-green-600 mb-2">Dermatologist</p>
                    <p class="text-xs text-gray-600 mb-3">Specialist in skin conditions and cosmetic dermatology</p>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <span class="text-yellow-400">★★★★★</span>
                            <span class="text-sm text-gray-600 ml-1">4.8</span>
                        </div>
                        <button class="text-blue-500 text-sm font-medium hover:text-blue-600">Book Now</button>
                    </div>
                </div>
            </div>
            
            <!-- Doctor 3 -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                <img src="https://images.unsplash.com/photo-1582750433449-648ed127bb54?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" 
                     alt="Dr. James Wilson" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">Dr. James Wilson</h3>
                    <p class="text-sm text-purple-600 mb-2">General Practitioner</p>
                    <p class="text-xs text-gray-600 mb-3">Primary care physician with holistic approach</p>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <span class="text-yellow-400">★★★★☆</span>
                            <span class="text-sm text-gray-600 ml-1">4.7</span>
                        </div>
                        <button class="text-blue-500 text-sm font-medium hover:text-blue-600">Book Now</button>
                    </div>
                </div>
            </div>
            
            <!-- Doctor 4 -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                <img src="https://images.unsplash.com/photo-1559839734-2b71ea197ec2?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" 
                     alt="Dr. Emily Davis" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">Dr. Emily Davis</h3>
                    <p class="text-sm text-yellow-600 mb-2">Pediatrician</p>
                    <p class="text-xs text-gray-600 mb-3">Specialized care for children and adolescents</p>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <span class="text-yellow-400">★★★★★</span>
                            <span class="text-sm text-gray-600 ml-1">4.9</span>
                        </div>
                        <button class="text-blue-500 text-sm font-medium hover:text-blue-600">Book Now</button>
                    </div>
                </div>
            </div>
            
            <!-- Doctor 5 -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                <img src="https://images.unsplash.com/photo-1622253692010-333f2da6031d?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" 
                     alt="Dr. David Chen" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">Dr. David Chen</h3>
                    <p class="text-sm text-indigo-600 mb-2">Psychiatrist</p>
                    <p class="text-xs text-gray-600 mb-3">Mental health and behavioral therapy specialist</p>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <span class="text-yellow-400">★★★★☆</span>
                            <span class="text-sm text-gray-600 ml-1">4.6</span>
                        </div>
                        <button class="text-blue-500 text-sm font-medium hover:text-blue-600">Book Now</button>
                    </div>
                </div>
            </div>
            
            <!-- Doctor 6 -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                <img src="https://images.unsplash.com/photo-1607990281513-2c110a25bd8c?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" 
                     alt="Dr. Lisa Brown" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">Dr. Lisa Brown</h3>
                    <p class="text-sm text-red-600 mb-2">Gynecologist</p>
                    <p class="text-xs text-gray-600 mb-3">Women's health and reproductive medicine expert</p>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <span class="text-yellow-400">★★★★★</span>
                            <span class="text-sm text-gray-600 ml-1">4.8</span>
                        </div>
                        <button class="text-blue-500 text-sm font-medium hover:text-blue-600">Book Now</button>
                    </div>
                </div>
            </div>
            
            <!-- Doctor 7 -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                <img src="https://images.unsplash.com/photo-1551601651-2a8555f1a136?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" 
                     alt="Dr. Robert Taylor" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">Dr. Robert Taylor</h3>
                    <p class="text-sm text-orange-600 mb-2">Orthopedic Surgeon</p>
                    <p class="text-xs text-gray-600 mb-3">Bone, joint, and musculoskeletal specialist</p>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <span class="text-yellow-400">★★★★☆</span>
                            <span class="text-sm text-gray-600 ml-1">4.7</span>
                        </div>
                        <button class="text-blue-500 text-sm font-medium hover:text-blue-600">Book Now</button>
                    </div>
                </div>
            </div>
            
            <!-- Doctor 8 -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                <img src="https://images.unsplash.com/photo-1582750433449-648ed127bb54?ixlib=rb-4.0.3&auto=format&fit=crop&w=400&q=80" 
                     alt="Dr. Maria Rodriguez" class="w-full h-48 object-cover">
                <div class="p-4">
                    <h3 class="text-lg font-semibold text-gray-900 mb-1">Dr. Maria Rodriguez</h3>
                    <p class="text-sm text-teal-600 mb-2">Neurologist</p>
                    <p class="text-xs text-gray-600 mb-3">Brain and nervous system disorders specialist</p>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <span class="text-yellow-400">★★★★★</span>
                            <span class="text-sm text-gray-600 ml-1">4.9</span>
                        </div>
                        <button class="text-blue-500 text-sm font-medium hover:text-blue-600">Book Now</button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="mt-10 text-center">
            <button class="bg-blue-500 text-white px-8 py-3 rounded-lg hover:bg-blue-600 transition-colors font-medium mr-4">
                View All Doctors
            </button>
            <button class="border border-blue-500 text-blue-500 px-8 py-3 rounded-lg hover:bg-blue-50 transition-colors font-medium">
                Search by Specialty
            </button>
        </div>
    </div>
</section>

<!-- Call to Action Section -->
<section class="py-16 bg-gradient-to-r from-blue-600 to-blue-700">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl sm:text-4xl font-bold text-white mb-6">
            Ready to Take Control of Your Health?
        </h2>
        <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">
            Join thousands of patients who trust DokBook for their healthcare needs. 
            Book your consultation today and experience quality care from home.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            <button class="bg-white text-blue-600 px-8 py-4 rounded-lg hover:bg-gray-50 transition-colors font-semibold text-lg shadow-lg">
                Book Appointment Now
            </button>
            <button class="border-2 border-white text-white px-8 py-4 rounded-lg hover:bg-white hover:text-blue-600 transition-colors font-semibold text-lg">
                Learn More
            </button>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mt-12 pt-8 border-t border-blue-500">
            <div class="text-center">
                <div class="text-3xl font-bold text-white">10,000+</div>
                <div class="text-blue-200 text-sm">Happy Patients</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-white">500+</div>
                <div class="text-blue-200 text-sm">Qualified Doctors</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-white">24/7</div>
                <div class="text-blue-200 text-sm">Support Available</div>
            </div>
        </div>
    </div>
</section>
    
    @endsection
</html>