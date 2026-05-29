<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>{{ __('email2.order.title') }}</title>
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
              <div style="color:#fff;font-size:14px;font-weight:700;">{{ __('email2.order.secure_title') }}</div>
              <div style="color:#a8cbc8;font-size:12px;">{{ __('email2.order.secure_subtitle') }}</div>
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
      {{ __('email2.order.greeting') }} <span style="color:#1a7a6e;">{{ $seller->username }}</span>,
    </h2>
    <p style="font-size:14px;color:#333;margin:0 0 6px;">
      {{ __('email2.order.intro_line1') }} <a href="#" style="color:#1a7a6e;font-weight:700;text-decoration:none;">{{ $buyerPseudo }}</a>.
    </p>
    <p style="font-size:14px;color:#333;margin:0 0 6px;">{{ __('email2.order.intro_line2') }}</p>
    <p style="font-size:14px;color:#333;margin:0 0 24px;">{{ __('email2.order.intro_line3') }}</p>

    <!-- Order Summary Section -->
    <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:20px;">
      <tr>
        <td style="width:44px;height:44px;background:#1a4a47;border-radius:8px;text-align:center;vertical-align:middle;font-size:20px;width:44px;">
          📋
        </td>
        <td style="padding-left:14px;font-size:13px;font-weight:800;color:#1a1a1a;letter-spacing:0.5px;text-transform:uppercase;">
          {{ __('email2.order.summary_title') }}
        </td>
      </tr>
    </table>

    <!-- Items Loop -->
    @foreach($articlesWithGain as $index => $item)
    <table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e0e0e0;border-radius:10px;overflow:hidden;margin-bottom:16px;">
      <tr>
        <!-- Product Image -->
        <td style="width:160px;padding:12px;vertical-align:top;">
          <img src="{{ $item['photo'] }}" alt="{{ $item['titre'] }}" style="width:140px;height:140px;object-fit:cover;border-radius:6px;display:block;">
        </td>
        <!-- Product Details -->
        <td style="padding:16px 16px 16px 8px;vertical-align:top;">
          <div style="display:inline-block;background:#1a4a47;color:#fff;font-size:11px;font-weight:700;padding:3px 10px;border-radius:4px;margin-bottom:10px;">
            {{ __('email2.order.article') }} {{ $index + 1 }}
          </div>
          <div style="font-size:18px;font-weight:800;color:#1a1a1a;margin-bottom:10px;">{{ $item['titre'] }}</div>
          @if(!empty($orderId))
            <div style="font-size:13px;color:#555;margin-bottom:12px;">
                {{ __('email2.order.order_id') }}
                <span style="color:#1a7a6e;font-weight:700;">
                    CMD - {{ $orderId }}
                </span>
            </div>
        @endif
          <div style="font-size:13px;color:#555;margin-bottom:4px;">{{ __('email2.order.amount_label') }}</div>
          <div style="font-size:24px;font-weight:800;color:#1a1a1a;">
            {{ $item['gain'] }} <span style="font-size:14px;font-weight:600;color:#555;">{{ __('email2.order.currency') }}</span>
          </div>
        </td>
      </tr>
    </table>
    @endforeach

    <!-- Payment Method -->
    <table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e0e0e0;border-radius:10px;margin-bottom:16px;">
      <tr>
        <td style="width:60px;padding:18px 0 18px 18px;vertical-align:top;">
          <div style="width:44px;height:44px;background:#1a4a47;border-radius:8px;text-align:center;line-height:44px;font-size:20px;">💳</div>
        </td>
        <td style="padding:18px 18px 18px 14px;vertical-align:top;">
          <div style="font-size:13px;color:#777;margin-bottom:2px;">{{ __('email2.order.payment_method_label') }}</div>
          <div style="font-size:15px;font-weight:800;color:#1a1a1a;margin-bottom:8px;">{{ __('email2.order.payment_method_value') }}</div>
          <div style="font-size:13px;color:#555;line-height:1.6;margin-bottom:6px;">{{ __('email2.order.payment_desc') }}</div>
          <div style="font-size:13px;color:#555;line-height:1.6;">
           {!! __('email2.order.payment_rib', [
                'url' => auth()->check()
                    ? route('mes_informations', ['section' => 'cord'])
                    : route('login')
            ]) !!}
          </div>
        </td>
      </tr>
    </table>

    <!-- Pickup Info -->
    <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:16px;">
      <tr>
        <td style="width:44px;font-size:22px;vertical-align:middle;">📍</td>
        <td style="font-size:15px;font-weight:700;color:#1a1a1a;padding-left:10px;">{{ __('email2.order.pickup_title') }}</td>
      </tr>
    </table>

    <table width="100%" cellpadding="0" cellspacing="0" style="border:1px solid #e0e0e0;border-radius:10px;overflow:hidden;margin-bottom:20px;">
      <!-- Address -->
      <tr>
        <td style="padding:14px 18px;border-bottom:1px solid #e8e8e8;">
          <table cellpadding="0" cellspacing="0">
            <tr>
              <td style="font-size:18px;padding-right:14px;vertical-align:top;">🏠</td>
              <td style="color:#333;font-size:14px;font-weight:700;padding-right:20px;white-space:nowrap;vertical-align:top;">{{ __('email2.order.pickup_address_label') }}</td>
              <td style="color:#555;font-size:14px;line-height:1.6;">

                {!! $buyer->num_appartement ? 'App. ' . e($buyer->num_appartement) . ', ' : '' !!}

                {!! ($buyer->etage !== null && $buyer->etage !== '')
                    ? 'Étage ' . e($buyer->etage) . ', '
                    : '' !!}

                {!! $buyer->nom_batiment
                    ? 'Résidence ' . e($buyer->nom_batiment) . ', '
                    : '' !!}

                {!! $buyer->rue
                    ? 'Rue ' . e($buyer->rue) . ', '
                    : '' !!}

                {!! optional($buyer->city)->name
                    ? 'Ville ' . e($buyer->city->name) . ', '
                    : '' !!}

                {!! optional($buyer->region_info)->nom
                    ? e($buyer->region_info->nom)
                    : '' !!}

            </td>
            </tr>
          </table>
        </td>
      </tr>
      <!-- Region -->
      <tr>
        <td style="padding:14px 18px;border-bottom:1px solid #e8e8e8;">
          <table cellpadding="0" cellspacing="0">
            <tr>
              <td style="font-size:18px;padding-right:14px;">🗺️</td>
              <td style="color:#333;font-size:14px;font-weight:700;padding-right:20px;white-space:nowrap;">{{ __('email2.order.region_label') }}</td>
              <td style="color:#555;font-size:14px;">{{ optional($buyer->region_info)->nom }}</td>
            </tr>
          </table>
        </td>
      </tr>
      <!-- Phone -->
      <tr>
        <td style="padding:14px 18px;">
          <table cellpadding="0" cellspacing="0">
            <tr>
              <td style="font-size:18px;padding-right:14px;">📞</td>
              <td style="color:#333;font-size:14px;font-weight:700;padding-right:20px;white-space:nowrap;">{{ __('email2.order.phone_label') }}</td>
              <td><a href="tel: {{ $buyer->phone_number }}" style="color:#1a7a6e;font-size:14px;font-weight:600;text-decoration:none;">{{ $buyer->phone_number }}</a></td>
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
          <div style="font-size:15px;font-weight:700;color:#1a1a1a;margin-bottom:5px;">{{ __('email2.order.security_title') }}</div>
          <div style="font-size:13px;color:#555;line-height:1.5;">{{ __('email2.order.security_text') }}</div>
        </td>
      </tr>
    </table>

  </div>

  <!-- FOOTER -->
  <table width="100%" cellpadding="0" cellspacing="0" style="background:#1a4a47;">
    <tr>
      <td style="padding:16px 0;text-align:center;">
        <a href="#" style="color:#fff !important;font-size:13px;font-weight:500;text-decoration:none;padding:0 32px;display:inline-block;">
          ✉️ <span style="color:#fff !important;">{{ __('email2.order.footer_contact') }}</span>
        </a>
        <span style="display:inline-block;width:1px;height:20px;background:#4a7a76;vertical-align:middle;"></span>
        <a href="#" style="color:#fff !important;font-size:13px;font-weight:500;text-decoration:none;padding:0 32px;display:inline-block;">
          🛡️ <span style="color:#fff !important;">{{ __('email2.order.footer_trust') }}</span>
        </a>
      </td>
    </tr>
  </table>

  <div style="text-align:center;padding:12px;font-size:12px;color:#999;">{{ __('email2.order.footer_note') }}</div>

</div>
</body>
</html>
