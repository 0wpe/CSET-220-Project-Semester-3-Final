@extends('layouts.app')

@section('title', 'Leave Request')

@section('content')

@include('partials.navbar', ['activePage' => 'leave'])

<style>
    .leave-page {
        width: 100%;
        min-width: 0;
        padding: clamp(20px, 2.5vw, 44px) clamp(16px, 3vw, 55px);
        font-family: 'Inter', Arial, sans-serif;
        color: #172E29;
    }

    /* Page heading */

    .page-header {
        margin-bottom: clamp(24px, 2.5vw, 34px);
    }

    .eyebrow {
        color: #176454;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1.1px;
        margin-bottom: 8px;
    }

    .page-title {
        font-size: clamp(26px, 2.4vw, 38px);
        font-weight: 700;
        letter-spacing: -1.1px;
        line-height: 1.2;
        margin: 0;
    }

    .page-subtitle {
        color: #71817C;
        font-size: clamp(13px, 0.9vw, 15px);
        margin: 8px 0 0;
    }

    /* Alerts */

    .alert {
        padding: 14px 17px;
        border-radius: 12px;
        margin-bottom: 18px;
        font-size: 13px;
        line-height: 1.5;
    }

    .alert-success {
        background: #EAF4ED;
        color: #176454;
        border: 1px solid #C9E1D1;
    }

    .alert-danger {
        background: #FCEEEE;
        color: #A83232;
        border: 1px solid #F0CCCC;
    }

    .alert ul {
        margin: 0;
        padding-left: 20px;
        text-align: left;
    }

    /* Main layout */

    .leave-content-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr);
        gap: 25px;
        align-items: start;
    }

    .leave-form-section,
    .leave-history-section {
        min-width: 0;
    }

    .leave-form-section {
        display: flex;
        flex-direction: column;
        gap: 22px;
    }

    /* Leave balance */

    .balance-card {
        position: relative;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        min-height: 135px;
        padding: clamp(20px, 2vw, 28px);
        overflow: hidden;
        background: linear-gradient(120deg, #176454 0%, #124F43 100%);
        border-radius: 18px;
        color: #FFFFFF;
        box-shadow: 0 10px 25px rgba(23, 100, 84, 0.10);
    }

    .balance-card::after {
        content: "";
        position: absolute;
        width: 190px;
        height: 190px;
        right: -65px;
        top: -100px;
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 50%;
        box-shadow:
            0 0 0 24px rgba(255, 255, 255, 0.025),
            0 0 0 48px rgba(255, 255, 255, 0.02);
        pointer-events: none;
    }

    .balance-information,
    .balance-renewal {
        position: relative;
        z-index: 1;
    }

    .balance-label {
        color: #D4E7DF;
        font-size: 12px;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .balance-number {
        font-size: clamp(30px, 2.5vw, 40px);
        font-weight: 700;
        letter-spacing: -1px;
        line-height: 1.1;
    }

    .balance-caption {
        color: #C4DAD1;
        font-size: 11px;
        margin-top: 7px;
    }

    .balance-renewal {
        align-self: flex-end;
        color: #D4E7DF;
        font-size: 11px;
        text-align: right;
        white-space: nowrap;
    }

    /* Form panel */

    .leave-form-panel {
        padding: clamp(18px, 2vw, 28px);
        background: #FFFFFF;
        border: 1px solid #E5ECE8;
        border-radius: 17px;
        box-shadow: 0 4px 15px rgba(23, 46, 41, 0.025);
    }

    .form-panel-heading {
        margin-bottom: 23px;
        padding-bottom: 17px;
        border-bottom: 1px solid #EAF0EC;
    }

    .form-panel-heading h2 {
        font-size: 17px;
        font-weight: 700;
        letter-spacing: -0.3px;
        margin: 0;
    }

    .form-panel-heading p {
        color: #82908B;
        font-size: 11px;
        margin: 6px 0 0;
    }

    .leave-form {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
        min-width: 0;
    }

    .form-label {
        color: #263C35;
        font-size: 12px;
        font-weight: 700;
    }

    .form-control {
        width: 100%;
        min-width: 0;
        min-height: 48px;
        padding: 0 13px;
        background: #FAFCFB;
        border: 1px solid #DFE7E2;
        border-radius: 10px;
        color: #172E29;
        font-family: inherit;
        font-size: 13px;
        outline: none;
        transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
    }

    .form-control:focus,
    .reason-input:focus {
        background: #FFFFFF;
        border-color: #176454;
        box-shadow: 0 0 0 3px rgba(23, 100, 84, 0.09);
    }

    .date-fields {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }

    .date-fields .form-group {
        min-width: 0;
    }

    .date-fields input {
        min-width: 0;
    }

    .days-info {
        display: inline-flex;
        align-items: center;
        align-self: flex-start;
        gap: 7px;
        padding: 7px 11px;
        background: #EAF2EE;
        border: 1px solid #DCE9E2;
        border-radius: 9px;
        color: #176454;
        font-size: 11px;
        font-weight: 600;
    }

    .days-info::before {
        content: "✓";
        font-size: 12px;
        font-weight: 700;
    }

    .reason-input {
        width: 100%;
        min-height: 125px;
        padding: 13px 14px;
        resize: vertical;
        background: #FAFCFB;
        border: 1px solid #DFE7E2;
        border-radius: 10px;
        color: #172E29;
        font-family: inherit;
        font-size: 13px;
        line-height: 1.6;
        outline: none;
        transition: border-color 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
    }

    .reason-input::placeholder {
        color: #9AA7A1;
    }

    .reason-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: #82908B;
        font-size: 10px;
        margin-top: -3px;
    }

    .approval-note {
        display: flex;
        align-items: flex-start;
        gap: 9px;
        padding: 12px 13px;
        background: #F4F8F5;
        border: 1px solid #E7EFEA;
        border-radius: 10px;
        color: #71817C;
        font-size: 11px;
        line-height: 1.5;
        margin: 0;
    }

    .approval-note::before {
        content: "i";
        display: flex;
        justify-content: center;
        align-items: center;
        width: 17px;
        height: 17px;
        flex-shrink: 0;
        border-radius: 50%;
        background: #E0EEE6;
        color: #176454;
        font-size: 11px;
        font-weight: 700;
    }

    .submit-button {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 10px;
        width: 100%;
        min-height: 50px;
        padding: 0 18px;
        background: #176454;
        border: 1px solid #176454;
        border-radius: 10px;
        color: #FFFFFF;
        font-family: inherit;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: background 0.2s ease, transform 0.2s ease;
    }

    .submit-button:hover {
        background: #124F43;
        transform: translateY(-1px);
    }

    /* Recent requests */

    .leave-history-panel {
        padding: clamp(18px, 2vw, 25px);
        background: #FFFFFF;
        border: 1px solid #E5ECE8;
        border-radius: 17px;
        box-shadow: 0 4px 15px rgba(23, 46, 41, 0.025);
    }

    .recent-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin-bottom: 18px;
    }

    .recent-heading h2 {
        font-size: 17px;
        font-weight: 700;
        letter-spacing: -0.3px;
        margin: 0;
    }

    .view-all {
        color: #176454;
        font-size: 11px;
        font-weight: 700;
        text-decoration: none;
        white-space: nowrap;
    }

    .view-all:hover {
        text-decoration: underline;
    }

    .request-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .request-card {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        min-width: 0;
        min-height: 82px;
        padding: 14px;
        background: #F8FAF9;
        border: 1px solid #EAF0EC;
        border-radius: 12px;
    }

    .request-information {
        min-width: 0;
    }

    .request-title {
        color: #172E29;
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 6px;
        overflow-wrap: anywhere;
    }

    .request-date {
        color: #82908B;
        font-size: 11px;
        line-height: 1.5;
    }

    .request-status {
        flex-shrink: 0;
        padding: 6px 10px;
        background: #E4F3E9;
        border-radius: 20px;
        color: #24734D;
        font-size: 10px;
        font-weight: 700;
        text-align: center;
    }

    .history-footer {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 17px;
        padding-top: 15px;
        border-top: 1px solid #EAF0EC;
        color: #82908B;
        font-size: 10px;
    }

    .history-footer-mark {
        color: #176454;
        font-size: 13px;
        font-weight: 700;
    }

    /* Empty state */

    .empty-requests {
        padding: 30px 18px;
        background: #F8FAF9;
        border: 1px dashed #DCE7E0;
        border-radius: 12px;
        text-align: center;
    }

    .empty-requests-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        margin: 0 auto 12px;
        background: #EAF2EE;
        border-radius: 12px;
        color: #176454;
        font-size: 20px;
    }

    .empty-requests-title {
        color: #263C35;
        font-size: 13px;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .empty-requests-text {
        color: #82908B;
        font-size: 11px;
        line-height: 1.5;
    }

    /* Small phones */

    @media (max-width: 420px) {
        .leave-page {
            padding-left: 16px;
            padding-right: 16px;
        }

        .balance-card {
            align-items: flex-start;
            flex-direction: column;
            gap: 13px;
        }

        .balance-renewal {
            align-self: flex-start;
            text-align: left;
        }

        .date-fields {
            gap: 9px;
        }

        .form-control {
            padding-left: 9px;
            padding-right: 9px;
            font-size: 12px;
        }

        .request-card {
            align-items: flex-start;
        }

        .request-status {
            font-size: 9px;
            padding: 5px 8px;
        }
    }

    /* Tablet */

    @media (min-width: 768px) {
        .leave-content-grid {
            gap: 22px;
        }

        .leave-form-section {
            gap: 24px;
        }

        .balance-card {
            min-height: 145px;
        }

        .form-control {
            min-height: 51px;
            font-size: 14px;
        }

        .reason-input {
            min-height: 145px;
            font-size: 14px;
        }

        .submit-button {
            min-height: 54px;
            font-size: 14px;
        }

        .request-card {
            min-height: 90px;
            padding: 16px;
        }

        .request-title {
            font-size: 14px;
        }

        .request-date {
            font-size: 12px;
        }
    }

    /* Desktop */

    @media (min-width: 1100px) {
        .leave-content-grid {
            grid-template-columns: minmax(0, 1.15fr) minmax(320px, 0.85fr);
            gap: 26px;
        }

        .leave-form-section {
            gap: 22px;
        }

        .leave-history-panel {
            position: sticky;
            top: 24px;
        }

        .balance-card {
            min-height: 155px;
        }

        .leave-form-panel {
            padding: 26px;
        }

        .leave-form {
            gap: 21px;
        }

        .date-fields {
            gap: 16px;
        }

        .reason-input {
            min-height: 155px;
        }

        .submit-button {
            max-width: 260px;
        }

        .request-card {
            min-height: 95px;
            padding: 16px;
        }
    }

    /* Wide desktop */

    @media (min-width: 1500px) {
        .leave-content-grid {
            grid-template-columns: minmax(0, 1.2fr) minmax(360px, 0.8fr);
            gap: 34px;
        }

        .leave-form-panel,
        .leave-history-panel {
            padding: 29px;
        }

        .balance-card {
            min-height: 165px;
            padding: 30px;
        }

        .leave-form {
            gap: 23px;
        }

        .reason-input {
            min-height: 170px;
        }
    }
</style>

<main class="leave-page">

    {{-- Success Alert --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="text-align: center">
            {{ session('success') }}
        </div>
    @endif

    {{-- Specific 'error' Key or General Validation Errors --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="text-align: center">
            @if ($errors->has('error'))
                {{ $errors->first('error') }}
            @else
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif
        </div>
    @endif

    <header class="page-header">
        <div class="eyebrow">TIME TO RECHARGE</div>

        <h1 class="page-title">Leave request</h1>

        <p class="page-subtitle">
            Make room for life outside work.
        </p>
    </header>

    <div class="leave-content-grid">

        <div class="leave-form-section">

            <!-- Annual leave balance -->

            <section class="balance-card">

                <div class="balance-information">
                    <div class="balance-label">
                        Annual leave available
                    </div>

                    <div class="balance-number">
                        12 days
                    </div>

                    <div class="balance-caption">
                        Your current annual leave balance
                    </div>
                </div>

                <div class="balance-renewal">
                    Renews Jan 1, 2027
                </div>

            </section>

            <!-- Leave request form -->

            <section class="leave-form-panel">

                <div class="form-panel-heading">
                    <h2>Request time off</h2>

                    <p>
                        Complete the details below to send your request for approval.
                    </p>
                </div>

                <form class="leave-form" method="POST" action="{{ url('employee/leave-requests') }}">
                    @csrf

                    <div class="form-group">

                        <label class="form-label" for="leave-type">
                            Leave type
                        </label>

                        <select class="form-control" id="leave-type" name="leave_type">
                            <option value="Annual Leave" {{ old('leave_type') === 'Annual Leave' ? 'selected' : '' }}>Annual leave</option>
                            <option value="Personal Leave" {{ old('leave_type') === 'Personal Leave' ? 'selected' : '' }}>Personal leave</option>
                            <option value="Sick Leave" {{ old('leave_type') === 'Sick Leave' ? 'selected' : '' }}>Sick leave</option>
                            <option value="Unpaid Leave" {{ old('leave_type') === 'Unpaid Leave' ? 'selected' : '' }}>Unpaid leave</option>
                        </select>

                    </div>

                    <div class="date-fields">

                        <div class="form-group">

                            <label class="form-label" for="start-date">
                                Start date
                            </label>

                            <input
                                class="form-control"
                                type="date"
                                id="start-date"
                                name="start_date"
                                value="{{ old('start_date', '2026-11-23') }}"
                            >

                        </div>

                        <div class="form-group">

                            <label class="form-label" for="end-date">
                                End date
                            </label>

                            <input
                                class="form-control"
                                type="date"
                                id="end-date"
                                name="end_date"
                                value="{{ old('end_date', '2026-11-24') }}"
                            >

                        </div>

                    </div>

                    <div class="days-info">
                        2 working days · 10 days remaining
                    </div>

                    <div class="form-group">

                        <label class="form-label" for="leave-reason">
                            Reason for leave
                        </label>

                        <textarea
                            class="reason-input"
                            id="leave-reason"
                            name="reason"
                            maxlength="500"
                            placeholder="Enter your reason for requesting leave..."
                        >{{ old('reason', 'Taking a short break to spend time with family.') }}</textarea>

                        <div class="reason-footer">
                            <span>Maximum 500 characters</span>
                            <span id="character-count">0 / 500</span>
                        </div>

                    </div>

                    <p class="approval-note">
                        Sent to Alex Morgan for approval.
                    </p>

                    <button type="submit" class="submit-button">
                        Submit request <span aria-hidden="true">→</span>
                    </button>

                </form>

            </section>

        </div>

        <!-- Recent requests -->

        <section class="leave-history-section">

            <div class="leave-history-panel">

                <div class="recent-heading">

                    <h2>Recent requests</h2>

                    <a href="#" class="view-all">
                        View all →
                    </a>

                </div>

                <div class="request-list">

                    @forelse ($recentRequests ?? collect() as $leaveRequest)
                        @php
                            $days = $leaveRequest->start_date->diffInDays($leaveRequest->end_date) + 1;
                        @endphp

                        <div class="request-card">

                            <div class="request-information">

                                <div class="request-title">
                                    {{ $leaveRequest->leave_type }}
                                </div>

                                <div class="request-date">
                                    {{ $leaveRequest->start_date->format('M j, Y') }} · {{ $days }} {{ \Illuminate\Support\Str::plural('day', $days) }}
                                </div>

                            </div>

                            <span class="request-status">
                                {{ $leaveRequest->status }}
                            </span>

                        </div>

                    @empty

                        <div class="empty-requests">

                            <div class="empty-requests-icon" aria-hidden="true">
                                ◷
                            </div>

                            <div class="empty-requests-title">
                                No leave requests yet
                            </div>

                            <div class="empty-requests-text">
                                Submitted requests will appear here.
                            </div>

                        </div>

                    @endforelse

                </div>

                <div class="history-footer">
                    <span class="history-footer-mark">✓</span>
                    Your submitted requests are listed here.
                </div>

            </div>

        </section>

    </div>

</main>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const reasonInput = document.getElementById('leave-reason');
        const characterCount = document.getElementById('character-count');

        if (!reasonInput || !characterCount) {
            return;
        }

        function updateCharacterCount() {
            characterCount.textContent = `${reasonInput.value.length} / 500`;
        }

        updateCharacterCount();

        reasonInput.addEventListener('input', updateCharacterCount);
    });
</script>

@endsection