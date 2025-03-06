<?php

namespace App\Http\Controllers;

use App\Http\Resources\CaseFileResource;
use App\Http\Resources\CaseResource;
use App\Http\Resources\CaseResourceVanduski;
use App\Http\Resources\CaseTypeResource;
use App\Http\Resources\InstitutionResource;
use App\Models\_Case;
use App\Models\CaseFile;
use App\Models\CaseType;
use App\Models\Folder;
use App\Models\Institution;
use Aws\S3\S3Client;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Aws\Exception\AwsException;

class CaseController extends Controller
{
    public $caseType;
    public $case;
    public $caseFile;

    public function __construct()
    {
        $this->caseType = new CaseType();
        $this->case = new _Case();
        $this->caseFile = new CaseFile();
    }

    public function view()
    {
        Session::put('create-case-or-trial', 'case');
        return view('case.index');
    }

    public function viewVansudski()
    {
        Session::put('create-case-or-trial', 'case');
        return view('case.vansudski');
    }

    public function getCaseTypes()
    {
        $caseTypes = $this->caseType->getCaseTypes();
        return response(CaseTypeResource::collection($caseTypes));;
    }

    public function getCases(Request $request)
    {
        $numberData = 50;
        $case_type_id = $request->caseType;
        $search = (object)$request->search;
        $page = $request->page;
        $cases = _Case::where('case_type_id', $case_type_id);
        if ($search->institution !== '' && $search->institution !== null) {
            $cases = $cases->where('institution_id', $search->institution['id']);
        }
        if ($search->institution_number !== '' && $search->institution_number !== null) {
            $cases = $cases->where('number_institution', 'LIKE', '%' . $search->institution_number. '%');
        }
        if ($search->number_office !== '' && $search->number_office !== null) {
            $cases = $cases->where('number_office',  'LIKE', '%' .$search->number_office. '%');
        }
        if ($search->person_1 !== '' && $search->person_1 !== null) {
            $cases = $cases->where('prosecutor', 'LIKE', '%' . $search->person_1['prosecutor'] . '%');
        }
        if ($search->person_2 !== '' && $search->person_2 !== null) {
            $cases = $cases->where('defendants', 'LIKE', '%' . $search->person_2['defendants'] . '%');
        }
        if ($search->archive !== '' && $search->archive !== null) {
            $cases = $cases->where('archive',  'LIKE', '%' .$search->archive. '%');
        }
        if ($search->mark !== '' && $search->mark !== null) {
            $cases = $cases->where('mark',  'LIKE', '%' .$search->mark. '%');
        }
        $count = ceil($cases->count() / $numberData);
        $cases = $cases->orderBy(DB::raw('CAST(number_office AS SIGNED)'), 'ASC')->skip($page * $numberData)->take($numberData)->get();

        return response(['data' => CaseResource::collection($cases), 'count' => $count]);
    }




    public function getCasesVansudski(Request $request)
    {
        $numberData = 150;
        $case_type_id = $request->caseType;
        $search = (object)$request->search;
        $page = $request->page;


        $cases = _Case::where('case_type_id', 5);

        if ($search->status !== '' && $search->status !== null) {
            $cases = $cases->where('status', 'LIKE', '%' . $search->status. '%');
        }
        if ($search->number_office !== '' && $search->number_office !== null) {
            $cases = $cases->where('vansudski_number',  'LIKE', '%' .$search->number_office. '%');
        }
        if ($search->person_1 !== '' && $search->person_1 !== null) {
            $cases = $cases->where('prosecutor', 'LIKE', '%' . $search->person_1['prosecutor'] . '%');
        }


        if ($search->mark !== '' && $search->mark !== null) {
            $cases = $cases->where('mark',  'LIKE', '%' .$search->mark. '%');
        }

        if ($search->datumSlikanjaOdOriginal !== '' && $search->datumSlikanjaOdOriginal !== null) {
            $date = Carbon::parse($search->datumSlikanjaOdOriginal)->format("Y-m-d");
            $cases = $cases->where('fail_day', '>=', $date);
        }
        if ($search->datumSlikanjaDoOriginal !== '' && $search->datumSlikanjaDoOriginal !== null) {
            $date = Carbon::parse($search->datumSlikanjaDoOriginal)->format("Y-m-d");
            $cases = $cases->where('fail_day', '<=', $date);
        }

        if ($search->damageNumber !== '' && $search->damageNumber !== null) {
            $cases = $cases->where('damage_number',  'LIKE', '%' .$search->damageNumber. '%');
        }
        if ($search->at !== '' && $search->at !== null) {
            $cases = $cases->where('at',  'LIKE', '%' .$search->at. '%');
        }
        if ($search->lawsuit !== '' && $search->lawsuit !== null) {
            $cases = $cases->where('lawsuit',  'LIKE', '%' .$search->lawsuit. '%');
        }
        if ($search->description !== '' && $search->description !== null) {
            $cases = $cases->where('note',  'LIKE', '%' .$search->description. '%');
        }

        $maxCount = $cases->count();
        $count = ceil($cases->count() / $numberData);
        $cases = $cases->orderBy(DB::raw('CAST(id AS SIGNED)'), 'DESC')->skip($page * $numberData)->take($numberData)->get();

        return response(['data' => CaseResourceVanduski::collection($cases), 'count' => $count , 'maxCount' => $maxCount , 'numberData' => $numberData ]);
    }


