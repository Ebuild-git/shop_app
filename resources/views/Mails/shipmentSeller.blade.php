<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>{{ __('email2.shipment.subject') }}</title>
</head>
<body style="margin:0;padding:0;background:#f4f4f4;font-family:'Segoe UI',Arial,sans-serif;color:#222;">
<div style="max-width:600px;margin:30px auto;background:#fff;border-radius:8px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,0.08);">

  <!-- HEADER -->
  <table width="100%" cellpadding="0" cellspacing="0" style="background:#1a4a47;">
    <tr>
      <td style="padding:18px 28px;">
        <img src="{{ config('app.url') }}/icons/logo11.png" alt="SHOPIN" style="height:44px;display:block;">
      </td>
      <td style="padding:18px 28px;text-align:right;">
        <table cellpadding="0" cellspacing="0" style="margin-left:auto;">
          <tr>
            <td style="padding-right:10px;font-size:24px;">🚚</td>
            <td>
              <div style="color:#fff;font-size:14px;font-weight:700;">{{ __('email2.shipment.header_title') }}</div>
              <div style="color:#a8cbc8;font-size:12px;">{{ __('email2.shipment.header_subtitle') }}</div>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <!-- BODY -->
  <div style="padding:30px 32px 28px;">

    <!-- Greeting -->
    <h2 style="font-size:24px;font-weight:800;color:#1a1a1a;margin:0 0 14px;">
      {{ __('email2.shipment.greeting') }} <span style="color:#1a7a6e;">{{ $recipient->username }}</span>,
    </h2>
    <p style="font-size:14px;color:#333;margin:0 0 24px;">
      {{ __('email2.shipment.seller_intro') }}
    </p>

    <!-- Shipment Info Card -->
    <table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e0e0e0;border-radius:10px;overflow:hidden;margin-bottom:20px;">
      <tr>
        <td style="padding:14px 18px;background:#fafafa;border-bottom:1px solid #e0e0e0;">
          <table cellpadding="0" cellspacing="0">
            <tr>
              <td style="width:44px;height:44px;background:#1a4a47;border-radius:8px;text-align:center;vertical-align:middle;font-size:20px;">
                📦
              </td>
              <td style="padding-left:14px;font-size:13px;font-weight:800;color:#1a1a1a;letter-spacing:0.5px;text-transform:uppercase;">
                {{ __('email2.shipment.details_title') }}
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td style="padding:0 18px 16px;">
          <div style="font-size:13px;color:#888;margin-bottom:4px;">
            {{ __('email2.shipment.shipment_label') }}
          </div>
          <div style="font-size:20px;font-weight:800;color:#1a7a6e;">
            {{ $shipmentId }}
          </div>
        </td>
      </tr>
      <tr>
        <td style="border-top:1px solid #eee;padding:16px 18px 0;">
          <table cellpadding="0" cellspacing="0" width="100%">
            <tr>
              <td style="width:22px;vertical-align:top;padding-top:2px;">
                <div style="width:18px;height:18px;background:#1a7a6e;border-radius:50%;color:#fff;font-size:12px;font-weight:800;text-align:center;line-height:18px;">!</div>
              </td>
              <td style="padding-left:10px;">
                <div style="font-size:14px;font-weight:800;color:#1a7a6e;text-transform:uppercase;letter-spacing:0.3px;margin-bottom:4px;">
                  {{ __('email2.shipment.next_step_title') }}
                </div>
                <div style="font-size:13px;color:#333;line-height:1.5;">
                  {{ __('email2.shipment.next_step_text') }}
                </div>
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td style="padding:18px;">
          <a href="{{ $labelUrl }}" target="_blank"
             style="display:block;background:#1a4a47;color:#fff !important;text-decoration:none;text-align:center;padding:14px 0;border-radius:8px;font-size:14px;font-weight:700;letter-spacing:0.3px;">
            🖨️ {{ __('email2.shipment.label_button') }}
          </a>
          <div style="text-align:center;font-size:12px;color:#999;margin-top:10px;">
            {{ __('email2.shipment.label_required_note') }}
          </div>
        </td>
      </tr>
    </table>
      <tr>
        <td style="padding:16px 18px;border-bottom:1px solid #eee;">
          <div style="font-size:12px;color:#888;text-transform:uppercase;letter-spacing:0.4px;margin-bottom:4px;">
            {{ __('email2.shipment.order_label') }}
          </div>
          <div style="font-size:18px;font-weight:800;color:#1a1a1a;">
            CMD-{{ $orderId }}
          </div>
        </td>
      </tr>
      <tr>
        <td style="padding:16px 18px;">
          <div style="font-size:12px;color:#888;text-transform:uppercase;letter-spacing:0.4px;margin-bottom:4px;">
            {{ __('email2.shipment.shipment_label') }}
          </div>
          <div style="font-size:18px;font-weight:800;color:#1a7a6e;">
            {{ $shipmentId }}
          </div>
        </td>
      </tr>
    </table>

    <!-- Pickup Reminder -->
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#eef6f5;border-radius:10px;margin-bottom:8px;">
      <tr>
        <td style="width:46px;padding:18px 0 18px 16px;vertical-align:top;">
          <div style="width:46px;height:46px;background:#d0eae8;border-radius:8px;text-align:center;line-height:46px;font-size:22px;">🕒</div>
        </td>
        <td style="padding:18px 18px 18px 14px;vertical-align:top;">
          <div style="font-size:15px;font-weight:700;color:#1a1a1a;margin-bottom:5px;">{{ __('email2.shipment.pickup_reminder_title') }}</div>
          <div style="font-size:13px;color:#555;line-height:1.5;">{{ __('email2.shipment.pickup_reminder_text') }}</div>
        </td>
      </tr>
    </table>

  </div>

  <!-- FOOTER -->
  <table width="100%" cellpadding="0" cellspacing="0" style="background:#1a4a47;">
    <tr>
      <td style="padding:16px 0;text-align:center;">
        <a href="#" style="color:#fff !important;font-size:13px;font-weight:500;text-decoration:none;padding:0 32px;display:inline-block;">
          ✉️ <span style="color:#fff !important;">{{ __('email2.shipment.footer_contact') }}</span>
        </a>
        <span style="display:inline-block;width:1px;height:20px;background:#4a7a76;vertical-align:middle;"></span>
        <a href="#" style="color:#fff !important;font-size:13px;font-weight:500;text-decoration:none;padding:0 32px;display:inline-block;">
          🛡️ <span style="color:#fff !important;">{{ __('email2.shipment.footer_trust') }}</span>
        </a>
      </td>
    </tr>
  </table>

  <div style="text-align:center;padding:12px;font-size:12px;color:#999;">{{ __('email2.shipment.footer_note') }}</div>

</div>
</body>
</html>
