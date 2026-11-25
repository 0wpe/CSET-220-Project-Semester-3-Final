@extends('layouts.app')

@section('title', 'Add / Edit Employee')

@section('content')
@include('partials.navbar-admin', ['activePage' => 'employees'])

<main>

<style>
    * {
        box-sizing: border-box;
    }

    .employee-editor {
        width: 100%;
        max-width: none;
        margin: 0;
        padding: 18px 24px 30px;
        font-family: 'Inter', Arial, sans-serif;
        color: #172E29;
    }

    .eyebrow {
        color: #176454;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: .3px;
    }

    .employee-editor h1 {
        font-size: 28px;
        margin: 8px 0;
        font-weight: 600;
    }

    .subtitle {
        color: #687B74;
        font-size: 13px;
        margin-bottom: 22px;
    }

    .mode-switch,
    .pay-switch {
        display: flex;
        background: #E7F0EB;
        border-radius: 11px;
        padding: 2px;
        margin-bottom: 24px;
    }

    .mode-switch button,
    .pay-switch button {
        flex: 1;
        border: 1px solid transparent;
        background: transparent;
        border-radius: 10px;
        padding: 10px;
        font-size: 14px;
        font-weight: 600;
        color: #687B74;
        cursor: pointer;
    }

    .mode-switch button.active,
    .pay-switch button.active {
        background: #176454;
        color: white;
    }

    .mode-switch button:first-child.active,
    .pay-switch button:first-child.active {
        background: white;
        color: #172E29;
        border-color: #DFE7E2;
    }

    .form-section {
        margin-bottom: 24px;
    }

    .form-section h2 {
        font-size: 17px;
        margin: 0 0 16px;
        font-weight: 600;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px 14px;
    }

    .field {
        display: flex;
        flex-direction: column;
        gap: 7px;
        min-width: 0;
    }

    .field.full {
        grid-column: 1 / -1;
    }

    .field label {
        color: #687B74;
        font-size: 11px;
        font-weight: 500;
    }

    .field input,
    .field select {
        width: 100%;
        height: 49px;
        padding: 0 12px;
        border: 1px solid #DFE7E2;
        border-radius: 10px;
        background: white;
        color: #172E29;
        font-family: inherit;
        font-size: 13px;
        outline: none;
    }

    .field input:focus,
    .field select:focus {
        border-color: #176454;
        box-shadow: 0 0 0 2px rgba(23, 100, 84, .1);
    }

    .save-button {
        width: 100%;
        height: 49px;
        border: none;
        border-radius: 12px;
        background: #176454;
        color: white;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
    }

    .save-button:hover {
        background: #124F43;
    }

    .button-group {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .delete-button {
        width: 100%;
        height: 49px;
        border: 1px solid #E5C7C7;
        border-radius: 12px;
        background: #FFFFFF;
        color: #A33D3D;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
    }

    .delete-button:hover {
        background: #FFF4F4;
    }

    /* Phone layout */

    @media (max-width: 480px) {
        .form-grid {
            grid-template-columns: 1fr;
        }

        .field.full {
            grid-column: auto;
        }
    }

    /* Tablet layout */

    @media (min-width: 768px) {
        .employee-editor {
            padding: 30px 32px 45px;
        }

        .employee-editor h1 {
            font-size: 32px;
        }

        .subtitle {
            font-size: 14px;
            margin-bottom: 28px;
        }

        .form-section {
            margin-bottom: 32px;
        }

        .form-section h2 {
            font-size: 19px;
        }

        .field label {
            font-size: 12px;
        }

        .field input,
        .field select {
            height: 52px;
            font-size: 14px;
        }

        .save-button,
        .delete-button {
            height: 52px;
        }

        .button-group {
            flex-direction: row;
        }

        .button-group button {
            flex: 1;
        }
    }

    /* Desktop layout */

    @media (min-width: 1100px) {
        .employee-editor {
            padding: 38px 42px 50px;
        }

        .employee-form-content {
            max-width: 1050px;
            background: #FFFFFF;
            padding: 30px;
            border-radius: 16px;
        }

        .form-grid {
            gap: 20px;
        }

        .form-section {
            margin-bottom: 36px;
        }

        .mode-switch {
            max-width: 420px;
        }

        .pay-switch {
            margin-bottom: 0;
        }
    }

    @media (min-width: 1500px) {
        .employee-editor {
            padding: 44px 55px 55px;
        }

        .employee-form-content {
            max-width: 1150px;
            padding: 36px;
        }
    }
</style>

<div class="employee-editor">

    <div class="eyebrow">EMPLOYEE PROFILES</div>

    <h1>Add / edit employee</h1>

    <p class="subtitle">
        Keep employee and pay details up to date.
    </p>

    <div class="mode-switch">
        <button type="button" id="addMode" onclick="setMode('add')">
            Add employee
        </button>

        <button type="button" id="editMode" class="active" onclick="setMode('edit')">
            Edit employee
        </button>
    </div>

    <div class="employee-form-content">

        <form method="POST" action="{{ url('/admin/employees') }}" id="employeeForm">
            @csrf

            @if(session('success'))
                <p style="color: green;">
                    {{ session('success') }}
                </p>
            @endif

            @if(session('temporaryPassword'))
                <p style="color: green;">
                    Temporary Password: {{ session('temporaryPassword') }}
                </p>
            @endif

            @if($errors->any())
                <div style="color: red;">
                    @foreach($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <section class="form-section">

                <h2>Personal information</h2>

                <div class="form-grid">

                    <div class="field full" id="employeeSelectField">
                        <label for="employee_id">Select Employee</label>

                        <select id="employee_id" name="employee_id" onchange="loadEmployee()">
                            <option value="">Select an employee</option>

                            @foreach($employees as $employee)
                                <option
                                    value="{{ $employee->employee_id }}"
                                    data-email="{{ $employee->user->email ?? '' }}"
                                    data-department="{{ $employee->department_id }}"
                                    data-designation="{{ $employee->designation }}"
                                    data-pay-type="{{ $employee->pay_type }}"
                                    data-salary="{{ $employee->base_salary }}"
                                    data-hourly="{{ $employee->hourly_rate }}"
                                >
                                    {{ $employee->user->first_name ?? '' }}
                                    {{ $employee->user->last_name ?? '' }}
                                    (ID: {{ $employee->employee_id }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="field" id="firstNameField">
                        <label for="first_name">First name</label>
                        <input type="text" id="first_name" name="first_name">
                    </div>

                    <div class="field" id="lastNameField">
                        <label for="last_name">Last name</label>
                        <input type="text" id="last_name" name="last_name">
                    </div>

                    <div class="field full">
                        <label for="email">Work email</label>
                        <input type="email" id="email" name="email" required>
                    </div>

                    <div class="field" id="passwordField">
                        <label for="password">New Password</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Enter new password"
                            autocomplete="new-password"
                        >
                    </div>

                    <div class="field" id="passwordConfirmField">
                        <label for="password_confirmation">Confirm Password</label>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            placeholder="Confirm new password"
                            autocomplete="new-password"
                        >
                    </div>

                </div>

            </section>

            <section class="form-section">

                <h2>Employment & pay</h2>

                <div class="form-grid">

                    <div class="field">
                        <label for="department">Department</label>

                        <select id="department" name="department_id" required>
                            <option value="">Select Department</option>

                            @foreach($departments as $department)
                                <option value="{{ $department->department_id }}">
                                    {{ $department->department_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="field">
                        <label for="designation">Designation</label>

                        <select id="designation" name="designation" required>
                            <option value="">Select designation</option>
                            <option value="Manager">Manager</option>
                            <option value="HR Specialist">HR Specialist</option>
                            <option value="Accountant">Accountant</option>
                            <option value="Software Developer">Software Developer</option>
                            <option value="IT Support Specialist">IT Support Specialist</option>
                            <option value="Sales Associate">Sales Associate</option>
                            <option value="Marketing Specialist">Marketing Specialist</option>
                            <option value="Customer Service Representative">Customer Service Representative</option>
                            <option value="Administrative Assistant">Administrative Assistant</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="field full">
                        <label for="manager">Manager</label>
                        <select id="manager">
                            <option>Alex Morgan</option>
                            <option>Leah Chen</option>
                            <option>Noah Williams</option>
                            <option>Sofia Patel</option>
                        </select>
                    </div>

                    <div class="field full">
                        <label>Employment type</label>

                        <input type="hidden" name="pay_type" id="pay_type" value="Salaried">

                        <div class="pay-switch">
                            <button type="button" id="hourlyMode" onclick="setPayType('hourly')">
                                Hourly
                            </button>

                            <button type="button" id="salaryMode" class="active" onclick="setPayType('salary')">
                                Salaried
                            </button>
                        </div>
                    </div>

                    <div class="field full">
                        <label for="salary" id="salaryLabel">Monthly salary (USD)</label>
                        <input
                            type="number"
                            id="salary"
                            name="base_salary"
                            placeholder="Enter salary"
                            min="0"
                            step="0.01"
                            required
                        >
                    </div>

                </div>

            </section>

            <div class="button-group">

                <button type="submit" class="save-button">
                    Save employee
                </button>

                <button
                    type="button"
                    class="delete-button"
                    id="deleteEmployeeButton"
                    onclick="deleteEmployee()"
                    style="display: none;"
                >
                    Delete employee
                </button>

            </div>

        </form>

    </div>

</div>

</main>

<script>
    function setMode(mode) {
        const isAdd = mode === 'add';

        const form = document.getElementById('employeeForm');
        const employeeDropdown = document.getElementById('employee_id');

        if (isAdd) {
            form.action = "{{ url('/admin/employees') }}";
            document.getElementById('methodOverride')?.remove();
        } else {
            if (!document.getElementById('methodOverride')) {
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.id = 'methodOverride';
                methodInput.value = 'PUT';
                form.appendChild(methodInput);
            }

            if (employeeDropdown.value) {
                form.action = "{{ url('/admin/employees') }}/" + employeeDropdown.value;
            }
        }

        document.getElementById('addMode').classList.toggle('active', isAdd);
        document.getElementById('editMode').classList.toggle('active', !isAdd);

        document.getElementById('employeeSelectField').style.display =
            isAdd ? 'none' : 'flex';

        document.getElementById('firstNameField').style.display =
            isAdd ? 'flex' : 'none';

        document.getElementById('lastNameField').style.display =
            isAdd ? 'flex' : 'none';

        if (isAdd) {
            document.getElementById('first_name').value = '';
            document.getElementById('last_name').value = '';
            document.getElementById('email').value = '';
            document.getElementById('designation').value = '';
            document.getElementById('salary').value = '';
            document.getElementById('department').value = '';
            document.getElementById('employee_id').value = '';
        }

        document.getElementById('first_name').disabled = !isAdd;
        document.getElementById('last_name').disabled = !isAdd;

        document.getElementById('passwordField').style.display =
            isAdd ? 'none' : 'flex';

        document.getElementById('passwordConfirmField').style.display =
            isAdd ? 'none' : 'flex';

        document.getElementById('deleteEmployeeButton').style.display =
            isAdd ? 'none' : 'block';
    }

    function loadEmployee() {
        const dropdown = document.getElementById('employee_id');
        const selected = dropdown.options[dropdown.selectedIndex];

        if (!dropdown.value) {
            return;
        }

        document.getElementById('employeeForm').action =
            "{{ url('/admin/employees') }}/" + dropdown.value;

        document.getElementById('email').value = selected.dataset.email;
        document.getElementById('department').value = selected.dataset.department;
        document.getElementById('designation').value = selected.dataset.designation;

        const payType = selected.dataset.payType;

        setPayType(payType.toLowerCase() === 'hourly' ? 'hourly' : 'salary');

        document.getElementById('salary').value =
            payType.toLowerCase() === 'hourly'
                ? selected.dataset.hourly
                : selected.dataset.salary;
    }

    setMode('add');

    function setPayType(type) {
        document.getElementById('hourlyMode').classList.toggle('active', type === 'hourly');
        document.getElementById('salaryMode').classList.toggle('active', type === 'salary');

        document.getElementById('salaryLabel').textContent =
            type === 'hourly' ? 'Hourly rate (USD)' : 'Monthly salary (USD)';

        document.getElementById('pay_type').value =
            type === 'hourly' ? 'Hourly' : 'Salaried';

        document.getElementById('salary').name =
            type === 'hourly' ? 'hourly_rate' : 'base_salary';
    }

    function saveEmployee(event) {
        event.preventDefault();
        alert('Employee form is ready. Saving will be available when the backend is connected.');
        return false;
    }

    function deleteEmployee() {
        const employeeId = document.getElementById('employee_id').value;

        if (!employeeId) {
            alert('Please select an employee first.');
            return;
        }

        if (!confirm('Are you sure you want to permanently delete this employee? This action cannot be undone.')) {
            return;
        }

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ url('/admin/employees') }}/" + employeeId;

        form.innerHTML = `
            @csrf
            @method('DELETE')
        `;

        document.body.appendChild(form);
        form.submit();
    }
</script>
@endsection