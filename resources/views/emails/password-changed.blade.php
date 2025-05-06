<x-mail::message>
<style>
    :root {
        --primary-color: #121215;
        --font-size-paragraph: 1rem;
        --line-height-paragraph: 1.5;
        --font-size-footer: 0.75rem;
        --margin-top-footer: 1.875rem;
        --text-color: var(--primary-color);
    }

    .email-h1 {
        margin-top: 0;
        color: var(--text-color);
        font-weight: bold;
    }

    .email-paragraph {
        color: var(--text-color);
        font-size: var(--font-size-paragraph);
        font-weight: normal;
        line-height: var(--line-height-paragraph);
    }

    .footer-paragraph {
        margin-top: var(--margin-top-footer);
        text-align: center;
        font-size: var(--font-size-footer);
        font-weight: normal;
        color: var(--primary-color);
    }
</style>

<div class="email-body">
    <h1 class="email-h1">Password Successfully Changed</h1>
    <p class="email-paragraph">
        Hello {{ $user->name }},<br>
        We inform you that the password for your account has been successfully changed.<br>
        If you were not the one who made this change, please contact our support team immediately.
    </p>
</div>

<p class="footer-paragraph">
    Thank you
</p>
</x-mail::message>
