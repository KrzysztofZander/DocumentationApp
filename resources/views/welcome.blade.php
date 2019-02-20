<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <script>window.Laravel = { csrfToken: '{{ csrf_token() }}' }</script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

        <title>DocumentationApp</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Script -->
        <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
        <script src="https://unpkg.com/popper.js"></script>
        <script src="https://unpkg.com/vue/dist/vue.js"></script>
        <script src="https://unpkg.com/v-tooltip"></script>

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-size: 14px;
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

            .table-responsive {
                text-align: left;
                margin-left: 30px;
                margin-top: 30px;
                width: 60%;
            }

            .title {
                font-size: 84px;
            }

            .links > a {
                color: #636b6f;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-decoration: none;
                text-transform: uppercase;
            }

            .m-b-md {
                margin-bottom: 30px;
            }

            
        </style>
    </head>
    <body>
    <div style="padding:5px;">
        <div class="flex-center position-ref">
            @if (Route::has('login'))
                <div class="top-right links">
                    @auth
                        <a href="{{ url('/home') }}">Home</a>
                    @else
                        <a href="{{ route('login') }}">Login</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}">Register</a>
                        @endif
                    @endauth
                </div>
            @endif
        </div>

        <!-- Tabela z dokumentami -->
        <div id="app">
        <show-document></show-document>
        </div>

    <div class="addFile" style="float:left;">

    <div id="addNewFile" >
        <h2>Dodaj nowy dokument</h2>
        <!-- Formularz dodania dokumentu -->
        <form action="{{ route('storeDoc') }}" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}

            <!-- Nazwa dokumentu -->
            <div class="form-group">
            <input type="text" class="form-control" placeholder="Nazwa (puste by zachować oryginalną nazwę) " name="name">
            </div>

            <!-- Kontrahent  -->
            <div class="form-group">
            <input type="text" class="form-control" placeholder="Kontrahent" name="counterparty">
            </div>

            <!-- Firma  -->
            <div class="form-group">
            <select  class="form-control" name="company">
            <option value="" disabled selected>Wybierz firme</option>
                <option value="retencja"> Retencja </option>
                <option value="biopro"> Biopro </option>
            </select>
            </div>

            <!-- Kierunek  -->
            <div class="form-group">
            <select  class="form-control" name="inOrOut">
            <option value="" disabled selected>Wybierz kierunek</option>
                <option value="Przychodzący"> Przychodzący </option>
                <option value="Wychodzący"> Wychodzący </option>
            </select>
            </div>

            <!-- Data dokumentu -->
            <div class="form-group">
            <input type="text" class="form-control" placeholder="Data na dokumencie" onfocus="(this.type='date')" name="dateOfDoc">
            </div>

            <!-- Typ dokumentu -->
            <div class="form-group">
            <select class="form-control" placeholder="Typ dokumentu" name="typeOfDoc">
                <option value="Faktura"> Faktura </option>
                <option value="Umowa"> Umowa </option>
                <option value="Inne"> Inne </option>
            </select>
            </div>

            <!-- Numer na  dokumencie -->
            <div class="form-group">
            <input type="text" class="form-control" placeholder="Numer na dokumencie" name="numberOnDoc" >
            </div>

            <!-- Opis dokumentu -->
            <div class="form-group">
            <input type="text" class="form-control" placeholder="Opis" name="description" >
            </div>

            <!-- plik.pdf -->
            <div class="form-group">
            <input type="file"   placeholder="Plik" name="file">
            
            <!-- zatwierdzenie dokumentu -->
            <button type="submit" class="btn btn-success" >Dodaj</button>
            </div>

            <div>
            @if(session()->has('msg_add'))
                <div class="alert alert-success">
                    {{ session()->get('msg_add') }}
                </div>
            @endif
            </div>

            @if (Session::has('message'))
            <div class="alert alert-info">{{ Session::get('message') }}</div>
            @endif


        </form>

        <!-- Sprzawdzenie skrzynki -->
        <div style="margin-top:10px;">
        <h2> Pobierz dokumenty z poczty</h2>
            <td><button  class="btn btn-primary" onclick="location.href='{{ url('/checkmail') }}'">Pobierz dokumenty</button></td>
            @if(session()->has('msg'))
                <div class="alert alert-success">
                    {{ session()->get('msg') }}
                </div>
            @endif
            @if(session()->has('msg_er'))
                <div class="alert alert-danger">
                    {{ session()->get('msg_er') }}
                </div>
            @endif
        </div>

        </div>
    </div>




    </div>
    
    <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>
