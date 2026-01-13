<?php

namespace Modules\Teachers\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TeachersController extends Controller
{
    public function index()
    {
        return view('teachers::index');
    }

    public function create()
    {
        return view('teachers::create');
    }

    public function store(Request $request) {}

    public function show($id)
    {
        return view('teachers::show');
    }

    public function edit($id)
    {
        return view('teachers::edit');
    }

    public function update(Request $request, $id) {}

    public function destroy($id) {}
}
