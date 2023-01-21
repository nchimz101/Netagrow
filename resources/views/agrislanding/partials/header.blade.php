<!-- Top Notification Bar -->
@if (session('status'))
<a class="block pt-16 pb-6 bg-blue-900 opacity-100 md:h-16 md:pt-0 md:pb-0">
    <span class="relative flex items-center justify-center h-full max-w-6xl pl-10 pr-20 mx-auto leading-tight text-left md:text-center">
      <span class="text-white">{{ __("".session('status')) }}</span>
    </span>
</a>
@endif
  
<!-- Section 1 - Header -->
<section class="relative w-full bg-top bg-cover md:bg-center" x-data="{ showMenu: false }" style="background-image:url('/agris/bg.jpeg')">
    
    

    <div class="absolute inset-0 w-full h-full bg-gray-900 opacity-25"></div>
    <div class="absolute inset-0 w-full h-64 opacity-50 bg-gradient-to-b from-black to-transparent"></div>
    <div class="relative flex items-center justify-between w-full h-20 px-8">

        <a href="/" class="relative flex items-center h-full pr-6 text-2xl font-extrabold text-white">{{ strtolower(config('global.site_name','AgriS')) }}<span class="text-green-400">.</span></a>
        @include('agrislanding.partials.nav')

        <!-- Mobile Nav  -->
        <nav class="fixed top-0 right-0 z-30 z-50 flex w-10 h-10 mt-4 mr-4 md:hidden">
            <button @click="showMenu = !showMenu" class="flex items-center justify-center w-10 h-10 rounded-full hover:bg-white hover:bg-opacity-25 focus:outline-none">
                <svg class="w-5 h-5 text-gray-200 fill-current" x-show="!showMenu" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z"></path></svg>
                <svg class="w-5 h-5 text-gray-500" x-show="showMenu" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </nav>
        <!-- End Mobile Nav -->
    </div>
    <div class="relative z-10 max-w-6xl px-10 py-40 mx-auto">
        <div class="flex flex-col items-center h-full lg:flex-row">
            <div class="flex flex-col items-center justify-center w-full h-full lg:w-2/3 lg:items-start">
                <p class="inline-block w-auto px-3 py-1 mb-5 text-xs font-medium text-white uppercase bg-green-400 rounded-full bg-gradient-to-br">{{ __('agris.slogan_subtitle') }}</p>
                <h1 class="font-extrabold tracking-tight text-center text-white text-7xl lg:text-left xl:pr-32">{{ __('agris.slogan') }}</h1>
            </div>
            @guest
                <div class="w-full max-w-sm mt-20 lg:mt-0 lg:w-1/3">
                    <div class="relative">
                        <div class="relative z-10 h-auto p-8 pt-6 overflow-hidden bg-white border-b-2 border-gray-300 rounded-lg shadow-2xl px-7">
                            <form action="{{ route('newrestaurant.register') }}">
                            <h3 class="mb-6 text-2xl font-light">{{ __('agris.form_title')}}</h3><h3>
                            <div class="relative block mb-4">
                                <input type="text" name="name" class="block w-full px-4 py-3 border border-gray-200 rounded-lg shadow-sm focus:border-green-500 focus:outline-none" placeholder="{{ __('agris.form_input_name')}}">
                            </div>
                            <div class="block mb-4">
                                <input type="email" name="email" class="block w-full px-4 py-3 border border-gray-200 rounded shadow-sm focus:border-green-500 focus:outline-none" placeholder="{{ __('agris.form_input_email')}}">
                            </div>
                            <div class="block mb-4">
                                <input type="phone" name="phone" class="block w-full px-4 py-3 border border-gray-200 rounded shadow-sm focus:border-green-500 focus:outline-none" placeholder="{{ __('agris.form_input_phone')}}">
                            </div>
                            <div class="block">
                                <button  disabled  id="submitRegister" class="w-full px-3 py-3 font-medium text-white bg-green-400 opacity-50 ">{{ __('agris.form_sign_up')}}</button>
                            </div>
                            <br />
                            <div class="form-check"><input type="checkbox" name="termsCheckBox" id="termsCheckBox" class="h-4 w-4 border border-gray-300 rounded-sm bg-white checked:bg-blue-600 checked:border-blue-600 focus:outline-none transition duration-200 mt-1 align-top  "> 
                                <label for="terms" class="form-check-label text-gray-500">
                                &nbsp;&nbsp;{{__('agris.i_agree_to')}}
                                <a href="{{config('settings.link_to_ts')}}" target="_blank" style="text-decoration: underline;">{{__('agris.terms_of_service')}}</a> {{__('agris.and')}}
                                <a href="{{config('settings.link_to_pr')}}" target="_blank" style="text-decoration: underline;">{{__('agris.privacy_policy')}}</a>.
                                </label>
                            </div>
                        </h3>
                    </form>
                        </div>
                    </div>
                </div>
            @endguest
        </div>
    </div>

</section>
