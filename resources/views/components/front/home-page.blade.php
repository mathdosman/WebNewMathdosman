<x-front.layout>
<!-- Main Content-->
<div class="container px-4 px-lg-5">
    <div class="row gx-4 gx-lg-5 justify-content-center">
        <div class="col-md-10 col-lg-8 col-xl-7">
            @foreach ($data as $key=>$value)
            <!-- Post preview-->
            <div class="row">
                <div class="col-3">
                    @if($value->thumbnail == 0)
                    <img src="{{ asset('thumbnails/kosong.png') }}" alt="" width="100px" style="display:block; margin-top:40px; margin-left:auto; margin-right:auto">
                    @else
                    @isset($value->thumbnail)
                        <img src="{{ asset(getenv('CUSTOM_THUMBNAIL_LOCATION').'/'.$value->thumbnail) }}" width="100px" style="display:block; margin-top:40px; margin-left:auto; margin-right:auto" />
                    @endisset
                    @endif
                </div>
                <div class="col-9">
                    <x-front.blog-list title='{{ $value->title }}' description='{{ $value->description }}' date="{{ $value->created_at->isoFormat('dddd, D MMMM Y') }}" user='{{ $value->user->name }}' link="{{ route('blog-detail',['slug'=>$value->slug]) }}" />
                </div>
            </div>
            @endforeach
            <!-- Pager-->
            <div class="d-flex justify-content-between mb-4">
                    <div>
                        @if(!$data->onFirstPage())
                        <a class="btn btn-primary text-uppercase" href="{{ $data->previousPageUrl() }}">&larr; Newer Posts</a>
                        @endif
                    </div>
                    <div>
                        @if ($data->hasMorePages())
                        <a class="btn btn-primary text-uppercase" href="{{ $data->nextPageUrl() }}">Older Posts &rarr;</a>
                        @endif
                    </div>
        </div>
    </div>
</div>
</div>

<a class="float" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample">

        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="auto" fill="currentColor" class="bi bi-chevron-expand" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M3.646 9.146a.5.5 0 0 1 .708 0L8 12.793l3.646-3.647a.5.5 0 0 1 .708.708l-4 4a.5.5 0 0 1-.708 0l-4-4a.5.5 0 0 1 0-.708m0-2.292a.5.5 0 0 0 .708 0L8 3.207l3.646 3.647a.5.5 0 0 0 .708-.708l-4-4a.5.5 0 0 0-.708 0l-4 4a.5.5 0 0 0 0 .708"/>
          </svg>
</a>

@include('components.front.drawer')
</x-front.layout>

