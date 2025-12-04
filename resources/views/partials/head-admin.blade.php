<meta name="csrf-token" content="{{ csrf_token() }}">
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta http-equiv="X-UA-Compatible" content="ie=edge">

<title>{{ $title ?? config('app.name') }}</title>
<meta name="description" content="{{ $description ?? config('app.name') }}">
<meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">

<link rel="canonical" href="">
{{-- <link rel="icon" href="{{ asset('storage/vertex_favicon.svg') }}" sizes="any">
<link rel="icon" href="{{ asset('storage/vertex_favicon.svg') }}" type="image/svg+xml">
<link rel="apple-touch-icon" href="{{ asset('storage/vertex_favicon.svg') }}"> --}}

<meta property="og:locale" content="pl_PL">
<meta property="og:type" content="article">
<meta property="og:title" content="{{ $title ?? config('app.name') }}">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
<meta property="og:description" content="{{ $description ?? config('app.name') }}">
<meta property="og:url" content="">
<meta property="og:site_name" content="GlamSpot">
<meta property="article:publisher" content="">
<meta property="og:image" content="">
<meta property="og:image:width" content="2200">
<meta property="og:image:height" content="1485">
<meta property="og:image:type" content="image/jpeg">

<style>[x-cloak]{display:none}</style>


@vite(['resources/css/app.css', 'resources/js/app.js'])
@livewireStyles

<link rel="stylesheet" href="https://cdn.ckeditor.com/ckeditor5/47.3.0/ckeditor5.css" />

@include('partials.city-search-script')

<style>
	.main-container {
		width: 795px;
		margin-left: auto;
		margin-right: auto;
	}
</style>