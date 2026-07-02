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
        <img src="{{ config('app.url') }}/icons/logo.png" alt="{{ config('app.name') }}" style="height:44px;display:block;">
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
      {{ __('thank_you2', ['username' => $user->username]) }}
    </h2>

    <p style="font-size:14px;color:#333;margin:0 0 6px;">
      {{ __('order_placed_success') }}
    </p>

    <p style="font-size:14px;color:#333;margin:0 0 20px;">
      {{ __('notify_delivery') }}
    </p>

    <!-- ORDER SUMMARY -->
    <table width="100%" cellpadding="0" cellspacing="0"
           style="border:1px solid #e0e0e0;border-radius:10px;overflow:hidden;margin-bottom:14px;">

      <!-- Section header -->
      <tr>
        <td style="padding:14px 18px;background:#fafafa;border-bottom:1px solid #e0e0e0;">
          <table cellpadding="0" cellspacing="0">
            <tr>
              <td style="width:44px;height:44px;background:#1a4a47;border-radius:8px;text-align:center;vertical-align:middle;">
                <span style="color:#fff;font-size:18px;font-weight:900;font-family:Georgia,serif;line-height:44px;">&#35;</span>
              </td>
              <td style="padding-left:14px;font-size:14px;font-weight:800;color:#1a1a1a;text-transform:uppercase;letter-spacing:0.5px;">
                {{ __('order_summary2') }}
                @if(!empty($orderId))
                    <span style="color:#1a7a6e;">— CMD-{{ $orderId }}</span>
                @endif
              </td>
            </tr>
          </table>
        </td>
      </tr>

      @php
        $total = 0;
        $groupedBySeller = collect($articles_panier)->groupBy('vendeur');
      @endphp

      @foreach ($groupedBySeller as $sellerName => $sellerArticles)

        <!-- Seller header -->
        <tr>
          <td style="padding:12px 18px 6px 18px;border-top:1px solid #f0f0f0;background:#f9fefe;">
            <table cellpadding="0" cellspacing="0">
              <tr>
                <td style="vertical-align:middle;padding-right:8px;">
                  <div style="width:24px;height:24px;background:#1a7a6e;border-radius:4px;text-align:center;line-height:24px;font-size:11px;font-weight:900;color:#fff;font-family:Arial,sans-serif;">
                    V
                  </div>
                </td>
                <td style="font-size:13px;font-weight:800;color:#1a7a6e;vertical-align:middle;">
                  {{ __('seller_label') }}: {{ $sellerName }}
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <!-- Articles for this seller -->
        @foreach ($sellerArticles as $article)
        <tr>
          <td style="padding:8px 14px 10px 14px;">
            <table width="100%" cellpadding="0" cellspacing="0"
                   style="border:1px solid #e0e0e0;border-radius:10px;overflow:hidden;">
              <tr>
                <!-- Product image -->
                <td style="width:140px;padding:12px;vertical-align:top;">
                  <img src="{{ $article['photo'] }}"
                       alt="{{ $article['titre'] }}"
                       style="width:120px;height:120px;object-fit:cover;border-radius:6px;display:block;">
                </td>
                <!-- Product info -->
                <td style="padding:14px 10px;vertical-align:top;">
                  <div style="font-size:15px;font-weight:800;color:#1a1a1a;margin-bottom:10px;">
                    {{ $article['titre'] }}
                  </div>
                  <div style="font-size:11px;color:#888;text-transform:uppercase;letter-spacing:0.4px;margin-bottom:2px;">
                    {{ __('product_ref') }}
                  </div>
                  <div style="font-size:13px;color:#1a7a6e;font-weight:700;">
                    P-{{ $article['id'] }}
                  </div>
                </td>
                <!-- Price -->
                <td style="padding:14px 14px 14px 0;vertical-align:top;text-align:right;white-space:nowrap;">
                  <div style="font-size:11px;color:#888;text-transform:uppercase;letter-spacing:0.4px;margin-bottom:4px;margin-top:2px;">
                    {{ __('article_price') }}
                  </div>
                  <div style="font-size:17px;font-weight:800;color:#1a1a1a;">
                    {{ $article['prix'] }}&nbsp;<span style="font-size:12px;color:#555;font-weight:400;">{{ __('currency') }}</span>
                  </div>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        @php $total += $article['prix']; @endphp
        @endforeach

        <!-- Delivery fee for this seller -->
        @php
          $sellerDeliveryFee = $sellerArticles->first()['delivery_fee'] ?? null;
        @endphp
        @if (!is_null($sellerDeliveryFee))
        <tr>
          <td style="padding:0 18px 14px 18px;">
            <table width="100%" cellpadding="0" cellspacing="0"
                   style="border-top:1px dashed #d0d0d0;">
              <tr>
                <td style="padding-top:10px;vertical-align:middle;">
                  <table cellpadding="0" cellspacing="0">
                    <tr>
                      <td style="vertical-align:middle;padding-right:7px;">
                        <div style="width:22px;height:22px;background:#f0f0f0;border-radius:4px;text-align:center;line-height:22px;font-size:10px;font-weight:900;color:#555;font-family:Arial,sans-serif;">
                          &gt;
                        </div>
                      </td>
                      <td style="font-size:13px;color:#555;vertical-align:middle;">
                        {{ __('delivery_fees') }} ({{ __('from') }} <strong style="color:#333;">{{ $sellerName }}</strong>)
                      </td>
                    </tr>
                  </table>
                </td>
                <td style="padding-top:10px;text-align:right;font-size:13px;font-weight:700;color:#1a1a1a;white-space:nowrap;">
                  {{ number_format($sellerDeliveryFee, 2, ',', '') }}&nbsp;{{ __('currency') }}
                </td>
              </tr>
            </table>
          </td>
        </tr>
        @endif

      @endforeach

    </table>
    <!-- END ORDER SUMMARY -->

    <!-- TOTALS -->
    <table width="100%" cellpadding="0" cellspacing="0"
           style="border:1px solid #e0e0e0;border-radius:10px;overflow:hidden;margin-bottom:18px;">
      <tr>
        <td style="padding:12px 18px;font-size:14px;color:#555;border-bottom:1px solid #eee;">
          {{ __('shipping_fees') }}
        </td>
        <td style="padding:12px 18px;text-align:right;font-size:14px;font-weight:700;color:#1a1a1a;border-bottom:1px solid #eee;white-space:nowrap;">
          {{ number_format($totalShippingFees, 2, ',', '') }}&nbsp;{{ __('currency') }}
        </td>
      </tr>
      <tr style="background:#f9fefe;">
        <td style="padding:14px 18px;font-size:16px;font-weight:800;color:#1a1a1a;">
          {{ __('total2') }}
        </td>
        <td style="padding:14px 18px;text-align:right;font-size:19px;font-weight:900;color:#1a7a6e;white-space:nowrap;">
          {{ number_format($total + $totalShippingFees, 2, ',', '') }}&nbsp;{{ __('currency') }}
        </td>
      </tr>
    </table>

    <!-- PAYMENT METHOD -->
    <table width="100%" cellpadding="0" cellspacing="0"
           style="border:1px solid #e0e0e0;border-radius:10px;overflow:hidden;margin-bottom:14px;">
      <tr>
        <td style="width:60px;padding:16px 0 16px 16px;vertical-align:middle;">
          <div style="width:44px;height:44px;background:#1a4a47;border-radius:8px;text-align:center;line-height:44px;font-size:11px;font-weight:900;color:#fff;font-family:Arial,sans-serif;">
            PAY
          </div>
        </td>
        <td style="padding:16px;vertical-align:middle;">
          <span style="font-size:14px;font-weight:800;color:#1a1a1a;">
            {{ __('payment_method') }}
          </span>
        </td>
        <td style="padding:16px;text-align:right;vertical-align:middle;white-space:nowrap;">
          <span style="font-size:14px;font-weight:700;color:#1a7a6e;">
            {{ __('payment_method1') }}
          </span>
        </td>
      </tr>
    </table>

    <!-- DELIVERY INFORMATION TITLE -->
    <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:12px;">
      <tr>
        <td style="vertical-align:middle;padding-right:10px;width:28px;">
          <div style="width:24px;height:24px;background:#1a4a47;border-radius:4px;text-align:center;line-height:24px;font-size:11px;font-weight:900;color:#fff;font-family:Arial,sans-serif;">
            &gt;
          </div>
        </td>
        <td style="font-size:15px;font-weight:800;color:#1a1a1a;vertical-align:middle;">
          {{ __('delivery_information') }}
        </td>
      </tr>
    </table>

    <!-- DELIVERY DETAILS -->
    <table width="100%" cellpadding="0" cellspacing="0"
           style="border:1px solid #e0e0e0;border-radius:10px;overflow:hidden;margin-bottom:16px;">

      <!-- Address -->
      <tr>
        <td style="width:56px;padding:14px 0 14px 16px;vertical-align:middle;">
          <div style="width:30px;height:30px;background:#f0f0f0;border-radius:6px;text-align:center;line-height:30px;font-size:10px;font-weight:900;color:#1a4a47;font-family:Arial,sans-serif;">
            ADR
          </div>
        </td>
        <td style="padding:14px 8px;vertical-align:middle;">
          <strong style="font-size:13px;color:#1a1a1a;">{{ __('delivery_address') }}</strong>
        </td>
        <td style="padding:14px 16px 14px 0;vertical-align:middle;text-align:right;">
          <span style="font-size:13px;color:#555;line-height:1.6;">
            {!! $user->num_appartement ? 'App. ' . e($user->num_appartement) . ', ' : '' !!}
            {!! ($user->etage !== null && $user->etage !== '') ? __('floor') . ' ' . e($user->etage) . ', ' : '' !!}
            {!! $user->nom_batiment ? 'Res. ' . e($user->nom_batiment) . ', ' : '' !!}
            {!! $user->rue ? e($user->rue) . ', ' : '' !!}
            {!! $user->address ? e($user->address) . ', ' : '' !!}
            {!! optional($user->city)->name ? e($user->city->name) . ', ' : '' !!}
            {!! optional($user->region_info)->nom ? e($user->region_info->nom) : '' !!}
          </span>
        </td>
      </tr>

      <!-- Region -->
      <tr style="border-top:1px solid #eee;">
        <td style="width:56px;padding:14px 0 14px 16px;vertical-align:middle;">
          <div style="width:30px;height:30px;background:#f0f0f0;border-radius:6px;text-align:center;line-height:30px;font-size:10px;font-weight:900;color:#1a4a47;font-family:Arial,sans-serif;">
            REG
          </div>
        </td>
        <td style="padding:14px 8px;vertical-align:middle;">
          <strong style="font-size:13px;color:#1a1a1a;">{{ __('region') }}</strong>
        </td>
        <td style="padding:14px 16px 14px 0;vertical-align:middle;text-align:right;">
          <span style="font-size:13px;color:#555;">
            {{ $user->region_info->nom ?? '-' }}
          </span>
        </td>
      </tr>

      <!-- Phone -->
      <tr style="border-top:1px solid #eee;">
        <td style="width:56px;padding:14px 0 14px 16px;vertical-align:middle;">
          <div style="width:30px;height:30px;background:#f0f0f0;border-radius:6px;text-align:center;line-height:30px;font-size:10px;font-weight:900;color:#1a4a47;font-family:Arial,sans-serif;">
            TEL
          </div>
        </td>
        <td style="padding:14px 8px;vertical-align:middle;">
          <strong style="font-size:13px;color:#1a1a1a;">{{ __('phone_number') }}</strong>
        </td>
        <td style="padding:14px 16px 14px 0;vertical-align:middle;text-align:right;">
          <a href="tel:{{ $user->phone_number }}"
             style="color:#1a7a6e;font-size:13px;font-weight:700;text-decoration:none;">
            {{ $user->phone_number ?? '-' }}
          </a>
        </td>
      </tr>

    </table>

    <!-- SAFETY NOTICE -->
    <table width="100%" cellpadding="0" cellspacing="0"
           style="background:#eef6f5;border-radius:10px;border:1px solid #c8e6e3;">
      <tr>
        <td style="width:60px;padding:18px 0 18px 18px;vertical-align:middle;">
          <div style="width:38px;height:38px;background:#1a7a6e;border-radius:8px;text-align:center;line-height:38px;font-size:12px;font-weight:900;color:#fff;font-family:Arial,sans-serif;">
            OK
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
  <!-- END BODY -->

  <!-- FOOTER -->
  <table width="100%" cellpadding="0" cellspacing="0" style="background:#1a4a47;">
    <tr>
      <td style="padding:16px;text-align:center;">
        <a href="mailto:{{ config('mail.from.address') }}"
           style="color:#fff;text-decoration:none;font-size:13px;font-weight:600;padding:0 16px;">
          {{ __('contact_us1') }}
        </a>
        <span style="color:#4a7a76;font-size:16px;">|</span>
        <a href="{{ config('app.url') }}/trust-safety"
           style="color:#fff;text-decoration:none;font-size:13px;font-weight:600;padding:0 16px;">
          {{ __('trust_safety') }}
        </a>
      </td>
    </tr>
  </table>

  <!-- Fine print -->
  <div style="text-align:center;padding:12px;font-size:12px;color:#999;">
    {{ __('automated_message') }}
  </div>

</div>
</body>
</html>
