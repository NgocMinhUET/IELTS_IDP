<!DOCTYPE html>
<html>
<head>
    <title>Xác nhận email</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background-color: #4CAF50; color: white; padding: 10px; text-align: center; }
        .content { padding: 20px; }
        .button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin: 20px 0;
        }
        .footer { margin-top: 20px; font-size: 12px; color: #777; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Xác nhận địa chỉ email</h2>
        </div>
        
        <div class="content">
            <p>Cảm ơn bạn đã đăng ký tài khoản. Vui lòng nhấp vào nút bên dưới để xác nhận địa chỉ email của bạn:</p>
            
            <a href="{{$verification_url}}" class="button">Xác nhận Email</a>

            <p>Link sẽ hết hạn trong {{$time_expired}} </p>
            
            <p>Nếu bạn không tạo tài khoản này, vui lòng bỏ qua email này.</p>
            
            <p>Trân trọng,<br>Đội ngũ {{config('app.name')}}</p>
        </div>
    </div>
</body>
</html>