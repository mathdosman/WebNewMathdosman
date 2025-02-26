<x-front.layout-detail>
    <x-slot name='pageTitle'>{{ $data->title }}</x-slot>
    <div class="" style="margin-top:100px">
        <h1 class="text-center">{{ $data->title }}</h1>
        <p class="text-center" style="font-size: 1rem !important">{{ $data->created_at->isoFormat('dddd, D MMMM Y') }}</p>
    </div>
<!-- Main Content-->
<article class="mb-4 mt-3">
    <div class="container px-4 px-lg-5">
        <div class="row gx-4 gx-lg-5 justify-content-center">
            <div class="col-md-10 col-lg-8 col-xl-7">
                {!! $data->content !!}
                <!-- Pager-->
                <div class="d-flex justify-content-between mb-4 mt-5">
                    <div>
                        @if ($pagination['next'])
                        <a href="{{ route('blog-detail',['slug'=>$pagination['next']->slug]) }}">&larr;{{ $pagination['next']->title }}  </a>
                        @else
                        <span></span>
                        @endif
                    </div>
                    <div>
                        @if ($pagination['prev'])
                            <a href="{{ route('blog-detail',['slug'=>$pagination['prev']->slug]) }}"> {{ $pagination['prev']->title }} &rarr;</a>
                        @else
                            <span></span>
                        @endif
                    </div>
                </div>
        </div>
    </div>
</article>

</x-front.layout-detail>

