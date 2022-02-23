@extends('site.master.layout')

@section('content')

    <div class="page">

        <section id="section-content" class="content page-13 moto-section" data-widget="section"
                 data-container="section">
            <div
                class="moto-widget moto-widget-block row-fixed moto-spacing-top-auto moto-spacing-right-auto moto-spacing-bottom-auto moto-spacing-left-auto"
                data-widget="block" data-spacing="aaaa"
                style="background-color:;background-image:url('{{asset('www/content/img/connect.jpg')}}');background-position:center;background-repeat:no-repeat;background-size:cover;"
                data-bg-image="{{asset('www/content/img/connect.jpg')}}" data-bg-position="center">
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
                                <div class="moto-widget-text-content moto-widget-text-editable">
                                    <h1 style="text-align: center;" class="moto-text_system_5">Connect Groups</h1></div>
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
                class="moto-widget moto-widget-block moto-bg-color_custom5 moto-spacing-top-large moto-spacing-right-auto moto-spacing-bottom-large moto-spacing-left-auto"
                style="background-color:;" data-spacing="lala" data-widget="block" data-bg-position="left ">
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
                                                <iframe id="onechurch_form_groups"
                                                        src="https://libertychurch.onechurchsoftware.com/embed/groups?bg=f5f1ec&text=6a6a6a&box=eeeeee&button=f2103d&accent=575757&title=0ab8a4"
                                                        seamless="seamless" width="100%" height="180" scrolling="auto"
                                                        frameborder="0" style="border-width: 0px;"></iframe>
                                                <script src="{{asset('js/bridge.js')}}"></script>
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
