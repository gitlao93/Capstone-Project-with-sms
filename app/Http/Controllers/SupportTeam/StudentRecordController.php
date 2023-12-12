<?php

namespace App\Http\Controllers\SupportTeam;

use App\Helpers\Qs;
use App\Helpers\Mk;
use App\Helpers\SmsHern;
use App\Http\Requests\Student\StudentRecordCreate;
use App\Http\Requests\Student\StudentRecordUpdate;
use App\Repositories\LocationRepo;
use App\Repositories\MyClassRepo;
use App\Repositories\StudentRepo;
use App\Repositories\UserRepo;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\EnrollmentVerification;
use App\User;

class StudentRecordController extends Controller
{
    protected $loc, $my_class, $user, $student;

   public function __construct(LocationRepo $loc, MyClassRepo $my_class, UserRepo $user, StudentRepo $student)
   {
       $this->middleware('teamSA', ['only' => ['edit','update', 'reset_pass', 'create', 'graduated'] ]);
       $this->middleware('super_admin', ['only' => ['destroy',] ]);

        $this->loc = $loc;
        $this->my_class = $my_class;
        $this->user = $user;
        $this->student = $student;
   }

    public function reset_pass($st_id)
    {
        $st_id = Qs::decodeHash($st_id);
        $data['password'] = Hash::make('student');
        $this->user->update($st_id, $data);
        return back()->with('flash_success', __('msg.p_reset'));
    }

    public function send_verification_code()
    {
        $mobile_number = request()->get('mobile_number');

        $code = strtoupper(Str::random(10));

        EnrollmentVerification::where(['mobile_number' => $mobile_number])->delete();
        EnrollmentVerification::create(['mobile_number' => $mobile_number, 'code' => $code]);

        $message = 'Your enrollment verification is ' . $code;
        $to = $mobile_number; // UNCOMMENT THIS LINE AFTER FREE SUBSCRIPTION HAS BEEN UPGRADE ON SMSHERN
        SmsHern::sendSms($message, $to);
    }

    public function create()
    {
        $data['my_classes'] = $this->my_class->all();
        $data['parents'] = $this->user->getUserByType('parent');
        $data['dorms'] = $this->student->getAllDorms();
        $data['states'] = $this->loc->getStates();
        $data['nationals'] = $this->loc->getAllNationals();
        return view('pages.support_team.students.add', $data);
    }

    public function create_public()
    {
        $data['my_classes'] = $this->my_class->all();
        $data['parents'] = $this->user->getUserByType('parent');
        $data['dorms'] = $this->student->getAllDorms();
        $data['states'] = $this->loc->getStates();
        $data['nationals'] = $this->loc->getAllNationals();
        return view('pages.support_team.students.add_public', $data);
    }

