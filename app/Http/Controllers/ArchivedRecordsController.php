<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\DbHelperController;
use App\Models\Student;

class ArchivedRecordsController extends Controller
{
    public function index(DbHelperController $db)
    {
        $students = $db->getArchives();
        return view('ArchivedRecords.index', ['students' => $students]);
    }

    public function getCredentials(DbHelperController $db)
    {
        $students = $db->getUnarchivedRecords();
        return view('ArchivedRecords.unarchived_credential',  ['students' => $students]);
    }

    public function viewRecord(DbHelperController $db, $studentID)
    {
        $fromIndexPage = Student::select('archive_status'
        )->where('student_id', $studentID)->firstOrFail();

        if (substr($fromIndexPage, -2, 1) == 0) {
            $student = $db->getStudentInfo($studentID);
            return view('ArchivedRecords.view_record', [
                'student' => $student['studentInfo'],
                'credentials' => $student['credentials'],
                'picturePath' =>  $student['picturePath'],
            ]);
        }

        $archivedStudent = $db->getArchivedStudentInfo($studentID);
        return view('ArchivedRecords.view_archived_record', [
            'student' => $archivedStudent['studentInfo'],
            'credentials' => $archivedStudent['credentials'],
            'picturePath' =>  $archivedStudent['picturePath'],
        ]);
    }

    public function deleteRecord(DbHelperController $db, $studID)
    {
        $db->deleteStudent($studID, true);
        return redirect('/archived_records')->with('msgCred', 'Record Successfully Removed');
    }

    public function updateRecord($id, Request $request, DbHelperController $db)
    {
        $db->updateStudent($request, $id);
        return redirect('/archived_records/view_record/' . $id)->with('msg', 'Record Successfully Updated');
    }

    public function archiveSingleRecord(DbHelperController $db, $id)
    {
        $db->singleArchive($id);
        return redirect('/archived_records')->with('msg', 'Record Successfully Archived');
    }

    public function addSingleRec(CredentialController $credController, Request $request){
        $credController->saveCredential($request, $request->keyName, $request->fileName);

        return redirect('/archived_records/view_record/'.$request->student_id)->header('Cache-Control',
        'no-store, no-cache, must-revalidate')->with('msgCred', 'Credential Successfully Added');
    }

    public function updateCredential(CredentialController $credController, Request $request, $studID, $docID)
    {
        $credController->updateCredential($request, $studID, $docID);
        return redirect('/archived_records/view_record/' . $studID)->header(
            'Cache-Control',
            'no-store, no-cache, must-revalidate'
        )->with('msgCred', 'Credential Successfully Updated');
    }

    public function deleteCredential(CredentialController $credController, $studID, $docID)
    {
        $credController->deleteCredential($docID);

        return redirect('/archived_records/view_record/' . $studID)->with('msgCred', 'Credential Successfully Removed');
    }
}