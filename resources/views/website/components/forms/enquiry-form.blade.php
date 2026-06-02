<section class="enquiry-section">
    <div class="container">
        <div class="enquiry-form-card">
            <h3>Enquiry Form</h3>

            @if (session('enquiry_success'))
                <div class="alert alert-success">{{ session('enquiry_success') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-error">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('website.enquiry.store') }}" class="form-grid">
                @csrf
                <div class="form-field">
                    <label for="enq_name">Full Name *</label>
                    <input type="text" id="enq_name" name="name" value="{{ old('name') }}" maxlength="50" placeholder="Your full name" required>
                </div>
                <div class="form-field">
                    <label for="enq_phone">Contact Number *</label>
                    <input type="text" id="enq_phone" name="contact_number" value="{{ old('contact_number') }}" maxlength="10" pattern="[0-9]{10}" placeholder="10-digit mobile" required>
                </div>
                <div class="form-field full">
                    <label for="enq_email">Email Address *</label>
                    <input type="email" id="enq_email" name="email" value="{{ old('email') }}" maxlength="50" placeholder="your@email.com" required>
                </div>
                <div class="form-field full">
                    <label for="enq_message">Your Message *</label>
                    <textarea id="enq_message" name="message" rows="4" maxlength="255" placeholder="Tell us how we can help you..." required>{{ old('message') }}</textarea>
                </div>
                <div class="full">
                    <button type="submit" class="enquiry-submit">Submit Enquiry</button>
                </div>
            </form>
        </div>
    </div>
</section>

<style>
    .enquiry-section {
        padding: 2.4rem 0;
    }

    .enquiry-form-card {
        background: #fff;
        border: 1px solid #dbe3ee;
        border-radius: 14px;
        padding: 1rem;
        box-shadow: 0 10px 28px rgba(15, 23, 42, 0.06);
    }

    .enquiry-form-card h3 {
        margin: 0 0 0.85rem;
        color: #0f172a;
        font-size: 1.1rem;
        font-weight: 700;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.72rem;
    }

    .form-field {
        display: flex;
        flex-direction: column;
        gap: 0.28rem;
    }

    .form-field.full,
    .full {
        grid-column: 1 / -1;
    }

    .form-field label {
        color: #0f172a;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .form-field input,
    .form-field textarea {
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        padding: 0.62rem 0.68rem;
        font: inherit;
        background: #fff;
        width: 100%;
        box-sizing: border-box;
    }

    .form-field textarea {
        min-height: 6.2rem;
        resize: vertical;
    }

    .form-field input:focus,
    .form-field textarea:focus {
        outline: none;
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.13);
    }

    .alert {
        border-radius: 10px;
        padding: 0.6rem 0.68rem;
        font-size: 0.84rem;
        margin-bottom: 0.65rem;
    }

    .alert-success {
        border: 1px solid #86efac;
        background: #f0fdf4;
        color: #166534;
    }

    .alert-error {
        border: 1px solid #fda4af;
        background: #fff1f2;
        color: #9f1239;
    }

    .alert ul {
        margin: 0.36rem 0 0;
        padding-left: 1rem;
    }

    .enquiry-submit {
        width: 100%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        border: 1px solid transparent;
        padding: 0.7rem 1rem;
        font-size: 0.88rem;
        font-weight: 700;
        transition: 0.2s ease;
        background: #1d4ed8;
        color: #fff;
        cursor: pointer;
    }

    .enquiry-submit:hover {
        background: #1e3a8a;
    }

    @media (max-width: 900px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