    public function store_public(StudentRecordCreate $req)
    {
        $verification_code = request()->get('verification_code');
        $mobile_number = request()->get('phone');
        $enrollment_verification = EnrollmentVerification::where(['mobile_number' => $mobile_number])->first();
        if (!$enrollment_verification || ($enrollment_verification && $enrollment_verification->code != $verification_code)) {
            return response()->json([
                "message" => "The given data was invalid.",
                "errors" => [
                    "verification_code" => ["Invalid sms verification code."]
                ]], 422);
        }

       $data =  $req->only(Qs::getUserRecord());
       $sr =  $req->only(Qs::getStudentData());

        $ct = $this->my_class->findTypeByClass($req->my_class_id)->code;
       /* $ct = ($ct == 'J') ? 'JSS' : $ct;
        $ct = ($ct == 'S') ? 'SS' : $ct;*/

        $data['user_type'] = 'student';
        $data['name'] = ucwords(request()->get('first_name')) . " " . ucwords(request()->get('middle_name')) . " " . ucwords(request()->get('last_name'));
        $data['first_name'] = request()->get('first_name');
        $data['middle_name'] = request()->get('middle_name');
        $data['last_name'] = request()->get('last_name');
        $data['code'] = $enrollment_verification->code;
        $data['password'] = Hash::make('student');
        $data['photo'] = Qs::getDefaultUserImage();
        $adm_no = $req->adm_no;
        // $data['username'] = strtoupper(explode(' ', $req->name)[0].$sr['year_admitted'].($adm_no ?: mt_rand(1000, 99999)));
        $data['username'] = $adm_no;

        // if($req->hasFile('birth_certificate')) {
        //     $photo = $req->file('birth_certificate');
        //     $f = Qs::getFileMetaData($photo);
        //     $f['name'] = 'birth_certificate.' . $f['ext'];
        //     $f['path'] = $photo->storeAs(Qs::getUploadPath('student').$data['code'], $f['name']);
        //     $data['birth_certificate'] = asset('storage/' . $f['path']);
        // }
        $data['birth_certificate'] = request()->get('birth_certificate');

        if($req->hasFile('report_card')) {
            $photo = $req->file('report_card');
            $f = Qs::getFileMetaData($photo);
            $f['name'] = 'report_card.' . $f['ext'];
            $f['path'] = $photo->storeAs(Qs::getUploadPath('student').$data['code'], $f['name']);
            $data['report_card'] = asset('storage/' . $f['path']);
        }

        $user = $this->user->create($data); // Create User

        $sr['adm_no'] = $adm_no;
        $sr['user_id'] = $user->id;
        $sr['session'] = Qs::getSetting('current_session');

        $student = $this->student->createRecord($sr); // Create Student
        $enrollment_verification->delete();

        // todo add parents (father, mother, guardian)

        if ($student) {
            $message = 'Hello ' . $data['name'] . ', you are now enrolled. Your username: ' . $data['username'] . ', password: student';
            $to = $user->phone; //UNCOMMENT THIS LINE AFTER FREE SUBSCRIPTION HAS BEEN UPGRADE ON SMSHERN
            SmsHern::sendSms($message, $to);
        }

        return Qs::jsonStoreOk();
    }

    public function store(StudentRecordCreate $req)
    {
       $data =  $req->only(Qs::getUserRecord());
       $sr =  $req->only(Qs::getStudentData());

        $ct = $this->my_class->findTypeByClass($req->my_class_id)->code;
       /* $ct = ($ct == 'J') ? 'JSS' : $ct;
        $ct = ($ct == 'S') ? 'SS' : $ct;*/

        $data['user_type'] = 'student';
        $data['name'] = ucwords($req->name);
        $data['code'] = strtoupper(Str::random(10));
        $data['password'] = Hash::make('student');
        $data['photo'] = Qs::getDefaultUserImage();
        $adm_no = $req->adm_no;
        // $data['username'] = strtoupper(explode(' ', $req->name)[0].$sr['year_admitted'].($adm_no ?: mt_rand(1000, 99999)));
        $data['username'] = $adm_no;

        if($req->hasFile('birth_certificate')) {
            $photo = $req->file('birth_certificate');
            $f = Qs::getFileMetaData($photo);
            $f['name'] = 'birth_certificate.' . $f['ext'];
            $f['path'] = $photo->storeAs(Qs::getUploadPath('student').$data['code'], $f['name']);
            $data['birth_certificate'] = asset('storage/' . $f['path']);
        }

        if($req->hasFile('report_card')) {
            $photo = $req->file('report_card');
            $f = Qs::getFileMetaData($photo);
            $f['name'] = 'report_card.' . $f['ext'];
            $f['path'] = $photo->storeAs(Qs::getUploadPath('student').$data['code'], $f['name']);
            $data['report_card'] = asset('storage/' . $f['path']);
        }

        $user = $this->user->create($data); // Create User

        $sr['adm_no'] = $adm_no;
        $sr['user_id'] = $user->id;
        $sr['session'] = Qs::getSetting('current_session');

        $student = $this->student->createRecord($sr); // Create Student

        return Qs::jsonStoreOk();
    }

