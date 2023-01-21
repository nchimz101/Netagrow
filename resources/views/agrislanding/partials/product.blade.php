<!-- Section 2 - Product-->
<section id="product" class="py-20 bg-white">
    <div class="flex flex-col px-8 mx-auto space-y-12 max-w-7xl xl:px-12">
        <div class="relative">
            <h2 class="w-full text-3xl font-bold text-center sm:text-4xl md:text-5xl">{{ __('agris.product_title') }}</h2>
            <p class="w-full py-8 mx-auto -mt-2 text-lg text-center text-gray-700 intro sm:max-w-3xl">{{ __('agris.product_subtitle') }}</p>
        </div>
        @foreach ($processes as $key => $process)
            <div class="flex flex-col mb-8 animated fadeIn sm:flex-row">
                <div class="flex items-center mb-8 sm:w-1/2 md:w-5/12 {{$key%2==0?"":"sm:order-last"}}">
                    <img class="rounded-lg shadow-xl" src="{{ $process->image_link }}" alt="">
                </div>
                <div class="flex flex-col justify-center mt-5 mb-8 md:mt-0 sm:w-1/2 md:w-7/12 {{$key%2==0?" sm:pl-16":" sm:pr-16"}}">
                    <p class="mb-2 text-sm font-semibold leading-none text-left text-green-600 uppercase">{{ $process->subtitle }}</p>
                    <h3 class="mt-2 text-2xl sm:text-left md:text-4xl">{{ $process->title }}</h3>
                    <p class="mt-5 text-lg text-gray-700 text md:text-left">{{ $process->description }}</p>
                </div>
            </div>
        @endforeach
    </div>
</section>  