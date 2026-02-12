@extends('components.admin-layout')

@section('title', 'Quick Access - Fingerprint Voting System')

@section('content')
    <h2 class="text-2xl font-bold mb-8">Quick Access</h2>

    <!-- Quick Access Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

        <!-- Manage Accounts -->
        @include('components.quick-access-card', [
            'title' => 'Manage Accounts',
            'desc' => 'Manage Roles',
            'icon_bg' => 'bg-blue-600',
            'route' => route('view.manage-accounts'),
            'icon_path' => '/icons/person.png'
        ])

        <!-- Fingerprint Enrollment -->
        @include('components.quick-access-card', [
            'title' => 'Fingerprint Enrollment',
            'desc' => 'Register student biometric data',
            'icon_bg' => 'bg-green-500',
            'route' => route('view.finger-print'),
            'icon_path' => '/icons/fingerprint.png'
        ])

        <!-- Voting Logs -->
        @include('components.quick-access-card', [
            'title' => 'Voting Logs',
            'desc' => 'View voting records',
            'icon_bg' => 'bg-yellow-400',
            'route' => '#',
            'icon_path' => '/icons/how_to_vote.png',
        ])

        <!-- Election Control -->
        @include('components.quick-access-card', [
            'title' => 'Election Control',
            'desc' => 'Configure election settings',
            'icon_bg' => 'bg-rose-500',
            'route' => '#',
            'icon_path'=>'/icons/settings.png'
        ])

        <!-- System Activity -->
        @include('components.quick-access-card', [
            'title' => 'System Activity',
            'desc' => 'Monitor real-time system logs',
            'icon_bg' => 'bg-green-500',
            'route' => '#',
            'icon_path' => '/icons/earthquake.png'
        ])

        <!-- Reports & Analytics -->
        @include('components.quick-access-card', [
            'title' => 'Reports & Analytics',
            'desc' => 'Generate system reports',
            'icon_bg' => 'bg-blue-600',
            'route' => '#',
            'icon_path'=>'/icons/chart_data.png'
        ])

    </div>
@endsection
