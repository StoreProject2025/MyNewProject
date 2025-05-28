@extends('layouts.master')
@section('title', 'Edit User')
@section('content')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            $("#clean_permissions").click(function () {
                $('input[name="permissions[]"]').prop('checked', false);
            });
            $("#clean_roles").click(function () {
                $('input[name="roles[]"]').prop('checked', false);
            });
        });
    </script>


<div class="col col-2">
 @can('admin_users')                          
 @csrf
@method('PUT')
    <button type="submit" class="btn btn-warning form-control">update credit</button>
@endcan
</div>
