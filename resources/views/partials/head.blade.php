@php use App\Services\FileService; @endphp
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="{{ config('app.name') }} - Build, showcase, and impress with your professional portfolio">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	@auth
		<meta name="vapid-public-key" content="{{ config('webpush.vapid.public_key') }}">
	@endauth

	<title>{{siteSetting()->name }} | {{ isset($title) ? __("lang.$title") : __('lang.home') }}</title>
	<meta name="description" content="@yield('meta_description', siteSetting()->description)">
	<meta name="keywords" content="@yield('meta_keywords', siteSetting()->description)">
	<link rel="icon" href="{{ siteSetting()->getFirstMediaUrl('favicon') }}" type="image/x-icon"/>
	<link rel="shortcut icon" href="{{ siteSetting()->getFirstMediaUrl('favicon') }}" type="image/x-icon"/>

	@vite(['resources/js/app.js'])
	@yield('style')
	<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />
	<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

	{{-- Dynamic Colors from Site Settings --}}
	<style>
		:root {
			--color-primary: {{ siteSetting()->color_primary ?? '#25376F' }};
			--color-secondary: {{ siteSetting()->color_secondary ?? '#FFFEFC' }};
			--color-accent: {{ siteSetting()->color_accent ?? '#25376F' }};
		}
	</style>

</head>