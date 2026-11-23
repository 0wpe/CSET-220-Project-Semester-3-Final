@extends('layouts.app')

@section('title', 'Leave Management')

@section('content')

@include('partials.navbar-admin', ['activePage' => 'leave'])

<style>
    body {
        background: #F6F8F7;
    }

    .leave-management {
        max-width: 1100px;
        margin: 35px auto;
        padding: 0 24px 40px;
        font-family: 'Inter', sans-serif;
        color: #172E29;
    }

    .leave-management *,
    .leave-management *::before,
    .leave-management *::after {
        box-sizing: border-box;
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
        gap: 12px;
        margin-bottom: 14px;
    }

    .section-heading-left {
        display: flex;
        align-items: center;
        gap: 8px;
        min-width: 0;
    }

    .section-heading h2 {
        font-size: 18px;
        font-weight: 600;
        line-height: 1.25;
        margin: 0;
    }

    .section-count {
        color: #176454;
        font-size: 12px;
        font-weight: 500;
        line-height: 1.25;
        margin: 0;
        padding: 0;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .collapse-toggle {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        padding: 0;
        border: none;
        background: transparent;
        color: #172E29;
        cursor: pointer;
        border-radius: 6px;
        flex-shrink: 0;
    }

    .collapse-toggle:hover {
        background: rgba(23, 100, 84, 0.08);
    }

    .collapse-arrow {
        display: inline-block;
        font-size: 12px;
        line-height: 1;
        transition: transform 0.2s ease;
    }

    .collapse-toggle[aria-expanded="false"] .collapse-arrow {
        transform: rotate(-90deg);
    }

    .pending-requests-body.is-collapsed,
    .request-history-body.is-collapsed {
        display: none;
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

    .status.approved,
    .status.accepted {
        background: #E7F0EB;
        color: #176454;
    }

    .status.rejected,
    .status.denied {
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
    $pendingRequests  = $leaveRequests->filter(fn ($r) => strtolower($r->status) === 'pending');
    $approvedRequests = $leaveRequests->filter(fn ($r) => in_array(strtolower($r->status), ['approved', 'accepted'], true));
    $rejectedRequests = $leaveRequests->filter(fn ($r) => in_array(strtolower($r->status), ['rejected', 'denied'], true));
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
            <span class="overview-number">{{ $approvedRequests->count() }}</span>
        </div>

        <div class="overview-card">
            <span class="overview-label">Denied</span>
            <span class="overview-number">{{ $rejectedRequests->count() }}</span>
        </div>

    </div>

    <!-- Pending Requests -->
    <section class="leave-section">

        <div class="section-heading">
            <div class="section-heading-left">
                <button
                    type="button"
                    class="collapse-toggle"
                    id="toggle-pending-requests"
                    aria-expanded="true"
                    aria-controls="pending-requests-body"
                    aria-label="Toggle Pending Requests"
                >
                    <span class="collapse-arrow" aria-hidden="true">&#9660;</span>
                </button>
                <h2>Pending Requests</h2>
            </div>
            <span class="section-count">{{ $pendingRequests->count() }} incoming</span>
        </div>

        <div class="pending-requests-body" id="pending-requests-body">
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
                    $statusBadgeClass = match ($statusLower) {
                        'approved', 'accepted' => 'approved',
                        'rejected', 'denied'   => 'rejected',
                        default                => 'pending',
                    };
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

                        <span class="status {{ $statusBadgeClass }}">{{ ucfirst($leaveRequest->status) }}</span>
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
        </div>

    </section>

    <!-- Request History -->
    <section class="leave-section">

        <div class="section-heading">
            <div class="section-heading-left">
                <button
                    type="button"
                    class="collapse-toggle"
                    id="toggle-request-history"
                    aria-expanded="false"
                    aria-controls="request-history-body"
                    aria-label="Toggle Request History"
                >
                    <span class="collapse-arrow" aria-hidden="true">&#9660;</span>
                </button>
                <h2>Request History</h2>
            </div>
            <span class="section-count">{{ $reviewedRequests->count() }} reviewed</span>
        </div>

        <div class="request-history-body is-collapsed" id="request-history-body">
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
                    $statusBadgeClass = match ($statusLower) {
                        'approved', 'accepted' => 'approved',
                        'rejected', 'denied'   => 'rejected',
                        default                => 'pending',
                    };
                    $reviewerUser = $leaveRequest->reviewer?->user;
                    $reviewerName = $reviewerUser
                        ? trim(($reviewerUser->first_name ?? '') . ' ' . ($reviewerUser->last_name ?? ''))
                        : null;
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

                        <span class="status {{ $statusBadgeClass }}">{{ ucfirst($leaveRequest->status) }}</span>
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
                                {{ ucfirst($statusLower) }} by {{ $reviewerName ?: ('Admin #' . $leaveRequest->reviewed_by) }}
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
        </div>

        <div class="history-footer">
            All {{ $leaveRequests->count() }} {{ \Illuminate\Support\Str::plural('request', $leaveRequests->count()) }} shown
        </div>

    </section>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggles = [
            { btnId: 'toggle-pending-requests', bodyId: 'pending-requests-body' },
            { btnId: 'toggle-request-history', bodyId: 'request-history-body' },
        ];

        toggles.forEach(function ({ btnId, bodyId }) {
            const toggleBtn = document.getElementById(btnId);
            const bodyEl = document.getElementById(bodyId);

            if (!toggleBtn || !bodyEl) return;

            toggleBtn.addEventListener('click', function () {
                const isExpanded = toggleBtn.getAttribute('aria-expanded') === 'true';
                toggleBtn.setAttribute('aria-expanded', String(!isExpanded));
                bodyEl.classList.toggle('is-collapsed', isExpanded);
            });
        });
    });
</script>

@endsection
