<?php

namespace Modules\Subject\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        return view('subject::index');
    }

    public function create()
    {
        return view('subject::create');
    }

    public function store(Request $request) {}

    public function show($id)
    {
        return view('subject::show');
    }

    public function edit($id)
    {
        return view('subject::edit');
    }

    public function update(Request $request, $id) {}

    public function destroy($id) {}
}
