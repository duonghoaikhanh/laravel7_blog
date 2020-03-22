@extends('admin.layouts.app_admin')
@section('title', trans(config('posts.' . $post_type . '.title')))
@section('content')
<div class="content_wrapper">
    <div class="page_title">
        <h3>{!! trans(config('posts.' . $post_type . '.title')) !!}</h3>
        <a class="button_title" href="{!! url('/admin/posts/' . $post_type . '/add') !!}">Add New {!! trans(config('posts.' . $post_type . '.title')) !!}</a>
    </div>
    @include('admin.includes.boxes.notify')
    <div class="page_content">
        <div class="datatable">
            <div class="table_filter">
                <ul>
                    <li{!! $filter == 'all' || $filter == '' ? ' class="active"' : '' !!}><a href="{!! url('/admin/posts/' . $post_type) !!}">All <span>({!! $filter_count['all'] !!})</span></a></li>
                    <li{!! $filter == 'publish' ? ' class="active"' : '' !!}><a href="{!! url('/admin/posts/' . $post_type . '?filter=publish') !!}">Published <span>({!! $filter_count['publish'] !!})</span></a></li>
                    <li{!! $filter == 'draft' ? ' class="active"' : '' !!}><a href="{!! url('/admin/posts/' . $post_type . '?filter=draft') !!}">Drafts <span>({!! $filter_count['draft'] !!})</span></a></li>
                    <li{!! $filter == 'trash' ? ' class="active"' : '' !!}><a href="{!! url('/admin/posts/' . $post_type . '?filter=trash') !!}">Trash <span>({!! $filter_count['trash'] !!})</span></a></li>
                </ul>
                <div class="clearfix"></div>
            </div>
            <div class="table_top_actions">
                <div class="table_top_actions_left">
                    <div class="table_actions">
                        <select class="form-control bulk_action">
                            <option value="-1">Bulk Actions</option>
                            <option value="delete">Move To Trash</option>
                        </select>
                        <button class="btn btn-default submit_bulk_action">Apply</button>
                    </div>
                </div>
                <div class="table_top_actions_right">
                    <img class="search_loading" src="{!! asset('contents/images/defaults/spinner.gif') !!}" alt="Search Loading">
                    <div class="table_search">
                        <input type="text" class="form-control table_search_text" placeholder="Keyword...">
                        <span class="clear_search"><i class="glyphicon glyphicon-remove"></i></span>
                        <button type="button" class="btn btn-default table_search_submit">Search</button>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="table-responsive">
                @php
                    $columns = config('posts.' . $post_type . '.columns');
                @endphp
                <table class="table">
                    <thead>
                        <tr>
                            <th class="table_checkbox"><input type="checkbox" class="flat check_all_records"></th>
                            @foreach($columns as $column)
                            <th>{!! __('columns.' . $column) !!}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($data) > 0)
                        @foreach($data as $value)
                        <tr>
                            <td><input type="checkbox" value="{!! $value->post_id !!}" class="flat check_item" name="table_records"></td>
                            @foreach($columns as $column)
                            @include('admin.posts.columns.' . $column)
                            @endforeach
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="{!! count($columns) + 1 !!}">Items not found.</td>
                        </tr>
                        @endif
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="table_checkbox"><input type="checkbox" class="flat check_all_records"></th>
                            @foreach($columns as $column)
                            <th>{!! __('columns.' . $column) !!}</th>
                            @endforeach
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="table_bottom_actions">
                <div class="table_bottom_actions_left">
                    <div class="table_actions">
                        <select class="form-control bulk_action">
                            <option value="-1">Bulk Actions</option>
                            <option value="delete">Move To Trash</option>
                        </select>
                        <button type="button" class="btn btn-default submit_bulk_action">Apply</button>
                    </div>
                </div>
                <div class="table_bottom_actions_right">
                    <div class="table_items">{!! 'Hiển thị ' . $data->count() . ' trên ' . $data->total() !!}</div>
                </div>
                <div class="table_paginate">
                    {!! $data->links() !!}
                </div>
                <div class="clearfix"></div>
            </div>
        </div>
    </div>
</div>
@stop
@push('js')
<script type="text/javascript">
    table_search($('.table_search_submit'), '{!! url('/admin/posts/' . $post_type . '/search') !!}');
</script>
@endpush