    public function getInstitutions(Request $request)
    {
        $institution = Institution::orderBy('name')->whereIn('institution_type_id', $request->institutionsType)->where("activation" , 1)->get();
        return response(InstitutionResource::collection($institution));
    }

    public function getPersons(Request $request)
    {
        $case = _Case::where('case_type_id', $request->case_type_id);
//        $person_1_list = (array)   array_unique( array_filter($case->pluck('prosecutor')->toArray()) );
//        $person_2_list = (array) array_unique(array_filter($case->pluck('defendants')->toArray()));

        $person_1_list = (array)$case->select('prosecutor')->where("prosecutor", "!=", null)->distinct('prosecutor')->get()->toArray();
        $person_2_list = (array)$case->select('defendants')->where("defendants", "!=", null)->distinct('defendants')->get()->toArray();
        return response(['person_1_list' => $person_1_list, 'person_2_list' => $person_2_list]);
    }

    public function createCase(Request $request)
    {
        $createCase = $this->case->createCase($request);

        if ($createCase->status() !== 200) {
            return response()->json('Došlo je do greške', 400);
        }
        return response(new CaseResource($createCase->original), 201);
    }

    public function filesUpload(Request $request)
    {
        $dataUpload = [];
        if ($request->hasFile('files')) {
            $filesData = []; // Akumulira podatke o fajlovima za kasniju obradu

            // Kreiraj S3Client
            $s3Client = new S3Client([
                'version' => 'latest',
                'region' => 'eu-north-1',
                'credentials' => [
                    'key'    => 'AKIAQHAV7WN2HJBNTSUK',
                    'secret' => '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',
                ],
            ]);

            foreach ($request->file('files') as $file) {
                // Generiši ime fajla
                $nameOriginalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $name = $nameOriginalName . '-G-' . date('Y-m-d') . '-' . rand(10, 100000) . '.' . $file->getClientOriginalExtension();

                // Inicijalizuj multipart upload
                $result = $s3Client->createMultipartUpload([
                    'Bucket' => 'janko-bucket',
                    'Key' => $name,
                ]);

                $uploadId = $result['UploadId'];
                $parts = []; // Ovo treba biti niz
                $partNumber = 1;

                // Učitaj svaki deo
                $filePath = $file->getRealPath();
                $handle = fopen($filePath, 'rb');
                $partSize = 5 * 1024 * 1024; // 5 MB po delu

                while (!feof($handle)) {
                    // Pročitaj deo fajla
                    $data = fread($handle, $partSize);

                    // Proveri da li je deo prazna
                    if (strlen($data) === 0) {
                        break; // Ako nema više podataka, izađi iz petlje
                    }

                    // Učitaj deo na S3
                    $result = $s3Client->uploadPart([
                        'Bucket' => 'janko-bucket',
                        'Key' => $name,
                        'UploadId' => $uploadId,
                        'PartNumber' => $partNumber,
                        'Body' => $data,
                    ]);

                    // Dodaj informacije o delu
                    $parts[] = [  // Ovo treba biti niz
                        'ETag' => $result['ETag'],
                        'PartNumber' => $partNumber,
                    ];
                    $partNumber++;
                }
                fclose($handle);

                // Završite multipart upload
                $s3Client->completeMultipartUpload([
                    'Bucket' => 'janko-bucket',
                    'Key' => $name,
                    'UploadId' => $uploadId,
                    'MultipartUpload' => [
                        'Parts' => $parts, // Ovo treba biti niz
                    ],
                ]);

                // Dodaj podatke u privremeni niz
                $filesData[] = [
                    'name' => $name,
                    'path' => $nameOriginalName,
                    'upload_date' => date('Y-m-d'),
                ];
            }

            // Sačuvaj sve fajlove u bazi odjednom
            foreach ($filesData as $fileData) {
                $caseFile = new CaseFile();
                $caseFile->name = $fileData['name'];
                $caseFile->path = $fileData['path'];
                $caseFile->upload_date = $fileData['upload_date'];
                $caseFile->save();

                // Dodaj ID u niz
                $dataUpload[] = $caseFile->id;
            }
        }

        return response(['ids' => $dataUpload]);
    }






