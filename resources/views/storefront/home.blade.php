@extends('layouts.storefront')

@section('meta_title', \App\Models\SiteSetting::get('meta_title', 'Jem Designs & Co. — Heritage, Reimagined'))
@section('meta_description', \App\Models\SiteSetting::get('meta_description'))

@section('content')

@foreach($sections as $section)
  @if($section->section_key === 'hero')
    @include('storefront.partials._hero')
  @elseif($section->section_key === 'top_sellers')
    @include('storefront.partials._top_sellers')
  @elseif($section->section_key === 'story')
    @include('storefront.partials._story')
  @elseif($section->section_key === 'collections')
    @include('storefront.partials._collections')
  @elseif($section->section_key === 'founder')
    @include('storefront.partials._founder')
  @elseif($section->section_key === 'testimonials')
    {{-- Testimonials now managed via Marketing > Testimonials --}}
  @elseif($section->section_key === 'instagram')
    @include('storefront.partials._instagram')
  @endif
@endforeach

{{-- Marketing Sections (dynamic) --}}
@foreach($marketingSections as $section)
  @include('storefront.partials._marketing_section', ['section' => $section])
@endforeach

@endsection
