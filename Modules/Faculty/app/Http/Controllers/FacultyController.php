<?php

namespace Modules\Faculty\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FacultyController extends Controller
{
    public function index()
    {
        return view('faculty::index');
    }

    public function create()
    {
        return view('faculty::create');
    }

    public function store(Request $request) {}

    public function show($id)
    {
        return view('faculty::show');
    }

    public function edit($id)
    {
        return view('faculty::edit');
    }

    public function update(Request $request, $id) {}

    public function destroy($id) {}
}
