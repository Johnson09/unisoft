@extends('layouts.app')

@section('content')
<script type="text/javascript">
    $(document).ready(function() {

        var set = {{ $set }};

        if (set == null) {
            location.href = "/";
        }

    });
</script>
<div class="content-fluid">
	<img src="public/images/logo/logo.png" id="logo">
</div>
<style type="text/css">
	#logo {
		width: 70%;
		display: block;
		margin: auto;
	}
</style>
@endsection
