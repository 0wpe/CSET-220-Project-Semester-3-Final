@extends('layouts.app')

@section('title', 'Your Profile | Tempo HR')

@section('content')

@include('partials.navbar', ['activePage' => 'profile'])

<style>
    .profile-page {
        width: 100%;
        min-width: 0;
        padding: clamp(22px, 2.5vw, 42px) clamp(18px, 3vw, 55px);
        color: #172E29;
        font-family: 'Inter', Arial, sans-serif;
    }

    .page-header {
        margin-bottom: 28px;
    }

    .eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        color: #176454;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1px;
        margin-bottom: 9px;
    }

    .eyebrow::before {
        content: '';
        width: 7px;
        height: 7px;
        background: #176454;
        border-radius: 50%;
    }

    .page-title {
        color: #172E29;
        font-size: clamp(27px, 2.5vw, 38px);
        font-weight: 700;
        letter-spacing: -1px;
        line-height: 1.2;
        margin: 0 0 7px;
    }

    .page-subtitle {
        color: #71817C;
        font-size: clamp(13px, 1vw, 15px);
        margin: 0;
    }

    .success-message {
        background: #E7F0EB;
        color: #176454;
        padding: 13px 16px;
        border: 1px solid #D5E8DC;
        border-radius: 11px;
        font-size: 13px;
        margin-bottom: 20px;
    }

    .profile-layout {
        display: grid;
        grid-template-columns: minmax(0, 1.15fr) minmax(280px, 0.85fr);
        gap: clamp(20px, 2.3vw, 32px);
        align-items: start;
    }

    .profile-main-column,
    .profile-side-column {
        min-width: 0;
    }

    /* Identity card */

    .identity-card {
        position: relative;
        overflow: hidden;
        background: linear-gradient(135deg, #176454 0%, #124F43 100%);
        border-radius: 20px;
        padding: clamp(22px, 2.5vw, 32px);
        color: #FFFFFF;
        box-shadow: 0 8px 22px rgba(23, 100, 84, 0.12);
    }

    .identity-card::after {
        content: '';
        position: absolute;
        width: 220px;
        height: 220px;
        top: -115px;
        right: -85px;
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 50%;
        box-shadow:
            0 0 0 28px rgba(255, 255, 255, 0.035),
            0 0 0 56px rgba(255, 255, 255, 0.025);
        pointer-events: none;
    }

    .identity-header {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        gap: 18px;
        min-width: 0;
    }

    .profile-picture,
    .profile-placeholder {
        width: clamp(68px, 6vw, 88px);
        height: clamp(68px, 6vw, 88px);
        border-radius: 50%;
        flex-shrink: 0;
    }

    .profile-picture {
        object-fit: cover;
        border: 3px solid rgba(255, 255, 255, 0.75);
        background: #E7F0EB;
    }

    .profile-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        background: #E7F0EB;
        color: #176454;
        font-size: 25px;
        font-weight: 700;
        border: 3px solid rgba(255, 255, 255, 0.75);
    }

    .identity-text {
        min-width: 0;
    }

    .identity-card h2 {
        color: #FFFFFF;
        font-size: clamp(20px, 2vw, 27px);
        font-weight: 700;
        letter-spacing: -0.5px;
        line-height: 1.25;
        overflow-wrap: anywhere;
        margin: 0 0 6px;
    }

    .job-title {
        color: #D4E7DF;
        font-size: 13px;
        margin: 0;
    }

    .identity-bottom {
        position: relative;
        z-index: 1;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 25px;
        padding-top: 18px;
        border-top: 1px solid rgba(255, 255, 255, 0.18);
    }

    .status {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: rgba(255, 255, 255, 0.14);
        color: #FFFFFF;
        border: 1px solid rgba(255, 255, 255, 0.18);
        border-radius: 20px;
        padding: 7px 12px;
        font-size: 11px;
        font-weight: 600;
    }

    .status::before {
        content: '';
        width: 7px;
        height: 7px;
        background: #8BE0AE;
        border-radius: 50%;
    }

    .employee-id {
        color: #D4E7DF;
        font-size: 11px;
        font-weight: 600;
    }

    /* Section headings */

    .section-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin: 27px 0 13px;
    }

    .section-heading h2 {
        color: #172E29;
        font-size: clamp(17px, 1.4vw, 20px);
        font-weight: 700;
        letter-spacing: -0.3px;
        margin: 0;
    }

    .edit-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 9px 14px;
        background: #E7F0EB;
        border: 1px solid #D5E8DC;
        border-radius: 9px;
        color: #176454;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        cursor: pointer;
        font-family: inherit;
        transition: background 0.2s;
    }

    .edit-link:hover {
        background: #D5E8DC;
    }

    /* Information cards */

    .details-card {
        background: #FFFFFF;
        border: 1px solid #E3EAE7;
        border-radius: 16px;
        padding: 0 clamp(16px, 2vw, 24px);
        box-shadow: 0 4px 16px rgba(23, 46, 41, 0.025);
    }

    .detail-row {
        display: grid;
        grid-template-columns: minmax(110px, 0.65fr) minmax(0, 1.35fr);
        align-items: center;
        gap: 15px;
        padding: 17px 0;
        border-bottom: 1px solid #E7EDE9;
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        display: block;
        color: #71817C;
        font-size: 11px;
        font-weight: 600;
    }

    .detail-value {
        display: block;
        color: #172E29;
        font-size: 13px;
        font-weight: 600;
        overflow-wrap: anywhere;
    }

    /* Employment information */

    .employment-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0 22px;
    }

    .employment-item {
        min-width: 0;
        padding: 17px 0;
        border-bottom: 1px solid #E7EDE9;
    }

    .employment-item:nth-last-child(-n+2) {
        border-bottom: none;
    }

    .employment-item .detail-label {
        margin-bottom: 7px;
    }

    /* Edit form */

    .edit-form {
        display: none;
        background: #FFFFFF;
        border: 1px solid #E3EAE7;
        border-radius: 16px;
        padding: clamp(18px, 2vw, 28px);
        margin-top: 14px;
        box-shadow: 0 4px 16px rgba(23, 46, 41, 0.035);
    }

    .edit-form.active {
        display: block;
    }

    .form-heading {
        margin-bottom: 22px;
    }

    .form-heading h3 {
        color: #172E29;
        font-size: 17px;
        font-weight: 700;
        margin: 0 0 5px;
    }

    .form-heading p {
        color: #71817C;
        font-size: 12px;
        margin: 0;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 0 20px;
    }

    .form-group {
        min-width: 0;
        margin-bottom: 18px;
    }

    .form-group label {
        display: block;
        color: #172E29;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .form-group input {
        width: 100%;
        min-height: 43px;
        padding: 10px 12px;
        background: #FFFFFF;
        border: 1px solid #DFE7E2;
        border-radius: 9px;
        font-family: inherit;
        font-size: 13px;
        color: #172E29;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-group input:focus {
        outline: none;
        border-color: #176454;
        box-shadow: 0 0 0 3px rgba(23, 100, 84, 0.09);
    }

    .form-group input[type="file"] {
        padding: 9px;
        background: #F8FAF9;
    }

    .form-help {
        color: #71817C;
        font-size: 11px;
        line-height: 1.5;
        margin: 7px 0 0;
    }

    .error-message {
        color: #B42318;
        font-size: 11px;
        margin-top: 6px;
    }

    .form-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        margin-top: 5px;
    }

    .save-button,
    .cancel-button {
        min-height: 42px;
        padding: 10px 18px;
        border-radius: 9px;
        font-family: inherit;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.2s;
    }

    .save-button {
        background: #176454;
        color: #FFFFFF;
        border: 1px solid #176454;
    }

    .save-button:hover {
        background: #124F43;
    }

    .cancel-button {
        background: #FFFFFF;
        color: #172E29;
        border: 1px solid #DFE7E2;
    }

    .cancel-button:hover {
        background: #F6F8F7;
    }

    /* Help card */

    .profile-help {
        position: relative;
        overflow: hidden;
        background: #E7F0EB;
        border: 1px solid #D5E8DC;
        border-radius: 15px;
        padding: 20px;
        margin-top: 22px;
    }

    .profile-help h3 {
        color: #176454;
        font-size: 14px;
        font-weight: 700;
        margin: 0 0 7px;
    }

    .profile-help p {
        color: #426B5D;
        font-size: 12px;
        line-height: 1.6;
        margin: 0;
    }

    /* Responsive layout */

    @media (max-width: 950px) {
        .profile-layout {
            grid-template-columns: minmax(0, 1fr);
        }

        .profile-side-column {
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            gap: 0;
        }

        .profile-help {
            margin-top: 20px;
        }
    }

    @media (max-width: 600px) {
        .profile-page {
            padding: 22px 16px 35px;
        }

        .page-header {
            margin-bottom: 22px;
        }

        .identity-card {
            padding: 21px;
            border-radius: 17px;
        }

        .identity-header {
            gap: 14px;
        }

        .identity-bottom {
            margin-top: 19px;
            padding-top: 15px;
        }

        .section-heading {
            margin-top: 23px;
        }

        .detail-row {
            grid-template-columns: 1fr;
            gap: 6px;
            padding: 14px 0;
        }

        .employment-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0 15px;
        }

        .employment-item {
            padding: 15px 0;
        }

        .edit-form {
            padding: 18px;
        }

        .form-grid {
            grid-template-columns: minmax(0, 1fr);
        }
    }

    @media (max-width: 380px) {
        .profile-page {
            padding-left: 13px;
            padding-right: 13px;
        }

        .identity-card {
            padding: 17px;
        }

        .profile-picture,
        .profile-placeholder {
            width: 62px;
            height: 62px;
        }

        .identity-card h2 {
            font-size: 18px;
        }

        .employee-id {
            font-size: 10px;
        }

        .employment-grid {
            grid-template-columns: minmax(0, 1fr);
        }

        .employment-item:nth-last-child(-n+2) {
            border-bottom: 1px solid #E7EDE9;
        }

        .employment-item:last-child {
            border-bottom: none;
        }
    }

    @media (min-width: 1500px) {
        .profile-layout {
            gap: 36px;
        }

        .identity-card {
            padding: 34px;
        }

        .details-card {
            padding-left: 28px;
            padding-right: 28px;
        }
    }
