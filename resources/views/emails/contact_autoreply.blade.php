<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
<meta charset="UTF-8">
</head>

<body style="font-family: Arial, sans-serif; background-color: #fafafa; padding: 20px; line-height: 1.6;">
  <div style="max-width: 600px; margin: auto; background: white; border-radius: 8px; padding: 20px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">

    <h2 style="text-align: center; font-weight: bold; font-size: 28px; letter-spacing: 1px;">
      <span style="color:#000000;">SHOP</span><span style="color:#008080;">IN</span>
    </h2>
    <p style="text-align: center;">🇲🇦</p>

    <hr style="border: none; border-top: 1px solid #ddd; margin: 20px 0;">


    @if(app()->getLocale() === 'fr')
      <p><strong>🇫🇷 Bonjour {{ $name }},</strong></p>
      <p>Merci de nous avoir contactés ! Nous avons bien reçu votre message concernant <strong>"{{ $subject }}"</strong>.</p>
      <p>Notre équipe vous répondra dans les plus brefs délais.</p>

      <p style="margin-top: 10px;">Belle journée à vous,<br>
        <strong><span style="color:#000000;">L’équipe SHOP</span><span style="color:#008080;">IN</span></strong>
      </p>


    @elseif(app()->getLocale() === 'en')
      <p><strong>🇬🇧 Hello {{ $name }},</strong></p>
      <p>Thank you for reaching out! We have received your message about <strong>"{{ $subject }}"</strong>.</p>
      <p>Our team will get back to you as soon as possible.</p>

      <p style="margin-top: 10px;">Have a great day,<br>
        <strong><span style="color:#000000;">The SHOP</span><span style="color:#008080;">IN</span> Team</strong>
      </p>


    @elseif(app()->getLocale() === 'ar')
      <p style="direction: rtl; text-align: right;"><strong>🇲🇦 مرحبًا {{ $name }}،</strong></p>
      <p style="direction: rtl; text-align: right;">شكرًا لتواصلكم معنا! لقد تلقينا رسالتكم بخصوص <strong>"{{ $subject }}"</strong>.</p>
      <p style="direction: rtl; text-align: right;">سوف يتواصل معكم فريقنا في أقرب وقت ممكن.</p>

      <p style="direction: rtl; text-align: right; margin-top: 10px;">يوم سعيد،<br>
        <strong><span style="color:#000000;">فريق SHOP</span><span style="color:#008080;">IN</span></strong>
      </p>

    @endif

  </div>
</body>
</html>
