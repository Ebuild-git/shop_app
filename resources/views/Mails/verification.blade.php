<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>{{ __('email.welcome.title') }}</title>
</head>
<body style="margin:0;padding:0;background:#f4f4f4;font-family:'Segoe UI',Arial,sans-serif;color:#222;">
<div style="max-width:600px;margin:30px auto;background:#fff;border-radius:8px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,0.08);">

  <!-- HEADER -->
  <table width="100%" cellpadding="0" cellspacing="0" style="background:#1a4a47;">
    <tr>
      <td style="padding:18px 28px;">
        <img src="{{ config('app.url') }}/icons/logo.png" alt="SHOPIN" style="height:44px;display:block;">
      </td>
      <td style="padding:18px 28px;text-align:right;">
        <table cellpadding="0" cellspacing="0" style="margin-left:auto;">
          <tr>
            <td style="padding-right:10px;font-size:24px;">🛡️</td>
            <td>
              <div style="color:#fff;font-size:14px;font-weight:700;">{{ __('email.welcome.secure_title') }}</div>
              <div style="color:#a8cbc8;font-size:12px;">{{ __('email.welcome.secure_subtitle') }}</div>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <!-- BODY -->
  <div style="padding:36px 32px 28px;">
    <h1 style="font-size:28px;font-weight:800;color:#1a1a1a;margin:0 0 18px;">{!! __('email.welcome.heading') !!}</h1>

    <p style="font-size:15px;color:#222;margin:0 0 10px;">
      {!! __('email.welcome.greeting', ['username' => '<a href="#" style="color:#1a7a6e;font-weight:600;text-decoration:none;">' . $user->username . '</a>']) !!},
    </p>
    <p style="font-size:15px;color:#444;line-height:1.6;margin:0 0 28px;">
      {{ __('email.welcome.message') }}
    </p>

    <!-- CTA BUTTON -->
    <a href="{{ route('verify_account', ['id_user' => $user->id, 'token' => $token]) }}"
       style="display:inline-block;background:#1a4a47;color:#ffffff !important;font-size:15px;font-weight:700;padding:15px 28px;border-radius:8px;text-decoration:none;margin-bottom:32px;">
      🔗 <span style="color:#ffffff !important;">{{ __('email.welcome.cta_button') }}</span>
    </a>

    <!-- DIVIDER -->
    <hr style="border:none;border-top:1px solid #e5e5e5;margin:0 0 28px;">

    <!-- Account Info Title -->
    <table cellpadding="0" cellspacing="0" style="margin-bottom:16px;">
      <tr>
        <td style="width:44px;height:44px;background:#1a4a47;border-radius:8px;text-align:center;vertical-align:middle;font-size:20px;">
          👤
        </td>
        <td style="padding-left:14px;font-size:17px;font-weight:700;color:#1a1a1a;">
          {{ __('email.welcome.account_info_title') }}
        </td>
      </tr>
    </table>

    <!-- Info Card -->
    <table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e0e0e0;border-radius:10px;overflow:hidden;margin-bottom:28px;">
      <tr>
        <td style="padding:16px 18px;border-bottom:1px solid #e8e8e8;">
          <table cellpadding="0" cellspacing="0">
            <tr>
              <td style="font-size:16px;padding-right:14px;">👤</td>
              <td style="color:#555;font-size:14px;min-width:130px;padding-right:14px;">{{ __('email.welcome.label_username') }}</td>
              <td style="color:#1a7a6e;font-size:14px;font-weight:600;">{{ $user->username }}</td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td style="padding:16px 18px;">
          <table cellpadding="0" cellspacing="0">
            <tr>
              <td style="font-size:16px;padding-right:14px;">✉️</td>
              <td style="color:#555;font-size:14px;min-width:130px;padding-right:14px;">{{ __('email.welcome.label_email') }}</td>
              <td style="color:#222;font-size:14px;font-weight:400;">{{ $user->email }}</td>
            </tr>
          </table>
        </td>
      </tr>
    </table>

    <!-- Security Box -->
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#eef6f5;border-radius:10px;margin-bottom:8px;">
      <tr>
        <td style="width:46px;padding:18px 0 18px 16px;vertical-align:top;">
          <div style="width:46px;height:46px;background:#d0eae8;border-radius:8px;text-align:center;line-height:46px;font-size:22px;">🛡️</div>
        </td>
        <td style="padding:18px 18px 18px 14px;vertical-align:top;">
          <div style="font-size:15px;font-weight:700;color:#1a1a1a;margin-bottom:5px;">{{ __('email.welcome.security_title') }}</div>
          <div style="font-size:13px;color:#555;line-height:1.5;">{{ __('email.welcome.security_text') }}</div>
        </td>
      </tr>
    </table>
  </div>

  <!-- FOOTER -->
  <table width="100%" cellpadding="0" cellspacing="0" style="background:#1a4a47;">
    <tr>
      <td style="padding:16px 0;text-align:center;">
        <a href="{{ config('app.url') }}/contact" style="color:#fff !important;font-size:13px;font-weight:500;text-decoration:none;padding:0 32px;display:inline-block;">
          ✉️ <span style="color:#fff !important;">{{ __('email.welcome.footer_contact') }}</span>
        </a>
        <span style="display:inline-block;width:1px;height:20px;background:#4a7a76;vertical-align:middle;"></span>
        <a href="{{ config('app.url') }}/politique-confidentialite" style="color:#fff !important;font-size:13px;font-weight:500;text-decoration:none;padding:0 32px;display:inline-block;">
          🛡️ <span style="color:#fff !important;">{{ __('email.welcome.footer_trust') }}</span>
        </a>
      </td>
    </tr>
  </table>

  <div style="text-align:center;padding:12px;font-size:12px;color:#999;">{{ __('email.welcome.footer_note') }}</div>

</div>
</body>
</html>
