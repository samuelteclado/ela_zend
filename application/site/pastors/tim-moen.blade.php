@extends('site.master.layout')

@section('content')
    @include('site.pastors.top')

    <section id="section-content" class="content page-10 moto-section" data-widget="section" data-container="section">
        <div
            class="moto-widget moto-widget-block moto-bg-color_custom5 moto-spacing-top-large moto-spacing-right-auto moto-spacing-bottom-large moto-spacing-left-auto"
            data-widget="block" data-spacing="lala" style="background-color:;">
            <div class="container-fluid">
                <div class="row">
                    <div class="moto-cell col-sm-12" data-container="container">

                        <div
                            class="moto-widget moto-widget-row row-fixed moto-spacing-top-auto moto-spacing-right-auto moto-spacing-bottom-auto moto-spacing-left-auto"
                            data-grid-type="sm" data-widget="row" data-spacing="aaaa">

                            <div class="container-fluid">
                                <div class="row" data-container="container">
                                    <div class="moto-widget moto-widget-row__column moto-cell col-sm-3"
                                         data-widget="row.column" data-bgcolor-class="" data-container="container">

                                        <div data-widget-id="wid__image__5956decd1f863"
                                             class="moto-widget moto-widget-image moto-preset-default moto-align-center_mobile-h moto-spacing-top-auto moto-spacing-right-auto moto-spacing-bottom-small moto-spacing-left-auto "
                                             data-preset="default" data-widget="image">
                                            <div class="moto-widget-image-link">
                                                <img
                                                    data-src="{{ asset('www/content/pastors/tim-moen.jpg') }}"
                                                    class="moto-widget-image-picture lazyload" data-id="184" title=""
                                                    alt="" draggable="false">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="moto-widget moto-widget-row__column moto-cell col-sm-8"
                                         data-widget="row.column" data-bgcolor-class="" data-container="container">

                                        <div data-widget-id="wid__text__5956decd1fa02"
                                             class="moto-widget moto-widget-text moto-preset-default  moto-spacing-top-auto moto-spacing-right-small moto-spacing-bottom-small moto-spacing-left-small "
                                             data-widget="text" data-preset="default" data-spacing="asss"
                                             data-animation="">
                                            <div class="moto-widget-text-content moto-widget-text-editable"><p
                                                    class="moto-text_system_9">Pastor Tim Moen</p>
                                                <p class="moto-text_system_9">&nbsp;</p>
                                                <p class="moto-text_system_10">
                                                    As the Lead Pastor of Liberty Church, I provide teaching, leadership, and guidance for the church as a whole.
                                                    I have served full time in the ministry for nearly my whole career.
                                                    I am compelled to live and lead a life of influence both within the church and within the community.
                                                    For over twenty years, I have had the privilege of leading hundreds of individuals in non-profit and church organizations.
                                                    I have served in two other churches, as an executive director for a network of 180 churches in Southern New England, and as CEO of The Q 99.7 radio station.
                                                    I have also served on two college boards, as well as many committees and advisory teams.  </p>
                                                <p class="moto-text_system_10 moto-spacing-top-small">

                                                My wife, Tina, and I have been married for over 26 years and have two grown children, Makenna and Tytus. </p>
                                            </div>
                                        </div>
                                       <!-- <div data-widget-id="wid__button__5956decd1ff52"
                                             class="moto-widget moto-widget-button moto-preset-4 moto-align-left moto-spacing-top-auto moto-spacing-right-auto moto-spacing-bottom-auto moto-spacing-left-small "
                                             data-widget="button" data-preset="4">
                                            <a href="tell:508-845-0800" data-action="page"
                                               class="moto-widget-button-link moto-size-medium moto-link">
                                                <span class="fa moto-widget-theme-icon"></span>
                                                <span class="moto-widget-button-label"></span>508-845-0800 ext 1008</a>
                                        </div>--!>
                                        <div data-widget-id="wid__button__5956decd1ff52"
                                             class="moto-widget moto-widget-button moto-preset-4 moto-align-left moto-spacing-top-auto moto-spacing-right-auto moto-spacing-bottom-auto moto-spacing-left-small "
                                             data-widget="button" data-preset="4">
                                            <a href="mailto:tmoen@libertychurches.org"
                                               class="moto-widget-social-links-extended__link moto-spacing-right-small">
                                                <span style="font-size: 2.5rem" class="moto-color1_4 moto-widget-social-links-extended__icon fas fa-envelope"></span>
                                            </a>
                                            <a href="https://www.facebook.com/TimothyMoen"
                                               class="moto-widget-social-links-extended__link moto-spacing-right-small" target="_blank">
                                                <span style="font-size: 2.5rem" class=" moto-color1_4 moto-widget-social-links-extended__icon fa fa-facebook"></span>
                                            </a>
                                            <a href="https://www.instagram.com/timothymoen"
                                               class="moto-widget-social-links-extended__link" target="_blank">
                                                <span style="font-size: 2.5rem" class=" moto-color1_4 moto-widget-social-links-extended__icon fa fa-instagram"></span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
