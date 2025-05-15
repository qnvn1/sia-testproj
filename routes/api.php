<?php
// routes/api.php
Route::get('/test-key', function () {
    return response()->json(['message' => 'API working']);
});
