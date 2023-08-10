<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->ajax())
        {
            $query = User::query();
            
            return DataTables::of($query)
            ->addColumn('action', function($item) {
                return '
                    <a href="'. route('dashboard.user.edit', $item->id).'" class="bg-gray-500 text-white text-center rounded-md px-2 py-1 mr-3">
                        Edit
                    </a>
                    <form class="inline-block" action="'. route('dashboard.user.destroy', $item->id).'" method="POST">
                        '. method_field('delete') . csrf_field() .'
                        <button type="submit" class="bg-red-500 text-white text-center rounded-md px-2 py-1 m-2">
                            Delete
                        </button>
                    </form>
                ';
            })
            ->editColumn('roles', function ($item) {
                if ($item->roles === 'ADMIN') {
                    return '<button style="pointer-events:none" class="sm:rounded-lg px-4 py-2 bg-yellow-200 text-yellow-800 font-semibold my-10">'. $item->roles .'</button>';
                } elseif ($item->roles === 'USER') {
                    return '<button style="pointer-events:none" class="sm:rounded-lg px-4 py-2 bg-green-200 text-green-800 font-semibold my-10">'. $item->roles .'</button>';
                }
            })
            ->rawColumns(['action','roles'])
            ->make();
        }
        return view('pages.dashboard.user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('pages.dashboard.user.edit', [
            'item' => $user
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
    {
        $data = $request->all();
        $user->update($data);

        return redirect()->route('dashboard.user.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('dashboard.user.index');
    }
}
