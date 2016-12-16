@extends('layouts.bericht-generator')

@section('content')
    <div class="col-md-3">
        <form method="POST" action="/">
            {{csrf_field()}}
            <div class="form-group" style="margin-top: 30px">
                <label class="control-label requiredField" for="start">
                    Kalenderwoche Start
                    <span class="asteriskField">
                    </span>
                </label>
                <div class="input-group">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar">
                        </i>
                    </div>
                    <input class="form-control datepicker" id="datepicker1" name="start" type="text"/>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label requiredField" for="nr">
                    Nr.
                    <span class="asteriskField">
                    </span>
                </label>
                <div class="input-group">
                    <div class="input-group-addon">
                    </div>
                    <input class="form-control" id="nr" name="nr" type="text"
                    />
                </div>
            </div>

            <div class="form-group">
                <label class="control-label requiredField" for="year">
                    Jahr
                    <span class="asteriskField">
                    </span>
                </label>
                <div class="input-group">
                    <div class="input-group-addon">
                    </div>
                    <input class="form-control" id="year" name="year" type="text"
                    />
                </div>
            </div>

            <div class="form-group ">
                <label class="control-label requiredField" for="end">
                    Kalenderwoche Ende
                    <span class="asteriskField">
       </span>
                </label>
                <div class="input-group">
                    <div class="input-group-addon">
                        <i class="fa fa-calendar">
                        </i>
                    </div>
                    <input class="form-control datepicker" id="datepicker2" name="end" type="text"/>
                </div>
            </div>
            <div class="form-group">
                <div>
                    <button class="btn btn-primary " name="submit" type="submit">
                        Bericht generieren
                    </button>
                </div>
            </div>
        </form>
    </div>
    @if ( Config::get('app.debug') )
        <script type="text/javascript">
            document.write('<script src="//localhost:35729/livereload.js?snipver=1" type="text/javascript"><\/script>')
        </script>
    @endif
@endsection