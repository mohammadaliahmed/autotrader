<?php

/**
 * Plugin Name: Autotrader Plugin
 * Plugin URI: http://www.appsinventiv.com/
 * Description: Cars Data
 * Version: 1.0
 * Author: Mohammad Ali Ahmed
 * Author URI: http://appsinventiv.com/
 **/


add_action('wp_ajax_data_fetch', 'data_fetch');
add_action('wp_ajax_nopriv_data_fetch', 'data_fetch');
function data_fetch()
{
    global $wpdb;
    $carsMake = $wpdb->get_results("
SELECT distinct(model) FROM `wp_cars_data` where make='" . $_POST['carMake'] . "'  order by model asc
");
    echo json_encode($carsMake);
    wp_die();

}

function tbare_wordpress_plugin_demo($atts)
{

    global $wpdb;
    $carsMake = $wpdb->get_results("Select distinct(make) from wp_cars_data order by make asc");
    $make = $_GET['make'];
    $model = $_GET['model'];
    $limit = 10;
    ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.4.1/dist/css/bootstrap.min.css"
          integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <form method="GET">
        <div class="card bg-dark">
            <center>
                <h3 style="color:#fff;margin: 20px">Search cars</h3>
            </center>
            <div class="row p-5">

                <div class="col-sm-12 col-lg-5">
                    <label class="text-white">Select car model</label>
                    <select class="form-select  h-100 w-100" name="make" id="car_make">
                        <option data-dropdownvalue="-1" value="" data-count="-1" class="dropdown-top-row">Any Make
                        </option>
                        <optgroup label="All Makes">
                            <?php
                            foreach ($carsMake

                                     as $carMake) {
                                if ($carMake->make == $make) {
                                    ?>
                                    <option selected
                                            value="<?php echo $carMake->make ?>"><?php echo $carMake->make ?></option>

                                    <?php
                                } else {
                                    ?>
                                    <option value="<?php echo $carMake->make ?>"><?php echo $carMake->make ?></option>

                                    <?php
                                }
                            }
                            ?>
                        </optgroup>
                    </select>
                </div>
                <div class="col-sm-12 col-lg-5">
                    <label id="modelLabel" class="text-white">Select car make</label>

                    <select class="form-select  h-100 w-100" name="model">
                        <option data-dropdownvalue="-1" value="" data-count="-1"
                                class="dropdown-top-row">Any Model
                        </option>
                        <optgroup id="car_model" label="Any Model">


                        </optgroup>
                    </select>
                </div>
                <div class="col-sm-12 col-lg-2">
                    <label class="text-dark">.</label>


                    <button class="btn btn-success btn-block w-100 h-100" name="submit" type="submit">Search</button>
                </div>

            </div>
        </div>
    </form>


    <script>
        $(".stars").each(function () {
            // Get the value
            var val = $(this).data("rating");
            // Make sure that the value is in 0 - 5 range, multiply to get width
            var size = Math.max(0, (Math.min(5, val))) * 16;
            // Create stars holder
            var $span = $('<span />').width(size);
            // Replace the numerical value with stars
            $(this).html($span);
        });
    </script>
    <script>

        $.fn.stars = function () {
            return $(this).each(function () {
                const rating = $(this).data("rating");
                const numStars = $(this).data("numStars");
                const fullStar = '<i style="color: orange" class="fas fa-star"></i>'.repeat(Math.floor(rating));
                const halfStar = (rating % 1 !== 0) ? '<i style="color: orange"  class="fas fa-star-half-alt"></i>' : '';
                const noStar = '<i style="color: orange" class="fa fa-star-o"></i>'.repeat(Math.floor(numStars - rating));
                $(this).html(`${fullStar}${halfStar}${noStar}`);
            });
        }
        $(function () {
            $('.stars').stars();
        });
    </script>
    <script type="text/javascript">
        window.onload = function () {
            var eSelect = document.getElementById('car_make');
            eSelect.onchange = function () {

                console.log(eSelect.value);
                $.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    type: 'post',
                    data: {
                        action: 'data_fetch',
                        "carMake": eSelect.value
                    },
                    success: function (data) {
                        // console.log(data);
                        var returnedData = JSON.parse(data);
                        var opts = "";
                        for (var key in returnedData) {
                            // console.log(returnedData[key].model);
                            opts += "<option>" + returnedData[key].model + "</option>";
                        }
                        console.log(opts);
                        $("#car_model").html(opts);

                    }
                });


            }
        }
    </script>

    <?php

    if (isset($_GET['submit'])) {

        $make = $_GET['make'];
        $model = $_GET['model'];

        $cars = $wpdb->get_results("Select count(*) as counta from wp_cars_data  where make like '%" . $make . "%' and 
    model like '%" . $model . "%'");
        $total_rows = $cars[0]->counta;
        $total_pages = ceil($total_rows / $limit);

        if (!isset ($_GET['goto'])) {
            $page_number = 1;
        } else {
            $page_number = $_GET['goto'];
        }
        $goTo = $page_number;
        $initial_page = ($page_number - 1) * $limit;

        $cars = $wpdb->get_results("select * from wp_cars_data where make like '%" . $make . "%' and 
    model like '%" . $model . "%'  LIMIT " . $initial_page . ',' . $limit);
        foreach ($cars as $car) {
            ?>
            <div class="card bg-light m-3 p-2">
                <div class="row">
                    <div class="col-sm-4">
                        <img src="
                        <?php echo explode("|", $car->images)[0] ?>" width="300">

                    </div>
                    <div class="col-sm-6">
                        <div class="proximity">
                            <i class="fa fa-map-pin"></i>
                            <span style="font-size:13px"><?php echo $car->location ?></span>
                        </div>
                        <div class="title">
                            <a href="<?php echo $car->url ?>">
                                <?php echo $car->title ?>
                            </a>

                        </div>
                        <div class="description">

                            <span class="stars" data-rating="<?php echo $car->googleRating ?>"
                                  data-num-stars="5"></span>
                            <br>
                            <small> <?php echo mb_strimwidth($car->description, 0, 250, "..."); ?></small>

                        </div>
                    </div>
                    <div class="col-sm-2">
                        <strong>$<?php echo number_format($car->price) ?></strong>

                    </div>
                </div>
                <div class="row">

                    <div class="d-flex m-2" style=" overflow-x: scroll;">
                                                <img class="m-1" src="
                        <?php echo explode("|", $car->images)[1] ?>" width="100">
                                                <img class="m-1" src="
                        <?php echo explode("|", $car->images)[2] ?>" width="100">
                                                <img class="m-1" src="
                        <?php echo explode("|", $car->images)[3] ?>" width="100">
                                                <img class="m-1" src="
                        <?php echo explode("|", $car->images)[4] ?>" width="100">
                                                <img class="m-1" src="
                        <?php echo explode("|", $car->images)[5] ?>" width="100">
                                                <img class="m-1" src="
                        <?php echo explode("|", $car->images)[6] ?>" width="100">
                                                <img class="m-1" src="
                        <?php echo explode("|", $car->images)[7] ?>" width="100">
                                                <img class="m-1" src="
                        <?php echo explode("|", $car->images)[8] ?>" width="100">
                                                <img class="m-1" src="
                        <?php echo explode("|", $car->images)[9] ?>" width="100">
                                                <img class="m-1" src="
                        <?php echo explode("|", $car->images)[10] ?>" width="100">
                    </div>
                </div>

            </div>

            <?php

        }
        echo "<center>";
        for ($page_number = 1; $page_number <= $total_pages; $page_number++) {
            if ($page_number == $goTo) {
                echo '<a class="border  border-success m-1" href = "?make=' . $make . '&model=' . $model . '&goto=' . $page_number . '">' . $page_number . ' </a>';

            } else {
                echo '<a class="border m-1" href = "?make=' . $make . '&model=' . $model . '&goto=' . $page_number . '">' . $page_number . ' </a>';

            }
        }
        echo "</center>";
    }

}


add_shortcode('tbare-plugin-demoa', 'tbare_wordpress_plugin_demoa');
add_shortcode('tbare-plugin-demo', 'tbare_wordpress_plugin_demo');






