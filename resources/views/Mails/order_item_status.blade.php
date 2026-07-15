<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>{{ $type === 'cancelled' ? __('oic_cancelled_title') : __('oic_restored_title') }}</title>
</head>
<body style="margin:0;padding:0;background:#f4f4f4;font-family:'Segoe UI',Arial,sans-serif;color:#222;">

<div style="max-width:600px;margin:30px auto;background:#fff;border-radius:8px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,0.08);">

  <!-- HEADER -->
  <table width="100%" cellpadding="0" cellspacing="0" style="background:#1a4a47;">
    <tr>
      <td style="padding:18px 28px;">
        <img src="{{ config('app.url') }}/icons/logo11.png" alt="{{ config('app.name') }}" style="height:44px;display:block;">
      </td>
      <td style="padding:18px 28px;text-align:right;">
        <table cellpadding="0" cellspacing="0" style="margin-left:auto;">
          <tr>
            <td style="padding-right:10px;vertical-align:middle;">
              <div style="width:32px;height:32px;background:#2d6b66;border-radius:6px;text-align:center;line-height:32px;font-size:13px;font-weight:900;color:#a8cbc8;font-family:Georgia,serif;">
                S
              </div>
            </td>
            <td style="vertical-align:middle;">
              <div style="color:#fff;font-size:14px;font-weight:700;line-height:1.3;">
                {{ __('secure_marketplace') }}
              </div>
              <div style="color:#a8cbc8;font-size:12px;line-height:1.3;">
                {{ __('buy_sell_anonymous') }}
              </div>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>

  <!-- BODY -->
  <div style="padding:30px 32px 28px;">

    <!-- Greeting -->
    <h2 style="font-size:24px;font-weight:800;color:#1a7a6e;margin:0 0 14px;">
      {{ __('oic_hello', ['username' => $buyer->username]) }}
    </h2>

    <!-- Title -->
    <h3 style="font-size:18px;font-weight:700;color:#1a1a1a;margin:0 0 16px;">
      {{ $type === 'cancelled' ? __('oic_cancelled_title') : __('oic_restored_title') }}
    </h3>

    <!-- Message -->
    <p style="font-size:14px;color:#333;margin:0 0 24px;line-height:1.6;">
      {!! $type === 'cancelled'
        ? __('oic_cancelled_body', [
            'shipment_id' => 'CMD-' . $order->id,
            'post_id' => $post->id,
            'post_title' => $post->titre,
            'app_url' => config('app.url')
          ])
        : __('oic_restored_body', [
            'shipment_id' => 'CMD-' . $order->id,
            'post_id' => $post->id,
            'post_title' => $post->titre,
            'app_url' => config('app.url')
          ])
      !!}
    </p>

    <!-- ORDER ITEM CARD -->
    <table width="100%" cellpadding="0" cellspacing="0"
           style="border:1px solid #e0e0e0;border-radius:10px;overflow:hidden;margin-bottom:18px;">

      <!-- Section header -->
      <tr>
        <td style="padding:14px 18px;background:#fafafa;border-bottom:1px solid #e0e0e0;">
          <table cellpadding="0" cellspacing="0">
            <tr>
              <td style="width:44px;height:44px;background:#1a4a47;border-radius:8px;text-align:center;vertical-align:middle;">
                <span style="color:#fff;font-size:18px;font-weight:900;font-family:Georgia,serif;line-height:44px;">📋</span>
              </td>
              <td style="padding-left:14px;font-size:14px;font-weight:800;color:#1a1a1a;text-transform:uppercase;letter-spacing:0.5px;">
                {{ __('order_summary3') }} — CMD-{{ $order->id }}
              </td>
            </tr>
          </table>
        </td>
      </tr>

      <!-- Item row -->
      @if($post)
      <tr>
        <td style="padding:10px 14px;">
          <table width="100%" cellpadding="0" cellspacing="0"
                 style="border:1px solid #e0e0e0;border-radius:10px;overflow:hidden;">
            <tr>
              <!-- Product image -->
              <td style="width:140px;padding:12px;vertical-align:top;">
                <img src="{{ $postImage }}"
                     alt="{{ $post->titre }}"
                     style="width:120px;height:120px;object-fit:cover;border-radius:6px;display:block;">
              </td>
              <!-- Product info -->
              <td style="padding:14px 10px;vertical-align:top;">
                <a href="{{ config('app.url') }}/post/{{ $post->id }}"
                   style="font-size:15px;font-weight:800;color:#008080;text-decoration:underline;display:block;margin-bottom:10px;">
                  {{ $post->titre }}
                </a>
                <div style="font-size:11px;color:#888;text-transform:uppercase;letter-spacing:0.4px;margin-bottom:2px;">
                  {{ __('product_ref') }}
                </div>
                <div style="font-size:13px;color:#1a7a6e;font-weight:700;">
                  P-{{ $post->id }}
                </div>
                <div style="font-size:11px;color:#888;text-transform:uppercase;letter-spacing:0.4px;margin-bottom:2px;">
                {{ __('article_price') }}
                </div>
                <div style="font-size:15px;font-weight:800;color:#1a1a1a;">
                {{ $audience === 'seller' ? $post->calculateGain() : $post->getPrix() }}&nbsp;<span style="font-size:12px;color:#555;font-weight:400;">{{ __('currency') }}</span>
                </div>
              </td>
              <!-- Status badge -->
              <td style="padding:14px 14px 14px 0;vertical-align:top;text-align:right;white-space:nowrap;">
                @if($type === 'cancelled')
                  <span style="background:#fdecea;color:#c0392b;font-size:12px;font-weight:700;padding:4px 10px;border-radius:20px;display:inline-block;">
                    {{ __('oic_item_removed_badge') }}
                  </span>
                @else
                  <span style="background:#eaf6f4;color:#1a7a6e;font-size:12px;font-weight:700;padding:4px 10px;border-radius:20px;display:inline-block;">
                    {{ __('oic_item_restored_badge') }}
                  </span>
                @endif
              </td>
            </tr>
          </table>
        </td>
      </tr>
      @endif

    </table>

    <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:20px;">
    <tr>
        <td style="text-align:center;">
        @if($audience === 'seller')
            <a href="{{ config('app.url') }}/mes-publication?type=vente"
            style="display:inline-block;background:#008080;color:#fff;font-size:14px;font-weight:700;padding:12px 28px;border-radius:8px;text-decoration:none;">
            {{ __('oic_view_sales_btn') }}
            </a>
        @else
            <a href="{{ config('app.url') }}/mes-achats"
            style="display:inline-block;background:#008080;color:#fff;font-size:14px;font-weight:700;padding:12px 28px;border-radius:8px;text-decoration:none;">
            {{ __('oic_view_orders_btn') }}
            </a>
        @endif
        </td>
    </tr>
    </table>

    <!-- SAFETY NOTICE -->
    <table width="100%" cellpadding="0" cellspacing="0"
           style="background:#eef6f5;border-radius:10px;border:1px solid #c8e6e3;">
      <tr>
        <td style="width:60px;padding:18px 0 18px 18px;vertical-align:middle;">
          <div style="width:38px;height:38px;background:#1a7a6e;border-radius:8px;text-align:center;line-height:38px;font-size:12px;font-weight:900;color:#fff;font-family:Arial,sans-serif;">
            🛡️
          </div>
        </td>
        <td style="padding:18px 18px 18px 12px;">
          <div style="font-size:14px;font-weight:800;color:#1a1a1a;margin-bottom:4px;">
            {{ __('safety_title') }}
          </div>
          <div style="font-size:13px;color:#555;line-height:1.5;">
            {{ __('safety_body') }}
          </div>
        </td>
      </tr>
    </table>

  </div>

  <!-- FOOTER -->
  <table width="100%" cellpadding="0" cellspacing="0" style="background:#1a4a47;">
    <tr>
      <td style="padding:16px;text-align:center;">
        <a href="{{ config('app.url') }}/contact"
           style="color:#fff;text-decoration:none;font-size:13px;font-weight:600;padding:0 16px;">
          {{ __('contact_us1') }}
        </a>
        <span style="color:#4a7a76;font-size:16px;">|</span>
        <a href="{{ config('app.url') }}/politique-confidentialite"
           style="color:#fff;text-decoration:none;font-size:13px;font-weight:600;padding:0 16px;">
          {{ __('trust_safety') }}
        </a>
      </td>
    </tr>
  </table>

  <div style="text-align:center;padding:12px;font-size:12px;color:#999;">
    {{ __('automated_message') }}
  </div>

</div>
</body>
</html>
