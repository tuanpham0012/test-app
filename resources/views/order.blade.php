<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">


</head>

<body>
    <div class="container mt-5">
        <div class="step">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link step1" href="#">Step 1</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link step2" href="#">Step 2</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link step3" href="#">Step 3</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link step4" href="#">Review</a>
                </li>
            </ul>
        </div>
        <div class="content m-5 min-h-50">
            <div class="card row border-0" id="step1" style="display: none;">
                <div class="col-3 m-3">
                    <label for="basic-url" class="form-label">Please Select a meal</label>
                    <div class="input-group mb-3 col-3">
                        <select class="form-select" name="select-meal" id="select-meal">
                            @foreach ($meals as $value)
                                <option value="{{ $value }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-3 m-3">
                    <label for="basic-url" class="form-label">Please Enter Number of people</label>
                    <input type="number" min="1" class="form-control text-end" value="1" id="no-people">
                </div>
            </div>
            <div class="card row border-0" id="step2" style="display: none;">
                <div class="col-3 m-3">
                    <label for="basic-url" class="form-label">Please Select a Restaurant</label>
                    <div class="input-group mb-3 col-3">
                        <select class="form-select" name="select-restaurant" id="select-restaurant">
                            @foreach ($restaurants as $value)
                                <option value="{{ $value }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="card border-0" id="step3" style="display: none;">
                <div id="list-dishes">

                </div>
                <div class="">
                    <button class="btn btn-primary mx-4" id="add-dish"> + </button>
                </div>


            </div>
            <div class="card row border-0" id="step4" style="display: none;">

                <div class="col-8 m-3" id="result">
                </div>
            </div>
        </div>
        <div class="position-relative d-flex">
            <button type="button" class="btn btn-primary mx-4 position-absolute start-0" id="previus"> previus
            </button>
            <button type="button" class="btn btn-primary mx-4 position-absolute end-0" id="next"> next
            </button>
        </div>


    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" crossorigin="anonymous"></script>


    <script>
        let order = {
            meal: '',
            no_people: 1,
            restaurant: '',
            dishes: []
        }

        let step = 1

        var dishes = []

        let step1 = $('#step1')
        let step2 = $('#step2')
        let step3 = $('#step3')
        let step4 = $('#step4')

        function getDishes() {
            $.ajax({
                url: "{{ route('get-dishes') }}",
                type: 'get',
                dataType: 'html',
                data: {
                    meal: order.meal,
                    restaurant: order.restaurant
                }
            }).done(function(res) {
                // dishes = res.data.dishes;
                dishes = JSON.parse(res).dishes;
                if (dishes.length < 1 && step == 2) {
                    $('#next').prop('disabled', true);
                } else {
                    $('#next').prop('disabled', false);
                }
            })

        }

        function addDish() {
            let html = `<div class="row col-12">
                        <div class="col-3 m-3">
                            <label for="basic-url" class="form-label">Please Select a Dish</label>
                            <div class="input-group mb-3 col-3">
                                <select class="form-select" name="select-dish">`

            dishes.forEach(item => {
                html += "<option value='" + item.name + "'>" + item.name + "</option>"
            });

            html += `</select>
                            </div>
                        </div>
                        <div class="col-3 m-3">
                            <label for="basic-url" class="form-label">Please enter no. of servings</label>
                            <div class="col-3">
                                <input type="number" min="1" class="form-control text-end" value="1" name="no-dish">
                            </div>

                        </div>
                    </div>`

            $('#list-dishes').append(html);
        }

        function submit() {
            let dishes = document.getElementsByName("select-dish");
            let no = document.getElementsByName("no-dish");
            order.dishes = [];
            for (let index = 0; index < dishes.length; index++) {
                let dish = {
                    dish: dishes[index].value,
                    no: no[index].value
                }
                order.dishes.push(dish);
            }

            let html = `<table class="table">
                            <tbody>
                                <tr class="m-4">
                                    <th scope="row">Meal</th>
                                    <td>` + order.meal + `</td>
                                </tr>
                                <tr class="m-4">
                                    <th scope="row">No. of. People</th>
                                    <td>` + order.no_people + `</td>
                                </tr>
                                <tr class="m-4">
                                    <th scope="row">Restaurant</th>
                                    <td>` + order.restaurant + `</td>
                                </tr>
                                <tr class="m-4">
                                    <th scope="row">Dishes</th>
                                    <td>
                                        <ul class="list-group">`
            order.dishes.forEach(item => {
                html += `<li class="list-group-item disabled" aria-disabled="true">` + item.dish + ' - ' + item.no +
                    `</li>`
            })
            html += `</ul>
                                    </td>
                                </tr>
                            </tbody>
                        </table>`
            $('#result').html(html)
            console.log(order);
        }

        function loadStep(empty = true) {
            switch (step) {
                case 1:
                    step1.show()
                    step2.hide()
                    step3.hide()
                    step4.hide()
                    $('#previus').hide()
                    $('#next').show()
                    $('.step1').addClass('active')
                    $('.step2').removeClass('active')
                    break;
                case 2:
                    step1.hide()
                    step2.show()
                    step3.hide()
                    step4.hide()
                    $('#previus').show()
                    $('#next').text('next')
                    $('.step1').removeClass('active')
                    $('.step2').addClass('active')
                    $('.step3').removeClass('active')
                    if (dishes.length < 1 && step == 2) {
                        $('#next').prop('disabled', true);
                    } else {
                        $('#next').prop('disabled', false);
                    }
                    break;
                case 3:
                    step1.hide()
                    step2.hide()
                    step3.show()
                    step4.hide()
                    $('#next').show()
                    $('#next').text('submit')
                    if (empty) {
                        $('#list-dishes').empty()
                        addDish();
                    }
                    $('.step2').removeClass('active')
                    $('.step3').addClass('active')
                    $('.step4').removeClass('active')
                    break;
                case 4:
                    step1.hide()
                    step2.hide()
                    step3.hide()
                    step4.show()
                    $('#previus').show()
                    $('#next').hide()
                    submit()
                    $('.step4').addClass('active')
                    $('.step3').removeClass('active')
                    break;
                default:
                    step1.show()
                    step2.hide()
                    step3.hide()
                    step4.hide()
                    $('#previus').hide()
                    $('#next').show()
                    break;
            }
        }

        $(document).ready(function() {
            console.log('hell')
            loadStep()
            order.meal = $('#select-meal').val()
            order.no_people = $('#no-people').val()
            order.restaurant = $('#select-restaurant').val()
            getDishes()
        });

        $('#select-meal').change(function() {
            order.meal = $(this).val();
            getDishes()
        })

        $('#no-people').change(function() {
            order.no_people = $(this).val() ?? 1;
        })

        $('#select-restaurant').change(function() {
            order.restaurant = $(this).val();
            getDishes()
        })
        $('#previus').click(function() {
            let oldStep = step
            step--
            if (oldStep == 4) {
                loadStep(false)
            } else {
                loadStep()
            }
            $('#next').prop('disabled', false);

        })
        $('#next').click(function() {
            step++
            loadStep()
        })
        $('#add-dish').click(function() {
            addDish()
        })
    </script>
</body>

</html>
