@extends('layouts.app')

@section('title', 'Leave Management')

@section('content')

@include('partials.navbar-admin', ['activePage' => 'leave-requests'])

<style>
    * {
        box-sizing: border-box;
    }

    .leave-management {
        max-width: 1100px;
        margin: 35px auto;
        padding: 0 24px 40px;
        font-family: 'Inter', sans-serif;
        color: #172E29;
    }

    .leave-header {
        margin-bottom: 28px;
    }

    .section-label {
        color: #176454;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .leave-header h1 {
        font-size: 28px;
        font-weight: 600;
        margin: 0 0 8px;
    }

    .leave-header p {
        color: #687B74;
        font-size: 13px;
        margin: 0;
    }

    .leave-overview {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-bottom: 32px;
    }

    .overview-card {
        background: #FFFFFF;
        border-radius: 12px;
        padding: 12px;
        min-height: 72px;
    }

    .overview-card.pending {
        background: #176454;
    }

    .overview-label {
        display: block;
        color: #687B74;
        font-size: 11px;
        font-weight: 500;
        margin-bottom: 4px;
    }

    .pending .overview-label {
        color: #D4E7DF;
    }

    .overview-number {
        display: block;
        color: #172E29;
        font-size: 22px;
        font-weight: 600;
    }

    .pending .overview-number {
        color: #FFFFFF;
    }

    .leave-section {
        margin-bottom: 32px;
    }

    .section-heading {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 14px;
    }

    .section-heading h2 {
        font-size: 18px;
        font-weight: 600;
        margin: 0;
    }

    .section-count {
        color: #176454;
        font-size: 12px;
        font-weight: 500;
    }

    .leave-card {
        background: #FFFFFF;
        border-radius: 12px;
        padding: 14px;
        margin-bottom: 12px;
        box-shadow: 0 2px 8px rgba(23, 46, 41, 0.03);
    }

    .leave-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 10px;
    }

    .employee-name {
        font-size: 14px;
        font-weight: 600;
        margin: 0 0 4px;
    }

    .employee-department {
        color: #687B74;
        font-size: 11px;
    }

    .status {
        display: inline-block;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 500;
        white-space: nowrap;
    }

    .status.pending {
        background: #FBEBDC;
        color: #8E4F22;
    }

    .status.approved {
        background: #E7F0EB;
        color: #176454;
    }

    .status.rejected {
        background: #F9E9E7;
        color: #A13C32;
    }

    .leave-details {
        margin-top: 16px;
    }

    .leave-dates {
        font-size: 12px;
        font-weight: 500;
        margin-bottom: 7px;
    }

    .leave-reason {
        color: #687B74;
        font-size: 11px;
        line-height: 1.5;
    }

    .leave-actions {
        display: flex;
        gap: 10px;
        margin-top: 16px;
    }

    .leave-button {
        flex: 1;
        height: 34px;
        border-radius: 9px;
        font-family: inherit;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
    }

    .deny-button {
        background: #FFFFFF;
        border: 1px solid #DFE7E2;
        color: #172E29;
    }

    .accept-button {
        background: #176454;
        border: 1px solid #176454;
        color: #FFFFFF;
    }

    .history-card {
        min-height: 114px;
    }

    .history-details {
        margin-top: 12px;
    }

    .reviewed-by {
        color: #687B74;
        font-size: 10px;
        margin-top: 7px;
    }

    .history-footer {
        color: #687B74;
        font-size: 11px;
        margin-top: 16px;
    }

    @media (max-width: 600px) {
        .leave-management {
            margin-top: 24px;
            padding: 0 16px 30px;
        }

        .leave-header h1 {
            font-size: 25px;
        }

        .leave-overview {
            gap: 8px;
        }

        .overview-card {
            padding: 10px;
        }

        .section-heading h2 {
            font-size: 16px;
        }
    }
</style>

@php
    $pendingRequests = $leaveRequests->filter(fn ($r) => strtolower($r->status) === 'pending');
    $acceptedRequests = $leaveRequests->filter(fn ($r) => strtolower($r->status) === 'accepted');
    $deniedRequests = $leaveRequests->filter(fn ($r) => strtolower($r->status) === 'denied');
    $reviewedRequests = $leaveRequests->filter(fn ($r) => strtolower($r->status) !== 'pending');
@endphp

