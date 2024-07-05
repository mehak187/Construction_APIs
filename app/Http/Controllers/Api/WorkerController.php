<?php

namespace App\Http\Controllers\Api;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Hash;
use App\Mail\PasswordResetMail;
use App\Models\PasswordReset;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Leave;
use App\Models\attendance;
use App\Models\SiteManagement;
use App\Models\Salary;
use Illuminate\Support\Facades\DB;
use Validator;
use Mail;
use Illuminate\Support\Str;

class WorkerController extends Controller
{
    use ApiResponseTrait;
    public function test(){ 
        $userId=auth()->user()->id;
        echo $userId;die();
    }
    //  ---------------------------------
    //      Site Worker &  Superviser
    //  ---------------------------------
    public function checkin(Request $request){ 
        try {
            $validatedData = Validator::make($request->all(), [
                'location' => 'required',
                'checkinPhoto' => 'required',
                'checkin' => 'required',
                'project_name' => 'required',
                'superviserid' => 'required',
            ]);
            if ($validatedData->fails()) {
                return response()->json(['error' => $validatedData->errors()], 400);
            }
            $user = Auth::user();
            if ($user->role != 'siteWorker' && $user->role != 'supervisor') {
                return response()->json(['error' => 'Only site workers and supervisers can post attendence.'], 403);
            }
            $userId=auth()->user()->id;
            $checkinPhoto = $request->file('checkinPhoto');
               // ------for live---------
            $liveURL = "http://constructionapp.wantar-system.com/uploads/";
            $checkinPhoto_name = $liveURL . time() . "_" . $checkinPhoto->getClientOriginalName();
            $destinationpath = public_path('uploads/');
            $checkinPhoto->move($destinationpath,$checkinPhoto_name);

            $input = $request->all();
            $input['uid'] = $userId;
            $input['status'] = "incomplete";
            $input['checkinPhoto'] = $checkinPhoto_name;
            $input['checkout'] = "";
            $input['checkoutPhoto'] = "";
            if(auth()->user()->role=="supervisor"){
                $input['superviserid'] = 0;
            }
            $user = attendance::create($input);
            return response()->json(['msg' => 'You are checked in successfully.'], 201);
        } 
            catch (\Exception $e) {
                return $this->sendError('Error.', $e->getMessage());    
            }
    }
    public function checkout(Request $request){
        $user = Auth::user();
        if ($user->role != 'siteWorker' && $user->role != 'supervisor') {
            return response()->json(['error' => 'Only site workers and supervisers can access this.'], 403);
        }
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'checkout' => 'required',
            'checkoutPhoto' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }
        $id = $request->id;
        $login_id = auth()->user()->id;;
        $leave = attendance::where('uid', $login_id)->where('id', $id)->first();
        if (!$leave) {
            return response()->json(['error' => 'Attendance record not found'], 404);
        }
        $checkoutPhoto = $request->file('checkoutPhoto');

        $liveURL = "http://constructionapp.wantar-system.com/uploads/";
        $checkoutPhoto_name = $liveURL . time() . "_" . $checkoutPhoto->getClientOriginalName();
        $destinationPath = public_path('uploads/');
        $checkoutPhoto->move($destinationPath, $checkoutPhoto_name);
    
        $leave->checkout = $request->checkout;
        $leave->overtime = $request->overtime;
        $leave->checkoutPhoto = $checkoutPhoto_name;
        $leave->status = "complete";
        $leave->save();
    
