<?php

namespace Modules\Curriculum\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CurriculumController extends Controller
{
    public function index()
    {
        return view('curriculum::index');
    }

    public function create()
    {
        return view('curriculum::create');
    }

    public function store(Request $request) {}

    public function show($id)
    {
        return view('curriculum::show');
    }

    public function edit($id)
    {
        return view('curriculum::edit');
    }

    public function update(Request $request, $id) {}

    public function destroy($id) {}
}
