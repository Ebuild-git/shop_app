{{-- <!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>{{ __('email2.pickup_cancelled.subject') }}</title>
</head>
<body style="margin:0;padding:0;background:#f4f4f4;font-family:'Segoe UI',Arial,sans-serif;color:#222;">
<div style="max-width:600px;margin:30px auto;background:#fff;border-radius:8px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,0.08);">

  <!-- HEADER -->
  <table width="100%" cellpadding="0" cellspacing="0" style="background:#7a1a1a;">
    <tr>
      <td style="padding:18px 28px;">
        <img src="{{ config('app.url') }}/icons/logo11.png" alt="SHOPIN" style="height:44px;display:block;">
      </td>
      <td style="padding:18px 28px;text-align:right;">
        <table cellpadding="0" cellspacing="0" style="margin-left:auto;">
          <tr>
            <td style="padding-right:10px;font-size:24px;">🚫</td>
            <td>
              <div style="color:#fff;font-size:14px;font-weight:700;">{{ __('email2.pickup_cancelled.header_title') }}</div>
              <div style="color:#e0b8b8;font-size:12px;">{{ __('email2.pickup_cancelled.header_subtitle') }}</div>
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
      {{ __('email2.pickup_cancelled.greeting') }} {{ $recipient->username }},
    </h2>
    <p style="font-size:14px;color:#333;margin:0 0 24px;">
      {{ __('email2.pickup_cancelled.buyer_intro') }}
    </p>

    <!-- Order Reference -->
    <table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e0e0e0;border-radius:10px;overflow:hidden;margin-bottom:20px;">
      <tr>
        <td style="padding:16px 18px;">
          <div style="font-size:12px;color:#888;text-transform:uppercase;letter-spacing:0.4px;margin-bottom:4px;">
            {{ __('email2.pickup_cancelled.order_label') }}
          </div>
          <div style="font-size:19px;font-weight:800;color:#1a1a1a;">
            CMD-{{ $orderId }}
          </div>
        </td>
      </tr>
    </table>

    <!-- Cancelled shipments per vendor -->
    <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:12px;">
      <tr>
        <td style="width:44px;height:44px;background:#7a1a1a;border-radius:8px;text-align:center;vertical-align:middle;font-size:20px;">
          🚫
        </td>
        <td style="padding-left:14px;font-size:13px;font-weight:800;color:#1a1a1a;letter-spacing:0.5px;text-transform:uppercase;">
          {{ __('email2.pickup_cancelled.tracking_title') }}
        </td>
      </tr>
    </table>

    <table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e0e0e0;border-radius:10px;overflow:hidden;margin-bottom:20px;">
      <tr>
        <td style="padding:14px 18px;">
          <table width="100%" cellpadding="0" cellspacing="0">
            <tr>
              <td style="vertical-align:middle;">
                <div style="font-size:12px;color:#888;text-transform:uppercase;letter-spacing:0.4px;margin-bottom:3px;">
                  {{ __('email2.pickup_cancelled.from_seller_label') }}
                </div>
                <div style="font-size:14px;font-weight:700;color:#1a1a1a;">
                  {{ $items->first()->vendor->username ?? ($items->first()->vendor->firstname . ' ' . $items->first()->vendor->lastname) }}
                </div>
              </td>
              <td style="text-align:right;vertical-align:middle;">
                <div style="font-size:12px;color:#888;text-transform:uppercase;letter-spacing:0.4px;margin-bottom:3px;">
                  {{ __('email2.pickup_cancelled.shipment_label') }}
                </div>
                <div style="font-size:14px;font-weight:800;color:#7a1a1a;text-decoration:line-through;">
                  {{ $shipmentId }}
                </div>
              </td>
            </tr>
          </table>
        </td>
      </tr>
    </table>

    <!-- Next Steps -->
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#f5eeee;border-radius:10px;margin-bottom:8px;">
      <tr>
        <td style="width:46px;padding:18px 0 18px 16px;vertical-align:top;">
          <div style="width:46px;height:46px;background:#ead0d0;border-radius:8px;text-align:center;line-height:46px;font-size:22px;">ℹ️</div>
        </td>
        <td style="padding:18px 18px 18px 14px;vertical-align:top;">
          <div style="font-size:15px;font-weight:700;color:#1a1a1a;margin-bottom:5px;">{{ __('email2.pickup_cancelled.notice_title') }}</div>
          <div style="font-size:13px;color:#555;line-height:1.5;">{{ __('email2.pickup_cancelled.notice_text') }}</div>
        </td>
      </tr>
    </table>

  </div>

  <!-- FOOTER -->
  <table width="100%" cellpadding="0" cellspacing="0" style="background:#7a1a1a;">
    <tr>
      <td style="padding:16px 0;text-align:center;">
        <a href="#" style="color:#fff !important;font-size:13px;font-weight:500;text-decoration:none;padding:0 32px;display:inline-block;">
          ✉️ <span style="color:#fff !important;">{{ __('email2.pickup_cancelled.footer_contact') }}</span>
        </a>
        <span style="display:inline-block;width:1px;height:20px;background:#a04a4a;vertical-align:middle;"></span>
        <a href="#" style="color:#fff !important;font-size:13px;font-weight:500;text-decoration:none;padding:0 32px;display:inline-block;">
          🛡️ <span style="color:#fff !important;">{{ __('email2.pickup_cancelled.footer_trust') }}</span>
        </a>
      </td>
    </tr>
  </table>

  <div style="text-align:center;padding:12px;font-size:12px;color:#999;">{{ __('email2.pickup_cancelled.footer_note') }}</div>

</div>
</body>
</html> --}}
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>{{ __('email2.pickup_cancelled_buyer.subject') }}</title>
</head>
<body style="margin:0;padding:0;background:#f4f4f4;font-family:'Segoe UI',Arial,sans-serif;color:#222;">
<div style="max-width:600px;margin:30px auto;background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,0.06);border:1px solid #eee;">

  <!-- HEADER -->
  <div style="text-align:center;padding:26px 0 16px;">
    <div style="font-size:30px;font-weight:800;letter-spacing:-0.5px;">
      <span style="color:#1a1a1a;">SHOP</span><span style="color:#0d6e6e;">IN</span>
    </div>
  </div>
  <div style="height:3px;background:linear-gradient(90deg,#0d6e6e,#1a1a1a);"></div>

  <!-- BODY -->
  <div style="padding:30px 32px 10px;">

    <h2 style="font-size:22px;font-weight:800;color:#1a1a1a;margin:0 0 14px;">
      {{ __('email2.pickup_cancelled_buyer.greeting') }}
    </h2>
    <p style="font-size:14px;color:#333;line-height:1.5;margin:0 0 22px;">
      {{ __('email2.pickup_cancelled_buyer.intro') }}
    </p>

    <!-- Cancellation details card -->
    <table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e6e6e6;border-radius:12px;overflow:hidden;margin-bottom:22px;">
      <tr>
        <td style="padding:16px 18px;border-bottom:1px solid #eee;">
          <table cellpadding="0" cellspacing="0">
            <tr>
              <td style="width:34px;height:34px;background:#c0392b;border-radius:50%;text-align:center;vertical-align:middle;color:#fff;font-size:16px;">
                &#8856;
              </td>
              <td style="padding-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }}:12px;font-size:15px;font-weight:800;color:#1a1a1a;text-transform:uppercase;">
                {{ __('email2.pickup_cancelled_buyer.details_title') }}
              </td>
            </tr>
          </table>
        </td>
      </tr>
      <tr>
        <td style="padding:16px 18px;border-bottom:1px solid #eee;">
          <div style="font-size:11px;color:#999;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:4px;">
            {{ __('email2.pickup_cancelled_buyer.order_label') }}
          </div>
          <div style="font-size:19px;font-weight:800;color:#1a1a1a;">
            CMD-{{ $orderId }}
          </div>
        </td>
      </tr>
      <tr>
        <td style="padding:16px 18px;">
          <div style="font-size:11px;color:#999;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:4px;">
            {{ __('email2.pickup_cancelled_buyer.shipment_label') }}
          </div>
          <div style="font-size:16px;font-weight:700;">
            <a href="#" style="color:#0d6e6e;text-decoration:underline;">{{ $shipmentId }}</a>
          </div>
        </td>
      </tr>
    </table>

    <!-- Info box -->
    <table width="100%" cellpadding="0" cellspacing="0" style="background:#faeeee;border-radius:12px;margin-bottom:8px;">
      <tr>
        <td style="width:52px;padding:18px 0 18px 16px;vertical-align:top;">
          <div style="width:34px;height:34px;background:#0d6e6e;border-radius:50%;text-align:center;line-height:34px;color:#fff;font-size:16px;">i</div>
        </td>
        <td style="padding:18px 18px 18px 6px;vertical-align:top;">
          <div style="font-size:15px;font-weight:800;color:#1a1a1a;margin-bottom:8px;">
            {{ __('email2.pickup_cancelled_buyer.info_title') }}
          </div>
          <div style="font-size:13px;color:#444;line-height:1.6;">
            {{ __('email2.pickup_cancelled_buyer.info_line1') }}<br>
            {{ __('email2.pickup_cancelled_buyer.info_line2') }}<br><br>
            {{ __('email2.pickup_cancelled_buyer.info_line3') }}
          </div>
        </td>
      </tr>
    </table>
  </div>

  <!-- FOOTER -->
  <div style="text-align:center;padding:20px 20px 6px;border-top:1px solid #eee;margin-top:10px;">
    <div style="color:#0d6e6e;font-size:16px;margin-bottom:4px;">&#128737;</div>
    <div style="font-size:14px;font-weight:800;color:#1a1a1a;">{{ __('email2.pickup_cancelled_buyer.security_title') }}</div>
    <div style="font-size:12px;color:#888;margin-top:2px;">{{ __('email2.pickup_cancelled_buyer.security_text') }}</div>
  </div>

  <div style="text-align:center;padding:14px 0;border-top:1px solid #eee;">
    <a href="/contact" style="color:#0d6e6e;font-size:13px;font-weight:600;text-decoration:none;padding:0 20px;">
      &#9993; {{ __('email2.pickup_cancelled_buyer.footer_contact') }}
    </a>
    <span style="display:inline-block;width:1px;height:14px;background:#ddd;vertical-align:middle;"></span>
    <a href="https://shopin.ma/" style="color:#0d6e6e;font-size:13px;font-weight:600;text-decoration:none;padding:0 20px;">
      &#127760; {{ __('email2.pickup_cancelled_buyer.footer_trust') }}
    </a>
  </div>

  <div style="text-align:center;padding:10px 20px 20px;font-size:12px;color:#aaa;">
    {{ __('email2.pickup_cancelled_buyer.footer_note') }}<br>
    {{ __('email2.pickup_cancelled_buyer.copyright', ['year' => date('Y')]) }}
  </div>

</div>
</body>
</html>
