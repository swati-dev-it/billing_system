<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <style>
    body {
      font-family: DejaVu Sans, sans-serif;
      color: #333;
      font-size: 12px;
      position: relative;
    }

    .header, .footer {
      text-align: center;
      padding: 10px;
    }

    .watermark {
      position: fixed;
      top: 45%;
      left: 50%;
      transform: translate(-50%, -50%);
      opacity: 0.35;
      z-index: -1;
    }

    .invoice-title {
      color: #A84400;
      font-size: 32px;
      margin: 20px 0;
    }

    .section-title {
      font-weight: bold;
      color: #A84400;
      margin-top: 20px;
      font-size: 14px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
    }

    th, td {
      padding: 8px 12px;
      border-bottom: 1px solid #ddd;
    }

    th {
      background: #f9f9f9;
      color: #A84400;
      text-align: left;
    }

    .totals td {
      border: none;
      padding: 6px 0;
    }

    .totals .label {
      text-align: right;
    }

    .balance {
      font-weight: bold;
      font-size: 16px;
      color: #A84400;
    }
  </style>
</head>
<body>

  <!-- Watermark -->
  <div class="watermark">
    <img src="{{ public_path('images/taptik_logo.png') }}" width="250">
  </div>

  <!-- Header -->
  <div class="header">
    <img src="{{ public_path('images/taptik_logo.png') }}" width="60" />
    <h2>{{ $company['name'] }}</h2>
    <p>{{ $company['address'] }}<br>{{ $company['contact'] }}</p>
    <div class="invoice-title">INVOICE</div>
  </div>

  <!-- Bill To -->
  <p class="section-title">Bill To</p>
  <p>
    {{ $user->name }}<br>
    {{ $user->address ?? 'N/A' }}<br>
    {{ $user->phone ?? 'N/A' }}
  </p>

  <!-- Invoice Meta -->
  <table>
    <tr>
      <td><strong>Invoice#:</strong> {{ $invoice->invoice_number }}</td>
      <td><strong>Invoice Date:</strong> {{ $invoice->invoice_date }}</td>
      <td><strong>Due Date:</strong> {{ $invoice->due_date }}</td>
    </tr>
  </table>

  <!-- Items -->
  <table>
    <thead>
      <tr>
        <th>#</th>
        <th>Items</th>
        <th style="text-align: right;">Amount (₹)</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($invoice->items as $index => $item)
        <tr>
          <td>{{ $index + 1 }}</td>
          <td>
            <strong>{{ $item->item }}</strong><br>
          </td>
          <td style="text-align: right;">{{ number_format($item->amount, 2) }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <!-- Totals -->
  @php
    $subTotal = $invoice->items->sum('amount');
    $taxRate = $invoice->tax_rate ?? 0;
    $tax = $subTotal * ($taxRate / 100);
    $total = $invoice->total_amount;
  @endphp

  <table class="totals">
    <tr>
      <td class="label">Sub Total:</td>
      <td style="text-align: right;">₹{{ number_format($subTotal, 2) }}</td>
    </tr>
    <tr>
      <td class="label">Tax Rate ({{ $taxRate }}%):</td>
      <td style="text-align: right;">₹{{ number_format($tax, 2) }}</td>
    </tr>
    <tr class="balance">
      <td class="label">Total:</td>
      <td style="text-align: right;">₹{{ number_format($total, 2) }}</td>
    </tr>
  </table>

  <p><strong>Terms & Conditions</strong><br>All payments must be made in full before commencement of any further work.</p>

</body>
</html>
