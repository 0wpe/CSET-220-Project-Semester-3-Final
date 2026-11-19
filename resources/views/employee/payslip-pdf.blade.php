
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Employee Payslip</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #333;
            font-size: 12px;
            margin: 30px;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #198754;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .header h1 {
            color: #198754;
            margin: 0;
            font-size: 26px;
        }

        .header p {
            color: #777;
            margin-top: 6px;
        }

        .section-title {
            color: #198754;
            font-size: 15px;
            margin-top: 25px;
            margin-bottom: 10px;
        }

        .details {
            width: 100%;
            border-collapse: collapse;
        }

        .details td {
            padding: 10px;
            border: 1px solid #ddd;
        }

        .details td:first-child {
            font-weight: bold;
            background: #f5f5f5;
            width: 40%;
        }

        .earnings {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .earnings th,
        .earnings td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .earnings th {
            background: #198754;
            color: white;
        }

        .amount {
            text-align: right !important;
        }

        .net-pay {
            margin-top: 25px;
            padding: 18px;
            background: #e9f7ef;
            border: 1px solid #198754;
            text-align: center;
        }

        .net-pay h2 {
            color: #198754;
            margin: 0 0 8px;
            font-size: 16px;
        }

        .net-pay p {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }

        .note {
            margin-top: 25px;
            color: #777;
            font-size: 10px;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            color: #999;
            font-size: 10px;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>Smart HR: Tempo</h1>
        <p>Employee Payroll Statement</p>
    </div>

    <h3 class="section-title">Employee Information</h3>

    <table class="details">
        <tr>
            <td>Employee Name</td>
            <td>
                {{ $employee->user->first_name }}
                {{ $employee->user->last_name }}
            </td>
        </tr>

        <tr>
            <td>Employee ID</td>
            <td>{{ $employee->employee_id }}</td>
        </tr>

        <tr>
            <td>Pay Type</td>
            <td>{{ $employee->pay_type }}</td>
        </tr>

        <tr>
            <td>Pay Period</td>
            <td>
                {{ \Carbon\Carbon::parse($payroll->payroll_month)->format('F Y') }}
            </td>
        </tr>
    </table>

    <h3 class="section-title">Payroll Summary</h3>

    <table class="earnings">
        <thead>
            <tr>
                <th>Description</th>
                <th class="amount">Amount</th>
            </tr>
        </thead>

        <tbody>
            @if ($employee->pay_type === 'Hourly')
                <tr>
                    <td>Regular Hours</td>
                    <td class="amount">
                        {{ number_format($payroll->regular_hours ?? 0, 2) }}
                    </td>
                </tr>

                <tr>
                    <td>Overtime Hours</td>
                    <td class="amount">
                        {{ number_format($payroll->overtime_hours ?? 0, 2) }}
                    </td>
                </tr>
            @endif

            <tr>
                <td>Gross Pay</td>
                <td class="amount">
                    ${{ number_format($payroll->gross_pay ?? 0, 2) }}
                </td>
            </tr>

            <tr>
                <td>Estimated Deductions</td>
                <td class="amount">
                    -${{ number_format($payroll->deductions ?? 0, 2) }}
                </td>
            </tr>

            <tr>
                <td><strong>Net Pay</strong></td>
                <td class="amount">
                    <strong>
                        ${{ number_format($payroll->net_pay ?? 0, 2) }}
                    </strong>
                </td>
            </tr>
        </tbody>
    </table>

    <div class="net-pay">
        <h2>Net Pay</h2>
        <p>${{ number_format($payroll->net_pay ?? 0, 2) }}</p>
    </div>

    <p class="note">
        Deductions are estimated using the project's 20% deduction assumption.
        This statement is generated electronically and does not require a signature.
    </p>

    <div class="footer">
        Smart HR: Tempo | Employee Payroll Statement
    </div>

</body>
</html>
