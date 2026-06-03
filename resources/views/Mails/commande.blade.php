<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>{{ __('thank_you1') }}</title>
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
            <td style="padding-right:10px;font-size:24px;">🛡</td>
            <td>
              <div style="color:#fff;font-size:14px;font-weight:700;">
                {{ __('secure_marketplace') }}
              </div>
              <div style="color:#a8cbc8;font-size:12px;">
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
    <h2 style="font-size:24px;font-weight:800;color:#1a1a1a;margin:0 0 14px;">
      {{ __('thank_you2', ['username' => $user->username]) }}
    </h2>

    <p style="font-size:14px;color:#333;margin:0 0 6px;">
      {{ __('order_placed_success') }}
    </p>

    <p style="font-size:14px;color:#333;margin:0 0 20px;">
      {{ __('notify_delivery') }}
    </p>

    <!-- ORDER SUMMARY -->
    <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:14px;">
      <tr>
        <td style="width:44px;height:44px;background:#1a4a47;border-radius:8px;text-align:center;vertical-align:middle;font-size:20px;">
          🔒
        </td>
        <td style="padding-left:14px;font-size:13px;font-weight:800;color:#1a1a1a;text-transform:uppercase;">
          {{ __('order_summary2') }}
        </td>
      </tr>
    </table>

    @php $total = 0; @endphp

    <!-- PRODUCTS -->
    @foreach ($articles_panier as $article)

    <table width="100%" cellpadding="0" cellspacing="0"
   style="border:1px solid #e0e0e0;border-radius:10px;overflow:hidden;margin-bottom:14px;">
    <tr>

        <td style="width:140px;padding:12px;vertical-align:top;">
            <img src="{{ $article['photo'] }}"
                alt="{{ $article['titre'] }}"
                style="width:120px;height:120px;object-fit:cover;border-radius:6px;display:block;">
        </td>

        <td style="padding:14px;vertical-align:middle;">
            <div style="font-size:16px;font-weight:800;color:#1a1a1a;margin-bottom:8px;">
                {{ $article['titre'] }}
            </div>

            <div style="font-size:13px;color:#555;margin-bottom:4px;">
                {{ __('product_ref') }} :
                <span style="color:#1a7a6e;font-weight:700;">
                    P-{{ $article['id'] }}
                </span>
            </div>

            <div style="font-size:13px;color:#555;">
                {{ __('seller2') }} :
                <span style="color:#1a7a6e;font-weight:700;">
                    {{ $article['vendeur'] ?? 'N/A' }}
                </span>
            </div>
        </td>

        <!-- Price aligned to middle (same level as seller/ref lines) -->
        <td style="padding:14px;vertical-align:middle;text-align:right;white-space:nowrap;">
            <div style="font-size:18px;font-weight:800;color:#1a1a1a;">
                {{ $article['prix'] }} <span style="font-size:13px;color:#555;">{{ __('currency') }}</span>
            </div>
        </td>

    </tr>
