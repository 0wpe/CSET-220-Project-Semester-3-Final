
@extends('layouts.app')

@section('title', 'Leave Request')

@section('content')

@include('partials.navbar', ['activePage' => 'leave'])

<style>
    * {
        box-sizing: border-box;
    }

    .leave-page {
        width: 100%;
        max-width: 1100px;
        margin: 0 auto;
        padding: 18px 24px 30px;
        font-family: 'Inter', sans-serif;
        color: #172E29;
    }

    .eyebrow {
        color: #176454;
        font-size: 11px;
        font-weight: 600;
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
        margin: 0 0 20px;
    }

    /* Leave balance */

    .balance-card {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        min-height: 83px;
        padding: 15px 16px;
        background: #E7F0EB;
        border-radius: 14px;
        margin-bottom: 24px;
    }

    .balance-label {
        color: #176454;
        font-size: 12px;
        font-weight: 500;
        margin-bottom: 2px;
    }

    .balance-number {
        color: #176454;
        font-size: 25px;
        font-weight: 600;
    }

    .balance-renewal {
        color: #176454;
        font-size: 11px;
        white-space: nowrap;
        align-self: flex-end;
        padding-bottom: 7px;
    }

    /* Form */

    .leave-form {
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .form-label {
        color: #172E29;
        font-size: 12px;
        font-weight: 500;
    }

    .form-control {
        width: 100%;
        min-height: 49px;
        padding: 0 13px;
        background: #FFFFFF;
        border: 1px solid #DFE7E2;
        border-radius: 10px;
        color: #172E29;
        font-family: inherit;
        font-size: 13px;
        outline: none;
    }

    .form-control:focus {
        border-color: #176454;
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
        align-self: flex-start;
        align-items: center;
        padding: 5px 10px;
        background: #E7F0EB;
        border-radius: 13px;
        color: #176454;
        font-size: 11px;
        font-weight: 500;
    }

    .reason-input {
        width: 100%;
        min-height: 112px;
        padding: 14px;
        resize: vertical;
        background: #FFFFFF;
        border: 1px solid #DFE7E2;
        border-radius: 10px;
        color: #172E29;
        font-family: inherit;
        font-size: 14px;
        outline: none;
    }

    .reason-input:focus {
        border-color: #176454;
    }

    .reason-footer {
        display: flex;
        justify-content: flex-end;
        color: #687B74;
        font-size: 10px;
        margin-top: -10px;
    }

    .approval-note {
        color: #687B74;
        font-size: 12px;
        margin: 0;
    }

    .submit-button {
        width: 100%;
        min-height: 50px;
        background: #176454;
        border: none;
        border-radius: 11px;
        color: #FFFFFF;
        font-family: inherit;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
    }

    .submit-button:hover {
        background: #125447;
    }

    /* Recent requests */

    .recent-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        margin: 30px 0 12px;
    }

    .recent-heading h2 {
        font-size: 17px;
        font-weight: 600;
        margin: 0;
    }

    .view-all {
        color: #176454;
        font-size: 12px;
        font-weight: 600;
        text-decoration: none;
    }

    .request-card {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        min-height: 83px;
        padding: 13px 16px;
        background: #FFFFFF;
        border-radius: 16px;
    }

    .request-title {
        color: #172E29;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 6px;
    }

    .request-date {
        color: #687B74;
        font-size: 12px;
    }

    .request-status {
        flex-shrink: 0;
        padding: 5px 10px;
        background: #E7F0EB;
        border-radius: 13px;
        color: #176454;
        font-size: 11px;
        font-weight: 500;
    }

    @media (max-width: 360px) {
        .leave-page {
            padding-left: 16px;
            padding-right: 16px;
        }

        .balance-card {
            gap: 6px;
        }

        .balance-renewal {
            font-size: 10px;
        }

        .date-fields {
            gap: 8px;
        }

        .form-control {
            padding: 0 9px;
            font-size: 12px;
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


    <div class="eyebrow">TIME TO RECHARGE</div>

    <h1 class="page-title">Leave request</h1>

    <p class="page-subtitle">
        Make room for life outside work.
    </p>

    <!-- Annual leave balance -->

    <section class="balance-card">

        <div>
            <div class="balance-label">
                Annual leave available
            </div>

            <div class="balance-number">
                12 days
            </div>
        </div>

        <div class="balance-renewal">
            Renews Jan 1, 2027
        </div>

    </section>

    <!-- Leave request form -->

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
                    value="2026-11-23"
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
                    value="2026-11-24"
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
            >Taking a short break to spend time with family.</textarea>

            <div class="reason-footer">
                <span id="character-count">49 / 500</span>
            </div>

        </div>

        <p class="approval-note">
            Sent to Alex Morgan for approval.
        </p>

        <button type="submit" class="submit-button">
            Submit request
        </button>

    </form>

    <!-- Recent requests -->

    <section>

        <div class="recent-heading">

            <h2>Recent requests</h2>

            <a href="#" class="view-all">
                View all
            </a>

        </div>

        @forelse ($recentRequests ?? collect() as $leaveRequest)
            @php
                $days = $leaveRequest->start_date->diffInDays($leaveRequest->end_date) + 1;
            @endphp

            <div class="request-card">
                <div>
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
            <div class="request-card">
                <div>
                    <div class="request-title">
                        No leave requests yet
                    </div>

                    <div class="request-date">
                        Submitted requests will appear here.
                    </div>
                </div>
            </div>
        @endforelse

    </section>

</main>

<script>
    const reasonInput = document.getElementById('leave-reason');
    const characterCount = document.getElementById('character-count');

    reasonInput.addEventListener('input', function () {
        characterCount.textContent = `${this.value.length} / 500`;
    });
</script>

@endsection
