
@extends('layouts.app')

@section('title', 'Your Profile')

@section('content')
    @include('partials.navbar', ['activePage' => 'profile'])

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #F6F8F7;
            font-family: 'Inter', sans-serif;
            color: #172E29;
        }

        .profile-page {
            width: min(100% - 48px, 900px);
            margin: 18px auto 40px;
        }

        .eyebrow {
            color: #176454;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: .3px;
            margin-bottom: 8px;
        }

        .page-title {
            font-size: 28px;
            font-weight: 600;
            margin: 0 0 4px;
        }

        .page-subtitle {
            color: #687B74;
            font-size: 13px;
            margin: 0;
        }

        .identity-card {
            background: #FFFFFF;
            border-radius: 20px;
            padding: 19px 20px;
            margin-top: 30px;
            min-height: 142px;
        }

        .identity-header {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .profile-picture {
            width: 76px;
            height: 76px;
            border-radius: 50%;
            object-fit: cover;
            background: #E7F0EB;
            flex-shrink: 0;
        }

        .profile-placeholder {
            width: 76px;
            height: 76px;
            border-radius: 50%;
            background: #E7F0EB;
            color: #176454;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            font-weight: 600;
            flex-shrink: 0;
        }

        .identity-card h2 {
            font-size: 23px;
            font-weight: 600;
            margin: 0 0 5px;
        }

        .job-title {
            color: #687B74;
            font-size: 14px;
            margin: 0;
        }

        .identity-bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-top: 19px;
        }

        .status {
            display: inline-flex;
            align-items: center;
            background: #E7F0EB;
            color: #176454;
            border-radius: 13px;
            padding: 5px 10px;
            font-size: 11px;
            font-weight: 500;
        }

        .employee-id {
            color: #687B74;
            font-size: 11px;
            font-weight: 500;
        }

        .section-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 27px 0 12px;
        }

        .section-heading h2 {
            font-size: 17px;
            font-weight: 600;
            margin: 0;
        }

        .edit-link {
            color: #176454;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            background: none;
            border: none;
            cursor: pointer;
            font-family: inherit;
        }

        .details-card {
            background: #FFFFFF;
            border-radius: 16px;
            padding: 0 18px;
        }

        .detail-row {
            padding: 14px 0;
            border-bottom: 1px solid #DFE7E2;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            display: block;
            color: #687B74;
            font-size: 11px;
            font-weight: 500;
            margin-bottom: 5px;
        }

        .detail-value {
            display: block;
            color: #172E29;
            font-size: 14px;
            font-weight: 500;
            overflow-wrap: anywhere;
        }

        .employment-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px 14px;
            padding: 16px 0;
        }

        .profile-help {
            background: #E7F0EB;
            border-radius: 12px;
            padding: 11px 16px;
            margin-top: 24px;
        }

        .profile-help h3 {
            color: #176454;
            font-size: 13px;
            font-weight: 600;
            margin: 0 0 5px;
        }

        .profile-help p {
            color: #176454;
            font-size: 11px;
            margin: 0;
        }

        .edit-form {
            display: none;
            background: #FFFFFF;
            border-radius: 16px;
            padding: 18px;
            margin-top: 12px;
        }

        .edit-form.active {
            display: block;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-size: 12px;
            font-weight: 500;
            margin-bottom: 7px;
        }

        .form-group input {
            width: 100%;
            padding: 11px 12px;
            border: 1px solid #DFE7E2;
            border-radius: 9px;
            font-family: inherit;
            font-size: 13px;
            color: #172E29;
        }

        .form-group input:focus {
            outline: none;
            border-color: #176454;
        }

        .form-group input[type="file"] {
            background: #FFFFFF;
        }

        .form-help {
            color: #687B74;
            font-size: 11px;
            margin-top: 6px;
        }

        .form-actions {
            display: flex;
            gap: 10px;
        }

        .save-button,
        .cancel-button {
            padding: 10px 18px;
            border-radius: 9px;
            font-family: inherit;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
        }

        .save-button {
            background: #176454;
            color: #FFFFFF;
            border: none;
        }

        .cancel-button {
            background: #FFFFFF;
            color: #172E29;
            border: 1px solid #DFE7E2;
        }

        .success-message {
            background: #E7F0EB;
            color: #176454;
            padding: 12px 15px;
            border-radius: 9px;
            font-size: 13px;
            margin-top: 20px;
        }

        .error-message {
            color: #B42318;
            font-size: 11px;
            margin-top: 5px;
        }

        @media (max-width: 480px) {
            .profile-page {
                width: calc(100% - 48px);
            }

            .employment-grid {
                gap: 18px 10px;
            }

            .identity-card h2 {
                font-size: 19px;
            }
        }
    </style>

    <main class="profile-page">

        <div class="eyebrow">MY ACCOUNT</div>

        <h1 class="page-title">Your profile</h1>
        <p class="page-subtitle">Your details, all in one place.</p>

        @if(session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif

        <!-- Employee Identity -->
        <section class="identity-card">

            <div class="identity-header">

                @if($employee->user->profile_picture)
                    <img
                        src="{{ asset('storage/' . $employee->user->profile_picture) }}"
                        alt="Profile picture"
                        class="profile-picture"
                    >
                @else
                    <div class="profile-placeholder">
                        {{ strtoupper(substr($employee->user->first_name, 0, 1) . substr($employee->user->last_name, 0, 1)) }}
                    </div>
                @endif

                <div>
                    <h2>
                        {{ $employee->user->first_name }}
                        {{ $employee->user->last_name }}
                    </h2>

                    <p class="job-title">
                        {{ $employee->designation ?? 'No designation assigned' }}
                    </p>
                </div>

            </div>

            <div class="identity-bottom">
                <span class="status">Active employee</span>

                <span class="employee-id">
                    Employee ID: {{ $employee->employee_id }}
                </span>
            </div>

        </section>

        <!-- Personal Information -->
        <div class="section-heading">
            <h2>Personal information</h2>

            <button type="button" class="edit-link" id="editButton">
                Edit
            </button>
        </div>

        <section class="details-card">

            <div class="detail-row">
                <span class="detail-label">First name</span>
                <span class="detail-value">
                    {{ $employee->user->first_name }}
                </span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Last name</span>
                <span class="detail-value">
                    {{ $employee->user->last_name }}
                </span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Work email</span>
                <span class="detail-value">
                    {{ $employee->user->email }}
                </span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Phone number</span>
                <span class="detail-value">
                    {{ $employee->user->phone_number ?? 'Not provided' }}
                </span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Location</span>
                <span class="detail-value">
                    {{ $employee->user->location ?? 'Not provided' }}
                </span>
            </div>

        </section>

        <!-- Edit Form -->
        <form
            class="edit-form {{ $errors->any() ? 'active' : '' }}"
            id="editForm"
            method="POST"
            action="{{ route('employee.profile.update') }}"
            enctype="multipart/form-data"
        >

            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="profile_picture">Profile picture</label>
                <input
                    type="file"
                    id="profile_picture"
                    name="profile_picture"
                    accept="image/jpeg,image/png,image/jpg,image/webp"
                >
                <p class="form-help">Accepted formats: JPG, PNG, or WEBP. Maximum size: 2 MB.</p>

                @error('profile_picture')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="first_name">First name</label>
                <input
                    type="text"
                    id="first_name"
                    name="first_name"
                    value="{{ old('first_name', $employee->user->first_name) }}"
                    required
                >

                @error('first_name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="last_name">Last name</label>
                <input
                    type="text"
                    id="last_name"
                    name="last_name"
                    value="{{ old('last_name', $employee->user->last_name) }}"
                    required
                >

                @error('last_name')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Work email</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    value="{{ old('email', $employee->user->email) }}"
                    required
                >

                @error('email')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="phone_number">Phone number</label>
                <input
                    type="text"
                    id="phone_number"
                    name="phone_number"
                    value="{{ old('phone_number', $employee->user->phone_number) }}"
                >

                @error('phone_number')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="location">Location</label>
                <input
                    type="text"
                    id="location"
                    name="location"
                    value="{{ old('location', $employee->user->location) }}"
                >

                @error('location')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="save-button">
                    Save changes
                </button>

                <button type="button" class="cancel-button" id="cancelButton">
                    Cancel
                </button>
            </div>

        </form>

        <!-- Employment Information -->
        <div class="section-heading">
            <h2>Employment</h2>
        </div>

        <section class="details-card">

            <div class="employment-grid">

                <div>
                    <span class="detail-label">Department</span>
                    <span class="detail-value">
                        {{ $employee->department->department_name ?? 'Not assigned' }}
                    </span>
                </div>

                <div>
                    <span class="detail-label">Pay type</span>
                    <span class="detail-value">
                        {{ $employee->pay_type }}
                    </span>
                </div>

                <div>
                    <span class="detail-label">Designation</span>
                    <span class="detail-value">
                        {{ $employee->designation ?? 'Not assigned' }}
                    </span>
                </div>

                <div>
                    <span class="detail-label">Employee ID</span>
                    <span class="detail-value">
                        {{ $employee->employee_id }}
                    </span>
                </div>

                <div>
                    <span class="detail-label">Joined</span>
                    <span class="detail-value">
                        {{ $employee->user->hire_date?->format('F j, Y') ?? 'Not provided' }}
                    </span>
                </div>

            </div>

        </section>

        <section class="profile-help">
            <h3>Something changed?</h3>
            <p>Contact HR to update employment details.</p>
        </section>

    </main>

    <script>
        const editButton = document.getElementById('editButton');
        const cancelButton = document.getElementById('cancelButton');
        const editForm = document.getElementById('editForm');

        editButton.addEventListener('click', function () {
            editForm.classList.add('active');
        });

        cancelButton.addEventListener('click', function () {
            editForm.classList.remove('active');
        });
    </script>

@endsection
