@extends('admin.admin-dashboard')

@section('content')
<div class="container mx-auto px-6 py-6">
    <h2 class="text-2xl font-bold mb-6">Doctors Information</h2>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto bg-white shadow-md rounded-lg">
        <table class="min-w-full text-sm text-left text-gray-600">
            <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3">#</th>
                    <th class="px-6 py-3">Name</th>
                    <th class="px-6 py-3">Phone</th>
                    <th class="px-6 py-3">License No.</th>
                    <th class="px-6 py-3">Address</th>
                    <th class="px-6 py-3">Years Exp.</th>
                    <th class="px-6 py-3">Medical School</th>
                    <th class="px-6 py-3">Hospital</th>
                    <th class="px-6 py-3">Specializations</th>
                    <th class="px-6 py-3">Created</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($doctors as $index => $doctor)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3">{{ $index + 1 }}</td>
                        <td class="px-6 py-3">{{ $doctor->first_name }} {{ $doctor->last_name }}</td>
                        <td class="px-6 py-3">{{ $doctor->phone }}</td>
                        <td class="px-6 py-3">{{ $doctor->license_number }}</td>
                        <td class="px-6 py-3">
                            {{ $doctor->street_address }}, {{ $doctor->city }}, {{ $doctor->state }} {{ $doctor->postal_code }}, {{ $doctor->country }}
                        </td>
                        <td class="px-6 py-3">{{ $doctor->years_experience ?? '-' }}</td>
                        <td class="px-6 py-3">{{ $doctor->medical_school ?? '-' }}</td>
                        <td class="px-6 py-3">{{ $doctor->hospital_affiliation ?? '-' }}</td>
                        <td class="px-6 py-3">
                            @if(is_array($doctor->specializations))
                                {{ implode(', ', $doctor->specializations) }}
                            @else
                                {{ $doctor->specializations }}
                            @endif
                        </td>
                        <td class="px-6 py-3">{{ $doctor->created_at->format('Y-m-d') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="px-6 py-4 text-center text-gray-500">No doctors found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
