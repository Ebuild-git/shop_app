<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>SHOPIN Shipping Label</title>
<style>
  @page {
    margin: 0;
    size: 148mm 260mm;
  }
  * { box-sizing: border-box; }
  body {
    font-family: 'Arial', 'Helvetica Neue', sans-serif;
    margin: 0;
    padding: 6mm;
    color: #16233a;
  }
  table { border-collapse: collapse; width: 100%; }

  .label-wrapper {
    border: 1.5px solid #d6e6e6;
    border-radius: 10px;
    width: 100%;
    padding: 4mm;
  }

  /* TOP: logo + barcode */
  .top-table td { vertical-align: middle; padding: 1mm 2mm; }
  .top-table .logo-td { width: 55%; }
  .top-table .barcode-td { width: 45%; }
  .logo .shop {
    font-size: 26px;
    font-weight: 900;
    color: #0a0a0a;
    letter-spacing: -0.5px;
  }
  .logo .shop .in { color: #008080; }
  .logo .marketplace {
    margin-top: 2px;
    font-size: 8.5px;
    letter-spacing: 3px;
    color: #4d9999;
    font-weight: bold;
  }
  .barcode-box {
    border: 1.5px solid #d6e6e6;
    border-radius: 8px;
    padding: 2.5mm 3mm;
    text-align: center;
    background: #fafcfc;
  }
  .barcode-box img { display: block; margin: 0 auto; width: 100%; max-width: 55mm; }
  .barcode-num {
    font-weight: bold;
    font-size: 9.5px;
    letter-spacing: 1.5px;
    margin-top: 2px;
    color: #16233a;
  }

  /* Section card wrapper */
  .card {
    border: 1.5px solid #d6e6e6;
    border-radius: 10px;
    margin: 3mm 0;
    overflow: hidden;
  }

  /* Horizontal header bar */
  .card-header {
    background: #008080;
    color: #fff;
    padding: 2mm 4mm;
    font-size: 9px;
    font-weight: bold;
    letter-spacing: 1.5px;
  }

  .content-table td {
    border-right: 1px solid #e3efef;
    border-bottom: 1px solid #e3efef;
    padding: 2.8mm 4mm;
    vertical-align: top;
  }
  .content-table tr:last-child td { border-bottom: none; }
  .content-table td:last-child { border-right: none; }

  .label-text {
    color: #4d8080;
    font-weight: bold;
    font-size: 7.5px;
    letter-spacing: 0.4px;
    text-transform: uppercase;
    margin-bottom: 2px;
  }
  .value-text { font-weight: 800; font-size: 12.5px; color: #16233a; }
  .value-text.small { font-size: 10.5px; }

  .cod-pill {
    display: inline-block;
    background: #16233a;
    color: #fff;
    padding: 1.5mm 3mm;
    border-radius: 20px;
    font-weight: 800;
    font-size: 11px;
    margin-top: 1px;
  }

  .shipper-name { font-weight: 800; font-size: 13px; margin-top: 1px; color: #16233a; }
  .shipper-sub { font-size: 9px; margin-top: 2px; color: #4a5a5a; }
  .shipper-brand { font-weight: 800; font-size: 13px; margin-top: 2px; color: #008080; }

  /* FOOTER */
  .footer-card {
    border: 1.5px solid #d6e6e6;
    border-radius: 10px;
    margin: 3mm 0 0 0;
    overflow: hidden;
  }
  .footer-table td { padding: 3mm 4mm; vertical-align: middle; }
  .footer-table .icon-td { width: 8%; }
  .footer-table .aramex-td { width: 25%; text-align: right; }
  .info-icon {
    width: 18px;
    height: 18px;
    border-radius: 50%;
    background: #008080;
    color: #fff;
    text-align: center;
    font-weight: bold;
    font-style: italic;
    font-size: 10px;
  }
  .footer-text { font-size: 8px; line-height: 1.4; color: #4a5a5a; }
  .footer-text b { display: block; font-size: 8.5px; color: #16233a; }
  .aramex {
    background: #e2231a;
    color: #fff;
    font-weight: 900;
    font-style: italic;
    font-size: 12px;
    padding: 4px 10px;
    border-radius: 20px;
    text-align: center;
  }
  .aramex small {
    display: block;
    font-style: normal;
    font-weight: normal;
    font-size: 5px;
    letter-spacing: 0.5px;
  }
</style>
</head>
<body>

<div class="label-wrapper">

  <!-- TOP -->
  <table class="top-table">
    <tr>
      <td class="logo-td">
        <div class="logo">
          <div class="shop">SHOP<span class="in">IN</span></div>
          <div class="marketplace">MARKETPLACE</div>
        </div>
      </td>
      <td class="barcode-td">
        <div class="barcode-box">
          <img src="{{ $barcode_base64 }}" alt="barcode">
          <div class="barcode-num">{{ $shipment_id }}</div>
        </div>
      </td>
    </tr>
  </table>

  <!-- SHIPMENT DETAILS -->
  <div class="card">
    <div class="card-header">SHIPMENT DETAILS</div>
    <table class="content-table">
      <tr>
        <td><div class="label-text">Origin</div><div class="value-text">{{ strtoupper($origin) }}</div></td>
        <td><div class="label-text">Destination</div><div class="value-text">{{ strtoupper($destination) }}</div></td>
        <td style="border-right:none;"><div class="label-text">Product</div><div class="value-text">{{ $product }}</div></td>
      </tr>
      <tr>
        <td colspan="3">
          <div class="label-text">Description of goods</div>
          <div class="value-text">{{ strtoupper($description) }}</div>
        </td>
      </tr>
      <tr>
        <td><div class="label-text">Customs value</div><div class="value-text">{{ $customs_value }}</div></td>
        <td><div class="label-text">Goods origin</div><div class="value-text">{{ $goods_origin }}</div></td>
        <td style="border-right:none;">
          <div class="label-text">COD value</div>
          <div class="cod-pill">{{ $cod_value }} DHS</div>
        </td>
      </tr>
      <tr>
        <td colspan="3">
          <div class="label-text">Pieces</div>
          <div class="value-text">{{ $pcs }}</div>
        </td>
      </tr>
    </table>
  </div>

  <!-- SHIPPER DETAILS (its own card) -->
  <div class="card">
    <div class="card-header">SHIPPER DETAILS</div>
    <table class="content-table">
      <tr>
        <td style="width:65%; border-bottom:none;">
          <div class="shipper-name">{{ strtoupper($shipper['name']) }}</div>
          <div class="shipper-sub">{{ $shipper['code'] }}</div>
          <div class="shipper-brand">{{ $shipper['brand'] }}</div>
          <div class="shipper-sub">{{ $shipper['city'] }}, {{ $shipper['country'] }}</div>
        </td>
        <td style="border-right:none; border-bottom:none;">
          <div class="label-text">Services</div>
          <div class="value-text" style="margin-bottom:6px;">{{ $services }}</div>
          <div class="label-text">Print date</div>
          <div class="value-text small">{{ $print_date }}</div>
        </td>
      </tr>
    </table>
  </div>

  <!-- CONSIGNEE DETAILS (own card, same header treatment as shipper) -->
  <div class="card">
    <div class="card-header">CONSIGNEE DETAILS</div>
    <table class="content-table">
      <tr>
        <td style="border-right:none; border-bottom:none;">
          <div class="shipper-name">{{ strtoupper($consignee['name']) }}</div>
          <div class="shipper-sub">{{ $consignee['code'] }}</div>
        </td>
      </tr>
    </table>
  </div>

  <!-- FOOTER -->
  <div class="footer-card">
    <table class="footer-table">
      <tr>
        <td class="icon-td"><div class="info-icon">i</div></td>
        <td>
          <div class="footer-text">
            <b>Merci de ne pas contacter le vendeur.</b>
            Toute la livraison est gérée par Aramex.
          </div>
        </td>
        <td class="aramex-td">
          <div class="aramex">aramex<small>delivery unlimited</small></div>
        </td>
      </tr>
    </table>
  </div>

</div>

</body>
</html>
