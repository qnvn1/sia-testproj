<!DOCTYPE html>
<html>
<head><title>Weekly Meal Plan</title></head>
<body>
    <h1>Meal Plan (Monday to Friday)</h1>

    @foreach($meals as $index => $meal)
        <div style="margin-bottom: 20px;">
            <strong>{{ $days[$index] }}</strong><br>
            Meal: {{ $meal['title'] }}<br>
            Calories: {{ $meal['calories'] ?? 'N/A' }}<br>
            <img src="{{ $meal['image'] }}" width="150">
        </div>
    @endforeach
</body>
</html>