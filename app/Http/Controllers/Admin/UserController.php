<?php

namespace App\Http\Controllers\Admin;


use App\Models\User;
use App\Helpers\Helper;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::user();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('first_name', function ($row) {
                    return $row->first_name .' '.  $row->last_name;
                })
                ->addColumn('email', function ($row) {
                    return $row->email ?? '--';
                })
                ->editColumn('status', function ($row) {
                    return $row->status == true ?
                        "<span class='badge badge-success' style='font-size: 15px'>Active</span>" :
                        "<span class='badge badge-danger' style='font-size: 15px'>Inactive</span>";
                })

                ->addColumn('action', function ($row) {
                    // Encrypt the ID and make it URL-safe

                    $edit_url = route('admin.users.edit', $row->id);
                    $delete_url = route('admin.users.destroy', $row->id);

                    $action_btn = "<a href='{$edit_url}' class='action-btns1 mr-2'>
                                        <i class='fe fe-edit text-primary' data-toggle='tooltip' data-placement='top' title='Edit'></i>
                                    </a>
                                    <a href='javascript:void(0);' onclick='deleteUserConfirmation(\"{$row->id}\")' class='action-btns1 mr-2' data-toggle='tooltip' data-placement='top' title='Delete'>
                                        <i class='fe fe-trash-2 text-danger'></i>
                                    </a>";
                    return "<div class='fs-20 d-flex align-items-center'>" . $action_btn . "</div>";
                })
            ->rawColumns(['status', 'action','user_type'])
            ->make(true);
        }

        return view('admin.users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
       return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            // Decrypt the user ID
            $user = User::findOrFail($id);

            return view('admin.users.create', compact('user')); // Use the same create view for editing
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            // Handle decryption error
            return redirect()->route('admin.users.index')->with('error', 'Invalid or tampered user ID.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $user = User::findOrFail($id);
            // Validate the request data
            $request->validate([
                'status' => 'required|boolean',
            ], [
                'status.required' => 'Please Select User Status.',
                'status.boolean' => 'The status field must be true or false.',
            ]);

            $user->status = filter_var($request->input('status'), FILTER_VALIDATE_BOOLEAN);

            $imagePath = Helper::updateImage($request,$user,'profile');
            if($imagePath){
                $user->profile = $imagePath;
            }

            $user->save();

            return redirect()->route('admin.users.index')->with('success', 'User updated successfully!');
        } catch (\Exception $e) {
            return redirect()->route('admin.users.index')->with('error', $e->getMessage());

        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            // Find and delete the User
            $User = User::findOrFail($id);
            Helper::deleteImage($User,'profile');
            $User->delete();

            return response()->json(['status' => true, 'message' => 'User deleted successfully!']);

        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Invalid or tampered User ID.'], 400);
        }
    }

}
