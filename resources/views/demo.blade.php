<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Kotsms Demo範例</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body .title .m-b-md{
                background-color: #fff;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }

            .full-height {
                height: 100vh;
            }

            .flex-center {
                align-items: center;
                display: flex;
                justify-content: center;
            }

            .position-ref {
                position: relative;
            }

            .top-right {
                position: absolute;
                right: 10px;
                top: 18px;
            }

            .content {
                text-align: center;
            }

            .title {
                font-size: 84px;
            }

            .m-b-md {
                margin-bottom: 30px;
            }
        </style>

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>

        <!-- Fonts -->
        <link rel="dns-prefetch" href="https://fonts.gstatic.com">

        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    </head>
    <body>
        <div class="flex-center position-ref full-height">
            <div class="content">
                <div class="title m-b-md">
                    Hello Kotsms
                </div>
            </div>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">{{ __('簡訊王測試簡訊') }}</div>
                            <div class="card-body">
                                <form method="post" action="{{ route('kotsms.send') }}">
                                    @csrf

                                    @if (isset($kotsms['errors']))
                                    <div class="alert alert-warning" role="alert">
                                        <strong>錯誤！</strong>{{ $kotsms['errors']['message'] }}
                                    </div>
                                    @elseif(isset($kotsms['success']) && $kotsms['success'])
                                        <div class="alert alert-success" role="alert">
                                            {{ $kotsms['data']['message'] }}
                                        </div>
                                    @endif

                                    <div class="form-group row">
                                        <label for="mobile" class="col-sm-4 col-form-label text-md-right">{{ __('手機號碼') }}</label>

                                        <div class="col-md-6">
                                            <input id="mobile" type="number" class="form-control" name="number" value="" required autofocus>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="content" class="col-sm-4 col-form-label text-md-right">{{ __('簡訊內容') }}</label>

                                        <div class="col-md-6">
                                            <textarea id="content" type="content" class="form-control" name="content" rows="10" required>{{ $content ?? null }}</textarea>
                                        </div>
                                    </div>
                                    <div class="form-group row mb-0">
                                        <div class="col-md-8 offset-md-4">
                                            <button type="submit" class="btn btn-primary">
                                                {{ __('寄出') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
