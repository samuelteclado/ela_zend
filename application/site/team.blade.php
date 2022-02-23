@extends('site.master.layout')
@section('content')
    @include('site.pastors.top')

    <section id="section-content" class="content page-10 moto-section" data-widget="section" data-container="section">
        <div
            class="moto-widget moto-widget-block moto-bg-color_custom5 moto-spacing-top-medium moto-spacing-right-auto moto-spacing-bottom-medium moto-spacing-left-auto"
            data-widget="block" data-spacing="lala">
            <div class="container-fluid">
                <div class="row">
                    <div class="moto-cell col-sm-12" data-container="container">
                        <div
                            class="moto-widget moto-widget-block row-fixed moto-bg-color_custom5 moto-spacing-right-auto moto-spacing-bottom-medium moto-spacing-left-auto"
                            data-widget="block" data-spacing="lala" data-bg-position="left top">
                            <div class="moto-cell col-sm-12" data-container="container">
                                <div class="moto-widget-text-content moto-widget-text-editable">
                                    <h2 style="text-align: center;" class="moto-text_system_7">Pastoral Team</h2>
                                </div>
                            </div>
                        </div>
                        <div
                            class="moto-widget moto-widget-row row-fixed moto-spacing-top-auto moto-spacing-right-auto moto-spacing-bottom-auto moto-spacing-left-auto"
                            data-grid-type="sm" data-widget="row" data-spacing="aaaa">
                            <div class="container-fluid">
                                <div class="row" data-container="container">
                                    <div class="moto-widget moto-widget-row__column moto-cell col-sm-2"
                                         data-widget="row.column" data-bgcolor-class="" data-container="container">
                                        <div data-widget-id="wid__image__5956decd1f863"
                                             class="moto-widget moto-widget-image moto-preset-default moto-align-center_mobile-h moto-spacing-top-auto moto-spacing-right-auto moto-spacing-bottom-small moto-spacing-left-auto "
                                             data-preset="default" data-widget="image">
                                            <div class="moto-widget-image-link">
                                                <img data-src="{{ asset('www/content/pastors/tim-moen.jpg') }}"
                                                     class="moto-widget-image-picture lazyload" data-id="184" title=""
                                                     alt="" draggable="false">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="moto-widget moto-widget-row__column moto-cell col-sm-4"
                                         data-widget="row.column" data-bgcolor-class="" data-container="container">
                                        <div data-widget-id="wid__text__5956decd1fa02"
                                             class="moto-widget moto-widget-text moto-preset-default  moto-spacing-top-auto moto-spacing-right-small moto-spacing-bottom-small moto-spacing-left-small "
                                             data-widget="text" data-preset="default" data-spacing="asss"
                                             data-animation="">
                                            <div class="moto-widget-text-content moto-widget-text-editable">
                                                <p class="moto-text_system_9">
                                                    <a target="_self" data-action="url" class="moto-link"
                                                       href="{{ route('site.pastors.tim-moen') }}">Tim Moen</a>
                                                </p>
                                                <p class="moto-text_system_11">Lead Pastor</p>
                                                <p class="moto-text_system_9">&nbsp;</p>
                                                <p class="moto-text_system_10">
                                                    I have served full time in the ministry for nearly my whole career.
                                                    I am compelled to live and lead a life of influence both within the
                                                    church and within the community. </p></div>
                                        </div>
                                        <div data-widget-id="wid__button__5956decd1ff52"
                                             class="moto-widget moto-widget-button moto-preset-4 moto-align-left moto-spacing-top-auto moto-spacing-right-auto moto-spacing-bottom-auto moto-spacing-left-small "
                                             data-widget="button" data-preset="4">
                                            <a href="{{ route('site.pastors.tim-moen') }}" data-action="page"
                                               class="moto-widget-button-link moto-size-medium moto-link">
                                            <span class="fa moto-widget-theme-icon">
                                            </span>
                                                <span class="moto-widget-button-label">Read more</span>
                                            </a>
                                        </div>
                                    </div>

                                    <div class="moto-widget moto-widget-row__column moto-cell col-sm-2"
                                         data-widget="row.column" data-bgcolor-class="" data-container="container">
                                        <div data-widget-id="wid__image__5956decd1f863"
                                             class="moto-widget moto-widget-image moto-preset-default moto-align-center_mobile-h moto-spacing-top-auto moto-spacing-right-auto moto-spacing-bottom-small moto-spacing-left-auto "
                                             data-preset="default" data-widget="image">
                                            <div class="moto-widget-image-link">
                                                <img data-src="{{ asset('www/content/pastors/mary.jpg') }}"
                                                     class="moto-widget-image-picture lazyload" data-id="184" title=""
                                                     alt="" draggable="false">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="moto-widget moto-widget-row__column moto-cell col-sm-4"
                                         data-widget="row.column" data-bgcolor-class="" data-container="container">
                                        <div data-widget-id="wid__text__5956decd1fa02"
                                             class="moto-widget moto-widget-text moto-preset-default  moto-spacing-top-auto moto-spacing-right-small moto-spacing-bottom-small moto-spacing-left-small "
                                             data-widget="text" data-preset="default" data-spacing="asss"
                                             data-animation="">
                                            <div class="moto-widget-text-content moto-widget-text-editable">
                                                <p class="moto-text_system_9">
                                                    <a target="_self" data-action="url" class="moto-link"
                                                       href="{{ route('site.pastors.mary-bard') }}">Mary Bard</a>
                                                </p>
                                                <p class="moto-text_system_11">Worship/Women/Administration</p>
                                                <p class="moto-text_system_9">&nbsp;</p>
                                                <p class="moto-text_system_10">I was raised to be in the ministry...
                                                    plain and simple. My parents were not going to have it any other way
                                                    and for that I am very thankful.</p></div>
                                        </div>
                                        <div data-widget-id="wid__button__5956decd1ff52"
                                             class="moto-widget moto-widget-button moto-preset-4 moto-align-left moto-spacing-top-auto moto-spacing-right-auto moto-spacing-bottom-auto moto-spacing-left-small "
                                             data-widget="button" data-preset="4">
                                            <a href="{{ route('site.pastors.mary-bard') }}" data-action="page"
                                               class="moto-widget-button-link moto-size-medium moto-link">
                                            <span class="fa moto-widget-theme-icon">
                                            </span>
                                                <span class="moto-widget-button-label">Read more</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div
                            class="moto-widget moto-widget-row row-fixed moto-spacing-top-auto moto-spacing-right-auto moto-spacing-bottom-auto moto-spacing-left-auto"
                            data-grid-type="sm" data-widget="row" data-spacing="aaaa">
                            <div class="container-fluid">
                                <div class="row" data-container="container">

                                    <div class="moto-widget moto-widget-row__column moto-cell col-sm-2"
                                         data-widget="row.column" data-bgcolor-class="" data-container="container">
                                        <div data-widget-id="wid__image__5956decd1f863"
                                             class="moto-widget moto-widget-image moto-preset-default moto-align-center_mobile-h moto-spacing-top-auto moto-spacing-right-auto moto-spacing-bottom-small moto-spacing-left-auto "
                                             data-preset="default" data-widget="image">
                                            <div class="moto-widget-image-link">
                                                <img data-src="{{ asset('www/content/pastors/tina.jpg') }}"
                                                     class="moto-widget-image-picture lazyload" data-id="184"
                                                     title="" alt="" draggable="false">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="moto-widget moto-widget-row__column moto-cell col-sm-4"
                                         data-widget="row.column" data-bgcolor-class="" data-container="container">
                                        <div data-widget-id="wid__text__5956decd1fa02"
                                             class="moto-widget moto-widget-text moto-preset-default  moto-spacing-top-auto moto-spacing-right-small moto-spacing-bottom-small moto-spacing-left-small "
                                             data-widget="text" data-preset="default" data-spacing="asss"
                                             data-animation="">
                                            <div class="moto-widget-text-content moto-widget-text-editable">
                                                <p class="moto-text_system_9">
                                                    <a target="_self" data-action="url" class="moto-link"
                                                       href="{{ route('site.pastors.tina') }}">Tina Moen</a>
                                                </p>
                                                <p class="moto-text_system_11">Ministry Development</p>
                                                <p class="moto-text_system_9">&nbsp;</p>
                                                <p class="moto-text_system_10">I love God and I love people!
                                                    Having grown up as a military child, an “Air Force Brat,”
                                                    I can relate with the quote, “There are no strangers, only
                                                    friends I’ve yet to meet.”
                                                    The people and places of my life have constantly changed, but
                                                    God … He has always remained the same!</p></div>
                                        </div>
                                        <div data-widget-id="wid__button__5956decd1ff52"
                                             class="moto-widget moto-widget-button moto-preset-4 moto-align-left moto-spacing-top-auto moto-spacing-right-auto moto-spacing-bottom-auto moto-spacing-left-small "
                                             data-widget="button" data-preset="4">
                                            <a href="{{ route('site.pastors.tina') }}" data-action="page"
                                               class="moto-widget-button-link moto-size-medium moto-link">
                                                <span class="fa moto-widget-theme-icon">
                                                </span>
                                                <span class="moto-widget-button-label">Read more</span>
                                            </a>
                                        </div>
                                    </div>


                                    <div class="moto-widget moto-widget-row__column moto-cell col-sm-2"
                                         data-widget="row.column" data-bgcolor-class="" data-container="container">
                                        <div data-widget-id="wid__image__5956decd1f863"
                                             class="moto-widget moto-widget-image moto-preset-default moto-align-center_mobile-h moto-spacing-top-auto moto-spacing-right-auto moto-spacing-bottom-small moto-spacing-left-auto "
                                             data-preset="default" data-widget="image">
                                            <div class="moto-widget-image-link">
                                                <img data-src="{{ asset('www/content/pastors/chris-hurtado.jpg') }}"
                                                     class="moto-widget-image-picture lazyload" data-id="184"
                                                     title="" alt="" draggable="false">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="moto-widget moto-widget-row__column moto-cell col-sm-4"
                                         data-widget="row.column" data-bgcolor-class="" data-container="container">
                                        <div data-widget-id="wid__text__5956decd1fa02"
                                             class="moto-widget moto-widget-text moto-preset-default  moto-spacing-top-auto moto-spacing-right-small moto-spacing-bottom-small moto-spacing-left-small "
                                             data-widget="text" data-preset="default" data-spacing="asss"
                                             data-animation="">
                                            <div class="moto-widget-text-content moto-widget-text-editable">
                                                <p class="moto-text_system_9">
                                                    <a target="_self" data-action="url" class="moto-link"
                                                       href="{{ route('site.pastors.chris-hurtado') }}">Chris
                                                        Hurtado</a>
                                                </p>
                                                <p class="moto-text_system_11">Youth Ministry</p>
                                                <p class="moto-text_system_9">&nbsp;</p>
                                                <p class="moto-text_system_10">I believe that biblically empowered
                                                    young people will change the world.
                                                    I serve as the interim Youth Pastor here at Liberty Church where I
                                                    oversee the student ministry for Junior High and High School
                                                    students</p></div>
                                        </div>
                                        <div data-widget-id="wid__button__5956decd1ff52"
                                             class="moto-widget moto-widget-button moto-preset-4 moto-align-left moto-spacing-top-auto moto-spacing-right-auto moto-spacing-bottom-auto moto-spacing-left-small "
                                             data-widget="button" data-preset="4">
                                            <a href="{{ route('site.pastors.chris-hurtado') }}" data-action="page"
                                               class="moto-widget-button-link moto-size-medium moto-link">
                                                <span class="fa moto-widget-theme-icon">
                                                </span>
                                                <span class="moto-widget-button-label">Read more</span>
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

        <div
            class="moto-widget moto-widget-block moto-bg-color_custom6 moto-spacing-top-small moto-spacing-right-auto moto-spacing-bottom-small moto-spacing-left-auto"
            data-widget="block" data-spacing="lala">
            <div class="container-fluid">
                <div class="row">
                    <div class="moto-cell col-sm-12" data-container="container">
                        <div
                            class="moto-widget moto-widget-block row-fixed  moto-spacing-right-auto  moto-spacing-top-small moto-spacing-bottom-medium moto-spacing-left-auto"
                            data-widget="block" data-spacing="lala" data-bg-position="left top">
                            <div class="moto-cell col-sm-12" data-container="container">
                                <div class="moto-widget-text-content moto-widget-text-editable">
                                    <h2 style="text-align: center;" class="moto-text_system_7">Staff</h2>
                                </div>
                            </div>
                        </div>
                        <div
                            class="moto-widget moto-widget-row row-fixed moto-spacing-top-auto moto-spacing-right-auto moto-spacing-bottom-auto moto-spacing-left-auto"
                            data-grid-type="sm" data-widget="row" data-spacing="aaaa">
                            <div class="container-fluid">
                                <div class="row" data-container="container">
                                    <div class="moto-widget moto-widget-row__column moto-cell col-sm-4"
                                         data-widget="row.column" data-bgcolor-class="" data-container="container">
                                        <div data-widget-id="wid__image__5956decd1f863"
                                             class="moto-widget moto-widget-image moto-preset-default moto-align-center_mobile-h moto-spacing-top-auto moto-spacing-right-auto moto-spacing-bottom-small moto-spacing-left-auto "
                                             data-preset="default" data-widget="image">
                                            <div class="moto-widget-image-link moto-spacing-bottom-small">
                                                <img data-src="{{ asset('www/content/img/staff/samuel.jpg') }}"
                                                     class="moto-widget-image-picture lazyload" data-id="184" title=""
                                                     alt="" draggable="false">
                                            </div>
                                            <p class="moto-text_system_9">Samuel Rocha</p>
                                            <p class="moto-text_system_11">Worship/Media</p>
                                            <p class="moto-text_system_10">
                                                <a href="mailto:worship@libertychurches.org">
                                                    worship@libertychurches.org
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="moto-widget moto-widget-row__column moto-cell col-sm-4"
                                         data-widget="row.column" data-bgcolor-class="" data-container="container">
                                        <div data-widget-id="wid__image__5956decd1f863"
                                             class="moto-widget moto-widget-image moto-preset-default moto-align-center_mobile-h moto-spacing-top-auto moto-spacing-right-auto moto-spacing-bottom-small moto-spacing-left-auto "
                                             data-preset="default" data-widget="image">
                                            <div class="moto-widget-image-link moto-spacing-bottom-small">
                                                <img data-src="{{ asset('www/content/img/staff/ever1.jpg') }}"
                                                     class="moto-widget-image-picture lazyload" data-id="184" title=""
                                                     alt="" draggable="false">
                                            </div>
                                            <p class="moto-text_system_9">Ever Feliciano</p>
                                            <p class="moto-text_system_11">Media/Graphics</p>
                                            <p class="moto-text_system_10">
                                                <a href="mailto:media@libertychurches.org">
                                                    media@libertychurches.org
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="moto-widget moto-widget-row__column moto-cell col-sm-4"
                                         data-widget="row.column" data-bgcolor-class="" data-container="container">
                                        <div data-widget-id="wid__image__5956decd1f863"
                                             class="moto-widget moto-widget-image moto-preset-default moto-align-center_mobile-h moto-spacing-top-auto moto-spacing-right-auto moto-spacing-bottom-small moto-spacing-left-auto "
                                             data-preset="default" data-widget="image">
                                            <div class="moto-widget-image-link moto-spacing-bottom-small">
                                                <img data-src="{{ asset('www/content/img/staff/patri.jpg') }}"
                                                     class="moto-widget-image-picture lazyload" data-id="184"
                                                     title="Patrick"
                                                     alt="" draggable="false">
                                            </div>
                                            <p class="moto-text_system_9">Patrick Schultz</p>
                                            <p class="moto-text_system_11">Graphics</p>
                                        </div>
                                    </div>
                                    <div class="moto-widget moto-widget-row__column moto-cell col-sm-4"
                                         data-widget="row.column" data-bgcolor-class="" data-container="container">
                                        <div data-widget-id="wid__image__5956decd1f863"
                                             class="moto-widget moto-widget-image moto-preset-default moto-align-center_mobile-h moto-spacing-top-auto moto-spacing-right-auto moto-spacing-bottom-small moto-spacing-left-auto "
                                             data-preset="default" data-widget="image">
                                            <div class="moto-widget-image-link moto-spacing-bottom-small">
                                                <img data-src="{{ asset('www/content/img/staff/lori.jpg') }}"
                                                     class="moto-widget-image-picture lazyload" data-id="184" title=""
                                                     alt="" draggable="false">
                                            </div>
                                            <p class="moto-text_system_9">Lori Cladis</p>
                                            <p class="moto-text_system_11">Finance Officer</p>
                                            <p class="moto-text_system_10">
                                                <a href="mailto:lcladis@libertychurches.org">
                                                    lcladis@libertychurches.org
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="moto-widget moto-widget-row__column moto-cell col-sm-4"
                                         data-widget="row.column" data-bgcolor-class="" data-container="container">
                                        <div data-widget-id="wid__image__5956decd1f863"
                                             class="moto-widget moto-widget-image moto-preset-default moto-align-center_mobile-h moto-spacing-top-auto moto-spacing-right-auto moto-spacing-bottom-small moto-spacing-left-auto "
                                             data-preset="default" data-widget="image">
                                            <div class="moto-widget-image-link moto-spacing-bottom-small">
                                                <img data-src="{{ asset('www/content/img/staff/jonny.jpeg') }}"
                                                     class="moto-widget-image-picture lazyload" data-id="184" title=""
                                                     alt="" draggable="false">
                                            </div>
                                            <p class="moto-text_system_9">Jonny Mcintosh</p>
                                            <p class="moto-text_system_11">KidsLife Director</p>
                                            <p class="moto-text_system_10">
                                                <a href="mailto:jmcintosh@libertychurches.org ">
                                                    jmcintosh@libertychurches.org
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="moto-widget moto-widget-row__column moto-cell col-sm-4"
                                         data-widget="row.column" data-bgcolor-class="" data-container="container">
                                        <div data-widget-id="wid__image__5956decd1f863"
                                             class="moto-widget moto-widget-image moto-preset-default moto-align-center_mobile-h moto-spacing-top-auto moto-spacing-right-auto moto-spacing-bottom-small moto-spacing-left-auto "
                                             data-preset="default" data-widget="image">
                                            <div class="moto-widget-image-link moto-spacing-bottom-small">
                                                <img data-src="{{ asset('www/content/img/staff/yellonda.jpg') }}"
                                                     class="moto-widget-image-picture lazyload" data-id="184" title=""
                                                     alt="" draggable="false">
                                            </div>
                                            <p class="moto-text_system_9">Yelonda Monroe-Carroll</p>
                                            <p class="moto-text_system_11">KidsLife Director</p>
                                            <p class="moto-text_system_10">
                                                <a href="mailto:ycarroll@libertychurches.org ">
                                                    ycarroll@libertychurches.org
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="moto-widget moto-widget-row__column moto-cell col-sm-4"
                                         data-widget="row.column" data-bgcolor-class="" data-container="container">
                                        <div data-widget-id="wid__image__5956decd1f863"
                                             class="moto-widget moto-widget-image moto-preset-default moto-align-center_mobile-h moto-spacing-top-auto moto-spacing-right-auto moto-spacing-bottom-small moto-spacing-left-auto "
                                             data-preset="default" data-widget="image">
                                            <div class="moto-widget-image-link moto-spacing-bottom-small">
                                                <img data-src="{{ asset('www/content/img/staff/jordan.jpeg') }}"
                                                     class="moto-widget-image-picture lazyload" data-id="184" title=""
                                                     alt="" draggable="false">
                                            </div>
                                            <p class="moto-text_system_9">Jordan Reed</p>
                                            <p class="moto-text_system_11">Admin Support</p>
                                            <p class="moto-text_system_10">
                                                <a href="mailto:jreed@libertychurches.org ">
                                                    jreed@libertychurches.org
                                                </a>
                                            </p>
                                        </div>
                                    </div>


                                    <div class="moto-widget moto-widget-row__column moto-cell col-sm-4"
                                         data-widget="row.column" data-bgcolor-class="" data-container="container">
                                        <div data-widget-id="wid__image__5956decd1f863"
                                             class="moto-widget moto-widget-image moto-preset-default moto-align-center_mobile-h moto-spacing-top-auto moto-spacing-right-auto moto-spacing-bottom-small moto-spacing-left-auto "
                                             data-preset="default" data-widget="image">
                                            <div class="moto-widget-image-link moto-spacing-bottom-small">
                                                <img data-src="{{ asset('www/content/img/staff/steve.jpeg') }}"
                                                     class="moto-widget-image-picture lazyload" data-id="184" title=""
                                                     alt="" draggable="false">
                                            </div>
                                            <p class="moto-text_system_9">Steve White</p>
                                            <p class="moto-text_system_11">Property Manager</p>
                                            <p class="moto-text_system_10">
                                                <a href="mailto:swhite@libertychurches.org ">
                                                    swhite@libertychurches.org
                                                </a>
                                            </p>
                                        </div>
                                    </div>


                                    <div class="moto-widget moto-widget-row__column moto-cell col-sm-4"
                                         data-widget="row.column" data-bgcolor-class="" data-container="container">
                                        <div data-widget-id="wid__image__5956decd1f863"
                                             class="moto-widget moto-widget-image moto-preset-default moto-align-center_mobile-h moto-spacing-top-auto moto-spacing-right-auto moto-spacing-bottom-small moto-spacing-left-auto "
                                             data-preset="default" data-widget="image">
                                            <div class="moto-widget-image-link moto-spacing-bottom-small">
                                                <img data-src="{{ asset('www/content/img/staff/jimmy.jpg') }}"
                                                     class="moto-widget-image-picture lazyload" data-id="184" title=""
                                                     alt="" draggable="false">
                                            </div>
                                            <p class="moto-text_system_9">Jimmy Rossetti</p>
                                            <p class="moto-text_system_11">Property Maintenance/Custodian</p>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div
            class="moto-widget moto-widget-block moto-bg-color_custom5 moto-spacing-top-small moto-spacing-right-auto moto-spacing-bottom-large moto-spacing-left-auto"
            data-widget="block" data-spacing="lala">
            <div class="container-fluid">
                <div class="row">
                    <div class="moto-cell col-sm-12" data-container="container">
                        <div
                            class="moto-widget moto-widget-block row-fixed  moto-spacing-right-auto  moto-spacing-top-small moto-spacing-bottom-medium moto-spacing-left-auto"
                            data-widget="block" data-spacing="lala" data-bg-position="left top">
                            <div class="moto-cell col-sm-12" data-container="container">
                                <div class="moto-widget-text-content moto-widget-text-editable">
                                    <h2 style="text-align: center;" class="moto-text_system_7">Board of Directors</h2>
                                </div>
                            </div>
                        </div>
                        <div
                            class="moto-widget moto-widget-row row-fixed moto-spacing-top-small moto-spacing-right-auto moto-spacing-bottom-zero moto-spacing-left-auto"
                            data-grid-type="sm" data-widget="row" data-spacing="aaaa">
                            <div class="container-fluid">
                                <div class="row" data-container="container">
                                    <div class="col-sm-4">
                                        <div
                                            class="moto-widget moto-widget-row__column moto-cell col-sm-8 moto-spacing-right-medium moto-spacing-left-medium"
                                            data-widget="row.column" data-container="container">
                                            <div data-widget-id="wid__image__5956decd1f863"
                                                 class="moto-widget moto-widget-image moto-preset-default moto-align-center_mobile-h moto-spacing-top-auto moto-spacing-right-auto moto-spacing-bottom-small moto-spacing-left-auto "
                                                 data-preset="default" data-widget="image">
                                                <div class="moto-widget-image-link moto-spacing-bottom-small">
                                                    <img data-src="{{ asset('www/content/img/boarder/ted.jpg') }}"
                                                         class="moto-widget-image-picture lazyload" data-id="184"
                                                         title=""
                                                         alt="" draggable="false">
                                                </div>
                                                <p class="moto-text_system_13">Ted Kim</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div
                                            class="moto-widget moto-widget-row__column moto-cell col-sm-8 moto-spacing-right-medium "
                                            data-widget="row.column" data-bgcolor-class="" data-container="container">
                                            <div data-widget-id="wid__image__5956decd1f863"
                                                 class="moto-widget moto-widget-image moto-preset-default moto-align-center_mobile-h moto-spacing-top-auto moto-spacing-right-auto moto-spacing-bottom-small moto-spacing-left-auto "
                                                 data-preset="default" data-widget="image">
                                                <div class="moto-widget-image-link moto-spacing-bottom-small">
                                                    <img data-src="{{ asset('www/content/img/boarder/evelyns.jpg') }}"
                                                         class="moto-widget-image-picture lazyload" data-id="184"
                                                         title=""
                                                         alt="" draggable="false">
                                                </div>
                                                <p class="moto-text_system_13">Evelyn Sandoval</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="moto-widget moto-widget-row__column moto-cell col-sm-8"
                                             data-widget="row.column" data-bgcolor-class="" data-container="container">
                                            <div data-widget-id="wid__image__5956decd1f863"
                                                 class="moto-widget moto-widget-image moto-preset-default moto-align-center_mobile-h moto-spacing-top-auto moto-spacing-right-auto moto-spacing-bottom-small moto-spacing-left-auto "
                                                 data-preset="default" data-widget="image">
                                                <div class="moto-widget-image-link moto-spacing-bottom-small">
                                                    <img data-src="{{ asset('www/content/img/boarder/jim.jpg') }}"
                                                         class="moto-widget-image-picture lazyload" data-id="184"
                                                         title=""
                                                         alt="" draggable="false">
                                                </div>
                                                <p class="moto-text_system_13">Jim Cahill</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="moto-widget moto-widget-row__column moto-cell col-sm-8"
                                             data-widget="row.column" data-bgcolor-class="" data-container="container">
                                            <div data-widget-id="wid__image__5956decd1f863"
                                                 class="moto-widget moto-widget-image moto-preset-default moto-align-center_mobile-h moto-spacing-top-auto moto-spacing-right-auto moto-spacing-bottom-small moto-spacing-left-auto "
                                                 data-preset="default" data-widget="image">
                                                <div class="moto-widget-image-link moto-spacing-bottom-small">
                                                    <img data-src="{{ asset('www/content/img/boarder/evelynp.jpg') }}"
                                                         class="moto-widget-image-picture lazyload" data-id="184"
                                                         title=""
                                                         alt="" draggable="false">
                                                </div>
                                                <p class="moto-text_system_13">Evelyn Pereira</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="moto-widget moto-widget-row__column moto-cell col-sm-8"
                                             data-widget="row.column" data-bgcolor-class="" data-container="container">
                                            <div data-widget-id="wid__image__5956decd1f863"
                                                 class="moto-widget moto-widget-image moto-preset-default moto-align-center_mobile-h moto-spacing-top-auto moto-spacing-right-auto moto-spacing-bottom-small moto-spacing-left-auto "
                                                 data-preset="default" data-widget="image">
                                                <div class="moto-widget-image-link moto-spacing-bottom-small">
                                                    <img data-src="{{ asset('www/content/img/boarder/jeffw.jpg') }}"
                                                         class="moto-widget-image-picture lazyload" data-id="184"
                                                         title=""
                                                         alt="" draggable="false">
                                                </div>
                                                <p class="moto-text_system_13">Jeff Watson</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="moto-widget moto-widget-row__column moto-cell col-sm-8"
                                             data-widget="row.column" data-bgcolor-class="" data-container="container">
                                            <div data-widget-id="wid__image__5956decd1f863"
                                                 class="moto-widget moto-widget-image moto-preset-default moto-align-center_mobile-h moto-spacing-top-auto moto-spacing-right-auto moto-spacing-bottom-small moto-spacing-left-auto "
                                                 data-preset="default" data-widget="image">
                                                <div class="moto-widget-image-link moto-spacing-bottom-small">
                                                    <img data-src="{{ asset('www/content/img/boarder/kate.jpg') }}"
                                                         class="moto-widget-image-picture lazyload" data-id="184"
                                                         title=""
                                                         alt="" draggable="false">
                                                </div>
                                                <p class="moto-text_system_13">Kate Hallett</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div
            class="moto-widget moto-widget-block moto-bg-color_custom6 moto-spacing-top-small moto-spacing-right-auto moto-spacing-bottom-small moto-spacing-left-auto"
            data-widget="block" data-spacing="lala">
            <div class="container-fluid">
                <div class="row">
                    <div class="moto-cell col-sm-12" data-container="container">
                        <div
                            class="moto-widget moto-widget-block row-fixed  moto-spacing-right-auto  moto-spacing-top-small moto-spacing-bottom-medium moto-spacing-left-auto"
                            data-widget="block" data-spacing="lala" data-bg-position="left top">
                            <div class="moto-cell col-sm-12" data-container="container">
                                <div class="moto-widget-text-content moto-widget-text-editable">
                                    <h2 style="text-align: center;" class="moto-text_system_7">Ministry Directors</h2>
                                </div>
                            </div>
                        </div>
                        <div
                            class="moto-widget moto-widget-row row-fixed moto-spacing-top-auto moto-spacing-right-auto moto-spacing-bottom-auto moto-spacing-left-auto"
                            data-grid-type="sm" data-widget="row" data-spacing="aaaa">
                            <div class="container-fluid">
                                <div class="row" data-container="container">
                                    <div class="moto-widget moto-widget-row__column moto-cell col-sm-4"
                                         data-widget="row.column" data-bgcolor-class="" data-container="container">
                                        <div data-widget-id="wid__image__5956decd1f863"
                                             class="moto-widget moto-widget-image moto-preset-default moto-align-center_mobile-h moto-spacing-top-auto moto-spacing-right-auto moto-spacing-bottom-small moto-spacing-left-auto "
                                             data-preset="default" data-widget="image">
                                            <div class="moto-widget-image-link moto-spacing-bottom-small">
                                                <img data-src="{{ asset('www/content/img/leaders/liz.jpg') }}"
                                                     class="moto-widget-image-picture lazyload" data-id="184" title=""
                                                     alt="" draggable="false">
                                            </div>
                                            <p class="moto-text_system_9">Ron & Liz Belanger</p>
                                            <p class="moto-text_system_11">Royal Family Kids Camp</p>
                                            <p class="moto-text_system_10">
                                                <a href="mailto:royalfamilykids@libertychurches.org">
                                                    royalfamilykids@libertychurches.org
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="moto-widget moto-widget-row__column moto-cell col-sm-4"
                                         data-widget="row.column" data-bgcolor-class="" data-container="container">
                                        <div data-widget-id="wid__image__5956decd1f863"
                                             class="moto-widget moto-widget-image moto-preset-default moto-align-center_mobile-h moto-spacing-top-auto moto-spacing-right-auto moto-spacing-bottom-small moto-spacing-left-auto "
                                             data-preset="default" data-widget="image">
                                            <div class="moto-widget-image-link moto-spacing-bottom-small">
                                                <img data-src="{{ asset('www/content/img/leaders/frank.jpg') }}"
                                                     class="moto-widget-image-picture lazyload" data-id="184" title=""
                                                     alt="" draggable="false">
                                            </div>
                                            <p class="moto-text_system_9">Frank & Betsey Cavallo</p>
                                            <p class="moto-text_system_11">Marriage Ministry</p>
                                            <p class="moto-text_system_10">
                                                <a href="mailto:marriageministry@libertychurches.org">
                                                    marriageministry@libertychurches.org
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="moto-widget moto-widget-row__column moto-cell col-sm-4"
                                         data-widget="row.column" data-bgcolor-class="" data-container="container">
                                        <div data-widget-id="wid__image__5956decd1f863"
                                             class="moto-widget moto-widget-image moto-preset-default moto-align-center_mobile-h moto-spacing-top-auto moto-spacing-right-auto moto-spacing-bottom-small moto-spacing-left-auto "
                                             data-preset="default" data-widget="image">
                                            <div class="moto-widget-image-link moto-spacing-bottom-small">
                                                <img data-src="{{ asset('www/content/img/leaders/samuel.jpg') }}"
                                                     class="moto-widget-image-picture lazyload" data-id="184" title=""
                                                     alt="" draggable="false">
                                            </div>
                                            <p class="moto-text_system_9">Pastor Samuel Kariuki</p>
                                            <p class="moto-text_system_11">African Fellowship Ministry</p>
                                            <p class="moto-text_system_10">
                                                <a href="mailto:africanministry@libertychurches.org">
                                                    africanministry@libertychurches.org
                                                </a>
                                            </p>
                                        </div>
                                    </div>

                                    <div class="moto-widget moto-widget-row__column moto-cell col-sm-4"
                                         data-widget="row.column" data-bgcolor-class="" data-container="container">
                                        <div data-widget-id="wid__image__5956decd1f863"
                                             class="moto-widget moto-widget-image moto-preset-default moto-align-center_mobile-h moto-spacing-top-auto moto-spacing-right-auto moto-spacing-bottom-small moto-spacing-left-auto "
                                             data-preset="default" data-widget="image">
                                            <div class="moto-widget-image-link moto-spacing-bottom-small">
                                                <img data-src="{{ asset('www/content/img/leaders/bebe.jpg') }}"
                                                     class="moto-widget-image-picture lazyload" data-id="184" title=""
                                                     alt="" draggable="false">
                                            </div>
                                            <p class="moto-text_system_9">Bebe Lavergne</p>
                                            <p class="moto-text_system_11">Liberty Worship, Music Director</p>
                                            <p class="moto-text_system_10">
                                                <a href="mailto:lcladis@libertychurches.org">
                                                    worship@libertychurches.org
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="moto-widget moto-widget-row__column moto-cell col-sm-4"
                                         data-widget="row.column" data-bgcolor-class="" data-container="container">
                                        <div data-widget-id="wid__image__5956decd1f863"
                                             class="moto-widget moto-widget-image moto-preset-default moto-align-center_mobile-h moto-spacing-top-auto moto-spacing-right-auto moto-spacing-bottom-small moto-spacing-left-auto "
                                             data-preset="default" data-widget="image">
                                            <div class="moto-widget-image-link moto-spacing-bottom-small">
                                                <img data-src="{{ asset('www/content/img/leaders/dana.jpg') }}"
                                                     class="moto-widget-image-picture lazyload" data-id="184" title=""
                                                     alt="" draggable="false">
                                            </div>
                                            <p class="moto-text_system_9">Diana Lavergne</p>
                                            <p class="moto-text_system_11">Liberty Worship, Admin</p>
                                            <p class="moto-text_system_10">
                                                <a href="mailto:lcladis@libertychurches.org">
                                                    worship@libertychurches.org
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="moto-widget moto-widget-row__column moto-cell col-sm-4"
                                         data-widget="row.column" data-bgcolor-class="" data-container="container">
                                        <div data-widget-id="wid__image__5956decd1f863"
                                             class="moto-widget moto-widget-image moto-preset-default moto-align-center_mobile-h moto-spacing-top-auto moto-spacing-right-auto moto-spacing-bottom-small moto-spacing-left-auto "
                                             data-preset="default" data-widget="image">
                                            <div class="moto-widget-image-link moto-spacing-bottom-small">
                                                <img data-src="{{ asset('www/content/img/leaders/Tyler.jpg') }}"
                                                     class="moto-widget-image-picture lazyload" data-id="184" title=""
                                                     alt="" draggable="false">
                                            </div>
                                            <p class="moto-text_system_9">Tyler Keenan</p>
                                            <p class="moto-text_system_11">Liberty Worship, Creative Arts Director</p>
                                            <p class="moto-text_system_10">
                                                <a href="mailto:worship@libertychurches.org">
                                                    worship@libertychurches.org
                                                </a>
                                            </p>
                                        </div>
                                    </div>


                                    <div class="moto-widget moto-widget-row__column moto-cell col-sm-4"
                                         data-widget="row.column" data-bgcolor-class="" data-container="container">
                                        <div data-widget-id="wid__image__5956decd1f863"
                                             class="moto-widget moto-widget-image moto-preset-default moto-align-center_mobile-h moto-spacing-top-auto moto-spacing-right-auto moto-spacing-bottom-small moto-spacing-left-auto "
                                             data-preset="default" data-widget="image">
                                            <div class="moto-widget-image-link moto-spacing-bottom-small">
                                                <img data-src="{{ asset('www/content/img/leaders/Chris.jpg') }}"
                                                     class="moto-widget-image-picture lazyload" data-id="184" title=""
                                                     alt="" draggable="false">
                                            </div>
                                            <p class="moto-text_system_9">Chris Lavergne</p>
                                            <p class="moto-text_system_11">Liberty Worship, Vocal Director</p>
                                            <p class="moto-text_system_10">
                                                <a href="mailto:lcladis@libertychurches.org">
                                                    worship@libertychurches.org
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="moto-widget moto-widget-row__column moto-cell col-sm-4"
                                         data-widget="row.column" data-bgcolor-class="" data-container="container">
                                        <div data-widget-id="wid__image__5956decd1f863"
                                             class="moto-widget moto-widget-image moto-preset-default moto-align-center_mobile-h moto-spacing-top-auto moto-spacing-right-auto moto-spacing-bottom-small moto-spacing-left-auto "
                                             data-preset="default" data-widget="image">
                                            <div class="moto-widget-image-link moto-spacing-bottom-small">
                                                <img data-src="{{ asset('www/content/img/leaders/Kathy.jpeg') }}"
                                                     class="moto-widget-image-picture lazyload" data-id="184" title=""
                                                     alt="" draggable="false">
                                            </div>
                                            <p class="moto-text_system_9">Kathy Lavergne</p>
                                            <p class="moto-text_system_11">Liberty Worship, Vocal Director</p>
                                            <p class="moto-text_system_10">
                                                <a href="mailto:lcladis@libertychurches.org">
                                                    worship@libertychurches.org
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="moto-widget moto-widget-row__column moto-cell col-sm-4"
                                         data-widget="row.column" data-bgcolor-class="" data-container="container">
                                        <div data-widget-id="wid__image__5956decd1f863"
                                             class="moto-widget moto-widget-image moto-preset-default moto-align-center_mobile-h moto-spacing-top-auto moto-spacing-right-auto moto-spacing-bottom-small moto-spacing-left-auto "
                                             data-preset="default" data-widget="image">
                                            <div class="moto-widget-image-link moto-spacing-bottom-small">
                                                <img data-src="{{ asset('www/content/img/leaders/mera.jpg') }}"
                                                     class="moto-widget-image-picture lazyload" data-id="184" title=""
                                                     alt="" draggable="false">
                                            </div>
                                            <p class="moto-text_system_9">Marelyn Malinowski</p>
                                            <p class="moto-text_system_11">First Impressions</p>
                                            <p class="moto-text_system_10">
                                                <a href="mailto:firstimpressions@libertychurches.org">
                                                    firstimpressions@libertychurches.org
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="moto-widget moto-widget-row__column moto-cell col-sm-4"
                                         data-widget="row.column" data-bgcolor-class="" data-container="container">
                                        <div data-widget-id="wid__image__5956decd1f863"
                                             class="moto-widget moto-widget-image moto-preset-default moto-align-center_mobile-h moto-spacing-top-auto moto-spacing-right-auto moto-spacing-bottom-small moto-spacing-left-auto "
                                             data-preset="default" data-widget="image">
                                            <div class="moto-widget-image-link moto-spacing-bottom-small">
                                                <img data-src="{{ asset('www/content/img/leaders/jeff_w.jpg') }}"
                                                     class="moto-widget-image-picture lazyload" data-id="184" title=""
                                                     alt="" draggable="false">
                                            </div>
                                            <p class="moto-text_system_9">Jeff & Dina Watson</p>
                                            <p class="moto-text_system_11">Celebrate Recovery</p>
                                            <p class="moto-text_system_10">
                                                <a href="mailto:celebraterecovery@libertychurches.org">
                                                    celebraterecovery@libertychurches.org
                                                </a>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="moto-widget moto-widget-row__column moto-cell col-sm-4"
                                         data-widget="row.column" data-bgcolor-class="" data-container="container">
                                        <div data-widget-id="wid__image__5956decd1f863"
                                             class="moto-widget moto-widget-image moto-preset-default moto-align-center_mobile-h moto-spacing-top-auto moto-spacing-right-auto moto-spacing-bottom-small moto-spacing-left-auto "
                                             data-preset="default" data-widget="image">
                                            <div class="moto-widget-image-link moto-spacing-bottom-small">
                                                <img data-src="{{ asset('www/content/img/leaders/david.jpg') }}"
                                                     class="moto-widget-image-picture lazyload" data-id="184" title=""
                                                     alt="" draggable="false">
                                            </div>
                                            <p class="moto-text_system_9">David Zukauskas, Sr</p>
                                            <p class="moto-text_system_11">Security</p>
                                            <p class="moto-text_system_10">
                                                <a href="mailto:info@libertychurches.org">
                                                    info@libertychurches.org
                                                </a>
                                            </p>
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
