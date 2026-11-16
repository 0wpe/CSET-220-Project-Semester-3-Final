
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

        @media (max-width: 480px) {
            .profile-page {
                width: calc(100% - 48px);
            }

            .employment-grid {
                gap: 18px 10px;
            }
        }
    </style>

    <main class="profile-page">
        <div class="eyebrow">MY ACCOUNT</div>

        <h1 class="page-title">Your profile</h1>
        <p class="page-subtitle">Your details, all in one place.</p>

        <section class="identity-card">
            <h2>Maya Thompson</h2>
            <p class="job-title">Product Designer</p>

            <div class="identity-bottom">
                <span class="status">Active employee</span>
                <span class="employee-id">EMP-024</span>
            </div>
        </section>

        <div class="section-heading">
            <h2>Personal information</h2>
            <a href="#" class="edit-link">Edit</a>
        </div>

        <section class="details-card">
            <div class="detail-row">
                <span class="detail-label">Work email</span>
                <span class="detail-value">maya.thompson@tempo.co</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Phone number</span>
                <span class="detail-value">+1 (415) 555-0182</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Location</span>
                <span class="detail-value">San Francisco, CA</span>
            </div>
        </section>

        <div class="section-heading">
            <h2>Employment</h2>
        </div>

        <section class="details-card">
            <div class="employment-grid">
                <div>
                    <span class="detail-label">Department</span>
                    <span class="detail-value">Design</span>
                </div>

                <div>
                    <span class="detail-label">Employment type</span>
                    <span class="detail-value">Full-time</span>
                </div>

                <div>
                    <span class="detail-label">Reporting to</span>
                    <span class="detail-value">Alex Morgan</span>
                </div>

                <div>
                    <span class="detail-label">Joined</span>
                    <span class="detail-value">March 12, 2023</span>
                </div>
            </div>
        </section>

        <section class="profile-help">
            <h3>Something changed?</h3>
            <p>Contact HR to update employment details.</p>
        </section>
    </main>
@endsection
