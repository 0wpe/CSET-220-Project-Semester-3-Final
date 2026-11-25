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
        min-width: 0;
        margin: 0;
        padding: clamp(22px, 3vw, 44px) clamp(18px, 3vw, 55px);
    }

    /* Header */

    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        margin-bottom: 24px;
        color: #176454;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
    }

    .back-link:hover {
        color: #124F43;
        text-decoration: underline;
    }

    .profile-heading {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        gap: 18px;
        flex-wrap: wrap;
    }

    .workspace-label {
        margin-bottom: 7px;
        color: #176454;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .8px;
    }

    .profile-title {
        margin: 0;
        color: #172E29;
        font-size: clamp(27px, 2.3vw, 35px);
        font-weight: 650;
        line-height: 1.2;
    }

    .profile-subtitle {
        margin: 8px 0 0;
        color: #687B74;
        font-size: 13px;
    }

    .admin-id-label {
        padding: 9px 13px;
        border: 1px solid #DFE7E2;
        border-radius: 9px;
        background: #FFFFFF;
        color: #687B74;
        font-size: 12px;
        font-weight: 600;
    }

    /* Messages */

    .success-message,
    .error-message {
        margin-top: 20px;
        padding: 13px 16px;
        border-radius: 10px;
        font-size: 13px;
        line-height: 1.5;
    }

    .success-message {
        background: #E7F0EB;
        border-left: 4px solid #176454;
        color: #176454;
    }

    .error-message {
        background: #FCE8E8;
        border-left: 4px solid #B42318;
        color: #B42318;
    }

    /* Profile overview */

    .employee-card {
        position: relative;
        overflow: hidden;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        margin-top: 27px;
        padding: 27px 30px;
        background: #FFFFFF;
        border: 1px solid #E3EAE7;
        border-radius: 18px;
        box-shadow: 0 3px 12px rgba(23, 46, 41, .035);
    }

    .employee-header {
        display: flex;
        align-items: center;
        gap: 18px;
        min-width: 0;
    }

    .profile-picture,
    .profile-placeholder {
        width: 82px;
        height: 82px;
        border-radius: 50%;
        flex-shrink: 0;
        background: #E7F0EB;
    }

    .profile-picture {
        object-fit: cover;
        border: 3px solid #E7F0EB;
    }

    .profile-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        color: #176454;
        font-size: 25px;
        font-weight: 700;
    }

    .employee-name {
        margin: 0;
        color: #172E29;
        font-size: clamp(21px, 2vw, 27px);
        font-weight: 650;
        line-height: 1.3;
        overflow-wrap: anywhere;
    }

    .employee-position {
        margin-top: 6px;
        color: #687B74;
        font-size: 13px;
    }

    .employee-card-footer {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .active-badge {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 7px 12px;
        background: #E7F0EB;
        border-radius: 20px;
        color: #176454;
        font-size: 11px;
        font-weight: 650;
        white-space: nowrap;
    }

    .active-badge::before {
        content: '';
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #176454;
    }

    .employment-type {
        color: #687B74;
        font-size: 12px;
        white-space: nowrap;
    }

    /* Edit profile */

    .profile-actions {
        display: flex;
        justify-content: flex-end;
        margin-top: 18px;
    }

    .edit-profile-toggle {
        min-width: 175px;
        padding: 12px 20px;
        border: 1px solid #176454;
        border-radius: 9px;
        background: #176454;
        color: #FFFFFF;
        font-family: inherit;
        font-size: 13px;
        font-weight: 650;
        cursor: pointer;
        transition: background .2s ease;
    }

    .edit-profile-toggle:hover {
        background: #124F43;
    }

    .edit-profile-form {
        margin-top: 17px;
        padding: 25px;
        background: #FFFFFF;
        border: 1px solid #E3EAE7;
        border-radius: 16px;
    }

    .edit-profile-form[hidden] {
        display: none;
    }

    .edit-form-heading {
        margin: 0 0 5px;
        font-size: 18px;
        font-weight: 650;
    }

    .edit-form-description {
        margin: 0 0 22px;
        color: #687B74;
        font-size: 12px;
    }

    .form-group {
        min-width: 0;
        margin-bottom: 17px;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }

    .form-group label {
        display: block;
        margin-bottom: 7px;
        color: #52665E;
        font-size: 12px;
        font-weight: 600;
    }

    .form-group input {
        width: 100%;
        min-height: 43px;
        padding: 10px 12px;
        border: 1px solid #DFE7E2;
        border-radius: 8px;
        background: #FFFFFF;
        font-family: inherit;
        font-size: 13px;
        color: #172E29;
    }

    .form-group input:focus {
        outline: none;
        border-color: #176454;
        box-shadow: 0 0 0 3px rgba(23, 100, 84, .09);
    }

    .form-group input[type="file"] {
        padding: 8px;
        background: #F6F8F7;
    }

    .current-picture {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 12px;
        color: #687B74;
        font-size: 12px;
    }

    .current-picture img {
        width: 48px;
        height: 48px;
        border: 2px solid #E7F0EB;
        border-radius: 50%;
        object-fit: cover;
    }

    .save-button {
        min-width: 170px;
        min-height: 44px;
        padding: 11px 20px;
        border: none;
        border-radius: 9px;
        background: #176454;
        color: #FFFFFF;
        font-family: inherit;
        font-size: 13px;
        font-weight: 650;
        cursor: pointer;
    }

    .save-button:hover {
        background: #124F43;
    }

    /* Information sections */

    .profile-details-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.15fr) minmax(0, .85fr);
        gap: 23px;
        align-items: stretch;
        margin-top: 30px;
    }

    .profile-section {
        min-width: 0;
    }

    .section-heading {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0 0 14px;
        color: #172E29;
        font-size: 17px;
        font-weight: 650;
    }

    .section-heading::before {
        content: '';
        width: 4px;
        height: 19px;
        flex-shrink: 0;
        border-radius: 4px;
        background: #176454;
    }

    .details-card,
    .attendance-card {
        height: calc(100% - 36px);
        min-height: 230px;
        padding: 24px;
        background: #FFFFFF;
        border: 1px solid #E3EAE7;
        border-radius: 16px;
    }

    .detail-label,
    .attendance-label {
        margin-bottom: 6px;
        color: #71817C;
        font-size: 11px;
        font-weight: 600;
    }

    .detail-value,
    .attendance-value {
        color: #172E29;
        font-size: 13px;
        font-weight: 550;
        overflow-wrap: anywhere;
    }

    .detail-divider {
        height: 1px;
        margin: 18px 0;
        background: #E3EAE7;
    }

    .detail-grid,
    .attendance-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
    }

    /* Account information */

    .attendance-card {
        background: #E7F0EB;
        border-color: #E7F0EB;
    }

    .account-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        margin-bottom: 22px;
        border-radius: 12px;
        background: #FFFFFF;
        color: #176454;
        font-size: 19px;
        font-weight: 700;
    }

    .leave-balance {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-top: 28px;
        padding-top: 18px;
        border-top: 1px solid rgba(23, 100, 84, .15);
        color: #52665E;
        font-size: 12px;
    }

    .leave-days {
        color: #176454;
        font-size: 12px;
        font-weight: 650;
        text-align: right;
    }

    /* Leave information */

    .upcoming-leave-card {
        min-height: 75px;
        padding: 17px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        background: #FFFFFF;
        border: 1px solid #E3EAE7;
        border-radius: 12px;
    }

    .upcoming-leave-date {
        color: #687B74;
        font-size: 12px;
        font-weight: 500;
    }

    .approved-badge {
        padding: 6px 11px;
        background: #E7F0EB;
        border-radius: 20px;
        color: #176454;
        font-size: 11px;
        font-weight: 600;
        white-space: nowrap;
    }

    /* Desktop layout */

    @media (min-width: 1200px) {
        .profile-details-grid {
            gap: 28px;
        }

        .details-card,
        .attendance-card {
            padding: 27px;
        }

        .profile-details-grid > .profile-section:last-child {
            grid-column: 1 / -1;
        }
    }

    /* Tablet and mobile */

    @media (max-width: 900px) {
        .profile-details-grid {
            grid-template-columns: minmax(0, 1fr);
            gap: 22px;
        }

        .details-card,
        .attendance-card {
            height: auto;
            min-height: 0;
        }

        .profile-details-grid > .profile-section:last-child {
            grid-column: auto;
        }
    }

    @media (max-width: 600px) {
        .profile-container {
            padding: 22px 17px 30px;
        }

        .back-link {
            margin-bottom: 20px;
        }

        .admin-id-label {
            display: none;
        }

        .employee-card {
            align-items: flex-start;
            flex-direction: column;
            padding: 20px;
            margin-top: 22px;
            border-radius: 15px;
        }

        .employee-header {
            gap: 13px;
        }

        .profile-picture,
        .profile-placeholder {
            width: 65px;
            height: 65px;
        }

        .profile-placeholder {
            font-size: 21px;
        }

        .employee-name {
            font-size: 20px;
        }

        .employee-position {
            font-size: 12px;
        }

        .employee-card-footer {
            width: 100%;
            justify-content: space-between;
            padding-top: 14px;
            border-top: 1px solid #E3EAE7;
        }

        .profile-actions {
            justify-content: stretch;
        }

        .edit-profile-toggle {
            width: 100%;
        }

        .edit-profile-form {
            padding: 18px;
        }

        .form-row {
            grid-template-columns: minmax(0, 1fr);
            gap: 0;
        }

        .save-button {
            width: 100%;
        }

        .profile-details-grid {
            margin-top: 25px;
        }

        .section-heading {
            font-size: 16px;
        }

        .details-card,
        .attendance-card {
            padding: 19px;
            border-radius: 14px;
        }

        .detail-grid,
        .attendance-grid {
            gap: 17px 12px;
        }

        .detail-value,
        .attendance-value {
            font-size: 12px;
        }

        .leave-balance {
            font-size: 11px;
        }
    }

    @media (max-width: 360px) {
        .profile-container {
            padding-left: 13px;
            padding-right: 13px;
        }

        .employee-name {
            font-size: 18px;
        }

        .employee-header {
            gap: 10px;
        }

        .detail-grid,
        .attendance-grid {
            grid-template-columns: minmax(0, 1fr);
        }

        .upcoming-leave-card {
            align-items: flex-start;
            flex-direction: column;
        }
    }
