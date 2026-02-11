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
            'icon_path' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'
        ])

        <!-- Fingerprint Enrollment -->
        @include('components.quick-access-card', [
            'title' => 'Fingerprint Enrollment',
            'desc' => 'Register student biometric data',
            'icon_bg' => 'bg-green-500',
            'route' => route('view.finger-print'),
            'icon_path' => 'M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A10.003 10.003 0 015.02 10.334m12.118-.813c2.012 2.344 3.137 5.396 3.082 8.592m-4.708-7.376a15.998 15.998 0 00-4.032-6.109m-.61 4.905a6.005 6.005 0 013.478 2.404'
        ])

        <!-- Voting Logs -->
        @include('components.quick-access-card', [
            'title' => 'Voting Logs',
            'desc' => 'View voting records',
            'icon_bg' => 'bg-yellow-400',
            'route' => '#',
            'icon_path' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01'
        ])

        <!-- Election Control -->
        @include('components.quick-access-card', [
            'title' => 'Election Control',
            'desc' => 'Configure election settings',
            'icon_bg' => 'bg-rose-500',
            'route' => '#',
            'icon_path' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z'
        ])

        <!-- System Activity -->
        @include('components.quick-access-card', [
            'title' => 'System Activity',
            'desc' => 'Monitor real-time system logs',
            'icon_bg' => 'bg-green-500',
            'route' => '#',
            'icon_path' => 'M13 10V3L4 14h7v7l9-11h-7z'
        ])

        <!-- Student Eligibility -->
        @include('components.quick-access-card', [
            'title' => 'Student Eligibility',
            'desc' => 'Track email verification status',
            'icon_bg' => 'bg-rose-500',
            'route' => '#',
            'icon_path' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'
        ])

        <!-- Reports & Analytics -->
        @include('components.quick-access-card', [
            'title' => 'Reports & Analytics',
            'desc' => 'Generate system reports',
            'icon_bg' => 'bg-blue-600',
            'route' => '#',
            'icon_path' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'
        ])

    </div>
@endsection