</table>

    @php $total += $article['prix']; @endphp
    @endforeach

    <!-- TOTALS -->
    <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:18px;">
      <tr>
        <td style="padding:10px 0;font-size:14px;color:#555;">
          {{ __('shipping_fees') }}
        </td>
        <td style="text-align:right;font-size:14px;font-weight:700;color:#1a1a1a;">
          {{ number_format($totalShippingFees, 2, '.', '') }} {{ __('currency') }}
        </td>
      </tr>

      <tr>
        <td style="padding:10px 0;font-size:16px;font-weight:800;color:#1a1a1a;border-top:1px solid #eee;">
          {{ __('total2') }}
        </td>
        <td style="text-align:right;font-size:18px;font-weight:900;color:#1a7a6e;border-top:1px solid #eee;">
          {{ number_format($total + $totalShippingFees, 2, '.', '') }} {{ __('currency') }}
        </td>
      </tr>
    </table>

    <!-- PAYMENT -->
    <table width="100%" cellpadding="0" cellspacing="0"
           style="border:1px solid #e0e0e0;border-radius:10px;margin-bottom:14px;">
      <tr>
        <td style="width:60px;padding:18px 0 18px 18px;vertical-align:top;">
          <div style="width:44px;height:44px;background:#1a4a47;border-radius:8px;text-align:center;line-height:44px;font-size:20px;">
            💵
          </div>
        </td>
        <td style="padding:18px;">
          <div style="font-size:13px;color:#777;margin-bottom:4px;">
            {{ __('payment_method') }}
          </div>
          <div style="font-size:15px;font-weight:800;color:#1a1a1a;">
            {{ __('payment_method1') }}
          </div>
        </td>
      </tr>
    </table>

    <!-- DELIVERY -->
    <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:14px;">
      <tr>
        <td style="width:44px;font-size:22px;">📍</td>
        <td style="font-size:15px;font-weight:800;color:#1a1a1a;">
          {{ __('delivery_information') }}
        </td>
      </tr>
    </table>

    <table width="100%" cellpadding="0" cellspacing="0"
           style="border:1px solid #e0e0e0;border-radius:10px;overflow:hidden;margin-bottom:16px;">

      <!-- ADDRESS -->
      <tr>
        <td style="padding:14px 18px;border-bottom:1px solid #eee;">
          <strong style="display:block;margin-bottom:6px;">🏠 {{ __('delivery_address') }}</strong>
          <span style="font-size:13px;color:#555;line-height:1.6;">

            {!! $user->num_appartement ? 'App. ' . e($user->num_appartement) . ', ' : '' !!}
            {!! ($user->etage !== null && $user->etage !== '') ? __('floor') . ' ' . e($user->etage) . ', ' : '' !!}
            {!! $user->nom_batiment ? 'Résidence ' . e($user->nom_batiment) . ', ' : '' !!}
            {!! $user->rue ? 'Rue ' . e($user->rue) . ', ' : '' !!}
            {!! $user->address ? e($user->address) . ', ' : '' !!}

            {!! optional($user->city)->name
                    ? 'Ville ' . e($user->city->name) . ', '
                    : '' !!}

            {!! optional($user->region_info)->nom
                    ? e($user->region_info->nom)
                    : '' !!}

          </span>
        </td>
      </tr>

      <!-- REGION -->
      <tr>
        <td style="padding:14px 18px;border-bottom:1px solid #eee;">
          <strong>🗺️ {{ __('region') }}</strong><br>
          <span style="font-size:13px;color:#555;">
            {{ $user->region_info->nom ?? '-' }}
          </span>
        </td>
      </tr>

      <!-- PHONE -->
      <tr>
        <td style="padding:14px 18px;">
          <strong>📞 {{ __('phone_number') }}</strong><br>
          <a href="tel:{{ $user->phone_number }}"
             style="color:#1a7a6e;font-size:13px;font-weight:700;text-decoration:none;">
            {{ $user->phone_number ?? '-' }}
          </a>
        </td>
      </tr>

    </table>

    <!-- SAFETY -->
    <table width="100%" cellpadding="0" cellspacing="0"
           style="background:#eef6f5;border-radius:10px;">
      <tr>
        <td style="width:46px;padding:18px;font-size:22px;">🛡️</td>
        <td style="padding:18px 18px 18px 0;">
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
        <a href="mailto:{{ config('mail.from.address') }}"
           style="color:#fff;text-decoration:none;font-size:13px;font-weight:600;padding:0 20px;">
          ✉️ {{ __('contact_us1') }}
        </a>

        <span style="color:#4a7a76;">|</span>

        <a href="{{ config('app.url') }}/trust-safety"
           style="color:#fff;text-decoration:none;font-size:13px;font-weight:600;padding:0 20px;">
          🛡 {{ __('trust_safety') }}
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
