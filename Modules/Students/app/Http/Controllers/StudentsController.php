<?php

namespace Modules\Students\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentsController extends Controller
{
    public function index()
    {
        return view('students::index');
    }

    public function create()
    {
        return view('students::create');
    }

    public function store(Request $request) {}

    public function show($id)
    {
        return view('students::show');
    }

    public function edit($id)
    {
        return view('students::edit');
    }

    public function update(Request $request, $id) {}

    public function destroy($id) {}
}
