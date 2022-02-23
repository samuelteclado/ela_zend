@extends('site.master.layout')

@section('content')

    <div class="page">

        <section id="section-content" class="content page-13 moto-section" data-widget="section"
                 data-container="section">
            <div
                class="moto-widget moto-widget-block row-fixed moto-spacing-top-auto moto-spacing-right-auto moto-spacing-bottom-auto moto-spacing-left-auto"
                data-widget="block" data-spacing="aaaa"
                style="background-color:;background-image:url('{{asset('www/content/img/events.jpg')}}');background-position:center;background-repeat:no-repeat;background-size:cover;"
                data-bg-image="{{asset('www/content/img/events.jpg')}}" data-bg-position="center">
                <div class="container-fluid">
                    <div class="row">
                        <div class="moto-cell col-sm-12" data-container="container">
                            <div data-widget-id="wid__spacer__5bdfe799c8cd6"
                                 class="moto-widget moto-widget-spacer moto-preset-default moto-spacing-top-large moto-spacing-right-auto moto-spacing-bottom-small moto-spacing-left-auto "
                                 data-widget="spacer" data-preset="default" data-spacing="lasa"
                                 data-visible-on="mobile-v">
                                <div class="moto-widget-spacer-block" style="height:30px"></div>
                            </div>
                            <div
                                class="moto-widget moto-widget-text moto-preset-default moto-spacing-top-auto moto-spacing-right-small moto-spacing-bottom-auto moto-spacing-left-small"
                                data-widget="text" data-preset="default" data-spacing="asas" data-animation="">
                                <div class="moto-widget-text-content moto-widget-text-editable"><h1
                                        style="text-align: center;" class="moto-text_system_5">CALENDAR</h1></div>
                            </div>
                            <div data-widget-id="wid__spacer__5bdfe799ca010"
                                 class="moto-widget moto-widget-spacer moto-preset-default moto-spacing-top-large moto-spacing-right-auto moto-spacing-bottom-auto moto-spacing-left-auto "
                                 data-widget="spacer" data-preset="default" data-spacing="laaa"
                                 data-visible-on="mobile-v">
                                <div class="moto-widget-spacer-block" style="height:20px"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Page  -->
            <div
                class="moto-widget moto-widget-block moto-bg-color_custom5 moto-spacing-top-medium moto-spacing-right-auto moto-spacing-bottom-medium moto-spacing-left-auto"
                data-spacing="lala" data-widget="block" data-bg-position="left ">
                <div class="container-fluid">
                    <div class="row">
                        <div class="moto-cell col-sm-12" data-container="container">
                            <div
                                class="moto-widget moto-widget-row row-fixed moto-spacing-top-auto moto-spacing-right-auto moto-spacing-bottom-auto moto-spacing-left-auto"
                                data-grid-type="sm" data-widget="row" data-spacing="aaaa" style=""
                                data-bg-position="left top">
                                <div class="container-fluid">
                                    <div class="row" data-container="container">
                                        <div class="moto-widget moto-widget-row__column moto-cell col-sm-12"
                                             data-widget="row.column" data-bgcolor-class="" data-container="container"
                                             style="" data-bg-position="left top">
                                            <div
                                                class="moto-widget moto-widget-text moto-preset-default moto-spacing-top-auto moto-spacing-right-auto moto-spacing-bottom-small moto-spacing-left-auto"
                                                data-widget="text" data-preset="default" data-spacing="aasa"
                                                data-animation="">
                                                <!-- ONE CHURCH-->

                                                <iframe id="onechurch_form_events" src="https://libertychurch.onechurchsoftware.com/embed/events?bg=ffffff&text=6a6a6a&box=eeeeee&button=f2103d&accent=575757&title=f2103d" seamless="seamless" width="100%" height="180" scrolling="auto" frameborder="0" style="border-width: 0px;"></iframe> <script src="https://s3.amazonaws.com/onechurch/bridge.js"></script>                                                <script src="https://s3.amazonaws.com/onechurch/bridge.js"></script>

                                                <!-- SUBSPLASH-->
                                                <!--<script id="subsplash-embed-wf9qrgv" type="text/javascript"> var target = document.getElementById("subsplash-embed-wf9qrgv"); var script = document.createElement("script"); script.type = "text/javascript"; script.onload = function() { subsplashEmbed( "+3bd8/lb/ca/+wf9qrgv?embed&branding", "https://subsplash.com/", "subsplash-embed-wf9qrgv" ); }; script.src = "https://dashboard.static.subsplash.com/production/web-client/external/embed-1.1.0.js"; target.parentElement.insertBefore(script, target); </script>-->

                                            </div>
                                        </div>
                                        <div class="moto-widget moto-widget-row__column moto-cell col-sm-2"
                                             data-widget="row.column" data-bgcolor-class="" data-container="container"
                                             style="" data-bg-position="left top">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
        </section>
    </div>
@endsection
