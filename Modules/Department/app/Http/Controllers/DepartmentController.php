<?php

namespace Modules\Department\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        return view('department::index');
    }

    public function create()
    {
        return view('department::create');
    }

    public function store(Request $request) {}

    public function show($id)
    {
        return view('department::show');
    }

    public function edit($id)
    {
        return view('department::edit');
    }

    public function update(Request $request, $id) {}

    public function destroy($id) {}
}
