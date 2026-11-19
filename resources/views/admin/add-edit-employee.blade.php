
@extends('layouts.app')

@section('title', 'Add / Edit Employee')

@section('content')
@include('partials.navbar-admin', ['activePage' => 'employees'])

<style>
    * {
        box-sizing: border-box;
    }

    .employee-editor {
        max-width: 600px;
        margin: 18px auto 40px;
        padding: 0 24px;
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

    @media (max-width: 480px) {
        .employee-editor {
            padding: 0 20px;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .field.full {
            grid-column: auto;
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

    <form method="POST" action="{{ url('/admin/employees') }}">
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

                <div class="field">
                    <label for="first_name">First name</label>
                    <input type="text" id="first_name" name="first_name" required>
                </div>

                <div class="field">
                    <label for="last_name">Last name</label>
                    <input type="text" id="last_name" name="last_name" required>
                </div>

                

                <div class="field full">
                    <label for="email">Work email</label>
                    <input type="email" id="email" name="email" required>
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
    <input type="text" id="designation" name="designation" placeholder="Enter designation" required>
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
                <input type="number" id="salary" name="base_salary" placeholder="Enter salary" min="0" step="0.01" required>
                </div>

            </div>

        </section>

        <button type="submit" class="save-button">
            Save employee
        </button>

    </form>

</div>

<script>
    function setMode(mode) {
    document.getElementById('addMode').classList.toggle('active', mode === 'add');
    document.getElementById('editMode').classList.toggle('active', mode === 'edit');

    if (mode === 'add') {
        document.getElementById('first_name').value = '';
        document.getElementById('last_name').value = '';
        document.getElementById('email').value = '';
        document.getElementById('designation').value = '';
        document.getElementById('salary').value = '';
        document.getElementById('department').value = '';
    }
}

    function setPayType(type) {
        document.getElementById('hourlyMode').classList.toggle('active', type === 'hourly');
        document.getElementById('salaryMode').classList.toggle('active', type === 'salary');

        document.getElementById('salaryLabel').textContent =
            type === 'hourly' ? 'Hourly rate (USD)' : 'Monthly salary (USD)';
    }

    function saveEmployee(event) {
        event.preventDefault();
        alert('Employee form is ready. Saving will be available when the backend is connected.');
        return false;
    }
</script>
@endsection