    public function listByClass($class_id)
    {
        $data['my_class'] = $mc = $this->my_class->getMC(['id' => $class_id])->first();
        $data['students'] = $this->student->findStudentsByClass($class_id);
        $data['sections'] = $this->my_class->getClassSections($class_id);

        return is_null($mc) ? Qs::goWithDanger() : view('pages.support_team.students.list', $data);
    }

    public function graduated()
    {
        $data['my_classes'] = $this->my_class->all();
        $data['students'] = $this->student->allGradStudents();

        return view('pages.support_team.students.graduated', $data);
    }

    public function not_graduated($sr_id)
    {
        $d['grad'] = 0;
        $d['grad_date'] = NULL;
        $d['session'] = Qs::getSetting('current_session');
        $this->student->updateRecord($sr_id, $d);

        return back()->with('flash_success', __('msg.update_ok'));
    }

    public function show($sr_id)
    {
        $sr_id = Qs::decodeHash($sr_id);
        if(!$sr_id){return Qs::goWithDanger();}

        $data['sr'] = $this->student->getRecord(['id' => $sr_id])->first();

        /* Prevent Other Students/Parents from viewing Profile of others */
        if(Auth::user()->id != $data['sr']->user_id && !Qs::userIsTeamSAT() && !Qs::userIsMyChild($data['sr']->user_id, Auth::user()->id)){
            return redirect(route('dashboard'))->with('pop_error', __('msg.denied'));
        }

        return view('pages.support_team.students.show', $data);
    }

    public function edit($sr_id)
    {
        $sr_id = Qs::decodeHash($sr_id);
        if(!$sr_id){return Qs::goWithDanger();}

        $data['sr'] = $this->student->getRecord(['id' => $sr_id])->first();
        $data['my_classes'] = $this->my_class->all();
        $data['parents'] = $this->user->getUserByType('parent');
        $data['dorms'] = $this->student->getAllDorms();
        $data['states'] = $this->loc->getStates();
        $data['nationals'] = $this->loc->getAllNationals();
        return view('pages.support_team.students.edit', $data);
    }

    public function update(StudentRecordUpdate $req, $sr_id)
    {
        $sr_id = Qs::decodeHash($sr_id);
        if(!$sr_id){return Qs::goWithDanger();}

        $sr = $this->student->getRecord(['id' => $sr_id])->first();
        $d =  $req->only(Qs::getUserRecord());
        $d['name'] = ucwords($req->name);

        if($req->hasFile('photo')) {
            $photo = $req->file('photo');
            $f = Qs::getFileMetaData($photo);
            $f['name'] = 'photo.' . $f['ext'];
            $f['path'] = $photo->storeAs(Qs::getUploadPath('student').$sr->user->code, $f['name']);
            $d['photo'] = asset('storage/' . $f['path']);
        }

        $this->user->update($sr->user->id, $d); // Update User Details

        $srec = $req->only(Qs::getStudentData());

        $this->student->updateRecord($sr_id, $srec); // Update St Rec

        /*** If Class/Section is Changed in Same Year, Delete Marks/ExamRecord of Previous Class/Section ****/
        Mk::deleteOldRecord($sr->user->id, $srec['my_class_id']);

        return Qs::jsonUpdateOk();
    }

    public function destroy($st_id)
    {
        $st_id = Qs::decodeHash($st_id);
        if(!$st_id){return Qs::goWithDanger();}

        $sr = $this->student->getRecord(['user_id' => $st_id])->first();
        $path = Qs::getUploadPath('student').$sr->user->code;
        Storage::exists($path) ? Storage::deleteDirectory($path) : false;
        $this->user->delete($sr->user->id);

        return back()->with('flash_success', __('msg.del_ok'));
    }

}
