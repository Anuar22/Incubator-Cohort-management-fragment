// Redirect root or dashboard to the tracker
Route::get('/', function () {
    return redirect()->route('tracker.index');
});