

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Reset Password</title>
</head>

<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 0; box-sizing: border-box;">
    <div style=" padding: 20px;">
        <div
            style="display: block;width: 600px;margin: 0 auto; border-radius: 16px; overflow: hidden; background-color: #FBFDFF;">
            <table style="border: 0; width: 600px;">
                <tr>
                    <td style="padding: 30px 0; background-color: #E8DFF4;
                    ">
                        <div style="text-align: center; ">
                            <img src="{{ asset('assets/images/logo/LogoHeader.png') }}" alt="logo" width="142" height="55">
                            <p
                                style="width:fit-content; font-size: 24px; font-weight: 600; color: #1E1F21; line-height: 36px; margin: 20px auto 0 auto;">
                                Reset Your Password
                            </p>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="padding:32px 0 40px 0;">
                        <p
                            style="display: block; text-align: center; font-size: 22px; line-height: 33px; color: #1E1F21; margin: 0 0 15px 8px; font-weight: 600;">
                            Hello {{$maildata['first_name']}} {{$maildata['last_name']}},
                        </p>
                        <p
                            style="display: block; text-align: center; font-size: 16px; line-height: 24px; font-weight: 400; color: #454748; margin: 0 auto; width: 475px;">
                            You are receiving this email because we received a password reset request for your account.
                        </p>
                        <a href="{{$resetUrl}}"

                            style="display: block; padding: 10px 2px; color: #6D4C8D; border-radius: 56px; background-color: rgba(123, 70, 174, 0.07);  margin: 24px auto; text-decoration: none; font-weight: 600; font-size: 16px; line-height: 24px;  border: 0;  cursor: pointer; width: 206px; text-align: center;">
                            Reset Your Password here

                        </a>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div style="width: fit-content;  overflow: hidden; margin: 10px auto 18px auto; ">
                            <p
                                style="font-size: 16px; font-weight: 400; color: #454748; line-height: 24px; float: left; margin: 0;">
                                Need assistance?
                            </p>
                            <a href="{{ env('APP_FRONTEND_URL') }}/contact-us"
                                style="font-size: 16px; font-weight: 600; color: #0b2c58; line-height: 24px; float: left; margin: 0; text-decoration: none; margin-left: 5px; padding: 0; border: 0; background-color: transparent; cursor: pointer;">
                                Contact us
                            </a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td style="text-align: center; background-color: #EBEDEF; padding: 36px 10px;">
                        <p
                            style="width:fit-content; font-size: 16px; font-weight: 400; color: #454748; line-height: 24px;  margin: 0 auto 12px auto ;">
                            © {{ date('Y') }}. Designed by <a href="#">PizzaElectric</a> All rights reserved.
                        </p>
                        <div style="width: fit-content; margin: 0 auto; overflow: hidden;">
                             <a href="{{ env('APP_FRONTEND_URL') }}/privacy-policy"
                                style=" font-size: 16px; font-weight: 600; color: #0b2c58; line-height: 24px;background-color: transparent; padding: 0; border: 0; width: fit-content; cursor: pointer; float: left;">
                                Privacy Policy
                            </a>
                            <p
                                style="margin:0 8px 0px 8px; font-size: 16px; font-weight: 600; color: #0b2c58; line-height: 20px; float: left;">
                                |
                            </p>
                             <a href="{{ env('APP_FRONTEND_URL') }}/terms-conditions"
                                style=" font-size: 16px; font-weight: 600; color: #0b2c58; line-height: 24px;  background-color: transparent; padding: 0; border: 0; width: fit-content; cursor: pointer; float: left;">
                                Terms of Service
                            </a>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
    </div>

</body>

</html>