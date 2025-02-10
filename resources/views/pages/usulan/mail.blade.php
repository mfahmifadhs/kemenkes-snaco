<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Token Pengambilan</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f9f9f9;
        }

        .recovery-box {
            text-align: center;
            background-color: #ffffff;
            padding: 20px 40px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        .otp-code {
            background-color: #DCDCDC;
            padding: 10px;
            border-radius: 4px;
            font-size: 24px;
            font-weight: bold;
            font-family: monospace;
            color: #333;
            display: inline-block;
            margin-top: 10px;
        }

        .logo {
            color: #000080;
            font-weight: bold;
        }

        h2 {
            color: black;
        }

        p {
            color: black;
        }
    </style>
</head>

<body>
    <div class="recovery-box">
        <span class="logo">SNACOMS</span>
        <h2>Token Pengambilan Barang</h2>
        <div class="otp-code">
            {{ $data['otp'] }}
        </div>
        <p>
            Silahkan gunakan Token diatas untuk melakukan pengambilan barang
        </p>
    </div>
</body>

</html>
