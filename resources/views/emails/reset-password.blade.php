<x-mail::message>
<style>
    :root {
        --primary-color: #121215;
        --font-size-paragraph: 1rem;
        --line-height-paragraph: 1.5;
        --font-size-warning: 0.875rem;
        --margin-top-warning: 1.25rem;
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

    .warning-paragraph {
        margin-top: var(--margin-top-warning);
        font-size: var(--font-size-warning);
        font-weight: normal;
        color: var(--text-color);
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
    <h1 class="email-h1">Reset Password</h1>
    <p class="email-paragraph">
        You have requested to reset your password. Click the button below to proceed:
    </p>
    <x-mail::button :url="url('/reset-password?token=' . $token . '&email=' . urlencode($user->email))" class="reset-button">
        Reset Password
    </x-mail::button>
    <p class="warning-paragraph">
        If you did not request a password reset, you can ignore this email.
    </p>
</div>

<p class="footer-paragraph">
    Thank you
</p>
</x-mail::message>