</style>

<main class="profile-container">

    <a href="{{ url('/admin/dashboard') }}" class="back-link">
        ← Back to dashboard
    </a>

    <header class="profile-heading">
        <div>
            <div class="workspace-label">ACCOUNT SETTINGS</div>

            <h1 class="profile-title">Admin profile</h1>

            <p class="profile-subtitle">
                Manage your administrator account and personal information.
            </p>
        </div>

        <div class="admin-id-label">
            ADMIN-{{ str_pad($admin->admin_id, 3, '0', STR_PAD_LEFT) }}
        </div>
    </header>

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

    <!-- Profile overview -->

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

    <!-- Edit profile button -->

    <div class="profile-actions">
        <button
            type="button"
            class="edit-profile-toggle"
            id="editProfileButton"
            onclick="toggleEditProfile()"
        >
            Edit Profile
        </button>
    </div>

    <!-- Edit profile form -->

    <section id="editProfileSection" hidden>

        <form
            action="{{ route('admin.profile.update') }}"
            method="POST"
            enctype="multipart/form-data"
            class="edit-profile-form"
        >

            @csrf
            @method('PUT')

            <h2 class="edit-form-heading">Edit profile information</h2>

            <p class="edit-form-description">
                Update your personal details and profile picture.
            </p>

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

    <!-- Profile information -->

    <div class="profile-details-grid">

        <section class="profile-section">

            <h2 class="section-heading">
                Contact & account information
            </h2>

            <div class="details-card">

                <div>
                    <div class="detail-label">Email address</div>

                    <div class="detail-value">
                        {{ $admin->user->email }}
                    </div>
                </div>

                <div class="detail-divider"></div>

                <div class="detail-grid">

                    <div>
                        <div class="detail-label">Phone number</div>

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

        <section class="profile-section">

            <h2 class="section-heading">
                Account information
            </h2>

            <div class="attendance-card">

                <div class="account-icon">
                    ✓
                </div>

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

        <section class="profile-section">

            <h2 class="section-heading">
                Leave information
            </h2>

            <div class="upcoming-leave-card">

                <span class="upcoming-leave-date">
                    No admin leave records available
                </span>

            </div>

        </section>

    </div>

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