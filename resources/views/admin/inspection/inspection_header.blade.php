<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Property Inspection</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      color: #333;
      margin: 0;
      padding: 0;
    }
    .container {
      width: 700px;
      margin: 0 auto;
      padding: 20px;
    }
    h1 {
      text-align: center;
      font-size: 28px;
      margin-bottom: 20px;
    }
    .logo {
      display: block;
      margin: 0 auto;
      width: 200px;
      height: 200px;
      object-fit: contain;
    }
    .contact {
      text-align: center;
      font-size: 12px;
      margin-top: 10px;
      line-height: 1.4;
    }
    .inspector {
      text-align: center;
      font-size: 12px;
      margin-top: 15px;
    }
    .main {
      display: flex;
      justify-content: space-between;
      margin-top: 30px;
    }
    .address {
      width: 30%;
      font-size: 14px;
      line-height: 1.5;
    }
    .address strong {
      display: block;
      margin-bottom: 8px;
    }
    .property-image {
      width: 300px;
      height: 200px;
      object-fit: cover;
      border: 1px solid #ccc;
    }
    .date-container {
      width: 30%;
      text-align: right;
      font-size: 24px;
      font-weight: bold;
      line-height: 1.2;
    }
    .date-container .label {
      font-size: 12px;
      font-weight: normal;
      text-transform: uppercase;
      margin-bottom: 6px;
      display: inline-block;
    }
    .footer {
      text-align: center;
      font-size: 12px;
      margin-top: 50px;
      line-height: 1.4;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Property Inspection</h1>
    <img src="https://placehold.co/100x75?text=Dummy+Image" alt="Company Logo" class="logo">
    <div class="contact">
      01925 88807<br>
      telford@peterichardsons.co.uk
    </div>
    <div class="inspector">Property inspected by Kate Howells</div>
    <div class="main">
      <div class="address">
        <strong>Address</strong>
        39 Withywood Drive<br>
        Telford<br>
        Shropshire<br>
        TF3 2HT
      </div>
      <img src="https://placehold.co/100x75?text=Dummy+Image" alt="Property" class="property-image">
      <div class="date-container">
        <div class="label">Carried Out</div>
        November 28th 2024
      </div>
    </div>
    <div class="footer">
      Peter Richardson Estates 10-12 Hills Lane Drive, Madeley, Telford, Shropshire, TF7 4BP
    </div>
  </div>
</body>
</html>
