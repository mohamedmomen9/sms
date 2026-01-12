<?php

namespace Modules\Campus\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CampusController extends Controller
{
    public function index()
    {
        return view('campus::index');
    }

    public function create()
    {
        return view('campus::create');
    }

    public function store(Request $request) {}

    public function show($id)
    {
        return view('campus::show');
    }

    public function edit($id)
    {
        return view('campus::edit');
    }

    public function update(Request $request, $id) {}

    public function destroy($id) {}
}
