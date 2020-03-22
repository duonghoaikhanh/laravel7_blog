
@extends('admin.layouts.app_admin')
@section('title', __('Dashboard'))
@section('content')
    <div class="content_wrapper">
        <div class="page_title">
            <h3>{!! __('Dashboard') !!}</h3>
        </div>
        @include('admin.includes.boxes.notify')
        <div class="page_content">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="x_panel">
                        <div class="x_title">
                            <h2>Site Tracking</h2>
                        </div>
                        <div class="x_content">
                            <canvas id="tracking_chart" style="width:100%;height:200px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