</style>

<main class="profile-page">

    <header class="page-header">

        <div class="eyebrow">
            MY ACCOUNT
        </div>

        <h1 class="page-title">
            Your profile
        </h1>

        <p class="page-subtitle">
            Your personal and employment information.
        </p>

    </header>

    @if(session('success'))
        <div class="success-message">
            {{ session('success') }}
        </div>
    @endif

    <div class="profile-layout">

        <div class="profile-main-column">

            <!-- Employee identity -->

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

                    <div class="identity-text">

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

                    <span class="status">
                        Active employee
                    </span>

                    <span class="employee-id">
                        Employee ID: {{ $employee->employee_id }}
                    </span>

                </div>

            </section>

            <!-- Personal information -->

            <div class="section-heading">

                <h2>Personal information</h2>

                <button
                    type="button"
                    class="edit-link"
                    id="editButton"
                >
                    Edit profile
                </button>

            </div>

            <section class="details-card">

                <div class="detail-row">

                    <span class="detail-label">
                        First name
                    </span>

                    <span class="detail-value">
                        {{ $employee->user->first_name }}
                    </span>

                </div>

                <div class="detail-row">

                    <span class="detail-label">
                        Last name
                    </span>

                    <span class="detail-value">
                        {{ $employee->user->last_name }}
                    </span>

                </div>

                <div class="detail-row">

                    <span class="detail-label">
                        Work email
                    </span>

                    <span class="detail-value">
                        {{ $employee->user->email }}
                    </span>

                </div>

                <div class="detail-row">

                    <span class="detail-label">
                        Phone number
                    </span>

                    <span class="detail-value">
                        {{ $employee->user->phone_number ?? 'Not provided' }}
                    </span>

                </div>

                <div class="detail-row">

                    <span class="detail-label">
                        Location
                    </span>

                    <span class="detail-value">
                        {{ $employee->user->location ?? 'Not provided' }}
                    </span>

                </div>

            </section>

            <!-- Edit profile form -->

            <form
                class="edit-form {{ $errors->any() ? 'active' : '' }}"
                id="editForm"
                method="POST"
                action="{{ route('employee.profile.update') }}"
                enctype="multipart/form-data"
            >

                @csrf
                @method('PUT')

                <div class="form-heading">

                    <h3>Edit personal information</h3>

                    <p>
                        Update your details and save your changes.
                    </p>

                </div>

                <div class="form-group">

                    <label for="profile_picture">
                        Profile picture
                    </label>

                    <input
                        type="file"
                        id="profile_picture"
                        name="profile_picture"
                        accept="image/jpeg,image/png,image/jpg,image/webp"
                    >

                    <p class="form-help">
                        Accepted formats: JPG, PNG, or WEBP. Maximum size: 2 MB.
                    </p>

                    @error('profile_picture')
                        <div class="error-message">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                <div class="form-grid">

                    <div class="form-group">

                        <label for="first_name">
                            First name
                        </label>

                        <input
                            type="text"
                            id="first_name"
                            name="first_name"
                            value="{{ old('first_name', $employee->user->first_name) }}"
                            required
                        >

                        @error('first_name')
                            <div class="error-message">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="form-group">

                        <label for="last_name">
                            Last name
                        </label>

                        <input
                            type="text"
                            id="last_name"
                            name="last_name"
                            value="{{ old('last_name', $employee->user->last_name) }}"
                            required
                        >

                        @error('last_name')
                            <div class="error-message">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="form-group">

                        <label for="email">
                            Work email
                        </label>

                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email', $employee->user->email) }}"
                            required
                        >

                        @error('email')
                            <div class="error-message">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="form-group">

                        <label for="phone_number">
                            Phone number
                        </label>

                        <input
                            type="text"
                            id="phone_number"
                            name="phone_number"
                            value="{{ old('phone_number', $employee->user->phone_number) }}"
                        >

                        @error('phone_number')
                            <div class="error-message">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                    <div class="form-group">

                        <label for="location">
                            Location
                        </label>

                        <input
                            type="text"
                            id="location"
                            name="location"
                            value="{{ old('location', $employee->user->location) }}"
                        >

                        @error('location')
                            <div class="error-message">
                                {{ $message }}
                            </div>
                        @enderror

                    </div>

                </div>

                <div class="form-actions">

                    <button type="submit" class="save-button">
                        Save changes
                    </button>

                    <button
                        type="button"
                        class="cancel-button"
                        id="cancelButton"
                    >
                        Cancel
                    </button>

                </div>

            </form>

        </div>

        <div class="profile-side-column">

            <!-- Employment information -->

            <div class="section-heading">
                <h2>Employment information</h2>
            </div>

            <section class="details-card">

                <div class="employment-grid">

                    <div class="employment-item">

                        <span class="detail-label">
                            Department
                        </span>

                        <span class="detail-value">
                            {{ $employee->department->department_name ?? 'Not assigned' }}
                        </span>

                    </div>

                    <div class="employment-item">

                        <span class="detail-label">
                            Pay type
                        </span>

                        <span class="detail-value">
                            {{ $employee->pay_type }}
                        </span>

                    </div>

                    <div class="employment-item">

                        <span class="detail-label">
                            Designation
                        </span>

                        <span class="detail-value">
                            {{ $employee->designation ?? 'Not assigned' }}
                        </span>

                    </div>

                    <div class="employment-item">

                        <span class="detail-label">
                            Employee ID
                        </span>

                        <span class="detail-value">
                            {{ $employee->employee_id }}
                        </span>

                    </div>

                    <div class="employment-item">

                        <span class="detail-label">
                            Joined
                        </span>

                        <span class="detail-value">
                            {{ $employee->user->hire_date?->format('F j, Y') ?? 'Not provided' }}
                        </span>

                    </div>

                </div>

            </section>

            <!-- HR help -->

            <section class="profile-help">

                <h3>Something changed?</h3>

                <p>
                    For changes to your department, designation, pay type,
                    or other employment details, please contact HR.
                </p>

            </section>

        </div>

    </div>

</main>

<script>
    const editButton = document.getElementById('editButton');
    const cancelButton = document.getElementById('cancelButton');
    const editForm = document.getElementById('editForm');

    editButton.addEventListener('click', function () {
        editForm.classList.add('active');
        editForm.scrollIntoView({
            behavior: 'smooth',
            block: 'nearest'
        });
    });

    cancelButton.addEventListener('click', function () {
        editForm.classList.remove('active');
    });
</script>

@endsection