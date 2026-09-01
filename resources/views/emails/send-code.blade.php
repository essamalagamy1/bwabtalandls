@php
    $siteSetting = siteSetting();
    $logoUrl = $siteSetting?->getFirstMediaUrl('logo_black') 
        ?: ($siteSetting?->getFirstMediaUrl('logo_white') 
        ?: (file_exists(public_path('logo.png')) ? asset('logo.png') : null));
    $appName = $siteSetting?->getTranslation('name', app()->getLocale()) 
        ?: config('app.name');
@endphp
<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="rtl">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>{{ __('lang.verification_code') }}</title>

	<!-- Bootstrap 5 -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

	<style>
        body {
            font-family: 'Segoe UI', Tahoma, -apple-system, BlinkMacSystemFont, Roboto, Helvetica, Arial, sans-serif;
            background-color: #f8fafc;
            color: #334155;
            direction: rtl;
            text-align: center;
        }

        .container {
            max-width: 500px;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
            padding: 32px 24px;
            margin-top: 40px;
            margin-bottom: 40px;
            direction: rtl;
        }

        .otp-code {
            font-size: 28px;
            font-weight: 700;
            color: #4f46e5;
            background: #eef2ff;
            padding: 12px 24px;
            display: inline-block;
            border-radius: 8px;
            letter-spacing: 4px;
            margin: 16px 0;
            border: 1px dashed #6366f1;
        }

        .footer {
            font-size: 14px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
            padding-top: 16px;
            margin-top: 24px;
        }

        .footer a {
            color: #4f46e5;
            text-decoration: none;
            font-weight: 500;
        }

        .footer a:hover {
            text-decoration: underline;
        }

        .copyright {
            font-size: 12px;
            color: #94a3b8;
            margin-top: 10px;
            text-align: center;
        }
	</style>
</head>
<body class="d-flex justify-content-center align-items-center min-vh-100" dir="rtl">
<div class="container text-center" dir="rtl">
	<!-- الشعار -->
	<div class="header my-3">
		@if ($logoUrl)
			<img src="{{ $logoUrl }}" alt="{{ $appName }}" class="img-fluid" style="max-height: 65px; width: auto; max-width: 200px; object-fit: contain;">
		@else
			<h3 class="fw-bold text-dark">{{ $appName }}</h3>
		@endif
	</div>

	<!-- المحتوى -->
	<div class="content">
		<h4 class="mb-3 text-dark fw-bold">{{ __('lang.hello', ['name' => $user->name]) }}</h4>
		<p class="mb-2 text-muted">{{ __('lang.verification_message') }}</p>
		<div class="otp-code">{{ $code }}</div>
		<p class="mb-2 text-muted" style="font-size: 13px;">{{ __('lang.ignore_message') }}</p>
	</div>

	<!-- التذييل -->
	<div class="footer">
		<p class="mb-1">{{ __('lang.thank_you') }} <strong>{{ $appName }}</strong>!</p>
		<p class="mb-0">{{ __('lang.contact_us_to_help') }} <a href="{{ config('app.url') }}">{{ __('lang.support_link') }}</a></p>
	</div>
	<div class="copyright">
		<p>© {{ date('Y') }} {{ __('lang.all_rights_reserved') }} <strong>{{ $appName }}</strong></p>
	</div>
</div>
</body>
</html>
