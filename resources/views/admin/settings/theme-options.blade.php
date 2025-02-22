@extends('admin.partials.main')
@section('content')
    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2 breadcrumb-new">
            <h3 class="content-header-title mb-0 d-inline-block">{{ $page['page_title'] }}</h3>
            <div class="row breadcrumbs-top d-inline-block">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="theme-color">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Theme Detail
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content-body">
        <section id="settings-form">
            <div class="row match-height">
                <div class="col-md-12">
                    <div class="card">

                        <div class="card-content collapse show">
                            <div class="card-body">
                                @include('admin.partials.flashes')
                                <form class="form" method="post" action="{{ route('admin.save.themeOptions') }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-body">
                                                <div class="row form-group">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="site_name">Logo</label>
                                                            <input type="file" name="logo" class="input-custom-file_1"
                                                                aria-invalid="false">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        @if (isset($setting['logo']) && !empty($setting['logo']))
                                                            <div
                                                                class="d-flex border rounded mt-2 p-1 justify-content-center" style="background-color: #041e41;">
                                                                <img class="img-fluid"
                                                                    src="{{ URL::asset('storage/logo/' . $setting['logo']) }}"
                                                                    width="100" />
                                                            </div>
                                                        @else
                                                            <div
                                                                class="d-flex border rounded mt-2 p-1 justify-content-center">
                                                                <span><i class="la la-image"
                                                                        style="font-size:2rem"></i></span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="row form-group">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="site_name">Favicon</label>
                                                            <input type="file" name="favicon" class="input-custom-file_1"
                                                                aria-invalid="false">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        @if (isset($setting['favicon']) && !empty($setting['favicon']))
                                                            <div
                                                                class="d-flex border rounded mt-2 p-1 justify-content-center" style="background-color: #041e41;">
                                                                <img class="img-fluid"
                                                                    src="{{ URL::asset('storage/logo/' . $setting['favicon']) }}"
                                                                    width="100" />
                                                            </div>
                                                        @else
                                                            <div
                                                                class="d-flex border rounded mt-2 p-1 justify-content-center">
                                                                <span><i class="la la-image"
                                                                        style="font-size:2rem"></i></span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-actions">
                                        <button type="submit" class="theme-btn btn btn-primary">
                                            <i class="la la-check-square-o"></i> Save
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
