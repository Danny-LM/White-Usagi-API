<x-mail::message>
<style>
    :root {
        --primary-color: #6D28D9;
        --secondary-color: #FFFFFF;
        --text-color: #EEEEEE;
        --border-radius: 8px;
        --padding: 30px;
        --button-bg: var(--secondary-color);
        --button-text: var(--primary-color);
        --button-border-radius: 6px;
        --button-padding: 12px 24px;
    }
</style>

<div style="background-color: var(--primary-color); color: var(--secondary-color); padding: var(--padding); border-radius: var(--border-radius); text-align: center;">
    <h1 style="margin-top: 0;">Reset Password</h1>
    <p style="font-size: 16px; line-height: 1.5;">
        You have requested to reset your password. Click the button below to proceed:
    </p>
    <x-mail::button :url="url('/reset-password?token=' . $token . '&email=' . urlencode($notifiable->email))" color="primary" style="background-color: var(--button-bg); color: var(--button-text); border-radius: var(--button-border-radius); padding: var(--button-padding); text-decoration: none; font-weight: bold; display: inline-block;">
        Reset Password
    </x-mail::button>
    <p style="margin-top: 20px; font-size: 14px; color: var(--text-color);">
        If you did not request a password reset, you can ignore this email.
    </p>
</div>

<p style="margin-top: 30px; text-align: center; font-size: 12px; color: #999999;">
    Thank you,<br>
    {{ config('app.name') }}
</p>
</x-mail::message>
