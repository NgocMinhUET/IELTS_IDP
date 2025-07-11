<?php

namespace App\Http\Controllers\CMS;

use App\Exceptions\CMS\ImportStudentException;
use App\Http\Requests\Student\ImportStudentRequest;
use App\Http\Requests\Student\StoreStudentRequest;
use App\Services\CMS\StudentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class StudentController extends CMSController
{
    private array $rootBreadcrumbs = ['Student' => null];

    public function __construct(
        public StudentService $studentService,
    ) {
        parent::__construct();

        $this->rootBreadcrumbs['Student'] = route('admin.students.index');
    }

    public function index(Request $request)
    {
        $this->breadcrumbs = array_merge($this->rootBreadcrumbs, [
            'List' => null
        ]);

        $students = $this->studentService->getPaginateStudents($request);

        return view('students.index', compact('students'));
    }


    public function create()
    {
        $this->breadcrumbs = array_merge($this->rootBreadcrumbs, [
            'Create' => null
        ]);

        return view('students.create');
    }

    public function store(StoreStudentRequest $request)
    {
        $studentPayload = $request->validated();

        $student = $this->studentService->storeStudent($studentPayload);

        return redirect()
            ->route('admin.students.detail', $student->id)
            ->with('success', 'Student created. The password is: ' . $studentPayload['password'] . ', 
            this password not show again. Please save it in a safe place.');
    }

    public function detail($id)
    {
        $this->breadcrumbs = array_merge($this->rootBreadcrumbs, [
            'Detail' => null
        ]);

        $student = $this->studentService->getStudent($id);

        return view('students.create', compact('student'));
    }

    public function update(StoreStudentRequest $request, $id)
    {
        $studentPayload = $request->validated();

        $student = $this->studentService->updateStudent($studentPayload, $id);

        $alertMessage = 'Student updated.';
        if (!empty($studentPayload['new_password'])) {
            $alertMessage .= ' The new password is: ' . $studentPayload['new_password'] . ',
                this password not show again. Please save it in a safe place.';
        }

        return redirect()
            ->route('admin.students.detail', $student->id)
            ->with('success', $alertMessage);
    }

    public function import()
    {
        $this->breadcrumbs = array_merge($this->rootBreadcrumbs, [
            'Import' => null
        ]);

        return view('students.import');
    }

    public function downloadImportTemplate(): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        return response()->download(storage_path('templates/import-template.xlsx'));
    }


    public function executeImport(ImportStudentRequest $request)
    {
        try {
            $file = $request->file('file');

            $insertNumber = $this->studentService->executeImportStudents($file);

            return redirect()->back()->with(
                'success', "Imported " . $insertNumber . " students successfully.",
            );
        } catch (BadRequestHttpException $exception) {
            return redirect()->back()
                ->withErrors(['file' => $exception->getMessage()]);
        } catch (ImportStudentException $exception) {
            return redirect()->back()
                ->with('import_errors', $exception->getErrors());
        } catch (\Throwable $exception) {
            Log::error($exception->getMessage());
            abort(500);
        }
    }
}