<div class="leave-management">

    @if (session('success'))
        <div class="leave-card" style="border-left: 4px solid #176454; margin-bottom: 16px;">
            <div class="leave-reason" style="color: #176454; font-weight: 500;">
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if ($errors->any())
        <div class="leave-card" style="border-left: 4px solid #A13C32; margin-bottom: 16px;">
            <div class="leave-reason" style="color: #A13C32; font-weight: 500;">
                {{ $errors->first() }}
            </div>
        </div>
    @endif

    <!-- Leave Overview -->
    <div class="leave-header">
        <div class="section-label">TEAM TIME OFF</div>

        <h1>Leave Management</h1>

        <p>Review requests and manage your team's time off.</p>
    </div>

    <div class="leave-overview">

        <div class="overview-card pending">
            <span class="overview-label">Pending</span>
            <span class="overview-number">{{ $pendingRequests->count() }}</span>
        </div>

        <div class="overview-card">
            <span class="overview-label">Accepted</span>
            <span class="overview-number">{{ $acceptedRequests->count() }}</span>
        </div>

        <div class="overview-card">
            <span class="overview-label">Denied</span>
            <span class="overview-number">{{ $deniedRequests->count() }}</span>
        </div>

    </div>

    <!-- Pending Requests -->
    <section class="leave-section">

        <div class="section-heading">
            <h2>Pending Requests</h2>
            <span class="section-count">{{ $pendingRequests->count() }} incoming</span>
        </div>

        @forelse ($pendingRequests as $leaveRequest)
            @php
                $employee = $leaveRequest->employee;
                $user = $employee?->user;
                $employeeName = $user
                    ? trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''))
                    : 'Unknown Employee';
                $employeeName = $employeeName !== '' ? $employeeName : 'Unknown Employee';
                $departmentName = $employee?->department?->department_name ?? 'General';
                $start = $leaveRequest->start_date;
                $end = $leaveRequest->end_date;
                $days = (int) $start->diffInDays($end) + 1;
                $statusLower = strtolower($leaveRequest->status);
            @endphp

            <div class="leave-card" data-request-id="{{ $leaveRequest->request_id }}">

                <div class="leave-card-header">
                    <div>
                        <h3 class="employee-name">{{ $employeeName }}</h3>
                        <div class="employee-department">
                            {{ $departmentName }}
                            @if (!empty($employee?->employee_id))
                                · EMP-{{ str_pad((string) $employee->employee_id, 3, '0', STR_PAD_LEFT) }}
                            @endif
                        </div>
                    </div>

                    <span class="status {{ $statusLower }}">{{ ucfirst($leaveRequest->status) }}</span>
                </div>

                <div class="leave-details">
                    <div class="leave-dates">
                        @if ($start->isSameDay($end))
                            {{ $start->format('M j, Y') }}
                        @elseif ($start->isSameMonth($end))
                            {{ $start->format('M j') }}–{{ $end->format('j, Y') }}
                        @else
                            {{ $start->format('M j, Y') }}–{{ $end->format('M j, Y') }}
                        @endif
                        · {{ $leaveRequest->leave_type }} · {{ $days }} {{ \Illuminate\Support\Str::plural('day', $days) }}
                    </div>

                    <div class="leave-reason">
                        Reason: {{ $leaveRequest->reason ?: 'No reason provided' }}
                    </div>
                </div>

                <div class="leave-actions">
                    <form method="POST"
                          action="{{ route('admin.leave-requests.update-status', $leaveRequest->request_id) }}"
                          style="flex: 1; display: flex;">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="Rejected">
                        <button type="submit" class="leave-button deny-button">
                            Deny
                        </button>
                    </form>

                    <form method="POST"
                          action="{{ route('admin.leave-requests.update-status', $leaveRequest->request_id) }}"
                          style="flex: 1; display: flex;">
                        @csrf
                        @method('PATCH')
                        <input type="hidden" name="status" value="Approved">
                        <button type="submit" class="leave-button accept-button">
                            Accept
                        </button>
                    </form>
                </div>

            </div>
        @empty
            <div class="leave-card">
                <div class="leave-details">
                    <div class="leave-reason">No pending leave requests.</div>
                </div>
            </div>
        @endforelse

    </section>

    <!-- Request History -->
    <section class="leave-section">

        <div class="section-heading">
            <h2>Request History</h2>
            <span class="section-count">{{ $reviewedRequests->count() }} reviewed</span>
        </div>

        @forelse ($reviewedRequests as $leaveRequest)
            @php
                $employee = $leaveRequest->employee;
                $user = $employee?->user;
                $employeeName = $user
                    ? trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? ''))
                    : 'Unknown Employee';
                $employeeName = $employeeName !== '' ? $employeeName : 'Unknown Employee';
                $departmentName = $employee?->department?->department_name ?? 'General';
                $start = $leaveRequest->start_date;
                $end = $leaveRequest->end_date;
                $days = (int) $start->diffInDays($end) + 1;
                $statusLower = strtolower($leaveRequest->status);
                $reviewerName = $leaveRequest->reviewer?->name;
            @endphp

            <div class="leave-card history-card" data-request-id="{{ $leaveRequest->request_id }}">

                <div class="leave-card-header">
                    <div>
                        <h3 class="employee-name">{{ $employeeName }}</h3>
                        <div class="employee-department">
                            {{ $departmentName }}
                            @if (!empty($employee?->employee_id))
                                · EMP-{{ str_pad((string) $employee->employee_id, 3, '0', STR_PAD_LEFT) }}
                            @endif
                        </div>
                    </div>

                    <span class="status {{ $statusLower }}">{{ ucfirst($leaveRequest->status) }}</span>
                </div>

                <div class="history-details">
                    <div class="leave-dates">
                        @if ($start->isSameDay($end))
                            {{ $start->format('M j, Y') }}
                        @elseif ($start->isSameMonth($end))
                            {{ $start->format('M j') }}–{{ $end->format('j, Y') }}
                        @else
                            {{ $start->format('M j, Y') }}–{{ $end->format('M j, Y') }}
                        @endif
                        · {{ $leaveRequest->leave_type }} · {{ $days }} {{ \Illuminate\Support\Str::plural('day', $days) }}
                    </div>

                    <div class="reviewed-by">
                        @if (!empty($leaveRequest->reviewed_by))
                            {{ ucfirst($statusLower) }} by {{ $reviewerName ?? ('Admin #' . $leaveRequest->reviewed_by) }}
                        @elseif (!empty($leaveRequest->reason))
                            Reason: {{ $leaveRequest->reason }}
                        @endif
                    </div>
                </div>

            </div>
        @empty
            <div class="leave-card history-card">
                <div class="history-details">
                    <div class="reviewed-by">No reviewed requests yet.</div>
                </div>
            </div>
        @endforelse

        <div class="history-footer">
            All {{ $leaveRequests->count() }} {{ \Illuminate\Support\Str::plural('request', $leaveRequests->count()) }} shown
        </div>

    </section>

</div>

@endsection