        return response()->json(['message' => 'You are checked out successfully']);
    }
    public function myAttendance(){
        try {
            $user = Auth::user();
            if ($user->role != 'siteWorker' && $user->role != 'supervisor') {
                return response()->json(['error' => 'Only site workers and supervisers can post and view their attendence.'], 403);
            }
            $user_id=auth()->user()->id;
            $att = attendance::where('uid',$user_id)->orderBy('id', 'desc')->get();
            $success = 'Attendance';
            return $this->sendJsonResponse($success, $att);
        } catch (\Exception $e) {
            return $this->sendError('Error.', $e->getMessage());    
        }
    }
    public function myprojects() {
        try {
            $user_id = auth()->user()->id;
            if (auth()->user()->role == "supervisor") {
                $projects = SiteManagement::leftJoin('nickyClockinSystem_users as supervisor', 'nickyClockinSystem_site_management.supervisor', '=', 'supervisor.id')
                    ->select(
                        'supervisor.name as supervisor_name',
                        'supervisor.staff_id',
                        'nickyClockinSystem_site_management.*'
                    )->where('supervisor', $user_id)->orderBy('nickyClockinSystem_site_management.id', 'desc')->get();
            } 
            elseif (auth()->user()->role == "siteWorker") {
                $projects = SiteManagement::all()->filter(function ($project) use ($user_id) {
                    $employee_ids = unserialize($project->employees);
                    return is_array($employee_ids) && in_array($user_id, $employee_ids);
                })->values(); // Reset array keys
            }
            else {
                $projects = collect(); // Return an empty collection if no role matches
            }
           
            $projects->transform(function ($project) {
                $employee_names = [];
                $employee_ids = [];
    
                if (!empty($project->employees)) {
                    $employee_ids = unserialize($project->employees);
    
                    if ($employee_ids !== false && is_array($employee_ids)) {
                        $employees = DB::table('nickyClockinSystem_users')->whereIn('id', $employee_ids)->pluck('name')->toArray();
                        $employee_names = $employees;
                    }
                }
    
                $project->employee_names = $employee_names; // Add employee names to the project
                unset($project->employees);
                
                // Determine the base URL based on the project's role
                if ($project->role == 1) {
                    $baseUrl = env('SUBAPP_URL');
                } else {
                    $baseUrl = config('app.url'); 
                }
    
                if (!empty($project->Images)) {
                    $images = json_decode($project->Images, true);
                    $project->Images = collect($images)->map(function ($image) use ($baseUrl) {
                        $image_path = trim($image, '[]"'); // Remove square brackets and quotes
                        return $baseUrl . '/' . $image_path;
                    })->toArray();
                } else {
                    $project->Images = [];
                }
    
                error_log('Image URLs for project ' . $project->id . ': ' . json_encode($project->Images));
                return $project;
            });
            
            $success = 'Projects';
            return $this->sendJsonResponse($success, $projects);
        } catch (\Exception $e) {
            return $this->sendError('Error.', $e->getMessage());
        }
    }
    public function todayAttendance(Request $request){
        try {
            $user = Auth::user();
            if ($user->role != 'siteWorker' && $user->role != 'supervisor') {
                return response()->json(['error' => 'Only site workers and supervisers can access this.'], 403);
            }
            $userId = auth()->user()->id;
            $date = $request->input('date');
            $attendanceRecords = Attendance::whereDate('checkin', $date)->where('uid', $userId)->first();
            
            return response()->json(['success' => true, 'data' => $attendanceRecords], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error.', 'message' => $e->getMessage()], 500);    
        }
    }
    public function mySalary(){
        try {
            $user = Auth::user();
            if ($user->role != 'siteWorker' && $user->role != 'supervisor') {
                return response()->json(['error' => 'Only site workers and supervisers can access this.'], 403);
            }
            $user_id=auth()->user()->id;
            $salary = Salary::where('userId',$user_id)->orderBy('id', 'desc')->get();
            $success = 'Salary';
            return $this->sendJsonResponse($success, $salary);
        } catch (\Exception $e) {
            return $this->sendError('Error.', $e->getMessage());    
        }
    }
    //  ---------------------------------
    //              Office worker
    //  ---------------------------------
    public function leave(Request $request){ 
        try {
            $user = Auth::user();
            if ($user->role != 'officeWorker') {
                return response()->json(['error' => 'Only office worker can apply for leave'], 403);
            }
            $validatedData = Validator::make($request->all(), [
                'start_date' => 'required',
                'end_date' => 'required',
                'description' => 'required|string',
                'timeAdded' => 'required',
                'attachment' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048',
                'leaveType' => 'required|string',
            ]);
            if ($validatedData->fails()) {
                return response()->json(['error' => $validatedData->errors()], 400);
            }
            $user = Auth::user();
            if ($user->role != 'officeWorker') {
                return response()->json(['error' => 'Only office workers can apply for leave.'], 403);
            }
            $userId = auth()->user()->id;
            $photo = $request->file('attachment');
            
            // Store the attachment
            $photo_name = time() . "_" . $photo->getClientOriginalName();
            $destinationPath = public_path('uploads/');
            $photo->move($destinationPath, $photo_name);

            // Prepare the input data
            $input = $request->all();
            $input['userId'] = $userId;
            $input['status'] = "pending";
            $input['attachment'] = $photo_name;

            // Create the leave request
            $leave = Leave::create($input);

            return response()->json(['msg' => 'Leave request sent successfully.'], 201);
        } 
        catch (\Exception $e) {
            return response()->json(['error' => 'Error.', 'message' => $e->getMessage()], 500);
        }
    }
    public function myLeaves(){
        try {
            $user = Auth::user();
            if ($user->role != 'officeWorker') {
                return response()->json(['error' => 'Only office worker can view their leaves'], 403);
            }
            $user_id=auth()->user()->id;
            $leaves = Leave::where('userId',$user_id)->orderBy('id', 'desc')->get();
            $success = 'Leaves';
            return $this->sendJsonResponse($success, $leaves);
        } catch (\Exception $e) {
            return $this->sendError('Error.', $e->getMessage());    
        }
    }
    public function updateLeave(Request $request){
        $user = Auth::user();
        if ($user->role != 'officeWorker') {
            return response()->json(['error' => 'Only office worker can access this'], 403);
        }
        $validatedData = Validator::make($request->all(), [
            'id' => 'required',
        ]);
        if ($validatedData->fails()) {
            return response()->json(['error' => $validatedData->errors()], 400);
        }
        $data = $request->all();
        $leave=Leave::find($request->id);
        $leave->update($data);
        return response()->json(['message' => 'Leave updated successfully']);
    }
    //  ---------------------------------
    //      Admin &  Office worker
    //  ---------------------------------
    
    public function registerWorker(Request $request) {
        \DB::beginTransaction();
        try{
            $user = Auth::user();
            if ($user->role != 'officeWorker' && $user->role != 'admin') {
                return response()->json(['error' => 'Only office workers and admin can access this.'], 403);
            }
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'phone' => 'required',
                'password' => 'required',
            ]);
    
            if($validator->fails()){
                return $this->sendAuthError('Validation Error.', $validator->errors());       
            }
    
            $input = $request->all();
            $input['password'] = bcrypt($input['password']);
            $input['staff_id'] = Str::upper(Str::random(10));
            $user = User::create($input);
            
            $data['token'] =  $user->createToken('MyApp')->plainTextToken;
            \DB::commit();
            return $this->sendResponse('User register successfully.',$data);
            
        } catch (\Exception $e) {
            \DB::rollback();
            return $this->sendAuthError('Error.', $e->getMessage());    
        }

    }
    public function updateWorker(Request $request) {
        \DB::beginTransaction();
        try{
            $user = Auth::user();
            if ($user->role != 'officeWorker' && $user->role != 'admin') {
                return response()->json(['error' => 'Only office workers and admin can access this.'], 403);
            }
            $validatedData = Validator::make($request->all(), [
                'id' => 'required',
            ]);

            if ($validatedData->fails()) {
                return response()->json(['error' => $validatedData->errors()], 400);
            }
            $id = $request->id;
            $user = User::findOrFail($id);
            $input = $request->all();
            $input['password'] = bcrypt($input['password']);
            $user->update($input);
            $data['token'] =  $user->createToken('MyApp')->plainTextToken;
            \DB::commit();
            return $this->sendResponse('User updated successfully.',$data);
        } catch (\Exception $e) {
            \DB::rollback();
            return $this->sendAuthError('Error.', $e->getMessage());    
        }
    }
    public function saveProjects(Request $request) {
        try {
            $validatedData = Validator::make($request->all(), [
                'Title' => 'required',
                'Location' => 'required',
                'supervisor' => 'required',
                'employees' => 'required',
                'Images' => 'sometimes|required|array',
                'lat' => 'required',
                'lng' => 'required',
            ]);

            if ($validatedData->fails()) {
                return response()->json(['error' => $validatedData->errors()], 400);
            }
            $user = Auth::user();
            if ($user->role != 'officeWorker' && $user->role != 'admin') {
                return response()->json(['error' => 'Only office workers and admin can access this.'], 403);
            }

            $input = $request->all();
            $input['role'] = 1;
            $imageUrls = [];
            if ($request->hasFile('Images')) {
                foreach ($request->file('Images') as $image) {
                    $liveURL = "uploads/";
                    $image_name = time() . "_" . $image->getClientOriginalName();
                    $destinationPath = public_path('uploads/');
                    $image->move($destinationPath, $image_name);
                    $imageUrls[] = $liveURL . $image_name;
                }
            }
            error_log('Image URLs: ' . json_encode($imageUrls));
            $input['Images'] = json_encode($imageUrls);
            error_log('JSON Encoded Images: ' . $input['Images']);
            $input['employees'] = $this->serializeEmployees($request->input('employees'));
            SiteManagement::create($input);
            return response()->json(['msg' => 'Project Saved successfully.'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error.', 'message' => $e->getMessage()], 500);    
        }
    }
    private function serializeEmployees($employees) {
        $employeeArray = explode(',', $employees); // Convert comma-separated string to array if necessary
        $serialized = 'a:' . count($employeeArray) . ':{';
        foreach ($employeeArray as $index => $employee) {
            $serialized .= 'i:' . $index . ';s:' . strlen($employee) . ':"' . $employee . '";';
        }
        $serialized .= '}';
        return $serialized;
    }
    public function updateProject(Request $request) {
        $id = $request->id;
        try {
            $validatedData = Validator::make($request->all(), [
                'Title' => 'sometimes|required',
                'Location' => 'sometimes|required',
                'Description' => 'sometimes|required',
                'supervisor' => 'sometimes|required',
                'employees' => 'sometimes|required',
                'Images' => 'sometimes|required|array',
                'lat' => 'sometimes|required',
                'lng' => 'sometimes|required',
            ]);
    
            if ($validatedData->fails()) {
                return response()->json(['error' => $validatedData->errors()], 400);
            }
    
            $user = Auth::user();
            if ($user->role != 'officeWorker' && $user->role != 'admin') {
                return response()->json(['error' => 'Only office workers and admin can access this.'], 403);
            }
    
            $project = SiteManagement::findOrFail($id);
    
            $input = $request->all();
    
            // Process Images
            $imageUrls = $project->Images ? json_decode($project->Images, true) : [];
            if ($request->hasFile('Images')) {
                foreach ($request->file('Images') as $image) {
                    $liveURL = "uploads/";
                    $image_name = time() . "_" . $image->getClientOriginalName();
                    $destinationPath = public_path('uploads/');
                    $image->move($destinationPath, $image_name);
                    $imageUrls[] = $liveURL . $image_name;
                }
            }
            error_log('Image URLs: ' . json_encode($imageUrls));
            $input['Images'] = json_encode($imageUrls);
            error_log('JSON Encoded Images: ' . $input['Images']);
            if ($request->has('employees')) {
                $input['employees'] = $this->serializeEmployeesUp($request->input('employees'));
            }
            $project->update($input);
            return response()->json(['msg' => 'Project updated successfully.'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error.', 'message' => $e->getMessage()], 500);    
        }
    }
    private function serializeEmployeesUp($employees) {
        $employeeArray = explode(',', $employees);
        $serialized = 'a:' . count($employeeArray) . ':{';
        foreach ($employeeArray as $index => $employee) {
            $serialized .= 'i:' . $index . ';s:' . strlen($employee) . ':"' . $employee . '";';
        }
        $serialized .= '}';
        return $serialized;
    }
    public function allProjects() {
        try {
            $user = Auth::user();
            if ($user->role != 'officeWorker' && $user->role != 'admin') {
                return response()->json(['error' => 'Only office workers and admin can access this.'], 403);
            }
            $user_id = auth()->user()->id;
            $projects = SiteManagement::leftJoin('nickyClockinSystem_users as supervisor', 'nickyClockinSystem_site_management.supervisor', '=', 'supervisor.id')
                ->select(
                    'supervisor.name as supervisor_name',
                    'supervisor.staff_id',
                    'nickyClockinSystem_site_management.*'
                )->orderBy('nickyClockinSystem_site_management.id', 'desc')->get();

            $projects->transform(function ($project) {
                $employee_names = [];
                $employee_ids = [];

                if (!empty($project->employees)) {
                    $employee_ids = unserialize($project->employees);

                    if ($employee_ids !== false && is_array($employee_ids)) {
                        $employees = DB::table('nickyClockinSystem_users')->whereIn('id', $employee_ids)->pluck('name')->toArray();
                        $employee_names = $employees;
                    }
                }
                $project->employee_names = $employee_names; // Add employee names to the project
                unset($project->employees);

                // Determine the base URL based on the project's role
                if ($project->role == 1) {
                    $baseUrl = env('SUBAPP_URL');
                } else {
                    $baseUrl = config('app.url');
                }

                if (!empty($project->Images)) {
                    $images = json_decode($project->Images, true);
                    $project->Images = collect($images)->map(function ($image) use ($baseUrl) {
                        $image_path = trim($image, '[]"'); // Remove square brackets and quotes
                        return $baseUrl . '/' . $image_path;
                    })->toArray();
                } else {
                    $project->Images = [];
                }

                error_log('Image URLs for project ' . $project->id . ': ' . json_encode($project->Images));
                return $project;
            });

            $success = 'Projects';
            return $this->sendJsonResponse($success, $projects);
        } catch (\Exception $e) {
            return $this->sendError('Error.', $e->getMessage());
        }
    }
    public function addsalary(Request $request){ 
        try {
            $user = Auth::user();
            if ($user->role != 'officeWorker' && $user->role != 'admin') {
                return response()->json(['error' => 'Only office workers and admin can access this.'], 403);
            }
            $validatedData = Validator::make($request->all(), [
                'start_date' => 'required',
                'end_date' => 'required',
                'userId' => 'required',
                'pay' => 'required',
            ]);
            if ($validatedData->fails()) {
                return response()->json(['error' => $validatedData->errors()], 400);
            }
            $input = $request->all();
            $user = Salary::create($input);
            return response()->json(['msg' => 'Salary added successfully.'], 201);
        } 
            catch (\Exception $e) {
                return $this->sendError('Error.', $e->getMessage());    
            }
    }
    public function allSalary(){
        try {
            $user = Auth::user();
            if ($user->role != 'officeWorker' && $user->role != 'admin') {
                return response()->json(['error' => 'Only office workers and admin can access this.'], 403);
            }
            $salary = Salary::all();
            $success = 'Salary';
            return $this->sendJsonResponse($success, $salary);
        } catch (\Exception $e) {
            return $this->sendError('Error.', $e->getMessage());    
        }
    }
    public function allAttendance(){
        try {
            $user = Auth::user();
            if ($user->role != 'officeWorker' && $user->role != 'admin') {
                return response()->json(['error' => 'Only office workers and admin can access this.'], 403);
            }
            $data = attendance::leftJoin('nickyClockinSystem_users', 'attendances.uid', '=', 'nickyClockinSystem_users.id')
            ->select(
                'nickyClockinSystem_users.name as uname',
                'nickyClockinSystem_users.role as role',
                'nickyClockinSystem_users.lastname',
                'nickyClockinSystem_users.staff_id',
                'attendances.*',
            )
            ->orderBy('attendances.id', 'desc')
            ->get();

            $success = 'Attendance';
            return $this->sendJsonResponse($success, $data);
        } catch (\Exception $e) {
            return $this->sendError('Error.', $e->getMessage());    
        }
    }
    public function empProfile(){
        try {
            $user = Auth::user();
            if ($user->role != 'officeWorker' && $user->role != 'admin') {
                return response()->json(['error' => 'Only office workers and admin can access this.'], 403);
            }
            $profile = User::where('role','supervisor') ->orWhere('role', 'siteWorker')
            ->orderBy('id', 'desc')->get();
            $success = 'Profile';
            return $this->sendJsonResponse($success, $profile);
        } catch (\Exception $e) {
            return $this->sendError('Error.', $e->getMessage());    
        }
    }
}