    public function filesUploadVansudski( Request $request)
    {
        $dataUpload = [];
        if ($request->hasFile('files')) {
            $filesData = []; // Akumulira podatke o fajlovima za kasniju obradu

            // Kreiraj S3Client
            $s3Client = new S3Client([
                'version' => 'latest',
                'region' => 'eu-north-1',
                'credentials' => [
                    'key'    => 'AKIAQHAV7WN2HJBNTSUK',
                    'secret' => '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',
                ],
            ]);

            foreach ($request->file('files') as $file) {
                // Generiši ime fajla
                $nameOriginalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $name = $nameOriginalName . '-G-' . date('Y-m-d') . '-' . rand(10, 100000) . '.' . $file->getClientOriginalExtension();

                // Inicijalizuj multipart upload
                $result = $s3Client->createMultipartUpload([
                    'Bucket' => 'janko-bucket',
                    'Key' => $name,
                ]);

                $uploadId = $result['UploadId'];
                $parts = []; // Ovo treba biti niz
                $partNumber = 1;

                // Učitaj svaki deo
                $filePath = $file->getRealPath();
                $handle = fopen($filePath, 'rb');
                $partSize = 5 * 1024 * 1024; // 5 MB po delu

                while (!feof($handle)) {
                    // Pročitaj deo fajla
                    $data = fread($handle, $partSize);

                    // Proveri da li je deo prazna
                    if (strlen($data) === 0) {
                        break; // Ako nema više podataka, izađi iz petlje
                    }

                    // Učitaj deo na S3
                    $result = $s3Client->uploadPart([
                        'Bucket' => 'janko-bucket',
                        'Key' => $name,
                        'UploadId' => $uploadId,
                        'PartNumber' => $partNumber,
                        'Body' => $data,
                    ]);

                    // Dodaj informacije o delu
                    $parts[] = [  // Ovo treba biti niz
                        'ETag' => $result['ETag'],
                        'PartNumber' => $partNumber,
                    ];
                    $partNumber++;
                }
                fclose($handle);

                // Završite multipart upload
                $s3Client->completeMultipartUpload([
                    'Bucket' => 'janko-bucket',
                    'Key' => $name,
                    'UploadId' => $uploadId,
                    'MultipartUpload' => [
                        'Parts' => $parts, // Ovo treba biti niz
                    ],
                ]);

                // Dodaj podatke u privremeni niz
                $filesData[] = [
                    'name' => $name,
                    'path' => $nameOriginalName,
                    'upload_date' => date('Y-m-d'),
                ];
            }

            // Sačuvaj sve fajlove u bazi odjednom
            foreach ($filesData as $fileData) {
                $caseFile = new CaseFile();
                $caseFile->name = $fileData['name'];
                $caseFile->path = $fileData['path'];
                $caseFile->case_id = $request->caseID;
                $caseFile->upload_date = $fileData['upload_date'];
                $caseFile->folder_id = $request->folderID;
                $caseFile->save();

                // Dodaj ID u niz
                $dataUpload[] = $caseFile->id;
            }
        }

        return response(['ids' => $dataUpload]);
    }





