<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags-->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Title Page-->
    <title>amoCRM</title>

    <!-- Icons font CSS-->
    <link href="{{ asset("assets/vendor/mdi-font/css/material-design-iconic-font.min.css")  }}" rel="stylesheet" media="all">
    <link href="{{  asset("assets/vendor/font-awesome-4.7/css/font-awesome.min.css")  }}" rel="stylesheet" media="all">
    <!-- Font special for pages-->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">

    <!-- Vendor CSS-->
    <link href="{{  asset("assets/vendor/select2/select2.min.css")  }}" rel="stylesheet" media="all">
    <link href="{{  asset("assets/vendor/datepicker/daterangepicker.css")  }}" rel="stylesheet" media="all">

    <!-- Main CSS-->
    <link href="{{  asset("assets/css/main.css")  }}" rel="stylesheet" media="all">
</head>

<body>
<div class="page-wrapper bg-gra-03 p-t-45 p-b-50">
    <div class="wrapper wrapper--w790">
        <div class="card card-5">
            <div class="card-heading">
                <h2 class="title">contact form</h2>
            </div>
            <div class="card-body">
                <form method="POST" action="{{  route('contact.store')  }}">

                    @csrf

                    <div class="form-row m-b-55">
                        <div class="name">name</div>
                        <div class="value">
                            <div class="row row-space">
                                <div class="col-2">
                                    <div class="input-group-desc">
                                        <input class="input--style-5" id="form.first-name" type="text" name="first_name">
                                        <label class="label--desc">first name</label>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="input-group-desc">
                                        <input class="input--style-5" type="text" id="form.last-name" name="last_name">
                                        <label class="label--desc">last name</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="name">phone</div>
                        <div class="value">
                            <div class="input-group">
                                <input class="input--style-5" id="form.phone" type="number" name="phone">
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="name">email</div>
                        <div class="value">
                            <div class="input-group">
                                <input class="input--style-5" id="form.email" type="email" name="email">
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="name">age</div>
                        <div class="value">
                            <div class="input-group">
                                <input class="input--style-5" id="form.age" type="number" name="age">
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="name">gender</div>
                        <div class="value">
                            <div class="input-group">
                                <div class="rs-select2 js-select-simple select--no-search">
                                    <select id="form.gender" name="gender">
                                        <option disabled="disabled" selected="selected">choose option</option>
                                        <option>male</option>
                                        <option>female</option>
                                        <option>other</option>
                                    </select>
                                    <div class="select-dropdown"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <button class="btn btn--radius-2 btn--red" id="submit-button" type="submit">submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Jquery JS-->
<script src="{{  asset("assets/vendor/jquery/jquery.min.js")  }}"></script>
<!-- Vendor JS-->
<script src="{{ asset("assets/vendor/select2/select2.min.js")  }}"></script>
<script src="{{  asset("assets/vendor/datepicker/moment.min.js")  }}"></script>
<script src="{{  asset("assets/vendor/datepicker/daterangepicker.js")  }}"></script>

<!-- Main JS-->
<script src="{{  asset("assets/js/global.js")  }}"></script>
<script src="{{  asset("assets/js/contact.submit.js")  }}"></script>

</body>

</html>
<!-- end document-->
