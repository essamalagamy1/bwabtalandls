@props(['url'])
@php
    $siteSetting = siteSetting();
    $logoUrl = $siteSetting?->getFirstMediaUrl('logo_black') 
        ?: ($siteSetting?->getFirstMediaUrl('logo_white') 
        ?: (file_exists(public_path('logo.png')) ? asset('logo.png') : null));
    $appName = $siteSetting?->getTranslation('name', app()->getLocale()) 
        ?: config('app.name');
@endphp
<tr>
	<td class="header" align="center" dir="rtl" style="text-align: center; padding: 25px 0;">
		<a href="{{ $url }}" style="display: inline-block; text-decoration: none;">
			@if ($logoUrl)
				<img src="{{ $logoUrl }}" class="logo" alt="{{ $appName }}" style="max-height: 65px; width: auto; max-width: 220px; object-fit: contain; vertical-align: middle; border: 0;" />
			@else
				<span style="font-size: 20px; font-weight: bold; color: #1e293b;">{{ $appName }}</span>
			@endif
		</a>
	</td>
</tr>