    public function updateFiles(Request $request)
    {

        if (count($request->dataUploadedID) > 0) {
            foreach ($request->dataUploadedID as $data) {
                $file = CaseFile::find($data);

                if ($file) {
                    $file->case_id = $request->case_id;
                    $file->save();
                }
            }
        }
        return response('{}', 204);
    }

    public function getCaseById($id)
    {
        if (is_numeric($id)) {
            $caseAndFiles = $this->case->getCaseById($id);
            return $caseAndFiles;
        } else {
            return [];
        }
    }

    public function removeFile($id)
    {
        if (is_numeric($id)) {
            $caseFileFromID = CaseFile::find($id);

            if (!$caseFileFromID) {
                return response('{}', 404);
            }

            $removeFile = $this->caseFile->removeFile($caseFileFromID);

            if ($removeFile->status() == 204) {
                $caseFileFromID->delete();
            }


            return response('{}', 204);

        }

        return response('Došlo je do greške', 500);
    }




    public function updateFilePath($id, Request $request)
    {

        if (is_numeric($id)) {
            $caseFile = CaseFile::find($id);

            if (!$caseFile) {

                return response('{}', 404);
            }
            CaseFile::where("id" ,$id)->update([
                "path" => $request->path
            ]);



            return response('{}', 204);

        }

        return response('Došlo je do greške', 500);
    }




    public function updateCase($id, Request $request)
    {

        if (is_numeric($id)) {
            $case = _Case::find($id);

            if (!$case) {

                return response('{}', 404);
            }


            $updateCase = $this->case->editCase($case, $request);

            return response('{}', 204);

        }

        return response('Došlo je do greške', 500);
    }




    public function updateCaseVansudski($id, Request $request)
    {

        if (is_numeric($id)) {
            $case = _Case::find($id);

            if (!$case) {

                return response('{}', 404);
            }


            $case = _Case::where("id", $id)->update([$request->field => $request->value]);
            $case = _Case::find($id);
            return response(["data" => $case] , 200);
        }

        return response('Došlo je do greške', 500);
    }

    public function deleteCase($id)
    {
        if (is_numeric($id)) {
            $case = _Case::find($id);

            if (!$case) {
                return response('{}', 404);
            }

            foreach ($case->case_files as $file) {
                $removeFile = $this->caseFile->removeFile($file);
                if ($removeFile->status() == 204) {
                    $file->delete();
                }

            }

            $updateCase = $this->case->deleteCase($case);

            return response('{}', 204);

        }

        return response('Došlo je do greške', 500);
    }

    public function checkExistingName(Request $request)
    {
        $name = _Case::where("prosecutor" , "LIKE" , "%$request->prosecutor%")->where("case_type_id" , $request->case_type_id)->get()->pluck("prosecutor");

        return response()->json(["allNames" => $name]);
    }

    public function createCaseVansudski()
    {
        $case = _Case::create([
            "case_type_id" => 5,
        ]);

        return response(["data" => new CaseResourceVanduski($case)] , 200);
    }

    public function getCaseFilesClient($id, $date)
    {
        $case = _Case::where("id", $id)->where("created_at" , $date)->first();
        if (!$case) {
            $case = null;
            return redirect("/");
        }
        $caseFiles = CaseFile::where("case_id", $id)->get();
        $case = new CaseResourceVanduski($case);
        $caseFiles = CaseFileResource::collection($caseFiles);
        $folders = Folder::select("id", "name")->orderBy("id")->get();

        return view("client.client_files", compact("case" , "caseFiles" , "folders"));
    }

    public function getCaseFilesClientFromFolder($folder, $id)
    {
        $folderdb = Folder::find($folder);
        if(!$folderdb){
            return response()->json([]);
        }
        $caseFiles = CaseFile::where("case_id", $id);
        if(strtoupper($folderdb->name) !== "SVI FAJLOVI"){
            $caseFiles = $caseFiles->where("folder_id" ,$folder);
        }
        $caseFiles = $caseFiles->get();
        $caseFiles = CaseFileResource::collection($caseFiles);

        return response()->json($caseFiles);
    }

}
