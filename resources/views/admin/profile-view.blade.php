
@extends('layouts.app')

@section('title', 'Admin Profile')

@section('content')

@include('partials.navbar-admin', ['activePage' => 'profile'])

<style>
    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        background: #F6F8F7;
        font-family: 'Inter', Arial, sans-serif;
        color: #172E29;
    }

    .profile-container {
        width: 100%;
        max-width: 402px;
        margin: 0 auto;
        padding: 18px 24px 40px;
    }

    .back-link {
        display: inline-block;
        margin-bottom: 18px;
        color: #176454;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
    }

    .profile-title {
        margin: 0;
        font-size: 26px;
        font-weight: 600;
        line-height: 1.45;
        color: #172E29;
    }

    .profile-subtitle {
        margin: 3px 0 0;
        font-size: 12px;
        font-weight: 400;
        color: #687B74;
    }

    .employee-card {
        margin-top: 20px;
        padding: 17px 18px;
        min-height: 132px;
        background: #FFFFFF;
        border-radius: 18px;
    }

    .employee-header {
        display: flex;
        align-items: center;
        gap: 13px;
    }

    .profile-picture {
        width: 65px;
        height: 65px;
        border-radius: 50%;
        object-fit: cover;
        flex-shrink: 0;
        background: #E7F0EB;
    }

    .profile-placeholder {
        width: 65px;
        height: 65px;
        border-radius: 50%;
        background: #E7F0EB;
        color: #176454;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 21px;
        font-weight: 600;
        flex-shrink: 0;
    }

    .employee-name {
        margin: 0;
        font-size: 23px;
        font-weight: 600;
        line-height: 1.45;
        color: #172E29;
    }

    .employee-position {
        margin-top: 3px;
        font-size: 13px;
        font-weight: 400;
        color: #687B74;
    }

    .employee-card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 17px;
    }

    .active-badge {
        padding: 4px 10px;
        background: #E7F0EB;
        border-radius: 13px;
        color: #176454;
        font-size: 11px;
        font-weight: 500;
    }

    .employment-type {
        font-size: 11px;
        font-weight: 400;
        color: #687B74;
    }

    .section-heading {
        margin: 27px 0 12px;
        font-size: 17px;
        font-weight: 600;
        color: #172E29;
    }

    .details-card {
        padding: 15px 17px;
        background: #FFFFFF;
        border-radius: 16px;
    }

    .detail-label {
        margin-bottom: 5px;
        font-size: 11px;
        font-weight: 500;
        color: #687B74;
    }

    .detail-value {
        font-size: 13px;
        font-weight: 500;
        color: #172E29;
        overflow-wrap: anywhere;
    }

    .detail-divider {
        height: 1px;
        margin: 15px 0;
        background: #DFE7E2;
    }

    .detail-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .attendance-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 8px;
    }

    .attendance-card {
        padding: 17px;
        background: #E7F0EB;
        border-radius: 16px;
    }

    .attendance-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .attendance-label {
        margin-bottom: 5px;
        font-size: 11px;
        font-weight: 500;
        color: #687B74;
    }

    .attendance-value {
        font-size: 13px;
        font-weight: 500;
        color: #172E29;
    }

    .leave-balance {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 22px;
        font-size: 12px;
        font-weight: 400;
        color: #176454;
    }

    .leave-days {
        font-size: 14px;
        font-weight: 600;
    }

    .upcoming-leave-card {
        min-height: 63px;
        padding: 0 16px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #FFFFFF;
        border-radius: 12px;
    }

    .upcoming-leave-date {
        font-size: 12px;
        font-weight: 500;
        color: #172E29;
    }

    .approved-badge {
        padding: 4px 10px;
        background: #E7F0EB;
        border-radius: 13px;
        color: #176454;
        font-size: 11px;
        font-weight: 500;
    }

    .edit-profile-toggle {
        width: 100%;
        padding: 12px;
        margin-top: 20px;
        border: none;
        border-radius: 8px;
        background: #176454;
        color: #FFFFFF;
        font-family: inherit;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
    }

    .edit-profile-toggle:hover {
        background: #124D41;
    }

    .edit-profile-form {
        margin-top: 15px;
        padding: 17px;
        background: #FFFFFF;
        border-radius: 16px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .form-group label {
        display: block;
        margin-bottom: 6px;
        font-size: 12px;
        font-weight: 500;
        color: #687B74;
    }

    .form-group input {
        width: 100%;
        padding: 10px;
        border: 1px solid #DFE7E2;
        border-radius: 7px;
        font-family: inherit;
        font-size: 12px;
        color: #172E29;
    }

    .form-group input:focus {
        outline: none;
        border-color: #176454;
    }

    .current-picture {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 12px;
        font-size: 11px;
        color: #687B74;
    }

    .current-picture img {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        object-fit: cover;
    }

    .save-button {
        width: 100%;
        padding: 12px;
        border: none;
        border-radius: 8px;
        background: #176454;
        color: #FFFFFF;
        font-family: inherit;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
    }

    .save-button:hover {
        background: #124D41;
    }

    .success-message {
        margin-bottom: 15px;
        padding: 12px;
        background: #E7F0EB;
        border-radius: 8px;
        color: #176454;
        font-size: 12px;
    }

    .error-message {
        margin-bottom: 15px;
        padding: 12px;
        background: #FCE8E8;
        border-radius: 8px;
        color: #B42318;
        font-size: 12px;
    }

    @media (max-width: 360px) {
        .profile-container {
            padding-left: 16px;
            padding-right: 16px;
        }

        .profile-title {
            font-size: 24px;
        }

        .employee-name {
            font-size: 21px;
        }

        .attendance-row {
            align-items: flex-start;
            flex-direction: column;
        }
    }
</style>

<main class="profile-container">

    <a href="{{ url('/admin/dashboard') }}" class="back-link">
        ← Back to dashboard
    </a>

    <h1 class="profile-title">Admin profile</h1>

    <p class="profile-subtitle">
        Administrator account · ADMIN-{{ str_pad($admin->admin_id, 3, '0', STR_PAD_LEFT) }}
    </p>

    <section class="employee-card">

        <div class="employee-header">

            @if($admin->user->profile_picture)
                <img
                    src="{{ asset('storage/' . $admin->user->profile_picture) }}"
                    alt="Admin profile picture"
                    class="profile-picture"
                >
            @else
                <div class="profile-placeholder">
                    {{ strtoupper(substr($admin->user->first_name, 0, 1) . substr($admin->user->last_name, 0, 1)) }}
                </div>
            @endif

            <div>
                <h2 class="employee-name">
                    {{ $admin->user->first_name }}
                    {{ $admin->user->last_name }}
                </h2>

                <div class="employee-position">
                    Administrator
                </div>
            </div>

        </div>

        <div class="employee-card-footer">

            <span class="active-badge">
                Admin account
            </span>

            <span class="employment-type">
                Administrator
            </span>

        </div>

    </section>

    <button
        type="button"
        class="edit-profile-toggle"
        id="editProfileButton"
        onclick="toggleEditProfile()"
    >
        Edit Profile
    </button>

    <section id="editProfileSection" hidden>

        <h2 class="section-heading">
            Edit profile
        </h2>

        <form
            action="{{ route('admin.profile.update') }}"
            method="POST"
            enctype="multipart/form-data"
            class="edit-profile-form"
        >

            @csrf
            @method('PUT')

            @if(session('success'))
                <div class="success-message">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="error-message">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <div class="form-row">

                <div class="form-group">
                    <label for="first_name">First name</label>
                    <input
                        type="text"
                        id="first_name"
                        name="first_name"
                        value="{{ old('first_name', $admin->user->first_name) }}"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="last_name">Last name</label>
                    <input
                        type="text"
                        id="last_name"
                        name="last_name"
                        value="{{ old('last_name', $admin->user->last_name) }}"
                        required
                    >
                </div>

            </div>

            <div class="form-group">
                <label for="email">Email address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email', $admin->user->email) }}"
                    required
                >
            </div>

            <div class="form-group">
                <label for="phone_number">Phone number</label>
                <input
                    type="text"
                    id="phone_number"
                    name="phone_number"
                    value="{{ old('phone_number', $admin->user->phone_number) }}"
                >
            </div>

            <div class="form-group">
                <label for="location">Location</label>
                <input
                    type="text"
                    id="location"
                    name="location"
                    value="{{ old('location', $admin->user->location) }}"
                >
            </div>

            <div class="form-group">

                <label for="profile_picture">Profile picture</label>

                @if($admin->user->profile_picture)
                    <div class="current-picture">
                        <img
                            src="{{ asset('storage/' . $admin->user->profile_picture) }}"
                            alt="Current profile picture"
                        >
                        <span>Current profile picture</span>
                    </div>
                @endif

                <input
                    type="file"
                    id="profile_picture"
                    name="profile_picture"
                    accept="image/jpeg,image/png,image/jpg,image/webp"
                >

            </div>

            <button type="submit" class="save-button">
                Save Changes
            </button>

        </form>

    </section>

    <section>

        <h2 class="section-heading">
            Contact & account information
        </h2>

        <div class="details-card">

            <div>
                <div class="detail-label">Email</div>

                <div class="detail-value">
                    {{ $admin->user->email }}
                </div>
            </div>

            <div class="detail-divider"></div>

            <div class="detail-grid">

                <div>
                    <div class="detail-label">Phone</div>

                    <div class="detail-value">
                        {{ $admin->user->phone_number ?? 'Not provided' }}
                    </div>
                </div>

                <div>
                    <div class="detail-label">Location</div>

                    <div class="detail-value">
                        {{ $admin->user->location ?? 'Not provided' }}
                    </div>
                </div>

            </div>

            <div class="detail-divider"></div>

            <div class="detail-grid">

                <div>
                    <div class="detail-label">Joined</div>

                    <div class="detail-value">
                        {{ $admin->user->hire_date?->format('F j, Y') ?? 'Not provided' }}
                    </div>
                </div>

                <div>
                    <div class="detail-label">Account role</div>

                    <div class="detail-value">
                        {{ $admin->user->role }}
                    </div>
                </div>

            </div>

            <div class="detail-divider"></div>

            <div class="detail-grid">

                <div>
                    <div class="detail-label">Admin ID</div>

                    <div class="detail-value">
                        {{ $admin->admin_id }}
                    </div>
                </div>

                <div>
                    <div class="detail-label">User ID</div>

                    <div class="detail-value">
                        {{ $admin->user->user_id }}
                    </div>
                </div>

            </div>

        </div>

    </section>

    <section>

        <h2 class="section-heading">
            Account information
        </h2>

        <div class="attendance-card">

            <div class="attendance-grid">

                <div>
                    <div class="attendance-label">
                        Account type
                    </div>

                    <div class="attendance-value">
                        Administrator
                    </div>
                </div>

                <div>
                    <div class="attendance-label">
                        Account status
                    </div>

                    <div class="attendance-value">
                        Active
                    </div>
                </div>

            </div>

            <div class="leave-balance">

                <span>Employee leave tracking</span>

                <span class="leave-days">
                    Not applicable
                </span>

            </div>

        </div>

    </section>

    <section>

        <h2 class="section-heading">
            Leave information
        </h2>

        <div class="upcoming-leave-card">

            <span class="upcoming-leave-date">
                No admin leave records available
            </span>

        </div>

    </section>

</main>

<script>
    function toggleEditProfile() {
        const editSection = document.getElementById('editProfileSection');
        const editButton = document.getElementById('editProfileButton');

        editSection.hidden = !editSection.hidden;

        editButton.textContent = editSection.hidden
            ? 'Edit Profile'
            : 'Cancel Editing';
    }

    @if($errors->any())
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('editProfileSection').hidden = false;
            document.getElementById('editProfileButton').textContent = 'Cancel Editing';
        });
    @endif
</script>

@endsection
