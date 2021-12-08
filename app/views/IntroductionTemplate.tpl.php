<?php

namespace kivweb_sp\views;

/////////////////////////////////////////////////////////////
/////////// Sablona pro zobrazeni uvodni stranky  ///////////
/////////////////////////////////////////////////////////////
// urceni globalnich promennych, se kterymi sablona pracuje
global $tplData;

?>
<!-- Úvod -->
<section class="bg-success bg-gradient pt-5" id="uvod">
    <div class="container px-5 py-5">
        <div class="row gx-5 align-items-center">
            <div class="col-lg-6 col-md-6 text-white ">
                <h1 class="fw-bold py-3">ECO <sup>2022</sup></h1>
                <p>
                    Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Etiam egestas wisi a erat. Aliquam
                    erat volutpat. Pellentesque sapien. Aenean vel massa quis mauris vehicula lacinia. Curabitur
                    bibendum justo.
                </p>
                <p class="fw-bold fs-5 ">
                    3. dubna - 5. dubna 2022
                </p>
            </div>
            <div class="col-lg-6 col-md-6">
                <img style="width: 90%;" class="d-block m-auto" src="public/img/earth.png" alt="">
            </div>
        </div>
    </div>
    <div class="svg-border-waves text-white pt-5">
        <!-- Wave SVG Border-->
        <svg class="wave" style="pointer-events: none" fill="currentColor" preserveAspectRatio="none"
             xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 1920 75">
            <defs>
                <style>
                    .a {
                        fill: none;
                    }

                    .b {
                        clip-path: url(#a);
                    }

                    .d {
                        opacity: 0.5;
                        isolation: isolate;
                    }
                </style>
            </defs>
            <clipPath id="a">
                <rect class="a" width="1920" height="75"></rect>
            </clipPath>
            <g class="b">
                <path class="c"
                      d="M1963,327H-105V65A2647.49,2647.49,0,0,1,431,19c217.7,3.5,239.6,30.8,470,36,297.3,6.7,367.5-36.2,642-28a2511.41,2511.41,0,0,1,420,48"></path>
            </g>
            <g class="b">
                <path class="d"
                      d="M-127,404H1963V44c-140.1-28-343.3-46.7-566,22-75.5,23.3-118.5,45.9-162,64-48.6,20.2-404.7,128-784,0C355.2,97.7,341.6,78.3,235,50,86.6,10.6-41.8,6.9-127,10"></path>
            </g>
            <g class="b">
                <path class="d"
                      d="M1979,462-155,446V106C251.8,20.2,576.6,15.9,805,30c167.4,10.3,322.3,32.9,680,56,207,13.4,378,20.3,494,24"></path>
            </g>
            <g class="b">
                <path class="d"
                      d="M1998,484H-243V100c445.8,26.8,794.2-4.1,1035-39,141-20.4,231.1-40.1,378-45,349.6-11.6,636.7,73.8,828,150"></path>
            </g>
        </svg>
    </div>
</section>

<!-- O konferenci -->
<section class="bg-white bg-gradient py-5" id="o_konferenci">
    <div class="container py-5 px-5">
        <div class="row mb-5 justify-content-center">
            <div class="col-lg-9 text-center">
                <h3>O konferenci</h3>
                <p class="text-secondary ">
                    Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Nullam feugiat, turpis at pulvinar
                    vulputate, erat libero tristique tellus, nec bibendum odio risus sit amet ante. Etiam quis quam.
                </p>
            </div>
        </div>
        <div class="row text-center">
            <div class="col-lg-3 col-md-6 col-12 m-lg-0 mb-4">
                <div class="icon-conf bg-success bg-gradient rounded-circle m-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="2rem" height="2rem" fill="currentColor"
                         class="bi bi-thermometer-half text-white" viewBox="0 0 16 16">
                        <path d="M9.5 12.5a1.5 1.5 0 1 1-2-1.415V6.5a.5.5 0 0 1 1 0v4.585a1.5 1.5 0 0 1 1 1.415z"/>
                        <path d="M5.5 2.5a2.5 2.5 0 0 1 5 0v7.55a3.5 3.5 0 1 1-5 0V2.5zM8 1a1.5 1.5 0 0 0-1.5 1.5v7.987l-.167.15a2.5 2.5 0 1 0 3.333 0l-.166-.15V2.5A1.5 1.5 0 0 0 8 1z"/>
                    </svg>
                </div>
                <h5>Globální oteplování</h5>
                <p>
                    Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Nullam feugiat, turpis at pulvinar
                    vulputate.
                </p>
            </div>
            <div class="col-lg-3 col-md-6 col-12 m-lg-0 mb-4">
                <div class="icon-conf bg-success bg-gradient rounded-circle m-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="2rem" height="2rem" fill="currentColor"
                         class="bi bi-water text-white" viewBox="0 0 16 16">
                        <path d="M.036 3.314a.5.5 0 0 1 .65-.278l1.757.703a1.5 1.5 0 0 0 1.114 0l1.014-.406a2.5 2.5 0 0 1 1.857 0l1.015.406a1.5 1.5 0 0 0 1.114 0l1.014-.406a2.5 2.5 0 0 1 1.857 0l1.015.406a1.5 1.5 0 0 0 1.114 0l1.757-.703a.5.5 0 1 1 .372.928l-1.758.703a2.5 2.5 0 0 1-1.857 0l-1.014-.406a1.5 1.5 0 0 0-1.114 0l-1.015.406a2.5 2.5 0 0 1-1.857 0l-1.014-.406a1.5 1.5 0 0 0-1.114 0l-1.015.406a2.5 2.5 0 0 1-1.857 0L.314 3.964a.5.5 0 0 1-.278-.65zm0 3a.5.5 0 0 1 .65-.278l1.757.703a1.5 1.5 0 0 0 1.114 0l1.014-.406a2.5 2.5 0 0 1 1.857 0l1.015.406a1.5 1.5 0 0 0 1.114 0l1.014-.406a2.5 2.5 0 0 1 1.857 0l1.015.406a1.5 1.5 0 0 0 1.114 0l1.757-.703a.5.5 0 1 1 .372.928l-1.758.703a2.5 2.5 0 0 1-1.857 0l-1.014-.406a1.5 1.5 0 0 0-1.114 0l-1.015.406a2.5 2.5 0 0 1-1.857 0l-1.014-.406a1.5 1.5 0 0 0-1.114 0l-1.015.406a2.5 2.5 0 0 1-1.857 0L.314 6.964a.5.5 0 0 1-.278-.65zm0 3a.5.5 0 0 1 .65-.278l1.757.703a1.5 1.5 0 0 0 1.114 0l1.014-.406a2.5 2.5 0 0 1 1.857 0l1.015.406a1.5 1.5 0 0 0 1.114 0l1.014-.406a2.5 2.5 0 0 1 1.857 0l1.015.406a1.5 1.5 0 0 0 1.114 0l1.757-.703a.5.5 0 1 1 .372.928l-1.758.703a2.5 2.5 0 0 1-1.857 0l-1.014-.406a1.5 1.5 0 0 0-1.114 0l-1.015.406a2.5 2.5 0 0 1-1.857 0l-1.014-.406a1.5 1.5 0 0 0-1.114 0l-1.015.406a2.5 2.5 0 0 1-1.857 0L.314 9.964a.5.5 0 0 1-.278-.65zm0 3a.5.5 0 0 1 .65-.278l1.757.703a1.5 1.5 0 0 0 1.114 0l1.014-.406a2.5 2.5 0 0 1 1.857 0l1.015.406a1.5 1.5 0 0 0 1.114 0l1.014-.406a2.5 2.5 0 0 1 1.857 0l1.015.406a1.5 1.5 0 0 0 1.114 0l1.757-.703a.5.5 0 1 1 .372.928l-1.758.703a2.5 2.5 0 0 1-1.857 0l-1.014-.406a1.5 1.5 0 0 0-1.114 0l-1.015.406a2.5 2.5 0 0 1-1.857 0l-1.014-.406a1.5 1.5 0 0 0-1.114 0l-1.015.406a2.5 2.5 0 0 1-1.857 0l-1.757-.703a.5.5 0 0 1-.278-.65z"/>
                    </svg>
                </div>
                <h5>Kyselé oceány</h5>
                <p>
                    Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Nullam feugiat, turpis at pulvinar
                    vulputate.
                </p>
            </div>
            <div class="col-lg-3 col-md-6 col-12 m-lg-0 mb-4">
                <div class="icon-conf bg-success bg-gradient rounded-circle m-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="2rem" height="2rem" fill="currentColor"
                         class="bi bi-cloud-fog2 text-white" viewBox="0 0 16 16">
                        <path d="M8.5 4a4.002 4.002 0 0 0-3.8 2.745.5.5 0 1 1-.949-.313 5.002 5.002 0 0 1 9.654.595A3 3 0 0 1 13 13H.5a.5.5 0 0 1 0-1H13a2 2 0 0 0 .001-4h-.026a.5.5 0 0 1-.5-.445A4 4 0 0 0 8.5 4zM0 8.5A.5.5 0 0 1 .5 8h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm0 2a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5z"/>
                    </svg>
                </div>
                <h5>Znečištění ovzduší</h5>
                <p>
                    Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Nullam feugiat, turpis at pulvinar
                    vulputate.
                </p>
            </div>
            <div class="col-lg-3 col-md-6 col-12 m-lg-0 mb-4">
                <div class="icon-conf bg-success bg-gradient rounded-circle m-auto mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="2rem" height="2rem" fill="currentColor"
                         class="bi bi-recycle text-white" viewBox="0 0 16 16">
                        <path d="M9.302 1.256a1.5 1.5 0 0 0-2.604 0l-1.704 2.98a.5.5 0 0 0 .869.497l1.703-2.981a.5.5 0 0 1 .868 0l2.54 4.444-1.256-.337a.5.5 0 1 0-.26.966l2.415.647a.5.5 0 0 0 .613-.353l.647-2.415a.5.5 0 1 0-.966-.259l-.333 1.242-2.532-4.431zM2.973 7.773l-1.255.337a.5.5 0 1 1-.26-.966l2.416-.647a.5.5 0 0 1 .612.353l.647 2.415a.5.5 0 0 1-.966.259l-.333-1.242-2.545 4.454a.5.5 0 0 0 .434.748H5a.5.5 0 0 1 0 1H1.723A1.5 1.5 0 0 1 .421 12.24l2.552-4.467zm10.89 1.463a.5.5 0 1 0-.868.496l1.716 3.004a.5.5 0 0 1-.434.748h-5.57l.647-.646a.5.5 0 1 0-.708-.707l-1.5 1.5a.498.498 0 0 0 0 .707l1.5 1.5a.5.5 0 1 0 .708-.707l-.647-.647h5.57a1.5 1.5 0 0 0 1.302-2.244l-1.716-3.004z"/>
                    </svg>
                </div>
                <h5>Recyklace</h5>
                <p>
                    Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Nullam feugiat, turpis at pulvinar
                    vulputate.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Program konference -->
<section class="bg-light bg-gradient py-5" id="program">
    <div class="container p-5">
        <div class="row justify-content-center mb-5">
            <div class="col-lg-9 text-center">
                <h3> Program konference </h3>
                <p class="text-secondary ">
                    Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Nullam feugiat, turpis at pulvinar
                    vulputate, erat libero tristique tellus, nec bibendum odio risus sit amet ante. Etiam quis quam.
                </p>
            </div>
        </div>
        <div class="row justify-content-center text-center">
            <div class="col-md-4 m-lg-0 mb-4">
                <div class="text-light bg-success bg-gradient p-4 rounded-3 h-100 shadow-sm">
                    <h5 class="fw-bold mb-4">Den 1</h5>
                    <hr>
                    <p>
                        <strong>09:00 - 11:00</strong><br>
                        Lorem ipsum dolor sit amet<br>
                        <em>(Lorem Ipsum)</em>
                    </p>
                    <p>
                        <strong>12:00 - 14:00</strong><br>
                        Lorem ipsum dolor sit amet<br>
                        <em>(Lorem Ipsum)</em>
                    </p>
                    <p>
                        <strong>15:00 - 17:00</strong><br>
                        Lorem ipsum dolor sit amet<br>
                        <em>(Lorem Ipsum)</em>
                    </p>
                </div>

            </div>
            <div class="col-md-4 m-lg-0 mb-4 ">
                <div class="text-light bg-success bg-gradient p-4 rounded-3 h-100 shadow-sm">
                    <h5 class="fw-bold mb-4">Den 2</h5>
                    <hr>
                    <p>
                        <strong>09:00 - 11:00</strong><br>
                        Lorem ipsum dolor sit amet<br>
                        <em>(Lorem Ipsum)</em>
                    </p>
                    <p>
                        <strong>13:00 - 15:00</strong><br>
                        Lorem ipsum dolor sit amet<br>
                        <em>(Lorem Ipsum)</em>
                    </p>
                </div>

            </div>
            <div class="col-md-4 m-lg-0 mb-4">
                <div class="text-light bg-success bg-gradient p-4 rounded-3 h-100 shadow-sm">
                    <h5 class="fw-bold mb-4">Den 3</h5>
                    <hr>
                    <p>
                        <strong>09:00 - 10:00</strong><br>
                        Lorem ipsum dolor sit amet<br>
                        <em>(Lorem Ipsum)</em>
                    </p>
                    <p>
                        <strong>11:00 - 12:00</strong><br>
                        Lorem ipsum dolor sit amet<br>
                        <em>(Lorem Ipsum)</em>
                    </p>
                    <p>
                        <strong>14:00 - 15:00</strong><br>
                        Lorem ipsum dolor sit amet<br>
                        <em>(Lorem Ipsum)</em>
                    </p>
                    <p>
                        <strong>15:00 - 16:00</strong><br>
                        Lorem ipsum dolor sit amet<br>
                        <em>(Lorem Ipsum)</em>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>

