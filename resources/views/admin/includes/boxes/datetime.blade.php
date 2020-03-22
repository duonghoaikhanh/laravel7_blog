<style>
	.datetime_inline select{
		display: inline-block;
		font-size: 12px;
		border-color: #ddd;
	}
</style>
@php
	$datetime_year = date('Y');
	$datetime_month = date('m');
	$datetime_day = date('d');
	$datetime_hour = date('H');
	$datetime_minute = date('i');
	if(isset($datetime) && !empty($datetime)){
		$datetime_year = date('Y', strtotime($datetime));
		$datetime_month = date('m', strtotime($datetime));
		$datetime_day = date('d', strtotime($datetime));
		$datetime_hour = date('H', strtotime($datetime));
		$datetime_minute = date('i', strtotime($datetime));
	}
@endphp
<div class="datetime_inline">
	<select name="datetime[year]">
		@for($i = 2050; $i > 1900; $i--)
		<option value="{!! $i !!}"{!! $datetime_year == $i ? ' selected' : '' !!}>{!! $i !!}</option>
		@endfor
	</select>
	<select name="datetime[month]">
		@for($i = 1; $i <= 12; $i++)
		@php
			$dt_month = $i;
			if(strlen($i) == 1){
				$dt_month = '0' . $i;
			}
		@endphp
		<option value="{!! $dt_month !!}"{!! $datetime_month == $dt_month ? ' selected' : '' !!}>{!! $dt_month !!}</option>
		@endfor
	</select>
	<select name="datetime[day]">
		@for($i = 1; $i <= 31; $i++)
		@php
			$dt_day = $i;
			if(strlen($i) == 1){
				$dt_day = '0' . $i;
			}
		@endphp
		<option value="{!! $dt_day !!}"{!! $datetime_day == $dt_day ? ' selected' : '' !!}>{!! $dt_day !!}</option>
		@endfor
	</select>
	@
	<select name="datetime[hour]">
		@for($i = 0; $i <= 23; $i++)
		@php
			$dt_hour = $i;
			if(strlen($i) == 1){
				$dt_hour = '0' . $i;
			}
		@endphp
		<option value="{!! $dt_hour !!}"{!! $datetime_hour == $dt_hour ? ' selected' : '' !!}>{!! $dt_hour !!}</option>
		@endfor
	</select>
	<select name="datetime[minute]">
		@for($i = 0; $i <= 59; $i++)
		@php
			$dt_minute = $i;
			if(strlen($i) == 1){
				$dt_minute = '0' . $i;
			}
		@endphp
		<option value="{!! $dt_minute !!}"{!! $datetime_minute == $dt_minute ? ' selected' : '' !!}>{!! $dt_minute !!}</option>
		@endfor
	</select>
</div>